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

    public static function encodeAudioToBase64($audioPath): string
    {
        $fullAudioPath = '/app/public/assets' . $audioPath;

        if (!file_exists($fullAudioPath)) {
            throw new \Exception("File not found: " . $fullAudioPath);
        }

        $audioData = file_get_contents($fullAudioPath);
        return base64_encode($audioData);
    }
}