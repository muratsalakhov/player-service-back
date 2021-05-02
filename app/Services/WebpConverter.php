<?php

namespace App\Services;

use WebPConvert\WebPConvert;

class WebpConverter {

    public static function convertByName(array $images) {
        $storageLink = storage_path() . "/app/public/zip-images/";
        foreach ($images as $image) {
            try {
                WebPConvert::convert($storageLink . $image, $storageLink . $image . ".webp", []);
            } catch (e $ex) {
                return json_encode(array("status" => $ex));
            }
        }
    }

    public static function frameConvert() {
        $timestamp = microtime(true);
        $image1 = imagecreatefrompng('/home/muratsalakhov/PhpstormProjects/player-service/player-api/storage/app/public/webp-images/image1.png');
        $image2 = imagecreatefrompng('/home/muratsalakhov/PhpstormProjects/player-service/player-api/storage/app/public/webp-images/image2.png');
        $width = imagesx($image1);
        $height = imagesy($image1);

        $image3 = imagecreatetruecolor($width, $height);
        $transparent = imagecolorallocatealpha($image3, 0, 0, 0, 127);
        imagefill($image3, 0, 0, $transparent);

        //$alpha = imagecolorallocatealpha($image3, 255, 255, 255, 127);
        for($x = 0; $x < $width; $x++) {
            for($y = 0; $y < $height; $y++) {
                // pixel color at (x, y)
                if (imagecolorat($image1, $x, $y) !== imagecolorat($image2, $x, $y)) {
                    $rgb = imagecolorsforindex($image2, imagecolorat($image2, $x, $y));

                    $pixelColor = imagecolorallocatealpha($image3, $rgb['red'], $rgb['green'], $rgb['blue'], $rgb['alpha']);
                    imagesetpixel($image3, $x, $y, $pixelColor);
                }
            }
        }
        // Restore Alpha
        imageAlphaBlending($image3, true);
        imageSaveAlpha($image3, true);
        imagepng($image3, '/home/muratsalakhov/PhpstormProjects/player-service/player-api/storage/app/public/webp-images/image3.png');
        return microtime(true) - $timestamp;
    }
};
