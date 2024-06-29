<?php
namespace Controllers;

use Exception;
use Services\ModificationsService;
use Models\Modification;

class ModificationsController extends Controller
{
    private $service;

    // initialize services
    function __construct()
    {
        $this->service = new ModificationsService();
    }

    public function getAll()
    {
        $page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
        $limit = isset($_GET['limit']) ? (int) $_GET['limit'] : 10;

        try {
            $result = $this->service->getModifications($page, $limit);
            $modifications = $result['modifications'];
            $totalItems = $result['totalItems'];
        } catch (Exception $e) {
            $this->respondWithError(500, $e->getMessage());
            return;
        }

        if (empty($modifications)) {
            $this->respondWithError(404, "No modifications found");
            return;
        }

        $this->respond([
            'modifications' => $modifications,
            'totalItems' => $totalItems
        ]);
    }

    public function delete($modificationId)
    {
        try {
            $modification = $this->service->getModificationById($modificationId);

            if ($modification === null) {
                $this->respondWithError(404, 'Modification not found');
                return;
            }

            if (!$this->deleteFile($modification->imagePath)) {
                $this->respondWithError(500, 'Failed to delete modification image');
                return;
            }

            $this->service->deleteModification($modificationId);
        } catch (Exception $e) {
            $this->respondWithError(500, $e->getMessage());
            return;
        }

        $this->respond([
            'message' => 'Modification deleted successfully'
        ]);
    }

    public function update($modificationId)
    {
        $name = htmlspecialchars($_POST['modificationName']);
        $description = htmlspecialchars($_POST['modificationDescription']);
        $price = filter_var($_POST['estimatedPrice'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        $modificationCurret = $this->service->getModificationById($modificationId);
        $currentImagePath = $modificationCurret->imagePath;

        if (isset($_FILES['modificationImage']) && $_FILES['gunImage']['error'] === UPLOAD_ERR_OK) {
            $imagePath = $this->handleFileUpload('modificationImage', 'images/modifications');
            if ($imagePath === null) {
                $this->respondWithError(400, 'Invalid image file');
                return;
            }
            $this->deleteFile($currentImagePath);

        } else {
            $imagePath = $currentImagePath;
        }

        if ($imagePath === null) {
            $this->respondWithError(400, 'Invalid image file');
            return;
        }

        $modification = new Modification($modificationId, $name, $imagePath, $description, $price);

        try {
            $this->service->updateModification($modification);
        } catch (Exception $e) {
            $this->respondWithError(500, $e->getMessage());
            return;
        }

        $this->respond([
            'message' => 'Modification updated successfully'
        ]);
    }

    public function create()
    {
        $name = htmlspecialchars($_POST['modificationName']);
        $description = htmlspecialchars($_POST['modificationDescription']);
        $price = filter_var($_POST['estimatedPrice'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        $imagePath = $this->handleFileUpload('modificationImage', 'images/modifications');
        if ($imagePath === null) {
            $this->respondWithError(400, 'Invalid image file');
            return;
        }

        $modification = new Modification(0, $name, $imagePath, $description, $price);

        try {
            $this->service->addModification($modification);
        } catch (Exception $e) {
            $this->respondWithError(500, $e->getMessage());
            return;
        }

        $this->respond([
            'message' => 'Modification added successfully'
        ]);

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