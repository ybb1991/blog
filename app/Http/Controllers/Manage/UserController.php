<?php

namespace App\Http\Controllers\Manage;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;

class UserController extends BaseController
{
    public function decode(Request $request)
    {
        dd(Auth::guard('manage')->user());
    }

    public function login(Request $request)
    {
        $info = Auth::guard('manage')->attempt(['phone'=>$request->phone, 'password'=>'123456']);
        return $this->response->array([
            'access_token'  => $info,    // JWT token 
            'user'          => Auth::guard('manage')->user(),
        ])->setStatusCode(201);
    }


    public function show($id)
    {
        dd(Auth::guard('manage')->user());
    }
}
