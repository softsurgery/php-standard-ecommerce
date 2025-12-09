 <?php
    session_start();
    function redirectUnauthenticated($root)
    {
        if (isset($_SESSION["email"]))
            echo $_SESSION["email"];
        else
            header("Location: " . $root . "views/auth/signin.php");
    }
