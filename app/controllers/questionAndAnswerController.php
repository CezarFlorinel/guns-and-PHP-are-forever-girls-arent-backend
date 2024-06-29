<?php
namespace Controllers;

use Exception;
use Services\QuestionAndAnswerService;
use Models\QuestionAndAnswer;

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

    public function create()
    {

        $answer = $_POST['answer'];
        $question = $_POST['question'];

        $postedObject = new QuestionAndAnswer(0, $question, $answer);

        if (!$postedObject) {
            $this->respondWithError(400, "Invalid input");
            return;
        }

        $postedObject->question = htmlspecialchars($postedObject->question);
        $postedObject->answer = htmlspecialchars($postedObject->answer);

        if ($postedObject->question == '' || $postedObject->answer == '') {
            $this->respondWithError(400, "Please Fill All Required Fields");
            return;
        }

        try {
            $this->service->addQandA($postedObject);
        } catch (Exception $e) {
            $this->respondWithError(500, $e->getMessage());
        }

        $this->respond("QandA created successfully");

    }

    public function deleteQandA($id)
    {
        try {
            $this->service->deleteQandA($id);
        } catch (Exception $e) {
            $this->respondWithError(500, $e->getMessage());
        }

        $this->respond("QandA deleted successfully");
    }
}