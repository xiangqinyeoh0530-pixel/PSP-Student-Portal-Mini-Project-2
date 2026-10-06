<?php
require_once __DIR__ . '/includes/header.php';
?>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow">
                <div class="card-header bg-warning text-dark">
                    <h4 class="mb-0">Change Password</h4>
                </div>

                <div class="card-body">
                    <?php if (!empty($error)): ?>
                        <div class="alert alert-danger" role="alert">
                            <?= htmlspecialchars($error) ?>
                        </div>
                    <?php endif; ?>

                    <?php if (!empty($success)): ?>
                        <div class="alert alert-success" role="alert">
                            <?= htmlspecialchars($success) ?>
                        </div>
                    <?php endif; ?>

                    <form method="POST"
                          action="../controllers/PasswordController.php"
                          onsubmit="return validatePasswordForm();"
                          novalidate>

                        <div class="mb-3">
                            <label for="old_password" class="form-label">Old Password</label>
                            <input type="password"
                                   class="form-control"
                                   id="old_password"
                                   name="old_password"
                                   autocomplete="current-password"
                                   required>
                        </div>

                        <div class="mb-3">
                            <label for="new_password" class="form-label">New Password</label>
                            <input type="password"
                                   class="form-control"
                                   id="new_password"
                                   name="new_password"
                                   minlength="6"
                                   maxlength="255"
                                   autocomplete="new-password"
                                   required>
                            <div class="form-text">Password must be at least 6 characters.</div>
                        </div>

                        <div class="mb-3">
                            <label for="confirm_password" class="form-label">Confirm Password</label>
                            <input type="password"
                                   class="form-control"
                                   id="confirm_password"
                                   name="confirm_password"
                                   minlength="6"
                                   maxlength="255"
                                   autocomplete="new-password"
                                   required>
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">Update Password</button>
                            <a href="../controllers/ProfileController.php" class="btn btn-secondary">Back to Profile</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="validationModal" tabindex="-1" aria-labelledby="validationModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content validation-modal">
            <div class="modal-header">
                <h5 class="modal-title" id="validationModalLabel">Please check your input</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="validationMessage"></div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-bs-dismiss="modal">OK</button>
            </div>
        </div>
    </div>
</div>

<script>
function showValidationError(message) {
    document.getElementById('validationMessage').textContent = message;
    bootstrap.Modal.getOrCreateInstance(document.getElementById('validationModal')).show();
}

function validatePasswordForm() {
    const oldPassword = document.getElementById('old_password').value;
    const newPassword = document.getElementById('new_password').value;
    const confirmPassword = document.getElementById('confirm_password').value;

    if (oldPassword.trim() === '' || newPassword.trim() === '' || confirmPassword.trim() === '') {
        showValidationError('All password fields are required.');
        return false;
    }

    if (newPassword.length < 6) {
        showValidationError('New password must be at least 6 characters long.');
        return false;
    }

    if (newPassword !== confirmPassword) {
        showValidationError('New password and confirm password do not match.');
        return false;
    }

    return true;
}
</script>

<?php
require_once __DIR__ . '/includes/footer.php';
?>
