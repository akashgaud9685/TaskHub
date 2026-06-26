<?php
require_once __DIR__ . '/../config/database.php';
session_start();
$isAuth = isset($_SESSION['user_id']);
session_write_close();

if (!$isAuth) {
    http_response_code(403);
    exit;
}

$maxTime = 25;
$startTime = time();

header('Content-Type: text/event-stream');
header('Cache-Control: no-cache');
header('X-Accel-Buffering: no');

if (ob_get_level()) ob_end_clean();
header('Content-Encoding: none');

$trackFile = __DIR__ . '/../storage/task_update.txt';
$lastTime = file_exists($trackFile) ? filemtime($trackFile) : 0;

echo "retry: 3000\n\n";
flush();

while (true) {
    if ((time() - $startTime) > $maxTime) break;
    clearstatcache();
    $currentTime = file_exists($trackFile) ? filemtime($trackFile) : 0;
    if ($currentTime > $lastTime) {
        echo "event: update\ndata: reload\n\n";
        flush();
        $lastTime = $currentTime;
    } else {
        echo ": heartbeat\n\n";
        flush();
    }
    sleep(3);
    if (connection_aborted()) break;
}
