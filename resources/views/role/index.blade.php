@extends('layouts.app')

@section('title', '角色管理')

@section('content')
<div class="mt-3 pb-3">
    <h1>角色管理</h1>
    <div class="relative flex flex-col bg-white border rounded">
        <div class="flex-auto p-5">
            <h2>角色清單</h2>
            <a href="{{ route('role.create') }}"
                class="px-4 text-sm text-white rounded cursor-pointer py-1.5 bg-mainCyanDark hover:bg-teal-400">
                <i class="fa fa-plus-circle" aria-hidden="true"></i> 新增角色
            </a>
            <div class="table-responsive mt-1">
                <table class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th class="p-2 font-normal text-left border-r last:border-r-0">角色</th>
                            <th class="p-2 font-normal text-left border-r last:border-r-0">保護</th>
                            <th class="p-2 font-normal text-left border-r last:border-r-0">操作</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($roles as $role)
                        <tr>
                            <td class="p-2 border-r last:border-r-0">
                                {{ $role->display_name }}（{{ $role->name }}）<br />
                                <small>
                                    <i class="fa fa-angle-double-right" aria-hidden="true"></i> {{ $role->description }}
                                </small>
                            </td>
                            <td class="p-2 text-center border-r last:border-r-0">
                                @if($role->protection)
                                <i class="fa fa-check fa-2x text-success" aria-hidden="true"></i>
                                @endif
                            </td>
                            <td class="p-2 border-r last:border-r-0">
                                <a href="{{ route('role.edit', $role) }}"
                                    class="px-4 text-sm text-white rounded cursor-pointer py-1.5 bg-mainCyanDark hover:bg-teal-400">
                                    <i class="w-3 h-3 i-fa6-solid-pen-to-square" aria-hidden="true"></i> 編輯角色
                                </a>
                                @unless($role->protection)
                                {!! Form::open([
                                'method' => 'DELETE',
                                'route' => ['role.destroy', $role],
                                'style' => 'display: inline',
                                'onSubmit' => "return confirm('確定要刪除此角色嗎？');"
                                ]) !!}
                                <button type="submit" class="btn btn-danger">
                                    <i class="fa fa-trash" aria-hidden="true"></i> 刪除角色
                                </button>
                                {!! Form::close() !!}
                                @endunless
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <hr />
            <h2>權限清單</h2>
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead>
                        <tr style="text-align: center">
                            <th class="single line">權限節點</th>
                            @foreach($roles as $role)
                            <th class="single line">
                                {{ $role->display_name }}
                            </th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($permissions as $permission)
                        <tr>
                            <td class="p-2 border-r last:border-r-0">
                                {{ $permission->display_name }}（{{ $permission->name }}）<br />
                                <small>
                                    <i class="fa fa-angle-double-right" aria-hidden="true"></i> {{
                                    $permission->description }}
                                </small>
                            </td>
                            @foreach($roles as $role)
                            <td class="text-center" style="text-align: center">
                                @if($permission->hasRole($role->name))
                                <i class="fa fa-check fa-2x text-success" aria-hidden="true"></i>
                                @endif
                            </td>
                            @endforeach
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection