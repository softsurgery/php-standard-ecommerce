<?php
session_start();
require_once __DIR__ . '/../../controllers/UserController.php';
$controller = new UserController();

$user = $controller->getByEmail($_POST['email']);
if ($user != null) {
    $encrypted = md5($_POST['password']);
    if ($encrypted != $user->getPassword()) {
        echo 'incorrect password';
    } else {
        $_SESSION['email'] = $user->getEmail();
        $_SESSION['authenticated'] = true;
        header('Location: ../../index.php');
    }
} else {
   echo  'account not found';
}