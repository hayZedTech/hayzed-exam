<?php
require_once "db.php";
require_once "styles.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {
    $student_id = $_SESSION['stid'];
    $sub = $_POST['sub'];

    if ($sub == "bio") {
        $stmt = $conn->prepare("SELECT student_id FROM biology01 WHERE student_id = :stid");
        $stmt->execute(['stid' => $student_id]);

        if ($stmt->rowCount() > 0) {
            echo "<script>alert('Biology has already been submitted'); window.location='subjects.php';</script>";
        } else {
            echo "<script>alert('You have 20 minutes, press Ok to start!'); window.location='biology.php';</script>";
        }

    } elseif ($sub == "eng") {
        $stmt = $conn->prepare("SELECT student_id FROM english01 WHERE student_id = :stid");
        $stmt->execute(['stid' => $student_id]);

        if ($stmt->rowCount() > 0) {
            echo "<script>alert('English Language has already been submitted'); window.location='subjects.php';</script>";
        } else {
            echo "<script>alert('You have 20 minutes, press Ok to start!'); window.location='english.php';</script>";
        }

    } elseif ($sub == "geo") {
        $stmt = $conn->prepare("SELECT student_id FROM geography01 WHERE student_id = :stid");
        $stmt->execute(['stid' => $student_id]);

        if ($stmt->rowCount() > 0) {
            echo "<script>alert('Geography has already been submitted'); window.location='subjects.php';</script>";
        } else {
            echo "<script>alert('You have 20 minutes, press Ok to start!'); window.location='geography.php';</script>";
        }

    } elseif ($sub == "math") {
        echo "<script>alert('Mathematics is not ready yet!!!'); window.location='subjects.php';</script>";
    }
}
?>

<?php require_once "topbar.php"; ?>

<form action="" method="post" class="p-4 m-3">
    <h1>Choose your subject</h1>
    <ul class="subjects list-unstyled fw-bold h5 text-primary">
        <li><input type="radio" name="sub" value="bio" class="my-2" required> Biology</li>
        <li><input type="radio" name="sub" value="eng" class="my-2" required> English</li>
        <li><input type="radio" name="sub" value="geo" class="my-2" required> Geography</li>
        <li><input type="radio" name="sub" value="math" class="my-2" required> Mathematics</li>
    </ul>
    <center>
        <button class="btn btn-primary px-5 mt-5 fw-bold" name="submit">Start your Exam</button>
    </center>
</form>
</body>
</html>
