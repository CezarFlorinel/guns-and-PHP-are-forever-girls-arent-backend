<?php
namespace Services;

use Repositories\GunRepository;
use Utilities\EncodeImage;

class GunService
{

    private $repository;

    function __construct()
    {
        $this->repository = new GunRepository();
    }

    public function getGunsToDisplayInGunsPage(): array
    {
        $guns = $this->repository->getGunsToDisplayInGunsPage();

        foreach ($guns as $gun) {
            $gun->imagePath = EncodeImage::encodeImageToBase64($gun->imagePath);
        }

        foreach ($guns as $gun) {
            $gun->soundPath = EncodeImage::encodeAudioToBase64($gun->soundPath);
        }

        return $guns;
    }
}