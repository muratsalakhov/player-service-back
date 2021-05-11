<?php

namespace App\Services;

use Illuminate\Support\Facades\Redis;

class ProgramHandler
{

    public $programKey = "8c8c";
    public $programZip;
    public $images;
    public $programScript;

    // скачать zip архив по ссылке
    public function downloadProgramByUrl(string $url = 'http://127.0.0.1:84/zip/', string $programPath) {
        set_time_limit(0);
        $timestamp = microtime(true);

        $zipResource = fopen($programPath . 'zip.zip', "w");

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

        $this->unzipProgram($programPath);
        return microtime(true) - $timestamp;
    }

    // распаковать zip архив
    public function unzipProgram($programPath) {
        $zip = new \ZipArchive();
        $zip->open($programPath . 'zip.zip');

        for($i = 0; $i < $zip->numFiles; $i++) {
            $filename = $zip->getNameIndex($i);
            if (preg_match('/\.png$/', $filename)) {
                $zip->extractTo($programPath . "/images/", $filename);
            } else if (preg_match('/\.json$/', $filename)) {
                $this->programScript = $programPath . "/json/" . $filename;
                $zip->extractTo($programPath . "/json/", $filename);
            }
        }
        $zip->close();

        try {
            unlink($programPath . 'zip.zip');
        } catch (Throwable $e) {

        }

        return json_encode(array("status" => "ok"));
    }
};
