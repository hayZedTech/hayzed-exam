<?php
include_once "db.php";

function cleanData($data) {
    $data = trim($data);
    $data = stripslashes($data);
    return $data;
}

$alertType = null;
$alertTitle = '';
$alertHtml = '';
$redirectUrl = 'index.php';

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['submit'])) {
    $email = strtolower(cleanData($_POST['email'] ?? ''));
    $identifier = cleanData($_POST['identifier'] ?? '');
    $new_password = $_POST['new_password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';

    if (empty($email) || empty($identifier) || empty($new_password)) {
        $alertType = 'warning';
        $alertTitle = 'Incomplete Information';
        $alertHtml = 'Please fill out all fields: your registered email, verification identifier, and new password.';
        $redirectUrl = 'index.php?tab=reset';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $alertType = 'warning';
        $alertTitle = 'Invalid Email Address';
        $alertHtml = 'The email address provided is not formatted correctly.';
        $redirectUrl = 'index.php?tab=reset';
    } elseif (strlen($new_password) < 6) {
        $alertType = 'warning';
        $alertTitle = 'Password Too Short';
        $alertHtml = 'Your new password must be at least <strong>6 characters</strong> in length.';
        $redirectUrl = 'index.php?tab=reset';
    } elseif ($new_password !== $confirm_password) {
        $alertType = 'warning';
        $alertTitle = 'Passwords Do Not Match';
        $alertHtml = 'The new password and confirmation password do not match. Please verify them.';
        $redirectUrl = 'index.php?tab=reset';
    } else {
        // Query candidate record by email
        $sel = "SELECT * FROM exam_student_prof01 WHERE LOWER(email) = LOWER(:email) LIMIT 1";
        $stmt = $conn->prepare($sel);
        $stmt->execute([':email' => $email]);

        if ($stmt->rowCount() > 0) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            // Verify identity against candidate's Exam Student ID or Registered Full Name
            $cleanedInputId = strtolower(trim($identifier));
            $savedStudentId = strtolower(trim($row['student_id'] ?? ''));
            $savedName = strtolower(trim($row['name'] ?? ''));

            $isVerified = ($cleanedInputId === $savedStudentId) || ($cleanedInputId === $savedName);

            if ($isVerified) {
                // Identity confirmed -> Hash new password and update
                $hashedPassword = password_hash($new_password, PASSWORD_BCRYPT, ['cost' => 12]);
                $upd = $conn->prepare("UPDATE exam_student_prof01 SET password = :pw, reset_token = NULL, reset_expires = NULL WHERE id = :id");
                $upd->execute([':pw' => $hashedPassword, ':id' => $row['id']]);

                $alertType = 'success';
                $alertTitle = 'Password Reset Successfully!';
                $alertHtml = 'Hello <strong>' . htmlspecialchars($row['name']) . '</strong>, your password has been securely updated.<br><br>' .
                             '<small class="text-muted">You can now log in with your updated password.</small>';
                $redirectUrl = 'index.php?reset=success&email=' . urlencode($email);
            } else {
                $alertType = 'error';
                $alertTitle = 'Verification Failed';
                $alertHtml = 'The Exam Student ID or Full Name entered does not match the candidate profile registered for <strong>' . htmlspecialchars($email) . '</strong>.<br><br>' .
                             '<small class="text-muted">Please double check your details and try again.</small>';
                $redirectUrl = 'index.php?tab=reset';
            }
        } else {
            $alertType = 'error';
            $alertTitle = 'Candidate Not Found';
            $alertHtml = 'No candidate account is registered with <strong>' . htmlspecialchars($email) . '</strong>.';
            $redirectUrl = 'index.php?tab=reset';
        }
    }
} else {
    header("Location: index.php?tab=reset");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Password Reset | HayZed Exam Portal</title>
    <link rel="stylesheet" href="bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        body {
            background: linear-gradient(135deg, #090e1a 0%, #0f172a 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: system-ui, -apple-system, sans-serif;
        }
    </style>
</head>
<body>
    <script>
        Swal.fire({
            icon: '<?= $alertType ?>',
            title: '<?= $alertTitle ?>',
            html: '<?= addslashes($alertHtml) ?>',
            confirmButtonColor: '<?= ($alertType === "success") ? "#10b981" : "#2563eb" ?>',
            confirmButtonText: '<?= ($alertType === "success") ? "Proceed to Login" : "Back to Reset" ?>',
            timer: <?= ($alertType === "success") ? "2500" : "null" ?>,
            timerProgressBar: <?= ($alertType === "success") ? "true" : "false" ?>,
            allowOutsideClick: false
        }).then(() => {
            window.location.href = '<?= $redirectUrl ?>';
        });
    </script>
</body>
</html>
