<?php

require_once __DIR__ . '/../../models/Submission.php';
require_once __DIR__ . '/../../controllers/SubmissionController.php';
require_once __DIR__ . '/../../models/config.php'; // For $pdo, if needed

session_start();

// Assuming user ID is stored in session
$userId = $_SESSION['user_id'] ?? null;

if (!$userId) {
    die("You must be logged in to submit the survey.");
}

// Get POST data
$quizId = $_POST['quiz_id'] ?? null;
$answers = $_POST['answers'] ?? [];

if (!$quizId || empty($answers)) {
    die("Quiz ID and answers are required.");
}

// Convert answers to JSON
$answersJson = json_encode($answers, JSON_UNESCAPED_UNICODE);

if ($answersJson === false) {
    die("Failed to encode answers.");
}

// Create Submission object
$submission = new Submission(
    $quizId,
    $userId,
    $answersJson,
    date('Y-m-d H:i:s') // current timestamp
);

// Save submission
$submissionCtrl = new SubmissionController();

try {
    // Optionally: check if a submission already exists and update instead of insert
    $existing = $submissionCtrl->getById($quizId, $userId);
    if ($existing) {
        $submissionCtrl->update($quizId, $userId, $submission);
    } else {
        $submissionCtrl->save($submission);
    }

    // Redirect or show success message
    header("Location: /quiz-success.php?quiz_id=" . $quizId);
    exit();
} catch (Exception $e) {
    die("Error saving submission: " . $e->getMessage());
}
