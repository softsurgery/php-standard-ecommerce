<?php
/**
 * Renders a Tailwind-style modal with buttons using renderButton().
 *
 * @param string $id Modal ID (unique)
 * @param string $title Modal title
 * @param string $content Modal main body content (HTML allowed)
 * @param array $footer Array of buttons, each like ['label' => '', 'class' => '', 'attrs' => [], 'variant' => 'default']
 * @param string $size Width size class (e.g., 'max-w-lg', 'max-w-2xl', 'max-w-3xl')
 */
function renderModal($id, $title = '', $content = '', $footer = [], $size = 'max-w-2xl') {
?>
<div id="<?= htmlspecialchars($id) ?>" tabindex="-1" aria-hidden="true"
    class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 flex justify-center items-center w-full h-full">
    <div class="relative w-full <?= htmlspecialchars($size) ?> max-h-full">
        <!-- Modal content -->
        <div class="relative bg-white dark:bg-gray-800 border rounded-lg shadow-lg p-6 min-w-[500px]">
            <!-- Header -->
            <div class="flex items-center justify-between border-b border-gray-200 pb-4">
                <h3 class="text-lg font-medium text-gray-900 dark:text-white"><?= htmlspecialchars($title) ?></h3>
                <button type="button"
                    class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 flex items-center justify-center dark:hover:bg-gray-600 dark:hover:text-white"
                    data-modal-hide="<?= htmlspecialchars($id) ?>" aria-label="Close">
                    <svg class="w-3 h-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                    </svg>
                </button>
            </div>

            <!-- Body -->
            <div class="py-4 text-gray-900 dark:text-white">
                <?= $content ?>
            </div>

            <!-- Footer -->
            <?php if (!empty($footer)): ?>
            <div class="flex items-center justify-end space-x-3 border-t border-gray-200 pt-4">
                <?php foreach ($footer as $btn): 
                    $label = $btn['label'] ?? 'Button';
                    $variant = $btn['variant'] ?? 'default';
                    $class = $btn['class'] ?? '';
                    $attrs = $btn['attrs'] ?? [];
                    
                    // Merge variant with any extra classes if provided
                    if ($class) $attrs['class'] = $class;
                    
                    renderButton($label, $variant, $attrs);
                endforeach; ?>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>
<?php
}
?>
