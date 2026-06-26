<?php
require_once __DIR__ . '/../config/database.php';
header('Content-Type: application/json');

session_start();
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['valid' => false]);
    exit;
}

// Verify DB token every 30 seconds; otherwise trust session
$now = time();
$lastCheck = $_SESSION['_last_session_check'] ?? 0;
if (($now - $lastCheck) < 30) {
    echo json_encode(['valid' => true]);
    exit;
}

try {
    $db = getBizDB();
    $stmt = $db->prepare('SELECT session_token FROM users WHERE id = :id');
    $stmt->execute([':id' => $_SESSION['user_id']]);
    $dbToken = $stmt->fetchColumn();

    $valid = $dbToken !== false && $dbToken === ($_SESSION['session_token'] ?? null);
    if ($valid) $_SESSION['_last_session_check'] = $now;
    echo json_encode(['valid' => $valid]);
} catch (PDOException $e) {
    echo json_encode(['valid' => false]);
}
