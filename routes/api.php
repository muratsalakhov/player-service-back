<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Redis;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/player/program/{id}', function ($id) {
    //$string = file_get_contents("/home/muratsalakhov/Загрузки/propusk.json");
    //$json_a = json_decode($string, true);
    //Redis::set($id, $string);
    //Redis::bgsave();
    $result = Redis::get($id);
    echo json_decode($result, true);
    //return view('welcome');
});

Route::get('/program/{id}', function ($id) {
    $result = Redis::get($id);
    return response($result, 200)->header('Content-Type', 'application/json');
});

Route::get('/script/mongo/', function () {
    //$result = '{' . Redis::get(1) . '}';
    $string = file_get_contents("/home/muratsalakhov/PhpstormProjects/player-service/player-api/storage/app/init_programs/api-script-mongo.json");
    return response(json_decode($string, true), 200)->header('Content-Type', 'application/json');
});

Route::get('/chapter/mongo/', function () {
    //$result = '{' . Redis::get(1) . '}';
    $string = file_get_contents("/home/muratsalakhov/PhpstormProjects/player-service/player-api/storage/app/init_programs/api-chapter-mongo.json");
    return response(json_decode($string, true), 200)->header('Content-Type', 'application/json');
});

Route::get('/chapter/mongo/{id}', function () {
    //$result = '{' . Redis::get(1) . '}';
    $string = file_get_contents("/home/muratsalakhov/PhpstormProjects/player-service/player-api/storage/app/init_programs/api-chapter-mongo-id.json");
    return response(json_decode($string, true), 200)->header('Content-Type', 'application/json');
});

