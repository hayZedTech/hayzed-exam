<?php 
include_once "db.php";

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['submit'])) {
    $email = cleanData($_POST['email']);

    // Use prepared statement
    $sel = "SELECT * FROM student_prof01 WHERE email = :email";
    $stmt = $conn->prepare($sel);
    $stmt->execute([':email' => $email]);

    if ($stmt->rowCount() > 0) {
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        $_SESSION['stid'] = $row["student_id"];
        $_SESSION['email'] = $row["email"];
        $_SESSION['name'] = $row["name"];

        // Example: if you want to insert into biology table
        // $ins = "INSERT INTO biology (student_id, status) VALUES (:stid, :status)";
        // $stmt2 = $conn->prepare($ins);
        // $stmt2->execute([
        //     ':stid' => $_SESSION['stid'],
        //     ':status' => 'Images/close1.png'
        // ]);

        echo "<script>alert('Choose your subject'); window.location='subjects.php';</script>";
    } else {
        echo "<script>alert('User not found'); window.location='index.php';</script>";
    }
}

function cleanData($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
    return $data;
}
?>
