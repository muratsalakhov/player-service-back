<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;

class StatisticController extends Controller
{
    public function addById(Request $request, $id)
    {
        $result = Redis::set("statistics:" . $id . ":" . time(), $request->getContent());
        if ($result === null) {
            return response($result, 503)->header('Content-Type', 'application/json');
        } else {
            return response($result, 200)->header('Content-Type', 'application/json');
        }
    }

    public function getById($id)
    {
        $keys = Redis::keys("statistics:" . $id . ":*");
        $keys = array_map(function ($k){
            return str_replace('laravel_database_', '', $k);
        }, $keys);
        $scripts = Redis::mget($keys);
        foreach ($scripts as $scriptId => $script) {
            $scripts[$scriptId] = json_decode($script);
        }
        return response($scripts, 200)->header('Content-Type', 'application/json');
    }
}
