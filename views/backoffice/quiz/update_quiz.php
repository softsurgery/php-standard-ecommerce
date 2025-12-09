<!DOCTYPE html>
<html lang="en">

<?php
require_once __DIR__ . '/../../shared/getHeader.php';
require_once __DIR__ . '/../../shared/ui/input.php';
require_once __DIR__ . '/../../shared/ui/checkbox.php';
require_once __DIR__ . '/../../shared/ui/textarea.php';
require_once __DIR__ . '/../../shared/ui/select.php';
require_once __DIR__ . '/../../shared/ui/label.php';
require_once __DIR__ . '/../../../controllers/QuizController.php';
require_once __DIR__ . '/../../../controllers/QuestionController.php';
require_once __DIR__ . '/../../../controllers/QuizQuestionController.php';

echo getPageHead('Update Quiz', '../../..');

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
                    <input type="hidden" name="quiz_id" value="<?= $quiz->getId() ?>">

                    <!-- Quiz Name & Description -->
                    <div class="mb-4 p-4 border rounded-lg bg-gray-300">
                        <?php
                        renderLabel('name', 'Name');
                        renderInput('text', 'name', $quiz->getName(), 'Enter quiz name');

                        renderLabel('description', 'Description');
                        renderTextarea('description', $quiz->getDescription(), 4, 'Enter quiz description');
                        ?>
                    </div>

                    <!-- Questions container -->
                    <div id="questions-container" class="mt-4 p-4 border rounded-lg bg-gray-100">
                        <?php foreach ($questions as $i => $q): ?>
                            <div class="mb-4 p-4 border rounded-lg bg-gray-300 question-row" draggable="true">

                                <?php renderInput('hidden', "questions[$i][id]", $q->getId()); ?>

                                <div class="flex flex-row gap-2">
                                    <div class="w-1/2">
                                        <?php
                                        renderLabel("questions[$i][label]", "Question Label");
                                        renderInput('text', "questions[$i][label]", $q->getLabel(), 'Enter question label');
                                        ?>
                                    </div>

                                    <div class="w-1/2">
                                        <?php
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
                                        ?>
                                    </div>
                                </div>

                                <!-- Extra fields -->
                                <div class="extra-fields mt-3 <?= in_array($q->getType(), ['CHECKBOX', 'RADIO', 'SLIDER']) ? '' : 'hidden' ?>">
                                    <!-- Choice List -->
                                    <div class="choice-list mt-3 p-3 bg-gray-200 border rounded <?= in_array($q->getType(), ['CHECKBOX', 'RADIO']) ? '' : 'hidden' ?>">
                                        <h4 class="font-semibold mb-2">Choices</h4>
                                        <div class="choices-container">
                                            <?php foreach ($q->getChoices() as $ci => $choice): ?>
                                                <div class="choice flex gap-2 mb-2">
                                                    <?php
                                                    renderInput(
                                                        "text",
                                                        "questions[$i][choices][$ci][label]",
                                                        $choice['label'] ?? '',
                                                        "Label",
                                                        ['class' => 'border px-2 py-1 flex-1']
                                                    );
                                                    renderInput(
                                                        "text",
                                                        "questions[$i][choices][$ci][id]",
                                                        $choice['id'] ?? '',
                                                        "ID",
                                                        ['class' => 'border px-2 py-1 flex-1']
                                                    );
                                                    renderCheckbox(
                                                        "questions[$i][choices][$ci][correct]",
                                                        $choice['correct'] ?? '',
                                                        'Correct',
                                                        ['class' => 'border px-2 py-1']
                                                    );
                                                    ?>
                                                    <button type="button" class="remove-choice bg-red-600 text-white px-2 rounded">X</button>
                                                </div>
                                            <?php endforeach; ?>
                                        </div>
                                        <button type="button" class="add-choice bg-blue-600 text-white px-3 py-1 rounded mt-2 hover:bg-blue-500">Add Choice</button>
                                    </div>

                                    <!-- Slider Fields -->
                                    <div class="slider-fields mt-3 p-3 bg-gray-200 border rounded <?= $q->getType() === 'SLIDER' ? '' : 'hidden' ?>">
                                        <?php
                                        renderLabel("questions[$i][slider][min]", "Min Value");
                                        renderInput("number", "questions[$i][slider][min]", $q->getSlider()['min'] ?? 0);
                                        renderLabel("questions[$i][slider][max]", "Max Value");
                                        renderInput("number", "questions[$i][slider][max]", $q->getSlider()['max'] ?? 100);
                                        ?>
                                    </div>
                                </div>

                                <button type="button" class="delete-question px-3 py-1 bg-red-600 text-white rounded hover:bg-red-500 mt-2">Delete</button>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <!-- Hidden Question Template -->
                    <div id="question-template" class="hidden">
                        <div class="mb-4 p-4 border rounded-lg bg-gray-300 question-row" draggable="true">
                            <div class="flex flex-row gap-2">
                                <div class="w-1/2">
                                    <?php
                                    renderLabel('questions[__INDEX__][label]', 'Question Label');
                                    renderInput('text', 'questions[__INDEX__][label]', '', 'Enter question label');
                                    ?>
                                </div>
                                <div class="w-1/2">
                                    <?php
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
                                    ?>
                                </div>
                            </div>

                            <!-- Extra fields placeholder -->
                            <div class="extra-fields mt-3 hidden">
                                <div class="choice-list mt-3 p-3 bg-gray-200 border rounded hidden">
                                    <h4 class="font-semibold mb-2">Choices</h4>
                                    <div class="choices-container"></div>
                                    <button type="button" class="add-choice bg-blue-600 text-white px-3 py-1 rounded mt-2 hover:bg-blue-500">Add Choice</button>
                                </div>

                                <div class="slider-fields mt-3 p-3 bg-gray-200 border rounded hidden">
                                    <?php
                                    renderLabel('questions[__INDEX__][slider][min]', 'Min Value');
                                    renderInput('number', 'questions[__INDEX__][slider][min]', 0);
                                    renderLabel('questions[__INDEX__][slider][max]', 'Max Value');
                                    renderInput('number', 'questions[__INDEX__][slider][max]', 100);
                                    ?>
                                </div>
                            </div>

                            <button type="button" class="delete-question px-3 py-1 bg-red-600 text-white rounded hover:bg-red-500 mt-2">Delete</button>
                        </div>
                    </div>

                    <!-- Hidden choice template -->
                    <div id="choice-template" class="hidden">
                        <div class="choice flex gap-2 mb-2">
                            <?php
                            renderInput('text', 'questions[__QINDEX__][choices][__CINDEX__][label]', '', 'Label', ['class' => 'border px-2 py-1 flex-1']);
                            renderInput('text', 'questions[__QINDEX__][choices][__CINDEX__][id]', '', 'ID', ['class' => 'border px-2 py-1 flex-1']);
                            renderCheckbox('questions[__QINDEX__][choices][__CINDEX__][correct]', '', 'Correct', ['class' => 'border px-2 py-1']);
                            ?>
                            <button type="button" class="remove-choice bg-red-600 text-white px-2 rounded">X</button>
                        </div>
                    </div>

                    <div class="flex gap-3 mt-4">
                        <button type="button" id="add-question" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-500">Add Question</button>
                        <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-500">Update Quiz</button>
                    </div>
                </form>
            </main>
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