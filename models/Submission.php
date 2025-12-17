<?php

class Submission
{
    private $quiz_id;
    private $user_id;
    private $score;
    private $answers;
    private $createdAt;

    // Constructor
    function __construct($quiz_id, $user_id, $score, $answers, $createdAt)
    {
        $this->quiz_id   = $quiz_id;
        $this->user_id   = $user_id;
        $this->score     = $score;
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

    function getScore()
    {
        return $this->score;
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

    function setScore($score)
    {
        $this->score = $score;
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
            'score'     => $this->score,
            'answers'   => json_decode($this->answers, true),
            'createdAt' => $this->createdAt
        ];
    }
}
