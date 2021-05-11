<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Storage;
use WebPConvert\WebPConvert;
use App\Services\ImageConverter;
use App\Services\ProgramHandler;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/init', function () {
    $string = file_get_contents(dirname(getcwd()) . '/storage/app/init_programs/propusk.json');
    //$json_a = json_decode($string, true);
    Redis::set("program:5f930b092ad7da32748cb8bc", $string);
    //Redis::bgsave();
    $result = Redis::get(1);
    echo $result;
});

Route::get('/test2/', function () {
    return ImageConverter::programFrameConvert('/home/muratsalakhov/PhpstormProjects/player-service/player-api/storage/app/init_programs/api-chapter-mongo-id-new-2.json');
});

Route::get('/zip/', function () {
    /*$zip = new ZipArchive;

    $fileName = 'myNewFile.zip';

    if ($zip->open('/home/muratsalakhov/PhpstormProjects/player-service/player-api/storage/app/public/test-img.zip', ZipArchive::CREATE) === TRUE)
    {
        $files = File::files(public_path('myFiles'));

        foreach ($files as $key => $value) {
            $relativeNameInZipFile = basename($value);
            $zip->addFile($value, $relativeNameInZipFile);
        }

        $zip->close();
    }
    */
    return response()->download('/home/muratsalakhov/PhpstormProjects/player-service/player-api/storage/app/public/test-img.zip');
});

Route::get('img/{name}', function ($name) {
    return response(Storage::get('/public/images/' . $name), 200)->header('Content-Type', 'image/png');
});

Route::get('/data/{name}', function ($name) {
    //$result = '{' . Redis::get(1) . '}';
    //$string = file_get_contents("/home/muratsalakhov/PhpstormProjects/player-service/player-api/storage/app/init_programs/api-chapter-mongo-id.json");
    //$string = file_get_contents("/home/muratsalakhov/PhpstormProjects/player-service/player-api/storage/app/public/test-img/" . $name);
    //Storage::put('public/images/image2.png', $string);
    $contents = Storage::get('/public/test-img/' . $name . '.png.webp');
    //$src = '/home/muratsalakhov/PhpstormProjects/player-service/player-api/storage/app/public/test-img/' . $name;
    //$src2 = '/home/muratsalakhov/PhpstormProjects/player-service/player-api/storage/app/public/test-img/' . $name . '.webp';

    //WebPConvert::convert($src, $src2, []);
    //Storage::put('/public/test-webp/', imageWebp(imageCreateFromPng($contents)));

    return response($contents, 200)->header('Content-Type', 'image/webp');
});
