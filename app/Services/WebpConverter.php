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
};
