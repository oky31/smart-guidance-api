<?php

namespace App;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;

class Register_Model extends Model
{
  //
  public function save_data($req)
  {
    try {

      $req_students_data = array(
        'nim' => $req->identity_number,
        'firstname' => $req->firstname,
        'middlename' => $req->middlename,
        'lastname' => $req->lastname,
        'faculty_code' => $req->faculty,
        'major_code' => $req->major,
        'theses_title' => $req->title_theses,
        'phone_number' => $req->phone_number
      );

      $req_lecturer_data = array(
        'nik' => $req->identity_number,
        'firstname' => $req->firstname,
        'middlename' => $req->middlename,
        'lastname' => $req->lastname,
        'faculty_code' => $req->faculty,
        'major_code' => $req->major,
        'phone_number' => $req->phone_number
      );

      $req_account_data = array(
        'email' => $req->email,
        'password' => Hash::make($req->password, [
          'rounds' => 10
        ]),
        'identity_number' => $req->identity_number,
      );

      $req_trx_login_data = array(
        'email' => $req->email,
        'otp_code' => substr(rand(), 0, 6),
        'status_otp' => '0',
        'status_account' => '0'
      );

      //Check Data if exists in lecturer
      $query_check_data_in_lecturer =
        DB::table('t_account')
        ->join('t_mtr_lecturer', 't_account.identity_number', '=', 't_mtr_lecturer.nik')
        ->select(
          'email',
          'nik as identity_number',
          'firstname',
          'middlename',
          'lastname',
          'faculty_code',
          'major_code'
        )
        ->Where('t_account.email', $req_account_data['email'])
        ->Where('nik', $req_lecturer_data['nik'])
        ->where('firstname', $req_lecturer_data['firstname'])
        ->where('middlename', $req_lecturer_data['middlename'])
        ->where('lastname', $req_lecturer_data['lastname'])
        ->where('faculty_code', $req_lecturer_data['faculty_code'])
        ->where('major_code', $req_lecturer_data['major_code']);

      //Check Data if exists in students
      $query_check_data_in_students =
        DB::table('t_account')
        ->join('t_mtr_students', 't_account.identity_number', '=', 't_mtr_students.nim')
        ->select(
          'email',
          'nim as identity_number',
          'firstname',
          'middlename',
          'lastname',
          'faculty_code',
          'major_code'
        )
        ->Where('t_account.email', $req_account_data['email'])
        ->Where('nim', $req_students_data['nim'])
        ->where('firstname', $req_students_data['firstname'])
        ->where('middlename', $req_students_data['middlename'])
        ->where('lastname', $req_students_data['lastname'])
        ->where('faculty_code', $req_students_data['faculty_code'])
        ->where('major_code', $req_students_data['major_code']);

      $query_check_all_data =
        $query_check_data_in_lecturer
        ->unionAll($query_check_data_in_students)
        ->count();

      if ($query_check_all_data > 0) {
        return 1;
      } else {
        //Check email if exist
        $query_check_email =
          DB::table('t_account')
          ->select(
            'email'
          )
          ->Where('t_account.email', $req_account_data['email'])
          ->count();

        if ($query_check_email > 0) {
          return array('code' => 4, 'messages' => 'email has been used');
        }

        //Check identity number if exist
        $query_check_identity_number =
          DB::table('t_account')
          ->select(
            'identity_number'
          )
          ->Where('t_account.identity_number', $req->identity_number)
          ->count();
        if ($query_check_identity_number > 0) {
          return array('code' => 4, 'messages' => 'nim or nik has been used');
        }

        if ($req->type == 'students') {
          try {
            DB::beginTransaction();
            DB::table('t_mtr_students')->insert($req_students_data);
            DB::table('t_account')->insert($req_account_data);
            DB::table('t_trx_login')->insert($req_trx_login_data);
            DB::commit();
            return array(
              'code' => 0,
              'data' => array('otp_code' => $req_trx_login_data['otp_code'])
            );
          } catch (\Exception $e) {
            DB::rollback();
            Log::warning(sprintf('Exception: %s', $e->getMessage()));
            throw ($e);
          }
        } else if ($req->type == 'lecturer') {
          try {
            // die('o');
            DB::beginTransaction();
            DB::table('t_mtr_lecturer')->insert($req_lecturer_data);
            DB::table('t_account')->insert($req_account_data);
            DB::table('t_trx_login')->insert($req_trx_login_data);
            DB::commit();

            // 

            return array(
              'code' => 0,
              'data' => array('otp_code' => $req_trx_login_data['otp_code'])
            );
          } catch (\Exception $e) {
            DB::rollback();
            Log::warning(sprintf('Exception: %s', $e->getMessage()));
            throw ($e);
          }
        } else if ($req->type != 'lecturer' || $req->type != 'students') {
          // 

          return 2;
        } else {

          return 3;
        }
      }
    } catch (\Exception $e) {
      Log::warning(sprintf('Exception: %s', $e->getMessage()));
      throw ($e);
    }
  }



  public function validate_otp($req)
  {
    if ($req->type == 'students') {
      try {
        $req_validate_data = array(
          'nim' => $req->identity_number,
          'firstname' => $req->firstname,
          'middlename' => $req->middlename,
          'lastname' => $req->lastname,
          'email' => $req->email,
          'faculty_code' => $req->faculty,
          'major_code' => $req->major,
          'otp_code' => $req->otp_code
        );

        //Check Status Code
        $res =  DB::table('t_trx_login')
          ->join('t_account', 't_trx_login.email', '=', 't_account.email')
          ->join('t_mtr_students', 't_account.nim', '=', 't_mtr_students.students_nim')
          ->select('status_otp')
          ->where('t_trx_login.email', $req_validate_data['email'])
          ->where('nim', $req_validate_data['nim'])
          ->where('firstname', $req_validate_data['firstname'])
          ->where('middlename', $req_validate_data['middlename'])
          ->where('lastname', $req_validate_data['lastname'])
          ->where('faculty_code', $req_validate_data['faculty_code'])
          ->where('major_code', $req_validate_data['major_code'])
          ->where('otp_code', $req_validate_data['otp_code'])
          ->get()->first();

        if ($res->status_otp_int == "1") {
          return false;
        } else {
          try {
            DB::table('t_trx_login')
              ->where('email', $req_validate_data['email'])
              ->where('otp_code', $req_validate_data['otp_code'])
              ->update(['status_otp_int' => '1']);
            return true;
          } catch (\Exception $e) {
            Log::warning(sprintf('Exception: %s', $e->getMessage()));
            throw ($e);
          }
        }
      } catch (\Exception $e) {
        Log::warning(sprintf('Exception: %s', $e->getMessage()));
        throw ($e);
      }
    } elseif ($req->type == 'lecturer') {
      try {
        $req_validate_data = array(
          'nik' => $req->identity_number,
          'firstname' => $req->firstname,
          'middlename' => $req->middlename,
          'lastname' => $req->lastname,
          'email' => $req->email,
          'faculty_code' => $req->faculty,
          'major_code' => $req->major,
          'otp_code' => $req->otp_code
        );

        //Check Status Code
        $res =  DB::table('t_trx_login')
          ->join('t_account', 't_trx_login.email', '=', 't_account.email')
          ->join('t_mtr_lecturer', 't_account.identity_number', '=', 't_mtr_lecturer.nik')
          ->select('status_otp')
          ->where('t_trx_login.email', $req_validate_data['email'])
          ->where('nik', $req_validate_data['nik'])
          ->where('firstname', $req_validate_data['firstname'])
          ->where('middlename', $req_validate_data['middlename'])
          ->where('lastname', $req_validate_data['lastname'])
          ->where('faculty_code', $req_validate_data['faculty_code'])
          ->where('major_code', $req_validate_data['major_code'])
          ->where('otp_code', $req_validate_data['otp_code'])
          ->get()->first();
        //
        if ($res->status_otp_int == "1") {
          return false;
        } else {
          try {
            DB::table('t_trx_login')
              ->where('email', $req_validate_data['email'])
              ->where('otp_code', $req_validate_data['otp_code'])
              ->update(['status_otp_int' => '1']);
            return true;
          } catch (\Exception $e) {
            DB::rollback();
            Log::warning(sprintf('Exception: %s', $e->getMessage()));
            throw ($e);
          }
        }
      } catch (\Exception $e) {
        Log::warning(sprintf('Exception: %s', $e->getMessage()));
        throw ($e);
      }
    }
  }

  public function request_otp($req)
  {
    if ($req->type == 'students') {
      try {

        $req_validate_data = array(
          'nim' => $req->identity_number,
          'firstname' => $req->firstname,
          'middlename' => $req->middlename,
          'lastname' => $req->lastname,
          'email' => $req->email,
          'faculty_code' => $req->faculty,
          'major_code' => $req->major
        );

        //Check Status Code
        $res =  DB::table('t_trx_login')
          ->join('t_account', 't_trx_login.email', '=', 't_account.email')
          ->join('t_mtr_students', 't_account.identity_number', '=', 't_mtr_students.nim')
          ->select('status_otp')
          ->where('t_trx_login.email', $req_validate_data['email'])
          ->where('nim', $req_validate_data['nim'])
          ->where('firstname', $req_validate_data['firstname'])
          ->where('middlename', $req_validate_data['middlename'])
          ->where('lastname', $req_validate_data['lastname'])
          ->where('faculty_code', $req_validate_data['faculty_code'])
          ->where('major_code', $req_validate_data['major_code'])
          ->where('otp_code', $req_validate_data['otp_code'])
          ->get()->first();

        if ($res->status_otp_int == "1") {
          return false;
        } else {
          try {
            DB::table('t_trx_login')
              ->where('email', $req_validate_data['email'])
              ->where('otp_code', $req_validate_data['otp_code'])
              ->update(['status_otp_int' => '1']);
            return true;
          } catch (\Exception $e) {
            DB::rollback();
            Log::warning(sprintf('Exception: %s', $e->getMessage()));
            throw ($e);
          }
        }
      } catch (\Exception $e) {
        Log::warning(sprintf('Exception: %s', $e->getMessage()));
        throw ($e);
      }
    } elseif ($req->type == 'lecturer') {
      try {

        $req_validate_data = array(
          'nik' => $req->identity_number,
          'firstname' => $req->firstname,
          'middlename' => $req->middlename,
          'lastname' => $req->lastname,
          'email' => $req->email,
          'faculty_code' => $req->faculty,
          'major_code' => $req->major
        );

        //Check Status Code
        $res =  DB::table('t_trx_login')
          ->join('t_account', 't_trx_login.email', '=', 't_account.email')
          ->join('t_mtr_lecturer', 't_account.identity_number', '=', 't_mtr_lecturer.nik')
          ->select('status_otp')
          ->where('t_trx_login.email', $req_validate_data['email'])
          ->where('nik', $req_validate_data['nik'])
          ->where('firstname', $req_validate_data['firstname'])
          ->where('middlename', $req_validate_data['middlename'])
          ->where('lastname', $req_validate_data['lastname'])
          ->where('faculty_code', $req_validate_data['faculty_code'])
          ->where('major_code', $req_validate_data['major_code'])
          ->where('otp_code', $req_validate_data['otp_code'])
          //->dd();

          ->get()->first();
        // dd($res);
        //
        if ($res->status_otp_int == "1") {
          // die('o');
          return false;
        } else {
          try {
            //  die('o');   
            DB::table('t_trx_login')
              ->where('email', $req_validate_data['email'])
              ->where('otp_code', $req_validate_data['otp_code'])
              ->update(['status_otp_int' => '1']);
            return true;
          } catch (\Exception $e) {
            //  DB::rollback();
            Log::warning(sprintf('Exception: %s', $e->getMessage()));
            throw ($e);
          }
        }
      } catch (\Exception $e) {
        Log::warning(sprintf('Exception: %s', $e->getMessage()));
        throw ($e);
      }
    }
  }
}
