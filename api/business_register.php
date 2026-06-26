<?php
require_once __DIR__ . '/../config/database.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    exit;
}

$businessName = trim($_POST['business_name'] ?? '');
$ownerName    = trim($_POST['owner_name'] ?? '');
$email        = trim($_POST['email'] ?? '');
$phone        = trim($_POST['phone'] ?? '');
$address      = trim($_POST['address'] ?? '');
$username     = trim($_POST['username'] ?? '');
$password     = $_POST['password'] ?? '';

if ($phone !== '' && !preg_match('/^[\d\s\-\+]+$/', $phone)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Phone number can only contain digits, spaces, hyphens, and plus sign']);
    exit;
}

if ($businessName === '' || $ownerName === '' || $email === '' || $username === '' || $password === '') {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Business name, owner name, email, username, and password are required']);
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Invalid email address']);
    exit;
}

if (strlen($password) < 8) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Password must be at least 8 characters']);
    exit;
}

if (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{8,}$/', $password)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Password must contain uppercase, lowercase, and a number']);
    exit;
}

if (!preg_match('/^[a-zA-Z0-9_]{3,50}$/', $username)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Username must be 3-50 characters (letters, numbers, underscores only)']);
    exit;
}

try {
    $db = getDB();

    $check = $db->prepare('SELECT id FROM businesses WHERE email = :email OR username = :username');
    $check->execute([':email' => $email, ':username' => $username]);
    if ($check->fetch()) {
        http_response_code(409);
        echo json_encode(['success' => false, 'message' => 'Email or username is already registered']);
        exit;
    }

    $prefix = strtoupper(substr(preg_replace('/[^a-zA-Z0-9]/', '', $businessName), 0, 4));
    if ($prefix === '') $prefix = 'TASK';
    $code = $prefix . '-' . str_pad(random_int(0, 99999), 5, '0', STR_PAD_LEFT);
    $existingCode = $db->prepare('SELECT id FROM businesses WHERE business_code = :code');
    $existingCode->execute([':code' => $code]);
    while ($existingCode->fetch()) {
        $code = $prefix . '-' . str_pad(random_int(0, 99999), 5, '0', STR_PAD_LEFT);
        $existingCode->execute([':code' => $code]);
    }

    $hash = password_hash($password, PASSWORD_BCRYPT);

    $db->beginTransaction();

    $stmt = $db->prepare('INSERT INTO businesses (business_name, owner_name, email, phone, address, username, password, business_code, email_verified) VALUES (:bn, :on, :em, :ph, :ad, :un, :pw, :code, 1) RETURNING id');
    $stmt->execute([
        ':bn'   => $businessName,
        ':on'   => $ownerName,
        ':em'   => $email,
        ':ph'   => $phone,
        ':ad'   => $address,
        ':un'   => $username,
        ':pw'   => $hash,
        ':code' => $code,
    ]);
    $businessId = (int) $stmt->fetchColumn();

    $stmt = $db->prepare('INSERT INTO users (name, email, username, password, role, department, status, business_id, business_name) VALUES (:name, :em, :un, :pw, :role, :dept, :status, :bid, :bn) RETURNING id');
    $stmt->execute([
        ':name'   => $ownerName,
        ':em'     => $email,
        ':un'     => $username,
        ':pw'     => $hash,
        ':role'   => 'admin',
        ':dept'   => 'Management',
        ':status' => 'active',
        ':bid'    => $businessId,
        ':bn'     => $businessName,
    ]);
    $userId = (int) $stmt->fetchColumn();

    $defaultDepts = ['Design', 'Development', 'Marketing', 'Sales', 'Support', 'Operations', 'Management'];
    $deptStmt = $db->prepare('INSERT INTO departments (name, business_id) VALUES (:name, :bid)');
    foreach ($defaultDepts as $d) {
        $deptStmt->execute([':name' => $d, ':bid' => $businessId]);
    }

    $db->commit();

    session_start();
    session_regenerate_id(true);

    $token = bin2hex(random_bytes(32));

    $_SESSION['user_id']       = $userId;
    $_SESSION['name']          = $ownerName;
    $_SESSION['username']      = $username;
    $_SESSION['role']          = 'admin';
    $_SESSION['session_token'] = $token;
    $_SESSION['business_id']   = $businessId;

    $tokStmt = $db->prepare('UPDATE users SET session_token = :token WHERE id = :id');
    $tokStmt->execute([':token' => $token, ':id' => $userId]);

    echo json_encode([
        'success'  => true,
        'message'  => 'Business registered successfully!',
        'redirect' => 'admin/dashboard.php?setup=1',
    ]);
} catch (PDOException $e) {
    if (isset($db) && $db->inTransaction()) $db->rollBack();
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'A database error occurred. Please try again.']);
} catch (\Throwable $e) {
    if (isset($db) && $db->inTransaction()) $db->rollBack();
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'An unexpected error occurred. Please try again.']);
}
