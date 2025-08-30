<?php
include_once "db.php";

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['submit'])) {
    $name = cleanData($_POST['name']);
    $email = cleanData($_POST['email']);
    
    // Generate student_id
    $id = uniqid();
    $my_id = substr($id, 6);
    $student_id = "25/" . $my_id;

    // Check if email exists
    $sel = "SELECT email FROM student_prof01 WHERE email = :email";
    $stmt = $conn->prepare($sel);
    $stmt->execute([':email' => $email]);

    if ($stmt->rowCount() > 0) {
        echo "<script>alert('Email already exists'); window.location='index.php';</script>";
    } else {
        // Insert new student
        $ins = "INSERT INTO student_prof01 (student_id, name, email) 
                VALUES (:student_id, :name, :email)";
        $stmt = $conn->prepare($ins);
        $stmt->execute([
            ':student_id' => $student_id,
            ':name' => $name,
            ':email' => $email
        ]);

        echo "<script>alert('Successfully registered'); window.location='index.php';</script>";
    }
}

function cleanData($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
    return $data;
}
?>
