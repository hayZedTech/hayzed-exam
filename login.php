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
    $password = $_POST['password'] ?? '';

    if (empty($email)) {
        $alertType = 'warning';
        $alertTitle = 'Email Required';
        $alertHtml = 'Please enter your registered email address.';
        $redirectUrl = 'index.php';
    } elseif (empty($password)) {
        $alertType = 'warning';
        $alertTitle = 'Password Required';
        $alertHtml = 'Please enter your account password to proceed.';
        $redirectUrl = 'index.php';
    } else {
        // Query candidate profile (case-insensitive email matching)
        $sel = "SELECT * FROM exam_student_prof01 WHERE LOWER(email) = LOWER(:email) LIMIT 1";
        $stmt = $conn->prepare($sel);
        $stmt->execute([':email' => $email]);

        if ($stmt->rowCount() > 0) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            // Check if password exists in the database
            if (!empty($row['password'])) {
                // Verify against secure hash
                if (password_verify($password, $row['password'])) {
                    // Successful login
                    $_SESSION['stid'] = $row["student_id"];
                    $_SESSION['email'] = $row["email"];
                    $_SESSION['name'] = $row["name"];

                    $alertType = 'success';
                    $alertTitle = 'Authentication Successful!';
                    $alertHtml = 'Welcome back, <strong>' . htmlspecialchars($row["name"]) . '</strong>.<br><small class="text-muted">Exam ID: ' . htmlspecialchars($row["student_id"]) . '</small>';
                    $redirectUrl = 'subjects.php';
                } else {
                    $alertType = 'error';
                    $alertTitle = 'Incorrect Password';
                    $alertHtml = 'The password you entered is incorrect.<br><small class="text-muted">If you cannot recall your password, click "Forgot Password" on the login screen to reset it.</small>';
                    $redirectUrl = 'index.php';
                }
            } else {
                // Existing account with no password set yet (auto-set candidate password)
                if (strlen($password) >= 6) {
                    $hashed = password_hash($password, PASSWORD_BCRYPT, ['cost' => 12]);
                    $upd = $conn->prepare("UPDATE exam_student_prof01 SET password = :pw WHERE id = :id");
                    $upd->execute([':pw' => $hashed, ':id' => $row['id']]);

                    $_SESSION['stid'] = $row["student_id"];
                    $_SESSION['email'] = $row["email"];
                    $_SESSION['name'] = $row["name"];

                    $alertType = 'success';
                    $alertTitle = 'Password Set &amp; Verified!';
                    $alertHtml = 'Welcome, <strong>' . htmlspecialchars($row["name"]) . '</strong>.<br>Your password has been securely registered to your candidate profile.<br><small class="text-muted">Exam ID: ' . htmlspecialchars($row["student_id"]) . '</small>';
                    $redirectUrl = 'subjects.php';
                } else {
                    $alertType = 'warning';
                    $alertTitle = 'Password Setup Required';
                    $alertHtml = 'Please enter a password with at least <strong>6 characters</strong> to secure your candidate profile.';
                    $redirectUrl = 'index.php';
                }
            }
        } else {
            $alertType = 'error';
            $alertTitle = 'Candidate Not Found';
            $alertHtml = 'No candidate account is registered with <strong>' . htmlspecialchars($email) . '</strong>.<br><small class="text-muted">Please double check your email address or register a new candidate account.</small>';
            $redirectUrl = 'index.php';
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
    <title>Authenticating | HayZed Exam Portal</title>
    <!-- Favicon -->
    <link rel="icon" type="image/svg+xml" href="favicon.svg">
    <link rel="alternate icon" href="favicon.ico">
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
            confirmButtonText: '<?= ($alertType === "success") ? "Continue to Examination Portal" : "Back to Login" ?>',
            timer: <?= ($alertType === "success") ? "2200" : "null" ?>,
            timerProgressBar: <?= ($alertType === "success") ? "true" : "false" ?>
        }).then(() => {
            window.location.href = '<?= $redirectUrl ?>';
        });
    </script>
</body>
</html>
