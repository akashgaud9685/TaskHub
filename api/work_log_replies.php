<?php
require_once __DIR__ . '/../config/database.php';

header('Content-Type: application/json');

session_start();
if (!isset($_SESSION['user_id'])) {
    http_response_code(403);
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}

$db = getBizDB();
$method = $_SERVER['REQUEST_METHOD'];
$userId = (int) $_SESSION['user_id'];
$isAdmin = $_SESSION['role'] === 'admin';

try {
    switch ($method) {
        case 'GET':
            $workLogId = (int) ($_GET['work_log_id'] ?? 0);
            if ($workLogId === 0) {
                http_response_code(400);
                echo json_encode(['success' => false, 'message' => 'Work log ID is required']);
                exit;
            }

            $sql = 'SELECT wr.id, wr.work_log_id, wr.user_id, wr.user_role, u.name AS user_name, wr.message, wr.created_at FROM work_log_replies wr JOIN users u ON wr.user_id = u.id WHERE wr.work_log_id = :wid';
            $params = [':wid' => $workLogId];

            if (!empty($_SESSION['business_id'])) {
                $sql .= ' AND u.business_id = :bid';
                $params[':bid'] = $_SESSION['business_id'];
            }

            $since = $_GET['since'] ?? '';
            if ($since !== '') {
                $sql .= ' AND wr.created_at > :since';
                $params[':since'] = $since;
            }

            $sql .= ' ORDER BY wr.created_at ASC';

            $stmt = $db->prepare($sql);
            $stmt->execute($params);
            echo json_encode(['success' => true, 'data' => $stmt->fetchAll()]);
            break;

        case 'POST':
            $workLogId = (int) ($_POST['work_log_id'] ?? 0);
            $message = trim($_POST['message'] ?? '');

            if ($workLogId === 0 || $message === '') {
                http_response_code(400);
                echo json_encode(['success' => false, 'message' => 'Work log ID and message are required']);
                exit;
            }

            $check = $db->prepare('SELECT wl.id, u.business_id FROM work_logs wl JOIN users u ON u.id = wl.staff_id WHERE wl.id = :wid');
            $check->execute([':wid' => $workLogId]);
            $workLog = $check->fetch();

            if (!$workLog) {
                http_response_code(404);
                echo json_encode(['success' => false, 'message' => 'Work log not found']);
                exit;
            }

            if (!empty($_SESSION['business_id']) && !empty($workLog['business_id']) && (int)$_SESSION['business_id'] !== (int)$workLog['business_id']) {
                http_response_code(403);
                echo json_encode(['success' => false, 'message' => 'Forbidden']);
                exit;
            }

            $stmt = $db->prepare('INSERT INTO work_log_replies (work_log_id, user_id, user_role, user_name, message) VALUES (:wid, :uid, :role, :name, :msg)');
            $stmt->execute([
                ':wid'  => $workLogId,
                ':uid'  => $userId,
                ':role' => $isAdmin ? 'admin' : 'staff',
                ':name' => $_SESSION['name'] ?? 'Unknown',
                ':msg'  => $message,
            ]);

            $nf = __DIR__ . '/../storage/activity_update.txt';
            $nd = dirname($nf);
            if (!is_dir($nd)) mkdir($nd, 0755, true);
            @touch($nf);
            @touch(__DIR__ . '/../storage/task_update.txt');

            echo json_encode(['success' => true, 'message' => 'Reply added']);
            break;

        default:
            http_response_code(405);
            echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    }
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Database error']);
}
