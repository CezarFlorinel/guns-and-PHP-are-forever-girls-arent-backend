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
    public function getModifications(int $page, int $limit): array
    {
        $offset = ($page - 1) * $limit;
        $modifications = $this->repository->getModifications($offset, $limit);
        $totalItems = $this->repository->getTotalModificationsCount();

        foreach ($modifications as $modification) {
            $modification->imagePath = EncodeImage::encodeImageToBase64($modification->imagePath);
        }

        return [
            'modifications' => $modifications,
            'totalItems' => $totalItems
        ];
    }
}