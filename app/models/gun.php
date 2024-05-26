<?php
namespace Models;

use Models\Enumerations\TypeOfGuns;

class Gun implements \JsonSerializable
{
    public int $gunId;
    public int $userId; // foreign key
    public string $gunName;
    public string $description;
    public string $countryOfOrigin;
    public float $estimatedPrice;
    public TypeOfGuns $typeOfGun;
    public string $imagePath;
    public string $soundPath;
    public bool $showInGunsPage;
    public int $year;

    public function __construct($gunId, $userId, $gunName, $description, $countryOfOrigin, $estimatedPrice, $typeOfGun, $imagePath, $soundPath, $showInGunsPage, $year)
    {
        $this->gunId = $gunId;
        $this->userId = $userId;
        $this->gunName = $gunName;
        $this->description = $description;
        $this->countryOfOrigin = $countryOfOrigin;
        $this->estimatedPrice = $estimatedPrice;
        $this->typeOfGun = $typeOfGun;
        $this->imagePath = $imagePath;
        $this->soundPath = $soundPath;
        $this->showInGunsPage = $showInGunsPage;
        $this->year = $year;
    }

    public function jsonSerialize(): mixed
    {
        return get_object_vars($this);
    }

}
