<!DOCTYPE html>
<html lang="en">

<?php


require_once '../getHeader.php';
require_once '../ui/drawer.php';
require_once '../ui/button.php';
require_once '../ui/pagination.php';
require_once './product-category.create-form.php';
require_once './product-category.update-form.php';
echo getPageHead('Product Category');
?>

<body>
    <div class="flex flex-1 overflow-hidden h-screens">
        <!-- Sidebar -->
        <?php
        require_once 'getBackofficeSidebar.php';
        echo getBackofficeSidebar();
        ?>
        <!-- Header + Main -->
        <div class="flex flex-col flex-1 overflow-hidden h-screen">
            <?php
            require_once './getBackofficeHeader.php';
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
                                require_once '../controllers/ProductCategoryController.php';
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
                                                    'aria-controls' => 'drawer-example',
                                                    'data-drawer-target' => 'drawer-example',
                                                    'data-drawer-show' => 'drawer-example',
                                                    'data-drawer-placement' => "right"
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
            require_once '../ui/toast.php';
            ?>
        </div>
    </div>


    <?php
    $createForm = renderProductCategoryCreateForm(
        '../views/product-category/handle-add.php',
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
        '../views/product-category/handle-update.php',
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
    ?>



    <?php
    require_once '../getScripts.php';
    echo getScripts();
    ?>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const form = document.querySelector('#create-drawer .drawer-content form');
            if (!form) return;

            form.addEventListener('submit', async (e) => {
                e.preventDefault();

                const formData = new FormData(form);
                const submitBtn = form.querySelector('button[type="submit"]');
                submitBtn.disabled = true;

                try {
                    const response = await fetch(form.action, {
                        method: form.method,
                        body: formData
                    });

                    const result = await response.json();
                    console.log(result);

                    if (result.success) {
                        // ✅ Add new row to the table dynamically
                        const tbody = document.querySelector('table tbody');
                        const newRow = document.createElement('tr');
                        newRow.className = 'bg-white border-b dark:bg-gray-800 dark:border-gray-700 border-gray-200';
                        newRow.innerHTML = `
          <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">${result.data.id}</th>
          <td class="px-6 py-4">${result.data.label}</td>
          <td class="px-6 py-4">${result.data.description}</td>
        `;
                        tbody.appendChild(newRow);

                        // ✅ Clear form fields
                        form.reset();

                        // ✅ Close the drawer (Flowbite way)
                        const drawerXEl = document.getElementById('create-drawer-x');
                        drawerXEl.click();
                        showToast('Product category added successfully!', 'success');
                    } else {
                        alert('Error: ' + (result.message || 'Failed to save category.'));
                    }
                } catch (error) {
                    console.error('AJAX error:', error);
                    alert('Unexpected error, check console.');
                    showToast(result.message || 'Failed to save category.', 'error');
                } finally {
                    submitBtn.disabled = false;
                }
            });
        });
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
</body>

</html>