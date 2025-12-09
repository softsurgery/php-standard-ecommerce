<?php

class Quiz
{
    private $id;
    private $name;
    private $description;

    // Constructor
    function __construct($id, $name, $description)
    {
        $this->id = $id;
        $this->name = $name;
        $this->description = $description;
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

    function getDescription()
    {
        return $this->description;
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

    function setDescription($description)
    {
        $this->description = $description;
    }


    function toArray() {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description
        ];
    }

}
?>