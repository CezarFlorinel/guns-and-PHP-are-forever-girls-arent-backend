<?php
namespace Utilities;

class EncodeImage
{
    public static function encodeImageToBase64($imagePath): string
    {
        $fullImagePath = '/app/public/assets' . $imagePath;
        $imageData = file_get_contents($fullImagePath);
        return base64_encode($imageData);
    }
}