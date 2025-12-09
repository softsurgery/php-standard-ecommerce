<?php

/**
 * Renders a Tailwind-styled select input with options (no label).
 * Options must be an array of objects or associative arrays with `id` and `label`.
 *
 * @param string $name       ID and name of the select
 * @param array  $options    Array of objects/arrays: [ ['id'=>..., 'label'=>...], ... ]
 * @param mixed  $selected   The currently selected value (matches the `id`)
 * @param array  $attrs      Extra HTML attributes (key => value)
 * @param string $class      Extra Tailwind classes
 */
function renderSelect($name, $options = [], $selected = '', $attrs = [], $class = '')
{
    $defaultClass = "bg-gray-50 border border-gray-300 text-gray-900 text-sm 
                     rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5
                     dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 
                     dark:text-white";
    if ($class) $defaultClass .= " " . $class;

    // Build additional attributes
    $attrString = '';
    foreach ($attrs as $key => $val) {
        $attrString .= ' ' . htmlspecialchars($key) . '="' . htmlspecialchars($val) . '"';
    }

    echo '<select id="' . htmlspecialchars($name) . '" name="' . htmlspecialchars($name) . '" class="' . htmlspecialchars($defaultClass) . '"' . $attrString . '>';

    foreach ($options as $opt) {
        // Support both objects and associative arrays
        $value = isset($opt->id) ? $opt->id : ($opt['id'] ?? '');
        $label = isset($opt->label) ? $opt->label : ($opt['label'] ?? '');
        $isSelected = ($value == $selected) ? ' selected' : '';
        echo '<option value="' . htmlspecialchars($value) . '"' . $isSelected . '>' . htmlspecialchars($label) . '</option>';
    }

    echo '</select>';
}
