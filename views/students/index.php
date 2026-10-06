<?php
require_once __DIR__ . '/../includes/header.php';
?>

<div class="container mt-5">
    <?php if (isset($_GET['updated'])): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            Student updated successfully!
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <?php if (isset($_GET['added'])): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            Student added successfully!
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <?php if (isset($_GET['deleted'])): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            Student deleted successfully!
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <?php if (isset($_GET['error'])): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?php
            $message = match ($_GET['error']) {
                'invalid_id' => 'Invalid student ID.',
                'delete_failed' => 'Failed to delete student.',
                default => 'An error occurred. Please try again.'
            };
            echo htmlspecialchars($message);
            ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <?php if (!empty($error)): ?>
        <div class="alert alert-danger" role="alert">
            <?= htmlspecialchars($error) ?>
        </div>
    <?php endif; ?>

    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
        <div>
            <h2 class="mb-1">PSP Student Grade Management</h2>
            <small class="text-muted">Manage student records and marks</small>
        </div>
        <div class="d-flex gap-2 flex-wrap">
            <a href="../controllers/ProfileController.php" class="btn btn-outline-secondary">
                <span aria-hidden="true">←</span> Back to Profile
            </a>
            <a href="../controllers/StudentController.php?action=create" class="btn btn-primary">Add Student</a>
        </div>
    </div>

    <div class="card shadow">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th>No.</th>
                            <th>Name</th>
                            <th>IC</th>
                            <th>Marks</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php if (!empty($students)): ?>
                        <?php foreach ($students as $index => $student): ?>
                            <tr>
                                <td><?= $index + 1 ?></td>
                                <td><?= htmlspecialchars($student['name']) ?></td>
                                <td><?= htmlspecialchars($student['ic']) ?></td>
                                <td><?= htmlspecialchars($student['marks']) ?></td>
                                <td>
                                    <a href="../controllers/StudentController.php?action=edit&id=<?= (int) $student['id'] ?>"
                                       class="btn btn-warning btn-sm">
                                        Edit
                                    </a>

                                    <button type="button"
                                            class="btn btn-danger btn-sm"
                                            data-bs-toggle="modal"
                                            data-bs-target="#deleteStudentModal"
                                            data-student-id="<?= (int) $student['id'] ?>"
                                            data-student-name="<?= htmlspecialchars($student['name'], ENT_QUOTES, 'UTF-8') ?>">
                                        Delete
                                    </button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5" class="text-center">No student records found.</td>
                        </tr>
                    <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteStudentModal" tabindex="-1" aria-labelledby="deleteStudentModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content delete-modal">
            <div class="modal-header border-0">
                <div class="delete-icon" aria-hidden="true">
                    <span>!</span>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center px-4 pt-0 pb-4">
                <h4 id="deleteStudentModalLabel" class="mb-2">Delete Student?</h4>
                <p class="text-muted mb-1">Are you sure you want to permanently delete</p>
                <p class="fw-bold mb-3" id="deleteStudentName">this student</p>
                <p class="small text-muted mb-0">This action cannot be undone.</p>
            </div>
            <div class="modal-footer border-0 justify-content-center gap-2 pb-4">
                <button type="button" class="btn btn-light border px-4" data-bs-dismiss="modal">Cancel</button>
                <form method="POST" action="../controllers/StudentController.php?action=delete" id="deleteStudentForm" class="m-0">
                    <input type="hidden" name="id" id="deleteStudentId" value="">
                    <button type="submit" class="btn btn-danger px-4">Yes, Delete</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const modal = document.getElementById('deleteStudentModal');
    if (!modal) return;

    modal.addEventListener('show.bs.modal', function (event) {
        const button = event.relatedTarget;
        const studentId = button.getAttribute('data-student-id');
        const studentName = button.getAttribute('data-student-name');

        document.getElementById('deleteStudentId').value = studentId;
        document.getElementById('deleteStudentName').textContent = studentName;
    });
});
</script>

<?php
require_once __DIR__ . '/../includes/footer.php';
?>
