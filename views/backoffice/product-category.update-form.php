<?php
function renderProductCategoryUpdateForm($action = '#', $values = [], $method = 'POST')
{
    $label = htmlspecialchars($values['label'] ?? '');
    $description = htmlspecialchars($values['description'] ?? '');

    // Start output buffering
    ob_start();
?>
    <form action="<?= htmlspecialchars($action) ?>" method="<?= htmlspecialchars($method) ?>" class="space-y-6">
        <!-- Label -->
        <input type="hidden" name="id" id="update-id">
        <div>
            <label for="label" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                Category Label
            </label>
            <input type="text" id="update-label" name="label"
                value="<?= $label ?>"
                required
                placeholder="Enter category name"
                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white">
        </div>

        <!-- Description -->
        <div>
            <label for="description" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                Description
            </label>
            <textarea id="update-description" name="description" rows="3" placeholder="Enter category description"
                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white"><?= $description ?></textarea>
        </div>

        <input type="text" class="hidden" value="./views/backoffice/product-category.php" name="origin">

        <!-- Submit Button -->
        <div class="flex justify-end">
            <?php
            renderButton('Update Category', 'green', ['type' => 'submit']);
            ?>
        </div>
    </form>
<?php
    // Return everything captured above as a string
    return ob_get_clean();
}
?>