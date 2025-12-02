<!DOCTYPE html>
<html lang="en">

<?php

require_once __DIR__ . '/../../shared/ui/button.php';
require_once __DIR__ . '/../../shared/ui/input.php';
require_once __DIR__ . '/../../shared/getHeader.php';
require_once __DIR__ . '/../../shared/ui/pagination.php';
require_once __DIR__ . '/../../../controllers/QuizController.php';

$totalItems = 100;
$page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
$size    = isset($_GET['size']) ? max(1, intval($_GET['size'])) : 10;
$search      = isset($_GET['search']) ? trim($_GET['search']) : '';

echo getPageHead('Quiz', '../../..');
?>

<body>
    <script>
        function goToQuizCreateForm() {
            window.location.href = "./add_quiz.php";
        }

        function goToQuizUpdateForm(id) {
            window.location.href = "./update_quiz.php?id=" + id;
        }
    </script>

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
            <main class="flex flex-col flex-1 p-5 bg-gray-300 overflow-hidden">
                <div class="relative container mx-auto flex flex-col flex-1 overflow-hidden">
                    <!-- Drawer Trigger -->
                    <!-- drawer init and toggle -->
                    <div class="text-center">
                        <?php renderButton('Add Quiz', $variant = 'green', $attrs = [
                            'onclick' => 'goToQuizCreateForm()',
                        ]) ?>
                    </div>
                    <form class="flex flex-row gap-2 justify-center items-center my-2" method="GET">
                        
                        <?php
                        echo "<input type='hidden' name='page' value='$page'>";
                        echo "<input type='hidden' name='size' value='$size'>";
                        
                        renderInput('text', 'search', $search, 'Filter quizzes', [
                            'class' => 'mb-1',
                        ]);
                        renderButton('Search', 'green', [
                            'class' => '',
                            'type' => 'submit',
                        ]);
                        ?>
                    </form>
                    <div class="overflow-auto bg-white rounded shadow">
                        <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                            <thead
                                class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400 sticky top-0">
                                <tr>
                                    <th class="px-6 py-3">ID</th>
                                    <th class="px-6 py-3">Name</th>
                                    <th class="px-6 py-3">Description</th>
                                    <th class="px-6 py-3 text-right">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 max-h-[500px] overflow-auto">
                                <?php
                                $controller = new QuizController();
                                $quizes = $controller->getPaginated($page, $size, $search);
                                foreach ($quizes['data'] as $quiz): ?>
                                    <tr class="bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700">
                                        <td class="px-6 py-4 font-medium text-gray-900 dark:text-white">
                                            <?= htmlspecialchars($quiz['id']) ?>
                                        </td>
                                        <td class="px-6 py-4"><?= htmlspecialchars($quiz['name']) ?></td>
                                        <td class="px-6 py-4"><?= htmlspecialchars($quiz['description']) ?></td>
                                        <td class="px-6 py-4 text-right">
                                            <div class="inline-flex space-x-2">
                                                <?php
                                                $id = $quiz['id'];
                                                renderButton('Edit', 'default', [
                                                    'onclick' => "goToQuizUpdateForm($id)",
                                                ]) ?>
                                                <?php renderButton('Delete', 'red', [
                                                    'data-modal-target' => 'delete-modal',
                                                    'data-modal-show' => 'delete-modal',
                                                    'data-quiz-id' => $quiz['id'],
                                                    'data-quiz-name' => htmlspecialchars($quiz['name']),
                                                ]) ?>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>


                    <?php
                    renderPagination($page, $size, 100, $id = 'pagination');
                    ?>
                </div>
            </main>
            <?php
            require_once  __DIR__ . '/../../shared/ui/toast.php';
            ?>
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

        </div>
    </div>

    <?php
    require_once __DIR__ . '/../../shared/getScripts.php';
    echo getScripts('../../..');
    ?>

</body>

</html>