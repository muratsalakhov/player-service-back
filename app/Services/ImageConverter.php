<?php

namespace App\Services;

use WebPConvert\WebPConvert;

class ImageConverter {

    public static function startConvert($programPath) {
        self::programFrameConvert($programPath);
    }

    // сжатие изображений программы
    public static function programFrameConvert($programPath) {
        $timestamp = microtime(true);
        $program = json_decode(file_get_contents($programPath . 'json/Script.json'), true);
        $newProgram = [];

        foreach ($program['frames'] as $currentFrame) {
            $newProgram[$currentFrame['uid']]['pictureLink'] = $currentFrame['pictureLink'];
            foreach ($currentFrame['actions'] as $action) {
                $newProgram[$currentFrame['uid']]['nextFrames'][] = $action['nextFrame']['uid'];
                if ($action['nextFrame']['uid'] !== null) {
                    $newProgram[$action['nextFrame']['uid']]['prevFrames'][] = $currentFrame['uid'];
                }
            }
        }

        $pngPath = $programPath . "images-png/";
        foreach ($newProgram as $frame) {
            if (isset($frame['prevFrames']) && count($frame['prevFrames']) === 1) {
                self::frameConvert($pngPath . $newProgram[$frame['prevFrames'][0]]['pictureLink'],$pngPath . $frame['pictureLink']);
            }
        }

        return microtime(true) - $timestamp;
    }

    // попиксельное вычитание двух изображений
    public static function frameConvert($startImage, $finishImage) {
        $image1 = imagecreatefrompng($startImage);
        $image2 = imagecreatefrompng($finishImage);
        $width = imagesx($image1);
        $height = imagesy($image1);

        $image3 = imagecreatetruecolor($width, $height);
        $transparent = imagecolorallocatealpha($image3, 0, 0, 0, 127);
        imagefill($image3, 0, 0, $transparent);

        for($x = 0; $x < $width; $x++) {
            for($y = 0; $y < $height; $y++) {
                if (imagecolorat($image1, $x, $y) !== imagecolorat($image2, $x, $y)) {
                    $rgb = imagecolorsforindex($image2, imagecolorat($image2, $x, $y));

                    $pixelColor = imagecolorallocatealpha($image3, $rgb['red'], $rgb['green'], $rgb['blue'], $rgb['alpha']);
                    imagesetpixel($image3, $x, $y, $pixelColor);
                }
            }
        }
        imageAlphaBlending($image3, true);
        imageSaveAlpha($image3, true);
        imagepng($image3, $finishImage . '.png');
        self::convertToWebp($finishImage . '.png');
        unlink($finishImage . '.png');
    }


    // конвертация изображения в Webp
    public static function convertToWebp($imagePath) {
        $webpPath = preg_replace('/\/images-png\//', '/images-webp/', $imagePath);
        $webpPath = preg_replace('/\.png$/', '.webp', $webpPath);
        try {
            WebPConvert::convert($imagePath, $webpPath, [
                'png' => [
                    'alpha-quality' => 100
                ],
            ]);
        } catch (\Throwable $ex) {
            return json_encode(array("status" => $ex));
        }
    }
};
