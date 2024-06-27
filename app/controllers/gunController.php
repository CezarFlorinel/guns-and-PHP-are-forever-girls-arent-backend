<?php
namespace Controllers;

use Exception;
use Services\GunService;

class GunController extends Controller
{
    private $service;

    // initialize services
    function __construct()
    {
        $this->service = new GunService();
    }

    public function getGunsToDisplayInGunsPage()
    {
        try {
            $guns = $this->service->getGunsToDisplayInGunsPage();
            $this->respond($guns);
        } catch (Exception $e) {
            $this->respondWithError(500, $e->getMessage());
            return;
        }

    }
}