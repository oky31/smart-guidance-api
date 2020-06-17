<?php

use Illuminate\Http\Request;

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });
//Auth
Route::post('auth', 'User\Auth@index');
Route::post('checkversion', 'Version@index');

//Register
Route::post('register', 'Registration@index');
Route::post('validate_otp', 'Registration@validate_otp');
Route::post('request_otp', 'Registration@request_otp');

//Classes
Route::get('class/', 'Classes@index');
Route::get('class/{identity_number}', 'Classes@index');
Route::post('class/create', 'Classes@_info');
Route::post('class/save', 'Classes@_save');
Route::post('class/delete', 'Classes@_save');

//Schedule
Route::get('schedule/', 'Schedule@index');
Route::get('schedule/{identity_number}/{class_code}', 'Schedule@index');
Route::post('schedule/create', 'Schedule@_info');
Route::post('schedule/save', 'Schedule@_save');
Route::post('schedule/delete', 'Schedule@_save');

//Attendance
Route::get('attendance/', 'Attendance@index');
Route::get('attendance/{identity_number}', 'Attendance@index');
Route::post('attendance/update', 'Attendance@update');
//Progress

//People Request
Route::get('people_request/', 'People_request@index');
Route::get('people_request/{identity_number}', 'People_request@index');
Route::post('people_request/update', 'People_request@update');


//User Details
Route::get('user_detail/', 'User_detail@index');
Route::get('user_detail/{identity_number}', 'User_detail@index');
Route::post('user_detail/update', 'User_detail@update');
Route::post('user_detail/upload_photo', 'User_detail@upload_photo_info');

// Route::post('register',function(){
//     return "ts";
// });

