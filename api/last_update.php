<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('HTTP/1.1 403 Forbidden');
    exit;
}
$f = __DIR__ . '/../storage/task_update.txt';
echo file_exists($f) ? filemtime($f) : '0';
