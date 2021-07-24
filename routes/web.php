<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Redis;
use App\Http\Controllers;
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

// Выдача изображений
Route::get('/data/{id}/{name}', [Controllers\ImageController::class, 'getImage']);
