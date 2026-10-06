<?php

require_once __DIR__ . '/../config/database.php';

class User
{
    private $conn;

    public function __construct()
    {
        global $conn;
        $this->conn = $conn;
    }

    public function getUserByName($name)
    {
        $sql = "SELECT * FROM users WHERE name = :name LIMIT 1";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':name', $name, PDO::PARAM_STR);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getUserByNric($nric)
    {
        $sql = "SELECT * FROM users WHERE nric = :nric LIMIT 1";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':nric', $nric, PDO::PARAM_STR);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getUserById($id)
    {
        $sql = "SELECT * FROM users WHERE id = :id LIMIT 1";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Passwords must be stored as hashes. Plain-text password comparison is not allowed.
    public function verifyPassword($inputPassword, $storedPassword)
    {
        if ($inputPassword === '' || empty($storedPassword)) {
            return false;
        }

        return password_verify($inputPassword, $storedPassword);
    }

    /**
     * Update the authenticated user's profile picture filename.
     * Only the filename is stored; the uploaded file itself is stored in /uploads.
     */
    public function updateProfilePicture($id, $filename)
    {
        $sql = "UPDATE users
                SET profile_picture = :profile_picture
                WHERE id = :id";

        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':profile_picture', $filename, PDO::PARAM_STR);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);

        return $stmt->execute();
    }

    public function updatePassword($id, $password)
    {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        if ($hashedPassword === false) {
            return false;
        }

        $sql = "UPDATE users
                SET password = :password
                WHERE id = :id";

        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':password', $hashedPassword, PDO::PARAM_STR);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);

        return $stmt->execute();
    }
}
?>
