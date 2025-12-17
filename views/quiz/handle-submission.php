<?php

require_once __DIR__ . '/../../models/config.php';
require_once __DIR__ . '/../../models/Submission.php';

require_once __DIR__ . '/../../controllers/SubmissionController.php';
require_once __DIR__ . '/../../controllers/QuizQuestionController.php';
require_once __DIR__ . '/../../controllers/QuestionController.php';

session_start();

$userId = $_SESSION['user_id'] ?? null;
if (!$userId) die("You must be logged in to submit the quiz.");

$quizId  = $_POST['quiz_id'] ?? null;
$answers = $_POST['answers'] ?? [];
if (!$quizId || empty($answers)) die("Quiz ID and answers are required.");

$quizQuestionCtrl = new QuizQuestionController();
$questions = $quizQuestionCtrl->getQuestionsForQuiz($quizId);
if (empty($questions)) die("Quiz has no questions.");

$score = 0;
$report = []; // store report per question

foreach ($questions as $question) {

    $questionId = $question->getId();
    $rate       = $question->getRate();
    $type       = $question->getType();
    $details    = json_decode($question->getDetails(), true);

    $questionReport = [
        'question_id' => $questionId,
        'type' => $type,
        'user_answer' => $answers[$questionId] ?? null,
        'correct' => null,
        'is_correct' => false,
        'points_awarded' => 0
    ];

    if (!isset($answers[$questionId])) {
        $report[] = $questionReport;
        continue;
    }

    $userAnswer = $answers[$questionId];
    $isCorrect = false;

    /* ---------- TEXT ---------- */
    if ($type === 'TEXT') {
        $correct = trim($details['correct'] ?? '');
        $isCorrect = strcasecmp(trim($userAnswer), $correct) === 0;
        $questionReport['correct'] = $correct;
    }

    /* ---------- SWITCH ---------- */ elseif ($type === 'SWITCH') {
        $correct = $details['correct'];
        $isCorrect = $userAnswer ? $correct === 'on' : $correct === 'off';
        $questionReport['correct'] = $correct;
    }

    /* ---------- RADIO ---------- */ elseif ($type === 'RADIO') {
        $correctId = null;
        foreach ($details['choices'] ?? [] as $choice) {
            if (!empty($choice['correct'])) {
                $correctId = $choice['id'];
                if ($userAnswer == $choice['id']) $isCorrect = true;
            }
        }
        $questionReport['correct'] = $correctId;
    }

    /* ---------- CHECKBOX ---------- */ elseif ($type === 'CHECKBOX' && is_array($userAnswer)) {
        $correctIds = [];
        foreach ($details['choices'] ?? [] as $choice) {
            if (!empty($choice['correct'])) $correctIds[] = $choice['id'];
        }
        sort($correctIds);
        sort($userAnswer);
        $isCorrect = ($correctIds === $userAnswer);
        $questionReport['correct'] = $correctIds;
    }

    /* ---------- SLIDER ---------- */ elseif ($type === 'SLIDER') {
        $value = (int)$userAnswer;
        $min   = (int)($details['validMin'] ?? 0);
        $max   = (int)($details['validMax'] ?? 0);
        $isCorrect = ($value >= $min && $value <= $max);
        $questionReport['correct'] = "{$min}-{$max}";
    }

    if ($isCorrect) {
        $score += $rate;
        $questionReport['points_awarded'] = $rate;
    }

    $questionReport['is_correct'] = $isCorrect;
    $report[] = $questionReport;
}

/* =========================
   Save Submission
========================= */
$answersJson = json_encode($answers, JSON_UNESCAPED_UNICODE);
$submission = new Submission($quizId, $userId, $score, $answersJson, date('Y-m-d H:i:s'));
$submissionCtrl = new SubmissionController();

try {
    $existing = $submissionCtrl->getById($quizId, $userId);
    if ($existing) {
        $submissionCtrl->update($quizId, $userId, $submission);
    } else {
        $submissionCtrl->save($submission);
    }
} catch (Exception $e) {
    die("Error saving submission: " . $e->getMessage());
}

/* =========================
   Generate Report
========================= */
echo "<h2>Quiz Report</h2>";
echo "<strong>Total Score:</strong> {$score}<br><hr>";

foreach ($report as $q) {
    echo "<strong>Question ID:</strong> {$q['question_id']}<br>";
    echo "<strong>Type:</strong> {$q['type']}<br>";
    echo "<strong>User Answer:</strong> ";
    echo "<pre>" . htmlspecialchars(print_r($q['user_answer'], true)) . "</pre>";
    echo "<strong>Correct:</strong> ";
    echo "<pre>" . htmlspecialchars(print_r($q['correct'], true)) . "</pre>";
    echo "<strong>Correct?</strong> " . ($q['is_correct'] ? "✅ Yes" : "❌ No") . "<br>";
    echo "<strong>Points Awarded:</strong> {$q['points_awarded']}<hr>";
}

exit;
