<?php
session_start();

require_once __DIR__ . '/../models/User.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../views/login.php');
    exit;
}

$name = trim($_POST['name'] ?? '');
$password = $_POST['password'] ?? '';

if ($name === '' || $password === '') {
    $error = 'Please enter your name and password.';
    require_once __DIR__ . '/../views/login.php';
    exit;
}

if (strlen($name) > 100 || strlen($password) > 255) {
    $error = 'Invalid name or password.';
    require_once __DIR__ . '/../views/login.php';
    exit;
}

try {
    $userModel = new User();
    $user = $userModel->getUserByName($name);

    if (!$user || !$userModel->verifyPassword($password, $user['password'])) {
        $error = 'Invalid name or password.';
        require_once __DIR__ . '/../views/login.php';
        exit;
    }

    // Prevent session fixation after successful authentication.
    session_regenerate_id(true);

    $_SESSION['user_id'] = (int) $user['id'];
    $_SESSION['user_name'] = $user['name'];
    $_SESSION['user_role'] = $user['role'];

    header('Location: ProfileController.php');
    exit;
} catch (PDOException $e) {
    $error = 'Unable to process login. Please try again later.';
    require_once __DIR__ . '/../views/login.php';
    exit;
}
?>
