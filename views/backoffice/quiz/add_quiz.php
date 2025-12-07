<!DOCTYPE html>
<html lang="en">

<?php

require_once __DIR__ . '/../../shared/getHeader.php';
require_once __DIR__ . '/../../shared/ui/input.php';
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
                    <div id="questions-container" class="mt-4 p-4 border rounded-lg bg-gray-100">
                        <?php
                        // Example: Prepopulate with 1 question (replace with DB data)
                        $questions = [
                            ['label' => '', 'type' => 'Text']
                        ];

                        foreach ($questions as $i => $q) {
                            // QUESTION ROW must be draggable and have class 'question-row'
                            echo '<div class="mb-4 p-4 border rounded-lg bg-gray-300 question-row" draggable="true">';

                            echo '<div class="flex flex-row gap-2">';

                            echo '<div class="w-1/2">';
                            renderLabel("questions[$i][label]", "Question Label");
                            renderInput('text', "questions[$i][label]", $q['label'], 'Enter question label');
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
                                $q['type']
                            );
                            echo '</div>';

                            echo '</div>';
                            echo '<button type="button" class="delete-question px-3 py-1 bg-red-600 text-white rounded hover:bg-red-500 mt-2">';
                            echo 'Delete';
                            echo '</button>';
                            echo '</div>';
                        }
                        ?>

                    </div>

                    <!-- Hidden template: note __INDEX__ placeholders -->
                    <div id="question-template" class="hidden">
                        <div class="mb-4 p-4 border rounded-lg bg-gray-300 question-row" draggable="true">
                            <?php
                            echo '<div class="flex flex-row gap-2">';

                            echo '<div class="w-1/2">';
                            // Use __INDEX__ placeholder — JS will replace it before inserting
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
                            Save Quiz
                        </button>
                    </div>
                </form>

            </main>

            <?php
            require_once  __DIR__ . '/../../shared/ui/toast.php';
            ?>

            <!-- Toast & URL param script (unchanged) -->
            <script>
                // Parse URL query parameters
                const urlParams = new URLSearchParams(window.location.search);

                // Check for success or error
                if (urlParams.get('success') === 'true') {
                    const message = urlParams.get('message') || 'Action completed successfully!';
                    showToast(message, 'success', 'bottom-right');
                } else if (urlParams.get('error') === 'true') {
                    const message = urlParams.get('message') || 'Something went wrong.';
                    showToast(message, 'error', 'bottom-right');
                }

                // Remove query parameters from URL without reloading
                if (urlParams.has('success') || urlParams.has('error')) {
                    const newUrl = window.location.pathname; // keeps same path, removes query
                    window.history.replaceState({}, document.title, newUrl);
                }
            </script>

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