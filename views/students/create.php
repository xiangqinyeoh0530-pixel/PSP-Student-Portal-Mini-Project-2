<?php
require_once __DIR__ . '/../includes/header.php';
?>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">Add New Student</h4>
                </div>

                <div class="card-body">
                    <?php if (!empty($error)): ?>
                        <div class="alert alert-danger" role="alert">
                            <?= htmlspecialchars($error) ?>
                        </div>
                    <?php endif; ?>

                    <form method="POST"
                          action="../controllers/StudentController.php?action=create"
                          onsubmit="return validateForm();"
                          novalidate>

                        <div class="mb-3">
                            <label for="name" class="form-label">Student Name</label>
                            <input type="text"
                                   class="form-control"
                                   id="name"
                                   name="name"
                                   maxlength="100"
                                   value="<?= htmlspecialchars($name ?? '') ?>"
                                   placeholder="Enter student name"
                                   required>
                        </div>

                        <div class="mb-3">
                            <label for="ic" class="form-label">IC Number</label>
                            <input type="text"
                                   class="form-control"
                                   id="ic"
                                   name="ic"
                                   maxlength="14"
                                   pattern="[0-9]{6}-[0-9]{2}-[0-9]{4}"
                                   value="<?= htmlspecialchars($ic ?? '') ?>"
                                   placeholder="e.g. 010101-07-0902"
                                   required>
                        </div>

                        <div class="mb-3">
                            <label for="program" class="form-label">Program</label>
                            <input type="text"
                                   class="form-control"
                                   id="program"
                                   name="program"
                                   maxlength="100"
                                   value="<?= htmlspecialchars($program ?? '') ?>"
                                   placeholder="Enter program"
                                   required>
                        </div>

                        <div class="mb-3">
                            <label for="marks" class="form-label">Marks</label>
                            <input type="number"
                                   class="form-control"
                                   id="marks"
                                   name="marks"
                                   min="0"
                                   max="100"
                                   step="1"
                                   value="<?= htmlspecialchars($marks ?? '') ?>"
                                   placeholder="Enter marks"
                                   required>
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">Add Student</button>
                            <a href="../controllers/StudentController.php?action=index" class="btn btn-secondary">Cancel</a>
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

function validateForm() {
    const name = document.getElementById('name').value.trim();
    const ic = document.getElementById('ic').value.trim();
    const program = document.getElementById('program').value.trim();
    const marks = document.getElementById('marks').value;

    if (name === '') {
        showValidationError('Please enter student name.');
        return false;
    }

    if (program === '') {
        showValidationError('Please enter program.');
        return false;
    }

    if (ic === '') {
        showValidationError('Please enter IC number.');
        return false;
    }

    if (!/^[0-9]{6}-[0-9]{2}-[0-9]{4}$/.test(ic)) {
        showValidationError('IC number must use the format 010101-07-0902.');
        return false;
    }

    if (marks === '' || !Number.isInteger(Number(marks))) {
        showValidationError('Please enter a whole number for marks.');
        return false;
    }

    if (Number(marks) < 0 || Number(marks) > 100) {
        showValidationError('Marks must be between 0 and 100.');
        return false;
    }

    return true;
}
</script>

<?php
require_once __DIR__ . '/../includes/footer.php';
?>
