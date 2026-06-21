<?php

require_once __DIR__ . '/../config/database.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    exit;
}

$username     = trim($_POST['username'] ?? '');
$password     = $_POST['password'] ?? '';
$role         = $_POST['role'] ?? 'admin';
$businessCode = trim($_POST['business_code'] ?? '');

if ($username === '' || $password === '') {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Username and password are required']);
    exit;
}

try {
    $mainDb = getDB();
    $bizId = null;

    if ($role === 'staff') {
        if ($businessCode === '') {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Business code is required for staff login']);
            exit;
        }

        $bizInfo = $mainDb->prepare("SELECT id FROM businesses WHERE business_code = :code AND status = 'active'");
        $bizInfo->execute([':code' => $businessCode]);
        $business = $bizInfo->fetch();

        if (!$business) {
            http_response_code(401);
            echo json_encode(['success' => false, 'message' => 'Invalid business code or business is inactive']);
            exit;
        }

        $bizId = (int) $business['id'];

        $stmt = $mainDb->prepare("SELECT id, name, username, password, role, status, photo FROM users WHERE (username = :username OR email = :email) AND role = 'staff' AND business_id = :bid LIMIT 1");
        $stmt->execute([':username' => $username, ':email' => $username, ':bid' => $bizId]);
        $user = $stmt->fetch();
    } else {
        $bizInfo = $mainDb->prepare("SELECT id, business_name, owner_name, username, password FROM businesses WHERE (username = :username OR email = :email) AND status = 'active' LIMIT 1");
        $bizInfo->execute([':username' => $username, ':email' => $username]);
        $business = $bizInfo->fetch();

        if ($business) {
            if (!password_verify($password, $business['password'])) {
                http_response_code(401);
                echo json_encode(['success' => false, 'message' => 'Invalid credentials']);
                exit;
            }

            $bizId = (int) $business['id'];

            $stmt = $mainDb->prepare("SELECT id, name, username, password, role, status, photo FROM users WHERE (username = :username OR email = :email) AND role = 'admin' AND business_id = :bid LIMIT 1");
            $stmt->execute([':username' => $username, ':email' => $username, ':bid' => $bizId]);
            $user = $stmt->fetch();

            if (!$user) {
                http_response_code(500);
                echo json_encode(['success' => false, 'message' => 'Admin user not found. Contact support.']);
                exit;
            }
        } else {
            $stmt = $mainDb->prepare("SELECT id, name, username, password, role, status, photo FROM users WHERE (username = :username OR email = :email) AND role = 'admin' LIMIT 1");
            $stmt->execute([':username' => $username, ':email' => $username]);
            $user = $stmt->fetch();

            if (!$user || !password_verify($password, $user['password'])) {
                http_response_code(401);
                echo json_encode(['success' => false, 'message' => 'Invalid credentials']);
                exit;
            }
        }
    }

    if (!$user || !password_verify($password, $user['password'])) {
        http_response_code(401);
        echo json_encode(['success' => false, 'message' => 'Invalid credentials']);
        exit;
    }

    if ($user['status'] !== 'active') {
        http_response_code(403);
        echo json_encode(['success' => false, 'message' => 'Account is deactivated. Contact administrator.']);
        exit;
    }

    session_start();
    session_regenerate_id(true);

    $token = bin2hex(random_bytes(32));

    $_SESSION['user_id']       = (int) $user['id'];
    $_SESSION['name']          = $user['name'];
    $_SESSION['username']      = $user['username'];
    $_SESSION['role']          = $user['role'];
    $_SESSION['photo']         = $user['photo'];
    $_SESSION['session_token'] = $token;
    if ($bizId) {
        $_SESSION['business_id'] = $bizId;
    }

    $tokStmt = $mainDb->prepare('UPDATE users SET session_token = :token WHERE id = :id');
    $tokStmt->execute([':token' => $token, ':id' => $user['id']]);

    session_write_close();

    $redirect = $user['role'] === 'admin' ? 'admin/dashboard.php' : 'staff/dashboard.php';

    echo json_encode([
        'success'  => true,
        'redirect' => $redirect,
        'message'  => 'Login successful',
    ]);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Server error. Please try again.']);
} catch (\Throwable $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Server error. Please try again.']);
}
