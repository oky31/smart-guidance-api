<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Attendance_Model extends Model
{
    //
    protected $table = 't_attendance';
    protected $info_attendance = array();
    protected $info_attendance_per_class = array();

    public function getAttendance($req)
    {
        $query_info_attendance =
            DB::table('t_schedule')
            ->select(
                'title_guidance',
                't_schedule.class_code',
                't_attendance.id_schedule',
                'date as date_held',
                'start_time',
                'end_time',
                't_schedule.created_at',
                DB::raw('count(IF (t_attendance.status = \'0\', 1, NULL)) \'will_attend\', 
                count(IF (t_attendance.status = \'1\', 1, NULL)) \'attend\',
                count(IF (t_attendance.status = \'2\', 1, NULL)) \'absent\'')
            )
            ->leftJoin($this->table, 't_attendance.id_schedule', '=', 't_schedule' . '.id')
            ->join('t_mtr_class', 't_mtr_class.class_code', '=', 't_schedule.class_code')
            ->where('t_schedule.status', '0')
            ->where('nik', $req)
            ->groupBy(
                't_schedule.class_code',
                't_attendance.id_schedule'
            )
            ->orderBy('title_guidance')
            ->get();
        //->dd();

        foreach ($query_info_attendance as $row) {
            # code...
            $this->info_attendance[] = array(
                'id_schedule'=>$row->id_schedule,
                'title_guidance' => $row->title_guidance,
                'date' => $row->date_held,
                'start_time' => $row->start_time,
                'end_time' => $row->end_time,
                'create_at' => $row->created_at,
                'will_attend' => $row->will_attend,
                'attend' => $row->attend,
                'absent' => $row->absent
            );
        }

        return $this->info_attendance;
    }


    public function getAttendanceperclass($req)
    {
        $query_info_attendance_per_class =
            DB::table($this->table)
            ->select(
                DB::raw('CONCAT(firstname ," ",middlename ," ",lastname) as fullname'),
                'theses_title',
                'join_date',
                't_attendance.id_schedule',
            )
            ->Join('t_schedule', 't_attendance.id_schedule', '=', 't_schedule.id')
            ->join('t_mtr_class', 't_mtr_class.class_code', '=', 't_schedule.class_code')
            ->join('t_mtr_students', 't_mtr_students.nim', '=', 't_attendance.nim')
            ->where('t_schedule.status', '0')
            ->where('nik', $req['nik'])
            ->where('t_schedule.class_code', $req['class_code'])
            ->where('t_schedule.title_guidance', $req['title_guidance'])
            ->orderBy('fullname')
            ->get();
        //->dd();

        foreach ($query_info_attendance_per_class as $row) {
            # code...
            $this->info_attendance_per_class[] = array(
                'fullname' => $row->fullname,
                'theses_title' => $row->theses_title,
                'join_date' => $row->join_date,
            );
        }

        return $this->info_attendance_per_class;
    }

    public function change_status($req)
    {
        $query_change_status =
            DB::table($this->table)            
            ->Join('t_schedule', 't_attendance.id_schedule', '=', 't_schedule.id')
            ->join('t_mtr_class', 't_mtr_class.class_code', '=', 't_schedule.class_code')
            ->join('t_mtr_students', 't_mtr_students.nim', '=', 't_attendance.nim')
            ->where('nim', $req['nim'])
            ->where('t_schedule.class_code', $req['class_code'])
            ->where('t_attendance.id_schedule', $req['id_schedule'])            
            ->update(['status' => $req['status']]);
        //->dd();

       

        return true;
    }
}
