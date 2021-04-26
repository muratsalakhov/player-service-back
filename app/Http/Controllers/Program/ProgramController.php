<?php

namespace App\Http\Controllers\Program;

use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use App\Models\ProgramModel;
use Illuminate\Support\Facades\Redis;
use GuzzleHttp\Client;

class ProgramController extends Controller
{
    public function getProgram($programId, $userToken) {

        $client = new Client();
        $program = new ProgramModel();

        if (!empty(Redis::get($programId))) {
            $res = $client->request('POST', 'url_to_the_admin_api', [
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
            $res = $client->request('POST', 'url_to_the_admin_api', [
                'program_id' => $programId,
                'user_id' => $userToken,
                'program_exist' => false
            ]);
            $result= json_decode($res->getBody()->getContents());

            if (!empty($result->{'program_url'})) {
                $program->getProgramByUrl($result->{'program_url'});
                // unzip and return program
                Redis::set($programId, $program->getProgramScript);
                return response()->json(Redis::get($programId), 200);
            }
            return response()->json("", 404);
        }
    }
}
