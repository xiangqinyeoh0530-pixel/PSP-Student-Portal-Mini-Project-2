<?php

require_once __DIR__ . '/../config/database.php';

class Student
{
    private $conn;
    public function __construct()
    {
        global $conn;
        $this->conn = $conn;
    }

    //read - get all students
    public function getAllStudents()
    {
        $sql = "SELECT * FROM students ORDER BY id DESC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    //read - get one student
    public function getStudentById($id)
    {
        $sql = "SELECT * FROM students WHERE id = :id";

        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    //create - all student
    public function addStudent($name, $ic, $marks)
    {
        $sql = "INSERT INTO students (name, ic, marks)
                VALUES (:name, :ic, :marks)";

        $stmt = $this->conn->prepare($sql);

        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':ic', $ic);
        $stmt->bindParam(':marks', $marks, PDO::PARAM_INT);

        return $stmt->execute();
    }

    //update - update student
    public function updateStudent($id, $name, $ic, $marks)
    {
        $sql = "UPDATE students
                SET name = :name,
                    ic = :ic,
                    marks = :marks
                WHERE id = :id";

        $stmt = $this->conn->prepare($sql);

        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':ic', $ic);
        $stmt->bindParam(':marks', $marks, PDO::PARAM_INT);

        return $stmt->execute();
    }

    // delete - delete student
    public function deleteStudent($id)
    {
        $sql = "DELETE FROM students WHERE id = :id";

        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);

        return $stmt->execute();
    }
}
?>