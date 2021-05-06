<?php

namespace App\Services;

class ProgramHandler
{

    public $programKey = "8c8c";
    public $programZip;
    public $images;
    public $programScript;

    // скачать zip архив по ссылке
    public function downloadProgramByUrl(string $url = 'https://drive.google.com/u/0/uc?id=1o8j3VTQRefEJ1W0oZOQMCz3HHMW5gEIB&export=download') {
        set_time_limit(0);
        $timestamp = microtime(true);
        $zipFile = "wordpress2.zip"; // Rename .zip file

        $fp = fopen('/home/muratsalakhov/PhpstormProjects/player-service/player-api/storage/app/public/zip/wordpress3.zip', 'w');

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_FILE, $fp);

        $data = curl_exec($ch);

        curl_close($ch);
        fclose($fp);

        /*
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_HEADER, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 0);
        curl_setopt($ch, CURLOPT_URL, $url);
        $raw_file_data = curl_exec($ch);
        curl_close($ch);
        file_put_contents(storage_path() . '/app/public/zip/Desktop.zip', $raw_file_data);*/
        /*if ($this->programZip === false) {
            return array("status" => "Program download by url failed");
        } else {
            //$this->unzipProgram($this->programZip);
            return microtime(true) - $timestamp;
            return;
        }*/
        //$this->unzipProgram('/home/muratsalakhov/PhpstormProjects/player-service/player-api/storage/app/public/zip/Desktop.zip');
        return microtime(true) - $timestamp;
    }

    // распаковать zip архив
    public function unzipProgram($zipUrl = '/home/muratsalakhov/PhpstormProjects/player-service/player-api/storage/app/public/zip/Desktop.zip') {
        $timestamp = microtime(true);
        $zip = new \ZipArchive();
        $zip->open($zipUrl);

        for($i = 0; $i < $zip->numFiles; $i++) {
            $filename = $zip->getNameIndex($i);
            if (preg_match('/\.png$/', $filename)) {
                $zip->extractTo(storage_path() . "/app/public/zip/zip-images/", $filename);
                //WebpConverter::convert(array($filename));
            } else if (preg_match('/\.json$/', $filename)) {
                $zip->extractTo(storage_path() . "/app/public/zip/zip-json/", $filename);
            }
        }
        $zip->close();
        return json_encode(array("status" => "ok"));
    }
};
