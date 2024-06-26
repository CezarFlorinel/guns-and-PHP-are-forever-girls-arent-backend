<?php
namespace Services;

use Repositories\ModificationsRepository;
use Utilities\EncodeImage;

class ModificationsService
{

    private $repository;

    function __construct()
    {
        $this->repository = new ModificationsRepository();
    }

    public function getModifications()
    {
        $modifications = $this->repository->getModifications();
        foreach ($modifications as $modification) {
            $modification->imagePath = EncodeImage::encodeImageToBase64($modification->imagePath);
        }
        return $modifications;
    }
}