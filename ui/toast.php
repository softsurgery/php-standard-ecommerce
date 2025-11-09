<?php
/**
 * Renders a Tailwind-style toast notification (static or initial).
 *
 * @param string $message The message to display inside the toast.
 * @param string $type    The toast type ('success', 'error', 'warning', 'info').
 * @param array  $attrs   Optional additional HTML attributes like ['id' => 'toast1'].
 */
function renderToast($message, $type = 'info', $attrs = [])
{
    $variants = [
        'success' => [
            'color' => 'text-green-500 bg-green-100 dark:bg-green-800 dark:text-green-200',
            'icon' => '<path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5Zm3.707 8.207-4 4a1 1 0 0 1-1.414 0l-2-2a1 1 0 0 1 1.414-1.414L9 10.586l3.293-3.293a1 1 0 0 1 1.414 1.414Z"/>',
        ],
        'error' => [
            'color' => 'text-red-500 bg-red-100 dark:bg-red-800 dark:text-red-200',
            'icon' => '<path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5Zm3.707 11.793a1 1 0 1 1-1.414 1.414L10 11.414l-2.293 2.293a1 1 0 0 1-1.414-1.414L8.586 10 6.293 7.707a1 1 0 0 1 1.414-1.414L10 8.586l2.293-2.293a1 1 0 0 1 1.414 1.414L11.414 10l2.293 2.293Z"/>',
        ],
        'warning' => [
            'color' => 'text-orange-500 bg-orange-100 dark:bg-orange-700 dark:text-orange-200',
            'icon' => '<path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM10 15a1 1 0 1 1 0-2 1 1 0 0 1 0 2Zm1-4a1 1 0 0 1-2 0V6a1 1 0 0 1 2 0v5Z"/>',
        ],
        'info' => [
            'color' => 'text-blue-500 bg-blue-100 dark:bg-blue-800 dark:text-blue-200',
            'icon' => '<path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5Zm1 14a1 1 0 1 1-2 0V9a1 1 0 0 1 2 0v5Zm-1-8a1 1 0 1 1 0-2 1 1 0 0 1 0 2Z"/>',
        ],
    ];

    $variant = $variants[$type] ?? $variants['info'];

    $attrs['role'] = $attrs['role'] ?? 'alert';
    $attrs['class'] = ($attrs['class'] ?? '') . ' flex items-center w-full max-w-xs p-4 mb-4 text-gray-500 bg-white rounded-lg shadow-sm dark:text-gray-400 dark:bg-gray-800';

    $attrString = '';
    foreach ($attrs as $key => $value) {
        $attrString .= sprintf(' %s="%s"', htmlspecialchars($key), htmlspecialchars(trim($value)));
    }

    echo <<<HTML
    <div{$attrString}>
        <div class="inline-flex items-center justify-center shrink-0 w-8 h-8 {$variant['color']} rounded-lg">
            <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                {$variant['icon']}
            </svg>
            <span class="sr-only">Icon</span>
        </div>
        <div class="ms-3 text-sm font-normal">{$message}</div>
        <button type="button" class="ms-auto -mx-1.5 -my-1.5 bg-white text-gray-400 hover:text-gray-900 rounded-lg focus:ring-2 focus:ring-gray-300 p-1.5 hover:bg-gray-100 inline-flex items-center justify-center h-8 w-8 dark:text-gray-500 dark:hover:text-white dark:bg-gray-800 dark:hover:bg-gray-700" aria-label="Close" onclick="this.parentElement.remove()">
            <span class="sr-only">Close</span>
            <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
            </svg>
        </button>
    </div>
HTML;
}
?>

<!-- Toast containers for all positions -->
<div id="toast-top-right" class="fixed top-5 right-5 z-50 space-y-2"></div>
<div id="toast-top-left" class="fixed top-5 left-5 z-50 space-y-2"></div>
<div id="toast-bottom-right" class="fixed bottom-5 right-5 z-50 space-y-2"></div>
<div id="toast-bottom-left" class="fixed bottom-5 left-5 z-50 space-y-2"></div>

<script>
function showToast(message, type = 'success', position = 'bottom-right') {
    const icons = {
        success: '<path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5Zm3.707 8.207-4 4a1 1 0 0 1-1.414 0l-2-2a1 1 0 0 1 1.414-1.414L9 10.586l3.293-3.293a1 1 0 0 1 1.414 1.414Z"/>',
        error: '<path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5Zm3.707 11.793a1 1 0 1 1-1.414 1.414L10 11.414l-2.293 2.293a1 1 0 0 1-1.414-1.414L8.586 10 6.293 7.707a1 1 0 0 1 1.414-1.414L10 8.586l2.293-2.293a1 1 0 0 1 1.414 1.414L11.414 10l2.293 2.293Z"/>',
        warning: '<path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM10 15a1 1 0 1 1 0-2 1 1 0 0 1 0 2Zm1-4a1 1 0 0 1-2 0V6a1 1 0 0 1 2 0v5Z"/>',
        info: '<path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5Zm1 14a1 1 0 1 1-2 0V9a1 1 0 0 1 2 0v5Zm-1-8a1 1 0 1 1 0-2 1 1 0 0 1 0 2Z"/>'
    };

    const colors = {
        success: 'text-green-500 bg-green-100 dark:bg-green-800 dark:text-green-200',
        error: 'text-red-500 bg-red-100 dark:bg-red-800 dark:text-red-200',
        warning: 'text-orange-500 bg-orange-100 dark:bg-orange-700 dark:text-orange-200',
        info: 'text-blue-500 bg-blue-100 dark:bg-blue-800 dark:text-blue-200'
    };

    const validPositions = ['top-left', 'top-right', 'bottom-left', 'bottom-right'];
    if (!validPositions.includes(position)) position = 'top-right';

    const containerId = 'toast-' + position;
    let container = document.getElementById(containerId);

    if (!container) {
        // Create container dynamically (fallback)
        container = document.createElement('div');
        container.id = containerId;
        container.className = `fixed ${position.includes('top') ? 'top-5' : 'bottom-5'} ${position.includes('right') ? 'right-5' : 'left-5'} z-50 space-y-2`;
        document.body.appendChild(container);
    }

    const toast = document.createElement('div');
    toast.className = 'flex items-center w-full max-w-xs p-4 mb-4 text-gray-500 bg-white rounded-lg shadow-sm dark:text-gray-400 dark:bg-gray-800 opacity-0 transform translate-x-5 transition-all duration-300';
    toast.setAttribute('role', 'alert');

    toast.innerHTML = `
        <div class="inline-flex items-center justify-center shrink-0 w-8 h-8 ${colors[type]} rounded-lg">
            <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                ${icons[type]}
            </svg>
        </div>
        <div class="ms-3 text-sm font-normal">${message}</div>
        <button type="button" class="ms-auto -mx-1.5 -my-1.5 bg-white text-gray-400 hover:text-gray-900 rounded-lg focus:ring-2 focus:ring-gray-300 p-1.5 hover:bg-gray-100 inline-flex items-center justify-center h-8 w-8 dark:text-gray-500 dark:hover:text-white dark:bg-gray-800 dark:hover:bg-gray-700" aria-label="Close">
            <svg class="w-3 h-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
            </svg>
        </button>
    `;

    container.appendChild(toast);

    // Animate in
    requestAnimationFrame(() => {
        toast.classList.remove('opacity-0', 'translate-x-5');
        toast.classList.add('opacity-100', 'translate-x-0');
    });

    // Auto remove after 3.5s
    setTimeout(() => {
        toast.classList.add('opacity-0', 'translate-x-5');
        setTimeout(() => toast.remove(), 300);
    }, 3500);

    // Manual close
    toast.querySelector('button').addEventListener('click', () => {
        toast.classList.add('opacity-0', 'translate-x-5');
        setTimeout(() => toast.remove(), 300);
    });
}
</script>
