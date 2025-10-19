<!DOCTYPE html>
<html lang="en">

<?php
require_once 'getHeader.php';
echo getPageHead('E-commerce');
?>

<body>
    <?php
    session_start();
    if (isset($_SESSION["email"]))
        echo $_SESSION["email"];
    else
        echo "you are not authenticated";
    ?>
    <div class="bg-red-500">
        <a class="hover:text-red-500" href="./views/handle-logout.php">Logout</a>
    </div>
    <?php
    require_once 'getScripts.php';
    echo getScripts();
    ?>
</body>

</html>