<?php
/**
 * Staff Management API
 * 
 * GET    /api/staff.php              - List all staff
 * POST   /api/staff.php              - Create new staff
 * PUT    /api/staff.php              - Update staff
 * PATCH  /api/staff.php?id=X         - Toggle active/inactive
 * DELETE /api/staff.php?id=X         - Delete staff + their tasks
 */

require_once __DIR__ . '/../config/database.php';

header('Content-Type: application/json');

session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    http_response_code(403);
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}

$db = getBizDB();
$method = $_SERVER['REQUEST_METHOD'];

try {
    switch ($method) {
        // ── LIST ALL STAFF ──────────────────────────────
        case 'GET':
            if (!empty($_SESSION['business_id'])) {
                $stmt = $db->prepare("SELECT id, name, username, role, department, status, photo, created_at FROM users WHERE role = 'staff' AND business_id = :bid ORDER BY created_at DESC");
                $stmt->execute([':bid' => $_SESSION['business_id']]);
            } else {
                $stmt = $db->query("SELECT id, name, username, role, department, status, photo, created_at FROM users WHERE role = 'staff' ORDER BY created_at DESC");
            }
            echo json_encode(['success' => true, 'data' => $stmt->fetchAll()]);
            break;

        // ── CREATE STAFF ────────────────────────────────
        case 'POST':
            $name       = trim($_POST['name'] ?? '');
            $username   = trim($_POST['username'] ?? '');
            $password   = $_POST['password'] ?? '';
            $department = trim($_POST['department'] ?? '');
            $status     = $_POST['status'] ?? 'active';

            if ($name === '' || $username === '' || $password === '') {
                http_response_code(400);
                echo json_encode(['success' => false, 'message' => 'Name, username, and password are required']);
                exit;
            }

            if (strcasecmp($department, 'Management') === 0) {
                http_response_code(400);
                echo json_encode(['success' => false, 'message' => 'Management is a reserved department and cannot be assigned to staff']);
                exit;
            }

            $bizId = !empty($_SESSION['business_id']) ? (int)$_SESSION['business_id'] : null;
            $check = $db->prepare('SELECT id FROM users WHERE username = :username AND business_id = :bid LIMIT 1');
            $check->execute([':username' => $username, ':bid' => $bizId]);
            if ($check->fetch()) {
                http_response_code(409);
                echo json_encode(['success' => false, 'message' => 'Username already exists in your business']);
                exit;
            }

            $hashed = password_hash($password, PASSWORD_BCRYPT, ['cost' => 12]);

            $bizName = null;
            if (!empty($_SESSION['business_id'])) {
                $bnStmt = $db->prepare('SELECT business_name FROM businesses WHERE id = :bid');
                $bnStmt->execute([':bid' => $_SESSION['business_id']]);
                $bizName = $bnStmt->fetchColumn();
            }

            $stmt = $db->prepare("INSERT INTO users (name, username, password, role, department, status, email, business_id, business_name) VALUES (:name, :username, :password, 'staff', :department, :status, :email, :bid, :bn) RETURNING id");
            $stmt->execute([
                ':name'       => $name,
                ':username'   => $username,
                ':password'   => $hashed,
                ':department' => $department,
                ':status'     => $status,
                ':email'      => $username . '@' . ($bizName ? preg_replace('/[^a-zA-Z0-9]/', '', strtolower($bizName)) : 'taskhub') . '.local',
                ':bid'        => $bizId,
                ':bn'         => $bizName,
            ]);

            echo json_encode(['success' => true, 'message' => 'Staff created successfully', 'id' => (int)$stmt->fetchColumn()]);
            break;

        // ── UPDATE STAFF ────────────────────────────────
        case 'PUT':
            parse_str(file_get_contents('php://input'), $data);

            $id         = (int)($data['id'] ?? 0);
            $name       = trim($data['name'] ?? '');
            $department = trim($data['department'] ?? '');
            $status     = $data['status'] ?? 'active';
            $password   = $data['password'] ?? '';

            if ($id === 0 || $name === '') {
                http_response_code(400);
                echo json_encode(['success' => false, 'message' => 'ID and name are required']);
                exit;
            }

            if (strcasecmp($department, 'Management') === 0) {
                http_response_code(400);
                echo json_encode(['success' => false, 'message' => 'Management is a reserved department and cannot be assigned to staff']);
                exit;
            }

            if ($password !== '') {
                $hashed = password_hash($password, PASSWORD_BCRYPT, ['cost' => 12]);
                $stmt = $db->prepare("UPDATE users SET name = :name, department = :department, status = :status, password = :password WHERE id = :id AND role = 'staff' AND business_id = :bid");
                $stmt->execute([
                    ':name'       => $name,
                    ':department' => $department,
                    ':status'     => $status,
                    ':password'   => $hashed,
                    ':id'         => $id,
                    ':bid'        => $_SESSION['business_id'] ?? 0,
                ]);
            } else {
                $stmt = $db->prepare("UPDATE users SET name = :name, department = :department, status = :status WHERE id = :id AND role = 'staff' AND business_id = :bid");
                $stmt->execute([
                    ':name'       => $name,
                    ':department' => $department,
                    ':status'     => $status,
                    ':id'         => $id,
                    ':bid'        => $_SESSION['business_id'] ?? 0,
                ]);
            }

            echo json_encode(['success' => true, 'message' => 'Staff updated successfully']);
            break;

        // ── TOGGLE STATUS / DELETE PHOTO ────────────────
        case 'PATCH':
            parse_str(file_get_contents('php://input'), $data);
            $id = (int)($data['id'] ?? 0);

            if ($id === 0) {
                http_response_code(400);
                echo json_encode(['success' => false, 'message' => 'ID is required']);
                exit;
            }

            if (isset($data['delete_photo'])) {
                $stmt = $db->prepare("SELECT photo FROM users WHERE id = :id AND role = 'staff'" . (!empty($_SESSION['business_id']) ? " AND business_id = :bid" : ""));
                $params = [':id' => $id];
                if (!empty($_SESSION['business_id'])) $params[':bid'] = $_SESSION['business_id'];
                $stmt->execute($params);
                $photo = $stmt->fetchColumn();

                if ($photo) {
                    $path = __DIR__ . '/../uploads/profile/' . $photo;
                    if (file_exists($path)) unlink($path);
                    $db->prepare('UPDATE users SET photo = NULL WHERE id = :id')->execute([':id' => $id]);
                    echo json_encode(['success' => true, 'message' => 'Photo deleted']);
                } else {
                    echo json_encode(['success' => true, 'message' => 'No photo to delete']);
                }
                exit;
            }

            $stmt = $db->prepare("UPDATE users SET status = CASE WHEN status = 'active' THEN 'inactive' ELSE 'active' END WHERE id = :id AND role = 'staff'" . (!empty($_SESSION['business_id']) ? " AND business_id = :bid" : ""));
            $params = [':id' => $id];
            if (!empty($_SESSION['business_id'])) $params[':bid'] = $_SESSION['business_id'];
            $stmt->execute($params);

            if ($stmt->rowCount() === 0) {
                http_response_code(404);
                echo json_encode(['success' => false, 'message' => 'Staff not found']);
                exit;
            }

            echo json_encode(['success' => true, 'message' => 'Status toggled']);
            break;

        // ── DELETE STAFF ────────────────────────────────
        case 'DELETE':
            parse_str(file_get_contents('php://input'), $data);
            $id = (int)($data['id'] ?? 0);

            if ($id === 0) {
                http_response_code(400);
                echo json_encode(['success' => false, 'message' => 'ID is required']);
                exit;
            }

            $db->beginTransaction();
            $db->prepare('DELETE FROM tasks WHERE assigned_to = :id')->execute([':id' => $id]);
            $stmt = $db->prepare("DELETE FROM users WHERE id = :id AND role = 'staff'" . (!empty($_SESSION['business_id']) ? " AND business_id = :bid" : ""));
            $params = [':id' => $id];
            if (!empty($_SESSION['business_id'])) $params[':bid'] = $_SESSION['business_id'];
            $stmt->execute($params);

            if ($stmt->rowCount() === 0) {
                $db->rollBack();
                http_response_code(404);
                echo json_encode(['success' => false, 'message' => 'Staff not found']);
                exit;
            }

            $db->commit();
            echo json_encode(['success' => true, 'message' => 'Staff and their tasks deleted']);
            break;

        default:
            http_response_code(405);
            echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    }
} catch (PDOException $e) {
    if (isset($db) && $db->inTransaction()) $db->rollBack();
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Database error']);
}
