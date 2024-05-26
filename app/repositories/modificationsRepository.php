<?php
namespace Repositories;

use PDO;
use PDOException;
use Models\Modification;

class ModificationsRepository extends Repository
{
    public function getModifications(): array
    {
        try {
            $stmt = $this->connection->prepare('SELECT * FROM Modification');
            $stmt->execute();
            $modifications = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return array_map(function ($modification) {
                return new Modification(
                    $modification['modificationId'],
                    $modification['modificationName'],
                    $modification['modificationImagePath'],
                    $modification['modificationDescription'],
                    $modification['modificationEstimatedPrice']
                );
            }, $modifications);
        } catch (PDOException $e) {
            echo $e;
            return [];
        }
    }

    public function deleteModification(int $modificationId): void
    {
        try {
            $stmt = $this->connection->prepare('DELETE FROM Modification WHERE modificationId = :modificationId');
            $stmt->bindParam(':modificationId', $modificationId);
            $stmt->execute();
        } catch (PDOException $e) {
            echo $e;
        }
    }

    public function addModification(Modification $modification)
    {
        try {
            $stmt = $this->connection->prepare('INSERT INTO Modification (modificationName, modificationImagePath, modificationDescription, modificationEstimatedPrice) VALUES (:name, :imagePath, :description, :estimatedPrice)');
            $stmt->bindParam(':name', $modification->name);
            $stmt->bindParam(':imagePath', $modification->imagePath);
            $stmt->bindParam(':description', $modification->description);
            $stmt->bindParam(':estimatedPrice', $modification->estimatedPrice);
            $stmt->execute();

            $modification->id = $this->connection->lastInsertId();
            return $modification;

        } catch (PDOException $e) {
            echo $e;
        }
    }

    public function updateModification(Modification $modification): void  // without the image path
    {
        try {
            $stmt = $this->connection->prepare('UPDATE Modification SET modificationName = :name, modificationDescription = :description, modificationEstimatedPrice = :estimatedPrice WHERE modificationId = :modificationId');
            $stmt->bindParam(':modificationId', $modification->modificationId);
            $stmt->bindParam(':name', $modification->name);
            $stmt->bindParam(':description', $modification->description);
            $stmt->bindParam(':estimatedPrice', $modification->estimatedPrice);
            $stmt->execute();
        } catch (PDOException $e) {
            echo $e;
        }
    }

}