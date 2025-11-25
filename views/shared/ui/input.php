<?php
/**
 * Renders a single Tailwind <input> with no label and no textarea support.
 *
 * @param string $type Input type (text, number, email, password, etc.)
 * @param string $name Name & ID of the input
 * @param string $value Default value
 * @param string $placeholder Placeholder
 * @param array  $attrs Additional HTML attributes (key => value)
 */
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

    // Build attributes into key="value"
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
        class="'.$defaultClass.'"'.$attrString.'
    >';
}
?>
