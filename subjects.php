<?php
require_once "db.php";
require_once "exam_api.php";

// Ensure candidate is authenticated
if (!isset($_SESSION['stid'])) {
    header("Location: index.php");
    exit;
}

$student_id = $_SESSION['stid'];
$student_name = $_SESSION['name'] ?? 'Candidate';
$student_email = $_SESSION['email'] ?? '';

// Fetch all past submissions by this candidate
$completedSubjects = [];
$pastSubmissions = [];
try {
    $stmt = $conn->prepare("SELECT * FROM exam_submissions WHERE student_id = :stid ORDER BY created_at DESC");
    $stmt->execute([':stid' => $student_id]);
    $pastSubmissions = $stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($pastSubmissions as $sub) {
        $key = strtolower($sub['subject']);
        if (!isset($completedSubjects[$key])) {
            $completedSubjects[$key] = $sub;
        }
    }

    // Also check legacy tables for backward compatibility
    $legacyTables = [
        'biology' => 'exam_biology01',
        'english' => 'exam_english01',
        'geography' => 'exam_geography01'
    ];
    foreach ($legacyTables as $subKey => $tableName) {
        if (!isset($completedSubjects[$subKey])) {
            $chk = $conn->prepare("SELECT * FROM {$tableName} WHERE student_id = :stid LIMIT 1");
            $chk->execute([':stid' => $student_id]);
            if ($chk->rowCount() > 0) {
                $completedSubjects[$subKey] = [
                    'subject' => $subKey,
                    'exam_type' => 'WAEC',
                    'year' => 2023,
                    'score' => 10,
                    'total_questions' => 10,
                    'percentage' => 100
                ];
            }
        }
    }
} catch (Exception $e) {
    // Graceful fallback
}

// 18 Core Subjects Categorized Cleanly
$subjectsList = [
    // Sciences & Agriculture
    [
        'id' => 'math',
        'slug' => 'mathematics',
        'title' => 'Mathematics',
        'category' => 'Sciences & Agriculture',
        'icon' => 'bi-calculator-fill',
        'color' => '#8b5cf6',
        'topics' => 'Algebra, Geometry, Trigonometry, Statistics, Calculus'
    ],
    [
        'id' => 'phy',
        'slug' => 'physics',
        'title' => 'Physics',
        'category' => 'Sciences & Agriculture',
        'icon' => 'bi-lightning-charge-fill',
        'color' => '#06b6d4',
        'topics' => 'Mechanics, Waves & Optics, Electricity, Modern Physics'
    ],
    [
        'id' => 'chem',
        'slug' => 'chemistry',
        'title' => 'Chemistry',
        'category' => 'Sciences & Agriculture',
        'icon' => 'bi-radioactive',
        'color' => '#ec4899',
        'topics' => 'Stoichiometry, Organic, Kinetics, Periodic Law'
    ],
    [
        'id' => 'bio',
        'slug' => 'biology',
        'title' => 'Biology',
        'category' => 'Sciences & Agriculture',
        'icon' => 'bi-heart-pulse-fill',
        'color' => '#10b981',
        'topics' => 'Genetics, Ecology, Cell Biology, Human Physiology'
    ],
    [
        'id' => 'agric',
        'slug' => 'agricultural_science',
        'title' => 'Agricultural Science',
        'category' => 'Sciences & Agriculture',
        'icon' => 'bi-tree-fill',
        'color' => '#16a34a',
        'topics' => 'Soil Science, Crop Production, Animal Husbandry, Farm Tools'
    ],

    // Arts & Humanities
    [
        'id' => 'eng',
        'slug' => 'english',
        'title' => 'English Language',
        'category' => 'Arts & Humanities',
        'icon' => 'bi-book-half',
        'color' => '#3b82f6',
        'topics' => 'Lexis & Structure, Oral Forms, Grammar, Concord'
    ],
    [
        'id' => 'lit',
        'slug' => 'literature',
        'title' => 'Literature-in-English',
        'category' => 'Arts & Humanities',
        'icon' => 'bi-feather',
        'color' => '#e11d48',
        'topics' => 'Drama, African Poetry, Prose Fiction, Literary Devices'
    ],
    [
        'id' => 'govt',
        'slug' => 'government',
        'title' => 'Government',
        'category' => 'Arts & Humanities',
        'icon' => 'bi-bank2',
        'color' => '#d97706',
        'topics' => 'Political Theory, Constitutions, Pre-Colonial, Federalism'
    ],
    [
        'id' => 'hist',
        'slug' => 'history',
        'title' => 'History',
        'category' => 'Arts & Humanities',
        'icon' => 'bi-hourglass-split',
        'color' => '#854d0e',
        'topics' => 'Trans-Saharan Trade, Sokoto Caliphate, 1914 Amalgamation, Independence'
    ],
    [
        'id' => 'irs',
        'slug' => 'irs',
        'title' => 'Islamic Religious Studies (IRS)',
        'category' => 'Arts & Humanities',
        'icon' => 'bi-moon-stars-fill',
        'color' => '#047857',
        'topics' => 'Quranic Sciences, Hadith, Tawhid, Fiqh, Caliphs, Islamic History'
    ],

    // Social Sciences & Commercial
    [
        'id' => 'econ',
        'slug' => 'economics',
        'title' => 'Economics',
        'category' => 'Social Sciences & Commercial',
        'icon' => 'bi-graph-up-arrow',
        'color' => '#059669',
        'topics' => 'Micro & Macro, Price Elasticity, Fiscal Policy, Trade'
    ],
    [
        'id' => 'comm',
        'slug' => 'commerce',
        'title' => 'Commerce',
        'category' => 'Social Sciences & Commercial',
        'icon' => 'bi-shop',
        'color' => '#0284c7',
        'topics' => 'Trade & Distribution, Banking, Insurance, Capital Markets'
    ],
    [
        'id' => 'acct',
        'slug' => 'accounting',
        'title' => 'Financial Accounting',
        'category' => 'Social Sciences & Commercial',
        'icon' => 'bi-cash-coin',
        'color' => '#4f46e5',
        'topics' => 'Double Entry, Trial Balance, Depreciation, Balance Sheet'
    ],
    [
        'id' => 'geo',
        'slug' => 'geography',
        'title' => 'Geography',
        'category' => 'Social Sciences & Commercial',
        'icon' => 'bi-globe-americas',
        'color' => '#f59e0b',
        'topics' => 'Physical, Human, Climatology, Cartography'
    ],

    // Vocational & Specialized Subjects
    [
        'id' => 'comp',
        'slug' => 'computer_studies',
        'title' => 'Computer Studies',
        'category' => 'Vocational & Specialized Subjects',
        'icon' => 'bi-laptop',
        'color' => '#2563eb',
        'topics' => 'Hardware Architecture, Networking, DBMS, Logic Gates, Algorithms'
    ],
    [
        'id' => 'hecon',
        'slug' => 'home_economics',
        'title' => 'Home Economics',
        'category' => 'Vocational & Specialized Subjects',
        'icon' => 'bi-house-heart-fill',
        'color' => '#db2777',
        'topics' => 'Food & Nutrition, Textiles, Family Resource Management, Laundry'
    ],
    [
        'id' => 'phe',
        'slug' => 'phe',
        'title' => 'Physical & Health Education (PHE)',
        'category' => 'Vocational & Specialized Subjects',
        'icon' => 'bi-activity',
        'color' => '#ea580c',
        'topics' => 'Cardiovascular Fitness, Athletics, Ball Games, First Aid R.I.C.E'
    ],
    [
        'id' => 'art',
        'slug' => 'fine_arts',
        'title' => 'Art (Fine Arts)',
        'category' => 'Vocational & Specialized Subjects',
        'icon' => 'bi-palette-fill',
        'color' => '#9333ea',
        'topics' => 'Elements & Principles, Nok/Benin Art, Perspective, Sculpture'
    ]
];

// Available Categories for Filter Bar
$categories = [
    'All' => 'All Subjects',
    'Sciences & Agriculture' => 'Sciences & Agriculture',
    'Arts & Humanities' => 'Arts & Humanities',
    'Social Sciences & Commercial' => 'Social Sciences & Commercial',
    'Vocational & Specialized Subjects' => 'Vocational & Specialized'
];

// Available Exam Bodies & Years
$examTypes = [
    'waec' => ['name' => 'WAEC (WASSCE)', 'desc' => 'West African Senior School Certificate'],
    'neco' => ['name' => 'NECO (SSCE)', 'desc' => 'National Examinations Council'],
    'jamb' => ['name' => 'JAMB (UTME)', 'desc' => 'Joint Admissions & Matriculation Board']
];

$years = [2024, 2023, 2022, 2021, 2020, 2019, 2018];

// Examination Standards / Duration Modes
$examModes = [
    'standard' => [
        'name' => 'Official Exam Standard',
        'desc' => 'Full official questions & duration (WAEC 50 Qs / 50-90m • NECO 60 Qs / 60m • JAMB 40-60 Qs / 40-45m)',
        'badge' => 'Official Standard'
    ],
    'sprint' => [
        'name' => 'Speed Sprint Practice',
        'desc' => '20 Questions • 20 Minutes timed speed drill',
        'badge' => '20 Qs / 20 Mins'
    ],
    'quick' => [
        'name' => 'Quick Review Assessment',
        'desc' => '10 Questions • 10 Minutes rapid test',
        'badge' => '10 Qs / 10 Mins'
    ]
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Candidate Assessment Hub | HayZed Exam Portal</title>
    <!-- Favicon -->
    <link rel="icon" type="image/svg+xml" href="favicon.svg">
    <link rel="alternate icon" href="favicon.ico">
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="bootstrap.min.css">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        :root {
            --brand-primary: #2563eb;
            --brand-dark: #090e1a;
            --card-radius: 1rem;
        }

        body {
            font-family: 'Plus Jakarta Sans', system-ui, -apple-system, sans-serif;
            background: #f8fafc;
            color: #1e293b;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        /* Top Navbar */
        .portal-nav {
            background: #090e1a;
            border-bottom: 1px solid rgba(255, 255, 255, 0.08);
            padding: 0.9rem 0;
            color: #fff;
        }

        .portal-brand {
            display: inline-flex;
            align-items: center;
            gap: 0.6rem;
            color: #fff;
            text-decoration: none;
            font-weight: 700;
            font-size: 1.2rem;
        }

        .brand-icon-box {
            width: 36px;
            height: 36px;
            border-radius: 8px;
            background: linear-gradient(135deg, #2563eb, #06b6d4);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.1rem;
        }

        /* Hero Banner */
        .profile-hero {
            background: linear-gradient(135deg, #090e1a 0%, #1e293b 100%);
            color: #fff;
            padding: 2.5rem 0;
            border-bottom: 1px solid #e2e8f0;
            position: relative;
        }

        .candidate-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            background: rgba(37, 99, 235, 0.2);
            color: #60a5fa;
            border: 1px solid rgba(96, 165, 250, 0.3);
            border-radius: 9999px;
            padding: 0.3rem 0.8rem;
            font-size: 0.8rem;
            font-weight: 600;
        }

        /* Exam Configuration Filter Bar */
        .config-bar {
            background: #ffffff;
            border-radius: 1rem;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.06);
            border: 1px solid #e2e8f0;
            padding: 1.5rem;
            margin-top: -2rem;
            position: relative;
            z-index: 5;
        }

        .config-select {
            border-radius: 0.65rem;
            border: 1.5px solid #cbd5e1;
            padding: 0.65rem 1rem;
            font-weight: 600;
            color: #1e293b;
        }

        .config-select:focus {
            border-color: #2563eb;
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.15);
        }

        /* Search & Filter Section */
        .search-box-wrapper {
            position: relative;
        }

        .search-box-input {
            padding: 0.85rem 1.25rem 0.85rem 3rem;
            border-radius: 0.75rem;
            border: 1.5px solid #cbd5e1;
            font-size: 0.95rem;
            font-weight: 500;
            transition: all 0.2s;
            background: #ffffff;
        }

        .search-box-input:focus {
            border-color: #2563eb;
            box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.12);
        }

        .search-icon-left {
            position: absolute;
            left: 1.1rem;
            top: 50%;
            transform: translateY(-50%);
            color: #94a3b8;
            font-size: 1.15rem;
            pointer-events: none;
        }

        .search-clear-btn {
            position: absolute;
            right: 0.8rem;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: #94a3b8;
            font-size: 1.1rem;
            cursor: pointer;
            display: none;
        }

        .search-clear-btn:hover {
            color: #ef4444;
        }

        /* Category Filter Pills */
        .filter-pills-bar {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            overflow-x: auto;
            padding-bottom: 0.25rem;
        }

        .filter-pill {
            border-radius: 9999px;
            padding: 0.5rem 1.1rem;
            font-size: 0.85rem;
            font-weight: 600;
            border: 1px solid #e2e8f0;
            background: #ffffff;
            color: #64748b;
            cursor: pointer;
            transition: all 0.2s ease;
            white-space: nowrap;
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
        }

        .filter-pill:hover {
            background: #f1f5f9;
            color: #1e293b;
            border-color: #cbd5e1;
        }

        .filter-pill.active {
            background: #2563eb;
            color: #ffffff;
            border-color: #2563eb;
            box-shadow: 0 4px 12px rgba(37, 99, 235, 0.25);
        }

        /* Subject Cards */
        .subject-card {
            background: #ffffff;
            border-radius: var(--card-radius);
            border: 1px solid #e2e8f0;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.03);
            transition: all 0.25s cubic-bezier(0.16, 1, 0.3, 1);
            overflow: hidden;
            display: flex;
            flex-direction: column;
            height: 100%;
        }

        .subject-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 16px 32px rgba(0, 0, 0, 0.08);
            border-color: #cbd5e1;
        }

        .subject-header {
            padding: 1.5rem;
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 0.75rem;
        }

        .subject-icon-box {
            width: 52px;
            height: 52px;
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            color: #fff;
            flex-shrink: 0;
        }

        .subject-body {
            padding: 0 1.5rem 1.5rem;
            flex: 1;
        }

        .subject-footer {
            padding: 1rem 1.5rem;
            background: #f8fafc;
            border-top: 1px solid #f1f5f9;
        }

        .btn-start-exam {
            border-radius: 0.65rem;
            font-weight: 700;
            padding: 0.65rem 1.25rem;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            transition: all 0.2s;
        }

        /* Empty Search State */
        .empty-state-box {
            background: #ffffff;
            border-radius: 1rem;
            border: 1.5px dashed #cbd5e1;
            padding: 3.5rem 1.5rem;
            text-align: center;
        }

        /* History Table */
        .history-card {
            background: #ffffff;
            border-radius: var(--card-radius);
            border: 1px solid #e2e8f0;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.03);
            overflow: hidden;
        }

        .portal-footer {
            margin-top: auto;
            background: #ffffff;
            border-top: 1px solid #e2e8f0;
            padding: 1.5rem 0;
            font-size: 0.85rem;
            color: #64748b;
        }
    </style>
</head>
<body>

    <!-- Top Portal Navigation -->
    <nav class="portal-nav">
        <div class="container d-flex align-items-center justify-content-between">
            <a href="subjects.php" class="portal-brand">
                <div class="brand-icon-box">
                    <i class="bi bi-mortarboard-fill"></i>
                </div>
                <span>HayZed <span style="color: #38bdf8;">CBT Portal</span></span>
            </a>
            <div class="d-flex align-items-center gap-3">
                <a href="result.php" class="btn btn-outline-light btn-sm rounded-pill px-3 py-1">
                    <i class="bi bi-award me-1 text-warning"></i> View My Results
                </a>
                <button type="button" onclick="confirmLogout()" class="btn btn-danger btn-sm rounded-pill px-3 py-1">
                    <i class="bi bi-box-arrow-right me-1"></i> Sign Out
                </button>
            </div>
        </div>
    </nav>

    <!-- Candidate Profile Banner -->
    <section class="profile-hero">
        <div class="container">
            <div class="row align-items-center justify-content-between gy-3">
                <div class="col-lg-8">
                    <div class="candidate-badge mb-2">
                        <i class="bi bi-patch-check-fill"></i> Candidate Verified
                    </div>
                    <h2 class="fw-bold mb-1">
                        Welcome, <?= htmlspecialchars($student_name) ?>!
                    </h2>
                    <p class="text-secondary mb-0 small">
                        Exam Registration ID: <span class="text-white font-monospace fw-bold"><?= htmlspecialchars($student_id) ?></span> 
                        &bull; <?= htmlspecialchars($student_email) ?>
                    </p>
                </div>
                <div class="col-lg-4 text-lg-end">
                    <span class="badge bg-success-subtle text-success border border-success-subtle p-2 px-3 rounded-pill fw-semibold">
                        <i class="bi bi-wifi me-1"></i> Examination Server Online
                    </span>
                </div>
            </div>
        </div>
    </section>

    <!-- Main Workspace -->
    <main class="container my-4">
        
        <!-- Exam Configuration & Filter -->
        <div class="config-bar mb-4">
            <div class="row g-3 align-items-center">
                <div class="col-md-4">
                    <label class="form-label small fw-bold text-muted mb-1">
                        <i class="bi bi-building me-1 text-primary"></i> 1. Examination Body
                    </label>
                    <select id="examTypeSelect" class="form-select config-select" onchange="updateExamParams()">
                        <?php foreach ($examTypes as $code => $type): ?>
                            <option value="<?= $code ?>"><?= $type['name'] ?> - <?= $type['desc'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label small fw-bold text-muted mb-1">
                        <i class="bi bi-calendar-event me-1 text-primary"></i> 2. Examination Year
                    </label>
                    <select id="examYearSelect" class="form-select config-select" onchange="updateExamParams()">
                        <?php foreach ($years as $yr): ?>
                            <option value="<?= $yr ?>" <?= ($yr === 2023) ? 'selected' : '' ?>><?= $yr ?> Past Paper</option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-5">
                    <label class="form-label small fw-bold text-muted mb-1">
                        <i class="bi bi-stopwatch me-1 text-primary"></i> 3. Standard &amp; Duration Mode
                    </label>
                    <select id="examModeSelect" class="form-select config-select" onchange="updateExamParams()">
                        <?php foreach ($examModes as $mKey => $mVal): ?>
                            <option value="<?= $mKey ?>"><?= $mVal['name'] ?> (<?= $mVal['badge'] ?>)</option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <!-- Dynamic Standard Info Strip -->
            <div class="mt-3 p-2 px-3 rounded bg-light border text-muted small d-flex align-items-center justify-content-between flex-wrap gap-2" id="examStandardSummary">
                <div class="d-flex align-items-center gap-2">
                    <i class="bi bi-shield-lock-fill text-success fs-5"></i>
                    <span id="standardRuleText"><strong>WAEC (WASSCE) Standard:</strong> 50 Questions per paper (General 50m • English 60m • Mathematics 90m). Auto-submitted on timeout.</span>
                </div>
                <span class="badge bg-primary-subtle text-primary border border-primary-subtle rounded-pill px-3 py-1 fw-bold" id="modeBadgeDisplay">Official Standard</span>
            </div>
        </div>

        <!-- Search Bar and Category Filters -->
        <div class="mb-4">
            <div class="row g-3 align-items-center mb-3">
                <div class="col-lg-6">
                    <div class="search-box-wrapper">
                        <i class="bi bi-search search-icon-left"></i>
                        <input type="text" 
                               id="subjectSearchInput" 
                               class="form-control search-box-input" 
                               placeholder="Search subjects or curriculum topics (e.g. Accounting, Algebra, Genetics)..." 
                               oninput="filterSubjects()">
                        <button type="button" id="clearSearchBtn" class="search-clear-btn" onclick="clearSearch()" title="Clear search">
                            <i class="bi bi-x-circle-fill"></i>
                        </button>
                    </div>
                </div>
                <div class="col-lg-6 text-lg-end">
                    <span class="text-muted small fw-semibold" id="subjectMatchCount">
                        Showing all <?= count($subjectsList) ?> examination papers
                    </span>
                </div>
            </div>

            <!-- Category Filter Tabs -->
            <div class="filter-pills-bar">
                <?php foreach ($categories as $catKey => $catLabel): ?>
                    <button type="button" 
                            class="filter-pill <?= ($catKey === 'All') ? 'active' : '' ?>" 
                            data-category="<?= htmlspecialchars($catKey) ?>" 
                            onclick="setCategoryFilter('<?= htmlspecialchars($catKey) ?>', this)">
                        <?php if ($catKey === 'All'): ?>
                            <i class="bi bi-grid-fill"></i>
                        <?php elseif ($catKey === 'Sciences & Agriculture'): ?>
                            <i class="bi bi-flower1"></i>
                        <?php elseif ($catKey === 'Arts & Humanities'): ?>
                            <i class="bi bi-palette2"></i>
                        <?php elseif ($catKey === 'Social Sciences & Commercial'): ?>
                            <i class="bi bi-graph-up"></i>
                        <?php else: ?>
                            <i class="bi bi-gear-fill"></i>
                        <?php endif; ?>
                        <?= htmlspecialchars($catLabel) ?>
                    </button>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- Section Title -->
        <div class="d-flex align-items-center justify-content-between mb-3">
            <div>
                <h4 class="fw-bold mb-0 text-dark">Available Subject Papers</h4>
                <p class="text-muted small mb-0">Select an authentic examination paper below to begin your computer-based test</p>
            </div>
            <span class="badge bg-primary rounded-pill px-3 py-2 fw-semibold">
                <?= count($subjectsList) ?> Core Exam Papers Ready
            </span>
        </div>

        <!-- Subjects Grid -->
        <div class="row g-4" id="subjectsGrid">
            <?php foreach ($subjectsList as $subj): 
                $subSlug = $subj['slug'];
                $isCompleted = isset($completedSubjects[$subSlug]);
                $lastResult = $isCompleted ? $completedSubjects[$subSlug] : null;
            ?>
                <div class="col-lg-4 col-md-6 subject-grid-item" 
                     data-slug="<?= htmlspecialchars($subj['slug']) ?>"
                     data-title="<?= htmlspecialchars(strtolower($subj['title'])) ?>"
                     data-category="<?= htmlspecialchars($subj['category']) ?>"
                     data-topics="<?= htmlspecialchars(strtolower($subj['topics'])) ?>">
                    <div class="subject-card">
                        <div class="subject-header">
                            <div>
                                <span class="badge bg-light text-muted border mb-2"><?= $subj['category'] ?></span>
                                <h5 class="fw-bold mb-0 text-dark"><?= $subj['title'] ?></h5>
                            </div>
                            <div class="subject-icon-box" style="background: <?= $subj['color'] ?>;">
                                <i class="bi <?= $subj['icon'] ?>"></i>
                            </div>
                        </div>

                        <div class="subject-body">
                            <p class="text-muted small mb-3">
                                <strong>Curriculum:</strong> <?= $subj['topics'] ?>
                            </p>
                            <div class="d-flex align-items-center gap-3 text-secondary small">
                                <span><i class="bi bi-file-earmark-text me-1"></i> <b id="cnt_q_<?= $subSlug ?>">50</b> Questions</span>
                                <span><i class="bi bi-stopwatch me-1"></i> <b id="cnt_d_<?= $subSlug ?>">50</b> Minutes</span>
                            </div>
                        </div>

                        <div class="subject-footer">
                            <?php if ($isCompleted): ?>
                                <div class="d-flex align-items-center justify-content-between">
                                    <span class="badge bg-success-subtle text-success border border-success-subtle py-2 px-2 rounded-2">
                                        <i class="bi bi-check2-circle me-1"></i> Submitted
                                    </span>
                                    <button type="button" class="btn btn-outline-primary btn-sm rounded-2 fw-semibold"
                                            onclick="promptStartExam('<?= $subj['slug'] ?>', '<?= $subj['title'] ?>', true)">
                                        <i class="bi bi-arrow-repeat me-1"></i> Retake Paper
                                    </button>
                                </div>
                            <?php else: ?>
                                <button type="button" class="btn btn-primary btn-start-exam w-100"
                                        onclick="promptStartExam('<?= $subj['slug'] ?>', '<?= $subj['title'] ?>', false)">
                                    <span>Launch Examination</span>
                                    <i class="bi bi-arrow-right"></i>
                                </button>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <!-- Empty Search State -->
        <div id="emptySearchState" class="empty-state-box mt-3 d-none">
            <i class="bi bi-search text-muted fs-1 mb-3 d-block"></i>
            <h5 class="fw-bold text-dark mb-1">No Matching Subject Papers Found</h5>
            <p class="text-muted small mb-3">We couldn't find any papers matching your search criteria. Try a different keyword or clear filters.</p>
            <button type="button" class="btn btn-outline-primary btn-sm rounded-pill px-3" onclick="clearSearchAndCategory()">
                <i class="bi bi-arrow-counterclockwise me-1"></i> Reset All Filters
            </button>
        </div>

        <!-- Recent Exam Submissions Section -->
        <?php if (!empty($pastSubmissions)): ?>
            <div class="mt-5">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <h5 class="fw-bold mb-0 text-dark">
                        <i class="bi bi-clock-history me-1 text-primary"></i> My Recent Examination Submissions
                    </h5>
                    <a href="result.php" class="text-decoration-none small fw-bold text-primary">
                        View Detailed Slip <i class="bi bi-arrow-right"></i>
                    </a>
                </div>
                <div class="history-card table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Subject</th>
                                <th>Exam Body</th>
                                <th>Year</th>
                                <th>Score</th>
                                <th>Percentage</th>
                                <th>Submitted Date</th>
                                <th class="text-end">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($pastSubmissions as $rec): ?>
                                <tr>
                                    <td class="fw-semibold text-dark">
                                        <i class="bi bi-journal-check text-primary me-1"></i>
                                        <?= htmlspecialchars(ExamEngine::getSubjectName($rec['subject'])) ?>
                                    </td>
                                    <td><span class="badge bg-light text-dark border"><?= htmlspecialchars(strtoupper($rec['exam_type'])) ?></span></td>
                                    <td><?= htmlspecialchars($rec['year']) ?></td>
                                    <td class="fw-bold text-success"><?= (int)$rec['score'] ?> / <?= (int)$rec['total_questions'] ?></td>
                                    <td>
                                        <div class="d-flex align-items-center gap-2">
                                            <div class="progress flex-grow-1" style="height: 6px; width: 80px;">
                                                <div class="progress-bar bg-success" style="width: <?= (float)$rec['percentage'] ?>%;"></div>
                                            </div>
                                            <span class="small fw-semibold"><?= (float)$rec['percentage'] ?>%</span>
                                        </div>
                                    </td>
                                    <td class="text-muted small"><?= date('M d, Y h:i A', strtotime($rec['created_at'])) ?></td>
                                    <td class="text-end">
                                        <a href="result.php?sub_id=<?= $rec['id'] ?>" class="btn btn-outline-primary btn-sm rounded-pill px-3 py-1">
                                            <i class="bi bi-receipt me-1"></i> Slip
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        <?php endif; ?>

    </main>

    <!-- Footer -->
    <footer class="portal-footer">
        <div class="container d-flex flex-column flex-sm-row align-items-center justify-content-between gap-2 text-center text-sm-start">
            <div>
                &copy; <?= date('Y') ?> <strong>HayZed Tech CBT Platform</strong>. All rights reserved.
            </div>
            <div>
                <span class="text-muted small"><i class="bi bi-shield-check text-success me-1"></i> Official Automated Assessment Engine</span>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="bootstrap.bundle.min.js"></script>

    <script>
        let currentExamType = 'waec';
        let currentYear = 2023;
        let currentMode = 'standard';
        let currentCategory = 'All';

        const subjectSlugs = [
            'mathematics', 'physics', 'chemistry', 'biology', 'agricultural_science',
            'english', 'literature', 'government', 'history', 'irs',
            'economics', 'commerce', 'accounting', 'geography',
            'computer_studies', 'home_economics', 'phe', 'fine_arts'
        ];

        // Standard timing calculation matching ExamEngine::getExamStandard
        function getSubjectStandard(type, slug, mode) {
            if (mode === 'sprint') {
                return { questions: 20, duration: 20 };
            }
            if (mode === 'quick') {
                return { questions: 10, duration: 10 };
            }
            if (type === 'jamb' || type === 'utme') {
                if (slug === 'english') return { questions: 60, duration: 45 };
                return { questions: 40, duration: 40 };
            }
            if (type === 'neco') {
                return { questions: 60, duration: 60 };
            }
            // WAEC
            if (slug === 'english') return { questions: 50, duration: 60 };
            if (slug === 'mathematics') return { questions: 50, duration: 90 };
            return { questions: 50, duration: 50 };
        }

        function updateExamParams() {
            currentExamType = document.getElementById('examTypeSelect').value;
            currentYear = document.getElementById('examYearSelect').value;
            currentMode = document.getElementById('examModeSelect').value;

            // Update info banner
            const ruleTextEl = document.getElementById('standardRuleText');
            const modeBadgeEl = document.getElementById('modeBadgeDisplay');

            if (currentMode === 'sprint') {
                ruleTextEl.innerHTML = '<strong>Speed Sprint Mode:</strong> 20 Questions in 20 Minutes across all papers. Ideal for quick timed drilling.';
                modeBadgeEl.textContent = 'Speed Sprint';
                modeBadgeEl.className = 'badge bg-warning-subtle text-warning border border-warning-subtle rounded-pill px-3 py-1 fw-bold';
            } else if (currentMode === 'quick') {
                ruleTextEl.innerHTML = '<strong>Quick Review Assessment:</strong> 10 Questions in 10 Minutes. Fast self-check on key subject topics.';
                modeBadgeEl.textContent = 'Quick Review';
                modeBadgeEl.className = 'badge bg-info-subtle text-info border border-info-subtle rounded-pill px-3 py-1 fw-bold';
            } else {
                modeBadgeEl.textContent = 'Official Standard';
                modeBadgeEl.className = 'badge bg-primary-subtle text-primary border border-primary-subtle rounded-pill px-3 py-1 fw-bold';

                if (currentExamType === 'jamb') {
                    ruleTextEl.innerHTML = '<strong>JAMB (UTME) Official Standard:</strong> 40 Questions in 40 Minutes per paper (Use of English: 60 Questions in 45 Minutes).';
                } else if (currentExamType === 'neco') {
                    ruleTextEl.innerHTML = '<strong>NECO (SSCE) Official Standard:</strong> 60 Questions in 60 Minutes per paper. Official Paper 1 objective regulations.';
                } else {
                    ruleTextEl.innerHTML = '<strong>WAEC (WASSCE) Official Standard:</strong> 50 Questions per paper (General 50m • English 60m • Mathematics 90m).';
                }
            }

            // Update each card's numbers
            subjectSlugs.forEach(slug => {
                const std = getSubjectStandard(currentExamType, slug, currentMode);
                const qEl = document.getElementById(`cnt_q_${slug}`);
                const dEl = document.getElementById(`cnt_d_${slug}`);
                if (qEl) qEl.textContent = std.questions;
                if (dEl) dEl.textContent = std.duration;
            });
        }

        // Live Search and Filter
        function filterSubjects() {
            const query = document.getElementById('subjectSearchInput').value.toLowerCase().trim();
            const clearBtn = document.getElementById('clearSearchBtn');
            clearBtn.style.display = query.length > 0 ? 'block' : 'none';

            const items = document.querySelectorAll('.subject-grid-item');
            let visibleCount = 0;

            items.forEach(item => {
                const title = item.getAttribute('data-title') || '';
                const category = item.getAttribute('data-category') || '';
                const topics = item.getAttribute('data-topics') || '';

                const matchesCategory = (currentCategory === 'All' || category === currentCategory);
                const matchesSearch = query === '' || 
                    title.includes(query) || 
                    topics.includes(query) || 
                    category.toLowerCase().includes(query);

                if (matchesCategory && matchesSearch) {
                    item.style.display = 'block';
                    visibleCount++;
                } else {
                    item.style.display = 'none';
                }
            });

            // Update Match Count Badge & Empty State
            const matchCountEl = document.getElementById('subjectMatchCount');
            const emptyStateEl = document.getElementById('emptySearchState');

            if (visibleCount === 0) {
                emptyStateEl.classList.remove('d-none');
                matchCountEl.textContent = 'No matching subjects found';
            } else {
                emptyStateEl.classList.add('d-none');
                if (query === '' && currentCategory === 'All') {
                    matchCountEl.textContent = `Showing all ${visibleCount} examination papers`;
                } else {
                    matchCountEl.textContent = `Showing ${visibleCount} of ${items.length} examination papers`;
                }
            }
        }

        // Category Filter Pill Selector
        function setCategoryFilter(categoryKey, btnEl) {
            currentCategory = categoryKey;
            
            // Update active pill styling
            document.querySelectorAll('.filter-pill').forEach(pill => pill.classList.remove('active'));
            if (btnEl) btnEl.classList.add('active');

            filterSubjects();
        }

        function clearSearch() {
            document.getElementById('subjectSearchInput').value = '';
            document.getElementById('clearSearchBtn').style.display = 'none';
            filterSubjects();
        }

        function clearSearchAndCategory() {
            document.getElementById('subjectSearchInput').value = '';
            document.getElementById('clearSearchBtn').style.display = 'none';
            currentCategory = 'All';
            document.querySelectorAll('.filter-pill').forEach(pill => {
                if (pill.getAttribute('data-category') === 'All') {
                    pill.classList.add('active');
                } else {
                    pill.classList.remove('active');
                }
            });
            filterSubjects();
        }

        // Initialize on load
        document.addEventListener('DOMContentLoaded', () => {
            updateExamParams();
            filterSubjects();
        });

        // Prompt start exam using SweetAlert2
        function promptStartExam(subjectSlug, subjectTitle, isRetake) {
            updateExamParams();
            const examTypeName = document.getElementById('examTypeSelect').selectedOptions[0].text;
            const modeName = document.getElementById('examModeSelect').selectedOptions[0].text;
            const std = getSubjectStandard(currentExamType, subjectSlug, currentMode);
            
            Swal.fire({
                title: `${subjectTitle} Examination`,
                html: `
                    <div class="text-start p-3 bg-light rounded border my-2">
                        <p class="mb-1"><strong>Assessment Body:</strong> ${examTypeName}</p>
                        <p class="mb-1"><strong>Paper Year:</strong> ${currentYear} Session</p>
                        <p class="mb-1"><strong>Assessment Mode:</strong> ${modeName}</p>
                        <p class="mb-1"><strong>Allocated Time:</strong> <span class="text-primary fw-bold">${std.duration} Minutes</span></p>
                        <p class="mb-0"><strong>Questions:</strong> <span class="text-primary fw-bold">${std.questions} Official Questions</span></p>
                    </div>
                    <div class="text-muted small mt-2">
                        <i class="bi bi-exclamation-triangle-fill text-warning me-1"></i>
                        The countdown timer will initiate immediately once you confirm. Do not close or refresh your browser.
                    </div>
                `,
                icon: isRetake ? 'question' : 'info',
                showCancelButton: true,
                confirmButtonColor: '#2563eb',
                cancelButtonColor: '#64748b',
                confirmButtonText: '<i class="bi bi-play-fill me-1"></i> Begin Examination Now',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        title: 'Loading Exam Paper...',
                        html: '<span class="text-muted">Fetching question bank and calibrating session...</span>',
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });
                    setTimeout(() => {
                        window.location.href = `exam.php?subject=${subjectSlug}&exam_type=${currentExamType}&year=${currentYear}&mode=${currentMode}`;
                    }, 500);
                }
            });
        }

        // Confirm logout with SweetAlert2
        function confirmLogout() {
            Swal.fire({
                title: 'Sign Out Confirmation',
                text: 'Are you sure you want to end your candidate session?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc2626',
                cancelButtonColor: '#64748b',
                confirmButtonText: 'Yes, Sign Out',
                cancelButtonText: 'Stay on Portal'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = 'logout.php';
                }
            });
        }

        <?php if (isset($_GET['msg']) && $_GET['msg'] === 'exam_cancelled'): ?>
        document.addEventListener('DOMContentLoaded', () => {
            Swal.fire({
                icon: 'info',
                title: 'Examination Cancelled',
                text: 'Your examination room session was safely cancelled. No score was recorded, and you can retake the exam whenever you are ready.',
                confirmButtonColor: '#2563eb'
            });
        });
        <?php endif; ?>
    </script>
</body>
</html>
