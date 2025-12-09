<?php

session_start();

$_SESSION["email"] = null;
$_SESSION["authenticated"] = false;
$_SESSION['user_id'] = null;


header('Location: ../../views/frontoffice');
