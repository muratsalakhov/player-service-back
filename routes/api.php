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

Route::get('/put-program/', function () {
    $script = file_get_contents("/home/muratsalakhov/PhpstormProjects/player-service/player-api/storage/app/init_programs/api-chapter-mongo-id-new-2.json");
    Redis::set("script:5fc13fcaaa303a46ead6365621", $script);
    return response(json_decode($script, true), 200)->header('Content-Type', 'application/json');
});

// Получить все сценарии из бд
Route::get('/player/script/', function () {
    $keys = Redis::keys("script:*");
    $keys = array_map(function ($k){
        return str_replace('laravel_database_', '', $k);
    }, $keys);
    $scripts = Redis::mget($keys);
    foreach ($scripts as $scriptId => $script) {
        $scripts[$scriptId] = json_decode($script);
    }
    return response($scripts, 200)->header('Content-Type', 'application/json');
});

// Получить сценарий по id
Route::get('/player/script/{id}', function ($id) {
    if (Redis::exists("script:" . $id)) {
        return response(json_decode(Redis::get("script:" . $id), true), 200)->header('Content-Type', 'application/json');
    } else {
        return response('{"status" : "Script not found"}', 404)->header('Content-Type', 'application/json');
    }
});

// Сохранения статистики прохождения
Route::post('/player/statistic/{id}', function (Request $request, $id) {
    return Redis::set("statistics:" . $id . time(), $request->getContent());
});

