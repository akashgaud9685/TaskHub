<?php

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
$bizId = !empty($_SESSION['business_id']) ? (int)$_SESSION['business_id'] : null;

try {
    switch ($method) {
        case 'GET':
            if ($bizId) {
                $stmt = $db->prepare("SELECT id, name, created_at FROM departments WHERE business_id = :bid ORDER BY CASE WHEN name = 'Management' THEN 0 ELSE 1 END, name ASC");
                $stmt->execute([':bid' => $bizId]);
            } else {
                $stmt = $db->query("SELECT id, name, created_at FROM departments ORDER BY CASE WHEN name = 'Management' THEN 0 ELSE 1 END, name ASC");
            }
            echo json_encode(['success' => true, 'data' => $stmt->fetchAll()]);
            break;

        case 'POST':
            $name = trim($_POST['name'] ?? '');
            if ($name === '') {
                http_response_code(400);
                echo json_encode(['success' => false, 'message' => 'Department name is required']);
                exit;
            }

            if (strcasecmp($name, 'Management') === 0) {
                http_response_code(400);
                echo json_encode(['success' => false, 'message' => 'Management is a reserved department and cannot be created']);
                exit;
            }

            $sql = 'SELECT id FROM departments WHERE name = :name';
            $params = [':name' => $name];
            if ($bizId) {
                $sql .= ' AND business_id = :bid';
                $params[':bid'] = $bizId;
            }
            $check = $db->prepare($sql);
            $check->execute($params);
            if ($check->fetch()) {
                http_response_code(409);
                echo json_encode(['success' => false, 'message' => 'Department already exists in your business']);
                exit;
            }

            $stmt = $db->prepare('INSERT INTO departments (name, business_id) VALUES (:name, :bid) RETURNING id');
            $stmt->execute([':name' => $name, ':bid' => $bizId]);
            echo json_encode(['success' => true, 'message' => 'Department created', 'id' => (int)$stmt->fetchColumn()]);
            break;

        case 'PUT':
            parse_str(file_get_contents('php://input'), $data);
            $id = (int)($data['id'] ?? 0);
            $name = trim($data['name'] ?? '');

            if ($id === 0 || $name === '') {
                http_response_code(400);
                echo json_encode(['success' => false, 'message' => 'ID and name are required']);
                exit;
            }

            $checkMgmt = $db->prepare('SELECT name FROM departments WHERE id = :id' . ($bizId ? ' AND business_id = :bid' : ''));
            $mgmtParams = [':id' => $id];
            if ($bizId) $mgmtParams[':bid'] = $bizId;
            $checkMgmt->execute($mgmtParams);
            $currentName = $checkMgmt->fetchColumn();
            if ($currentName && strcasecmp($currentName, 'Management') === 0) {
                http_response_code(400);
                echo json_encode(['success' => false, 'message' => 'Management is a reserved department and cannot be renamed']);
                exit;
            }

            if (strcasecmp($name, 'Management') === 0) {
                http_response_code(400);
                echo json_encode(['success' => false, 'message' => 'Management is a reserved department name']);
                exit;
            }

            $sql = 'UPDATE departments SET name = :name WHERE id = :id';
            $params = [':name' => $name, ':id' => $id];
            if ($bizId) {
                $sql .= ' AND business_id = :bid';
                $params[':bid'] = $bizId;
            }
            $stmt = $db->prepare($sql);
            $stmt->execute($params);

            if ($stmt->rowCount() === 0) {
                http_response_code(404);
                echo json_encode(['success' => false, 'message' => 'Department not found']);
                exit;
            }

            echo json_encode(['success' => true, 'message' => 'Department updated']);
            break;

        case 'DELETE':
            parse_str(file_get_contents('php://input'), $data);
            $id = (int)($data['id'] ?? 0);

            if ($id === 0) {
                http_response_code(400);
                echo json_encode(['success' => false, 'message' => 'ID is required']);
                exit;
            }

            $checkMgmt = $db->prepare('SELECT name FROM departments WHERE id = :id' . ($bizId ? ' AND business_id = :bid' : ''));
            $mgmtParams = [':id' => $id];
            if ($bizId) $mgmtParams[':bid'] = $bizId;
            $checkMgmt->execute($mgmtParams);
            $currentName = $checkMgmt->fetchColumn();
            if ($currentName && strcasecmp($currentName, 'Management') === 0) {
                http_response_code(400);
                echo json_encode(['success' => false, 'message' => 'Management is a reserved department and cannot be deleted']);
                exit;
            }

            $check = $db->prepare('SELECT COUNT(*) FROM users WHERE department = (SELECT name FROM departments WHERE id = :id)' . ($bizId ? ' AND business_id = :bid' : ''));
            $params = [':id' => $id];
            if ($bizId) $params[':bid'] = $bizId;
            $check->execute($params);
            if ($check->fetchColumn() > 0) {
                http_response_code(400);
                echo json_encode(['success' => false, 'message' => 'Department is assigned to staff members. Reassign them first.']);
                exit;
            }

            $sql = 'DELETE FROM departments WHERE id = :id';
            $params = [':id' => $id];
            if ($bizId) {
                $sql .= ' AND business_id = :bid';
                $params[':bid'] = $bizId;
            }
            $stmt = $db->prepare($sql);
            $stmt->execute($params);

            if ($stmt->rowCount() === 0) {
                http_response_code(404);
                echo json_encode(['success' => false, 'message' => 'Department not found']);
                exit;
            }

            echo json_encode(['success' => true, 'message' => 'Department deleted']);
            break;

        default:
            http_response_code(405);
            echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    }
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Database error']);
}
