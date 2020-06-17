<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Auth_Model extends Model
{
    //
    public function checkAccount($req)
    {
        $query_check_account = DB::table('t_account')
            ->select('*')
            ->where('t_account.email', $req)
            ->get()->count();
        if ($query_check_account > 0) {
            return 1;
        } else {
            return 0;
        }
    }

    public function checkPassword($req)
    {
        $reqData = array(
            'email' => $req->email,
            'password' => $req->password
        );
        $query_check_password = DB::table('t_account')
            ->select('password')
            ->where('t_account.email', $reqData['email'])
            ->get()->first();
        if ($query_check_password->password == $reqData['password']) {
            return true;
        } else {
            return false;
        }
    }

    public function AccountDetails($req)
    {
        $reqData = array(
            'email' => $req['email'],
            'type' => $req['type']
        );
        if ($reqData['type'] == 'lecturer') {
            $query_account_details = DB::table('t_account')
                ->select(
                    DB::raw('CONCAT(firstname, " ", middlename ," " ,lastname) as fullname'),
                    'nik',
                    'faculty_name',
                    'major_name'
                )
                ->join('t_mtr_lecturer', 't_account.identity_number', '=', 't_mtr_lecturer.nik')
                ->join('t_mtr_faculty', 't_mtr_lecturer.faculty_code', '=', 't_mtr_faculty.id')
                ->join('t_mtr_major', 't_mtr_lecturer.major_code', '=', 't_mtr_major.id')
                ->where('t_account.email', $reqData['email']);
            if ($query_account_details->count() == 0) {
                return 1;
            } else {
                $result_query_account_details = $query_account_details->first();
                $res = array(
                    'fullname' => $result_query_account_details->fullname,
                    'nik' => $result_query_account_details->nik,
                    'faculty_name' => $result_query_account_details->faculty_name,
                    'major_name' => $result_query_account_details->major_name,
                    'type' => 'lecturer'
                );
                return $res;
            }
        } elseif ($req['type'] == 'students') {
            $query_account_details = DB::table('t_account')
                ->select(
                    DB::raw('CONCAT(firstname, " ", middlename ," " ,lastname) as fullname'),
                    'nim',
                    'faculty_name',
                    'major_name',
                    'theses_title'
                )
                ->join('t_mtr_students', 't_account.identity_number', '=', 't_mtr_students.nim')
                ->join('t_mtr_faculty', 't_mtr_students.faculty_code', '=', 't_mtr_faculty.id')
                ->join('t_mtr_major', 't_mtr_students.major_code', '=', 't_mtr_major.id')
                ->where('t_account.email', $reqData['email']);

            if ($query_account_details->count() == 0) {
                return 2;
            } else {
                $result_query_account_details = $query_account_details->first();
                $res = array(
                    'fullname' => $result_query_account_details->fullname,
                    'nim' => $result_query_account_details->nim,
                    'faculty_name' => $result_query_account_details->faculty_name,
                    'major_name' => $result_query_account_details->major_name,
                    'type' => 'students'
                );
                return $res;
            }
        } else {
            return 1;
        }
    }
}
