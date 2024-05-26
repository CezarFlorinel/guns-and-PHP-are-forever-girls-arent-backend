<?php
namespace Models;

class Modification implements \JsonSerializable
{
    public int $modificationId;
    public string $name;
    public string $imagePath;
    public string $description;
    public float $estimatedPrice;

    public function __construct($modificationId, $name, $imagePath, $description, $estimatedPrice)
    {
        $this->modificationId = $modificationId;
        $this->name = $name;
        $this->imagePath = $imagePath;
        $this->description = $description;
        $this->estimatedPrice = $estimatedPrice;
    }

    public function jsonSerialize(): mixed
    {
        return get_object_vars($this);
    }
}
