<?php
/**
 * Tasks API
 * 
 * GET    /api/tasks.php                          - List tasks
 *        ?status=todo|in-progress|completed
 *        &assigned_to=ID
 *        &priority=low|medium|high|urgent
 *        &date_from=YYYY-MM-DD
 *        &date_to=YYYY-MM-DD
 * POST   /api/tasks.php                          - Create task (admin only)
 * PUT    /api/tasks.php                          - Update task status or notes
 * DELETE /api/tasks.php                          - Delete task (admin only)
 */

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

function notifyStaff() {
    $f = __DIR__ . '/../storage/task_update.txt';
    $d = dirname($f);
    if (!is_dir($d)) mkdir($d, 0755, true);
    @touch($f);
}

try {
    switch ($method) {
        // ── LIST TASKS ──────────────────────────────────
        case 'GET':
            $statusFilter   = $_GET['status'] ?? '';
            $assignedFilter = $_GET['assigned_to'] ?? '';
            $priorityFilter = $_GET['priority'] ?? '';
            $dateFrom       = $_GET['date_from'] ?? '';
            $dateTo         = $_GET['date_to'] ?? '';
            $taskIdFilter   = (int) ($_GET['id'] ?? 0);

            $sql = 'SELECT t.*, u.name AS assigned_name, u.department 
                    FROM tasks t 
                    JOIN users u ON t.assigned_to = u.id';

            $conditions = [];
            $params = [];

            if ($taskIdFilter > 0) {
                $conditions[] = 't.id = :tid';
                $params[':tid'] = $taskIdFilter;
            }

            if (!$isAdmin) {
                $conditions[] = 't.assigned_to = :user_id';
                $params[':user_id'] = $userId;
            } elseif (!empty($_SESSION['business_id'])) {
                $conditions[] = 'u.business_id = :bid';
                $params[':bid'] = $_SESSION['business_id'];
            }

            if ($statusFilter !== '' && in_array($statusFilter, ['todo', 'in-progress', 'completed'])) {
                $conditions[] = 't.status = :status';
                $params[':status'] = $statusFilter;
            }

            if ($assignedFilter !== '' && $isAdmin) {
                $conditions[] = 't.assigned_to = :assigned';
                $params[':assigned'] = (int) $assignedFilter;
            }

            if ($priorityFilter !== '' && in_array($priorityFilter, ['low', 'medium', 'high', 'urgent'])) {
                $conditions[] = 't.priority = :priority';
                $params[':priority'] = $priorityFilter;
            }

            if ($dateFrom !== '') {
                $conditions[] = 't.due_date >= :date_from';
                $params[':date_from'] = $dateFrom . ' 00:00:00';
            }

            if ($dateTo !== '') {
                $conditions[] = 't.due_date <= :date_to';
                $params[':date_to'] = $dateTo . ' 23:59:59';
            }

            if (!empty($conditions)) {
                $sql .= ' WHERE ' . implode(' AND ', $conditions);
            }

            $sql .= " ORDER BY 
                        CASE t.priority WHEN 'urgent' THEN 0 WHEN 'high' THEN 1 WHEN 'medium' THEN 2 ELSE 3 END, 
                        t.due_date ASC,
                        t.created_at DESC
                      LIMIT 500";

            $stmt = $db->prepare($sql);
            $stmt->execute($params);
            $tasks = $stmt->fetchAll();

            foreach ($tasks as &$task) {
                $task['status_label'] = ucfirst($task['status']);
                $task['priority_label'] = ucfirst($task['priority']);
            }

            echo json_encode(['success' => true, 'data' => $tasks]);
            break;

        // ── CREATE TASK ─────────────────────────────────
        case 'POST':
            if (!$isAdmin) {
                http_response_code(403);
                echo json_encode(['success' => false, 'message' => 'Only admins can assign tasks']);
                exit;
            }

            $title       = trim($_POST['title'] ?? '');
            $description = trim($_POST['description'] ?? '');
            $assignedTo  = (int) ($_POST['assigned_to'] ?? 0);
            $priority    = $_POST['priority'] ?? 'medium';
            $dueDate     = $_POST['due_date'] ?? '';

            if ($title === '' || $assignedTo === 0 || $dueDate === '') {
                http_response_code(400);
                echo json_encode(['success' => false, 'message' => 'Title, assigned staff, and due date are required']);
                exit;
            }

            if (!in_array($priority, ['low', 'medium', 'high', 'urgent'])) {
                $priority = 'medium';
            }

            if (!empty($_SESSION['business_id'])) {
                $check = $db->prepare("SELECT id FROM users WHERE id = :id AND role = 'staff' AND status = 'active' AND business_id = :bid");
                $check->execute([':id' => $assignedTo, ':bid' => $_SESSION['business_id']]);
            } else {
                $check = $db->prepare("SELECT id FROM users WHERE id = :id AND role = 'staff' AND status = 'active'");
                $check->execute([':id' => $assignedTo]);
            }
            if (!$check->fetch()) {
                http_response_code(400);
                echo json_encode(['success' => false, 'message' => 'Selected staff member not found or inactive']);
                exit;
            }

            $stmt = $db->prepare('INSERT INTO tasks (title, description, assigned_to, priority, due_date, business_id) VALUES (:title, :description, :assigned_to, :priority, :due_date, :bid) RETURNING id');
            $stmt->execute([
                ':title'       => $title,
                ':description' => $description,
                ':assigned_to' => $assignedTo,
                ':priority'    => $priority,
                ':due_date'    => $dueDate,
                ':bid'         => !empty($_SESSION['business_id']) ? (int)$_SESSION['business_id'] : null,
            ]);

            $newId = (int)$stmt->fetchColumn();
            notifyStaff();

            echo json_encode(['success' => true, 'message' => 'Task assigned successfully', 'id' => $newId]);
            break;

        // ── UPDATE TASK (status, notes, or admin edit) ─
        case 'PUT':
            parse_str(file_get_contents('php://input'), $data);

            $taskId    = (int) ($data['id'] ?? 0);
            $status    = $data['status'] ?? '';
            $workNotes = trim($data['work_notes'] ?? '');

            // Admin-only edit fields
            $title       = $data['title'] ?? '';
            $description = $data['description'] ?? '';
            $assignedTo  = (int) ($data['assigned_to'] ?? 0);
            $priority    = $data['priority'] ?? '';
            $dueDate     = $data['due_date'] ?? '';

            if ($taskId === 0) {
                http_response_code(400);
                echo json_encode(['success' => false, 'message' => 'Task ID is required']);
                exit;
            }

            $stmt = $db->prepare('SELECT t.id, t.assigned_to, t.status FROM tasks t JOIN users u ON t.assigned_to = u.id WHERE t.id = :id' . ($isAdmin && !empty($_SESSION['business_id']) ? ' AND u.business_id = :bid' : ''));
            $params = [':id' => $taskId];
            if ($isAdmin && !empty($_SESSION['business_id'])) $params[':bid'] = $_SESSION['business_id'];
            $stmt->execute($params);
            $task = $stmt->fetch();

            if (!$task) {
                http_response_code(404);
                echo json_encode(['success' => false, 'message' => 'Task not found']);
                exit;
            }

            if (!$isAdmin && (int)$task['assigned_to'] !== $userId) {
                http_response_code(403);
                echo json_encode(['success' => false, 'message' => 'You can only update your own tasks']);
                exit;
            }

            if (!$isAdmin && $task['status'] === 'completed') {
                http_response_code(400);
                echo json_encode(['success' => false, 'message' => 'Cannot modify a completed task']);
                exit;
            }

            $validTransitions = [
                'todo'        => ['in-progress'],
                'in-progress' => ['completed', 'todo'],
            ];

            $currentStatus = $task['status'];

            if ($status !== '') {
                if ($isAdmin) {
                    if (!in_array($status, ['todo', 'in-progress', 'completed'])) {
                        http_response_code(400);
                        echo json_encode(['success' => false, 'message' => 'Invalid status']);
                        exit;
                    }
                } else {
                    if (!isset($validTransitions[$currentStatus]) || !in_array($status, $validTransitions[$currentStatus])) {
                        http_response_code(400);
                        echo json_encode(['success' => false, 'message' => "Cannot change status from '$currentStatus' to '$status'"]);
                        exit;
                    }
                }
            }

            $updateFields = [];
            $updateParams = [':id' => $taskId];

            // Admin can edit task details (reassign, change title, etc.)
            if ($isAdmin) {
                if ($title !== '') {
                    $updateFields[] = 'title = :title';
                    $updateParams[':title'] = $title;
                }
                if ($description !== '') {
                    $updateFields[] = 'description = :description';
                    $updateParams[':description'] = $description;
                }
                if ($assignedTo > 0) {
                    $sql = "SELECT id FROM users WHERE id = :id AND role = 'staff' AND status = 'active'";
                    $params = [':id' => $assignedTo];
                    if (!empty($_SESSION['business_id'])) {
                        $sql .= ' AND business_id = :bid';
                        $params[':bid'] = $_SESSION['business_id'];
                    }
                    $check = $db->prepare($sql);
                    $check->execute($params);
                    if (!$check->fetch()) {
                        http_response_code(400);
                        echo json_encode(['success' => false, 'message' => 'Selected staff member not found or inactive']);
                        exit;
                    }
                    $updateFields[] = 'assigned_to = :assigned_to';
                    $updateParams[':assigned_to'] = $assignedTo;
                }
                if ($priority !== '' && in_array($priority, ['low', 'medium', 'high', 'urgent'])) {
                    $updateFields[] = 'priority = :priority';
                    $updateParams[':priority'] = $priority;
                }
                if ($dueDate !== '') {
                    $updateFields[] = 'due_date = :due_date';
                    $updateParams[':due_date'] = $dueDate;
                }
            }

            if ($status !== '' && $status !== $currentStatus) {
                $updateFields[] = 'status = :status';
                $updateParams[':status'] = $status;
            }

            if ($workNotes !== '') {
                $updateFields[] = 'work_notes = :work_notes';
                $updateParams[':work_notes'] = $workNotes;
            }

            if (!empty($updateFields)) {
                $sql = 'UPDATE tasks SET ' . implode(', ', $updateFields) . ' WHERE id = :id';
                $stmt = $db->prepare($sql);
                $stmt->execute($updateParams);
            }

            notifyStaff();

            echo json_encode(['success' => true, 'message' => 'Task updated successfully']);
            break;

        // ── DELETE TASK (admin only) ───────────────────
        case 'DELETE':
            if (!$isAdmin) {
                http_response_code(403);
                echo json_encode(['success' => false, 'message' => 'Only admins can delete tasks']);
                exit;
            }

            parse_str(file_get_contents('php://input'), $data);
            $taskId = (int) ($data['id'] ?? 0);

            if ($taskId === 0) {
                http_response_code(400);
                echo json_encode(['success' => false, 'message' => 'Task ID is required']);
                exit;
            }

            if (!empty($_SESSION['business_id'])) {
                $check = $db->prepare('SELECT t.id FROM tasks t JOIN users u ON t.assigned_to = u.id WHERE t.id = :id AND u.business_id = :bid');
                $check->execute([':id' => $taskId, ':bid' => $_SESSION['business_id']]);
            } else {
                $check = $db->prepare('SELECT id FROM tasks WHERE id = :id');
                $check->execute([':id' => $taskId]);
            }
            if (!$check->fetch()) {
                http_response_code(404);
                echo json_encode(['success' => false, 'message' => 'Task not found']);
                exit;
            }

            $stmt = $db->prepare('DELETE FROM tasks WHERE id = :id');
            $stmt->execute([':id' => $taskId]);

            notifyStaff();
            echo json_encode(['success' => true, 'message' => 'Task deleted successfully']);
            break;

        default:
            http_response_code(405);
            echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    }
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Database error']);
}
