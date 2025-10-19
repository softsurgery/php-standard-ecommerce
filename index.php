<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>E-commerce</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
</head>

<body>
    <?php
    session_start();
    if (isset($_SESSION["email"]))
        echo $_SESSION["email"];
    else
        echo "you are not authenticated";
    ?>
    <div>
        <a class="hover:text-red-500" href="./views/handle-logout.php">Logout</a>
    </div>
</body>

</html>