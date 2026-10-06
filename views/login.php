<?php
session_start();

if (isset($_SESSION['user_id'])) {
    header('Location: ../controllers/ProfileController.php');
    exit;
}

require_once __DIR__ . '/includes/header.php';
?>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">Student Login</h4>
                </div>

                <div class="card-body">
                    <?php if (!empty($error)): ?>
                        <div class="alert alert-danger" role="alert">
                            <?= htmlspecialchars($error) ?>
                        </div>
                    <?php endif; ?>

                    <form method="POST" action="../controllers/LoginController.php" onsubmit="return validateLoginForm();">
                        <div class="mb-3">
                            <label for="name" class="form-label">Name</label>
                            <input type="text"
                                   class="form-control"
                                   id="name"
                                   name="name"
                                   maxlength="100"
                                   value="<?= htmlspecialchars($_POST['name'] ?? '') ?>"
                                   required>
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input type="password"
                                   class="form-control"
                                   id="password"
                                   name="password"
                                   maxlength="255"
                                   autocomplete="current-password"
                                   required>
                        </div>

                        <button type="submit" class="btn btn-primary w-100">Login</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function validateLoginForm() {
    const name = document.getElementById('name').value.trim();
    const password = document.getElementById('password').value;

    if (name === '') {
        alert('Please enter your name.');
        return false;
    }

    if (password === '') {
        alert('Please enter your password.');
        return false;
    }

    return true;
}
</script>

<?php
require_once __DIR__ . '/includes/footer.php';
?>
