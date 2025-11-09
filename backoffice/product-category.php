<!DOCTYPE html>
<html lang="en">

<?php


require_once '../getHeader.php';
require_once '../ui/drawer.php';
require_once '../ui/button.php';
require_once './product-category.form.php';
echo getPageHead('Product Category');
?>

<body>
    <div class="flex flex-1 overflow-hidden h-screen">
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
            <main class="flex flex-col flex-1 p-5 bg-gray-300 overflow-y-auto overflow-x-hidden">
                <div class="relative">
                    <!-- Drawer Trigger -->
                    <!-- drawer init and toggle -->
                    <div class="text-center">

                        <?php renderButton('Add Product Category', $variant = 'green', $attrs = [
                            'aria-controls' => 'drawer-example',
                            'data-drawer-target' => 'drawer-example',
                            'data-drawer-show' => 'drawer-example',
                            'data-drawer-placement' => "right"
                        ]) ?>
                    </div>
                    <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                            <tr>
                                <th scope="col" class="px-6 py-3">
                                    ID
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    Label
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    Description
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            require_once '../controllers/ProductCategoryController.php';
                            $controller = new ProductCategoryController();
                            $categories = $controller->getAll();
                            foreach ($categories as $category) {
                                echo ' <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 border-gray-200">';
                                echo ' <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">';
                                echo $category['id'];
                                echo '</th>';
                                echo '<td class="px-6 py-4">';
                                echo $category['label'];
                                echo '</td>';
                                echo '<td class="px-6 py-4">';
                                echo $category['description'];
                                echo '</td>';
                                echo '</tr>';
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </main>
            <?php
            require_once '../ui/toast.php';
            ?>
        </div>
    </div>


    <?php
    $form = renderProductCategoryForm(
        '../views/handle-add.php',
        ['label' => '', 'description' => ''],
        'POST'
    );

    renderDrawer(
        'w-[30vw]',
        'drawer-example',
        'Help Menu',
        $form,
        'right'
    );
    ?>

    <?php
    require_once '../getScripts.php';
    echo getScripts();
    ?>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const form = document.querySelector('#drawer-example .drawer-content form');
            if (!form) return;

            form.addEventListener('submit', async (e) => {
                e.preventDefault();

                const formData = new FormData(form);
                const submitBtn = form.querySelector('button[type="submit"]');
                console.log('submitBtn', submitBtn)
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
                        const drawerXEl = document.getElementById('drawer-example-x');
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
    </script>
</body>

</html>