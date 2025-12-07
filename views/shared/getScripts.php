<?php
function getScripts($root = '')
{
    return "
        <script src='https://unpkg.com/lucide@latest'></script>
        <script>
            lucide.createIcons();
        </script>
        <script src='https://cdn.jsdelivr.net/npm/flowbite@3.1.2/dist/flowbite.min.js'></script>
        <script src='$root/assets/js/quiz-dnd.js'></script>
    ";
}
