<?php

require_once '../controllers/UserController.php';
$controller = new UserController();
$user = new User(null, '', '', null, $_POST['email'] , md5($_POST['password']));
$controller->save($user);

header('Location: ../signin.php');