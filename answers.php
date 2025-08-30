<?php
include_once "db.php";

// Function to insert answers safely
function insertAnswers($conn, $table, $student_id, $email, $name, $status, $answers) {
    $sql = "INSERT INTO {$table}
        (student_id, email, name, status, one, two, three, four, five, six, seven, eight, nine, ten)
        VALUES (:student_id, :email, :name, :status, :one, :two, :three, :four, :five, :six, :seven, :eight, :nine, :ten)";
    
    $stmt = $conn->prepare($sql);
    $stmt->execute([
        ':student_id' => $student_id,
        ':email' => $email,
        ':name' => $name,
        ':status' => $status,
        ':one' => $answers['one'],
        ':two' => $answers['two'],
        ':three' => $answers['three'],
        ':four' => $answers['four'],
        ':five' => $answers['five'],
        ':six' => $answers['six'],
        ':seven' => $answers['seven'],
        ':eight' => $answers['eight'],
        ':nine' => $answers['nine'],
        ':ten' => $answers['ten'],
    ]);
}

// Shared student info
$student_id = $_SESSION['stid'] ?? null;
$email = $_SESSION['email'] ?? null;
$name = $_SESSION['name'] ?? null;
$status = "Submitted";

// Handle Biology
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['bio'])) {
    insertAnswers($conn, "biology01", $student_id, $email, $name, $status, $_POST);
    echo "<script>alert('Biology Submitted'); window.location='result.php';</script>";
}

// Handle English
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['eng'])) {
    insertAnswers($conn, "english01", $student_id, $email, $name, $status, $_POST);
    echo "<script>alert('English Submitted'); window.location='result.php';</script>";
}

// Handle Geography
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['geo'])) {
    insertAnswers($conn, "geography01", $student_id, $email, $name, $status, $_POST);
    echo "<script>alert('Geography Submitted'); window.location='result.php';</script>";
}
?>
