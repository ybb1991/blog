<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use Dingo\Api\Contract\Http\Request;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Auth;
class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/admin';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function username()
    {
        return 'phone';
    }

    protected function guard()
    {
        return \Auth::guard('admin');
    }

    public function login(Request $request)
    {
        $request['phone'] = '17358483072';
        $request['password'] = '123456';
        $user = Admin::where('phone', $request->phone)->first();
        // $user->password = bcrypt('123456');
        // $user->save();
        // if($this->attemptLogin($request)){
        //     return response()->json(['code' => 200, 'msg' => '登录成功']);
        //    }else{
        //     return response()->json(['code' => 403, 'msg' => '用户名或密码错误']);
        //    }

        if ($this->attemptLogin($request)) {
            return $this->sendLoginResponse($request);
        }

        // If the login attempt was unsuccessful we will increment the number of attempts
        // to login and redirect the user back to the login form. Of course, when this
        // user surpasses their maximum number of attempts they will get locked out.
        $this->incrementLoginAttempts($request);

        return $this->sendFailedLoginResponse($request);
    }
}
