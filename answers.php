<?php
include_once "db.php";
require_once "exam_api.php";

// Ensure candidate is authenticated
$student_id = $_SESSION['stid'] ?? null;
$email = $_SESSION['email'] ?? null;
$name = $_SESSION['name'] ?? null;

if (!$student_id) {
    header("Location: index.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Determine subject and exam parameters
    $subjectRaw = $_POST['subject'] ?? '';
    if (empty($subjectRaw)) {
        if (isset($_POST['bio'])) $subjectRaw = 'biology';
        elseif (isset($_POST['eng'])) $subjectRaw = 'english';
        elseif (isset($_POST['geo'])) $subjectRaw = 'geography';
        else $subjectRaw = 'biology';
    }

    $subjectSlug = ExamEngine::normalizeSubject($subjectRaw);
    $examType = strtolower($_POST['exam_type'] ?? 'waec');
    $year = (int)($_POST['year'] ?? 2023);
    $mode = strtolower($_POST['mode'] ?? 'standard');
    $timeSpent = (int)($_POST['time_spent'] ?? 0);
    $totalQuestionsParam = (int)($_POST['total_questions'] ?? 0);

    // Conclude and clear authoritative active exam session
    $sessionKey = $_POST['session_key'] ?? md5("exam_{$student_id}_{$subjectSlug}_{$examType}_{$year}_{$mode}");
    if (isset($_SESSION['active_exams'][$sessionKey])) {
        $sessData = $_SESSION['active_exams'][$sessionKey];
        $startedAt = $sessData['start_time'] ?? (time() - $timeSpent);
        $serverTimeSpent = time() - $startedAt;
        if ($serverTimeSpent > 0) {
            $timeSpent = $serverTimeSpent;
        }
        unset($_SESSION['active_exams'][$sessionKey]);
    }

    if ($totalQuestionsParam <= 0) {
        $std = ExamEngine::getExamStandard($examType, $subjectSlug, $mode);
        $totalQuestionsParam = $std['questions'];
    }

    // Fetch official questions for scoring matching the exact count submitted
    $questions = ExamEngine::getQuestions($examType, $subjectSlug, $year, $totalQuestionsParam);
    $totalQuestions = count($questions);

    $score = 0;
    $reviewDetails = [];
    $legacyAnswers = [];

    // Map numbers to words for legacy table compatibility
    $numWordMap = [
        1 => 'one', 2 => 'two', 3 => 'three', 4 => 'four', 5 => 'five',
        6 => 'six', 7 => 'seven', 8 => 'eight', 9 => 'nine', 10 => 'ten'
    ];

    foreach ($questions as $idx => $q) {
        $qNum = $idx + 1;
        // Check new format (q_1) or legacy format (one)
        $userChoice = $_POST["q_{$qNum}"] ?? ($_POST[$numWordMap[$qNum]] ?? null);
        $userChoice = $userChoice !== null ? strtolower(trim((string)$userChoice)) : null;

        $correctAnswer = strtolower(trim($q['answer']));
        $isCorrect = false;

        // Legacy values were numeric 1/0, new values are letter a/b/c/d
        if ($userChoice !== null) {
            if ($userChoice === $correctAnswer || $userChoice === '1') {
                $isCorrect = true;
                $score++;
            }
        }

        $legacyAnswers[$numWordMap[$qNum]] = $isCorrect ? '1' : '0';

        $reviewDetails[] = [
            'q_num' => $qNum,
            'question' => $q['question'],
            'options' => $q['options'],
            'user_choice' => $userChoice,
            'correct_answer' => $correctAnswer,
            'is_correct' => $isCorrect,
            'explanation' => $q['explanation'] ?? ''
        ];
    }

    $percentage = ($totalQuestions > 0) ? round(($score / $totalQuestions) * 100, 2) : 0;
    $detailsJson = json_encode($reviewDetails);
    $submissionId = null;

    // 1. Insert into modern exam_submissions table
    try {
        $ins = "INSERT INTO exam_submissions 
                (student_id, email, name, exam_type, subject, year, score, total_questions, percentage, details)
                VALUES (:student_id, :email, :name, :exam_type, :subject, :year, :score, :total_questions, :percentage, :details)
                RETURNING id";
        $stmt = $conn->prepare($ins);
        $stmt->execute([
            ':student_id' => $student_id,
            ':email' => $email,
            ':name' => $name,
            ':exam_type' => $examType,
            ':subject' => $subjectSlug,
            ':year' => $year,
            ':score' => $score,
            ':total_questions' => $totalQuestions,
            ':percentage' => $percentage,
            ':details' => $detailsJson
        ]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $submissionId = $row['id'] ?? null;
    } catch (Exception $e) {
        // Fallback without RETURNING if needed
        try {
            $ins = "INSERT INTO exam_submissions 
                    (student_id, email, name, exam_type, subject, year, score, total_questions, percentage, details)
                    VALUES (:student_id, :email, :name, :exam_type, :subject, :year, :score, :total_questions, :percentage, :details)";
            $stmt = $conn->prepare($ins);
            $stmt->execute([
                ':student_id' => $student_id,
                ':email' => $email,
                ':name' => $name,
                ':exam_type' => $examType,
                ':subject' => $subjectSlug,
                ':year' => $year,
                ':score' => $score,
                ':total_questions' => $totalQuestions,
                ':percentage' => $percentage,
                ':details' => $detailsJson
            ]);
        } catch (Exception $ex) {
            // Log error silently
        }
    }

    // 2. Also populate legacy subject table if applicable for complete backward compatibility
    $legacyTableMap = [
        'biology' => 'exam_biology01',
        'english' => 'exam_english01',
        'geography' => 'exam_geography01'
    ];
    if (isset($legacyTableMap[$subjectSlug])) {
        $tbl = $legacyTableMap[$subjectSlug];
        try {
            $sql = "INSERT INTO {$tbl} 
                    (student_id, email, name, status, one, two, three, four, five, six, seven, eight, nine, ten)
                    VALUES (:student_id, :email, :name, :status, :one, :two, :three, :four, :five, :six, :seven, :eight, :nine, :ten)";
            $stmtLegacy = $conn->prepare($sql);
            $stmtLegacy->execute([
                ':student_id' => $student_id,
                ':email' => $email,
                ':name' => $name,
                ':status' => 'Submitted',
                ':one' => $legacyAnswers['one'],
                ':two' => $legacyAnswers['two'],
                ':three' => $legacyAnswers['three'],
                ':four' => $legacyAnswers['four'],
                ':five' => $legacyAnswers['five'],
                ':six' => $legacyAnswers['six'],
                ':seven' => $legacyAnswers['seven'],
                ':eight' => $legacyAnswers['eight'],
                ':nine' => $legacyAnswers['nine'],
                ':ten' => $legacyAnswers['ten'],
            ]);
        } catch (Exception $e) {
            // Ignore legacy constraint collisions
        }
    }

    // Cache latest result in session
    $_SESSION['last_result'] = [
        'id' => $submissionId,
        'student_id' => $student_id,
        'name' => $name,
        'exam_type' => strtoupper($examType),
        'subject' => $subjectSlug,
        'subject_title' => ExamEngine::getSubjectName($subjectSlug),
        'year' => $year,
        'score' => $score,
        'total_questions' => $totalQuestions,
        'percentage' => $percentage,
        'time_spent' => $timeSpent,
        'details' => $reviewDetails,
        'date' => date('Y-m-d H:i:s')
    ];

    $subjectTitle = ExamEngine::getSubjectName($subjectSlug);
    $targetUrl = $submissionId ? "result.php?sub_id={$submissionId}" : "result.php";
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Processing Submission | HayZed Exam Portal</title>
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
            // Clear client answer cache for this session
            try {
                sessionStorage.removeItem('cbt_ans_' + '<?= $sessionKey ?>');
                sessionStorage.removeItem('cbt_flags_' + '<?= $sessionKey ?>');
            } catch(e) {}

            Swal.fire({
                icon: 'success',
                title: '<?= addslashes($subjectTitle) ?> Submitted!',
                html: `
                    <div class="p-3 my-2 bg-light rounded border text-center">
                        <small class="text-muted text-uppercase fw-bold">Candidate Score</small><br>
                        <span class="fs-1 fw-bolder text-success font-monospace"><?= $score ?> / <?= $totalQuestions ?></span>
                        <div class="fw-semibold text-muted small mt-1"><?= $percentage ?>% Overall Performance</div>
                    </div>
                    <small class="text-muted">Loading your official result slip and question review...</small>
                `,
                confirmButtonColor: '#10b981',
                confirmButtonText: '<i class="bi bi-receipt me-1"></i> View Result Slip',
                timer: 2800,
                timerProgressBar: true,
                allowOutsideClick: false
            }).then(() => {
                window.location.href = '<?= $targetUrl ?>';
            });
        </script>
    </body>
    </html>
    <?php
    exit;
} else {
    header("Location: subjects.php");
    exit;
}
?>
