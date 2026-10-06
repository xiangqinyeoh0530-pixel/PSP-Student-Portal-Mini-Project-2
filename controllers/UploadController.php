<?php
session_start();

if (!isset($_SESSION['user_id']) || !filter_var($_SESSION['user_id'], FILTER_VALIDATE_INT)) {
    header('Location: ../views/login.php');
    exit;
}

require_once __DIR__ . '/../models/User.php';

$userId = (int) $_SESSION['user_id'];
$uploadDirectory = __DIR__ . '/../uploads/';
$maxFileSize = 2 * 1024 * 1024; // 2MB
$allowedExtensions = ['jpg', 'jpeg', 'png'];
$allowedMimeTypes = [
    'image/jpeg' => 'jpg',
    'image/png'  => 'png'
];

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ProfileController.php');
    exit;
}

if (!isset($_FILES['profile_picture']) || !is_array($_FILES['profile_picture'])) {
    $_SESSION['upload_error'] = 'Please select a profile picture.';
    header('Location: ProfileController.php');
    exit;
}

$file = $_FILES['profile_picture'];

if ($file['error'] !== UPLOAD_ERR_OK) {
    $message = 'The upload could not be completed.';
    if ($file['error'] === UPLOAD_ERR_INI_SIZE || $file['error'] === UPLOAD_ERR_FORM_SIZE) {
        $message = 'File size must not exceed 2MB.';
    }
    $_SESSION['upload_error'] = $message;
    header('Location: ProfileController.php');
    exit;
}

if (!is_uploaded_file($file['tmp_name'])) {
    $_SESSION['upload_error'] = 'Invalid upload request.';
    header('Location: ProfileController.php');
    exit;
}

if ($file['size'] <= 0 || $file['size'] > $maxFileSize) {
    $_SESSION['upload_error'] = 'File size must not exceed 2MB.';
    header('Location: ProfileController.php');
    exit;
}

// Validate the real MIME type, not only the filename extension.
$originalExtension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

if (!in_array($originalExtension, $allowedExtensions, true)) {
    $_SESSION['upload_error'] = 'Invalid file type. Only JPG, JPEG and PNG files are allowed.';
    header('Location: ProfileController.php');
    exit;
}

$finfo = new finfo(FILEINFO_MIME_TYPE);
$mimeType = $finfo->file($file['tmp_name']);

if (!isset($allowedMimeTypes[$mimeType])) {
    $_SESSION['upload_error'] = 'Invalid file type. Only JPG, JPEG and PNG files are allowed.';
    header('Location: ProfileController.php');
    exit;
}

$extension = $allowedMimeTypes[$mimeType];

// Confirm that the image is a real image and restrict the accepted image types.
$imageInfo = @getimagesize($file['tmp_name']);
if ($imageInfo === false || !isset($imageInfo['mime']) || $imageInfo['mime'] !== $mimeType) {
    $_SESSION['upload_error'] = 'The selected file is not a valid image.';
    header('Location: ProfileController.php');
    exit;
}

if (!is_dir($uploadDirectory) && !mkdir($uploadDirectory, 0755, true)) {
    $_SESSION['upload_error'] = 'Upload directory is unavailable. Please try again later.';
    header('Location: ProfileController.php');
    exit;
}

// Never trust the original filename. Generate a unique server-side filename.
$newFilename = uniqid('profile_', true) . '.' . $extension;
$destination = $uploadDirectory . $newFilename;

if (!move_uploaded_file($file['tmp_name'], $destination)) {
    $_SESSION['upload_error'] = 'Unable to save the uploaded picture. Please try again.';
    header('Location: ProfileController.php');
    exit;
}

$userModel = new User();

try {
    $user = $userModel->getUserById($userId);

    if (!$user) {
        @unlink($destination);
        $_SESSION['upload_error'] = 'User account could not be found.';
        header('Location: ../views/login.php');
        exit;
    }

    if (!$userModel->updateProfilePicture($userId, $newFilename)) {
        @unlink($destination);
        $_SESSION['upload_error'] = 'Unable to update your profile picture. Please try again.';
        header('Location: ProfileController.php');
        exit;
    }

    // Remove the previous picture only after the database update succeeds.
    if (!empty($user['profile_picture'])) {
        $oldFile = $uploadDirectory . basename($user['profile_picture']);
        if (is_file($oldFile)) {
            @unlink($oldFile);
        }
    }

    $_SESSION['upload_success'] = 'Profile picture uploaded successfully.';
} catch (PDOException $e) {
    @unlink($destination);
    $_SESSION['upload_error'] = 'Unable to update your profile picture. Please try again later.';
}

header('Location: ProfileController.php');
exit;
?>
