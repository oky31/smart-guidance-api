<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Class_Model;
use Illuminate\Support\Facades\Validator;

class Classes extends Controller
{
    //    
    protected function index($identity_number = NULL)
    {
        //Validate Header

        //Validate if parameter empty
        if (!isset($identity_number) && ($identity_number == NULL)) {
            return response()->json([
                'code' => 400,
                'messages' => 'Bad Request',
                'data' => ''
            ], 400);
        } else {
            //Get Class Data
            $Class_Model = new Class_Model;
            $class_data = $Class_Model->getClass($identity_number);
            return response()->json([
                'code' => '0',
                'messages' => 'Success',
                'data' => $class_data
            ], 200);
        }
    }

    protected function _info(Request $request)
    {
        //Validate Header
        // die('s');
        $messages = array('Messages');
        $validator = Validator::make($request->all(), [
            'identity_number' => 'required|max:8',
            'type' => 'required'
        ]);

        if ($validator->fails()) {
            foreach ($validator->errors()->getMessages() as $item) {
                array_push($messages, $item);
            }
            return $messages;
        } else {
            //Cek identity

            //Fungsi Simpan data        
            $res = array(
                'class_code' => 1,
                'activation_code' => substr(rand(), 0, 8),
                'registered_at' => date("Y:m:d H:i:s")
            );

            // if ($res == 0) {
            $data['code'] = 0;
            $data['messages'] = 'Success';
            $data['data'] = $res;
            return response()->json(
                $data,
                200
            );
            //}
        }
    }

    protected function _save(Request $request)
    {
        //Validate Header

        $messages = array('Messages');
        $validator = Validator::make($request->all(), [
            'identity_number' => 'required|max:8',
            'type' => 'required',
            'class_code' => 'required',
            'activation_code' => 'required',
            'registered_at' => 'required'
        ]);

        if ($validator->fails()) {
            foreach ($validator->errors()->getMessages() as $item) {
                array_push($messages, $item);
            }
            return $messages;
        } else {
            //Cek identity
            $Class_Model = new Class_Model;

            //Fungsi Simpan data        
            $res = $Class_Model->save_data($request);
            //   echo $res;
            //    die('2');

            if ($res == 0) {
                $data['code'] = 0;
                $data['messages'] = 'Class Saved';
                return $data;
            }
            // else if ($res == 2) {
            //     $data['code'] = 2;
            //     $data['messages'] = 'Account Type Invalid';
            //     return $data;
            // } elseif ($res['code'] == 4) {
            //     $data['code'] = 4;
            //     $data['messages'] = $res['messages'];
            //     return $data;
            // } else {
            //     $data['code'] = 1;
            //     $data['messages'] = 'Account Has been Registered';
            //     return $data;
            // }
        }
    }

    protected function _delete(Request $request)
    {
        //Validate Header

        $messages = array('Messages');
        $validator = Validator::make($request->all(), [
            'identity_number' => 'required|max:8',
            'type' => 'required',
            'class_code' => 'required',
            'activation_code' => 'required',
            'registered_at' => 'required'
        ]);

        if ($validator->fails()) {
            foreach ($validator->errors()->getMessages() as $item) {
                array_push($messages, $item);
            }
            return $messages;
        } else {
            $Class_Model = new Class_Model;

            //Fungsi Simpan data        
            $res = $Class_Model->delete_data($request);

            if ($res == 0) {
                $data['code'] = 0;
                $data['messages'] = 'Class Saved';
                return $data;
            }
            // else if ($res == 2) {
            //     $data['code'] = 2;
            //     $data['messages'] = 'Account Type Invalid';
            //     return $data;
            // } elseif ($res['code'] == 4) {
            //     $data['code'] = 4;
            //     $data['messages'] = $res['messages'];
            //     return $data;
            // } else {
            //     $data['code'] = 1;
            //     $data['messages'] = 'Account Has been Registered';
            //     return $data;
            // }
        }
    }
}
