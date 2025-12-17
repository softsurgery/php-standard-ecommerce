<!DOCTYPE html>
<html lang="en">

<?php
require_once __DIR__ . '/../../shared/getHeader.php';
require_once __DIR__ . '/../../shared/ui/input.php';
require_once __DIR__ . '/../../shared/ui/checkbox.php';
require_once __DIR__ . '/../../shared/ui/radio.php';
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
    <div class="flex flex-1 overflow-hidden h-screen">

        <?php
        require_once __DIR__ . '/../getBackofficeSidebar.php';
        echo getBackofficeSidebar('../../..', "quiz");
        ?>

        <div class="flex flex-col flex-1 overflow-hidden">

            <?php
            require_once __DIR__ . '/../getBackofficeHeader.php';
            echo getBackofficeHeader();
            ?>

            <main class="flex flex-col flex-1 p-5 bg-gray-300 overflow-auto">

                <form action="../../quiz/handle-update.php" method="post" class="container mx-auto">
                    <input type="hidden" name="quiz_id" value="<?= $quiz->getId() ?>">
                    <div>
                        <?php
                        echo '<div class="mb-4 p-4 border rounded-lg bg-gray-300 question-row" draggable="true">';

                        renderLabel('name', 'Quiz Name');
                        renderInput('text', 'name', $quiz->getName());

                        renderLabel('description', 'Description');
                        renderTextarea('description', $quiz->getDescription(), 4);
                        echo '</div>';

                        ?>
                    </div>

                    <!-- QUESTIONS -->
                    <div id="questions-container" class="mt-4 p-4 bg-gray-100 border rounded">

                        <?php foreach ($questions as $i => $q):

                            $details = json_decode($q->getDetails() ?? '{}', true);
                        ?>

                            <div class="mb-4 p-4 border rounded-lg bg-gray-300 question-row" draggable="true">

                                <?php renderInput('hidden', "questions[$i][id]", $q->getId()); ?>

                                <div class="flex gap-2">
                                    <div class="w-3/6">
                                        <?php renderLabel("questions[$i][label]", "Question"); ?>
                                        <?php renderInput("text", "questions[$i][label]", $q->getLabel()); ?>
                                    </div>

                                    <div class="w-2/6">
                                        <?php renderLabel("questions[$i][type]", "Type"); ?>
                                        <?php renderSelect(
                                            "questions[$i][type]",
                                            [
                                                (object)['id' => 'TEXT', 'label' => 'Text'],
                                                (object)['id' => 'SWITCH', 'label' => 'Switch'],
                                                (object)['id' => 'CHECKBOX', 'label' => 'Checkbox'],
                                                (object)['id' => 'RADIO', 'label' => 'Radio'],
                                                (object)['id' => 'SLIDER', 'label' => 'Slider'],
                                            ],
                                            $q->getType()
                                        ); ?>
                                    </div>

                                    <div class="w-1/6">
                                        <?php renderLabel("questions[$i][rate]", "Rate"); ?>
                                        <?php renderInput("number", "questions[$i][rate]", $q->getRate()); ?>
                                    </div>
                                </div>

                                <!-- EXTRA -->
                                <div class="extra-fields mt-3">

                                    <!-- TEXT -->
                                    <div class="text-fields p-3 bg-gray-200 rounded <?= $q->getType() === 'TEXT' ? '' : 'hidden' ?>">
                                        <?php
                                        renderLabel("questions[$i][correct]", "Correct Answer");
                                        renderInput(
                                            "text",
                                            "questions[$i][correct]",
                                            $details['correct'] ?? ''
                                        );
                                        ?>
                                    </div>

                                    <!-- SWITCH -->
                                    <div class="switch-fields p-3 bg-gray-200 rounded <?= $q->getType() === 'SWITCH' ? '' : 'hidden' ?>">
                                        <?php
                                        renderLabel("", "Correct Value");
                                        renderRadio("questions[$i][switch][on]", "on", ($details['correct'] ?? '') === 'on', "Yes");
                                        renderRadio("questions[$i][switch][on]", "off", ($details['correct'] ?? '') === 'off', "No");
                                        ?>
                                    </div>

                                    <!-- CHECKBOX / RADIO -->
                                    <div class="choice-list p-3 bg-gray-200 rounded <?= in_array($q->getType(), ['CHECKBOX', 'RADIO']) ? '' : 'hidden' ?>">
                                        <h4 class="font-semibold mb-2">Choices</h4>

                                        <div class="choices-container">
                                            <?php foreach (($details['choices'] ?? []) as $ci => $choice): ?>
                                                <div class="choice flex gap-2 mb-2">
                                                    <?php
                                                    renderInput("text", "questions[$i][choices][$ci][label]", $choice['label'] ?? '');
                                                    renderInput("text", "questions[$i][choices][$ci][id]", $choice['id'] ?? '');
                                                    renderCheckbox(
                                                        "questions[$i][choices][$ci][correct]",
                                                        !empty($choice['correct']),
                                                        'Correct'
                                                    );
                                                    ?>
                                                    <button type="button" class="remove-choice bg-red-600 text-white px-2">X</button>
                                                </div>
                                            <?php endforeach; ?>
                                        </div>

                                        <button type="button" class="add-choice bg-blue-600 text-white px-3 py-1 mt-2 rounded">
                                            Add Choice
                                        </button>
                                    </div>

                                    <!-- SLIDER -->
                                    <div class="slider-fields p-3 bg-gray-200 rounded <?= $q->getType() === 'SLIDER' ? '' : 'hidden' ?>">

                                        <h4 class="font-semibold">Allowed Range</h4>
                                        <div class="flex gap-2">
                                            <?php
                                            renderInput("number", "questions[$i][slider][min]", $details['min'] ?? 0);
                                            renderInput("number", "questions[$i][slider][max]", $details['max'] ?? 100);
                                            ?>
                                        </div>

                                        <h4 class="font-semibold mt-3">Correct Range</h4>
                                        <div class="flex gap-2">
                                            <?php
                                            renderInput("number", "questions[$i][slider][validMin]", $details['validMin'] ?? 0);
                                            renderInput("number", "questions[$i][slider][validMax]", $details['validMax'] ?? 100);
                                            ?>
                                        </div>
                                    </div>

                                </div>

                                <button type="button" class="delete-question mt-3 bg-red-600 text-white px-3 py-1 rounded">
                                    Delete
                                </button>

                            </div>

                        <?php endforeach; ?>
                    </div>

                    <!-- Hidden template: note __INDEX__ placeholders -->
                    <div id="question-template" class="hidden">
                        <div class="mb-4 p-4 border rounded-lg bg-gray-300 question-row" draggable="true">

                            <div class="flex flex-row gap-2">

                                <div class="w-3/6">
                                    <?php
                                    renderLabel('questions[__INDEX__][label]', 'Question Label');
                                    renderInput('text', 'questions[__INDEX__][label]', '', 'Enter question label');
                                    ?>
                                </div>

                                <div class="w-2/6">
                                    <?php
                                    renderLabel('questions[__INDEX__][type]', 'Question Type');
                                    renderSelect(
                                        'questions[__INDEX__][type]',
                                        [
                                            (object)['id' => 'TEXT', 'label' => 'Text'],
                                            (object)['id' => 'SWITCH', 'label' => 'Switch'],
                                            (object)['id' => 'CHECKBOX', 'label' => 'Checkbox'],
                                            (object)['id' => 'RADIO',   'label' => 'Radio'],
                                            (object)['id' => 'SLIDER',  'label' => 'Slider'],
                                        ],
                                        null
                                    );
                                    ?>
                                </div>

                                <div class="w-1/6">
                                    <?php
                                    renderLabel('questions[__INDEX__][rate]', 'Question Rate');
                                    renderInput('number', 'questions[__INDEX__][rate]', 1, 'Rate', ['min' => '1', 'max' => '100']);
                                    ?>
                                </div>

                            </div>

                            <!-- EXTRA FIELDS SECTION -->
                            <div class="extra-fields hidden mt-3">

                                <!-- CORRECT ANSWER (TEXT) -->
                                <div class="text-fields mt-3 p-3 bg-gray-200 border rounded">
                                    <?php
                                    renderLabel("questions[__INDEX__][correct]", "Correct answer");
                                    renderInput(
                                        'text',
                                        "questions[__INDEX__][correct]",
                                        '',
                                        'Correct answer',
                                        ['class' => 'border px-2 py-1 flex-1']
                                    );
                                    ?>
                                </div>

                                <!-- SWITCH FIELDS -->
                                <div class="switch-fields mt-3 p-3 bg-gray-200 border rounded">
                                    <?php
                                    renderLabel("questions[__INDEX__][switch][on]", "Correct Answer");
                                    renderRadio("questions[__INDEX__][switch][on]", 'on', true, "Yes");
                                    renderRadio("questions[__INDEX__][switch][on]", 'off', false, "No");
                                    ?>
                                </div>

                                <!-- CHOICE LIST (CHECKBOX / RADIO) -->
                                <div class="choice-list hidden mt-2 p-3 bg-gray-200 border rounded">

                                    <h4 class="font-semibold mb-2">Choices</h4>
                                    <div class="choices-container">

                                    </div>

                                    <button type="button"
                                        class="add-choice px-3 py-1 bg-blue-600 text-white rounded mt-2 hover:bg-blue-500">
                                        Add Choice
                                    </button>

                                </div>

                                <!-- SLIDER FIELDS -->
                                <div class="slider-fields mt-3 p-3 bg-gray-200 border rounded">

                                    <div class="flex flex-col gap-2">
                                        <div class="flex flex-row flex-1 gap-2">
                                            <div class="flex-1">
                                                <?php
                                                renderLabel("questions[__INDEX__][slider][min]", "Min Value");
                                                renderInput("number", "questions[__INDEX__][slider][min]", 0);
                                                ?>
                                            </div>
                                            <div class="flex-1">
                                                <?php
                                                renderLabel("questions[__INDEX__][slider][max]", "Max Value");
                                                renderInput("number", "questions[__INDEX__][slider][max]", 100);
                                                ?>
                                            </div>
                                        </div>
                                        <?php
                                        ?>
                                        <div class="flex flex-col gap-2">
                                            <?php
                                            renderLabel("", "Correct range");
                                            ?>
                                            <div class="flex flex-row flex-1 gap-2">
                                                <?php
                                                renderInput("number", "questions[__INDEX__][slider][validMin]",  0);
                                                renderInput("number", "questions[__INDEX__][slider][validMax]", 100);
                                                ?>
                                            </div>
                                        </div>

                                    </div>
                                </div>

                            </div>

                            <button type="button"
                                class="delete-question px-3 py-1 bg-red-600 text-white rounded hover:bg-red-500 mt-3">
                                Delete
                            </button>

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
                        <button type="button" id="add-question" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-500">
                            Add Question
                        </button>
                        <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded">
                            Update Quiz
                        </button>
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