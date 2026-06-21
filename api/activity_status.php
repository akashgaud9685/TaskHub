<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('HTTP/1.1 403 Forbidden');
    exit;
}
$f = __DIR__ . '/../storage/activity_update.txt';
echo file_exists($f) ? filemtime($f) : '0';
