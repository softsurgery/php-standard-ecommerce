<?php
/**
 * Renders a simple label for a form input.
 *
 * @param string $for Input ID this label is for
 * @param string $text The label text
 * @param string $class Optional extra classes
 */
function renderLabel($for, $text, $class = '') {
    $defaultClass = "block mb-2.5 text-sm font-medium text-heading";
    if ($class) $defaultClass .= " " . $class;

    echo '<label for="'.htmlspecialchars($for).'" class="'.htmlspecialchars($defaultClass).'">'
         . htmlspecialchars($text) .
         '</label>';
}
?>
