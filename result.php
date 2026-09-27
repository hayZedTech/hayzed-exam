<?php
require_once "db.php";
require_once "exam_api.php";

// Candidate authentication check
if (!isset($_SESSION['stid'])) {
    header("Location: index.php");
    exit;
}

$student_id = $_SESSION['stid'];
$student_name = $_SESSION['name'] ?? 'Candidate';
$student_email = $_SESSION['email'] ?? '';

// Specific submission ID requested or latest
$subId = isset($_GET['sub_id']) ? (int)$_GET['sub_id'] : null;
$submission = null;
$reviewQuestions = [];

if ($subId) {
    try {
        $stmt = $conn->prepare("SELECT * FROM exam_submissions WHERE id = :id AND student_id = :stid");
        $stmt->execute([':id' => $subId, ':stid' => $student_id]);
        $submission = $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (Exception $e) {}
}

if (!$submission && isset($_SESSION['last_result'])) {
    $submission = $_SESSION['last_result'];
    $reviewQuestions = $submission['details'] ?? [];
} elseif ($submission) {
    $reviewQuestions = !empty($submission['details']) ? json_decode($submission['details'], true) : [];
} else {
    // Attempt to fetch most recent submission
    try {
        $stmt = $conn->prepare("SELECT * FROM exam_submissions WHERE student_id = :stid ORDER BY created_at DESC LIMIT 1");
        $stmt->execute([':stid' => $student_id]);
        $submission = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($submission && !empty($submission['details'])) {
            $reviewQuestions = json_decode($submission['details'], true);
        }
    } catch (Exception $e) {}
}

// Fetch all candidate past records for portfolio overview
$allSubmissions = [];
try {
    $stmtAll = $conn->prepare("SELECT * FROM exam_submissions WHERE student_id = :stid ORDER BY created_at DESC");
    $stmtAll->execute([':stid' => $student_id]);
    $allSubmissions = $stmtAll->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {}

// Standard WAEC / NECO Grade calculation
function calculateGrade($percentage) {
    if ($percentage >= 75) return ['grade' => 'A1', 'label' => 'Distinction', 'color' => '#10b981', 'badge' => 'success'];
    if ($percentage >= 70) return ['grade' => 'B2', 'label' => 'Very Good', 'color' => '#059669', 'badge' => 'success'];
    if ($percentage >= 65) return ['grade' => 'B3', 'label' => 'Good', 'color' => '#0284c7', 'badge' => 'primary'];
    if ($percentage >= 60) return ['grade' => 'C4', 'label' => 'Credit', 'color' => '#2563eb', 'badge' => 'primary'];
    if ($percentage >= 55) return ['grade' => 'C5', 'label' => 'Credit', 'color' => '#3b82f6', 'badge' => 'info'];
    if ($percentage >= 50) return ['grade' => 'C6', 'label' => 'Credit', 'color' => '#6366f1', 'badge' => 'info'];
    if ($percentage >= 45) return ['grade' => 'D7', 'label' => 'Pass', 'color' => '#f59e0b', 'badge' => 'warning'];
    if ($percentage >= 40) return ['grade' => 'E8', 'label' => 'Pass', 'color' => '#d97706', 'badge' => 'warning'];
    return ['grade' => 'F9', 'label' => 'Fail', 'color' => '#ef4444', 'badge' => 'danger'];
}

$score = $submission['score'] ?? 0;
$total = $submission['total_questions'] ?? 10;
$percentage = $submission['percentage'] ?? round(($score / max($total, 1)) * 100, 2);
$gradeInfo = calculateGrade($percentage);
$subjectTitle = ExamEngine::getSubjectName($submission['subject'] ?? 'Biology');
$examTypeName = ExamEngine::getExamTypeName($submission['exam_type'] ?? 'WAEC');
$examYear = $submission['year'] ?? 2023;
$examDate = !empty($submission['created_at']) ? date('F d, Y • h:i A', strtotime($submission['created_at'])) : date('F d, Y • h:i A');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Official Examination Result Slip | HayZed CBT</title>
    <!-- Favicon -->
    <link rel="icon" type="image/svg+xml" href="favicon.svg">
    <link rel="alternate icon" href="favicon.ico">
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="bootstrap.min.css">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- Canvas Confetti -->
    <script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.6.0/dist/confetti.browser.min.js"></script>
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        :root {
            --brand-primary: #2563eb;
            --brand-dark: #090e1a;
        }

        body {
            font-family: 'Plus Jakarta Sans', system-ui, -apple-system, sans-serif;
            background: #f1f5f9;
            color: #1e293b;
            min-height: 100vh;
        }

        .result-nav {
            background: #090e1a;
            border-bottom: 2px solid #2563eb;
            padding: 0.85rem 0;
            color: #fff;
        }

        /* Printable Slip Card */
        .slip-card {
            background: #ffffff;
            border-radius: 1.25rem;
            border: 1px solid #e2e8f0;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.06);
            padding: 2.5rem;
            position: relative;
            overflow: hidden;
            margin-bottom: 2rem;
        }

        .slip-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 6px;
            background: linear-gradient(90deg, #2563eb, #06b6d4, #10b981);
        }

        .slip-header {
            border-bottom: 2px solid #f1f5f9;
            padding-bottom: 1.5rem;
            margin-bottom: 1.75rem;
        }

        .score-circle {
            width: 140px;
            height: 140px;
            border-radius: 50%;
            background: radial-gradient(circle, #ffffff 60%, #f8fafc 100%);
            border: 6px solid <?= $gradeInfo['color'] ?>;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.08);
            margin: auto;
        }

        .score-num {
            font-size: 2.2rem;
            font-weight: 800;
            color: <?= $gradeInfo['color'] ?>;
            line-height: 1;
        }

        .grade-badge-lg {
            font-size: 1.5rem;
            font-weight: 800;
            padding: 0.35rem 1.25rem;
            border-radius: 9999px;
            display: inline-block;
            background: <?= $gradeInfo['color'] ?>15;
            color: <?= $gradeInfo['color'] ?>;
            border: 1.5px solid <?= $gradeInfo['color'] ?>40;
        }

        /* Review Items */
        .review-card {
            background: #ffffff;
            border-radius: 0.75rem;
            border: 1px solid #e2e8f0;
            margin-bottom: 1rem;
            padding: 1.25rem 1.5rem;
            transition: all 0.2s;
        }

        .review-card.correct {
            border-left: 5px solid #10b981;
        }

        .review-card.incorrect {
            border-left: 5px solid #ef4444;
        }

        .opt-review-tag {
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            padding: 0.35rem 0.8rem;
            border-radius: 0.5rem;
            font-size: 0.9rem;
            font-weight: 600;
        }

        /* Print Media Styles */
        @media print {
            body {
                background: #ffffff !important;
                color: #000000 !important;
            }
            .no-print, .result-nav, footer {
                display: none !important;
            }
            .slip-card {
                box-shadow: none !important;
                border: 2px solid #000 !important;
                padding: 1.5rem !important;
                margin: 0 !important;
            }
        }
    </style>
</head>
<body>

    <!-- Top Navigation (No Print) -->
    <nav class="result-nav no-print">
        <div class="container d-flex align-items-center justify-content-between">
            <a href="subjects.php" class="text-white text-decoration-none fw-bold d-flex align-items-center gap-2">
                <i class="bi bi-mortarboard-fill text-primary fs-4"></i>
                <span>HayZed CBT Portal</span>
            </a>
            <div class="d-flex align-items-center gap-2">
                <a href="subjects.php" class="btn btn-outline-light btn-sm rounded-pill px-3 py-1">
                    <i class="bi bi-grid me-1"></i> Examination Hub
                </a>
                <button type="button" onclick="window.print()" class="btn btn-primary btn-sm rounded-pill px-3 py-1 fw-semibold">
                    <i class="bi bi-printer me-1"></i> Print Result Slip
                </button>
            </div>
        </div>
    </nav>

    <!-- Main Container -->
    <main class="container my-4 my-md-5">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                
                <!-- Printable Result Slip -->
                <div class="slip-card">
                    
                    <!-- Header -->
                    <div class="slip-header text-center">
                        <div class="d-inline-flex align-items-center gap-2 px-3 py-1 rounded-pill bg-light border text-muted small mb-2 fw-semibold">
                            <i class="bi bi-patch-check-fill text-success"></i> Official Online Statement of Result
                        </div>
                        <h2 class="fw-bolder mb-1 text-dark">
                            HayZed Computer Based Testing Platform
                        </h2>
                        <h5 class="text-primary fw-bold mb-0">
                            <?= htmlspecialchars($examTypeName) ?> &bull; <?= $examYear ?> Session
                        </h5>
                    </div>

                    <!-- Candidate & Paper Info Grid -->
                    <div class="row g-3 p-3 bg-light rounded-3 border mb-4">
                        <div class="col-md-6">
                            <div class="small text-muted text-uppercase fw-bold">Candidate Name</div>
                            <div class="fs-5 fw-bold text-dark"><?= htmlspecialchars($student_name) ?></div>
                        </div>
                        <div class="col-md-3">
                            <div class="small text-muted text-uppercase fw-bold">Student Exam ID</div>
                            <div class="fs-5 fw-bold text-primary font-monospace"><?= htmlspecialchars($student_id) ?></div>
                        </div>
                        <div class="col-md-3">
                            <div class="small text-muted text-uppercase fw-bold">Examination Date</div>
                            <div class="small fw-semibold text-dark mt-1"><?= $examDate ?></div>
                        </div>
                        <div class="col-md-6">
                            <div class="small text-muted text-uppercase fw-bold">Registered Email</div>
                            <div class="fw-semibold text-secondary"><?= htmlspecialchars($student_email) ?></div>
                        </div>
                        <div class="col-md-6">
                            <div class="small text-muted text-uppercase fw-bold">Subject Paper</div>
                            <div class="fw-bold text-dark fs-5"><i class="bi bi-book-half text-primary me-1"></i> <?= htmlspecialchars($subjectTitle) ?></div>
                        </div>
                    </div>

                    <!-- Scorecard Banner -->
                    <div class="row align-items-center text-center gy-4 my-2">
                        <div class="col-md-4">
                            <div class="score-circle">
                                <span class="score-num"><?= $score ?></span>
                                <small class="text-muted fw-bold">OUT OF <?= $total ?></small>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <small class="text-muted text-uppercase fw-bold d-block mb-1">Percentage Score</small>
                            <h1 class="display-4 fw-bolder mb-0" style="color: <?= $gradeInfo['color'] ?>;">
                                <?= $percentage ?>%
                            </h1>
                            <span class="badge bg-<?= $gradeInfo['badge'] ?> px-3 py-1 rounded-pill mt-1">
                                Assessment <?= ($percentage >= 50) ? 'Passed' : 'Requires Improvement' ?>
                            </span>
                        </div>
                        <div class="col-md-4">
                            <small class="text-muted text-uppercase fw-bold d-block mb-1">Official Grade</small>
                            <div class="grade-badge-lg">
                                <?= $gradeInfo['grade'] ?>
                            </div>
                            <div class="fw-bold text-dark mt-2"><?= $gradeInfo['label'] ?></div>
                        </div>
                    </div>

                    <!-- Official Verification Seal -->
                    <div class="p-3 bg-light rounded-3 border text-center my-4 small text-muted">
                        <i class="bi bi-shield-check text-success fs-5 me-1 align-middle"></i>
                        <span>This electronically generated result statement is validated and recorded on the assessment server database.</span>
                    </div>

                    <!-- Question-by-Question Deep Review -->
                    <?php if (!empty($reviewQuestions)): ?>
                        <div class="mt-4 pt-3 border-top">
                            <h5 class="fw-bold text-dark mb-3">
                                <i class="bi bi-card-checklist text-primary me-1"></i> Question-by-Question Performance Review
                            </h5>

                            <?php foreach ($reviewQuestions as $item): 
                                $isCorrect = !empty($item['is_correct']);
                                $userLetter = strtoupper($item['user_choice'] ?? 'N/A');
                                $correctLetter = strtoupper($item['correct_answer'] ?? '');
                            ?>
                                <div class="review-card <?= $isCorrect ? 'correct' : 'incorrect' ?>">
                                    <div class="d-flex align-items-center justify-content-between mb-2">
                                        <span class="fw-bold text-dark">
                                            Question <?= $item['q_num'] ?>
                                        </span>
                                        <?php if ($isCorrect): ?>
                                            <span class="badge bg-success-subtle text-success border border-success-subtle px-3 py-1 rounded-pill fw-bold">
                                                <i class="bi bi-check-circle-fill me-1"></i> Correct (+1)
                                            </span>
                                        <?php else: ?>
                                            <span class="badge bg-danger-subtle text-danger border border-danger-subtle px-3 py-1 rounded-pill fw-bold">
                                                <i class="bi bi-x-circle-fill me-1"></i> Incorrect (0)
                                            </span>
                                        <?php endif; ?>
                                    </div>

                                    <p class="fw-semibold text-dark mb-3">
                                        <?= nl2br(htmlspecialchars($item['question'])) ?>
                                    </p>

                                    <div class="d-flex flex-wrap gap-2 mb-3">
                                        <div class="opt-review-tag <?= $isCorrect ? 'bg-success-subtle text-success border border-success-subtle' : 'bg-danger-subtle text-danger border border-danger-subtle' ?>">
                                            <span>Your Answer:</span>
                                            <strong>Option <?= $userLetter ?></strong>
                                        </div>

                                        <div class="opt-review-tag bg-primary-subtle text-primary border border-primary-subtle">
                                            <span>Correct Answer:</span>
                                            <strong>Option <?= $correctLetter ?></strong>
                                        </div>
                                    </div>

                                    <?php if (!empty($item['explanation'])): ?>
                                        <div class="p-2 px-3 rounded bg-light border text-secondary small">
                                            <strong class="text-dark"><i class="bi bi-lightbulb-fill text-warning me-1"></i> Solution &amp; Explanation:</strong><br>
                                            <?= htmlspecialchars($item['explanation']) ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>

                    <!-- Action Buttons (No Print) -->
                    <div class="d-flex flex-column flex-sm-row align-items-center justify-content-center gap-3 mt-4 pt-3 border-top no-print">
                        <button type="button" onclick="window.print()" class="btn btn-outline-dark rounded-pill px-4 py-2 fw-bold">
                            <i class="bi bi-printer me-1"></i> Print / Save Slip
                        </button>
                        <a href="subjects.php" class="btn btn-primary rounded-pill px-4 py-2 fw-bold">
                            <i class="bi bi-arrow-repeat me-1"></i> Take Another Examination
                        </a>
                    </div>

                </div> <!-- end slip-card -->

            </div>
        </div>
    </main>

    <!-- Bootstrap JS Bundle -->
    <script src="bootstrap.bundle.min.js"></script>

    <!-- Confetti Script for good scores -->
    <?php if ($percentage >= 60): ?>
    <script>
        window.addEventListener('load', () => {
            confetti({
                particleCount: 120,
                spread: 70,
                origin: { y: 0.6 }
            });
        });
    </script>
    <?php endif; ?>
    <script>
        // Prevent Back-button re-POST or returning to submitted exam
        if (window.history && window.history.pushState) {
            window.history.pushState(null, document.title, window.location.href);
            window.addEventListener('popstate', function () {
                window.location.replace('subjects.php');
            });
        }
    </script>
</body>
</html>
