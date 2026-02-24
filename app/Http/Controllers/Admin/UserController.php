<?php

namespace App\Http\Controllers\Admin;

use App\Role;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function switchRoleIndex(Request $request)
    {
        $user = $request->user();

        return view('admin.users.switchRole.index', [
            'user'        => $user,
            'role_origin' => Role::find($user->origin_role),
            'role_now'    => $user->roles()->first(),
            'roles'       => Role::get(),
        ]);
    }

    public function switchRole(Request $request)
    {
        $user = $request->user();
        $roleId_switchTo = (int) $request->input('roleId_switchTo');

        // 只能向下切換身分，admin->縣市->公所
        if (intval($roleId_switchTo < $user->origin_role)) {
            return response()->json([
                'error' => '權限錯誤',
            ]);
        }

        // 寫入權限模組
        $user->roles()->sync([$roleId_switchTo]);
        return redirect()->route('admin.admin.switchRole.index');
    }
}
