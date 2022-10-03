<?php

namespace App\Http\Controllers;

use App\Models\Datalog;
use App\Models\Log;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class LogController extends Controller
{
    public static function insertLog($ip, $services, $function, $ref, $agent, $hostname)
    {
        $log = new Log();
        $log->ip = $ip;
        $log->services = $services;
        $log->function = $function;
        $log->ref = $ref;
        $log->agent = $agent;
        $log->hostname = $hostname;
        $log->save();
    }

    public static function insertDatalog($jenis, $jenis_data, $ip, $services, $function, $soapUrl, $data)
    {
        $datalog = new Datalog();
        $datalog->jenis = $jenis;
        $datalog->jenis_data = $jenis_data;
        $datalog->ip = $ip;
        $datalog->services = $services;
        $datalog->function = $function;
        $datalog->soapurl = $soapUrl;
        $datalog->data = $data;
        $datalog->save();
    }

    public function nfs(Request $request)
    {
        $file = $request->file('theFile');
        $name = $request->file('theFile')->getClientOriginalName();
        $result = Storage::disk('sftp')->putFileAs('/nfs', $file, $name);

        return $result;

    }

}
