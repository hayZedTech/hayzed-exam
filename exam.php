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

// Retrieve query parameters
$subjectSlug = ExamEngine::normalizeSubject($_GET['subject'] ?? 'biology');
$examType = strtolower($_GET['exam_type'] ?? 'waec');
$year = (int)($_GET['year'] ?? 2023);
$mode = strtolower($_GET['mode'] ?? 'standard');

// Calculate official examination standard question count & duration
$standardInfo = ExamEngine::getExamStandard($examType, $subjectSlug, $mode);
$requestedCount = $standardInfo['questions'];
$durationMinutes = $standardInfo['duration'];
$durationSeconds = $durationMinutes * 60;

// Unique authoritative session token for this specific candidate & exam paper
$sessionKey = md5("exam_{$student_id}_{$subjectSlug}_{$examType}_{$year}_{$mode}");

// Handle Exit Exam request (terminates active session without scoring so candidate can retake later)
if (isset($_GET['action']) && $_GET['action'] === 'exit_exam') {
    if (isset($_SESSION['active_exams'][$sessionKey])) {
        unset($_SESSION['active_exams'][$sessionKey]);
    }
    header("Location: subjects.php?msg=exam_cancelled");
    exit;
}

// Server-anchored timer management:
// Time is authoritatively tracked on the server so that refreshing the browser gains zero extra time.
if (!isset($_SESSION['active_exams'])) {
    $_SESSION['active_exams'] = [];
}

$now = time();

if (!isset($_SESSION['active_exams'][$sessionKey])) {
    // New exam session initiated
    $_SESSION['active_exams'][$sessionKey] = [
        'start_time' => $now,
        'end_time' => $now + $durationSeconds,
        'duration_seconds' => $durationSeconds,
        'subject' => $subjectSlug,
        'exam_type' => $examType,
        'year' => $year,
        'mode' => $mode
    ];
    $remainingSeconds = $durationSeconds;
    $elapsedSeconds = 0;
} else {
    // Ongoing session: candidate refreshed or re-entered the exam room
    $sessionData = $_SESSION['active_exams'][$sessionKey];
    $endTime = (int)$sessionData['end_time'];
    $remainingSeconds = max(0, $endTime - $now);
    $elapsedSeconds = min($sessionData['duration_seconds'], max(0, $sessionData['duration_seconds'] - $remainingSeconds));
}

$displayMins = floor($remainingSeconds / 60);
$displaySecs = $remainingSeconds % 60;
$timerFormatted = sprintf("%02d:%02d", $displayMins, $displaySecs);

// Fetch questions from ExamEngine
$questions = ExamEngine::getQuestions($examType, $subjectSlug, $year, $requestedCount);
$totalQuestions = count($questions);
$subjectTitle = ExamEngine::getSubjectName($subjectSlug);
$examTypeName = ExamEngine::getExamTypeName($examType);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($subjectTitle) ?> Exam | <?= htmlspecialchars($examTypeName) ?> <?= $year ?></title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="bootstrap.min.css">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        :root {
            --brand-primary: #2563eb;
            --brand-dark: #0f172a;
            --option-bg: #f8fafc;
            --option-border: #e2e8f0;
        }

        body {
            font-family: 'Plus Jakarta Sans', system-ui, -apple-system, sans-serif;
            background: #f1f5f9;
            color: #1e293b;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            user-select: none; /* Standard CBT exam anti-cheat UI */
        }

        /* Top Bar */
        .exam-header {
            background: #090e1a;
            color: #fff;
            padding: 0.75rem 0;
            border-bottom: 2px solid #2563eb;
            position: sticky;
            top: 0;
            z-index: 100;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2);
        }

        .exam-brand-badge {
            font-weight: 700;
            font-size: 1.15rem;
            color: #fff;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            text-decoration: none;
        }

        /* Timer Badge */
        .timer-box {
            display: inline-flex;
            align-items: center;
            gap: 0.6rem;
            padding: 0.45rem 1rem;
            border-radius: 9999px;
            font-size: 1.1rem;
            font-weight: 800;
            background: rgba(16, 185, 129, 0.15);
            color: #34d399;
            border: 1.5px solid rgba(52, 211, 153, 0.35);
            font-variant-numeric: tabular-nums;
            letter-spacing: 0.05em;
            transition: all 0.3s ease;
        }

        .timer-box.warning {
            background: rgba(245, 158, 11, 0.15);
            color: #fbbf24;
            border-color: rgba(245, 158, 11, 0.4);
        }

        .timer-box.danger {
            background: rgba(239, 68, 68, 0.2);
            color: #f87171;
            border-color: rgba(239, 68, 68, 0.5);
            animation: pulse-danger 1s infinite;
        }

        @keyframes pulse-danger {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.04); }
        }

        /* Question Stage */
        .question-card {
            background: #ffffff;
            border-radius: 1rem;
            border: 1px solid #e2e8f0;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.04);
            padding: 2rem;
            position: relative;
            min-height: 480px;
            display: flex;
            flex-direction: column;
        }

        .question-meta-bar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding-bottom: 1rem;
            border-bottom: 1px solid #f1f5f9;
            margin-bottom: 1.5rem;
        }

        .question-text {
            font-size: 1.15rem;
            font-weight: 600;
            line-height: 1.6;
            color: #0f172a;
            margin-bottom: 1.75rem;
        }

        /* Options */
        .option-item {
            border: 1.5px solid var(--option-border);
            border-radius: 0.75rem;
            background: var(--option-bg);
            padding: 0.85rem 1.25rem;
            margin-bottom: 0.85rem;
            display: flex;
            align-items: center;
            gap: 1rem;
            cursor: pointer;
            transition: all 0.15s ease-in-out;
        }

        .option-item:hover {
            border-color: #93c5fd;
            background: #f0f7ff;
            transform: translateX(3px);
        }

        .option-item.selected {
            background: #eff6ff;
            border-color: #2563eb;
            box-shadow: 0 0 0 2px rgba(37, 99, 235, 0.2);
        }

        .option-letter {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background: #e2e8f0;
            color: #475569;
            font-weight: 700;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.9rem;
            flex-shrink: 0;
            transition: all 0.15s;
        }

        .option-item.selected .option-letter {
            background: #2563eb;
            color: #fff;
        }

        .option-label {
            font-size: 1rem;
            font-weight: 500;
            color: #334155;
            margin-bottom: 0;
            flex-grow: 1;
        }

        /* Palette Card */
        .palette-card {
            background: #ffffff;
            border-radius: 1rem;
            border: 1px solid #e2e8f0;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.04);
            padding: 1.5rem;
        }

        .palette-grid {
            display: grid;
            grid-template-columns: repeat(5, 1fr);
            gap: 0.5rem;
            margin-top: 1rem;
            margin-bottom: 1.25rem;
        }

        .palette-btn {
            height: 42px;
            border-radius: 0.5rem;
            font-weight: 700;
            font-size: 0.95rem;
            border: 1px solid #e2e8f0;
            background: #f8fafc;
            color: #64748b;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.15s;
        }

        .palette-btn:hover {
            border-color: #93c5fd;
            color: #2563eb;
        }

        .palette-btn.current {
            outline: 2.5px solid #2563eb;
            outline-offset: 1px;
            font-weight: 800;
        }

        .palette-btn.answered {
            background: #2563eb;
            color: #ffffff;
            border-color: #2563eb;
        }

        .palette-btn.flagged {
            background: #f59e0b;
            color: #ffffff;
            border-color: #f59e0b;
        }

        .legend-dot {
            width: 12px;
            height: 12px;
            border-radius: 3px;
            display: inline-block;
        }

        /* Calculator Styles */
        .calc-screen {
            background: #090e1a;
            color: #38bdf8;
            font-family: monospace;
            font-size: 1.75rem;
            text-align: right;
            padding: 0.75rem 1rem;
            border-radius: 0.5rem;
            margin-bottom: 1rem;
            letter-spacing: 0.05em;
            min-height: 58px;
            overflow-x: auto;
        }

        .calc-btn {
            font-size: 1.1rem;
            font-weight: 600;
            padding: 0.65rem 0;
            border-radius: 0.5rem;
        }
    </style>
</head>
<body>

    <!-- Top Examination Nav -->
    <header class="exam-header">
        <div class="container d-flex align-items-center justify-content-between">
            <div class="d-flex align-items-center gap-3">
                <a href="#" class="exam-brand-badge">
                    <i class="bi bi-mortarboard-fill text-primary fs-4"></i>
                    <span class="d-none d-sm-inline">HayZed CBT</span>
                </a>
                <div class="vr bg-secondary d-none d-sm-block" style="height: 24px;"></div>
                <span class="badge bg-primary text-white py-1 px-2 d-none d-md-inline-block">
                    <?= htmlspecialchars($examTypeName) ?>
                </span>
                <span class="fw-bold text-white small">
                    <?= htmlspecialchars($subjectTitle) ?> (<?= $year ?>)
                </span>
            </div>

            <!-- Live Countdown Timer & Actions -->
            <div class="d-flex align-items-center gap-2 gap-md-3">
                <div class="timer-box <?= ($remainingSeconds <= 120 ? 'danger' : ($remainingSeconds <= 300 ? 'warning' : '')) ?>" id="timerBox">
                    <i class="bi bi-clock-history"></i>
                    <span id="timerDisplay"><?= $timerFormatted ?></span>
                </div>

                <button type="button" class="btn btn-outline-light btn-sm rounded-pill px-3 py-1 d-none d-md-inline-flex align-items-center gap-1" data-bs-toggle="modal" data-bs-target="#calculatorModal">
                    <i class="bi bi-calculator"></i>
                    <span>Calc</span>
                </button>

                <button type="button" class="btn btn-outline-light btn-sm rounded-pill px-2 py-1 d-none d-lg-inline-block" onclick="toggleFullScreen()" title="Fullscreen">
                    <i class="bi bi-arrows-fullscreen"></i>
                </button>

                <!-- Exit & Retake Later Button -->
                <button type="button" class="btn btn-outline-danger btn-sm rounded-pill px-3 py-1 fw-bold text-white border-danger d-inline-flex align-items-center gap-1" onclick="confirmExitExam()" title="Exit without submitting to retake later">
                    <i class="bi bi-box-arrow-right text-danger"></i>
                    <span class="d-none d-sm-inline">Exit Exam</span>
                </button>

                <button type="button" class="btn btn-success btn-sm rounded-pill px-3 py-1 fw-bold" onclick="confirmFinalSubmit()">
                    <i class="bi bi-check2-circle me-1"></i> Submit
                </button>
            </div>
        </div>
    </header>

    <!-- Main Workspace -->
    <main class="container my-4 flex-grow-1">
        <form id="examForm" action="answers.php" method="POST">
            <!-- Hidden session & paper tokens -->
            <input type="hidden" name="session_key" value="<?= htmlspecialchars($sessionKey) ?>">
            <input type="hidden" name="exam_type" value="<?= htmlspecialchars($examType) ?>">
            <input type="hidden" name="subject" value="<?= htmlspecialchars($subjectSlug) ?>">
            <input type="hidden" name="year" value="<?= (int)$year ?>">
            <input type="hidden" name="mode" value="<?= htmlspecialchars($mode) ?>">
            <input type="hidden" name="total_questions" value="<?= (int)$totalQuestions ?>">
            <input type="hidden" name="time_spent" id="timeSpentInput" value="<?= (int)$elapsedSeconds ?>">
            <input type="hidden" name="submit_exam" value="1">

            <div class="row g-4">
                
                <!-- Center: Active Question Canvas -->
                <div class="col-lg-8">
                    <div class="question-card">
                        
                        <!-- Question Header Meta -->
                        <div class="question-meta-bar">
                            <div>
                                <span class="badge bg-light text-primary border px-2 py-1 fw-bold" id="questionBadge">
                                    Question 1 of <?= $totalQuestions ?>
                                </span>
                                <span class="badge bg-warning-subtle text-warning border border-warning-subtle ms-2 d-none" id="flagIndicator">
                                    <i class="bi bi-flag-fill me-1"></i> Flagged
                                </span>
                            </div>
                            <div class="small text-muted">
                                Candidate: <strong class="text-dark"><?= htmlspecialchars($student_name) ?></strong> (<?= htmlspecialchars($student_id) ?>)
                            </div>
                        </div>

                        <!-- Question Views Containers -->
                        <?php foreach ($questions as $idx => $q): 
                            $qNum = $idx + 1;
                        ?>
                            <div class="question-pane" id="pane_<?= $qNum ?>" style="<?= ($qNum === 1) ? '' : 'display: none;' ?>">
                                
                                <div class="question-text">
                                    <?= nl2br(htmlspecialchars($q['question'])) ?>
                                </div>

                                <div class="options-container">
                                    <?php foreach ($q['options'] as $key => $optText): 
                                        $optKey = strtolower($key);
                                    ?>
                                        <div class="option-item" id="opt_item_<?= $qNum ?>_<?= $optKey ?>" onclick="selectOption(<?= $qNum ?>, '<?= $optKey ?>')">
                                            <input type="radio" 
                                                   name="q_<?= $qNum ?>" 
                                                   id="opt_input_<?= $qNum ?>_<?= $optKey ?>" 
                                                   value="<?= $optKey ?>" 
                                                   class="d-none">
                                            <div class="option-letter"><?= strtoupper($optKey) ?></div>
                                            <label class="option-label" for="opt_input_<?= $qNum ?>_<?= $optKey ?>">
                                                <?= htmlspecialchars($optText) ?>
                                            </label>
                                        </div>
                                    <?php endforeach; ?>
                                </div>

                            </div>
                        <?php endforeach; ?>

                        <!-- Stage Footer Navigation -->
                        <div class="mt-auto pt-4 d-flex align-items-center justify-content-between border-top">
                            <button type="button" class="btn btn-outline-secondary rounded-pill px-4 fw-semibold" id="prevBtn" onclick="prevQuestion()" disabled>
                                <i class="bi bi-arrow-left me-1"></i> Previous
                            </button>

                            <button type="button" class="btn btn-outline-warning rounded-pill px-3 fw-semibold" id="flagBtn" onclick="toggleFlagCurrentQuestion()">
                                <i class="bi bi-flag me-1"></i> <span id="flagBtnText">Flag for Review</span>
                            </button>

                            <button type="button" class="btn btn-primary rounded-pill px-4 fw-bold" id="nextBtn" onclick="nextQuestion()">
                                Next <i class="bi bi-arrow-right ms-1"></i>
                            </button>
                        </div>

                    </div>
                </div>

                <!-- Right Sidebar: Question Palette & Overview -->
                <div class="col-lg-4">
                    <div class="palette-card mb-4">
                        <div class="d-flex align-items-center justify-content-between">
                            <h6 class="fw-bold mb-0 text-dark">
                                <i class="bi bi-grid-3x3-gap-fill text-primary me-1"></i> Question Palette
                            </h6>
                            <span class="small text-muted">Total: <?= $totalQuestions ?></span>
                        </div>

                        <!-- Question Numbers Grid -->
                        <div class="palette-grid">
                            <?php for ($i = 1; $i <= $totalQuestions; $i++): ?>
                                <div class="palette-btn <?= ($i === 1) ? 'current' : '' ?>" id="palette_btn_<?= $i ?>" onclick="jumpToQuestion(<?= $i ?>)">
                                    <?= $i ?>
                                </div>
                            <?php endfor; ?>
                        </div>

                        <!-- Status Legend -->
                        <div class="row g-2 pt-2 border-top small text-muted">
                            <div class="col-6 d-flex align-items-center gap-2">
                                <span class="legend-dot bg-primary"></span>
                                <span>Answered (<b id="statAnswered">0</b>)</span>
                            </div>
                            <div class="col-6 d-flex align-items-center gap-2">
                                <span class="legend-dot bg-light border"></span>
                                <span>Unanswered (<b id="statUnanswered"><?= $totalQuestions ?></b>)</span>
                            </div>
                            <div class="col-6 d-flex align-items-center gap-2">
                                <span class="legend-dot bg-warning"></span>
                                <span>Flagged (<b id="statFlagged">0</b>)</span>
                            </div>
                            <div class="col-6 d-flex align-items-center gap-2">
                                <span class="legend-dot border border-2 border-primary"></span>
                                <span>Current</span>
                            </div>
                        </div>

                        <!-- Keyboard Shortcut Guide -->
                        <div class="mt-3 p-2 bg-light rounded border text-muted small">
                            <i class="bi bi-keyboard me-1 text-primary"></i>
                            <strong>Shortcuts:</strong> Press <kbd>A</kbd>, <kbd>B</kbd>, <kbd>C</kbd>, <kbd>D</kbd> to pick answer, <kbd>→</kbd> Next, <kbd>←</kbd> Prev.
                        </div>

                        <div class="mt-4 d-flex flex-column gap-2">
                            <button type="button" class="btn btn-success btn-lg w-100 rounded-3 fw-bold" onclick="confirmFinalSubmit()">
                                <i class="bi bi-send-check-fill me-1"></i> Finish &amp; Submit Paper
                            </button>
                            <button type="button" class="btn btn-outline-danger w-100 rounded-3 fw-semibold py-2" onclick="confirmExitExam()">
                                <i class="bi bi-x-circle me-1"></i> Cancel &amp; Exit (Retake Later)
                            </button>
                        </div>
                    </div>
                </div>

            </div>
        </form>
    </main>

    <!-- Scientific / Standard CBT Calculator Modal -->
    <div class="modal fade" id="calculatorModal" tabindex="-1" aria-labelledby="calcModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" style="max-width: 320px;">
            <div class="modal-content border-0 shadow-lg rounded-4">
                <div class="modal-header py-2 bg-dark text-white rounded-top-4">
                    <h6 class="modal-title fw-bold" id="calcModalLabel"><i class="bi bi-calculator me-1"></i> CBT Calculator</h6>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-3 bg-light rounded-bottom-4">
                    <div class="calc-screen" id="calcDisplay">0</div>
                    <div class="row g-2">
                        <div class="col-3"><button type="button" class="btn btn-danger calc-btn w-100" onclick="calcClear()">C</button></div>
                        <div class="col-3"><button type="button" class="btn btn-secondary calc-btn w-100" onclick="calcBack()">⌫</button></div>
                        <div class="col-3"><button type="button" class="btn btn-secondary calc-btn w-100" onclick="calcInput('/')">÷</button></div>
                        <div class="col-3"><button type="button" class="btn btn-secondary calc-btn w-100" onclick="calcInput('*')">×</button></div>

                        <div class="col-3"><button type="button" class="btn btn-light border calc-btn w-100" onclick="calcInput('7')">7</button></div>
                        <div class="col-3"><button type="button" class="btn btn-light border calc-btn w-100" onclick="calcInput('8')">8</button></div>
                        <div class="col-3"><button type="button" class="btn btn-light border calc-btn w-100" onclick="calcInput('9')">9</button></div>
                        <div class="col-3"><button type="button" class="btn btn-secondary calc-btn w-100" onclick="calcInput('-')">-</button></div>

                        <div class="col-3"><button type="button" class="btn btn-light border calc-btn w-100" onclick="calcInput('4')">4</button></div>
                        <div class="col-3"><button type="button" class="btn btn-light border calc-btn w-100" onclick="calcInput('5')">5</button></div>
                        <div class="col-3"><button type="button" class="btn btn-light border calc-btn w-100" onclick="calcInput('6')">6</button></div>
                        <div class="col-3"><button type="button" class="btn btn-secondary calc-btn w-100" onclick="calcInput('+')">+</button></div>

                        <div class="col-3"><button type="button" class="btn btn-light border calc-btn w-100" onclick="calcInput('1')">1</button></div>
                        <div class="col-3"><button type="button" class="btn btn-light border calc-btn w-100" onclick="calcInput('2')">2</button></div>
                        <div class="col-3"><button type="button" class="btn btn-light border calc-btn w-100" onclick="calcInput('3')">3</button></div>
                        <div class="col-3"><button type="button" class="btn btn-primary calc-btn w-100" onclick="calcCompute()">=</button></div>

                        <div class="col-6"><button type="button" class="btn btn-light border calc-btn w-100" onclick="calcInput('0')">0</button></div>
                        <div class="col-3"><button type="button" class="btn btn-light border calc-btn w-100" onclick="calcInput('.')">.</button></div>
                        <div class="col-3"><button type="button" class="btn btn-secondary calc-btn w-100" onclick="calcSqrt()">√</button></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS Bundle -->
    <script src="bootstrap.bundle.min.js"></script>

    <!-- CBT Engine JavaScript -->
    <script>
        const sessionKey = '<?= $sessionKey ?>';
        const storageKeyAns = 'cbt_ans_' + sessionKey;
        const storageKeyFlag = 'cbt_flags_' + sessionKey;

        const totalQuestions = <?= (int)$totalQuestions ?>;
        let currentQuestion = 1;
        const answers = {};
        const flagged = {};

        // Server-anchored timer variables
        let totalSeconds = <?= (int)$remainingSeconds ?>;
        let elapsedSeconds = <?= (int)$elapsedSeconds ?>;
        let isExitingOrSubmitting = false;
        let tabSwitchViolations = 0;

        const timerBox = document.getElementById('timerBox');
        const timerDisplay = document.getElementById('timerDisplay');
        const timeSpentInput = document.getElementById('timeSpentInput');

        // Formats seconds into MM:SS
        function formatTime(secs) {
            const m = Math.floor(Math.max(0, secs) / 60);
            const s = Math.max(0, secs) % 60;
            return `${String(m).padStart(2, '0')}:${String(s).padStart(2, '0')}`;
        }

        // Live timer display updater
        function updateTimerUI() {
            if (timerDisplay) {
                timerDisplay.textContent = formatTime(totalSeconds);
            }
            if (timerBox) {
                if (totalSeconds <= 120) {
                    timerBox.className = 'timer-box danger';
                } else if (totalSeconds <= 300) {
                    timerBox.className = 'timer-box warning';
                } else {
                    timerBox.className = 'timer-box';
                }
            }
        }

        updateTimerUI();

        // Check if allocated duration already expired
        if (totalSeconds <= 0) {
            setTimeout(() => {
                timeExpiredAutoSubmit();
            }, 300);
        }

        // Countdown interval
        const timerInterval = setInterval(() => {
            if (isExitingOrSubmitting) return;

            totalSeconds--;
            elapsedSeconds++;
            if (timeSpentInput) timeSpentInput.value = elapsedSeconds;

            if (totalSeconds <= 0) {
                clearInterval(timerInterval);
                if (timerDisplay) timerDisplay.textContent = '00:00';
                timeExpiredAutoSubmit();
                return;
            }

            updateTimerUI();
        }, 1000);

        // Restore candidate answers & flags from sessionStorage
        function restoreSavedState() {
            try {
                const cachedAns = sessionStorage.getItem(storageKeyAns);
                if (cachedAns) {
                    const parsedAns = JSON.parse(cachedAns);
                    for (const qNum in parsedAns) {
                        const optKey = parsedAns[qNum];
                        answers[qNum] = optKey;

                        // Check radio input
                        const radio = document.getElementById(`opt_input_${qNum}_${optKey}`);
                        if (radio) radio.checked = true;

                        // Highlight option UI card
                        const optItem = document.getElementById(`opt_item_${qNum}_${optKey}`);
                        if (optItem) optItem.classList.add('selected');

                        // Update palette button
                        const palBtn = document.getElementById(`palette_btn_${qNum}`);
                        if (palBtn) palBtn.classList.add('answered');
                    }
                }

                const cachedFlags = sessionStorage.getItem(storageKeyFlag);
                if (cachedFlags) {
                    const parsedFlags = JSON.parse(cachedFlags);
                    for (const qNum in parsedFlags) {
                        if (parsedFlags[qNum]) {
                            flagged[qNum] = true;
                            const palBtn = document.getElementById(`palette_btn_${qNum}`);
                            if (palBtn) {
                                palBtn.classList.remove('answered');
                                palBtn.classList.add('flagged');
                            }
                        }
                    }
                }
            } catch (e) {
                console.error('Session restore notice:', e);
            }

            updateStats();
            updateFlagDisplay();
        }

        // Jump to Question Pane
        function jumpToQuestion(qNum) {
            if (qNum < 1 || qNum > totalQuestions) return;

            // Hide old pane
            const oldPane = document.getElementById(`pane_${currentQuestion}`);
            const oldPalBtn = document.getElementById(`palette_btn_${currentQuestion}`);
            if (oldPane) oldPane.style.display = 'none';
            if (oldPalBtn) oldPalBtn.classList.remove('current');

            // Show new pane
            currentQuestion = qNum;
            const newPane = document.getElementById(`pane_${currentQuestion}`);
            const newPalBtn = document.getElementById(`palette_btn_${currentQuestion}`);
            if (newPane) newPane.style.display = 'block';
            if (newPalBtn) newPalBtn.classList.add('current');

            // Update Header & Nav Controls
            const qBadge = document.getElementById('questionBadge');
            if (qBadge) qBadge.textContent = `Question ${currentQuestion} of ${totalQuestions}`;

            const prevBtn = document.getElementById('prevBtn');
            if (prevBtn) prevBtn.disabled = (currentQuestion === 1);
            
            const nextBtn = document.getElementById('nextBtn');
            if (nextBtn) {
                if (currentQuestion === totalQuestions) {
                    nextBtn.innerHTML = 'Review <i class="bi bi-list-check ms-1"></i>';
                } else {
                    nextBtn.innerHTML = 'Next <i class="bi bi-arrow-right ms-1"></i>';
                }
            }

            updateFlagDisplay();
        }

        function nextQuestion() {
            if (currentQuestion < totalQuestions) {
                jumpToQuestion(currentQuestion + 1);
            } else {
                confirmFinalSubmit();
            }
        }

        function prevQuestion() {
            if (currentQuestion > 1) {
                jumpToQuestion(currentQuestion - 1);
            }
        }

        // Option selection with sessionStorage persistence
        function selectOption(qNum, optKey) {
            answers[qNum] = optKey;
            try {
                sessionStorage.setItem(storageKeyAns, JSON.stringify(answers));
            } catch(e) {}

            // Clear visual selection on this question
            ['a', 'b', 'c', 'd'].forEach(k => {
                const el = document.getElementById(`opt_item_${qNum}_${k}`);
                if (el) el.classList.remove('selected');
            });

            // Set visual selection
            const target = document.getElementById(`opt_item_${qNum}_${optKey}`);
            if (target) target.classList.add('selected');

            // Set radio button input checked
            const radio = document.getElementById(`opt_input_${qNum}_${optKey}`);
            if (radio) radio.checked = true;

            // Update palette button
            const palBtn = document.getElementById(`palette_btn_${qNum}`);
            if (!flagged[qNum] && palBtn) {
                palBtn.className = 'palette-btn answered' + (parseInt(qNum) === currentQuestion ? ' current' : '');
            }

            updateStats();
        }

        // Flagging with sessionStorage persistence
        function toggleFlagCurrentQuestion() {
            flagged[currentQuestion] = !flagged[currentQuestion];
            try {
                sessionStorage.setItem(storageKeyFlag, JSON.stringify(flagged));
            } catch(e) {}

            const palBtn = document.getElementById(`palette_btn_${currentQuestion}`);
            if (palBtn) {
                if (flagged[currentQuestion]) {
                    palBtn.classList.remove('answered');
                    palBtn.classList.add('flagged');
                } else {
                    palBtn.classList.remove('flagged');
                    if (answers[currentQuestion]) {
                        palBtn.classList.add('answered');
                    }
                }
            }
            updateFlagDisplay();
            updateStats();
        }

        function updateFlagDisplay() {
            const isFlagged = !!flagged[currentQuestion];
            const indicator = document.getElementById('flagIndicator');
            const flagBtnText = document.getElementById('flagBtnText');

            if (indicator && flagBtnText) {
                if (isFlagged) {
                    indicator.classList.remove('d-none');
                    flagBtnText.textContent = 'Remove Flag';
                } else {
                    indicator.classList.add('d-none');
                    flagBtnText.textContent = 'Flag for Review';
                }
            }
        }

        // Real-time overview counters
        function updateStats() {
            const answeredCount = Object.keys(answers).length;
            const flaggedCount = Object.values(flagged).filter(Boolean).length;
            const unansweredCount = totalQuestions - answeredCount;

            const statAns = document.getElementById('statAnswered');
            const statUnans = document.getElementById('statUnanswered');
            const statFlg = document.getElementById('statFlagged');

            if (statAns) statAns.textContent = answeredCount;
            if (statUnans) statUnans.textContent = unansweredCount;
            if (statFlg) statFlg.textContent = flaggedCount;
        }

        // Fullscreen Toggle
        function toggleFullScreen() {
            if (!document.fullscreenElement) {
                document.documentElement.requestFullscreen().catch(err => console.log(err));
            } else {
                if (document.exitFullscreen) {
                    document.exitFullscreen();
                }
            }
        }

        // Cancel & Exit Exam Room (Retake Later)
        function confirmExitExam() {
            Swal.fire({
                title: 'Exit Examination Room?',
                html: `
                    <div class="text-start p-3 bg-light rounded border my-2">
                        <p class="mb-1 text-danger fw-bold"><i class="bi bi-exclamation-triangle-fill me-1"></i> Discard Active Session</p>
                        <p class="mb-0 text-muted small">
                            If you exit now, your current answers will <strong>not</strong> be scored or recorded.
                        </p>
                    </div>
                    <p class="small text-muted mt-2">
                        You can return to the subject catalog and <strong>retake this examination from the beginning</strong> at any time.
                    </p>
                `,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc2626',
                cancelButtonColor: '#64748b',
                confirmButtonText: '<i class="bi bi-box-arrow-right me-1"></i> Yes, Terminate &amp; Exit',
                cancelButtonText: 'Stay in Exam Room'
            }).then((result) => {
                if (result.isConfirmed) {
                    isExitingOrSubmitting = true;
                    try {
                        sessionStorage.removeItem(storageKeyAns);
                        sessionStorage.removeItem(storageKeyFlag);
                    } catch(e) {}

                    Swal.fire({
                        title: 'Exiting Exam Room...',
                        html: '<span class="text-muted">Clearing session and returning to subjects...</span>',
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });

                    window.location.href = `exam.php?action=exit_exam&subject=<?= urlencode($subjectSlug) ?>&exam_type=<?= urlencode($examType) ?>&year=<?= (int)$year ?>&mode=<?= urlencode($mode) ?>`;
                }
            });
        }

        // Submission with SweetAlert2
        function confirmFinalSubmit() {
            const answeredCount = Object.keys(answers).length;
            const unansweredCount = totalQuestions - answeredCount;

            let icon = 'question';
            let title = 'Submit Examination?';
            let htmlText = `
                <div class="text-start p-3 bg-light rounded border my-2">
                    <p class="mb-1 text-success fw-bold"><i class="bi bi-check-circle me-1"></i> Answered: ${answeredCount} Questions</p>
                    <p class="mb-1 text-danger fw-bold"><i class="bi bi-x-circle me-1"></i> Unanswered: ${unansweredCount} Questions</p>
                    <p class="mb-0 text-muted small"><i class="bi bi-clock me-1"></i> Remaining Time: ${timerDisplay ? timerDisplay.textContent : ''}</p>
                </div>
            `;

            if (unansweredCount > 0) {
                icon = 'warning';
                title = 'Unanswered Questions Remaining!';
                htmlText += `<p class="text-danger small mt-2">You still have <strong>${unansweredCount}</strong> question(s) unattempted. Are you certain you wish to submit now?</p>`;
            } else {
                htmlText += `<p class="text-success small mt-2">All questions have been answered. You are ready to view your results!</p>`;
            }

            Swal.fire({
                title: title,
                html: htmlText,
                icon: icon,
                showCancelButton: true,
                confirmButtonColor: '#10b981',
                cancelButtonColor: '#64748b',
                confirmButtonText: '<i class="bi bi-check2-circle me-1"></i> Yes, Finish &amp; Submit',
                cancelButtonText: 'Continue Answering'
            }).then((result) => {
                if (result.isConfirmed) {
                    executeSubmit();
                }
            });
        }

        // Automatic submission on timeout
        function timeExpiredAutoSubmit() {
            if (isExitingOrSubmitting) return;
            isExitingOrSubmitting = true;

            Swal.fire({
                title: 'Time is Up!',
                text: 'Your allocated examination duration has elapsed. Submitting your answers automatically...',
                icon: 'info',
                timer: 2500,
                timerProgressBar: true,
                showConfirmButton: false,
                allowOutsideClick: false
            }).then(() => {
                executeSubmit();
            });
        }

        function executeSubmit() {
            isExitingOrSubmitting = true;
            try {
                sessionStorage.removeItem(storageKeyAns);
                sessionStorage.removeItem(storageKeyFlag);
            } catch(e) {}

            Swal.fire({
                title: 'Computing Examination Results...',
                html: '<span class="text-muted">Evaluating answer keys and compiling your result slip...</span>',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });
            document.getElementById('examForm').submit();
        }

        // Anti-tamper Toast Alert
        function showRestrictionToast(msg) {
            const Toast = Swal.mixin({
                toast: true,
                position: 'top',
                showConfirmButton: false,
                timer: 2000,
                timerProgressBar: true
            });
            Toast.fire({
                icon: 'warning',
                title: msg
            });
        }

        // ==========================================
        // STRICT ANTI-MANIPULATION SECURITY GUARDS
        // ==========================================

        // 1. Trap and Block Browser Back & Forward Navigation
        history.pushState(null, document.title, location.href);
        window.addEventListener('popstate', function (event) {
            history.pushState(null, document.title, location.href);
            Swal.fire({
                title: 'Navigation Restricted',
                html: 'Browser Back and Forward buttons are disabled during the live examination.<br><br>Please use the in-app question palette, or click <b>Exit Exam</b> or <b>Submit</b>.',
                icon: 'warning',
                confirmButtonColor: '#2563eb',
                confirmButtonText: 'Stay in Exam Room'
            });
        });

        // 2. Disable Right-Click Context Menu
        document.addEventListener('contextmenu', function (e) {
            e.preventDefault();
            showRestrictionToast('Right-click context menu is disabled in CBT mode.');
            return false;
        });

        // 3. Prevent Unauthorized Keyboard Manipulation (F5, Ctrl+R, DevTools, Inspect)
        document.addEventListener('keydown', function (e) {
            const isCtrlOrCmd = e.ctrlKey || e.metaKey;
            const isShift = e.shiftKey;
            const key = e.key.toLowerCase();
            const keyCode = e.keyCode || e.which;

            // Block F5
            if (keyCode === 116 || key === 'f5') {
                e.preventDefault();
                e.stopPropagation();
                showRestrictionToast('Page reload (F5) is disabled in the examination room.');
                return false;
            }

            // Block Ctrl+R / Cmd+R / Ctrl+Shift+R
            if (isCtrlOrCmd && key === 'r') {
                e.preventDefault();
                e.stopPropagation();
                showRestrictionToast('Page refresh shortcut is disabled.');
                return false;
            }

            // Block F12 (DevTools)
            if (keyCode === 123 || key === 'f12') {
                e.preventDefault();
                e.stopPropagation();
                showRestrictionToast('Developer inspection tools (F12) are strictly prohibited.');
                return false;
            }

            // Block Ctrl+Shift+I / Cmd+Option+I (Inspect)
            // Block Ctrl+Shift+J / Cmd+Option+J (Console)
            // Block Ctrl+Shift+C (Element Inspector)
            if (isCtrlOrCmd && isShift && (key === 'i' || key === 'j' || key === 'c')) {
                e.preventDefault();
                e.stopPropagation();
                showRestrictionToast('Inspection tools are prohibited during exams.');
                return false;
            }

            // Block Ctrl+U (View Source)
            if (isCtrlOrCmd && key === 'u') {
                e.preventDefault();
                e.stopPropagation();
                showRestrictionToast('Source view is disabled.');
                return false;
            }

            // Block Ctrl+S (Save Page)
            if (isCtrlOrCmd && key === 's') {
                e.preventDefault();
                e.stopPropagation();
                return false;
            }

            // Block Alt+Left / Alt+Right (Browser History)
            if (e.altKey && (key === 'arrowleft' || key === 'arrowright')) {
                e.preventDefault();
                e.stopPropagation();
                return false;
            }

            // Allow input fields if inside calculator or any input
            if (['input', 'textarea'].includes(document.activeElement.tagName.toLowerCase())) return;

            // Standard CBT navigation & answering shortcuts
            if (['a', 'b', 'c', 'd'].includes(key)) {
                selectOption(currentQuestion, key);
            } else if (e.key === 'ArrowRight' || key === 'n') {
                nextQuestion();
            } else if (e.key === 'ArrowLeft' || key === 'p') {
                prevQuestion();
            } else if (key === 'f') {
                toggleFlagCurrentQuestion();
            }
        });

        // 4. Departure Guard (window.onbeforeunload)
        window.addEventListener('beforeunload', function (e) {
            if (!isExitingOrSubmitting) {
                const message = 'Exam in progress! Are you sure you want to leave? Your exam timer will continue running.';
                e.preventDefault();
                e.returnValue = message;
                return message;
            }
        });

        // 5. Proctoring Focus Loss & Tab Switching Monitor
        document.addEventListener('visibilitychange', function () {
            if (isExitingOrSubmitting) return;

            if (document.hidden) {
                tabSwitchViolations++;
            } else {
                Swal.fire({
                    icon: 'warning',
                    title: 'Proctoring Notice: Window Focus Lost!',
                    html: `
                        <div class="text-danger fw-semibold my-2">
                            <i class="bi bi-shield-exclamation fs-2 d-block mb-1"></i>
                            Switching tabs or navigating away from the CBT screen is strictly prohibited.
                        </div>
                        <div class="p-2 bg-light rounded border text-muted small">
                            Violation warning count: <strong class="text-danger">${tabSwitchViolations}</strong>
                        </div>
                    `,
                    confirmButtonColor: '#2563eb',
                    confirmButtonText: 'Return to Examination'
                });
            }
        });

        // Basic CBT Calculator Logic
        let calcVal = '0';
        function calcInput(char) {
            if (calcVal === '0' && char !== '.') calcVal = '';
            calcVal += char;
            document.getElementById('calcDisplay').textContent = calcVal;
        }
        function calcClear() {
            calcVal = '0';
            document.getElementById('calcDisplay').textContent = '0';
        }
        function calcBack() {
            calcVal = calcVal.slice(0, -1);
            if (!calcVal) calcVal = '0';
            document.getElementById('calcDisplay').textContent = calcVal;
        }
        function calcSqrt() {
            try {
                calcVal = String(Math.sqrt(eval(calcVal)));
                document.getElementById('calcDisplay').textContent = calcVal;
            } catch(e) {
                document.getElementById('calcDisplay').textContent = 'Error';
                calcVal = '0';
            }
        }
        function calcCompute() {
            try {
                // sanitized basic arithmetic
                const sanitized = calcVal.replace(/[^0-9+\-*/.]/g, '');
                calcVal = String(eval(sanitized));
                document.getElementById('calcDisplay').textContent = calcVal;
            } catch(e) {
                document.getElementById('calcDisplay').textContent = 'Error';
                calcVal = '0';
            }
        }

        // Initialize restored state on DOM ready
        document.addEventListener('DOMContentLoaded', () => {
            restoreSavedState();
        });
    </script>
</body>
</html>
