<?php
namespace Repositories;

use PDO;
use PDOException;
use Models\QuestionAndAnswer;

class QandARepository extends Repository
{
    public function addQandA(QuestionAndAnswer $questionAndAnswer)
    {
        try {
            $stmt = $this->connection->prepare("INSERT INTO QuestionAndAnswer (question, answer) VALUES (:question, :answer)");
            $stmt->bindParam(':question', $questionAndAnswer->question);
            $stmt->bindParam(':answer', $questionAndAnswer->answer);
            $stmt->execute();

            $questionAndAnswer->questionAndAnswerId = $this->connection->lastInsertId();
            return $questionAndAnswer;

        } catch (PDOException $e) {
            echo $e;
            return null;
        }
    }

    public function getQandAs(): array
    {
        try {
            $stmt = $this->connection->prepare("SELECT * FROM QuestionAndAnswer");
            $stmt->execute();
            $QandAs = $stmt->fetchAll(PDO::FETCH_ASSOC);

            return array_map(function ($element) {
                return new QuestionAndAnswer(
                    $element['infoId'],
                    $element['question'],
                    $element['answer']
                );
            }, $QandAs);
        } catch (PDOException $e) {
            echo $e;
            return [];
        }
    }

    public function deleteQandA(int $id): void
    {
        try {
            $stmt = $this->connection->prepare("DELETE FROM QuestionAndAnswer WHERE infoId = :id");
            $stmt->bindParam(':id', $id);
            $stmt->execute();
        } catch (PDOException $e) {
            echo $e;
        }
    }

}