<?php
namespace Services;

use Repositories\QandARepository;

class QuestionAndAnswerService
{
    private $repository;

    function __construct()
    {
        $this->repository = new QandARepository();
    }

    public function getAll()
    {
        return $this->repository->getQandAs();
    }

}