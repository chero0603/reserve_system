<?php

namespace App\Http\Controllers\Social\Auth;

use App\Social;
use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;
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
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest:user')->except('logout');
    }

    // Guardの認証方法を指定
    protected function guard()
    {
        return \Auth::guard('user');
    }

    /**
     * OAuth redirect
     *
     * @param str $provider
     * @return \Illuminate\Http\Response
     */
    public function socialOauth($provider)
    {
        return Socialite::with($provider)->redirect();
    }

    public function handleSocialCallback(Request $request, $provider)
    {
        try {
            $social_user = Socialite::with($provider)->user();
        } catch (\Exception $e) {
            return redirect('/login');
        }

        $request->session()->flash('_old_input', ['name' => $social_user->name, 'email' => $social_user->email]);
        $request->session()->flash('social_data', ['provider_id' => $social_user->id, 'provider_name' => $provider, 'avatar' => $social_user->avatar]);

        // 認証したSNS情報でSocialモデル検索
        $social = Social::where('provider_id', $social_user->id)
                        ->where('provider_name', $provider)
                        ->first();

        // SNSに紐づくユーザーデータチェック
        if ($social) {
            if ($social->user) {
                $user = $social->user;
                // ログイン処理
                Auth::login($user, true);
                return redirect('/');
            } else {
                // ユーザー登録処理
                return redirect()->route('social.register');
            }
        } else {
            // ユーザー登録処理
            return redirect()->route('social.register');
        }
    }
}
