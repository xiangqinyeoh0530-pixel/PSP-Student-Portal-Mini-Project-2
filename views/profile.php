<?php
require_once __DIR__ . '/includes/header.php';

$uploadSuccess = $_SESSION['upload_success'] ?? '';
$uploadError = $_SESSION['upload_error'] ?? '';
unset($_SESSION['upload_success'], $_SESSION['upload_error']);

$profilePicture = !empty($user['profile_picture'])
    ? '../uploads/' . rawurlencode(basename($user['profile_picture']))
    : '';
?>

<div class="container mt-5 mb-5">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-7">
            <div class="card shadow">
                <div class="card-header bg-success text-white d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">Student Profile</h4>
                    <a href="../controllers/LogoutController.php" class="btn btn-light btn-sm">Logout</a>
                </div>

                <div class="card-body">
                    <?php if ($uploadSuccess): ?>
                        <div class="alert alert-success" role="alert">
                            <?= htmlspecialchars($uploadSuccess) ?>
                        </div>
                    <?php endif; ?>

                    <?php if ($uploadError): ?>
                        <div class="alert alert-danger" role="alert">
                            <?= htmlspecialchars($uploadError) ?>
                        </div>
                    <?php endif; ?>

                    <div class="profile-picture-section text-center mb-4">
                        <?php if ($profilePicture): ?>
                            <img src="<?= htmlspecialchars($profilePicture) ?>"
                                 alt="Profile picture"
                                 class="profile-picture">
                        <?php else: ?>
                            <div class="profile-picture profile-picture-placeholder" aria-label="No profile picture">
                                <?= htmlspecialchars(strtoupper(substr($user['name'], 0, 1))) ?>
                            </div>
                        <?php endif; ?>

                        <h5 class="mt-3 mb-1"><?= htmlspecialchars($user['name']) ?></h5>
                        <p class="text-muted mb-0">Profile Picture</p>
                    </div>

                    <form action="../controllers/UploadController.php" method="POST" enctype="multipart/form-data" class="mb-4">
                        <label for="profile_picture" class="form-label fw-semibold">Upload Profile Picture</label>
                        <input type="file"
                               class="form-control"
                               id="profile_picture"
                               name="profile_picture"
                               accept=".jpg,.jpeg,.png"
                               required>
                        <div class="form-text">Allowed: JPG, JPEG, PNG. Maximum size: 2MB.</div>
                        <button type="submit" class="btn btn-success mt-3">Upload Picture</button>
                    </form>

                    <hr>

                    <div class="row mb-3">
                        <div class="col-md-4"><strong>Name</strong></div>
                        <div class="col-md-8"><?= htmlspecialchars($user['name']) ?></div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-4"><strong>NRIC</strong></div>
                        <div class="col-md-8"><?= htmlspecialchars($user['nric'] ?? 'N/A') ?></div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-4"><strong>Program</strong></div>
                        <div class="col-md-8"><?= htmlspecialchars($user['program'] ?? 'N/A') ?></div>
                    </div>

                    <div class="profile-actions mt-4">
                        <a href="../controllers/PasswordController.php" class="btn btn-primary">Change Password</a>
                        <?php if (($_SESSION['user_role'] ?? 'student') === 'admin'): ?>
                            <a href="../controllers/StudentController.php?action=index" class="btn btn-secondary">Student List</a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
require_once __DIR__ . '/includes/footer.php';
?>
