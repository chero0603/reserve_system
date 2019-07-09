<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Socialite;

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
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    /**
     * OAuth redirect
     *
     * @param str $provider
     * @return \Illuminate\Http\Response
     */
    public function socialLogin($provider)
    {
        return Socialite::driver($provider)->redirect();
    }

    public function handleSocialCallback($social)
    {
        //ソーシャルサービス（情報）を取得
        $userSocial = Socialite::driver($social)->stateless()->user();
        dd($userSocial);
    }

    public function logout(Request $request)
    {
        $this->guard('user')->logout();
        return redirect('/');
    }
}
