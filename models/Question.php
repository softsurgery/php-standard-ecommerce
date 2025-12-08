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

    // Ensure $details is exposed as an array
    protected function detailsAsArray(): array
    {
        // If already an array, return it
        if (is_array($this->details)) {
            return $this->details;
        }

        // If it's a JSON string, decode it
        if (is_string($this->details) && $this->details !== '') {
            $decoded = json_decode($this->details, true);
            if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                return $decoded;
            }
        }

        // Fallback: empty array
        return [];
    }

    // Helper for choices
    public function getChoices(): array
    {
        $details = $this->detailsAsArray();
        return $details['choices'] ?? [];
    }

    // Helper for slider min/max
    public function getSlider(): array
    {
        $details = $this->detailsAsArray();

        // allow either ["min"=>..., "max"=>...] or top-level min/max keys if you used that
        if (isset($details['slider']) && is_array($details['slider'])) {
            $slider = $details['slider'];
        } else {
            $slider = [
                'min' => $details['min'] ?? null,
                'max' => $details['max'] ?? null,
            ];
        }

        // Provide safe defaults
        $slider['min'] = isset($slider['min']) ? (int)$slider['min'] : 0;
        $slider['max'] = isset($slider['max']) ? (int)$slider['max'] : 100;

        return $slider;
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
