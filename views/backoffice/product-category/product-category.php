<!DOCTYPE html>
<html lang="en">

<?php


require_once __DIR__ . '/../../shared/getHeader.php';
require_once __DIR__ . '/../../shared/ui/drawer.php';
require_once __DIR__ . '/../../shared/ui/dialog.php';
require_once __DIR__ . '/../../shared/ui/button.php';
require_once __DIR__ . '/../../shared/ui/pagination.php';
require_once __DIR__ . '/product-category.create-form.php';
require_once __DIR__ . '/product-category.update-form.php';

require_once __DIR__ . '/../../../controllers/ProductCategoryController.php';

echo getPageHead('Product Category', '../../..');
?>

<body>

    <div class="flex flex-1 overflow-hidden h-screens">
        <!-- Sidebar -->
        <?php
        require_once __DIR__ . '/../getBackofficeSidebar.php';
        echo getBackofficeSidebar('../../..', 'product-categories');
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
                        <?php renderButton('Add Product Category', $variant = 'green', $attrs = [
                            'aria-controls' => 'create-drawer',
                            'data-drawer-target' => 'create-drawer',
                            'data-drawer-show' => 'create-drawer',
                            'data-drawer-placement' => "right"
                        ]) ?>
                    </div>
                    <div class="overflow-auto bg-white rounded shadow">
                        <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                            <thead
                                class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400 sticky top-0">
                                <tr>
                                    <th class="px-6 py-3">ID</th>
                                    <th class="px-6 py-3">Label</th>
                                    <th class="px-6 py-3">Description</th>
                                    <th class="px-6 py-3 text-right">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 max-h-[500px] overflow-auto">
                                <?php
                                $controller = new ProductCategoryController();
                                $categories = $controller->getPaginated(1, 10); // page 1, size 10
                                foreach ($categories['data'] as $category): ?>
                                    <tr class="bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700">
                                        <td class="px-6 py-4 font-medium text-gray-900 dark:text-white">
                                            <?= htmlspecialchars($category['id']) ?>
                                        </td>
                                        <td class="px-6 py-4"><?= htmlspecialchars($category['label']) ?></td>
                                        <td class="px-6 py-4"><?= htmlspecialchars($category['description']) ?></td>
                                        <td class="px-6 py-4 text-right">
                                            <div class="inline-flex space-x-2">
                                                <?php renderButton('Edit', 'default', [
                                                    'aria-controls' => 'update-drawer',
                                                    'data-drawer-target' => 'update-drawer',
                                                    'data-drawer-show' => 'update-drawer',
                                                    'data-drawer-placement' => "right",
                                                    'data-id' => $category['id'],
                                                    'data-label' => htmlspecialchars($category['label']),
                                                    'data-description' => htmlspecialchars($category['description'])
                                                ]) ?>
                                                <?php renderButton('Delete', 'red', [
                                                    'data-modal-target' => 'delete-modal',
                                                    'data-modal-show' => 'delete-modal',
                                                    'data-category-id' => $category['id'],
                                                    'data-category-label' => htmlspecialchars($category['label']),
                                                ]) ?>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>


                    <?php
                    $totalItems = 100;
                    $currentPage = 2;
                    $pageSize = 10;
                    renderPagination($currentPage, $pageSize, $totalItems, $id = 'pagination');
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
    $createForm = renderProductCategoryCreateForm(
        '../../product-category/handle-add.php',
        ['label' => '', 'description' => ''],
        'POST'
    );

    renderDrawer(
        'w-[30vw]',
        'create-drawer',
        'New Product Category',
        $createForm,
        'right'
    );


    $updateForm = renderProductCategoryUpdateForm(
        '../../product-category/handle-update.php',
        ['label' => '', 'description' => ''],
        'POST'
    );


    renderDrawer(
        'w-[30vw]',
        'update-drawer',
        'Update Product Category',
        $updateForm,
        'right'
    );

    $content = '<p id="delete-modal-body">Are you sure you want to delete this category?</p>';
    $footer = [
        ['label' => 'Accept', 'variant' => 'green',  'class' => 'text-white bg-green-600 hover:bg-green-700', 'attrs' => ['id' => 'delete-confirm', 'data-modal-hide' => 'delete-modal']],
        ['label' => 'Decline',  'variant' => 'red', 'class' => 'text-gray-700 bg-gray-200 hover:bg-gray-300', 'attrs' => ['data-modal-hide' => 'delete-modal']],
    ];

    renderModal('delete-modal', 'Delete Category', $content, $footer, 'max-w-2xl');
    ?>



    <?php
    require_once __DIR__ . '/../../shared/getScripts.php';
    echo getScripts('../../..');
    ?>
    <!-- populate update modal -->
    <script>
        document.querySelectorAll('[data-drawer-target="update-drawer"]').forEach(btn => {
            btn.addEventListener('click', () => {
                const id = btn.getAttribute('data-id');
                const label = btn.getAttribute('data-label');
                const description = btn.getAttribute('data-description');

                // Fill form fields
                document.getElementById('update-id').value = id;
                document.getElementById('update-label').value = label;
                document.getElementById('update-description').value = description;
            });
        });
    </script>

    <!-- prepare delete modal -->
    <script>
        const origin = './views/backoffice/product-category/product-category.php';
        document.querySelectorAll('[data-modal-show]').forEach(btn => {
            btn.addEventListener('click', () => {
                const modalId = btn.getAttribute('data-modal-target');
                const modal = document.getElementById(modalId);

                // Populate modal content
                const categoryLabel = btn.getAttribute('data-category-label');
                const categoryId = btn.getAttribute('data-category-id');
                const body = modal.querySelector('#delete-modal-body');
                body.textContent = `Are you sure you want to delete the category "${categoryLabel}"?`;

                // Set confirm button action
                const confirmBtn = modal.querySelector('#delete-confirm');
                confirmBtn.onclick = function() {
                    window.location.href = `../../product-category/handle-delete.php?id=${categoryId}&origin=../../${origin}`;
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