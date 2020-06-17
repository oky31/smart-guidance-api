<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Schedule_Model;
use Illuminate\Support\Facades\Validator;

class Schedule extends Controller
{
    public function index($identity_number = NULL, $class_code = NULL)
    {
        //Validate Header
        //Validate if parameter empty
        if (($identity_number == NULL) && ($class_code == NULL)) {
            return response()->json([
                'code' => 400,
                'messages' => 'Bad Request',
                'data' => ''
            ], 400);
        } else {
            //Get Class Data
            $req = array(
                'identity_number' => $identity_number,
                'class_code' => $class_code
            );

            $Schedule_Model = new Schedule_Model;
            $schedule_data  = $Schedule_Model->getSchedule($req);
            return response()->json([
                'code' => '0',
                'messages' => 'Success',
                'data' => $schedule_data
            ], 200);
        }
    }

    public function _info(Request $request)
    {
        //Validate Header
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

            //Fungsi Simpan data        
            $data_res = array(
                'registered_at' => date("Y:m:d H:i:s")
            );

            // if ($res == 0) {
            $data['code'] = 0;
            $data['messages'] = 'Success';
            $data['data'] = $data_res;
            return response()->json(
                $data,
                200
            );
            //}
        }
    }

    public function _save(Request $request)
    {
        //Validate Header

        $messages = array('Messages');
        $validator = Validator::make($request->all(), [
            'class_code' => 'required',
            'date' => 'required',
            'title_guidance' => 'required',
            'registered_at' => 'required'
        ]);

        if ($validator->fails()) {
            foreach ($validator->errors()->getMessages() as $item) {
                array_push($messages, $item);
            }
            return $messages;
        } else {
            $Schedule_Model = new Schedule_Model;
            //Fungsi Simpan data        
            $res = $Schedule_Model->save_data($request);

            if ($res == 0) {
                $data['code'] = 0;
                $data['messages'] = 'Schedule Successfully Added';
                return response()->json(
                    $data,
                    200
                );
            } else {
                $data['code'] = 1;
                $data['messages'] = 'Failed Save';
                return response()->json(
                    $data,
                    200
                );
            }
        }
    }

    public function _update(Request $request)
    {
        //Validate Header

        $messages = array('Messages');
        $validator = Validator::make($request->all(), [
            'class_code' => 'required',
            'place' => 'required',
            'date_reschedule' => 'required',
            'start_time_reschedule' => 'required',
            'end_time_reschedule' => 'required',
            'title_guidance' => 'required',
            'registered_at' => 'required'
        ]);

        if ($validator->fails()) {
            foreach ($validator->errors()->getMessages() as $item) {
                array_push($messages, $item);
            }
            return $messages;
        } else {
            $Schedule_Model = new Schedule_Model;
            //Fungsi Simpan data        
            $res = $Schedule_Model->update_data($request);
            if ($res == 0) {
                $data['code'] = 0;
                $data['messages'] = 'Schedule Updated';
                return response()->json(
                    $data,
                    200
                );
            } else if ($res == 2) {
                $data['code'] = 2;
                $data['messages'] = 'Schedule cannot be changed between held times';
                return response()->json(
                    $data,
                    200
                );
            } else {
                $data['code'] = 4;
                $data['messages'] = 'Schedule cannot be resechedule between 1 hours class times';
                return response()->json(
                    $data,
                    200
                );
            }
        }
    }

    public function _delete(Request $request)
    {
        //Validate Header

        $messages = array('Messages');
        $validator = Validator::make($request->all(), [
            'class_code' => 'required',
            'place' => 'required',
            'date' => 'required',
            'start_time' => 'required',
            'end_time' => 'required',
            'title_guidance' => 'required',
            'registered_at' => 'required'
        ]);

        if ($validator->fails()) {
            foreach ($validator->errors()->getMessages() as $item) {
                array_push($messages, $item);
            }
            return $messages;
        } else {
            $Schedule_Model = new Schedule_Model;

            //Fungsi Simpan data        
            $res = $Schedule_Model->delete_data($request);

            if ($res == 0) {
                $data['code'] = 0;
                $data['messages'] = 'Schedule Successfully Deleted';
                return response()->json(
                    $data,
                    200
                );
            }
        }
    }
}
