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
        echo getBackofficeSidebar();
        ?>
        <!-- Header + Main -->
        <div class="flex flex-col flex-1 overflow-hidden h-screen">
            <?php
            require_once __DIR__ . '/../getBackofficeHeader.php';
            echo getBackofficeHeader();
            ?>
            <main class="flex flex-col flex-1 p-5 bg-gray-300 overflow-auto">
                <form action="../../quiz/handle-add.php" method="post" id="quiz-form">
                    <div>
                        <?php
                        renderLabel('name', 'Name');
                        renderInput('text', 'name', '', 'Enter quiz name');

                        renderLabel('description', 'Description');
                        renderTextarea('description', '', 4, 'Enter quiz description');
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

                            renderLabel("questions[$i][label]", "Question Label");
                            renderInput('text', "questions[$i][label]", $q['label'], 'Enter question label');

                            renderLabel("questions[$i][type]", "Question Type");
                            renderSelect(
                                "questions[$i][type]",
                                [
                                    (object)['id' => 'Text', 'label' => 'Text'],
                                    (object)['id' => 'Switch', 'label' => 'Switch'],
                                    (object)['id' => 'Checkbox', 'label' => 'Checkbox'],
                                    (object)['id' => 'Radio', 'label' => 'Radio'],
                                    (object)['id' => 'Slider', 'label' => 'Slider'],
                                ],
                                $q['type']
                            );

                            echo '</div>';
                        }
                        ?>
                    </div>

                    <!-- Hidden template: note __INDEX__ placeholders -->
                    <div id="question-template" class="hidden">
                        <div class="mb-4 p-4 border rounded-lg bg-gray-300 question-row" draggable="true">
                            <?php
                            // Use __INDEX__ placeholder — JS will replace it before inserting
                            renderLabel('questions[__INDEX__][label]', 'Question Label');
                            renderInput('text', 'questions[__INDEX__][label]', '', 'Enter question label');

                            renderLabel('questions[__INDEX__][type]', 'Question Type');
                            renderSelect(
                                'questions[__INDEX__][type]',
                                [
                                    (object)['id' => 'Text', 'label' => 'Text'],
                                    (object)['id' => 'Switch', 'label' => 'Switch'],
                                    (object)['id' => 'Checkbox', 'label' => 'Checkbox'],
                                    (object)['id' => 'Radio', 'label' => 'Radio'],
                                    (object)['id' => 'Slider', 'label' => 'Slider'],
                                ]
                            );
                            ?>
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

            <!-- Add Question + Drag & Drop + Reindexing -->
            <script>
                (function () {
                    const container = document.getElementById('questions-container');
                    const templateEl = document.getElementById('question-template');
                    let questionCount = container.querySelectorAll('.question-row').length;

                    // Read raw HTML template (must be innerHTML so placeholders remain)
                    const rawTemplate = templateEl.innerHTML;

                    // Utility: replace all occurrences of __INDEX__ with index
                    function buildQuestionHtml(index) {
                        return rawTemplate.replace(/__INDEX__/g, index);
                    }

                    // Insert node from HTML string and return the inserted element
                    function insertHtmlAsElement(html, parent) {
                        const temp = document.createElement('div');
                        temp.innerHTML = html.trim();
                        const el = temp.firstElementChild;
                        parent.appendChild(el);
                        return el;
                    }

                    // Update name/id/for indices for all rows to reflect DOM order
                    function reindexAll() {
                        const rows = container.querySelectorAll('.question-row');
                        rows.forEach((row, idx) => {
                            // For each input/select/textarea inside the row, replace index in name & id
                            row.querySelectorAll('input, select, textarea, label').forEach(el => {
                                // Update name attribute (e.g., questions[3][label])
                                if (el.name) {
                                    el.name = el.name.replace(/questions\[\d+\]/g, `questions[${idx}]`);
                                }
                                // Update id attribute
                                if (el.id) {
                                    el.id = el.id.replace(/questions\[\d+\]/g, `questions[${idx}]`);
                                }
                                // Update label 'for' attributes
                                if (el.tagName.toLowerCase() === 'label' && el.htmlFor) {
                                    el.htmlFor = el.htmlFor.replace(/questions\[\d+\]/g, `questions[${idx}]`);
                                }
                            });
                        });

                        // keep the questionCount in sync with current number of rows
                        questionCount = container.querySelectorAll('.question-row').length;
                    }

                    // Drag & drop handling
                    let dragged = null;

                    function setupDragForRow(row) {
                        row.addEventListener('dragstart', (e) => {
                            dragged = row;
                            row.classList.add('dragging');
                            // small data to enable drag in some browsers
                            e.dataTransfer.setData('text/plain', 'dragging');
                            e.dataTransfer.effectAllowed = 'move';
                        });

                        row.addEventListener('dragend', () => {
                            if (dragged) dragged.classList.remove('dragging');
                            dragged = null;
                            removeDropHover();
                            reindexAll(); // ensure indexes are correct after drop
                        });

                        row.addEventListener('dragover', (e) => {
                            e.preventDefault();
                            if (!dragged || dragged === row) return;
                            removeDropHover();
                            row.classList.add('drop-hover');
                        });

                        row.addEventListener('dragleave', () => {
                            row.classList.remove('drop-hover');
                        });

                        row.addEventListener('drop', (e) => {
                            e.preventDefault();
                            if (!dragged || dragged === row) return;

                            // Insert dragged before the drop target
                            container.insertBefore(dragged, row);
                            removeDropHover();
                            reindexAll();
                        });
                    }

                    function removeDropHover() {
                        container.querySelectorAll('.drop-hover').forEach(el => el.classList.remove('drop-hover'));
                    }

                    // Apply drag handlers to all rows (existing or newly added)
                    function applyDragToAll() {
                        const rows = container.querySelectorAll('.question-row');
                        rows.forEach(r => {
                            // Avoid binding multiple times — check a flag
                            if (!r.dataset.dragBound) {
                                setupDragForRow(r);
                                r.dataset.dragBound = '1';
                            }
                        });
                    }

                    // Add question handler
                    document.getElementById('add-question').addEventListener('click', () => {
                        const html = buildQuestionHtml(questionCount);
                        const newEl = insertHtmlAsElement(html, container);

                        // Make sure newly inserted element has the expected class and draggable attribute
                        // (render helpers already set them, but keep safe)
                        newEl.classList.add('question-row');
                        newEl.setAttribute('draggable', 'true');

                        // Bind drag events for the new row
                        applyDragToAll();

                        // Reindex names/ids so the new row gets proper index
                        reindexAll();
                    });

                    // Initial setup
                    applyDragToAll();
                    reindexAll();

                    // Optional: when form is submitted, ensure indexes are consistent
                    const form = document.getElementById('quiz-form');
                    form.addEventListener('submit', () => {
                        reindexAll();
                        // you can add validation here
                    });

                    // Expose a debug function in console (optional)
                    window.debugReindex = reindexAll;
                })();
            </script>

        </div>
    </div>

    <?php
    require_once __DIR__ . '/../../shared/getScripts.php';
    echo getScripts('../../..');
    ?>

</body>

</html>
