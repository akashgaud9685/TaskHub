<?php
header('Content-Type: text/plain');
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    http_response_code(403);
    exit;
}
session_write_close();
$f = __DIR__ . '/../storage/activity_update.txt';
echo file_exists($f) ? filemtime($f) : '0';
