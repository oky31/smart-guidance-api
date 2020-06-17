<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User_detail_Model;
use Illuminate\Support\Facades\Validator;

class User_detail extends Controller
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

            $User_detail_Model = new User_detail_Model();
            $data  = $User_detail_Model->getAttendance($req);
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

            $User_detail_Model = new User_detail_Model();
            $data  = $User_detail_Model->change_status($req);
            if ($data) {
                return response()->json([
                    'code' => '0',
                    'messages' => 'Success',
                    'data' => $data
                ], 200);
            }
        }
    }

    public function update_photo_info(Request $request)
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

            $User_detail_Model = new User_detail_Model();
            $data  = $User_detail_Model->change_status($req);
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
