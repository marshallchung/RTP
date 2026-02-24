<?php

namespace App\Http\Controllers\Admin\Nfa;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;

class IdentityController extends Controller
{
    /**
     * 顯示可切換之身分
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        /** @var User $user */
        $user = auth()->user();
        if (!$user->can_change_identity) {
            abort(403);
        }
        $originIdentity = session('origin_identity');
        /** @var User $originIdentityUser */
        $originIdentityUser = User::find($originIdentity);

        return view('admin.identity.index', compact('user', 'originIdentityUser'));
    }

    /**
     * 切換身分
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Validation\ValidationException
     */
    public function changeIdentity(Request $request)
    {
        /** @var User $user */
        $user = auth()->user();
        if (!$user->can_change_identity) {
            abort(403);
        }
        $this->validate($request, [
            'change_to' => 'required',
        ]);
        //切換身分對象
        $changeTo = $request->get('change_to');
        /** @var User $changeToUser */
        $changeToUser = User::find($changeTo);
        if (!in_array($changeTo, array_keys($user->changeable_identities)) || !$changeToUser) {
            \Flash::error('無效身分');

            return back();
        }
        //記錄原身分
        $originIdentity = session('origin_identity');
        if (!$originIdentity) {
            session(['origin_identity' => $user->id]);
        }
        //切換身分
        \Auth::login($changeToUser);

        return back();
    }

    /**
     * 還原身分
     *
     * @return \Illuminate\Http\Response
     */
    public function changeIdentityBack()
    {
        //原身分
        $originIdentity = session('origin_identity');
        /** @var User $originIdentityUser */
        $originIdentityUser = User::find($originIdentity);
        if (!$originIdentityUser) {
            return redirect()->route('admin.identity.index');
        }
        //切換身分
        \Auth::login($originIdentityUser);
        //清除原身分紀錄
        session(['origin_identity' => null]);

        return back();
    }
}
