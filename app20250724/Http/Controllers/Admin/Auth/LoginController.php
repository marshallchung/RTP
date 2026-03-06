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
        $password = Hash::make($request->input('password'));
        $this->validateLogin($request);
        $request->validate([
            'g-recaptcha-response' => ['required', new ReCaptcha]
        ]);
        // If the class is using the ThrottlesLogins trait, we can automatically throttle
        // the login attempts for this application. We'll key this by the username and
        // the IP address of the client making these requests into this application.
        if (
            method_exists($this, 'hasTooManyLoginAttempts') &&
            $this->hasTooManyLoginAttempts($request)
        ) {
            $this->fireLockoutEvent($request);

            return $this->sendLockoutResponse($request);
        }

        // 原登入方式
        if ($this->attemptLogin($request)) {
            $user = Auth::user();
            if ($user->change_default || ($user->next_change < Date('Y-m-d H:i:s'))) {
                return redirect('/admin/users/password');
            }
            return $this->sendLoginResponse($request);
        }
        // 嘗試使用 UserAlias 登入
        /** @var UserAlias $userAlias */
        $userAlias = UserAlias::where([
            'username' => $request->input('username'),
            'password' => md5($request->input('password')),
        ])->first();
        if ($userAlias) {
            auth()->login($userAlias->user);

            return $this->sendLoginResponse($request);
        }

        // If the login attempt was unsuccessful we will increment the number of attempts
        // to login and redirect the user back to the login form. Of course, when this
        // user surpasses their maximum number of attempts they will get locked out.
        $this->incrementLoginAttempts($request);

        //        return $this->sendFailedLoginResponse($request);
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
