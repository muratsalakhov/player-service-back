<?php

namespace App\Services;

class ProgramHandler
{

    public $programKey = "8c8c";
    public $programZip;
    public $images;
    public $programScript;

    public function downloadProgramByUrl(string $url) {

        $timestamp = microtime(true);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_URL, $url);
        //$this->programZip =
        curl_exec($ch);
        curl_close($ch);
        if ($this->programZip === false) {
            return array("status" => "Program download by url failed");
        } else {
            //$this->unzipProgram($this->programZip);
            return microtime(true) - $timestamp;
            return;
        }
    }

    public function unzipProgram($zipUrl = '/app/public/zip/test.zip') {
        $timestamp = microtime(true);
        $zip = new \ZipArchive();

        if ($zip->open(storage_path() . $zipUrl) != true) {
            return array("status" => "Program unzip operation failed");
        }

        for($i = 0; $i < $zip->numFiles; $i++) {
            $filename = $zip->getNameIndex($i);
            if (preg_match('/\.png$/', $filename)) {
                $zip->extractTo(storage_path() . "/app/public/zip-images/", $filename);
                //WebpConverter::convert(array($filename));
            } else if (preg_match('/\.json$/', $filename)) {
                $zip->extractTo(storage_path() . "/app/public/zip-json/", $filename);
            }
        }
        $this->programScript = "ok";
        $zip->close();
        return microtime(true) - $timestamp;
    }
};
