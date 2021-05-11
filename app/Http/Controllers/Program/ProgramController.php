<?php

namespace App\Http\Controllers\Program;

use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use App\Models\ProgramModel;
use Illuminate\Support\Facades\Redis;
use GuzzleHttp\Client;
use App\Services;

class ProgramController extends Controller
{
    public function getProgram($programId, $userToken) {

        $httpClient = new Client();

        if (!empty(Redis::get($programId))) {
            $res = $httpClient->request('POST', 'url_to_the_admin_api', [
                'program_id' => $programId,
                'user_id' => $userToken,
                'program_exist' => true
            ]);
            $result= json_decode($res->getBody()->getContents());

            if ($result->{'permission'} === true) {
                return response()->json(Redis::get($programId), 200);
            } else {
                return response()->json('{"status" : "Permission denied"}', 403);
            }
        } else {
            $res = $httpClient->request('POST', 'url_to_the_admin_api', [
                'program_id' => $programId,
                'user_id' => $userToken,
                'program_exist' => false
            ]);
            $result= json_decode($res->getBody()->getContents());
            if (!empty($result->{'program_url'})) {
                $programPath = storage_path() . '/' . $programId . '/';
                Services\ProgramHandler::downloadProgramByUrl($result->{'program_url'}, $programPath);
                // unzip and return program
                $pathToScript = storage_path() . '/' . $programId . '/Script.json';
                Redis::set($programId, file_get_contents($pathToScript));
                return response()->json(Redis::get($programId), 200);
            }
            return response()->json("", 404);
        }
    }
}
