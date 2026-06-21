<?php
require_once __DIR__ . '/config/database.php';

session_start();

if (isset($_SESSION['user_id'])) {
    try {
        $db = getBizDB();
        $stmt = $db->prepare('UPDATE users SET session_token = NULL WHERE id = :id');
        $stmt->execute([':id' => $_SESSION['user_id']]);
    } catch (\Throwable $e) {}
}

session_destroy();

$params = session_get_cookie_params();
setcookie(session_name(), '', time() - 42000,
    $params['path'], $params['domain'],
    $params['secure'], $params['httponly']
);

header('Location: index.php');
exit;
