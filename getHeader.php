<?php
function getPageHead($title)
{
    $safeTitle = htmlspecialchars($title, ENT_QUOTES, 'UTF-8');
    return "
        <head>
            <meta charset='UTF-8'>
            <meta name='viewport' content='width=device-width, initial-scale=1.0'>
            <title>$safeTitle</title>
            <script src='https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4'></script>
            <link rel='stylesheet' href='../assets/css/global.css' type='text/css'>
        </head>";
}
?>
