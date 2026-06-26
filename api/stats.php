<?php
require_once __DIR__ . '/../config/database.php';
header('Content-Type: application/json');
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}

try {
    $db = getBizDB();
    $bizId = !empty($_SESSION['business_id']) ? (int)$_SESSION['business_id'] : null;

    if ($bizId) {
        $st = $db->prepare("SELECT COUNT(*) FROM users WHERE role = 'staff' AND business_id = :bid");
        $st->execute([':bid' => $bizId]); $totalStaff = $st->fetchColumn();
        $st = $db->prepare("SELECT COUNT(*) FROM tasks t JOIN users u ON t.assigned_to = u.id WHERE u.business_id = :bid");
        $st->execute([':bid' => $bizId]); $totalTasks = $st->fetchColumn();
        $st = $db->prepare("SELECT COUNT(*) FROM tasks t JOIN users u ON t.assigned_to = u.id WHERE t.status = 'todo' AND u.business_id = :bid");
        $st->execute([':bid' => $bizId]); $pending = $st->fetchColumn();
        $st = $db->prepare("SELECT COUNT(*) FROM tasks t JOIN users u ON t.assigned_to = u.id WHERE t.status = 'in-progress' AND u.business_id = :bid");
        $st->execute([':bid' => $bizId]); $inProgress = $st->fetchColumn();
        $st = $db->prepare("SELECT COUNT(*) FROM tasks t JOIN users u ON t.assigned_to = u.id WHERE t.status = 'completed' AND u.business_id = :bid");
        $st->execute([':bid' => $bizId]); $completed = $st->fetchColumn();
    } else {
        $totalStaff = $db->query("SELECT COUNT(*) FROM users WHERE role = 'staff'")->fetchColumn();
        $totalTasks = $db->query("SELECT COUNT(*) FROM tasks")->fetchColumn();
        $pending = $db->query("SELECT COUNT(*) FROM tasks WHERE status = 'todo'")->fetchColumn();
        $inProgress = $db->query("SELECT COUNT(*) FROM tasks WHERE status = 'in-progress'")->fetchColumn();
        $completed = $db->query("SELECT COUNT(*) FROM tasks WHERE status = 'completed'")->fetchColumn();
    }

    echo json_encode([
        'success' => true,
        'data' => [
            'total_staff' => (int)$totalStaff,
            'total_tasks' => (int)$totalTasks,
            'pending' => (int)$pending,
            'in_progress' => (int)$inProgress,
            'completed' => (int)$completed,
        ]
    ]);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Database error']);
}
