<?php

class ProductCategory
{
    private $id;
    private $label;
    private $description;

    // Constructor
    function __construct($id, $label, $description)
    {
        $this->id = $id;
        $this->label = $label;
        $this->description = $description;
    }

    // Getters
    function getId()
    {
        return $this->id;
    }

    function getLabel()
    {
        return $this->label;
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

    function setLabel($label)
    {
        $this->label = $label;
    }

    function setDescription($description)
    {
        $this->description = $description;
    }


    function toArray() {
        return [
            'id' => $this->id,
            'label' => $this->label,
            'description' => $this->description
        ];
    }

}
?>