<!DOCTYPE html>
<html lang="en">

<?php

require_once __DIR__ . '/../../shared/ui/button.php';
require_once __DIR__ . '/../../shared/ui/input.php';
require_once __DIR__ . '/../../shared/ui/dialog.php';
require_once __DIR__ . '/../../shared/getHeader.php';
require_once __DIR__ . '/../../shared/ui/pagination.php';
require_once __DIR__ . '/../../../controllers/SubmissionController.php';

$totalItems = 100;
$page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
$size    = isset($_GET['size']) ? max(1, intval($_GET['size'])) : 10;

echo getPageHead('Submissions', '../../..');
?>

<body>
    <div class="flex flex-1 overflow-hidden h-screens">
        <!-- Sidebar -->
        <?php
        require_once __DIR__ . '/../getBackofficeSidebar.php';
        echo getBackofficeSidebar('../../..', "submissions");
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
                    <form class="flex flex-row gap-2 justify-center items-center my-2" method="GET">

                        <?php
                        echo "<input type='hidden' name='page' value='$page'>";
                        echo "<input type='hidden' name='size' value='$size'>";


                        ?>
                    </form>
                    <div class="overflow-auto bg-white rounded shadow">
                        <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                            <thead
                                class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400 sticky top-0">
                                <tr>
                                    <th class="px-6 py-3">Quiz Name</th>
                                    <th class="px-6 py-3">User Email</th>
                                    <th class="px-6 py-3">User Name</th>
                                    <th class="px-6 py-3">User Surname</th>
                                    <th class="px-6 py-3 text-right">Score</th>
                                    <th class="px-6 py-3 text-right">Created At</th>
                                    <th class="px-6 py-3 text-right">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 max-h-[500px] overflow-auto">
                                <?php
                                $controller = new SubmissionController();
                                $submissions = $controller->getPaginated($page, $size);
                                foreach ($submissions['data'] as $submission):
                                    $quiz_id = $submission['quiz_id'];
                                    $user_id = $submission['user_id'];
                                ?>
                                    <tr class="bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700">
                                        <td class="px-6 py-4"><?= htmlspecialchars($submission['quiz_name'], ENT_QUOTES, 'UTF-8') ?></td>
                                        <td class="px-6 py-4"><?= htmlspecialchars($submission['email'], ENT_QUOTES, 'UTF-8') ?></td>

                                        <td class="px-6 py-4">
                                            <?php if (!empty($submission['name'])): ?>
                                                <?= htmlspecialchars($submission['name'], ENT_QUOTES, 'UTF-8') ?>
                                            <?php else: ?>
                                                <span class="opacity-50">Not Specified</span>
                                            <?php endif; ?>
                                        </td>

                                        <td class="px-6 py-4">
                                            <?php if (!empty($submission['surname'])): ?>
                                                <?= htmlspecialchars($submission['surname'], ENT_QUOTES, 'UTF-8') ?>
                                            <?php else: ?>
                                                <span class="opacity-50">Not Specified</span>
                                            <?php endif; ?>
                                        </td>

                                        <td class="px-6 py-4"><?= $submission['score'] ?></td>

                                        <td class="px-6 py-4 text-right">
                                            <?= date('Y-m-d H:i', strtotime($submission['createdAt'])) ?>
                                        </td>
                                        <td>
                                            <?php
                                            renderButton('View', 'default', [
                                                'onclick' => "goToQuizSubmission($quiz_id, $user_id)",
                                            ]);
                                            ?>
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
        </div>
    </div>

    <?php
    $content = '<p id="delete-modal-body">Are you sure you want to delete this quiz?</p>';
    $footer = [
        ['label' => 'Accept', 'variant' => 'green',  'class' => 'text-white bg-green-600 hover:bg-green-700', 'attrs' => ['id' => 'delete-confirm', 'data-modal-hide' => 'delete-modal']],
        ['label' => 'Decline',  'variant' => 'red', 'class' => 'text-gray-700 bg-gray-200 hover:bg-gray-300', 'attrs' => ['data-modal-hide' => 'delete-modal']],
    ];

    renderModal('delete-modal', 'Delete Quiz', $content, $footer, 'max-w-2xl');
    require_once __DIR__ . '/../../shared/getScripts.php';
    echo getScripts('../../..');
    ?>
    <script>
        const origin = './views/backoffice/quiz/quiz-portal.php';
        document.querySelectorAll('[data-modal-show]').forEach(btn => {
            btn.addEventListener('click', () => {
                const modalId = btn.getAttribute('data-modal-target');
                const modal = document.getElementById(modalId);

                // Populate modal content
                const label = btn.getAttribute('data-quiz-name');
                const quizId = btn.getAttribute('data-quiz-id');
                const body = modal.querySelector('#delete-modal-body');
                body.textContent = `Are you sure you want to delete the quiz "${label}"?`;

                // Set confirm button action
                const confirmBtn = modal.querySelector('#delete-confirm');
                confirmBtn.onclick = function() {
                    window.location.href = `../../quiz/handle-delete.php?id=${quizId}&origin=../../${origin}`;
                };

                // Show modal
                modal.classList.remove('hidden');
            });
        });
        // Close modal buttons
        document.querySelectorAll('[data-modal-hide]').forEach(btn => {
            btn.addEventListener('click', () => {
                const modalId = btn.getAttribute('data-modal-hide');
                const modal = document.getElementById(modalId);
                modal.classList.add('hidden');
            });
        });
    </script>
</body>

</html>