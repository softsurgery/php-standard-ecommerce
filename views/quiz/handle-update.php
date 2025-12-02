<?php

require_once __DIR__ . '/../../controllers/QuizController.php';
require_once __DIR__ . '/../../controllers/QuestionController.php';
require_once __DIR__ . '/../../controllers/QuizQuestionController.php';
require_once __DIR__ . '/../../models/Quiz.php';
require_once __DIR__ . '/../../models/Question.php';
require_once __DIR__ . '/../../models/QuizQuestion.php';

try {

    if (empty($_POST['quiz_id'])) {
        throw new Exception("Missing quiz ID.");
    }
    if (empty($_POST['name'])) {
        throw new Exception("Quiz name is required.");
    }

    $quizId = $_POST['quiz_id'];
    $name = trim($_POST['name']);
    $description = trim($_POST['description'] ?? '');
    $questionsData = $_POST['questions'] ?? [];

    $quizCtrl = new QuizController();
    $questionCtrl = new QuestionController();
    $quizQuestionCtrl = new QuizQuestionController();

    global $pdo;
    $pdo->beginTransaction();

    // -----------------------------------------------------------
    // 1) UPDATE QUIZ ITSELF
    // -----------------------------------------------------------

    $quiz = new Quiz($quizId, $name, $description);
    $quizCtrl->update($quizId, $quiz);
    echo "Updated quiz";
    // -----------------------------------------------------------
    // 2) FETCH EXISTING QUESTIONS FOR THIS QUIZ
    // -----------------------------------------------------------

    $existingMappings = $quizQuestionCtrl->getByQuizId($quizId);
    // Format: [questionId => ordering]
    $existingQuestionIds = [];
    foreach ($existingMappings as $m) {
        $existingQuestionIds[$m->getQuestionId()] = $m->getOrdering();
    }

    // -----------------------------------------------------------
    // 3) BUILD NEW STATE FROM POST
    // -----------------------------------------------------------

    $newIds = [];              // final IDs in order
    $ordering = 0;

    foreach ($questionsData as $q) {

        $id    = isset($q['id']) ? (int)$q['id'] : null;
        $label = trim($q['label'] ?? '');
        $type  = trim($q['type'] ?? '');

        if ($label === '' || $type === '') {
            continue; // skip empty rows
        }

        if ($id) {
            // -------------------------------
            // EXISTING QUESTION → update it
            // -------------------------------
            $question = new Question($id, $label, $type, null);
            $questionCtrl->update($id, $question);

            // Update ordering in mapping
            $quizQuestionCtrl->updateOrdering($quizId, $id, $ordering);

            $newIds[] = $id;
        } else {
            // -------------------------------
            // NEW QUESTION → insert
            // -------------------------------
            $newQuestion = new Question(null, $label, $type, null);
            $created = $questionCtrl->save($newQuestion);

            $quizQuestion = new QuizQuestion(
                $quizId,
                $created->getId(),
                $ordering
            );
            $quizQuestionCtrl->save($quizQuestion);

            $newIds[] = $created->getId();
        }

        $ordering++;
    }

    // -----------------------------------------------------------
    // 4) DELETE REMOVED QUESTIONS
    // -----------------------------------------------------------

    foreach ($existingQuestionIds as $existingId => $oldOrdering) {
        if (!in_array($existingId, $newIds)) {

            // Remove mapping first
            $quizQuestionCtrl->delete($quizId, $existingId);

            // Optional: delete the question entirely
            // Only if it’s not used in other quizzes
            $count = $quizQuestionCtrl->countUsages($existingId);
            if ($count === 0) {
                $questionCtrl->delete($existingId);
            }
        }
    }

    $pdo->commit();

    $msg = urlencode("Quiz updated successfully.");
    header("Location: ../backoffice/quiz/quiz-portal.php?success=true&message=$msg");
    exit;
} catch (Exception $e) {

    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }

    $msg = urlencode($e->getMessage());
    header("Location: ../backoffice/quiz/edit-quiz.php?id=$quizId&error=true&message=$msg");
    exit;
}
