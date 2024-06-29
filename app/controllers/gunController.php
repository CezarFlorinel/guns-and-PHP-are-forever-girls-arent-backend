<?php
namespace Controllers;

use Exception;
use Services\GunService;
use Services\UserService;
use Models\Enumerations\TypeOfGuns;
use Models\Gun;

class GunController extends Controller
{
    private $service;
    private $userService;

    // initialize services
    function __construct()
    {
        $this->service = new GunService();
        $this->userService = new UserService();
    }

    #region getMethods
    public function getGunsToDisplayInGunsPage()
    {
        $page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
        $limit = isset($_GET['limit']) ? (int) $_GET['limit'] : 10;
        $searchTerm = isset($_GET['search']) ? $_GET['search'] : '';
        $type = isset($_GET['type']) ? $_GET['type'] : '';

        try {
            $result = $this->service->getGunsToDisplayInGunsPage($page, $limit, $searchTerm, $type);
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

    #endregion

    #region favouriteMethods
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

    #endregion

    public function createGun()
    {
        $userId = filter_var($_POST['userId'], FILTER_SANITIZE_NUMBER_INT);
        $gunName = htmlspecialchars($_POST['gunName']);
        $description = htmlspecialchars($_POST['gunDescription']);
        $year = filter_var($_POST['gunYear'], FILTER_SANITIZE_NUMBER_INT);
        $estimatedPrice = filter_var($_POST['estimatedPrice'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        $countryOfOrigin = htmlspecialchars($_POST['gunCountry']);
        $type = $_POST['gunType'];

        if (!$userId || !$gunName || !$description || !$year || !$estimatedPrice || !$countryOfOrigin || !$type) {
            $this->respondWithError(400, "Missing required fields");
            return;
        }

        $showInGunsPage = false;

        $user = $this->userService->getUserById($userId);

        if (!$user) {
            $this->respondWithError(404, "User not found");
            return;
        }

        if ($user->admin) {
            $showInGunsPage = true;
        }

        // Handle file uploads
        $imagePath = $this->handleFileUpload('gunImage', 'images/guns');
        $soundPath = $this->handleFileUpload('gunSound', 'sounds/weapons_sounds');

        if (!$imagePath || !$soundPath) {
            $this->respondWithError(400, "Invalid file type or file upload failed");
            return;
        }

        $gun = new Gun(
            0, // gunId will be set by the database
            $userId,
            $gunName,
            $description,
            $countryOfOrigin,
            $estimatedPrice,
            TypeOfGuns::tryFrom($type) ?? throw new \InvalidArgumentException("Invalid gun type"),
            $imagePath,
            $soundPath,
            $showInGunsPage,
            $year
        );

        try {
            $this->service->addGun($gun);
        } catch (Exception $e) {
            $this->respondWithError(500, $e->getMessage());
            return;
        }

        $this->respond("Gun added successfully");
    }

    public function updateGun($gunId)
    {
        // Check if data is coming as multipart/form-data
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST)) {
            $userId = filter_var($_POST['userId'], FILTER_SANITIZE_NUMBER_INT);
            $gunName = htmlspecialchars($_POST['gunName']);
            $description = htmlspecialchars($_POST['gunDescription']);
            $year = filter_var($_POST['gunYear'], FILTER_SANITIZE_NUMBER_INT);
            $estimatedPrice = filter_var($_POST['estimatedPrice'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
            $countryOfOrigin = htmlspecialchars($_POST['gunCountry']);
            $type = $_POST['gunType'];
            $showInGunsPage = false;

            if (!$userId || !$gunName || !$description || !$year || !$estimatedPrice || !$countryOfOrigin || !$type) {
                $this->respondWithError(400, "Missing required fields");
                return;
            }

            $user = $this->userService->getUserById($userId);

            if (!$user) {
                $this->respondWithError(404, "User not found");
                return;
            }

            if ($this->service->checkIfGunIsOwnedByUser($userId, $gunId) === false && !$user->admin) {
                $this->respondWithError(403, "You do not have permission to update this gun");
                return;
            }

            if ($user->admin) {
                $showInGunsPage = true;
            }

            if (isset($_FILES['gunImage']) && $_FILES['gunImage']['error'] === UPLOAD_ERR_OK) {
                $imagePath = $this->handleFileUpload('gunImage', 'images/guns');
                $currentImagePath = $this->service->getImagePathByGunId($gunId);
                if ($imagePath && $currentImagePath) {
                    $this->deleteFile($currentImagePath);
                }
            } else {
                $imagePath = $this->service->getImagePathByGunId($gunId);
            }

            if (isset($_FILES['gunSound']) && $_FILES['gunSound']['error'] === UPLOAD_ERR_OK) {
                $soundPath = $this->handleFileUpload('gunSound', 'sounds/weapons_sounds');
                $currentSoundPath = $this->service->getSoundPathByGunId($gunId);
                if ($soundPath && $currentSoundPath) {
                    $this->deleteFile($currentSoundPath);
                }
            } else {
                $soundPath = $this->service->getSoundPathByGunId($gunId);
            }

            if (!$imagePath || !$soundPath) {
                $this->respondWithError(400, "Invalid file type or file upload failed");
                return;
            }

            $gun = new Gun(
                $gunId,
                $userId,
                $gunName,
                $description,
                $countryOfOrigin,
                $estimatedPrice,
                TypeOfGuns::tryFrom($type) ?? throw new \InvalidArgumentException("Invalid gun type"),
                $imagePath,
                $soundPath,
                $showInGunsPage,
                $year
            );

            try {
                $this->service->updateGun($gun); // Ensure you have an updateGun method in your service and repository
                $this->respond("Gun updated successfully");
            } catch (Exception $e) {
                $this->respondWithError(500, $e->getMessage());
                return;
            }
        } else {
            $this->respondWithError(400, "Invalid request method or missing form data");
        }
    }


    public function deleteGun($gunId)
    {

        $gunId = filter_var($gunId, FILTER_SANITIZE_NUMBER_INT);

        if (!$gunId) {
            $this->respondWithError(400, "Invalid gun ID");
            return;
        }

        $imagePath = $this->service->getImagePathByGunId($gunId);
        $soundPath = $this->service->getSoundPathByGunId($gunId);

        if ($this->deleteFile($imagePath) && $this->deleteFile($soundPath)) {
            try {
                $this->service->deleteGun($gunId);
                $this->respond("Gun deleted successfully");
            } catch (Exception $e) {
                $this->respondWithError(500, $e->getMessage());
                return;
            }
        } else {
            $this->respondWithError(500, "Failed to delete gun");
        }

    }


    private function handleFileUpload($inputName, $directory)
    {
        if (isset($_FILES[$inputName])) {
            $file = $_FILES[$inputName];
            $projectRoot = realpath(__DIR__ . '/../../..');
            $uploadsDir = $projectRoot . '/app/public/assets/' . $directory;
            if (!file_exists($uploadsDir)) {
                mkdir($uploadsDir, 0777, true);
            }

            $allowedTypes = ['image/jpeg', 'image/png', 'image/jpg', 'image/webp', 'audio/mpeg', 'audio/mp3', 'audio/wav'];

            if ($file['error'] === UPLOAD_ERR_OK && in_array($file['type'], $allowedTypes)) {
                $uniqueSuffix = time() . '-' . rand(); // Ensuring unique filename
                $newFileName = $uniqueSuffix . '-' . basename($file['name']);
                $destination = $uploadsDir . '/' . $newFileName;

                if (move_uploaded_file($file['tmp_name'], $destination)) {
                    return "/$directory/$newFileName";
                } else {
                    return null; // Indicate failure
                }
            }
        }
        return null; // Indicate failure
    }

    private function deleteFile($filePath)
    {
        $projectRoot = realpath(__DIR__ . '/../../..');
        $fullPath = $projectRoot . '/app/public/assets/' . $filePath;
        if (file_exists($fullPath)) {
            return unlink($fullPath);
        }
        return false;
    }
}