<?php
namespace Repositories;

use PDO;
use PDOException;
use Models\Gun;
use Models\Enumerations\TypeOfGuns;

class GunRepository extends Repository
{

    // -------------------------- get methods --------------------------

    public function getGunById(int $gunId)
    {
        try {
            $stmt = $this->connection->prepare('SELECT * FROM Guns WHERE gunId = :gunId'); // Corrected table name to 'Guns'
            $stmt->bindParam(':gunId', $gunId);
            $stmt->execute();
            $gun = $stmt->fetch(PDO::FETCH_ASSOC);

            // Handle the possibility of the gun not being found
            if (!$gun) {
                throw new \Exception("Gun not found with ID $gunId");
            }

            // Convert 'type' from the database to 'TypeOfGuns' enum
            $typeOfGun = TypeOfGuns::tryFrom($gun['type']) ?? throw new \InvalidArgumentException("Invalid gun type");

            return new Gun(
                $gun['gunId'],
                $gun['userId'],
                $gun['gunName'],
                $gun['gunDescription'],
                $gun['countryOfOrigin'],
                $gun['gunEstimatedPrice'],
                $typeOfGun, // Corrected type conversion
                $gun['gunImagePath'],
                $gun['soundPath'],
                $gun['showInGunsPage'],
                $gun['year']
            );
        } catch (PDOException $e) {
            echo $e;
        }
    }

    public function getGuns()
    {
        try {
            $stmt = $this->connection->prepare('SELECT * FROM Guns');
            $stmt->execute();
            $guns = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return array_map(function ($gun) {
                $typeOfGun = TypeOfGuns::tryFrom($gun['type']) ?? throw new \InvalidArgumentException("Invalid gun type");

                $gunData = new Gun(
                    $gun['gunId'],
                    $gun['userId'],
                    $gun['gunName'],
                    $gun['gunDescription'],
                    $gun['countryOfOrigin'],
                    $gun['gunEstimatedPrice'],
                    $typeOfGun,
                    $gun['gunImagePath'],
                    $gun['soundPath'],
                    $gun['showInGunsPage'],
                    $gun['year'] ?? 0
                );
                return $gunData;

            }, $guns);
        } catch (PDOException $e) {
            echo $e;
        }
    }

    #region getGunsToDisplayInGunsPage
    public function getGunsToDisplayInGunsPage(int $offset, int $limit, $searchTerm = '', $type = ''): array
    {
        try {
            $query = 'SELECT * FROM Guns WHERE showInGunsPage = 1';
            $params = [];

            if (!empty($searchTerm)) {
                $query .= ' AND gunName LIKE :searchTerm';
                $params[':searchTerm'] = '%' . $searchTerm . '%';
            }

            if (!empty($type)) {
                $query .= ' AND type = :type';
                $params[':type'] = $type;
            }

            $query .= ' LIMIT :limit OFFSET :offset';
            $stmt = $this->connection->prepare($query);
            $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
            $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);

            foreach ($params as $key => $value) {
                $stmt->bindValue($key, $value);
            }

            $stmt->execute();
            $guns = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return array_map(function ($gunData) {
                $typeOfGun = TypeOfGuns::tryFrom($gunData['type']) ?? throw new \InvalidArgumentException("Invalid gun type");
                return new Gun(
                    $gunData['gunId'],
                    $gunData['userId'],
                    $gunData['gunName'],
                    $gunData['gunDescription'],
                    $gunData['countryOfOrigin'],
                    $gunData['gunEstimatedPrice'],
                    $typeOfGun,
                    $gunData['gunImagePath'],
                    $gunData['soundPath'],
                    $gunData['showInGunsPage'],
                    $gunData['year'] ?? 0
                );
            }, $guns);
        } catch (PDOException $e) {
            echo $e;
            return [];
        }
    }

    public function getTotalGunsToDisplayInGunsPage($searchTerm = '', $type = ''): int
    {
        try {
            $query = 'SELECT COUNT(*) as count FROM Guns WHERE showInGunsPage = 1';
            $params = [];

            if (!empty($searchTerm)) {
                $query .= ' AND gunName LIKE :searchTerm';
                $params[':searchTerm'] = '%' . $searchTerm . '%';
            }

            if (!empty($type)) {
                $query .= ' AND type = :type';
                $params[':type'] = $type;
            }

            $stmt = $this->connection->prepare($query);

            foreach ($params as $key => $value) {
                $stmt->bindValue($key, $value);
            }

            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return (int) $result['count'];
        } catch (PDOException $e) {
            echo $e;
            return 0;
        }
    }

    #endregion

    #region getGunsToDisplayInFavouritesPage

    public function getFavouriteGunsByUserID(int $userId): array
    {
        $arrayIds = array();
        $guns = array();
        try {
            $stmt = $this->connection->prepare('SELECT * FROM Favourite WHERE userId = :userId');
            $stmt->bindParam(':userId', $userId);
            $stmt->execute();
            $favourites = $stmt->fetchAll(PDO::FETCH_ASSOC);
            foreach ($favourites as $favourite) {
                array_push($arrayIds, $favourite['gunId']);
            }

        } catch (PDOException $e) {
            echo $e;
        }

        if (count($arrayIds) == 0) {
            return $guns;
        }
        try {
            foreach ($arrayIds as $id) {
                $stmt = $this->connection->prepare('SELECT * FROM Guns WHERE gunId = :id');
                $stmt->bindParam(':id', $id);
                $stmt->execute();
                $gun = $stmt->fetch(PDO::FETCH_ASSOC);
                $typeOfGun = TypeOfGuns::tryFrom($gun['type']) ?? throw new \InvalidArgumentException("Invalid gun type");

                $gunData = new Gun(
                    $gun['gunId'],
                    $gun['userId'],
                    $gun['gunName'],
                    $gun['gunDescription'],
                    $gun['countryOfOrigin'],
                    $gun['gunEstimatedPrice'],
                    $typeOfGun,
                    $gun['gunImagePath'],
                    $gun['soundPath'],
                    $gun['showInGunsPage'],
                    $gun['year'] ?? 0
                );
                array_push($guns, $gunData);
            }
            return $guns;
        } catch (PDOException $e) {
            echo $e;
            return [];
        }
    }
    public function getIntArrayFavouriteGunsByUserId(int $userId)
    {
        try {
            $stmt = $this->connection->prepare('SELECT * FROM Favourite WHERE userId = :userId');
            $stmt->bindParam(':userId', $userId);
            $stmt->execute();
            $favourites = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return array_map(function ($favourite) {
                return $favourite['gunId'];
            }, $favourites);
        } catch (PDOException $e) {
            echo $e;
        }
    }

    public function getGunsOwnedByUser(int $userId)
    {
        try {
            $stmt = $this->connection->prepare('SELECT * FROM Guns WHERE userId = :userId');
            $stmt->bindParam(':userId', $userId);
            $stmt->execute();
            $guns = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return array_map(function ($gunData) {
                // Convert database data into Gun object here
                $typeOfGun = TypeOfGuns::tryFrom($gunData['type']) ?? throw new \InvalidArgumentException("Invalid gun type");
                $gun = new Gun(
                    $gunData['gunId'],
                    $gunData['userId'],
                    $gunData['gunName'],
                    $gunData['gunDescription'],
                    $gunData['countryOfOrigin'],
                    $gunData['gunEstimatedPrice'],
                    $typeOfGun, // Now correctly an instance of TypeOfGuns
                    $gunData['gunImagePath'],
                    $gunData['soundPath'],
                    $gunData['showInGunsPage'],
                    $gunData['year'] ?? 0
                );

                return $gun;
            }, $guns);
        } catch (PDOException $e) {
            echo $e;
        }
    }
    public function getIDsOfGunsOwnedByUser(int $userId)
    {
        try {
            $stmt = $this->connection->prepare('SELECT gunId FROM Guns WHERE userId = :userId');
            $stmt->bindParam(':userId', $userId);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_COLUMN);
        } catch (PDOException $e) {
            echo $e;
        }
    }

    #endregion

    public function getImagePathByGunId(int $gunId)
    {
        $stmt = $this->connection->prepare('SELECT gunImagePath FROM Guns WHERE gunId = :gunId');
        $stmt->bindParam(':gunId', $gunId);
        $stmt->execute();
        return $stmt->fetchColumn();
    }

    public function getSoundPathByGunId(int $gunId)
    {
        $stmt = $this->connection->prepare('SELECT soundPath FROM Guns WHERE gunId = :gunId');
        $stmt->bindParam(':gunId', $gunId);
        $stmt->execute();
        return $stmt->fetchColumn();
    }

    public function checkIfGunIsOwnedByUser(int $userId, int $gunId): bool
    {
        try {
            $stmt = $this->connection->prepare('SELECT * FROM Guns WHERE userId = :userId AND gunId = :gunId');
            $stmt->bindParam(':userId', $userId);
            $stmt->bindParam(':gunId', $gunId);
            $stmt->execute();
            return $stmt->rowCount() > 0;
        } catch (PDOException $e) {
            echo $e;
            return false;
        }
    }


    // -------------------------- delete methods --------------------------
    public function removeGunFromFavourites(int $userId, int $gunId): void
    {
        try {
            $stmt = $this->connection->prepare('DELETE FROM Favourite WHERE userId = :userId AND gunId = :gunId');
            $stmt->bindParam(':userId', $userId);
            $stmt->bindParam(':gunId', $gunId);
            $stmt->execute();
        } catch (PDOException $e) {
            echo $e;
        }
    }

    public function removeGunFromFavouritesByGunId(int $gunId): void
    {
        try {
            $stmt = $this->connection->prepare('DELETE FROM Favourite WHERE gunId = :gunId');
            $stmt->bindParam(':gunId', $gunId);
            $stmt->execute();
        } catch (PDOException $e) {
            echo $e;
        }
    }

    public function deleteGun(int $gunId)
    {
        try {
            $this->removeGunFromFavouritesByGunId($gunId);

            $stmt = $this->connection->prepare('DELETE FROM Guns WHERE gunId = :gunId');
            $stmt->bindParam(':gunId', $gunId);
            $stmt->execute();
            return;

        } catch (PDOException $e) {
            echo $e;
        }
        return true;
    }


    #region filterGunsByTypeInGunsPage

    public function filterGunsByTypeInGunsPage($type, $isGunPage)
    {
        try {

            if ($isGunPage) {
                $stmt = $this->connection->prepare('SELECT * FROM Guns WHERE type = :type AND showInGunsPage = 1');

            } else {
                $stmt = $this->connection->prepare('SELECT * FROM Guns WHERE type = :type');
            }
            $stmt->bindParam(':type', $type);
            $stmt->execute();
            $guns = $stmt->fetchAll(PDO::FETCH_ASSOC);

            return array_map(function ($gunData) {
                // Convert database data into Gun object here
                $typeOfGun = TypeOfGuns::tryFrom($gunData['type']) ?? throw new \InvalidArgumentException("Invalid gun type");
                $gun = new Gun(
                    $gunData['gunId'],
                    $gunData['userId'],
                    $gunData['gunName'],
                    $gunData['gunDescription'],
                    $gunData['countryOfOrigin'],
                    $gunData['gunEstimatedPrice'],
                    $typeOfGun, // Now correctly an instance of TypeOfGuns
                    $gunData['gunImagePath'],
                    $gunData['soundPath'],
                    $gunData['showInGunsPage'],
                    $gunData['year'] ?? 0
                );

                return $gun;
            }, $guns);
        } catch (PDOException $e) {
            echo $e;
        }
    }

    public function searchGunsByNameInGunsPage($searchTerm, $isGunPage)
    {
        try {
            $searchTerm = "%" . $searchTerm . "%";

            if ($isGunPage) {
                $stmt = $this->connection->prepare('SELECT * FROM Guns WHERE gunName LIKE :searchTerm AND showInGunsPage = 1');
            } else {
                $stmt = $this->connection->prepare('SELECT * FROM Guns WHERE gunName LIKE :searchTerm');
            }
            $stmt->bindParam(':searchTerm', $searchTerm);
            $stmt->execute();
            $guns = $stmt->fetchAll(PDO::FETCH_ASSOC);

            return array_map(function ($gunData) {
                // Convert database data into Gun object here
                $typeOfGun = TypeOfGuns::tryFrom($gunData['type']) ?? throw new \InvalidArgumentException("Invalid gun type");
                $gun = new Gun(
                    $gunData['gunId'],
                    $gunData['userId'],
                    $gunData['gunName'],
                    $gunData['gunDescription'],
                    $gunData['countryOfOrigin'],
                    $gunData['gunEstimatedPrice'],
                    $typeOfGun, // Now correctly an instance of TypeOfGuns
                    $gunData['gunImagePath'],
                    $gunData['soundPath'],
                    $gunData['showInGunsPage'],
                    $gunData['year'] ?? 0
                );

                return $gun;
            }, $guns);
        } catch (PDOException $e) {
            echo $e;
        }
    }


    #endregion

    #region addMethods
    public function addGun(Gun $gun)
    {
        try {
            $query = 'INSERT INTO Guns (userId, gunName, gunDescription, countryOfOrigin, year, gunEstimatedPrice, type, gunImagePath, soundPath, showInGunsPage) VALUES (:userId, :gunName, :gunDescription, :countryOfOrigin, :year, :gunEstimatedPrice, :type, :gunImagePath, :soundPath, :showInGunsPage)';

            $stmt = $this->connection->prepare($query);
            $typeOfGuns = (string) $gun->typeOfGun->value;

            // Bind parameters
            $stmt->bindParam(':userId', $gun->userId);
            $stmt->bindParam(':gunName', $gun->gunName);
            $stmt->bindParam(':gunDescription', $gun->description);
            $stmt->bindParam(':countryOfOrigin', $gun->countryOfOrigin);
            $stmt->bindParam(':gunEstimatedPrice', $gun->estimatedPrice);
            $stmt->bindParam(':year', $gun->year);
            $stmt->bindParam(':type', $typeOfGuns); // might need to be converted, might give error
            $stmt->bindParam(':gunImagePath', $gun->imagePath);
            $stmt->bindParam(':soundPath', $gun->soundPath);
            $stmt->bindParam(':showInGunsPage', $gun->showInGunsPage, PDO::PARAM_BOOL);

            $stmt->execute();

            $gun->gunId = $this->connection->lastInsertId();

            return $gun;
        } catch (PDOException $e) {
            echo $e;

        }
    }

    public function addGunToFavourites(int $userId, int $gunId): void
    {
        try {
            $stmt = $this->connection->prepare('INSERT INTO Favourite (userId, gunId) VALUES (:userId, :gunId)');
            $stmt->bindParam(':userId', $userId);
            $stmt->bindParam(':gunId', $gunId);
            $stmt->execute();
        } catch (PDOException $e) {
            echo $e;
        }
    }

    #endregion

    // -------------------------- update methods --------------------------

    public function updateGun(Gun $gun)
    {
        try {
            $query = 'UPDATE Guns SET 
                gunName = :gunName, 
                gunDescription = :gunDescription, 
                countryOfOrigin = :countryOfOrigin, 
                year = :year,
                gunEstimatedPrice = :gunEstimatedPrice, 
                type = :type, 
                gunImagePath = :gunImagePath, 
                soundPath = :soundPath, 
                showInGunsPage = :showInGunsPage 
              WHERE gunId = :gunId';

            $stmt = $this->connection->prepare($query);

            $typeOfGuns = (string) $gun->typeOfGun->value;

            $stmt->bindParam(':gunId', $gun->gunId);
            $stmt->bindParam(':gunName', $gun->gunName);
            $stmt->bindParam(':gunDescription', $gun->description);
            $stmt->bindParam(':countryOfOrigin', $gun->countryOfOrigin);
            $stmt->bindParam(':year', $gun->year);
            $stmt->bindParam(':gunEstimatedPrice', $gun->estimatedPrice);
            $stmt->bindParam(':type', $typeOfGuns);
            $stmt->bindParam(':gunImagePath', $gun->imagePath);
            $stmt->bindParam(':soundPath', $gun->soundPath);
            $stmt->bindParam(':showInGunsPage', $gun->showInGunsPage, PDO::PARAM_BOOL);

            $stmt->execute();

            return $gun;

        } catch (PDOException $e) {
            echo $e;
        }
    }










}