<?php

class Submission
{
    private $quiz_id;
    private $user_id;
    private $answers;
    private $createdAt;

    // Constructor
    function __construct($quiz_id, $user_id, $answers, $createdAt)
    {
        $this->quiz_id   = $quiz_id;
        $this->user_id   = $user_id;
        $this->answers   = $answers;
        $this->createdAt = $createdAt;
    }

    // Getters
    function getQuizId()
    {
        return $this->quiz_id;
    }

    function getUserId()
    {
        return $this->user_id;
    }

    function getAnswers()
    {
        return $this->answers;
    }

    function getCreatedAt()
    {
        return $this->createdAt;
    }

    // Setters
    function setQuizId($quiz_id)
    {
        $this->quiz_id = $quiz_id;
    }

    function setUserId($user_id)
    {
        $this->user_id = $user_id;
    }

    function setAnswers($answers)
    {
        $this->answers = $answers;
    }

    function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;
    }

    function toArray()
    {
        return [
            'quiz_id'   => $this->quiz_id,
            'user_id'   => $this->user_id,
            'answers'   => json_decode($this->answers, true),
            'createdAt' => $this->createdAt
        ];
    }
}
