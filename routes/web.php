<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Storage;
use WebPConvert\WebPConvert;
use App\Services\WebpConverter;
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
    return WebpConverter::programFrameConvert('/home/muratsalakhov/PhpstormProjects/player-service/player-api/storage/app/init_programs/api-chapter-mongo-id-new-2.json');
});

Route::get('/test/', function () {
    return WebpConverter::frameConvert();
    //return file_get_contents('/home/muratsalakhov/PhpstormProjects/player-service/player-api/storage/app/public/zip/test.zip');
    //$json_a = json_decode($string, true);
    //Redis::set("program:5f930b092ad7da32748cb8bc", $string);
    //Redis::bgsave();
    //$result = Redis::get(1);
    //$programs = Redis::keys("program:*");
    //return WebpConverter::convert(array("image1.png", "image2.png"));

    //$src = '/home/muratsalakhov/PhpstormProjects/player-service/player-api/storage/app/public/test-img/5d5c8548-2b00-4136-a0c7-4345ca6a7204.png';
    //$src2 = '/home/muratsalakhov/PhpstormProjects/player-service/player-api/storage/app/public/test-img/5d5c8548-2b00-4136-a0c7-4345ca6a7204.webp';


    //WebPConvert::convert($src, $src2, []);

    //$img = imageCreateFromPng($src);
    //imageWebp($img, $info['dirname'] . '/' . $info['filename'] . '.' . 'webp', 100);
    //imagedestroy($img);
    //return ProgramHandler::unzipProgram(); //response(Redis::get("program:5f930b092ad7da32748cb8bc"), 200)->header('Content-Type', 'application/json');
    //echo json_decode(Redis::get("program:5f930b092ad7da32748cb8bc"), true);
    //echo print_r();
});

/*Route::get('data/{name}', function ($name) {
    //$result = '{' . Redis::get(1) . '}';
    //$string = file_get_contents("/home/muratsalakhov/PhpstormProjects/player-service/player-api/storage/app/init_programs/api-chapter-mongo-id.json");
    //$string = file_get_contents("/home/muratsalakhov/PhpstormProjects/player-service/player-api/storage/app/public/test-img/" . $name);
    //Storage::put('public/images/image2.png', $string);
    $contents = Storage::get('/public/test-img/' . $name);
    return response($contents, 200)->header('Content-Type', 'image/webp');
});*/

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
