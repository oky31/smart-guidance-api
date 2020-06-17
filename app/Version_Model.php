<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Version_Model extends Model
{
    public function checkDevicesVersion($data)
    {
        $res =  DB::table('t_mtr_version')
            ->select('allow')
            ->where('allow_sdk_version', $data->current_version)
            ->where('api_key', $data->api_key)
            ->get()->first();
        if (empty($res)) {
            return false;
        } else {
            return $res;
        }
    }
}
