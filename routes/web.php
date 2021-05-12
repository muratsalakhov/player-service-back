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

// Выдача изображений
Route::get('/data/{id}/{name}', function ($id, $name) {
    $programPath = storage_path() . '/app/public/' . $id . '/';

    try {
        return response(file_get_contents($programPath . 'images-webp/' . $name . '.webp'), 200)->header('Content-Type', 'image/webp');
    } catch (Throwable $e) {}

    try {
        return response(file_get_contents($programPath . 'images-png/' . $name), 200)->header('Content-Type', 'image/png');
    } catch (Throwable $e) {}

    return response(array("status" => "File not found"), 404)->header('Content-Type', 'application/json');
});
