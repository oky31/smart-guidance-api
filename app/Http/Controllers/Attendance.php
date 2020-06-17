<?php

namespace App\Http\Controllers;

use App\Attendance_Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class Attendance extends Controller

{
    //
    public function index($nik = NULL)
    {
        //Validate Header
        //Validate if parameter empty
        if ($nik == NULL) {
            return response()->json([
                'code' => 400,
                'messages' => 'Bad Request',
                'data' => ''
            ], 400);
        } else {
            //Get Class Data
            $req = $nik;

            $Attendance_Model = new Attendance_Model();
            $data  = $Attendance_Model->getAttendance($req);
            return response()->json([
                'code' => '0',
                'messages' => 'Success',
                'data' => $data
            ], 200);
        }
    }

    public function check(Request $request)
    {
        //Validate Header
        //Validate if parameter empty
        $messages = array('Messages');
        $validator = Validator::make($request->all(), [
            'nik'       => 'required',
            'class_code' => 'required',
            'title_guidance' => 'required'
        ]);

        if ($validator->fails()) {
            foreach ($validator->errors()->getMessages() as $item) {
                array_push($messages, $item);
            }
            return response()->json([
                'code' => 400,
                'messages' => 'Bad Request',
                'data' => $messages
            ], 400);
            return;
        } else {
            //Get Class Data

            $req = array(
                'nik' => $request->nik,
                'class_code' => $request->class_code,
                'title_guidance' => $request->title_guidance
            );

            $Attendance_Model = new Attendance_Model();
            $data  = $Attendance_Model->getAttendanceperclass($req);
            return response()->json([
                'code' => '0',
                'messages' => 'Success',
                'data' => $data
            ], 200);
        }
    }


    public function update(Request $request)
    {
        //Validate Header
        //Validate if parameter empty
        $messages = array('Messages');
        $validator = Validator::make($request->all(), [
            'id_schedule' => 'required',
            'nim' => 'required',
            'status' => 'required',
            'class_code' => 'required'
        ]);

        if ($validator->fails()) {
            foreach ($validator->errors()->getMessages() as $item) {
                array_push($messages, $item);
            }
            return response()->json([
                'code' => 400,
                'messages' => 'Bad Request',
                'data' => $messages
            ], 400);
            return;
        } else {
            //Get Class Data
            $req = array(
                'nim' => $request->nim,
                'status'     => $request->status,
                'class_code' => $request->class_code,
                'id_schedule' => $request->id_schedule
            );

            $Attendance_Model = new Attendance_Model();
            $data  = $Attendance_Model->change_status($req);
            if ($data) {
                return response()->json([
                    'code' => '0',
                    'messages' => 'Success',
                    'data' => $data
                ], 200);
            }
        }
    }
}
