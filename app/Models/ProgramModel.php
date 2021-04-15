<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use phpDocumentor\Reflection\Types\Resource_;

class ProgramModel extends Model
{

    protected $primaryKey;
    protected $zip;
    protected $images;
    protected $programBody;

    public function getProgramByUrl(string $url) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_URL, $url);
        $this->zip = curl_exec($ch);
        curl_close($ch);
    }

    public function unzipProgram() {
        $zip = new ZipArchive;
        if ($zip->open('/storage/app/public/zip/test.zip') === TRUE) {
            $zip->extractTo('/storage/app/public/images', '*.png');
            $zip->extractTo('/storage/app/public/json', '*.json');
            $zip->close();
            echo 'готово';
        } else {
            echo 'ошибка';
        }
    }
};

unzipProgram();
