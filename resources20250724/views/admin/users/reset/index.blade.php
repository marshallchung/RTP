@extends('admin.layouts.dashboard', [
'heading' => '新增&重設帳號',
'breadcrumbs' => ['新增&重設帳號']
])

@section('title', '新增&重設帳號')

@section('inner_content')
<div x-data="{
    showRole:'',
}" class="relative w-full max-w-5xl p-4 text-content text-mainAdminTextGrayDark">
    @if(in_array(auth()->user()->origin_role, [1, 2, 6]))
    <div class="p-5 mb-4 border-l-2 border-l-lime-400 bg-lime-50/70">
        <a href="{{ route('admin.users.create-user') }}" class="btn btn-lg btn-success">新增帳號</a>
    </div>
    @endif
    <div class="p-5 mb-4 border-l-2 border-l-sky-400 bg-sky-50/70">
        <?php
            $roles = [
                '' => '全選',
                2  => '消防署',
                4  => '縣市政府',
                6  => '社團法人臺灣防災教育訓練學會',
                7  => '防災士培訓機構'
            ];
            ?>
        {!! Form::select('filt_role_id', $roles, '', [
        'id' => 'filt_role_id',
        'class' => 'h-12 px-4 border-gray-300 rounded-md shadow-sm focus:border-cyan-300 focus:ring focus:ring-cyan-200
        focus:ring-opacity-50 w-full','x-model'=>'showRole'
        ]) !!}
    </div>
    <div class="p-5 mb-4 border-l-2 border-l-sky-400 bg-sky-50/70">
        <p class="m-0">{{ trans('app.users.reset.info') }}</p>
        <p class="m-0">{{ trans('app.users.reset.hint') }}</p>
    </div>
    <table class="w-full bg-white border shadow-lg text-mainAdminTextGrayDark">
        <thead>
            <tr class="border-b rounded-t bg-mainGray">
                <th class="p-2 font-bold border-r last:border-r-0">帳號</th>
                <th class="p-2 font-bold border-r last:border-r-0">對應帳號</th>
                <th class="w-32 p-2 font-bold border-r last:border-r-0">子帳號</th>
                <th class="p-2 font-bold border-r w-28 last:border-r-0">密碼</th>
                <th class="w-24 p-2 font-bold border-r last:border-r-0">刪除子帳號</th>
            </tr>
        </thead>
        <tbody class="text-content text-mainAdminTextGrayDark">
            @foreach ($users as $user)
            <tr x-show="showRole=='' || showRole=='{{ $user->origin_role }}'"
                class="text-left border-r last:border-r-0 odd:bg-white even:bg-mainLight">
                <td class="p-2 border-r last:border-r-0">{{ $user->username }}（{{ $user->name }}）</td>
                <td class="p-2 border-r last:border-r-0"></td>
                <td class="p-2 border-r last:border-r-0">
                    <a href="{{ route('admin.users.create-alias-user', $user) }}" class="px-4 text-sm rounded cursor-pointer py-1.5 bg-gray-100
                    hover:bg-gray-50 border">新增子帳號</a>
                </td>
                <td x-data="{showConfirm:false}" class="p-2 border-r last:border-r-0">
                    {!! Form::open(['method' => 'PUT', 'route' => ['admin.users.reset.update', $user->id]]) !!}
                    <button
                        class="w-20 flex justify-center items-center text-sm rounded cursor-pointer py-1.5 bg-gray-100  hover:bg-gray-50 border  border-gray-300"
                        x-show="!showConfirm" @click="showConfirm=!showConfirm" type="button">重設密碼</button>
                    <button class="w-20 flex justify-center items-center text-sm text-white rounded cursor-pointer py-1.5
                    bg-rose-500 hover:bg-rose-400" x-show="showConfirm" type="submit">確認</button>
                    {!! Form::close() !!}
                </td>
                <td class="p-2 border-r last:border-r-0"></td>
            </tr>
            @if ($user->userAliases)
            @foreach ($user->userAliases as $aliasUser)
            <tr x-show="showRole=='' || showRole=='{{ $user->origin_role }}'"
                class="text-left border-r last:border-r-0 odd:bg-white even:bg-mainLight">
                <td class="p-2 border-r last:border-r-0">
                    <i class="fa fa-angle-right"></i> {{ $aliasUser->username }}
                </td>
                <td class="p-2 border-r last:border-r-0">{{ $user->username }}（{{ $user->name }}）</td>
                <td class="p-2 border-r last:border-r-0"></td>
                <td x-data="{showConfirm:false}" class="p-2 border-r last:border-r-0">
                    {!! Form::open(['method' => 'PUT', 'route' => ['admin.users.reset.update', $aliasUser->id]]) !!}
                    {!! Form::hidden('is_alias', 1) !!}
                    <button
                        class="w-20 flex justify-center items-center text-sm rounded cursor-pointer py-1.5 bg-gray-100  hover:bg-gray-50 border  border-gray-300"
                        x-show="!showConfirm" @click="showConfirm=!showConfirm" type="button">重設密碼</button>
                    <button class="w-20 flex justify-center items-center text-sm text-white rounded cursor-pointer py-1.5
                                        bg-rose-500 hover:bg-rose-400" x-show="showConfirm" type="submit">確認</button>
                    {!! Form::close() !!}
                </td>
                <td x-data="{showConfirm:false}" class="p-2 border-r last:border-r-0">
                    {!! Form::open(['method' => 'DELETE', 'route' => ['admin.users.delete-alias-user', $aliasUser]]) !!}
                    <button
                        class="w-20 flex justify-center items-center text-sm rounded cursor-pointer py-1.5 bg-gray-100  hover:bg-gray-50 border  border-gray-300"
                        x-show="!showConfirm" @click="showConfirm=!showConfirm" type="button">刪除子帳號</button>
                    <button class="w-20 flex justify-center items-center text-sm text-white rounded cursor-pointer py-1.5
                                        bg-rose-500 hover:bg-rose-400" x-show="showConfirm" type="submit">確認</button>
                    {!! Form::close() !!}
                </td>
            </tr>
            @endforeach
            @endif
            @endforeach
        </tbody>
    </table>
</div>
@endsection

@section('scripts')
@endsection