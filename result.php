<?php 
include_once "db.php";
require_once "styles.php";

if (isset($_POST['exit1'])) {
    echo "<script>window.location='subjects.php';</script>";
    exit;
}
?>

<style>
    .display_result {
        width: 300px;
        height: 200px;
        box-shadow: 0 0 10px 5px skyblue;
        margin: 60px auto;
        padding: 20px;
        background-color: white;
    }
</style>

<?php require_once "topbar.php"; ?>

<div class="display_result">
<?php
$stid = $_SESSION['stid'] ?? null;

if ($stid) {
    // function to fetch results
    function fetchResult($conn, $table, $stid, $subjectName) {
        $sel = "SELECT * FROM {$table} WHERE student_id = :stid";
        $stmt = $conn->prepare($sel);
        $stmt->execute([':stid' => $stid]);

        if ($stmt->rowCount() > 0) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            // sum all question fields
            $score = 0;
            for ($i = 1; $i <= 10; $i++) {
                $score += (int)$row[(string)$i === "10" ? "ten" : strtolower(numToWord($i))];
            }

            echo "<h4>{$subjectName} : <span style='color:red;'>{$score}/10</span></h4>";
        } else {
            echo "<h4>{$subjectName}: <span style='color:red;'>Not found</span></h4>";
        }
    }

    // helper: convert number to word (to match db column names)
    function numToWord($num) {
        $map = [
            1 => "one", 2 => "two", 3 => "three", 4 => "four", 5 => "five",
            6 => "six", 7 => "seven", 8 => "eight", 9 => "nine", 10 => "ten"
        ];
        return $map[$num] ?? $num;
    }

    // Subjects
    fetchResult($conn, "biology01", $stid, "Biology");
    fetchResult($conn, "english01", $stid, "English Language");
    fetchResult($conn, "geography01", $stid, "Geography");
}
?>
</div>
<body></body>
</html>
