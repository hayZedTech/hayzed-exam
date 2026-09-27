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
    $name = cleanData($_POST['name'] ?? '');
    $email = strtolower(cleanData($_POST['email'] ?? ''));
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';

    // Field validations
    if (empty($name) || empty($email) || empty($password)) {
        $alertType = 'warning';
        $alertTitle = 'Incomplete Information';
        $alertHtml = 'All fields are required. Please provide your Full Name, Email Address, and Password.';
        $redirectUrl = 'index.php';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $alertType = 'warning';
        $alertTitle = 'Invalid Email Format';
        $alertHtml = 'The email address <strong>' . htmlspecialchars($email) . '</strong> is not valid. Please enter a valid email.';
        $redirectUrl = 'index.php';
    } elseif (strlen($password) < 6) {
        $alertType = 'warning';
        $alertTitle = 'Password Too Short';
        $alertHtml = 'For your security, your password must contain at least <strong>6 characters</strong>.';
        $redirectUrl = 'index.php';
    } elseif ($password !== $confirm_password) {
        $alertType = 'warning';
        $alertTitle = 'Passwords Do Not Match';
        $alertHtml = 'The password and confirmation password do not match. Please verify and try again.';
        $redirectUrl = 'index.php';
    } else {
        // Strict duplicate account check (case-insensitive email)
        $sel = "SELECT id, email, student_id FROM exam_student_prof01 WHERE LOWER(email) = LOWER(:email) LIMIT 1";
        $stmt = $conn->prepare($sel);
        $stmt->execute([':email' => $email]);

        if ($stmt->rowCount() > 0) {
            $alertType = 'warning';
            $alertTitle = 'Account Already Exists!';
            $alertHtml = 'A candidate account with <strong>' . htmlspecialchars($email) . '</strong> is already registered.<br><br>' .
                         '<small class="text-muted">Duplicate accounts are strictly disallowed. Please sign in using your existing account, or use "Forgot Password" if you need to reset your password.</small>';
            $redirectUrl = 'index.php';
        } else {
            // Generate guaranteed unique student exam ID
            $isUnique = false;
            $student_id = '';
            $attempts = 0;

            do {
                $uniqueHex = strtoupper(substr(md5(uniqid(mt_rand(), true)), 0, 7));
                $candidateId = date('y') . "/" . $uniqueHex;

                $chk = $conn->prepare("SELECT id FROM exam_student_prof01 WHERE student_id = :sid");
                $chk->execute([':sid' => $candidateId]);
                if ($chk->rowCount() === 0) {
                    $student_id = $candidateId;
                    $isUnique = true;
                }
                $attempts++;
            } while (!$isUnique && $attempts < 10);

            if (!$isUnique) {
                $student_id = date('y') . "/" . strtoupper(substr(uniqid(), -7));
            }

            // Secure password hash
            $hashedPassword = password_hash($password, PASSWORD_BCRYPT, ['cost' => 12]);

            try {
                // Insert new candidate record
                $ins = "INSERT INTO exam_student_prof01 (student_id, name, email, password, created_at) 
                        VALUES (:student_id, :name, :email, :password, NOW())";
                $insStmt = $conn->prepare($ins);
                $insStmt->execute([
                    ':student_id' => $student_id,
                    ':name' => $name,
                    ':email' => $email,
                    ':password' => $hashedPassword
                ]);

                // Set candidate session
                $_SESSION['stid'] = $student_id;
                $_SESSION['email'] = $email;
                $_SESSION['name'] = $name;

                $alertType = 'success';
                $alertTitle = 'Registration Successful!';
                $alertHtml = 'Welcome, <strong>' . htmlspecialchars($name) . '</strong>!<br><br>' .
                             '<div class="p-3 my-2 rounded bg-light border text-center">' .
                             '<small class="text-muted text-uppercase fw-bold">Your Official Exam ID</small><br>' .
                             '<span class="fs-4 fw-bolder text-primary font-monospace">' . htmlspecialchars($student_id) . '</span>' .
                             '</div>' .
                             '<small class="text-muted">Keep this ID safe. You are now logged in and ready to test.</small>';
                $redirectUrl = 'subjects.php';
            } catch (PDOException $ex) {
                if (strpos($ex->getMessage(), 'unique') !== false || strpos($ex->getMessage(), 'duplicate') !== false) {
                    $alertType = 'warning';
                    $alertTitle = 'Duplicate Registration Prevented';
                    $alertHtml = 'An account with this email address was just registered. Please sign in instead.';
                    $redirectUrl = 'index.php';
                } else {
                    $alertType = 'error';
                    $alertTitle = 'Registration Error';
                    $alertHtml = 'Unable to complete your registration at this time. Please try again.';
                    $redirectUrl = 'index.php';
                }
            }
        }
    }
} else {
    header("Location: index.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration | HayZed Exam Portal</title>
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
            confirmButtonText: '<?= ($alertType === "success") ? "Proceed to Exam Portal" : "Back to Portal" ?>',
            timer: <?= ($alertType === "success") ? "2800" : "null" ?>,
            timerProgressBar: <?= ($alertType === "success") ? "true" : "false" ?>,
            allowOutsideClick: false
        }).then(() => {
            window.location.href = '<?= $redirectUrl ?>';
        });
    </script>
</body>
</html>
