<?php
session_start();

if (!isset($_SESSION['user_id']) || !filter_var($_SESSION['user_id'], FILTER_VALIDATE_INT)) {
    header('Location: ../views/login.php');
    exit;
}

require_once __DIR__ . '/../models/User.php';

$userModel = new User();
$user = $userModel->getUserById((int) $_SESSION['user_id']);

if (!$user) {
    $_SESSION = [];
    session_destroy();
    header('Location: ../views/login.php');
    exit;
}

// The profile is always loaded using the authenticated session user ID.
require_once __DIR__ . '/../views/profile.php';
?>
