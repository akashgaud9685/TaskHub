<?php
session_start();
$isAuth = isset($_SESSION['user_id']);
session_write_close();

if (!$isAuth) {
    header('HTTP/1.1 403 Forbidden');
    exit;
}

set_time_limit(0);
ini_set('output_buffering', 'off');
ini_set('zlib.output_compression', false);

if (function_exists('apache_setenv')) {
    apache_setenv('no-gzip', '1');
}

header('Content-Type: text/event-stream');
header('Cache-Control: no-cache, must-revalidate');
header('Connection: keep-alive');
header('X-Accel-Buffering: no');
header('Content-Encoding: none');

ob_implicit_flush(true);
while (ob_get_level() > 0) {
    ob_end_flush();
}

$trackFile = __DIR__ . '/../storage/task_update.txt';
$lastTime = file_exists($trackFile) ? filemtime($trackFile) : 0;

echo "retry: 2000\n\n";
flush();

while (true) {
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
    sleep(2);
    if (connection_aborted()) break;
}
