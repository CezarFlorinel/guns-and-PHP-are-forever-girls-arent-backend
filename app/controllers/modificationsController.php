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
        try {
            $modifications = $this->service->getModifications();
        } catch (Exception $e) {
            $this->respondWithError(500, $e->getMessage());
        }

        if (!$modifications) {
            $this->respondWithError(404, "No modifications found");
            return;
        }

        $this->respond($modifications);
    }
}