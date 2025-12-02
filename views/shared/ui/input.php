<?php
function renderInput(
    $type,
    $name,
    $value = '',
    $placeholder = '',
    $attrs = []
) {
    $defaultClass = "bg-gray-50 border border-gray-300 text-gray-900 text-sm 
                     rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5
                     dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 
                     dark:text-white";

    // Merge `class` attribute if passed in $attrs
    $extraClass = '';
    if (isset($attrs['class'])) {
        $extraClass = $attrs['class'];
        unset($attrs['class']); // remove so we don't duplicate it later
    }

    // Final class attribute
    $class = trim($defaultClass . ' ' . $extraClass);

    // Build remaining attributes
    $attrString = '';
    foreach ($attrs as $key => $val) {
        $attrString .= " " . htmlspecialchars($key) . '="' . htmlspecialchars($val) . '"';
    }

    echo '
    <input
        type="'.htmlspecialchars($type).'"
        id="'.htmlspecialchars($name).'"
        name="'.htmlspecialchars($name).'"
        value="'.htmlspecialchars($value).'"
        placeholder="'.htmlspecialchars($placeholder).'"
        class="'.$class.'"'.$attrString.'
    >';
}

?>
