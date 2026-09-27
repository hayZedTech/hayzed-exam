<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HayZed CBT Exam Portal | Online Assessment System</title>
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
            --brand-secondary: #0ea5e9;
            --brand-success: #10b981;
            --dark-bg: #090e1a;
            --card-radius: 1.25rem;
        }

        * {
            box-sizing: border-box;
        }

        body {
            font-family: 'Plus Jakarta Sans', system-ui, -apple-system, sans-serif;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            background: 
                radial-gradient(circle at 15% 15%, rgba(37, 99, 235, 0.18) 0%, transparent 45%),
                radial-gradient(circle at 85% 85%, rgba(14, 165, 233, 0.14) 0%, transparent 45%),
                radial-gradient(circle at 50% 50%, rgba(16, 185, 129, 0.05) 0%, transparent 60%),
                linear-gradient(145deg, #090e1a 0%, #0f172a 45%, #131d31 100%);
            background-attachment: fixed;
            color: #f1f5f9;
            position: relative;
            overflow-x: hidden;
        }

        /* Subtle grid pattern overlay */
        body::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-image: radial-gradient(rgba(255, 255, 255, 0.06) 1px, transparent 1px);
            background-size: 32px 32px;
            pointer-events: none;
            z-index: 0;
        }

        .main-wrapper {
            position: relative;
            z-index: 1;
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2.5rem 1rem;
        }

        /* Navbar */
        .custom-nav {
            position: relative;
            z-index: 2;
            padding: 1rem 0;
            backdrop-filter: blur(12px);
            background: rgba(9, 14, 26, 0.7);
            border-bottom: 1px solid rgba(255, 255, 255, 0.08);
        }

        .brand-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.6rem;
            text-decoration: none;
            color: #fff;
            font-weight: 700;
            font-size: 1.25rem;
            letter-spacing: -0.02em;
        }

        .brand-icon-box {
            width: 40px;
            height: 40px;
            border-radius: 10px;
            background: linear-gradient(135deg, #2563eb, #06b6d4);
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 0 20px rgba(37, 99, 235, 0.5);
            color: #fff;
            font-size: 1.25rem;
        }

        /* Hero presentation card */
        .hero-section {
            padding: 1rem 1.5rem;
        }

        .status-pill {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.35rem 0.9rem;
            border-radius: 9999px;
            font-size: 0.8rem;
            font-weight: 600;
            background: rgba(16, 185, 129, 0.12);
            color: #34d399;
            border: 1px solid rgba(52, 211, 153, 0.25);
            margin-bottom: 1.25rem;
        }

        .status-dot {
            width: 8px;
            height: 8px;
            background: #10b981;
            border-radius: 50%;
            box-shadow: 0 0 10px #10b981;
            animation: pulse-dot 2s infinite;
        }

        @keyframes pulse-dot {
            0%, 100% { opacity: 1; transform: scale(1); }
            50% { opacity: 0.4; transform: scale(0.85); }
        }

        .hero-title {
            font-size: clamp(2rem, 3.8vw, 3rem);
            font-weight: 800;
            line-height: 1.15;
            letter-spacing: -0.03em;
            color: #ffffff;
            margin-bottom: 1rem;
        }

        .hero-gradient-text {
            background: linear-gradient(135deg, #60a5fa 0%, #38bdf8 50%, #34d399 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .hero-subtitle {
            color: #94a3b8;
            font-size: 1.05rem;
            line-height: 1.6;
            margin-bottom: 2rem;
            max-width: 520px;
        }

        /* Feature items */
        .feature-item {
            display: flex;
            align-items: flex-start;
            gap: 1rem;
            margin-bottom: 1.25rem;
        }

        .feature-icon-wrapper {
            flex-shrink: 0;
            width: 42px;
            height: 42px;
            border-radius: 10px;
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.1);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.15rem;
            color: #38bdf8;
        }

        .feature-text h6 {
            color: #f8fafc;
            font-weight: 600;
            margin-bottom: 0.2rem;
            font-size: 0.95rem;
        }

        .feature-text p {
            color: #94a3b8;
            font-size: 0.85rem;
            margin-bottom: 0;
            line-height: 1.45;
        }

        /* Auth Card */
        .auth-card {
            background: rgba(255, 255, 255, 0.98);
            border-radius: var(--card-radius);
            box-shadow: 
                0 25px 50px -12px rgba(0, 0, 0, 0.5),
                0 0 0 1px rgba(255, 255, 255, 0.15);
            color: #1e293b;
            padding: 2.25rem;
            position: relative;
            overflow: hidden;
        }

        /* Decorative top gradient bar on auth card */
        .auth-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 5px;
            background: linear-gradient(90deg, #2563eb, #0ea5e9, #10b981);
        }

        /* Custom Modern Tabs */
        .auth-tabs {
            background: #f1f5f9;
            padding: 0.35rem;
            border-radius: 0.75rem;
            display: flex;
            border: 1px solid #e2e8f0;
            margin-bottom: 1.75rem;
            width: 100%;
        }

        .auth-tabs .nav-item {
            flex: 1 1 0;
            display: flex;
        }

        .auth-tabs .nav-item.d-none {
            display: none !important;
        }

        .auth-tabs .nav-link {
            width: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            border: none;
            padding: 0.7rem 1rem;
            font-size: 0.95rem;
            font-weight: 600;
            color: #64748b;
            border-radius: 0.55rem;
            transition: all 0.2s ease-in-out;
            background: transparent;
        }

        .auth-tabs .nav-link:hover {
            color: #1e293b;
        }

        .auth-tabs .nav-link.active {
            background: #ffffff;
            color: #2563eb;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
        }

        /* Form Inputs */
        .form-floating-custom {
            margin-bottom: 1.25rem;
        }

        .form-floating-custom label {
            font-size: 0.85rem;
            font-weight: 600;
            color: #475569;
            margin-bottom: 0.4rem;
            display: block;
        }

        .input-icon-group {
            position: relative;
        }

        .input-icon-group .icon-addon {
            position: absolute;
            left: 1rem;
            top: 50%;
            transform: translateY(-50%);
            color: #94a3b8;
            font-size: 1.1rem;
            pointer-events: none;
            transition: color 0.2s;
            z-index: 4;
        }

        .modern-input {
            width: 100%;
            padding: 0.75rem 1rem 0.75rem 2.85rem;
            font-size: 0.95rem;
            color: #1e293b;
            background: #f8fafc;
            border: 1.5px solid #e2e8f0;
            border-radius: 0.75rem;
            transition: all 0.2s ease;
        }

        .modern-input.has-toggle {
            padding-right: 2.85rem;
        }

        .modern-input:focus {
            background: #ffffff;
            border-color: #2563eb;
            box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.12);
            outline: none;
        }

        .modern-input:focus + .icon-addon,
        .input-icon-group:focus-within .icon-addon {
            color: #2563eb;
        }

        .pw-toggle-btn {
            position: absolute;
            right: 0.75rem;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: #94a3b8;
            font-size: 1.15rem;
            cursor: pointer;
            padding: 0.25rem 0.4rem;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 0.5rem;
            transition: all 0.15s;
            z-index: 5;
        }

        .pw-toggle-btn:hover {
            color: #2563eb;
            background: rgba(37, 99, 235, 0.08);
        }

        .pw-strength-bar {
            height: 5px;
            border-radius: 3px;
            background: #e2e8f0;
            overflow: hidden;
            margin-top: 0.45rem;
            margin-bottom: 0.25rem;
        }

        .pw-strength-fill {
            height: 100%;
            width: 0%;
            border-radius: 3px;
            transition: all 0.3s ease;
        }

        .pw-strength-text {
            font-size: 0.78rem;
            font-weight: 600;
        }

        /* Buttons */
        .btn-modern-primary {
            background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
            border: none;
            color: #ffffff;
            font-weight: 700;
            font-size: 1rem;
            padding: 0.85rem 1.5rem;
            border-radius: 0.75rem;
            box-shadow: 0 10px 20px -5px rgba(37, 99, 235, 0.4);
            transition: all 0.25s ease;
            width: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
        }

        .btn-modern-primary:hover {
            background: linear-gradient(135deg, #1d4ed8 0%, #1e40af 100%);
            transform: translateY(-1px);
            box-shadow: 0 14px 24px -5px rgba(37, 99, 235, 0.5);
            color: #fff;
        }

        .btn-modern-success {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            border: none;
            color: #ffffff;
            font-weight: 700;
            font-size: 1rem;
            padding: 0.85rem 1.5rem;
            border-radius: 0.75rem;
            box-shadow: 0 10px 20px -5px rgba(16, 185, 129, 0.4);
            transition: all 0.25s ease;
            width: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
        }

        .btn-modern-success:hover {
            background: linear-gradient(135deg, #059669 0%, #047857 100%);
            transform: translateY(-1px);
            box-shadow: 0 14px 24px -5px rgba(16, 185, 129, 0.5);
            color: #fff;
        }

        .auth-switch-text {
            text-align: center;
            font-size: 0.88rem;
            color: #64748b;
            margin-top: 1.25rem;
            margin-bottom: 0;
        }

        .auth-switch-text a {
            color: #2563eb;
            font-weight: 600;
            text-decoration: none;
            cursor: pointer;
        }

        .auth-switch-text a:hover {
            text-decoration: underline;
        }

        /* Footer */
        .custom-footer {
            position: relative;
            z-index: 2;
            padding: 1.25rem 0;
            text-align: center;
            color: #64748b;
            font-size: 0.85rem;
            border-top: 1px solid rgba(255, 255, 255, 0.06);
            backdrop-filter: blur(10px);
            background: rgba(9, 14, 26, 0.5);
        }

        /* Sleek Loading Overlay */
        #spinner-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: #090e1a;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            z-index: 99999;
            transition: opacity 0.35s ease, visibility 0.35s ease;
        }

        .loader-ring {
            width: 54px;
            height: 54px;
            border: 3.5px solid rgba(37, 99, 235, 0.2);
            border-top-color: #38bdf8;
            border-radius: 50%;
            animation: spin 0.85s linear infinite;
        }

        .loader-label {
            margin-top: 1rem;
            font-size: 0.9rem;
            font-weight: 600;
            letter-spacing: 0.05em;
            color: #94a3b8;
            text-transform: uppercase;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }

        @media (max-width: 991.98px) {
            .hero-section {
                text-align: center;
                margin-bottom: 2rem;
            }
            .hero-subtitle {
                margin-left: auto;
                margin-right: auto;
            }
            .feature-item {
                text-align: left;
                justify-content: center;
            }
        }
    </style>
</head>
<body>

    <!-- Minimal Modern Preloader -->
    <div id="spinner-overlay">
        <div class="loader-ring"></div>
        <div class="loader-label">Starting Exam Portal...</div>
    </div>

    <!-- Navigation Header -->
    <header class="custom-nav">
        <div class="container d-flex align-items-center justify-content-between">
            <a href="index.php" class="brand-badge">
                <div class="brand-icon-box">
                    <i class="bi bi-mortarboard-fill"></i>
                </div>
                <span>HayZed <span class="hero-gradient-text">ExamPortal</span></span>
            </a>
            <div class="d-flex align-items-center gap-2">
                <span class="d-none d-md-inline-block text-secondary small fw-medium">
                    <i class="bi bi-shield-check text-success me-1"></i> CBT Secure Engine
                </span>
                <a href="biology02.php" class="btn btn-outline-light btn-sm rounded-pill px-3 py-1 fw-semibold">
                    <i class="bi bi-lightning-charge me-1 text-warning"></i> Quick Practice
                </a>
            </div>
        </div>
    </header>

    <!-- Main Content Area -->
    <main class="main-wrapper">
        <div class="container">
            <div class="row align-items-center justify-content-center g-4 g-lg-5">
                
                <!-- Left Column: Hero Information -->
                <div class="col-lg-6 hero-section">
                    <div class="status-pill">
                        <span class="status-dot"></span>
                        <span>2026 Examination Session Active</span>
                    </div>

                    <h1 class="hero-title">
                        Excellence in <br>
                        <span class="hero-gradient-text">Computer Based Testing</span>
                    </h1>

                    <p class="hero-subtitle">
                        Fast, reliable, and standardized online testing. Sign in with your registered candidate email to begin your examination papers or enroll as a new candidate.
                    </p>

                    <div class="features-list d-none d-md-block">
                        <div class="feature-item">
                            <div class="feature-icon-wrapper">
                                <i class="bi bi-stopwatch"></i>
                            </div>
                            <div class="feature-text">
                                <h6>Timed Examination Papers</h6>
                                <p>Automated countdown timer with auto-submit protection ensures fair assessment.</p>
                            </div>
                        </div>

                        <div class="feature-item">
                            <div class="feature-icon-wrapper">
                                <i class="bi bi-bar-chart-line"></i>
                            </div>
                            <div class="feature-text">
                                <h6>Instant Result Computation</h6>
                                <p>View real-time scores immediately after submitting each subject module.</p>
                            </div>
                        </div>

                        <div class="feature-item">
                            <div class="feature-icon-wrapper">
                                <i class="bi bi-fingerprint"></i>
                            </div>
                            <div class="feature-text">
                                <h6>Unique Candidate Identification</h6>
                                <p>Automated exam index generation (25/xxxxxx) tied securely to your profile.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Column: Auth Card -->
                <div class="col-lg-5 col-md-8 col-sm-11">
                    <div class="auth-card">
                        
                        <!-- Nav Tabs Switcher -->
                        <ul class="nav auth-tabs nav-fill w-100" id="authTab" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" id="login-tab" data-bs-toggle="tab" data-bs-target="#login-panel" type="button" role="tab" aria-controls="login-panel" aria-selected="true" onclick="hideResetTab()">
                                    <i class="bi bi-box-arrow-in-right me-1"></i> Sign In
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="signup-tab" data-bs-toggle="tab" data-bs-target="#signup-panel" type="button" role="tab" aria-controls="signup-panel" aria-selected="false" onclick="hideResetTab()">
                                    <i class="bi bi-person-plus me-1"></i> Register
                                </button>
                            </li>
                            <!-- Hidden until candidate clicks 'Forgot Password?' -->
                            <li class="nav-item d-none" id="reset-tab-item" role="presentation">
                                <button class="nav-link" id="reset-tab" data-bs-toggle="tab" data-bs-target="#reset-panel" type="button" role="tab" aria-controls="reset-panel" aria-selected="false">
                                    <i class="bi bi-key me-1"></i> Reset Password
                                </button>
                            </li>
                        </ul>

                        <!-- Tab Content -->
                        <div class="tab-content" id="authTabContent">
                            
                            <!-- Login Panel -->
                            <div class="tab-pane fade show active" id="login-panel" role="tabpanel" aria-labelledby="login-tab">
                                <div class="mb-4">
                                    <h4 class="fw-bold mb-1 text-dark">Candidate Sign In</h4>
                                    <p class="text-muted small mb-0">Enter your email and password to access your CBT dashboard</p>
                                </div>

                                <form action="login.php" method="post" id="loginForm">
                                    <input type="hidden" name="submit" value="1">
                                    
                                    <div class="form-floating-custom">
                                        <label for="login-email">Registered Email Address</label>
                                        <div class="input-icon-group">
                                            <input type="email" class="modern-input" id="login-email" name="email" placeholder="name@example.com" required autocomplete="email">
                                            <span class="icon-addon"><i class="bi bi-envelope"></i></span>
                                        </div>
                                    </div>

                                    <div class="form-floating-custom">
                                        <div class="d-flex justify-content-between align-items-center mb-1">
                                            <label for="login-password" class="mb-0">Password</label>
                                            <a href="javascript:void(0)" onclick="openResetPassword()" class="text-primary text-decoration-none small fw-semibold">
                                                <i class="bi bi-question-circle me-1"></i>Forgot Password?
                                            </a>
                                        </div>
                                        <div class="input-icon-group">
                                            <input type="password" class="modern-input has-toggle" id="login-password" name="password" placeholder="Enter your password" required autocomplete="current-password">
                                            <span class="icon-addon"><i class="bi bi-lock"></i></span>
                                            <button type="button" class="pw-toggle-btn" onclick="togglePasswordVisibility('login-password', this)" title="Show/Hide Password" tabindex="-1">
                                                <i class="bi bi-eye"></i>
                                            </button>
                                        </div>
                                    </div>

                                    <button type="submit" name="submit" class="btn-modern-primary mt-2" data-loading-text="Authenticating...">
                                        <span>Proceed to Exam</span>
                                        <i class="bi bi-arrow-right"></i>
                                    </button>

                                    <p class="auth-switch-text">
                                        New candidate? <a href="javascript:void(0)" onclick="switchTab('signup-tab')">Create an account</a>
                                    </p>
                                </form>
                            </div>

                            <!-- Signup Panel -->
                            <div class="tab-pane fade" id="signup-panel" role="tabpanel" aria-labelledby="signup-tab">
                                <div class="mb-3">
                                    <h4 class="fw-bold mb-1 text-dark">Candidate Registration</h4>
                                    <p class="text-muted small mb-0">Create your official candidate profile to begin taking examinations</p>
                                </div>

                                <form action="signup.php" method="post" id="signupForm">
                                    <input type="hidden" name="submit" value="1">

                                    <!-- Anti-Duplicate Policy Notice -->
                                    <div class="p-2 px-3 mb-3 rounded-3 bg-light border text-muted small d-flex align-items-center gap-2">
                                        <i class="bi bi-shield-check text-primary flex-shrink-0 fs-5"></i>
                                        <span><strong>One-Candidate Policy:</strong> Duplicate registrations with the same email are strictly disallowed.</span>
                                    </div>

                                    <div class="form-floating-custom">
                                        <label for="signup-name">Full Candidate Name</label>
                                        <div class="input-icon-group">
                                            <input type="text" class="modern-input" id="signup-name" name="name" placeholder="e.g. Azeez Ololade" required autocomplete="name">
                                            <span class="icon-addon"><i class="bi bi-person"></i></span>
                                        </div>
                                    </div>

                                    <div class="form-floating-custom">
                                        <label for="signup-email">Active Email Address</label>
                                        <div class="input-icon-group">
                                            <input type="email" class="modern-input" id="signup-email" name="email" placeholder="name@example.com" required autocomplete="email">
                                            <span class="icon-addon"><i class="bi bi-envelope"></i></span>
                                        </div>
                                    </div>

                                    <div class="form-floating-custom">
                                        <label for="signup-password">Account Password</label>
                                        <div class="input-icon-group">
                                            <input type="password" class="modern-input has-toggle" id="signup-password" name="password" placeholder="Min. 6 characters" required minlength="6" autocomplete="new-password" oninput="checkSignupStrength(this.value)">
                                            <span class="icon-addon"><i class="bi bi-lock"></i></span>
                                            <button type="button" class="pw-toggle-btn" onclick="togglePasswordVisibility('signup-password', this)" title="Show/Hide Password" tabindex="-1">
                                                <i class="bi bi-eye"></i>
                                            </button>
                                        </div>
                                        <div class="pw-strength-bar">
                                            <div class="pw-strength-fill" id="pwStrengthFill"></div>
                                        </div>
                                        <div class="d-flex justify-content-between align-items-center">
                                            <span class="pw-strength-text text-muted" id="pwStrengthText">Strength: None</span>
                                            <small class="text-muted">Min. 6 characters</small>
                                        </div>
                                    </div>

                                    <div class="form-floating-custom">
                                        <label for="signup-confirm-password">Confirm Password</label>
                                        <div class="input-icon-group">
                                            <input type="password" class="modern-input has-toggle" id="signup-confirm-password" name="confirm_password" placeholder="Re-enter your password" required minlength="6" autocomplete="new-password" oninput="checkSignupMatch()">
                                            <span class="icon-addon"><i class="bi bi-shield-lock"></i></span>
                                            <button type="button" class="pw-toggle-btn" onclick="togglePasswordVisibility('signup-confirm-password', this)" title="Show/Hide Password" tabindex="-1">
                                                <i class="bi bi-eye"></i>
                                            </button>
                                        </div>
                                        <div id="signupMatchText" class="small mt-1" style="display: none;"></div>
                                    </div>

                                    <button type="submit" name="submit" class="btn-modern-success mt-2" data-loading-text="Creating Account...">
                                        <span>Register &amp; Generate Exam ID</span>
                                        <i class="bi bi-check2-circle"></i>
                                    </button>

                                    <p class="auth-switch-text">
                                        Already registered? <a href="javascript:void(0)" onclick="switchTab('login-tab')">Sign in here</a>
                                    </p>
                                </form>
                            </div>

                            <!-- Reset Password Panel -->
                            <div class="tab-pane fade" id="reset-panel" role="tabpanel" aria-labelledby="reset-tab">
                                <div class="mb-3 d-flex align-items-center justify-content-between">
                                    <div>
                                        <h4 class="fw-bold mb-1 text-dark">Reset Password</h4>
                                        <p class="text-muted small mb-0">Verify your candidate identity to securely create a new password</p>
                                    </div>
                                    <button type="button" class="btn btn-sm btn-outline-secondary rounded-pill px-3" onclick="closeResetPassword()">
                                        <i class="bi bi-arrow-left me-1"></i> Back
                                    </button>
                                </div>

                                <form action="reset_password.php" method="post" id="resetForm">
                                    <input type="hidden" name="submit" value="1">
                                    
                                    <div class="form-floating-custom">
                                        <label for="reset-email">Registered Email Address</label>
                                        <div class="input-icon-group">
                                            <input type="email" class="modern-input" id="reset-email" name="email" placeholder="name@example.com" required autocomplete="email">
                                            <span class="icon-addon"><i class="bi bi-envelope"></i></span>
                                        </div>
                                    </div>

                                    <div class="form-floating-custom">
                                        <label for="reset-identifier">
                                            Identity Verification 
                                            <span class="text-muted fw-normal small">(Exam ID or Registered Full Name)</span>
                                        </label>
                                        <div class="input-icon-group">
                                            <input type="text" class="modern-input" id="reset-identifier" name="identifier" placeholder="e.g. 24/2CA7FE9 or Azeez Ololade" required>
                                            <span class="icon-addon"><i class="bi bi-person-badge"></i></span>
                                        </div>
                                        <small class="text-muted d-block mt-1">Enter either your official Exam ID or your full name as registered.</small>
                                    </div>

                                    <div class="form-floating-custom">
                                        <label for="reset-password">New Password</label>
                                        <div class="input-icon-group">
                                            <input type="password" class="modern-input has-toggle" id="reset-password" name="new_password" placeholder="Min. 6 characters" required minlength="6" autocomplete="new-password">
                                            <span class="icon-addon"><i class="bi bi-lock"></i></span>
                                            <button type="button" class="pw-toggle-btn" onclick="togglePasswordVisibility('reset-password', this)" title="Show/Hide Password" tabindex="-1">
                                                <i class="bi bi-eye"></i>
                                            </button>
                                        </div>
                                    </div>

                                    <div class="form-floating-custom">
                                        <label for="reset-confirm-password">Confirm New Password</label>
                                        <div class="input-icon-group">
                                            <input type="password" class="modern-input has-toggle" id="reset-confirm-password" name="confirm_password" placeholder="Re-enter new password" required minlength="6" autocomplete="new-password" oninput="checkResetMatch()">
                                            <span class="icon-addon"><i class="bi bi-shield-lock"></i></span>
                                            <button type="button" class="pw-toggle-btn" onclick="togglePasswordVisibility('reset-confirm-password', this)" title="Show/Hide Password" tabindex="-1">
                                                <i class="bi bi-eye"></i>
                                            </button>
                                        </div>
                                        <div id="resetMatchText" class="small mt-1" style="display: none;"></div>
                                    </div>

                                    <button type="submit" name="submit" class="btn-modern-primary mt-2" data-loading-text="Updating Password...">
                                        <span>Update Password</span>
                                        <i class="bi bi-check2-circle"></i>
                                    </button>

                                    <p class="auth-switch-text">
                                        Remembered your password? <a href="javascript:void(0)" onclick="closeResetPassword()">Back to Sign In</a>
                                    </p>
                                </form>
                            </div>

                        </div> <!-- end tab-content -->
                    </div> <!-- end auth-card -->
                </div> <!-- end col -->

            </div> <!-- end row -->
        </div> <!-- end container -->
    </main>

    <!-- Footer -->
    <footer class="custom-footer">
        <div class="container d-flex flex-column flex-sm-row align-items-center justify-content-between gap-2">
            <div>
                &copy; <?php echo date('Y'); ?> <strong>HayZed Tech</strong>. All rights reserved.
            </div>
            <div class="d-flex align-items-center gap-3">
                <span><i class="bi bi-lock-fill text-success me-1"></i> SSL 256-bit Encrypted</span>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS Bundle -->
    <script src="bootstrap.bundle.min.js"></script>

    <script>
        // Dismiss preloader
        window.addEventListener('load', () => {
            const overlay = document.getElementById('spinner-overlay');
            if (overlay) {
                overlay.style.opacity = '0';
                setTimeout(() => {
                    overlay.style.display = 'none';
                }, 350);
            }
        });

        // Helper to programmatically switch tabs
        function switchTab(tabId) {
            const tabEl = document.getElementById(tabId);
            if (tabEl && window.bootstrap) {
                const tab = new bootstrap.Tab(tabEl);
                tab.show();
            }
        }

        // Open Reset Password View (reveals tab item and switches to it)
        function openResetPassword() {
            const resetItem = document.getElementById('reset-tab-item');
            if (resetItem) resetItem.classList.remove('d-none');

            // Pre-fill email from login form if already entered
            const loginEmail = document.getElementById('login-email');
            const resetEmail = document.getElementById('reset-email');
            if (loginEmail && resetEmail && loginEmail.value.trim()) {
                resetEmail.value = loginEmail.value.trim();
            }

            switchTab('reset-tab');
        }

        // Close Reset Password View (hides reset tab and returns to Sign In)
        function closeResetPassword() {
            hideResetTab();
            switchTab('login-tab');
        }

        function hideResetTab() {
            const resetItem = document.getElementById('reset-tab-item');
            if (resetItem) resetItem.classList.add('d-none');
        }

        // Toggle Show / Hide Password
        function togglePasswordVisibility(inputId, btnEl) {
            const input = document.getElementById(inputId);
            if (!input) return;
            const icon = btnEl.querySelector('i');

            if (input.type === 'password') {
                input.type = 'text';
                if (icon) {
                    icon.classList.remove('bi-eye');
                    icon.classList.add('bi-eye-slash');
                }
            } else {
                input.type = 'password';
                if (icon) {
                    icon.classList.remove('bi-eye-slash');
                    icon.classList.add('bi-eye');
                }
            }
        }

        // Live Password Strength Meter for Signup
        function checkSignupStrength(val) {
            const fill = document.getElementById('pwStrengthFill');
            const text = document.getElementById('pwStrengthText');
            if (!fill || !text) return;

            if (!val || val.length === 0) {
                fill.style.width = '0%';
                fill.className = 'pw-strength-fill';
                text.textContent = 'Strength: None';
                text.className = 'pw-strength-text text-muted';
                return;
            }

            if (val.length < 6) {
                fill.style.width = '25%';
                fill.style.backgroundColor = '#ef4444';
                text.textContent = 'Weak (min. 6 characters)';
                text.className = 'pw-strength-text text-danger';
                return;
            }

            let score = 0;
            if (val.length >= 6) score++;
            if (val.length >= 8) score++;
            if (/[0-9]/.test(val)) score++;
            if (/[a-zA-Z]/.test(val)) score++;
            if (/[^a-zA-Z0-9]/.test(val)) score++;

            if (score <= 2) {
                fill.style.width = '40%';
                fill.style.backgroundColor = '#f59e0b';
                text.textContent = 'Fair';
                text.className = 'pw-strength-text text-warning';
            } else if (score <= 4) {
                fill.style.width = '75%';
                fill.style.backgroundColor = '#0ea5e9';
                text.textContent = 'Good';
                text.className = 'pw-strength-text text-info';
            } else {
                fill.style.width = '100%';
                fill.style.backgroundColor = '#10b981';
                text.textContent = 'Strong';
                text.className = 'pw-strength-text text-success';
            }

            checkSignupMatch();
        }

        // Live Password Match Validation for Signup
        function checkSignupMatch() {
            const p1 = document.getElementById('signup-password');
            const p2 = document.getElementById('signup-confirm-password');
            const matchEl = document.getElementById('signupMatchText');
            if (!p1 || !p2 || !matchEl) return;

            if (!p2.value) {
                matchEl.style.display = 'none';
                return;
            }

            matchEl.style.display = 'block';
            if (p1.value === p2.value) {
                matchEl.className = 'small mt-1 text-success fw-semibold';
                matchEl.innerHTML = '<i class="bi bi-check-circle-fill me-1"></i> Passwords match';
            } else {
                matchEl.className = 'small mt-1 text-danger fw-semibold';
                matchEl.innerHTML = '<i class="bi bi-x-circle-fill me-1"></i> Passwords do not match';
            }
        }

        // Live Password Match Validation for Reset
        function checkResetMatch() {
            const p1 = document.getElementById('reset-password');
            const p2 = document.getElementById('reset-confirm-password');
            const matchEl = document.getElementById('resetMatchText');
            if (!p1 || !p2 || !matchEl) return;

            if (!p2.value) {
                matchEl.style.display = 'none';
                return;
            }

            matchEl.style.display = 'block';
            if (p1.value === p2.value) {
                matchEl.className = 'small mt-1 text-success fw-semibold';
                matchEl.innerHTML = '<i class="bi bi-check-circle-fill me-1"></i> Passwords match';
            } else {
                matchEl.className = 'small mt-1 text-danger fw-semibold';
                matchEl.innerHTML = '<i class="bi bi-x-circle-fill me-1"></i> Passwords do not match';
            }
        }

        // Add loading state on button click & form submit
        document.querySelectorAll('form').forEach(form => {
            form.addEventListener('submit', function (e) {
                if (!this.checkValidity()) {
                    return;
                }
                const btn = this.querySelector('button[type="submit"]');
                if (btn && !btn.classList.contains('is-loading')) {
                    btn.dataset.originalHtml = btn.innerHTML;
                    btn.classList.add('is-loading');
                    btn.style.pointerEvents = 'none';
                    btn.style.opacity = '0.85';
                    const loadingText = btn.dataset.loadingText || 'Please wait...';
                    btn.innerHTML = `
                        <span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>
                        <span>${loadingText}</span>
                    `;
                }
            });
        });

        // Restore button state on pageshow (e.g. if navigated back from cache)
        window.addEventListener('pageshow', () => {
            document.querySelectorAll('button[type="submit"].is-loading').forEach(btn => {
                btn.classList.remove('is-loading');
                btn.style.pointerEvents = '';
                btn.style.opacity = '';
                if (btn.dataset.originalHtml) {
                    btn.innerHTML = btn.dataset.originalHtml;
                }
            });
        });

        // Handle URL parameters for Tab Switching and Toasts
        document.addEventListener('DOMContentLoaded', () => {
            const params = new URLSearchParams(window.location.search);
            const tabParam = params.get('tab');
            if (tabParam === 'reset') {
                openResetPassword();
            } else if (tabParam === 'signup') {
                hideResetTab();
                switchTab('signup-tab');
            } else {
                hideResetTab();
            }

            // After reset is successful, ensure candidate is on login tab
            if (params.get('reset') === 'success') {
                hideResetTab();
                switchTab('login-tab');

                const emailParam = params.get('email');
                if (emailParam) {
                    const loginEmail = document.getElementById('login-email');
                    if (loginEmail) loginEmail.value = emailParam;
                    const loginPw = document.getElementById('login-password');
                    if (loginPw) loginPw.focus();
                }

                // Clean URL query parameters so refresh does not re-trigger toast
                window.history.replaceState({}, document.title, window.location.pathname);

                Swal.fire({
                    icon: 'success',
                    title: 'Password Updated!',
                    text: 'Your password was successfully reset. You can now sign in with your new password.',
                    confirmButtonColor: '#2563eb'
                });
            }
        });
    </script>
</body>
</html>
