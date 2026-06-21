<?php
require_once __DIR__ . '/../config/database.php';

session_start();
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['valid' => false]);
    exit;
}

try {
    $db = getBizDB();
    $stmt = $db->prepare('SELECT session_token FROM users WHERE id = :id');
    $stmt->execute([':id' => $_SESSION['user_id']]);
    $dbToken = $stmt->fetchColumn();

    $valid = $dbToken !== false && $dbToken === ($_SESSION['session_token'] ?? null);
    echo json_encode(['valid' => $valid]);
} catch (PDOException $e) {
    echo json_encode(['valid' => true]);
}
