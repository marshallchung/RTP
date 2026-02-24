<?php

namespace App\Http\Controllers;

use App\DataTables\UserDataTable;
use App\DcUnit;
use App\DcUser;
use App\DpStudent;
use App\Models\DcUsersPasswordHistory;
use App\Models\DpStudentsPasswordHistory;
use App\Role;
use App\Rules\ReCaptcha;
use App\Services\MailService;
use App\User;
use Auth;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    use ThrottlesLogins;

    /**
     * UserController constructor.
     */
    public function __construct()
    {
        $this->middleware('permission:user.manage|user.view')->only([
            'index',
            'show',
        ]);
        $this->middleware('permission:user.manage')->only([
            'edit',
            'update',
            'destroy',
        ]);
        $this->middleware('throttle:6,1')->only([
            'login',
        ]);
    }

    /**
     * Display a listing of the resource.
     *
     * @param UserDataTable $dataTable
     * @return \Illuminate\Http\JsonResponse|\Illuminate\View\View
     */
    public function index(UserDataTable $dataTable)
    {
        return $dataTable->render('user.index');
    }

    /**
     * Display the specified resource.
     *
     * @param User $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        return view('user.show', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param User $user
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        $roles = Role::all();

        return view('user.edit', compact('user', 'roles'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param User $user
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Validation\ValidationException
     */
    public function update(Request $request, User $user)
    {
        $this->validate($request, [
            'name' => 'required|max:255',
        ]);

        $user->update([
            'name' => $request->input('name'),
        ]);
        //管理員禁止去除自己的管理員職務
        $keepAdmin = false;
        if ($user->id == auth()->user()->id) {
            $keepAdmin = true;
        }
        //移除原有權限
        $user->detachRoles($user->roles);
        //重新添加該有的權限
        if ($request->has('role')) {
            $user->addRoles($request->input('role'));
        }
        //加回管理員
        if ($keepAdmin) {
            $admin = Role::where('name', '=', 'Admin')->first();
            $user->addRole($admin);
        }

        return redirect()->route('user.show', $user)
            ->with('success', '資料修改完成。');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param User $user
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */
    public function destroy(User $user)
    {
        if ($user->hasRole('Admin')) {
            return redirect()->route('user.show', $user)
                ->with('warning', '無法刪除管理員，請先解除管理員角色。');
        }
        $user->delete();

        return redirect()->route('user.index')
            ->with('success', '會員已刪除。');
    }

    public function loginIndex(Request $request)
    {
        return view('user.login');
    }

    public function login(Request $request)
    {
        switch ($request->input('type')) {
            case 'dp':
                $auth = Auth::guard('dp');
                $table = 'dp_students';

                break;

            case 'dc':
                $auth = Auth::guard('dc');
                $table = 'dc_users';

                break;
        }

        // 驗證
        $validator = Validator::make($request->all(), [
            'username' => 'required|exists:' . $table . ',username',
            'password' => 'required',
            'g-recaptcha-response' => ['required', new ReCaptcha],
        ], [
            'username.required' => '請輸入帳號!',
            'username.exists'   => '帳號或密碼錯誤',
            'password.required' => '請輸入密碼!',
            'g-recaptcha-response.required' => '請點選我不是機器人!',
        ]);

        if ($validator->fails()) {
            $valiErrors = $validator->errors()->all();
            $msg = '';
            if (!empty($valiErrors)) {
                $msg = implode('<br>', $valiErrors);
            }

            return response()->json([
                'error' => $msg,
            ]);
        }

        // 登入
        if ($auth->attempt([
            'username' => $request->input('username'),
            'password' => $request->input('password'),
            'active'   => 1,
        ])) {
            $user = $auth->user(); // 導向
            $attempting_page = $request->session()->pull('attempting_page');
            $request->session()->forget('attempting_page');

            if ($user->change_default) {
                return response()->json([
                    'user_id' => $user->id,
                    'error' => '新帳號，為維護資訊安全，請立即變更密碼',
                    'reset_password' => 'change_default',
                    'type' => $request->input('type'),
                    'attempting_page' => $attempting_page,
                ]);
            } elseif ($user->next_change < Date('Y-m-d H:i:s')) {
                return response()->json([
                    'user_id' => $user->id,
                    'error' => '您已三個月未更換密碼，為維護資訊安全，請立即變更密碼',
                    'reset_password' => 'next_change',
                    'type' => $request->input('type'),
                    'attempting_page' => $attempting_page,
                ]);
            }
            return response()->json([
                'error'           => false,
                'user'            => $user,
                'attempting_page' => $attempting_page,
            ]);
        } else {
            return response()->json([
                'error' => '帳號或密碼錯誤',
            ]);
        }
    }

    public function changePassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'password'         => ['required', Password::min(12)
                ->letters()
                ->mixedCase()
                ->numbers()
                ->symbols()
                ->uncompromised()],
            'password-confirm' => 'required|same:password',
        ], [
            'password.required'         => '請輸入密碼!',
            'password.between'          => '密碼長度請介於6~12之間!',
            'password.min'              => '密碼長度必須12字元以上',
            'password-confirm.required' => '請輸入確認密碼欄位!',
            'password-confirm.same'     => '兩次密碼輸入結果不同!',
        ]);

        if ($validator->fails()) {
            $valiErrors = $validator->errors()->all();
            $msg = '';
            if (!empty($valiErrors)) {
                $msg = implode('<br>', $valiErrors);
            }

            return response()->json([
                'error' => $msg,
            ]);
        }
        $user_password = bcrypt($request->input('password'));
        $user_id = $request->input('user_id');
        if ($request->input('type') == 'dp') {
            $history = DpStudentsPasswordHistory::where('dp_students_id', $user_id)->orderBy('id', 'DESC')->limit(3)->get();
        } else {
            $history = DcUsersPasswordHistory::where('dc_users_id', $user_id)->orderBy('id', 'DESC')->limit(3)->get();
        }
        if ($history) {
            foreach ($history as $one_histoey) {
                if (Hash::check($request->input('password'), $one_histoey->password)) {
                    return response()->json([
                        'error' => '密碼不得與最近三次變更的重複',
                    ]);
                }
            }
        }
        if ($request->input('type') == 'dp') {
            DpStudentsPasswordHistory::create(['dp_students_id' => $user_id, 'password' => $user_password]);
            DpStudent::where('id', $user_id)->update([
                'password' => $user_password,
                'change_default' => 0,
                'next_change' => date("Y-m-d H:i:s", strtotime("+3 month")),
            ]);
        } else {
            DcUsersPasswordHistory::create(['dc_users_id' => $user_id, 'password' => $user_password]);
            DcUser::where('id', $user_id)->update([
                'password' => $user_password,
                'change_default' => 0,
                'next_change' => date("Y-m-d H:i:s", strtotime("+3 month")),
            ]);
        }
        return response()->json([
            'ok' => '密碼已更新成功！下次請使用新的密碼登入',
        ]);
    }

    public function logout(Request $request)
    {
        Auth::guard('dp')->logout();
        Auth::guard('dc')->logout();

        return redirect('/');
    }

    public function resetPassword(DcUser $user, Request $request)
    {
        $data = $request->all();
        $keys = array_keys($data);
        foreach ($keys as $key) {
            if ($key !== 'signature') {
                $data['dcUser'] = $key;
                break;
            }
        }
        return view('user.resetPassword', $data);
    }

    public function execResetPassword(Request $request)
    {
        if ($request->has('hasValidSignature') && $request->get('hasValidSignature') === 'hasValidSignature') {
            $validator = Validator::make($request->all(), [
                'mobile'           => 'required',
                'email'            => 'required|email',
                'password'         => ['required', Password::min(12)
                    ->letters()
                    ->mixedCase()
                    ->numbers()
                    ->symbols()
                    ->uncompromised()],
                'password-confirm' => 'required|same:password',
            ], [
                'mobile.required'           => '請輸入手機!',
                'email.required'            => '請輸入Email',
                'email.email'               => 'Email格式錯誤',
                'password.required'         => '請輸入密碼!',
                'password.between'          => '密碼長度請介於6~12之間!',
                'password.min'              => '密碼長度必須12字元以上',
                'password-confirm.required' => '請輸入確認密碼欄位!',
                'password-confirm.same'     => '兩次密碼輸入結果不同!',
            ]);

            $fallbackData = [
                'dcUser' => $request->get('dcUser'),
                'signature' => $request->get('signature'),
                'type'   => $request->input('type'),
                'mobile' => $request->input('mobile'),
                'email'  => $request->input('email'),
            ];

            if ($validator->fails()) {
                $valiErrors = $validator->errors()->all();
                $msg = '';
                if (!empty($valiErrors)) {
                    $msg = implode('<br>', $valiErrors);
                }

                flash($msg);

                return redirect()->route('user.resetPassword', $fallbackData);
            }
            $historyDB = null;
            $user_id_table = '';
            $user_password = bcrypt($request->input('password'));
            if ($request->input('type') == 'dp') {
                $user = DpStudent::where([
                    ['mobile', $request->input('mobile')],
                    ['email', $request->input('email')],
                ])->first();
            } else {
                $dcUnit = DcUnit::where([
                    ['phone', $request->input('mobile')],
                    ['email', $request->input('email')],
                ])->first();

                $user = null;
                if ($dcUnit) {
                    $user = $dcUnit->dcUser;
                }
            }

            if (!$user) {
                flash('密碼更新失敗! 您輸入手機和email找不到對應帳號。');

                return redirect()->route('user.resetPassword', $fallbackData);
            } else {
                $user_id = $user->id;
                if ($request->input('type') == 'dp') {
                    $history = DpStudentsPasswordHistory::where('dp_students_id', $user_id)->orderBy('id', 'DESC')->limit(3)->get();
                } else {
                    $history = DcUsersPasswordHistory::where('dc_users_id', $user_id)->orderBy('id', 'DESC')->limit(3)->get();
                }
                if ($history) {
                    foreach ($history as $one_histoey) {
                        if (Hash::check($request->input('password'), $one_histoey->password)) {
                            flash('密碼不得與最近三次變更的重複');
                            return redirect()->route('user.resetPassword', $fallbackData);
                        }
                    }
                }
                if ($request->input('type') == 'dp') {
                    DpStudentsPasswordHistory::create(['dp_students_id' => $user_id, 'password' => $user_password]);
                } else {
                    DcUsersPasswordHistory::create(['dc_users_id' => $user_id, 'password' => $user_password]);
                }
                $user->password = $user_password;
                $user->change_default = 0;
                $user->next_change = date("Y-m-d H:i:s", strtotime("+3 month"));
                $user->save();
                flash('密碼已更新成功！您的帳號是' . $user->username);

                return redirect()->route('user.login', ['type' => $request->input('type')]);
            }
        } else {
            $validator = Validator::make($request->all(), [
                'mobile'           => 'required',
                'email'            => 'required|email',
            ], [
                'mobile.required'           => '請輸入手機!',
                'email.required'            => '請輸入Email',
                'email.email'               => 'Email格式錯誤',
            ]);

            $fallbackData = [
                'type'   => $request->input('type'),
                'mobile' => $request->input('mobile'),
                'email'  => $request->input('email'),
            ];

            if ($validator->fails()) {
                $valiErrors = $validator->errors()->all();
                $msg = '';
                if (!empty($valiErrors)) {
                    $msg = implode('<br>', $valiErrors);
                }

                flash($msg);

                return redirect()->route('user.resetPassword', $fallbackData);
            }
            $dcUnit = DcUnit::where([
                ['phone', $request->input('mobile')],
                ['email', $request->input('email')],
            ])->first();

            $user = null;
            if ($dcUnit) {
                $user = $dcUnit->dcUser;
            }
            if (!$user) {
                flash('密碼更新失敗! 您輸入手機和email找不到對應帳號。');

                return redirect()->route('user.resetPassword', $fallbackData);
            }

            $formUrl = \URL::signedRoute('user.resetPassword', $user);

            $mailService = app(MailService::class);
            $mailService->addDcUserMailToQueue($dcUnit, $formUrl);

            if (request()->has('response_json') && request()->get('response_json')) {
                return response()->json(['msg' => "會員重設密碼認證信已寄出給 {$user->name}（{$dcUnit->email}）"]);
            } else {
                $fallbackData = [
                    'type'   => $request->input('type'),
                    'mobile' => $request->input('mobile'),
                    'email'  => $request->input('email'),
                ];
                flash("會員重設密碼認證信已寄出給 {$user->name}（{$dcUnit->email}）");
                return redirect()->route('user.resetPassword', $fallbackData);
            }
        }
    }
}
