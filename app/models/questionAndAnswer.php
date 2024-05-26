<?php
namespace Models;

class QuestionAndAnswer implements \JsonSerializable
{
    public int $questionAndAnswerId;
    public string $question;
    public string $answer;

    public function __construct($questionAndAnswerId, $question, $answer)
    {
        $this->questionAndAnswerId = $questionAndAnswerId;
        $this->question = $question;
        $this->answer = $answer;
    }

    public function jsonSerialize(): mixed
    {
        return get_object_vars($this);
    }

}