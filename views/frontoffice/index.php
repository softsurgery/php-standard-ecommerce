<!DOCTYPE html>
<html lang="en">

<?php
require_once __DIR__ . '/../shared/getHeader.php';
require_once __DIR__ . '/../shared/redirectUnauthenticated.php';
echo getPageHead('E-commerce', './');
?>

<body>
    <?php
    redirectUnauthenticated('../../');
    ?>
    <div>
        <a href=" ./quiz-list.php">Quiz List</a> <br>
        <a class="hover:text-red-500" href="../auth/handle-logout.php">Logout</a>
    </div>
    <?php
    require_once __DIR__ . '/../shared/getScripts.php';
    echo getScripts();
    ?>
</body>

</html>