<?php
namespace Services;

use Repositories\ModificationsRepository;
use Utilities\Encode;
use Models\Modification;

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
            $modification->imagePath = Encode::encodeImageToBase64($modification->imagePath);
        }

        return [
            'modifications' => $modifications,
            'totalItems' => $totalItems
        ];
    }

    public function deleteModification(int $modificationId): void
    {
        $this->repository->deleteModification($modificationId);
    }

    public function addModification(Modification $modification)
    {
        return $this->repository->addModification($modification);
    }

    public function updateModification(Modification $modification)
    {
        $this->repository->updateModification($modification);
    }

    public function getModificationById(int $modificationId)
    {
        return $this->repository->getModificationById($modificationId);
    }
}