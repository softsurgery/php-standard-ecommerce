<?php

session_start();

$_SESSION["email"] = null;
$_SESSION["authenticated"] = false;

header('Location: ../../index.php');