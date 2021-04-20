<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Storage;
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

Route::get('/test', function () {
    //$string = file_get_contents(dirname(getcwd()) . '/storage/app/init_programs/propusk.json');
    //$json_a = json_decode($string, true);
    //Redis::set("program:5f930b092ad7da32748cb8bc", $string);
    //Redis::bgsave();
    //$result = Redis::get(1);
    //$programs = Redis::keys("program:*");
    return response(Redis::get("program:5f930b092ad7da32748cb8bc"), 200)->header('Content-Type', 'application/json');
    //echo json_decode(Redis::get("program:5f930b092ad7da32748cb8bc"), true);
    //echo print_r();
});

Route::get('data/{name}', function ($name) {
    //$result = '{' . Redis::get(1) . '}';
    //$string = file_get_contents("/home/muratsalakhov/PhpstormProjects/player-service/player-api/storage/app/init_programs/api-chapter-mongo-id.json");
    //$string = file_get_contents("/home/muratsalakhov/PhpstormProjects/player-service/player-api/storage/app/public/test-img/" . $name);
    //Storage::put('public/images/image2.png', $string);
    $contents = Storage::get('/public/test-img/' . $name);
    return response($contents, 200)->header('Content-Type', 'image/png');
});
