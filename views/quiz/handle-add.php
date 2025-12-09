<?php

require_once __DIR__ . '/../../controllers/QuizController.php';
require_once __DIR__ . '/../../controllers/QuestionController.php';
require_once __DIR__ . '/../../controllers/QuizQuestionController.php';
require_once __DIR__ . '/../../models/Quiz.php';
require_once __DIR__ . '/../../models/Question.php';
require_once __DIR__ . '/../../models/QuizQuestion.php';

try {

    if (empty($_POST['name'])) {
        throw new Exception("Quiz name is required.");
    }

    $name = trim($_POST['name']);
    $description = trim($_POST['description'] ?? '');
    $questionsData = $_POST['questions'] ?? [];

    $questionData = array_slice($questionsData, -1);

    $quizCtrl = new QuizController();
    $questionCtrl = new QuestionController();
    $quizQuestionCtrl = new QuizQuestionController();

    global $pdo;
    $pdo->beginTransaction();

    // 1) Create the quiz

    $quiz = new Quiz(null, $name, $description);
    $quiz = $quizCtrl->save($quiz);

    // 2) Create question records
    $questionIds = [];

    $ordering = 0;

    foreach ($questionsData as $q) {

        $label = trim($q['label'] ?? '');
        $type  = trim($q['type'] ?? '');

        if ($label === '' || $type === '') continue;

        // Build details JSON
        $details = [];

        // Save choices (for checkbox, radio, etc.)
        if (isset($q['choices']) && is_array($q['choices']) && ($type == 'CHECKBOX' || $type == 'RADIO')) {
            $choices = [];

            foreach ($q['choices'] as $choice) {
                $choices[] = [
                    'id'    => $choice['id'] ?? null,
                    'label' => $choice['label'] ?? null,
                    'correct' => $choice['correct'] ?? null
                ];
            }

            $details['choices'] = $choices;
        }

        // Save slider info (min/max)
        if (isset($q['slider']) && $type === 'SLIDER') {
            $details['min'] = $q['slider']['min'] ?? null;
            $details['max'] = $q['slider']['max'] ?? null;
        }

        // Convert details to JSON
        $detailsJson = json_encode($details);

        // Create question
        $question = new Question(
            null,
            $label,
            $type,
            $detailsJson
        );

        $savedQuestion = $questionCtrl->save($question);

        // Map quiz-question
        $quizQuestion = new QuizQuestion(
            $quiz->getId(),
            $savedQuestion->getId(),
            $ordering
        );
        $quizQuestionCtrl->save($quizQuestion);

        $ordering++;
    }

    $pdo->commit();

    $msg = urlencode("Quiz created successfully.");
    header("Location: ../backoffice/quiz/quiz-portal.php?success=true&message=$msg");
    exit;
} catch (Exception $e) {

    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }

    $msg = urlencode($e->getMessage());
    header("Location: ../backoffice/quiz/add-quiz.php?error=true&message=$msg");
    exit;
}
