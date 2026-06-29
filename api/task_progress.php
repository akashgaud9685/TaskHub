<?php
header('Content-Type: application/json');
session_start();
if (!isset($_SESSION['user_id'])) {
    http_response_code(403);
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}

require_once __DIR__ . '/../config/database.php';

$method = $_SERVER['REQUEST_METHOD'];
$userId = (int) $_SESSION['user_id'];
$isAdmin = $_SESSION['role'] === 'admin';
$storageDir = __DIR__ . '/../storage/task_progress';

if (!is_dir($storageDir)) mkdir($storageDir, 0755, true);

try {
    switch ($method) {
        case 'POST':
            $taskId = (int) ($_POST['task_id'] ?? 0);
            $message = trim($_POST['message'] ?? '');

            if ($taskId === 0 || $message === '') {
                http_response_code(400);
                echo json_encode(['success' => false, 'message' => 'Task ID and message required']);
                exit;
            }

            $progress = [];
            $file = "$storageDir/$taskId.json";
            if (file_exists($file)) {
                $progress = json_decode(file_get_contents($file), true) ?: [];
            }

            $progress[] = [
                'user_id' => $userId,
                'user_name' => $_SESSION['name'] ?? 'Unknown',
                'user_role' => $isAdmin ? 'admin' : 'staff',
                'message' => $message,
                'created_at' => date('Y-m-d H:i:s'),
            ];

            file_put_contents($file, json_encode($progress), LOCK_EX);

            @touch(__DIR__ . '/../storage/task_update.txt');

            echo json_encode(['success' => true, 'message' => 'Progress updated']);
            break;

        case 'GET':
            $taskId = (int) ($_GET['task_id'] ?? 0);
            $taskIds = $_GET['task_ids'] ?? '';

            if ($taskId > 0) {
                $file = "$storageDir/$taskId.json";
                $data = file_exists($file) ? (json_decode(file_get_contents($file), true) ?: []) : [];
                echo json_encode(['success' => true, 'data' => $data]);
            } elseif ($taskIds !== '') {
                $ids = array_map('intval', explode(',', $taskIds));
                $result = [];
                foreach ($ids as $id) {
                    $file = "$storageDir/$id.json";
                    if (file_exists($file)) {
                        $result[$id] = json_decode(file_get_contents($file), true) ?: [];
                    }
                }
                echo json_encode(['success' => true, 'data' => $result]);
            } else {
                echo json_encode(['success' => true, 'data' => []]);
            }
            break;

        default:
            http_response_code(405);
            echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    }
} catch (\Throwable $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Server error']);
}
