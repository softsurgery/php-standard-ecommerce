<?php

class Question
{
    private $id;
    private $label;
    private $type;
    private $details;

    // Constructor
    function __construct($id, $label, $type, $details = [])
    {
        $this->id = $id;
        $this->label = $label;
        $this->type = $type;
        $this->details = $details;
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
    function getType()
    {
        return $this->type;
    }
    function getDetails()
    {
        return $this->details;
    }

    // Helper for choices
    function getChoices()
    {
        return $this->details['choices'] ?? [];
    }

    // Helper for slider min/max
    function getSlider()
    {
        return $this->details['slider'] ?? ['min' => 0, 'max' => 100];
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
    function setType($type)
    {
        $this->type = $type;
    }
    function setDetails($details)
    {
        $this->details = $details;
    }

    function toArray()
    {
        return [
            'id' => $this->id,
            'label' => $this->label,
            'type' => $this->type,
            'details' => $this->details
        ];
    }
}
