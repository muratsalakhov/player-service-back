<?php

namespace App\Services;

class ProgramHandler
{

    protected $programKey = "8c8c";
    protected $programZip;
    protected $images;
    protected $programScript;

    public function getProgramByUrl(string $url) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_URL, $url);
        $this->programZip = curl_exec($ch);
        curl_close($ch);
        //$this->unzipProgram();
    }

    public static function unzipProgram() {
        $timestamp = time();
        $zip = new \ZipArchive();
        if ($zip->open(storage_path() . '/app/public/zip/test.zip') === TRUE) {
            for($i = 0; $i < $zip->numFiles; $i++) {
                $filename = $zip->getNameIndex($i);
                if ($filename == 'Script.json') {
                    //$this->programScript = $zip->getFromIndex($i);
                } else if (preg_match('/\.png$/', $filename)) {
                    $zip->extractTo(storage_path() . "/app/public/zip-images/", $filename);
                    WebpConverter::convert(array($filename));
                }
            }
            $zip->close();
            return $timestamp - time();
        } else {
            return 'ошибка';
        }
    }
};
