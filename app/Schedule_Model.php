<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Schedule_Model extends Model
{
    //
    protected $table_name = "t_schedule";

    public function getSchedule($req)
    {
        $data_req = array(
            'class_code' => $req['class_code'],
            'nik' => $req['identity_number']
        );

        $query_getSchedule =
            DB::table($this->table_name)
            ->select(
                '*'
            )
            ->join('t_mtr_class', 't_mtr_class.class_code', '=', $this->table_name . '.class_code')
            ->where($this->table_name . '.status', '0')
            ->where($this->table_name . '.class_code', $data_req['class_code'])
            ->where('nik', $data_req['nik'])->get();

        return $query_getSchedule;
    }

    public function save_data($req)
    {
        $data_req = array(
            'date' => $req['date'],
            'start_time' => $req['start_time'],
            'end_time' => $req['end_time'],
            'place' => $req['place'],
            'class_code' => $req['class_code'],
            'title_guidance' => $req['title_guidance'],
            'created_at' => $req['registered_at']
        );

        try {
            DB::table($this->table_name)
                ->insert($data_req);
            DB::commit();
            return 0;
        } catch (\Exception $e) {
            DB::rollBack();
            throw ($e);
            return 1;
        }
    }

    public function update_data($req)
    {
        $data_req = array(
            'date' => $req['date_reschedule'],
            'start_time' => $req['start_time_reschedule'],
            'end_time' => $req['end_time_reschedule'],
            'place' => $req['place'],
            'class_code' => $req['class_code'],
            'title_guidance' => $req['title_guidance'],
            'created_at' => $req['registered_at']
        );

        //Validation

        //Check Parameters from database


        //Get Database
        $query_get_info_class = DB::table($this->table_name)
            ->select(
                '*'
            )
            ->where('class_code', $data_req['class_code'])
            ->where('date', $data_req['date'])
            ->where('start_time', $data_req['start_time'])
            ->where('end_time', $data_req['end_time'])
            ->where('place', $data_req['place'])
            ->where('title_guidance', $data_req['title_guidance'])
            ->where('created_at', $data_req['registered_at'])
            ->first();

        //Calculate Start data before 
        $getStartTime = $query_get_info_class->start_time;
        $getEndTime   = $query_get_info_class->end_time;
        $getDate     = $query_get_info_class->date;

        //Validasi jika ubah waktu 2 jam sebelum jam bimbingan //false

        //Validasi jika ubah waktu selesai pada saat jam bimbingan //False

        try {
            DB::table($this->table_name)                
                ->where('class_code', $data_req['class_code'])
                ->update($data_req);
            DB::commit();
            return 0;
        } catch (\Exception $e) {
            DB::rollBack();
            throw ($e);
            return 1;
        }
    }
}
