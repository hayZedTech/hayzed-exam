<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Biology Quick Practice Mode | HayZed CBT</title>
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
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Plus Jakarta Sans', system-ui, -apple-system, sans-serif;
            background: #f8fafc;
            color: #1e293b;
            min-height: 100vh;
        }

        .practice-header {
            background: #090e1a;
            color: #fff;
            padding: 1rem 0;
            border-bottom: 2px solid #10b981;
        }

        .practice-card {
            background: #ffffff;
            border-radius: 1rem;
            border: 1px solid #e2e8f0;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.04);
            padding: 1.75rem;
            margin-bottom: 1.5rem;
            transition: all 0.2s;
        }

        .practice-card:hover {
            border-color: #cbd5e1;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.06);
        }

        .opt-box {
            border: 1.5px solid #e2e8f0;
            border-radius: 0.75rem;
            padding: 0.75rem 1rem;
            margin-bottom: 0.65rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            cursor: pointer;
            transition: all 0.15s;
            background: #f8fafc;
        }

        .opt-box:hover {
            background: #f1f5f9;
            border-color: #94a3b8;
        }

        .opt-box.is-correct {
            background: #dcfce7 !important;
            border-color: #10b981 !important;
            color: #166534 !important;
            font-weight: 600;
        }

        .opt-box.is-wrong {
            background: #fee2e2 !important;
            border-color: #ef4444 !important;
            color: #991b1b !important;
            font-weight: 600;
        }

        .feedback-badge {
            font-size: 0.88rem;
            font-weight: 600;
            margin-top: 0.75rem;
            padding: 0.5rem 1rem;
            border-radius: 0.5rem;
            display: none;
        }
    </style>
</head>
<body>

    <!-- Top Navigation -->
    <header class="practice-header">
        <div class="container d-flex align-items-center justify-content-between">
            <div class="d-flex align-items-center gap-2">
                <i class="bi bi-lightning-charge-fill text-warning fs-4"></i>
                <span class="fw-bold text-white fs-5">Quick Practice: Biology</span>
                <span class="badge bg-success-subtle text-success border border-success-subtle ms-2">Untimed Study Mode</span>
            </div>
            <a href="index.php" class="btn btn-outline-light btn-sm rounded-pill px-3 py-1">
                <i class="bi bi-house-door me-1"></i> Return Home
            </a>
        </div>
    </header>

    <!-- Content -->
    <main class="container my-4 my-md-5" style="max-width: 860px;">
        
        <div class="p-3 mb-4 rounded-3 bg-white border d-flex align-items-center justify-content-between">
            <div>
                <h5 class="fw-bold text-dark mb-1">Interactive Self-Study Questions</h5>
                <p class="text-muted small mb-0">Select an answer to receive instant feedback. Click "Check Total Score" when finished.</p>
            </div>
            <button type="button" onclick="checkAllScore()" class="btn btn-primary rounded-pill px-4 fw-bold">
                <i class="bi bi-check2-circle me-1"></i> Check Total Score
            </button>
        </div>

        <form id="practiceForm">
            <?php
            $questions = [
                1 => ["question" => "The modification in structure, physiology and behaviour of plant and animal over generations is termed ______",
                      "options" => ["Adaptation" => 1, "Evolution" => 0, "Variation" => 0, "Succession" => 0]],
                2 => ["question" => "A bacterium that is spherically shaped is known as a ______",
                      "options" => ["Diplobacillus" => 0, "Coccus" => 1, "Bacillus" => 0, "Vibrio" => 0]],
                3 => ["question" => "The flame cells are specialized structures used for excretion in ______",
                      "options" => ["Liver Fluke" => 1, "Nematode" => 0, "Bacteria" => 0, "Volvox" => 0]],
                4 => ["question" => "Which of the following is an example of an organism acting as a disease vector?",
                      "options" => ["Fungi decomposing dead leaves" => 0, "Mosquito transmitting Plasmodium" => 1, "Lactobacillus in milk fermentation" => 0, "Algae producing oxygen" => 0]],
                5 => ["question" => "Which of the following characteristics relates directly to cellular irritability?",
                      "options" => ["Ability to respond to environmental stimuli" => 1, "Ability to synthesize proteins" => 0, "Ability to generate energy" => 0, "Ability to replicate DNA" => 0]],
                6 => ["question" => "The semi-permeable membrane that surrounds the vacuole in plant cells is the ______",
                      "options" => ["Elaioplast" => 0, "Amyloplast" => 0, "Tonoplast" => 1, "Cytoplast" => 0]],
                7 => ["question" => "Which of the following organs is NOT part of the human alimentary canal?",
                      "options" => ["Oesophagus" => 0, "Large intestine" => 0, "Liver" => 1, "Small intestine" => 0]],
                8 => ["question" => "Which of the following is a characteristic feature of Kingdom Plantae?",
                      "options" => ["Presence of chloroplasts" => 1, "Inability to photosynthesize" => 0, "Lack of cellulose cell walls" => 0, "Obligate heterotrophy" => 0]],
                9 => ["question" => "A biome characterized by hot dry summers, warm winters, and drought-tolerant vegetation is ______",
                      "options" => ["Steppe grassland" => 1, "Temperate desert" => 0, "Savannah grassland" => 0, "Tropical desert" => 0]],
                10 => ["question" => "Which of the following is an example of physiological variation in humans?",
                      "options" => ["Variation in blood pressure among individuals" => 1, "Variation in beak shape among birds" => 0, "Differences in rabbit coat color" => 0, "Leaf shape variations in plants" => 0]],
            ];

            foreach($questions as $num => $q): ?>
                <div class="practice-card" id="q_card_<?= $num ?>">
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <span class="badge bg-light text-primary border fw-bold px-2 py-1">Question <?= $num ?> of 10</span>
                    </div>

                    <h6 class="fw-bold text-dark mb-3"><?= htmlspecialchars($q['question']) ?></h6>

                    <div class="options-list">
                        <?php 
                        $letterMap = ['A', 'B', 'C', 'D'];
                        $idx = 0;
                        foreach($q['options'] as $text => $val): 
                            $letter = $letterMap[$idx] ?? '';
                            $idx++;
                        ?>
                            <div class="opt-box" onclick="handlePracticeOption(<?= $num ?>, this, <?= $val ?>)">
                                <span class="badge bg-light text-dark border"><?= $letter ?></span>
                                <input type="radio" name="pq_<?= $num ?>" value="<?= $val ?>" class="d-none">
                                <span><?= htmlspecialchars($text) ?></span>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <div class="feedback-badge" id="feedback_<?= $num ?>"></div>
                </div>
            <?php endforeach; ?>

            <div class="text-center my-4">
                <button type="button" onclick="checkAllScore()" class="btn btn-success btn-lg rounded-pill px-5 fw-bold shadow">
                    <i class="bi bi-award-fill me-1"></i> Submit Practice &amp; Check Total Score
                </button>
            </div>
        </form>
    </main>

    <!-- Bootstrap JS -->
    <script src="bootstrap.bundle.min.js"></script>

    <script>
        function handlePracticeOption(qNum, element, isCorrect) {
            const card = document.getElementById(`q_card_${qNum}`);
            const feedback = document.getElementById(`feedback_${qNum}`);
            const allOpts = card.querySelectorAll('.opt-box');

            // Reset options in this card
            allOpts.forEach(opt => {
                opt.classList.remove('is-correct', 'is-wrong');
            });

            // Mark radio button
            const radio = element.querySelector('input[type="radio"]');
            if (radio) radio.checked = true;

            feedback.style.display = 'block';

            if (isCorrect === 1) {
                element.classList.add('is-correct');
                feedback.className = 'feedback-badge bg-success-subtle text-success border border-success-subtle';
                feedback.innerHTML = '<i class="bi bi-check-circle-fill me-1"></i> Excellent! That is the correct answer.';
            } else {
                element.classList.add('is-wrong');
                feedback.className = 'feedback-badge bg-danger-subtle text-danger border border-danger-subtle';
                feedback.innerHTML = '<i class="bi bi-x-circle-fill me-1"></i> Incorrect. Review the topic and try again!';

                // Highlight correct option
                allOpts.forEach(opt => {
                    const r = opt.querySelector('input[type="radio"]');
                    if (r && r.value === '1') {
                        opt.classList.add('is-correct');
                    }
                });
            }
        }

        function checkAllScore() {
            let score = 0;
            let answered = 0;

            for (let i = 1; i <= 10; i++) {
                const checked = document.querySelector(`input[name="pq_${i}"]:checked`);
                if (checked) {
                    answered++;
                    score += parseInt(checked.value, 10);
                }
            }

            if (answered < 10) {
                Swal.fire({
                    title: 'Incomplete Practice',
                    html: `You have answered <strong>${answered}</strong> of 10 questions.<br>Please answer all questions to view your complete evaluation.`,
                    icon: 'warning',
                    confirmButtonColor: '#2563eb'
                });
                return;
            }

            const pct = Math.round((score / 10) * 100);

            Swal.fire({
                title: 'Practice Complete!',
                html: `
                    <div class="p-3 my-2 bg-light rounded border text-center">
                        <small class="text-muted text-uppercase fw-bold">Your Score</small><br>
                        <span class="fs-1 fw-bold text-success font-monospace">${score} / 10</span>
                        <div class="text-muted small mt-1">${pct}% Mastery Level</div>
                    </div>
                    <p class="text-muted small mt-2">Ready for a real, timed examination session?</p>
                `,
                icon: score >= 6 ? 'success' : 'info',
                showCancelButton: true,
                confirmButtonColor: '#10b981',
                cancelButtonColor: '#64748b',
                confirmButtonText: 'Go to Exam Center',
                cancelButtonText: 'Review Answers'
            }).then((res) => {
                if (res.isConfirmed) {
                    window.location.href = 'subjects.php';
                }
            });
        }
    </script>
</body>
</html>
