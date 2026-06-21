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

    $totalStaff = $db->query("SELECT COUNT(*) FROM users WHERE role = 'staff'" . ($bizId ? " AND business_id = $bizId" : ''))->fetchColumn();
    $totalTasks = $bizId ? $db->query("SELECT COUNT(*) FROM tasks t JOIN users u ON t.assigned_to = u.id WHERE u.business_id = $bizId")->fetchColumn() : $db->query('SELECT COUNT(*) FROM tasks')->fetchColumn();
    $pending = $bizId ? $db->query("SELECT COUNT(*) FROM tasks t JOIN users u ON t.assigned_to = u.id WHERE t.status = 'todo' AND u.business_id = $bizId")->fetchColumn() : $db->query("SELECT COUNT(*) FROM tasks WHERE status = 'todo'")->fetchColumn();
    $inProgress = $bizId ? $db->query("SELECT COUNT(*) FROM tasks t JOIN users u ON t.assigned_to = u.id WHERE t.status = 'in-progress' AND u.business_id = $bizId")->fetchColumn() : $db->query("SELECT COUNT(*) FROM tasks WHERE status = 'in-progress'")->fetchColumn();
    $completed = $bizId ? $db->query("SELECT COUNT(*) FROM tasks t JOIN users u ON t.assigned_to = u.id WHERE t.status = 'completed' AND u.business_id = $bizId")->fetchColumn() : $db->query("SELECT COUNT(*) FROM tasks WHERE status = 'completed'")->fetchColumn();

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
