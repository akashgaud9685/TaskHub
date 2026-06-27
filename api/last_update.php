<?php
header('Content-Type: text/plain');
session_start();
if (!isset($_SESSION['user_id'])) {
    http_response_code(403);
    exit;
}
session_write_close();
$f = __DIR__ . '/../storage/task_update.txt';
echo file_exists($f) ? filemtime($f) : '0';
