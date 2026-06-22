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
            $logIdFilter = (int) ($_GET['id'] ?? 0);

            if ($isAdmin) {
                $staffFilter = (int) ($_GET['staff_id'] ?? 0);
                $dateFrom = $_GET['date_from'] ?? '';
                $dateTo = $_GET['date_to'] ?? '';

                $sql = 'SELECT w.*, u.name AS staff_name, u.department,
                        COALESCE(rc.reply_count, 0) AS reply_count
                        FROM work_logs w
                        JOIN users u ON w.staff_id = u.id
                        LEFT JOIN (
                            SELECT work_log_id, COUNT(*) AS reply_count
                            FROM work_log_replies
                            GROUP BY work_log_id
                        ) rc ON w.id = rc.work_log_id';
                $conditions = [];
                $params = [];

                if ($logIdFilter > 0) {
                    $conditions[] = 'w.id = :lid';
                    $params[':lid'] = $logIdFilter;
                }

                if (!empty($_SESSION['business_id'])) {
                    $conditions[] = 'u.business_id = :bid';
                    $params[':bid'] = $_SESSION['business_id'];
                }

                if ($staffFilter > 0) {
                    $conditions[] = 'w.staff_id = :sid';
                    $params[':sid'] = $staffFilter;
                }
                if ($dateFrom !== '') {
                    $conditions[] = 'w.log_date >= :df';
                    $params[':df'] = $dateFrom;
                }
                if ($dateTo !== '') {
                    $conditions[] = 'w.log_date <= :dt';
                    $params[':dt'] = $dateTo;
                }
                if (!empty($conditions)) {
                    $sql .= ' WHERE ' . implode(' AND ', $conditions);
                }
                $sql .= ' ORDER BY w.log_date DESC, w.created_at DESC';
                if ($logIdFilter === 0) {
                    $sql .= ' LIMIT 50';
                }
            } else {
                $dateFrom = $_GET['date_from'] ?? '';
                $dateTo = $_GET['date_to'] ?? '';
                $sql = 'SELECT w.*,
                        COALESCE(rc.reply_count, 0) AS reply_count
                        FROM work_logs w
                        LEFT JOIN (
                            SELECT work_log_id, COUNT(*) AS reply_count
                            FROM work_log_replies
                            GROUP BY work_log_id
                        ) rc ON w.id = rc.work_log_id
                        WHERE w.staff_id = :uid';
                $params = [':uid' => $userId];
                if ($logIdFilter > 0) {
                    $sql .= ' AND w.id = :lid';
                    $params[':lid'] = $logIdFilter;
                }
                if ($dateFrom !== '') {
                    $sql .= ' AND w.log_date >= :df';
                    $params[':df'] = $dateFrom;
                }
                if ($dateTo !== '') {
                    $sql .= ' AND w.log_date <= :dt';
                    $params[':dt'] = $dateTo;
                }
                $sql .= ' ORDER BY w.log_date DESC, w.created_at DESC';
                if ($logIdFilter === 0) {
                    $sql .= ' LIMIT 50';
                }
            }

            $stmt = $db->prepare($sql);
            $stmt->execute($params ?? []);
            echo json_encode(['success' => true, 'data' => $stmt->fetchAll()]);
            break;

        case 'POST':
            if ($isAdmin) {
                http_response_code(403);
                echo json_encode(['success' => false, 'message' => 'Only staff can log work']);
                exit;
            }

            $description = trim($_POST['description'] ?? '');
            $logDate = $_POST['log_date'] ?? date('Y-m-d');

            if ($description === '') {
                http_response_code(400);
                echo json_encode(['success' => false, 'message' => 'Description is required']);
                exit;
            }

            $stmt = $db->prepare('INSERT INTO work_logs (staff_id, description, log_date, business_id) VALUES (:sid, :desc, :date, :bid)');
            $stmt->execute([
                ':sid'  => $userId,
                ':desc' => $description,
                ':date' => $logDate,
                ':bid'  => !empty($_SESSION['business_id']) ? (int)$_SESSION['business_id'] : null,
            ]);

            $nf = __DIR__ . '/../storage/activity_update.txt';
            $nd = dirname($nf);
            if (!is_dir($nd)) mkdir($nd, 0755, true);
            @touch($nf);

            echo json_encode(['success' => true, 'message' => 'Work logged successfully']);
            break;

        default:
            http_response_code(405);
            echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    }
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Database error']);
}
