<?php

class QuizQuestion
{
    private $question_id;
    private $quiz_id;
    private $ordering;

    // Constructor
    function __construct($quiz_id,$question_id, $ordering)
    {
        $this->question_id = $question_id;
        $this->quiz_id = $quiz_id;
        $this->ordering = $ordering;
    }

    // Getters
    function getQuestionId()
    {
        return $this->question_id;
    }

    function getQuizId()
    {
        return $this->quiz_id;
    }

    function getOrdering()
    {
        return $this->ordering;
    }

    // Setters
    function setQuestionId($question_id)
    {
        $this->question_id = $question_id;
    }

    function setQuizId($quiz_id)
    {
        $this->quiz_id = $quiz_id;
    }

    function setOrdering($ordering)
    {
        $this->ordering = $ordering;
    }

    function toArray() {
        return [
            'question_id' => $this->question_id,
            'quiz_id' => $this->quiz_id,
            'ordering' => $this->ordering,
        ];
    }

}
?>