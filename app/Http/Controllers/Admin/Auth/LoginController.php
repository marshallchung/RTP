<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Http\Controllers\Controller;
use App\Rules\ReCaptcha;
use App\User;
use App\UserAlias;
use DateTime;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\DB;

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

    use AuthenticatesUsers {
        login as originalLogin;
    }

    protected $maxAttempts = 5;

    protected $decayMinutes = 15;

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

    /**
     * Show the application's login form.
     *
     * @return \Illuminate\Http\Response
     */
    public function showLoginForm()
    {
        return view('admin.auth.login');
    }

    /**
     * Handle a login request to the application.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\Response|\Illuminate\Http\JsonResponse
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function login(Request $request)
    {

        $this->validateLogin($request);

        if (
            method_exists($this, 'hasTooManyLoginAttempts') &&
            $this->hasTooManyLoginAttempts($request)
        ) {
            $this->fireLockoutEvent($request);

            return $this->sendLockoutResponse($request);
        }
        //主帳號登入判斷
        if ($this->attemptLogin($request)) {
            $user = Auth::user();

            // 檢查是否要強制改密碼
            if ($user->change_default || ($user->next_change < now())) {
                return redirect('/admin/users/password');
            }

            // 進行 2FA 驗證判斷
            if ($user->is_2fa_enabled) {
                if (!$user->google2fa_secret) {
                    // 第一次尚未設定 2FA，導向初次設定畫面
                    session(['2fa:temp_user_id' => $user->id]);
                    Auth::logout();
                    return redirect()->route('auth.2fa.setup');
                }

                // 已設定過 2FA，導向輸入驗證碼畫面
                session(['2fa:user:id' => $user->id]);
                Auth::logout();
                return redirect()->route('auth.2fa.form');
            }

            // 若沒啟用 2FA，正常登入
            return $this->sendLoginResponse($request);
        }
        //子帳號登入判斷
        $userAlias = UserAlias::where([
            'username' => $request->input('username'),
            'password' => md5($request->input('password')),
        ])->first();

        if ($userAlias) {


            if (!$userAlias->aliases_google_2fa_secret) {

                return redirect()->route('auth.2fa.setup', [
                    'sub_user_id' => $userAlias->id
                ]);
            }

            return redirect()->route('auth.2fa.form', [
                'sub_user_id' => $userAlias->id
            ]);

            return $this->sendLoginResponse($request);
        }

        return redirect()->back()
            ->withInput($request->only([$this->username(), 'remember']))
            ->withErrors([
                $this->username() => trans('auth.failed'),
            ]);
    }


    /**
     * Get the login username to be used by the controller.
     *
     * @return string
     */
    public function username()
    {
        return 'username';
    }

    /**
     * The user has logged out of the application.
     *
     * @param \Illuminate\Http\Request $request
     * @return mixed
     */
    protected function loggedOut(Request $request)
    {
        //清除原身分紀錄
        session(['origin_identity' => null]);

        return redirect($this->redirectTo);
    }
}
