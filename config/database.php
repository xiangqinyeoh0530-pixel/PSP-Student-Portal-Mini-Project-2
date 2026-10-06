<?php

$host = "sql307.infinityfree.com";
$dbname = "if0_42988673_psp_student_portal";
$username = "if0_42988673";
$password = "060530080074Yxq";

try {
    $conn = new PDO(
        "mysql:host=$host;dbname=$dbname;charset=utf8mb4",
        $username,
        $password,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false
        ]
    );
} catch (PDOException $e) {
    // Do not expose database credentials or internal database errors to users.
    die("Database connection failed. Please try again later.");
}
?>
