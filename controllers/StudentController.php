<?php
session_start();

if (!isset($_SESSION['user_id']) || !filter_var($_SESSION['user_id'], FILTER_VALIDATE_INT)) {
    header('Location: ../views/login.php');
    exit;
}

if (($_SESSION['user_role'] ?? 'student') !== 'admin') {
    header('Location: ProfileController.php');
    exit;
}

require_once __DIR__ . '/../models/Student.php';

$studentModel = new Student();
$action = $_GET['action'] ?? 'index';

function validStudentInput($name, $ic, $marks): array
{
    $errors = [];

    if ($name === '') {
        $errors[] = 'Student name is required.';
    } elseif (strlen($name) > 100) {
        $errors[] = 'Student name must not exceed 100 characters.';
    }

    if ($ic === '') {
        $errors[] = 'IC number is required.';
    } elseif (!preg_match('/^\d{6}-\d{2}-\d{4}$/', $ic)) {
        $errors[] = 'IC number must use the format 010101-07-0902.';
    }

    if ($marks === '' || !ctype_digit((string) $marks)) {
        $errors[] = 'Marks must be a whole number between 0 and 100.';
    } elseif ((int) $marks < 0 || (int) $marks > 100) {
        $errors[] = 'Marks must be between 0 and 100.';
    }

    return $errors;
}

function validStudentUpdateInput($name, $ic, $marks): array
{
    $errors = [];

    if ($name === '') {
        $errors[] = 'Student name is required.';
    } elseif (strlen($name) > 100) {
        $errors[] = 'Student name must not exceed 100 characters.';
    }

    if ($ic === '') {
        $errors[] = 'IC number is required.';
    } elseif (!preg_match('/^\d{6}-\d{2}-\d{4}$/', $ic)) {
        $errors[] = 'IC number must use the format 010101-07-0902.';
    }

    if ($marks === '' || !ctype_digit((string) $marks)) {
        $errors[] = 'Marks must be a whole number between 0 and 100.';
    } elseif ((int) $marks < 0 || (int) $marks > 100) {
        $errors[] = 'Marks must be between 0 and 100.';
    }

    return $errors;
}

switch ($action) {

    // READ
    case 'index':
        try {
            $students = $studentModel->getAllStudents();
        } catch (PDOException $e) {
            $students = [];
            $error = 'Unable to load student records. Please try again later.';
        }

        require_once __DIR__ . '/../views/students/index.php';
        break;

    // CREATE
    case 'create':
        $name = '';
        $ic = '';
        $marks = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = trim($_POST['name'] ?? '');
            $ic = trim($_POST['ic'] ?? '');
            $marks = trim($_POST['marks'] ?? '');

            $errors = validStudentInput($name, $ic, $marks);

            if (empty($errors)) {
                try {
                    $result = $studentModel->addStudent($name, $ic, (int) $marks);

                    if ($result) {
                        header('Location: StudentController.php?action=index&added=1');
                        exit;
                    }

                    $error = 'Failed to add student.';
                } catch (PDOException $e) {
                    $error = 'Unable to add student. Please check that the IC number is unique.';
                }
            } else {
                $error = implode(' ', $errors);
            }
        }

        require_once __DIR__ . '/../views/students/create.php';
        break;

    // UPDATE
    case 'edit':
        $id = $_GET['id'] ?? '';

        if ($id === '' || !ctype_digit((string) $id) || (int) $id <= 0) {
            $error = 'Invalid student ID.';
            $students = $studentModel->getAllStudents();
            require_once __DIR__ . '/../views/students/index.php';
            break;
        }

        $id = (int) $id;
        $student = $studentModel->getStudentById($id);

        if (!$student) {
            $error = 'Student not found.';
            $students = $studentModel->getAllStudents();
            require_once __DIR__ . '/../views/students/index.php';
            break;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = trim($_POST['name'] ?? '');
            $ic = trim($_POST['ic'] ?? '');
            $marks = trim($_POST['marks'] ?? '');

            $errors = validStudentUpdateInput($name, $ic, $marks);

            $student['name'] = $name;
            $student['ic'] = $ic;
            $student['marks'] = $marks;

            if (empty($errors)) {
                try {
                    $result = $studentModel->updateStudent($id, $name, $ic, (int) $marks);

                    if ($result) {
                        header('Location: StudentController.php?action=index&updated=1');
                        exit;
                    }

                    $error = 'Failed to update student.';
                } catch (PDOException $e) {
                    $error = 'Unable to update student. Please check that the IC number is unique.';
                }
            } else {
                $error = implode(' ', $errors);
            }
        }

        require_once __DIR__ . '/../views/students/edit.php';
        break;

    // DELETE - uses POST so deletion is not triggered by a simple GET request.
    case 'delete':
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: StudentController.php?action=index');
            exit;
        }

        $id = $_POST['id'] ?? '';

        if ($id === '' || !ctype_digit((string) $id) || (int) $id <= 0) {
            header('Location: StudentController.php?action=index&error=invalid_id');
            exit;
        }

        try {
            $result = $studentModel->deleteStudent((int) $id);

            if ($result) {
                header('Location: StudentController.php?action=index&deleted=1');
                exit;
            }
        } catch (PDOException $e) {
            header('Location: StudentController.php?action=index&error=delete_failed');
            exit;
        }

        header('Location: StudentController.php?action=index&error=delete_failed');
        exit;

    default:
        header('Location: StudentController.php?action=index');
        exit;
}
?>
