<?php
namespace Services;

use Repositories\GunRepository;
use Utilities\Encode;

class GunService
{

    private $repository;

    function __construct()
    {
        $this->repository = new GunRepository();
    }

    public function getGunsToDisplayInGunsPage($page, int $limit, $searchTerm = '', $type = ''): array
    {
        $offset = ($page - 1) * $limit;
        $guns = $this->repository->getGunsToDisplayInGunsPage($offset, $limit, $searchTerm, $type);
        $totalItems = $this->repository->getTotalGunsToDisplayInGunsPage($searchTerm, $type);

        foreach ($guns as $gun) {
            $gun->imagePath = Encode::encodeImageToBase64($gun->imagePath);
        }

        foreach ($guns as $gun) {
            $gun->soundPath = Encode::encodeAudioToBase64($gun->soundPath);
        }

        return [
            'guns' => $guns,
            'totalItems' => $totalItems
        ];
    }

    public function getGunById($id)
    {
        $gun = $this->repository->getGunById($id);
        $gun->imagePath = Encode::encodeImageToBase64($gun->imagePath);
        $gun->soundPath = Encode::encodeAudioToBase64($gun->soundPath);
        return $gun;
    }

    public function getFavouriteGunsByUserID(int $userId): array
    {
        $guns = $this->repository->getFavouriteGunsByUserID($userId);
        foreach ($guns as $gun) {
            $gun->imagePath = Encode::encodeImageToBase64($gun->imagePath);
        }

        foreach ($guns as $gun) {
            $gun->soundPath = Encode::encodeAudioToBase64($gun->soundPath);
        }

        return $guns;
    }



    public function getIdsOfFavouriteGuns($userId): array
    {
        return $this->repository->getIntArrayFavouriteGunsByUserId($userId);
    }

    public function addGunToFavourites($userId, $gunId)
    {
        $this->repository->addGunToFavourites($userId, $gunId);
    }

    public function removeGunFromFavourites($userId, $gunId)
    {
        $this->repository->removeGunFromFavourites($userId, $gunId);
    }

    public function getGunsOwnedByUser($userId)
    {
        $guns = $this->repository->getGunsOwnedByUser($userId);
        foreach ($guns as $gun) {
            $gun->imagePath = Encode::encodeImageToBase64($gun->imagePath);
        }

        foreach ($guns as $gun) {
            $gun->soundPath = Encode::encodeAudioToBase64($gun->soundPath);
        }

        return $guns;
    }




}