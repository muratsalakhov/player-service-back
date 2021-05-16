<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;

class ScriptController extends Controller
{
    public function getAll() {
        $keys = Redis::keys("script:*");
        $keys = array_map(function ($k){
            return str_replace('laravel_database_', '', $k);
        }, $keys);
        $scripts = Redis::mget($keys);
        foreach ($scripts as $scriptId => $script) {
            $scripts[$scriptId] = json_decode($script);
        }
        return response($scripts, 200)->header('Content-Type', 'application/json');
    }

    public function getById($id) {
        if (Redis::exists("script:" . $id)) {
            return response(json_decode(Redis::get("script:" . $id), true), 200)->header('Content-Type', 'application/json');
        } else {
            return response('{"status" : "Script not found"}', 404)->header('Content-Type', 'application/json');
        }
    }
}
