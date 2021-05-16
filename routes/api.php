<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Redis;
use App\Http\Controllers;
use App\Services;

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

// ТЕСТ. Скачать zip
Route::get('/test/', function () {
    return Services\ProgramHandler::downloadProgramByUrl('http://127.0.0.1:84/zip/', storage_path() . '/app/public/5fc13fcaaa303a46ead63656/');
});

// ТЕСТ. Загрузить программу
Route::get('/put-program/', function () {
    $script = file_get_contents("/home/muratsalakhov/PhpstormProjects/player-service/player-api/storage/app/init_programs/api-chapter-mongo-id-new-2.json");
    Redis::set("script:5fc13fcaaa303a46ead63656", $script);
    return response(json_decode($script, true), 200)->header('Content-Type', 'application/json');
});

// Конвертировать изображения по переданному пути
Route::put('/convert/', function (Request $request) {
    $requestBody = json_decode($request->getContent(), true);
    Services\ImageConverter::startConvert($requestBody['programPath']);
    return response(array("status" => "ok"), 200)->header('Content-Type', 'application/json');
});

// Получить все сценарии из бд
Route::get('/player/script/', [Controllers\ScriptController::class, 'getAll']);

// Получить сценарий по id
Route::get('/player/script/{id}', [Controllers\ScriptController::class, 'getById']);

// Сохранения статистики прохождения
Route::post('/player/statistic/{id}', [Controllers\StatisticController::class, 'addById']);

// Получение статистики прохождения по id программы
Route::get('/player/statistic/{id}', [Controllers\StatisticController::class, 'getById']);

