<?php
function renderRadio(
    $name,
    $value,
    $checked = false,
    $label = '',
    $attrs = []
) {
    $defaultClass = "w-4 h-4 text-blue-600 bg-gray-100 border-gray-300
                     focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800
                     focus:ring-2 dark:bg-gray-700 dark:border-gray-600";

    // Merge class if passed in
    $extraClass = '';
    if (isset($attrs['class'])) {
        $extraClass = $attrs['class'];
        unset($attrs['class']);
    }

    $class = trim($defaultClass . ' ' . $extraClass);

    // Build remaining attributes
    $attrString = '';
    foreach ($attrs as $key => $val) {
        $attrString .= " " . htmlspecialchars($key) . '="' . htmlspecialchars($val) . '"';
    }

    // Unique ID per radio option
    $id = htmlspecialchars($name . '_' . $value);

    echo '
    <div class="flex items-center">
        <input
            type="radio"
            id="' . $id . '"
            name="' . htmlspecialchars($name) . '"
            value="' . htmlspecialchars($value) . '"
            class="' . $class . '"
            ' . ($checked ? 'checked' : '') . '
            ' . $attrString . '
        >
        ' . ($label !== ''
        ? '<label for="' . $id . '" class="ms-2 text-sm font-medium text-gray-900">' . htmlspecialchars($label) . '</label>'
        : '') . '
    </div>';
}
