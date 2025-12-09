<?php

require_once __DIR__ . '/../../controllers/QuizController.php';
require_once __DIR__ . '/../../controllers/QuestionController.php';
require_once __DIR__ . '/../../controllers/QuizQuestionController.php';

$quizId = $_GET['id'] ?? null;

if (!$quizId) {
    die("Quiz ID is required.");
}

$quizCtrl = new QuizController();
$questionCtrl = new QuestionController();
$quizQuestionCtrl = new QuizQuestionController();

// Load quiz
$quiz = $quizCtrl->getById($quizId);

if (!$quiz) {
    die("Quiz not found.");
}

// Load ordered questions inside quiz
$questions = $quizQuestionCtrl->getQuestionsForQuiz($quizId);

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title><?= htmlspecialchars($quiz->getName()) ?></title>
    <link rel="stylesheet" href="/path/to/tailwind.css">
</head>

<body class="bg-gray-100 p-8">

    <div class="max-w-2xl mx-auto bg-white p-6 rounded shadow">

        <h1 class="text-2xl font-bold mb-2">
            <?= htmlspecialchars($quiz->getName()) ?>
        </h1>

        <p class="text-gray-600 mb-6">
            <?= nl2br(htmlspecialchars($quiz->getDescription())) ?>
        </p>

        <form method="post" action="submit-survey.php">

            <input type="hidden" name="quiz_id" value="<?= $quizId ?>">

            <?php foreach ($questions as $i => $q): ?>

                <?php
                // echo '<pre>';
                // print_r($q);
                // echo '</pre>';
                $details = $q->getDetails();
                ?>

                <div class="mb-6 p-4 bg-gray-50 border rounded">
                    <h3 class="font-semibold mb-2">
                        <?= ($i + 1) . ". " . htmlspecialchars($q->getLabel()) ?>
                    </h3>

                    <!-- TEXT QUESTION -->
                    <?php if ($q->getType() === 'TEXT'): ?>
                        <input
                            type="text"
                            name="answers[<?= $q->getId() ?>]"
                            class="w-full p-2 border rounded"
                            placeholder="Your answer...">

                        <!-- SWITCH -->
                    <?php elseif ($q->getType() === 'SWITCH'): ?>
                        <label class="flex items-center gap-2">
                            <input type="checkbox" name="answers[<?= $q->getId() ?>]" value="1">
                            <span>Yes</span>
                        </label>

                        <!-- CHECKBOX -->
                    <?php elseif ($q->getType() === 'CHECKBOX'): ?>
                        <?php foreach ($q->getChoices() as $c): ?>
                            <label class="flex items-center gap-2 mb-1">
                                <input type="checkbox"
                                    name="answers[<?= $q->getId() ?>][]"
                                    value="<?= htmlspecialchars($c['id']) ?>">
                                <span><?= htmlspecialchars($c['label']) ?></span>
                            </label>
                        <?php endforeach; ?>

                        <!-- RADIO -->
                    <?php elseif ($q->getType() === 'RADIO'): ?>
                        <?php foreach ($q->getChoices() as $c): ?>
                            <label class="flex items-center gap-2 mb-1">
                                <input type="radio"
                                    name="answers[<?= $q->getId() ?>]"
                                    value="<?= htmlspecialchars($c['id']) ?>">
                                <span><?= htmlspecialchars($c['label']) ?></span>
                            </label>
                        <?php endforeach; ?>

                        <!-- SLIDER -->
                    <?php elseif ($q->getType() === 'SLIDER'): ?>
                        <input
                            type="range"
                            min="<?= htmlspecialchars($details['min'] ?? 0) ?>"
                            max="<?= htmlspecialchars($details['max'] ?? 100) ?>"
                            name="answers[<?= $q->getId() ?>]"
                            class="w-full">

                    <?php endif; ?>

                </div>

            <?php endforeach; ?>

            <button
                type="submit"
                class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-500">
                Submit Survey
            </button>

        </form>

    </div>

</body>

</html>