<?php

require_once __DIR__ . '/../config/database.php';

header('Content-Type: application/json');

session_start();
if (!isset($_SESSION['user_id'])) {
    http_response_code(403);
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    exit;
}

$userId = (int) $_SESSION['user_id'];

if (!isset($_FILES['photo']) || $_FILES['photo']['error'] !== UPLOAD_ERR_OK) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'No photo uploaded or upload error']);
    exit;
}

$file = $_FILES['photo'];
$ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
$allowed = ['jpg', 'jpeg', 'png', 'gif', 'webp'];

if (!in_array($ext, $allowed)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Allowed formats: jpg, jpeg, png, gif, webp']);
    exit;
}

$maxSize = 2 * 1024 * 1024;
if ($file['size'] > $maxSize) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Photo must be under 2MB']);
    exit;
}

$uploadDir = __DIR__ . '/../uploads/profile/';
if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0755, true);
}

$filename = 'user_' . $userId . '_' . time() . '.' . $ext;
$destPath = $uploadDir . $filename;

if (!move_uploaded_file($file['tmp_name'], $destPath)) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Failed to save photo']);
    exit;
}

try {
    $db = getBizDB();

    $old = $db->prepare('SELECT photo FROM users WHERE id = :id');
    $old->execute([':id' => $userId]);
    $oldPhoto = $old->fetchColumn();

    if ($oldPhoto && file_exists($uploadDir . $oldPhoto)) {
        unlink($uploadDir . $oldPhoto);
    }

    $stmt = $db->prepare('UPDATE users SET photo = :photo WHERE id = :id');
    $stmt->execute([':photo' => $filename, ':id' => $userId]);

    echo json_encode(['success' => true, 'message' => 'Photo uploaded', 'photo' => $filename]);
} catch (PDOException $e) {
    if (file_exists($destPath)) unlink($destPath);
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Database error']);
}
