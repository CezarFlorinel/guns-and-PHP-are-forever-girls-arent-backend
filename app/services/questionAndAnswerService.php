<?php
namespace Services;

use Repositories\QandARepository;
use Models\QuestionAndAnswer;

class QuestionAndAnswerService
{
    private $repository;

    function __construct()
    {
        $this->repository = new QandARepository();
    }

    public function getAll(): array
    {
        return $this->repository->getQandAs();
    }

    public function addQandA(QuestionAndAnswer $questionAndAnswer)
    {
        return $this->repository->addQandA($questionAndAnswer);
    }

    public function deleteQandA(int $id): void
    {
        $this->repository->deleteQandA($id);
    }

}