<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Auth_Model;
use Illuminate\Support\Facades\Validator;

class Auth extends Controller
{
    public function index(Request $request)
    {
        $messages = array('Messages');
        $validator = Validator::make($request->all(), [
            'email' => 'required|string',
            'password' => 'required|string',
            'type' => 'required|string'
        ]);

        if ($validator->fails()) {
            foreach ($validator->errors()->getMessages() as $item) {
                array_push($messages, $item);
            }
            return $messages;
        } else {
            //Call Model To Object
            $Auth_Model = new Auth_Model();
            //Check Account
            $checkAccount = $Auth_Model->checkAccount($request->email);
            //If Account Exist
            if ($checkAccount) {
                $checkPassword = $Auth_Model->checkPassword($request);
                //If Password True
                if ($checkPassword) {
                    $acccount_details        = $Auth_Model->AccountDetails($request);
                    if ($acccount_details == 1) {
                        $data['code'] = 1;
                        $data['messages'] = 'Your Account is registered as Students';
                        $data['data']  = '';
                        return $data;
                    } else if ($acccount_details == 2) {
                        $data['code'] = 2;
                        $data['messages'] = 'Your Account is registered as Lecturer';
                        $data['data']  = '';
                        return $data;
                    } else {
                        $acccount_details        = $Auth_Model->AccountDetails($request);
                        $acccount_details['key'] = 'sa';
                        $data['code'] = 0;
                        $data['messages'] = 'Login Success';
                        $data['data']  = $acccount_details;
                        return $data;
                    }
                } else {
                    //If Password Not True
                    $data['code'] = 1;
                    $data['messages'] = 'Invalid email or password';
                    $data['data']  = '';
                    return $data;
                }
                //If Account Not Exist
            } else {
                $data['code'] = 1;
                $data['messages'] = 'Account Not Registered';
                $data['data']  = '';
                return $data;
            }
        }
    }
}
