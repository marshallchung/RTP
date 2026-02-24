<?php

namespace App\Http\Controllers\Admin\Nfa;

use App\DcUnit;
use App\Http\Controllers\Controller;
use App\Nfa\Repositories\UserRepositoryInterface;
use App\User;
use App\UserAlias;
use DB;
use Flash;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\Rules\Password;

class ResetController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param UserRepositoryInterface $userRepo
     * @return Response
     */
    public function index(UserRepositoryInterface $userRepo)
    {
        $users = $userRepo->getUsers();
        $counties = $this->getCounties();
        $dcUnits = DcUnit::has('dcUser', '<', 1)->pluck('name', 'id')->toArray();

        return view('admin.users.reset.index', compact('users', 'counties', 'dcUnits'));
    }

    private function getCounties()
    {
        $countyIdNames = User::where('type', 'county')->whereNull('county_id')->pluck('name', 'id')->toArray();

        return [null => '-'] + $countyIdNames;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param UserRepositoryInterface $userRepo
     * @param int $id
     * @return Response
     */
    public function update(Request $request, UserRepositoryInterface $userRepo, $id)
    {
        /** @var User $user */
        $user = auth()->user();
        $isAlias = $request->input('is_alias');
        if (!in_array($user->origin_role, [1, 2, 6])) {
            if ($isAlias) {
                /** @var UserAlias $user */
                $targetUser = UserAlias::find($id)->user;
            } else {
                /** @var User $user */
                $targetUser = User::find($id);
            }
            if ($user->id != $targetUser->id) {
                Flash::error('僅能修改自身帳號與其子帳號');

                return redirect()->back();
            }
        }
        $userRepo->resetAccountPassword($id, $isAlias);
        Flash::success(trans('app.resetSuccess'));

        return redirect()->route('admin.users.reset.index');
    }

    public function getDistricts(Request $request)
    {
        return response()->json(User::where([
            'type'      => 'district',
            'county_id' => $request->input('county_id'),
        ])->get()->toArray());
    }

    /**
     * 新增使用者。
     *
     */
    public function createUser()
    {
        /** @var User $user */
        $user = auth()->user();
        if (!in_array($user->origin_role, [1, 2, 6])) {
            Flash::error('僅能為自己新增子帳號');

            return redirect()->back();
        }
        $types = [
            null          => null,
            'civil'       => '社團法人臺灣防災教育訓練學會訓練學會訓練學會',
            'dp-training' => '防災士培訓機構',
        ];

        return view('admin.users.reset.create-user', compact('types'));
    }

    /**
     * @param Request $request
     * @param User $user
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Validation\ValidationException
     */
    public function postCreateUser(Request $request)
    {
        /** @var User $user */
        $user = auth()->user();
        if (!in_array($user->origin_role, [1, 2, 6])) {
            Flash::error('僅能為自己新增子帳號');

            return redirect()->back();
        }
        $this->validate($request, [
            'type'     => 'required|in:civil,dp-training',
            'name'     => 'required',
            'username' => 'required',
            'password' => 'required',
        ]);

        $type = $request->get('type');
        $origin_roles = [
            'civil'       => 6,
            'dp-training' => 7,
        ];

        $user = new User();
        $user->name = $request->get('name');
        $user->username = $request->get('username');
        $user->password = bcrypt($request->get('password'));
        $user->type = $type;
        $user->origin_role = $origin_roles[$type];
        $user->save();

        Flash::success('帳號已建立');

        return redirect()->route('admin.users.reset.index');
    }

    /**
     * 新增子帳號(Alias)使用者。
     *
     */
    public function createAliasUser(User $user)
    {
        /** @var User $authUser */
        $authUser = auth()->user();
        if (!in_array($authUser->origin_role, [1, 2, 6])) {
            if ($authUser->id != $user->id) {
                Flash::error('僅能為自己新增子帳號');

                return redirect()->back();
            }
        }

        return view('admin.users.reset.create-alias-user', compact('user'));
    }

    /**
     * @param Request $request
     * @param User $user
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Validation\ValidationException
     */
    public function postCreateAliasUser(Request $request, User $user)
    {
        /** @var User $authUser */
        $authUser = auth()->user();
        if (!in_array($authUser->origin_role, [1, 2, 6])) {
            if ($authUser->id != $user->id) {
                Flash::error('僅能為自己新增子帳號');

                return redirect()->back();
            }
        }
        $this->validate($request, [
            'username' => 'required',
            'password'         => ['required', Password::min(12)
                ->letters()
                ->mixedCase()
                ->numbers()
                ->symbols()
                ->uncompromised()],
        ]);

        $username = ['username' => $request->input('username')];

        $users = DB::table('users')->select('id')->where($username);
        $userAliases = DB::table('user_aliases')->select('id')->where($username);
        $dpStudents = DB::table('dp_students')->select('id')->where($username);
        $dcUsers = DB::table('dc_users')->select('id')->where($username);

        if ($users->union($userAliases)->union($dpStudents)->union($dcUsers)->first()) {
            return back()->withErrors(['username' => '帳號重複！請輸入別的帳號。']);
        }

        UserAlias::create([
            'user_id'  => $user->id,
            'username' => $request->input('username'),
            'password' => md5($request->input('password')),
        ]);

        Flash::success('子帳號已建立');

        return redirect()->route('admin.users.reset.index');
    }

    /**
     * @param Request $request
     * @param UserAlias $userAlias
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Validation\ValidationException
     * @throws \Exception
     */
    public function postDeleteAliasUser(Request $request, UserAlias $userAlias)
    {
        /** @var User $user */
        $user = auth()->user();
        if (!in_array($user->origin_role, [1, 2, 6])) {
            if ($user->id != $userAlias->user_id) {
                Flash::error('僅能修改自己的子帳號');

                return redirect()->back();
            }
        }

        $userAlias->delete();

        Flash::success('子帳號已刪除');

        return redirect()->route('admin.users.reset.index');
    }
}
