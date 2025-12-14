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

echo getPageHead('Page', '../../..');
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
                <form action="../../quiz/handle-add.php" method="post" id="quiz-form" class="container mx-auto">
                    <div>
                        <?php
                        echo '<div class="mb-4 p-4 border rounded-lg bg-gray-300 question-row" draggable="true">';

                        renderLabel('name', 'Name');
                        renderInput('text', 'name', '', 'Enter quiz name');

                        renderLabel('description', 'Description');
                        renderTextarea('description', '', 4, 'Enter quiz description');

                        echo '</div>';

                        ?>
                    </div>

                    <!-- Questions container (existing questions will be here) -->
                    <div id="questions-container" class="mt-4 p-4 bg-gray-100 border rounded">
                        <?php
                        // Example: Prepopulate with 1 question (replace with DB data)
                        $questions = [
                            [
                                'label' => '',
                                'type'  => 'TEXT',
                                'correct' => '',
                                'choices' => [],
                                'slider' => ['min' => 0, 'max' => 100]
                            ]
                        ];

                        foreach ($questions as $i => $q): ?>

                            <div class="mb-4 p-4 border rounded-lg bg-gray-300 question-row" draggable="true">

                                <div class="flex flex-row gap-2">

                                    <!-- Question Label -->
                                    <div class="w-3/6">
                                        <?php
                                        renderLabel("questions[$i][label]", "Question Label");
                                        renderInput("text", "questions[$i][label]", $q['label'] ?? '', "Enter question label");
                                        ?>
                                    </div>

                                    <!-- Question Type -->
                                    <div class="w-2/6">
                                        <?php
                                        renderLabel("questions[$i][type]", "Question Type");
                                        renderSelect(
                                            "questions[$i][type]",
                                            [
                                                (object)['id' => 'TEXT', 'label' => 'Text'],
                                                (object)['id' => 'SWITCH', 'label' => 'Switch'],
                                                (object)['id' => 'CHECKBOX', 'label' => 'Checkbox'],
                                                (object)['id' => 'RADIO',   'label' => 'Radio'],
                                                (object)['id' => 'SLIDER',  'label' => 'Slider'],
                                            ],
                                            $q["type"] ?? "TEXT"
                                        );
                                        ?>
                                    </div>

                                    <!-- Question Rate -->
                                    <div class="w-1/6">
                                        <?php
                                        renderLabel("questions[$i][rate]", "Question Rate");
                                        renderInput("number", "questions[$i][rate]", $q['rate'] ?? 1, "Rate", ['min' => '1', 'max' => '100']);
                                        ?>
                                    </div>

                                </div>

                                <!-- EXTRA FIELDS WRAPPER -->
                                <div class="extra-fields hidden mt-3">

                                    <!-- CORRECT ANSWER (TEXT) -->
                                    <div class="text-fields mt-3 p-3 bg-gray-200 border rounded <?= ($q['type'] === 'TEXT') ? '' : 'hidden' ?>">
                                        <?php
                                        renderLabel("questions[$i][correct]", "Correct answer");
                                        renderInput(
                                            'text',
                                            "questions[$i][correct]",
                                            $q['correct'] ?? '',
                                            'Correct answer',
                                            ['class' => 'border px-2 py-1 flex-1']
                                        );
                                        ?>
                                    </div>

                                    <!-- SWITCH FIELDS -->
                                    <div class="switch-fields mt-3 p-3 bg-gray-200 border rounded <?= ($q['type'] === 'SWITCH') ? '' : 'hidden' ?>">
                                        <?php
                                        renderLabel("questions[$i][switch][on]", "Correct Answer");
                                        renderRadio("questions[$i][switch][on]", 'on', true, "Yes");
                                        renderRadio("questions[$i][switch][on]", 'off', false, "No");
                                        ?>
                                    </div>

                                    <!-- CHOICE LIST (CHECKBOX / RADIO) -->
                                    <div class="choice-list mt-3 p-3 bg-gray-200 border rounded <?= ($q['type'] === 'CHECKBOX' || $q['type'] === 'RADIO') ? '' : 'hidden' ?>">

                                        <h4 class="font-semibold mb-2">Choices</h4>
                                        <div class="choices-container">
                                            <?php if (!empty($q['choices'])): ?>
                                                <?php foreach ($q['choices'] as $ci => $choice): ?>
                                                    <div class="choice flex gap-2 mb-2">
                                                        <?php
                                                        // Label input
                                                        renderInput(
                                                            'text',
                                                            "questions[{$i}][choices][{$ci}][label]",
                                                            $choice['label'] ?? '',
                                                            'Label',
                                                            ['class' => 'border px-2 py-1 flex-1']
                                                        );
                                                        // ID input
                                                        renderInput(
                                                            'text',
                                                            "questions[{$i}][choices][{$ci}][id]",
                                                            $choice['id'] ?? '',
                                                            'ID',
                                                            ['class' => 'border px-2 py-1 flex-1']
                                                        );
                                                        renderCheckbox(
                                                            "questions[{$i}][choices][{$ci}][correct]",
                                                            $choice['correct'] ?? '',
                                                            'Correct',
                                                            ['class' => 'border px-2 py-1']
                                                        );
                                                        ?>
                                                        <button type="button" class="remove-choice bg-red-600 text-white px-2 rounded">
                                                            X
                                                        </button>
                                                    </div>
                                                <?php endforeach; ?>
                                            <?php endif; ?>
                                        </div>

                                        <button
                                            type="button"
                                            class="add-choice bg-blue-600 text-white px-3 py-1 rounded mt-2 hover:bg-blue-500">
                                            Add Choice
                                        </button>

                                    </div>

                                    <!-- SLIDER FIELDS -->
                                    <div class="slider-fields mt-3 p-3 bg-gray-200 border rounded <?= ($q['type'] === 'SLIDER') ? '' : 'hidden' ?>">

                                        <div class="flex flex-col gap-2">
                                            <div class="flex flex-row flex-1 gap-2">
                                                <div class="flex-1">
                                                    <?php
                                                    renderLabel("questions[$i][slider][min]", "Min Value");
                                                    renderInput("number", "questions[$i][slider][min]", $q['slider']['min'] ?? 0);
                                                    ?>
                                                </div>
                                                <div class="flex-1">
                                                    <?php
                                                    renderLabel("questions[$i][slider][max]", "Max Value");
                                                    renderInput("number", "questions[$i][slider][max]", $q['slider']['max'] ?? 100);
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
                                                    renderInput("number", "questions[$i][slider][validMin]", $q['slider']['validMin'] ?? 0);
                                                    renderInput("number", "questions[$i][slider][validMax]", $q['slider']['validMax'] ?? 100);
                                                    ?>
                                                </div>
                                            </div>

                                        </div>
                                    </div>

                                </div>

                                <!-- DELETE BUTTON -->
                                <button type="button"
                                    class="delete-question px-3 py-1 bg-red-600 text-white rounded hover:bg-red-500 mt-3">
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

                        <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-500">
                            Save Quiz
                        </button>
                    </div>
                </form>

            </main>

            <?php
            require_once  __DIR__ . '/../../shared/ui/toast.php';
            ?>

            <?php
            require_once __DIR__ . '/../../shared/getScripts.php';
            echo getScripts('../../..');
            ?>
            <script defer>
                document.addEventListener("DOMContentLoaded", function() {
                    setupQuizDND();
                });
            </script>

        </div>
    </div>



</body>

</html>