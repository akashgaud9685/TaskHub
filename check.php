<?php
$results = [];

$results[] = ['PHP Version', phpversion()];
$results[] = ['curl loaded', extension_loaded('curl') ? 'YES' : 'NO (required!)'];
$results[] = ['json loaded', extension_loaded('json') ? 'YES' : 'NO (required!)'];

if (extension_loaded('curl')) {
    if (!defined('SUPABASE_URL')) define('SUPABASE_URL', 'https://fmigjebieplnswqpgbgv.supabase.co');
    if (!defined('SUPABASE_ANON_KEY')) define('SUPABASE_ANON_KEY', 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJzdXBhYmFzZSIsInJlZiI6ImZtaWdqZWJpZXBsbnN3cXBnYmd2Iiwicm9sZSI6ImFub24iLCJpYXQiOjE3ODIwNTc4OTQsImV4cCI6MjA5NzYzMzg5NH0.paRSi8SFT0HDYCMiCyBqvqYiQKlzNGMfyWn2Fj2h_Fw');
    if (!defined('SUPABASE_SERVICE_KEY')) define('SUPABASE_SERVICE_KEY', getenv('SUPABASE_SERVICE_KEY') ?: '');
    $ch = curl_init();
    curl_setopt_array($ch, [
        CURLOPT_URL => SUPABASE_URL . '/rest/v1/',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HTTPHEADER => ['apikey: ' . SUPABASE_ANON_KEY, 'Authorization: Bearer ' . SUPABASE_ANON_KEY, 'Accept: application/json'],
        CURLOPT_TIMEOUT => 15,
        CURLOPT_SSL_VERIFYPEER => true,
        CURLOPT_USERAGENT => 'TaskHub-Check/1.0',
    ]);
    $resp = curl_exec($ch);
    $info = curl_getinfo($ch);
    $err = curl_error($ch);
    curl_close($ch);
    $results[] = ['Supabase REST API (anon key)', $info['http_code'] ? "HTTP {$info['http_code']}" : "FAILED: $err"];
    $results[] = ['Supabase URL', SUPABASE_URL ? 'OK' : 'MISSING'];
    $results[] = ['Supabase Anon Key', SUPABASE_ANON_KEY ? substr(SUPABASE_ANON_KEY, 0, 20) . '...' : 'MISSING'];
    if (SUPABASE_SERVICE_KEY) {
        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL => SUPABASE_URL . '/rest/v1/',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => ['apikey: ' . SUPABASE_SERVICE_KEY, 'Authorization: Bearer ' . SUPABASE_SERVICE_KEY, 'Accept: application/json'],
            CURLOPT_TIMEOUT => 15,
            CURLOPT_SSL_VERIFYPEER => true,
            CURLOPT_USERAGENT => 'TaskHub-Check/1.0',
        ]);
        $resp = curl_exec($ch);
        $info = curl_getinfo($ch);
        $err = curl_error($ch);
        curl_close($ch);
        $results[] = ['Supabase REST API (service key)', $info['http_code'] ? "HTTP {$info['http_code']}" : "FAILED: $err"];
    } else {
        $results[] = ['Supabase Service Key', 'NOT SET - Set SUPABASE_SERVICE_KEY env var if anon key fails'];
    }
}

$results[] = ['session extension', extension_loaded('session') ? 'YES' : 'NO'];
$results[] = ['storage/ writable', is_writable(__DIR__ . '/storage') ? 'YES' : 'NO'];
$results[] = ['uploads/ writable', is_writable(__DIR__ . '/uploads') ? 'YES' : 'NO'];

if (!file_exists(__DIR__ . '/storage/task_update.txt')) {
    @touch(__DIR__ . '/storage/task_update.txt');
}
$results[] = ['storage/task_update.txt', file_exists(__DIR__ . '/storage/task_update.txt') ? 'OK' : 'FAILED'];

require_once __DIR__ . '/config/database.php';

try {
    $db = getDB();
    $results[] = ['getDB()', 'OK - ' . get_class($db)];

    $stmt = $db->prepare('SELECT id, business_name FROM businesses LIMIT 5');
    $stmt->execute();
    $rows = $stmt->fetchAll();
    $results[] = ['Database query (businesses)', 'OK - ' . count($rows) . ' row(s) found'];
    if (!empty($rows)) {
        $results[] = ['Sample business', htmlspecialchars($rows[0]['business_name'] ?? 'N/A')];
    }

    $stmt2 = $db->prepare("SELECT id, name FROM users WHERE role = 'admin' LIMIT 5");
    $stmt2->execute();
    $rows2 = $stmt2->fetchAll();
    $results[] = ['Database query (users)', 'OK - ' . count($rows2) . ' row(s) found'];
} catch (Throwable $e) {
    $results[] = ['getDB()', 'FAILED: ' . $e->getMessage()];
    $results[] = ['Database query', 'FAILED: ' . $e->getMessage()];
}
?>
<!DOCTYPE html>
<html lang="en">
<head><meta charset="UTF-8"><title>TaskHub Check</title>
<script src="https://cdn.tailwindcss.com"></script>
<style>*{font-family:system-ui,sans-serif;}</style>
</head>
<body class="bg-slate-900 text-white p-8">
<div class="max-w-2xl mx-auto">
<h1 class="text-2xl font-bold mb-6">TaskHub Server Check</h1>
<table class="w-full border-collapse">
<thead><tr class="border-b border-slate-600"><th class="text-left py-2 px-4 text-slate-400">Check</th><th class="text-left py-2 px-4 text-slate-400">Result</th></tr></thead>
<tbody>
<?php foreach ($results as $r): ?>
<tr class="border-b border-slate-700/50">
<td class="py-2 px-4"><?= htmlspecialchars($r[0]) ?></td>
<td class="py-2 px-4">
<?php if (strpos($r[1], 'FAILED') === 0 || strpos($r[1], 'NO') === 0): ?>
<span class="text-red-500"><?= htmlspecialchars($r[1]) ?></span>
<?php else: ?>
<span class="text-emerald-500"><?= htmlspecialchars($r[1]) ?></span>
<?php endif; ?>
</td>
</tr>
<?php endforeach; ?>
</tbody>
</table>
</div>
</body>
</html>
