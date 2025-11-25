<?php

/**
 * Renders a Tailwind-styled textarea.
 *
 * @param string $name & ID of the textarea
 * @param string $value Default value
 * @param int    $rows Number of rows
 * @param string $placeholder Placeholder text
 * @param array  $attrs Extra HTML attributes (key => value)
 * @param string $class Extra Tailwind classes if needed
 */
function renderTextarea($name, $value = '', $rows = 4, $placeholder = '', $attrs = [], $class = '')
{
    $defaultClass = "bg-gray-50 border border-gray-300 text-gray-900 text-sm 
                     rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5
                     dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 
                     dark:text-white";
    if ($class) $defaultClass .= " " . $class;

    // Build attributes
    $attrString = '';
    foreach ($attrs as $key => $val) {
        $attrString .= " " . htmlspecialchars($key) . '="' . htmlspecialchars($val) . '"';
    }

    echo '<textarea
            id="' . htmlspecialchars($name) . '"
            name="' . htmlspecialchars($name) . '"
            rows="' . intval($rows) . '"
            placeholder="' . htmlspecialchars($placeholder) . '"
            class="' . htmlspecialchars($defaultClass) . '"' . $attrString . '>'
        . htmlspecialchars($value) .
        '</textarea>';
}
