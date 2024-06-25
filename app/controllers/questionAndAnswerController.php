<?php
namespace Controllers;

use Exception;
use Services\QuestionAndAnswerService;

class QuestionAndAnswerController extends Controller
{
    private $service;

    // initialize services
    function __construct()
    {
        $this->service = new QuestionAndAnswerService();
    }

    public function getAll()
    {
        try {
            $QandAs = $this->service->getAll();
        } catch (Exception $e) {
            $this->respondWithError(500, $e->getMessage());
        }

        if (!$QandAs) {
            $this->respondWithError(404, "No QandAs found");
            return;
        }

        $this->respond($QandAs);
    }
}