<?php
namespace Controllers;

use Exception;
use Services\ModificationsService;

class ModificationsController extends Controller
{
    private $service;

    // initialize services
    function __construct()
    {
        $this->service = new ModificationsService();
    }

    public function getAll()
    {
        $page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
        $limit = isset($_GET['limit']) ? (int) $_GET['limit'] : 10;

        try {
            $result = $this->service->getModifications($page, $limit);
            $modifications = $result['modifications'];
            $totalItems = $result['totalItems'];
        } catch (Exception $e) {
            $this->respondWithError(500, $e->getMessage());
            return;
        }

        if (empty($modifications)) {
            $this->respondWithError(404, "No modifications found");
            return;
        }

        $this->respond([
            'modifications' => $modifications,
            'totalItems' => $totalItems
        ]);
    }
}