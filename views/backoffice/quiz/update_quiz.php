<!DOCTYPE html>
<html lang="en">

<?php

require_once __DIR__ . '/../../shared/getHeader.php';
require_once __DIR__ . '/../../shared/ui/input.php';
require_once __DIR__ . '/../../shared/ui/textarea.php';
require_once __DIR__ . '/../../shared/ui/select.php';
require_once __DIR__ . '/../../shared/ui/label.php';
require_once __DIR__ . '/../../../controllers/QuizController.php';
require_once __DIR__ . '/../../../controllers/QuestionController.php';
require_once __DIR__ . '/../../../controllers/QuizQuestionController.php';

echo getPageHead('Page', '../../..');

$quizController = new QuizController();
$quizQuestionController = new QuizQuestionController();
$questionController = new QuestionController();
$quiz = $quizController->getById($_GET['id']);
$quizQuestions = $quizQuestionController->getByQuizId($quiz->getId());
$questionIds = array_map(fn($q) => $q->getQuestionId(), $quizQuestions);
$questions = $questionController->getByIds($questionIds);

?>

<body>

    <div class="flex flex-1 overflow-hidden h-screens">
        <!-- Sidebar -->
        <?php
        require_once __DIR__ . '/../getBackofficeSidebar.php';
        echo getBackofficeSidebar('../../..', "quiz");
        ?>
        <!-- Header + Main -->
        <div class="flex flex-col flex-1 overflow-hidden h-screen">
            <?php
            require_once __DIR__ . '/../getBackofficeHeader.php';
            echo getBackofficeHeader();
            ?>
            <main class="flex flex-col flex-1 p-5 bg-gray-300 overflow-auto">
                <form action="../../quiz/handle-update.php" method="post" id="quiz-form" class="container mx-auto">
                    <div>
                        <input type="hidden" name="quiz_id" value="<?= $quiz->getId() ?>">
                        <?php
                        echo '<div class="mb-4 p-4 border rounded-lg bg-gray-300 question-row" draggable="true">';

                        renderLabel('name', 'Name');
                        renderInput('text', 'name', $quiz->getName(), 'Enter quiz name');

                        renderLabel('description', 'Description');
                        renderTextarea('description', $quiz->getDescription(), 4, 'Enter quiz description');

                        echo '</div>';

                        ?>
                    </div>

                    <!-- Questions container (existing questions will be here) -->
                    <div id="questions-container" class="mt-4 p-4 border rounded-lg bg-gray-100">

                        <?php foreach ($questions as $i => $q): ?>
                            <div class="mb-4 p-4 border rounded-lg bg-gray-300 question-row" draggable="true">

                                <?php
                                // Hidden ID so update knows which question to modify
                                renderInput('hidden', "questions[$i][id]", $q->getId());

                                echo '<div class="flex flex-row gap-2">';

                                echo '<div class="w-1/2">';
                                renderLabel("questions[$i][label]", "Question Label");
                                renderInput('text', "questions[$i][label]", $q->getLabel(), 'Enter question label');
                                echo '</div>';

                                echo '<div class="w-1/2">';
                                renderLabel("questions[$i][type]", "Question Type");
                                renderSelect(
                                    "questions[$i][type]",
                                    [
                                        (object)['id' => 'TEXT', 'label' => 'Text'],
                                        (object)['id' => 'SWITCH', 'label' => 'Switch'],
                                        (object)['id' => 'CHECKBOX', 'label' => 'Checkbox'],
                                        (object)['id' => 'RADIO', 'label' => 'Radio'],
                                        (object)['id' => 'SLIDER', 'label' => 'Slider'],
                                    ],
                                    $q->getType()
                                );
                                echo '</div>';

                                echo '</div>';
                                echo '<button type="button" class="delete-question px-3 py-1 bg-red-600 text-white rounded hover:bg-red-500 mt-2">';
                                echo 'Delete';
                                echo '</button>';
                                ?>
                            </div>
                        <?php endforeach; ?>

                    </div>

                    <!-- Hidden template: note __INDEX__ placeholders -->
                    <div id="question-template" class="hidden">
                        <div class="mb-4 p-4 border rounded-lg bg-gray-300 question-row" draggable="true">
                            <?php
                            echo '<div class="flex flex-row gap-2">';

                            // Use __INDEX__ placeholder — JS will replace it before inserting
                            echo '<div class="w-1/2">';
                            renderLabel('questions[__INDEX__][label]', 'Question Label');
                            renderInput('text', 'questions[__INDEX__][label]', '', 'Enter question label');
                            echo '</div>';

                            echo '<div class="w-1/2">';
                            renderLabel('questions[__INDEX__][type]', 'Question Type');
                            renderSelect(
                                'questions[__INDEX__][type]',
                                [
                                    (object)['id' => 'TEXT', 'label' => 'Text'],
                                    (object)['id' => 'SWITCH', 'label' => 'Switch'],
                                    (object)['id' => 'CHECKBOX', 'label' => 'Checkbox'],
                                    (object)['id' => 'RADIO', 'label' => 'Radio'],
                                    (object)['id' => 'SLIDER', 'label' => 'Slider'],
                                ],
                            );
                            echo '</div>';

                            echo '</div>';
                            ?>
                            <button type="button" class="delete-question px-3 py-1 bg-red-600 text-white rounded hover:bg-red-500 mt-2">
                                Delete
                            </button>
                        </div>
                    </div>


                    <div class="flex gap-3 mt-4">
                        <button type="button" id="add-question" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-500">
                            Add Question
                        </button>

                        <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-500">
                            Update Quiz
                        </button>
                    </div>
                </form>

            </main>

            <?php
            require_once  __DIR__ . '/../../shared/ui/toast.php';
            ?>

            <!-- Toast & URL param script (unchanged) -->

        </div>
    </div>

    <?php
    require_once __DIR__ . '/../../shared/getScripts.php';
    echo getScripts('../../..');
    ?>

    <script defer>
        document.addEventListener("DOMContentLoaded", function() {
            setupQuizDND();
        });
    </script>

</body>

</html>