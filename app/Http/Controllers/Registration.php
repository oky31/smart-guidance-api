<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Register_Model as Rm;
use Illuminate\Support\Facades\Validator;

class Registration extends Controller
{
   //
   public function index(Request $request)
   {
      $messages = array('Messages');
      $validator = Validator::make($request->all(), [
         'identity_number' => 'required|max:8',
         'type' => 'required',
         'firstname' => 'required',
         'middlename' => 'required',
         'lastname' => 'required',
         'email' => 'required',
         'password' => 'required',
         'faculty' => 'required',
         'major' => 'required',
         'title_theses' => 'required',
         'phone_number' => 'required|max:14'
      ]);

      if ($validator->fails()) {
         foreach ($validator->errors()->getMessages() as $item) {
            array_push($messages, $item);
         }
         return $messages;
      } else {
         $Register_Model = new Rm();

         //Fungsi Simpan data        
         $res = $Register_Model->save_data($request);
         //   print_r($res);
         //    die('2');

         if ($res == 1) {
            $data['code'] = 1;
            $data['messages'] = 'Account Has been Registered';
            return $data;
         } else if ($res == 2) {
            $data['code'] = 2;
            $data['messages'] = 'Account Type Invalid';
            return $data;
         } elseif ($res['code'] == 4) {
            $data['code'] = 4;
            $data['messages'] = $res['messages'];
            return $data;
         } else {
            //return 0;
            $data['code']     = 0;
            $data['messages'] = 'Account Success Registered';
            //$data['data']     = $res['data'];
            return $data;
         }
      }
   }

   public function validate_otp(Request $request)
   {
      //die('1');
      $request->validate([
         'identity_number' => 'required',
         'type' => 'required',
         'firstname' => 'required',
         'middlename' => 'required',
         'lastname' => 'required',
         'email' => 'required',
         'faculty' => 'required',
         'major' => 'required',
         'otp_code' => 'required'
      ]);

      $Register_Model = new Rm();

      //Fungsi Simpan data        
      $res = $Register_Model->validate_otp($request);

      if ($res) {
         $data['code'] = 0;
         $data['messages'] = 'Success';
         return $data;
      } elseif ($res == 1) {
         $data['code'] = 1;
         $data['messages'] = 'Code Expired';
         return $data;
      } else {
         $data['code'] = 2;
         $data['messages'] = 'Invalid Code';
         return $data;
      }
   }

   public function request_otp(Request $request)
   {
      //die('1');
      $request->validate([
         'identity_number' => 'required',
         'type' => 'required',
         'firstname' => 'required',
         'middlename' => 'required',
         'lastname' => 'required',
         'email' => 'required',
         'faculty' => 'required',
         'major' => 'required'
      ]);

      $Register_Model = new Rm();

      //Fungsi Simpan data        
      $res = $Register_Model->request_otp($request);

      if ($res) {
         $data['code'] = 0;
         $data['messages'] = 'Success';
         return $data;
      } else {
         $data['code'] = 1;
         $data['messages'] = 'Invalid Code';
         return $data;
      }
   }
}
