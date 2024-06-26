<?php
namespace Repositories;

use PDO;
use PDOException;
use Models\Modification;

class ModificationsRepository extends Repository
{
    public function getModifications(int $offset, int $limit): array
    {
        try {
            $stmt = $this->connection->prepare('SELECT * FROM Modification LIMIT :limit OFFSET :offset');
            $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
            $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
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

    public function getTotalModificationsCount(): int
    {
        try {
            $stmt = $this->connection->prepare('SELECT COUNT(*) as count FROM Modification');
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return (int) $result['count'];
        } catch (PDOException $e) {
            echo $e;
            return 0;
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