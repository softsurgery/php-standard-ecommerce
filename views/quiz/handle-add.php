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

    $quizCtrl = new QuizController();
    $questionCtrl = new QuestionController();
    $quizQuestionCtrl = new QuizQuestionController();

    global $pdo;
    $pdo->beginTransaction();

    /* =========================
       1) CREATE QUIZ
    ========================== */
    $quiz = new Quiz(null, $name, $description);
    $quiz = $quizCtrl->save($quiz);

    $ordering = 0;

    /* =========================
       2) CREATE QUESTIONS
    ========================== */
    foreach ($questionsData as $q) {

        $label = trim($q['label'] ?? '');
        $type  = trim($q['type'] ?? '');
        $rate  = (int)($q['rate'] ?? 0);

        if ($label === '' || $type === '' || $rate <= 0) {
            continue;
        }

        $details = [];

        /* =========================
           TEXT VALIDATION
        ========================== */
        if ($type === 'TEXT') {
            $details['correct'] = trim($q['correct'] ?? '');
        }

        /* =========================
           SWITCH VALIDATION
        ========================== */
        if ($type === 'SWITCH') {
            // 'on' or 'off'
            $details['correct'] = $q['switch']['on'] ?? 'off';
        }

        /* =========================
           CHECKBOX / RADIO
        ========================== */
        if (
            ($type === 'CHECKBOX' || $type === 'RADIO') &&
            isset($q['choices']) &&
            is_array($q['choices'])
        ) {
            $choices = [];

            foreach ($q['choices'] as $choice) {
                $choices[] = [
                    'id'      => $choice['id'] ?? null,
                    'label'   => $choice['label'] ?? null,
                    'correct' => isset($choice['correct']) ? true : false
                ];
            }

            $details['choices'] = $choices;
        }

        /* =========================
           SLIDER VALIDATION
        ========================== */
        if ($type === 'SLIDER' && isset($q['slider'])) {

            $min = (int)($q['slider']['min'] ?? 0);
            $max = (int)($q['slider']['max'] ?? 100);

            $validMin = (int)($q['slider']['validMin'] ?? $min);
            $validMax = (int)($q['slider']['validMax'] ?? $max);

            if ($validMin < $min || $validMax > $max || $validMin > $validMax) {
                throw new Exception("Invalid slider validation range for question: {$label}");
            }

            $details['min'] = $min;
            $details['max'] = $max;
            $details['validMin'] = $validMin;
            $details['validMax'] = $validMax;
        }

        /* =========================
           SAVE QUESTION
        ========================== */
        $question = new Question(
            null,
            $label,
            $type,
            $rate,
            json_encode($details)
        );

        $savedQuestion = $questionCtrl->save($question);

        /* =========================
           MAP TO QUIZ
        ========================== */
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

    if (isset($pdo) && $pdo->inTransaction()) {
        $pdo->rollBack();
    }

    $msg = urlencode($e->getMessage());
    header("Location: ../backoffice/quiz/add-quiz.php?error=true&message=$msg");
    exit;
}
