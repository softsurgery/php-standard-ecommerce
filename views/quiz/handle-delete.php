<?php

require_once __DIR__ . '/../../controllers/QuizController.php';
require_once __DIR__ . '/../../controllers/QuizQuestionController.php';

try {

    if (empty($_GET['id'])) {
        throw new Exception("Missing quiz ID.");
    }

    $quizId = intval($_GET['id']);

    $quizCtrl = new QuizController();
    $quizQuestionCtrl = new QuizQuestionController();

    global $pdo;
    $pdo->beginTransaction();

    // 1) Delete all mappings quiz ↔ question
    $quizQuestionCtrl->deleteByQuizId($quizId);

    // 2) Delete the quiz
    $quizCtrl->delete($quizId);

    $pdo->commit();

    $msg = urlencode("Quiz #$quizId deleted successfully.");
    header("Location: ../backoffice/quiz/quiz-portal.php?success=true&message=$msg");
    exit;
} catch (Exception $e) {

    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }

    $msg = urlencode("Delete failed: " . $e->getMessage());
    header("Location: ../backoffice/quiz/quiz-portal.php?error=true&message=$msg");
    exit;
}
