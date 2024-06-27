<?php
namespace Controllers;

use Exception;
use Services\GunService;
use Models\Enumerations\TypeOfGuns;

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
        $page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
        $limit = isset($_GET['limit']) ? (int) $_GET['limit'] : 10;
        try {

            $result = $this->service->getGunsToDisplayInGunsPage($page, $limit);
            $guns = $result['guns'];
            $totalItems = $result['totalItems'];
        } catch (Exception $e) {
            $this->respondWithError(500, $e->getMessage());
            return;
        }

        if (empty($guns)) {
            $this->respondWithError(404, "No guns found");
            return;
        }

        $this->respond([
            'guns' => $guns,
            'totalItems' => $totalItems
        ]);
    }

    public function getFavouriteGunsByUserID($userId)
    {
        try {
            $guns = $this->service->getFavouriteGunsByUserID($userId);
        } catch (Exception $e) {
            $this->respondWithError(500, $e->getMessage());
            return;
        }

        $this->respond($guns);
    }

    public function getIdsOfFavouriteGuns($userId)
    {
        try {
            $ids = $this->service->getIdsOfFavouriteGuns($userId);
        } catch (Exception $e) {
            $this->respondWithError(500, $e->getMessage());
            return;
        }

        $this->respond($ids);
    }

    public function addGunToFavourites($userId, $gunId)
    {
        try {
            $this->service->addGunToFavourites($userId, $gunId);
        } catch (Exception $e) {
            $this->respondWithError(500, $e->getMessage());
            return;
        }

        $this->respond("Gun added to favourites");
    }

    public function removeGunFromFavourites($userId, $gunId)
    {
        try {
            $this->service->removeGunFromFavourites($userId, $gunId);
        } catch (Exception $e) {
            $this->respondWithError(500, $e->getMessage());
            return;
        }

        $this->respond("Gun removed from favourites");
    }

    public function getGunById($id)
    {
        try {
            $gun = $this->service->getGunById($id);
        } catch (Exception $e) {
            $this->respondWithError(500, $e->getMessage());
            return;
        }

        $this->respond($gun);
    }

    public function getGunsOwnedByUser($userId)
    {
        try {
            $guns = $this->service->getGunsOwnedByUser($userId);
        } catch (Exception $e) {
            $this->respondWithError(500, $e->getMessage());
            return;
        }

        $this->respond($guns);
    }

    public function getTypesOfGuns()
    {
        $types = TypeOfGuns::cases();
        $typeArray = [];
        foreach ($types as $type) {
            $typeArray[] = $type->value;
        }

        $this->respond($typeArray);
    }
}