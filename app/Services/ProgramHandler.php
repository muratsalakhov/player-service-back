<?php

namespace App\Services;

class ProgramHandler
{

    public $programKey = "8c8c";
    public $programZip;
    public $images;
    public $programScript;

    // скачать zip архив по ссылке
    public function downloadProgramByUrl(string $url = 'http://127.0.0.1:84/zip/') {
        set_time_limit(0);
        $timestamp = microtime(true);

        $zipFile = "/home/muratsalakhov/PhpstormProjects/player-service/player-api/storage/app/public/zip/test.zip"; // Rename .zip file
        $zipResource = fopen($zipFile, "w");

        // Get The Zip File From Server
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_FAILONERROR, true);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_AUTOREFERER, true);
        curl_setopt($ch, CURLOPT_BINARYTRANSFER,true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, false);
        curl_setopt($ch, CURLOPT_TIMEOUT,10);
        curl_setopt($ch, CURLOPT_FILE, $zipResource);

        $page = curl_exec($ch);

        $this->unzipProgram($zipFile);
        return microtime(true) - $timestamp;
    }

    // распаковать zip архив
    public function unzipProgram($zipUrl) {
        $timestamp = microtime(true);
        $zip = new \ZipArchive();
        $zip->open($zipUrl);

        for($i = 0; $i < $zip->numFiles; $i++) {
            $filename = $zip->getNameIndex($i);
            if (preg_match('/\.png$/', $filename)) {
                $zip->extractTo(storage_path() . "/app/public/zip/zip-images/", $filename);
                //WebpConverter::convert(array($filename));
            } else if (preg_match('/\.json$/', $filename)) {
                $this->programScript = storage_path() . "/app/public/zip/zip-json/" . $filename;
                $zip->extractTo(storage_path() . "/app/public/zip/zip-json/", $filename);
            }
        }
        $zip->close();

        ImageConverter::programFrameConvert($this->programScript);

        return json_encode(array("status" => "ok"));
    }
};
