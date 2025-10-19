<?php

class User
{
    private $id;
    private $name;
    private $surname;
    private $birthdate;
    private $email;
    private $password;

    // Constructor
    function __construct($id, $name, $surname, $birthdate, $email, $password)
    {
        $this->id = $id;
        $this->name = $name;
        $this->surname = $surname;
        $this->birthdate = $birthdate;
        $this->email = $email;
        $this->password = $password;
    }

    // Getters
    function getId()
    {
        return $this->id;
    }

    function getName()
    {
        return $this->name;
    }

    function getSurname()
    {
        return $this->surname;
    }

    function getBirthdate()
    {
        return $this->birthdate;
    }

    function getEmail()
    {
        return $this->email;
    }

    function getPassword()
    {
        return $this->password;
    }

    // Setters
    function setId($id)
    {
        $this->id = $id;
    }

    function setName($name)
    {
        $this->name = $name;
    }

    function setSurname($surname)
    {
        $this->surname = $surname;
    }

    function setBirthdate($birthdate)
    {
        $this->birthdate = $birthdate;
    }

    function setEmail($email)
    {
        $this->email = $email;
    }

    function setPassword($password)
    {
        $this->password = $password;
    }
}
?>
