<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Version_Model as Vcm;
use Illuminate\Support\Facades\Validator;

class Version extends Controller
{
    public function index(Request $request)
    {
        $messages = array('Messages');
        $validator = Validator::make($request->all(), [
            'current_version' => 'required|max:2',
            'api_key' => 'required|max:20'
        ]);
        if ($validator->fails()) {
            foreach ($validator->errors()->getMessages() as $item) {
                array_push($messages, $item);
            }
            return $messages;
        } else {
            //Panggil Model dengan objek baru
            $vcm = new Vcm();

            //Fungsi check version
            $res = $vcm->checkDevicesVersion($request);

            //Jika version valid
            if ($res->allow == 0) {
                $data['code'] = 0;
                $data['messages'] = 'Success';
                $data['data']  = '';
                return $data;
            } else if ($res->allow == 1) {
                $data['code'] = 1;
                $data['messages'] = 'Version Deprecated';
                $data['data']  = '';
                return $data;
            } else {
                $data['code'] = 2;
                $data['messages'] = 'Version Unavailable';
                $data['data']  = '';
                return $data;
            }
        }
    }
}
