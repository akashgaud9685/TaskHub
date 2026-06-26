<?php
ini_set('display_errors', '1');
error_reporting(E_ALL);

echo "Step 1: Including config...<br>";
require_once __DIR__ . '/config/database.php';
echo "Step 2: Config loaded OK<br>";

echo "Step 3: Session save path: " . session_save_path() . "<br>";
echo "Step 4: Session dir exists: " . (is_dir(session_save_path()) ? 'YES' : 'NO') . "<br>";
echo "Step 5: Session dir writable: " . (is_writable(session_save_path()) ? 'YES' : 'NO') . "<br>";

echo "Step 6: Starting session...<br>";
session_start();
echo "Step 7: Session started<br>";
echo "Session ID: " . session_id() . "<br>";
echo "User ID: " . ($_SESSION['user_id'] ?? 'NOT SET') . "<br>";
echo "Role: " . ($_SESSION['role'] ?? 'NOT SET') . "<br>";

echo "Step 8: Session file: " . session_save_path() . '/sess_' . session_id() . "<br>";
echo "Session file exists: " . (file_exists(session_save_path() . '/sess_' . session_id()) ? 'YES' : 'NO') . "<br>";

echo "Step 9: Checking DB...<br>";
try {
    $db = getBizDB();
    echo "DB OK<br>";
    if (!empty($_SESSION['session_token'])) {
        $tokCheck = $db->prepare('SELECT session_token FROM users WHERE id = :id');
        $tokCheck->execute([':id' => $_SESSION['user_id']]);
        $token = $tokCheck->fetchColumn();
        echo "DB Token: " . ($token ? substr($token, 0, 10) . '...' : 'NULL') . "<br>";
        echo "Session Token: " . (isset($_SESSION['session_token']) ? substr($_SESSION['session_token'], 0, 10) . '...' : 'NOT SET') . "<br>";
        echo "Tokens match: " . ($token === ($_SESSION['session_token'] ?? null) ? 'YES' : 'NO') . "<br>";
    }
} catch (Throwable $e) {
    echo "ERROR: " . $e->getMessage() . "<br>";
    echo "Line: " . $e->getLine() . "<br>";
}
?>