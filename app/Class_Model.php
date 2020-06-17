<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Class_Model extends Model
{
    //
    protected $table_name = "t_mtr_class";

    public function getClass($identity_number)
    {
        $query_getClass =
            DB::table($this->table_name)
            ->select(
                'class_code',
                'created_at',
            )
            ->where('status', '0')
            ->where('nik', $identity_number)->get();
        return $query_getClass;
    }

    public function save_data($req)
    {
        $req = array(
            'nik' => $req->identity_number,
            'class_code' => $req->class_code,
            'activation_code' => $req->activation_code,
            'status' => '0',
            'registered_at' => $req->registered_at
        );
        DB::table($this->table_name)
            ->insert($req);
        return 0;
    }

    public function delete_data($req)
    {
        $req = array(
            'nik' => $req->identity_number,
            'class_code' => $req->class_code,
            'activation_code' => $req->activation_code,
            'registered_at' => $req->registered_at
        );

        DB::table($this->table_name)
            ->where('nik', $req['nik'])
            ->where('class_code', $req['class_code'])
            ->where('activation_code', $req['activation_code'])
            ->where('registered_at', $req['registered_at'])
            ->update(['status_otp_int' => '1']);
        return 0;
    }
}
