<?php
require_once __DIR__ . '/../config/database.php';

header('Content-Type: application/json');
session_start();
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'trial_days_left' => 365, 'expired' => false]);
    exit;
}

$trialDays = 30;
$result = ['success' => true, 'trial_days_left' => 365, 'expired' => false, 'created_at' => null];

try {
    $db = getDB();
    if (!empty($_SESSION['business_id'])) {
        $stmt = $db->prepare('SELECT created_at FROM businesses WHERE id = :id');
        $stmt->execute([':id' => $_SESSION['business_id']]);
        $biz = $stmt->fetch();
        if ($biz && !empty($biz['created_at'])) {
            $created = strtotime($biz['created_at']);
            $now = time();
            $elapsed = floor(($now - $created) / 86400);
            $left = max(0, $trialDays - $elapsed);
            $result['trial_days_left'] = $left;
            $result['expired'] = $left <= 0;
            $result['created_at'] = $biz['created_at'];
        }
    }
} catch (\Throwable $e) {}

echo json_encode($result);
