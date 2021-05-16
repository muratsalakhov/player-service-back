<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Throwable;

class ImageController extends Controller
{
    public function getImage($id, $name) {
        $programPath = storage_path() . '/app/public/' . $id . '/';
        try {
            return response(file_get_contents($programPath . 'images-webp/' . $name . '.webp'), 200)->header('Content-Type', 'image/webp');
        } catch (Throwable $e) {}

        try {
            return response(file_get_contents($programPath . 'images-png/' . $name), 200)->header('Content-Type', 'image/png');
        } catch (Throwable $e) {}

        return response(array("status" => "File not found"), 404)->header('Content-Type', 'application/json');
    }
}
