<?php
session_start();

if (!isset($_SESSION['user_id']) || !filter_var($_SESSION['user_id'], FILTER_VALIDATE_INT)) {
    header('Location: ../views/login.php');
    exit;
}

require_once __DIR__ . '/../models/User.php';

$userId = (int) $_SESSION['user_id'];
$userModel = new User();

try {
    $user = $userModel->getUserById($userId);
} catch (PDOException $e) {
    $user = false;
}

if (!$user) {
    $_SESSION = [];
    session_destroy();
    header('Location: ../views/login.php');
    exit;
}

$error = '';
$success = '';
$oldPassword = '';
$newPassword = '';
$confirmPassword = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $oldPassword = $_POST['old_password'] ?? '';
    $newPassword = $_POST['new_password'] ?? '';
    $confirmPassword = $_POST['confirm_password'] ?? '';

    if ($oldPassword === '' || $newPassword === '' || $confirmPassword === '') {
        $error = 'All password fields are required.';
    } elseif (strlen($newPassword) < 6) {
        $error = 'New password must be at least 6 characters long.';
    } elseif (strlen($newPassword) > 255) {
        $error = 'New password must not exceed 255 characters.';
    } elseif ($newPassword !== $confirmPassword) {
        $error = 'New password and confirm password do not match.';
    } elseif (!$userModel->verifyPassword($oldPassword, $user['password'])) {
        $error = 'Old password is incorrect.';
    } elseif ($userModel->verifyPassword($newPassword, $user['password'])) {
        $error = 'New password must be different from the old password.';
    } else {
        try {
            $updated = $userModel->updatePassword($userId, $newPassword);

            if ($updated) {
                $success = 'Password changed successfully.';
                $oldPassword = '';
                $newPassword = '';
                $confirmPassword = '';
                $user = $userModel->getUserById($userId);
            } else {
                $error = 'Failed to update password. Please try again.';
            }
        } catch (PDOException $e) {
            $error = 'Failed to update password. Please try again later.';
        }
    }
}

require_once __DIR__ . '/../views/change_password.php';
?>
