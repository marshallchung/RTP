<?php

namespace App\Http\Controllers\Admin\Nfa;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePasswordRequest;
use App\Models\UsersPasswordHistory;
use App\Nfa\Repositories\UserRepositoryInterface;
use Laracasts\Flash\Flash;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class PasswordController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $user = Auth::user();
        $change_default = $user->change_default;
        $next_change = $user->next_change < Date('Y-m-d H:i:s');
        return view('admin.users.password.index', compact('change_default', 'next_change'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StorePasswordRequest $request
     * @param UserRepositoryInterface $userRepo
     * @return Response
     */
    public function store(StorePasswordRequest $request, UserRepositoryInterface $userRepo)
    {
        $user = Auth::user();
        if ($history = UsersPasswordHistory::where('user_id', $user->id)->orderBy('id', 'DESC')->limit(3)->get()) {
            foreach ($history as $one_histoey) {
                if (Hash::check($request->get('password'), $one_histoey->password)) {
                    Flash::success('密碼不得與最近三次變更的重複');
                    return redirect('/admin/users/password');
                }
            }
        }
        $userRepo->updatePassword($request->get('password'));

        Flash::success(trans('app.updateSuccess', ['type' => '密碼']));

        return redirect()->route('admin.dashboard.index');
    }
}
