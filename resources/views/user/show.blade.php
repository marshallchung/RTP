@extends('layouts.app')

@section('title', "{$user->name} - 會員資料")

@section('css')
<style>
    #gravatar:hover {
        border: 1px dotted black;
    }
</style>
@endsection

@section('content')
<div class="row justify-center mt-3 pb-3">
    <div class="col-md-8">
        <a href="{{ route('user.index') }}"
            class="text-white bg-mainGrayDark border-mainTextGrayDark rounded-md py-2 px-4">
            <i class="fa fa-arrow-left" aria-hidden="true"></i> 會員清單
        </a>
        <h1>{{ $user->name }} - 會員資料</h1>
        <div class="relative flex flex-col bg-white border rounded">
            <div class="card-body text-center">
                {{-- Gravatar大頭貼 --}}
                <img src="{{ Gravatar::src($user->email, 200) }}" class="img-thumbnail" id="gravatar"
                    title="Gravatar大頭貼" />
            </div>
            <div class="flex-auto p-5">
                <dl class="flex flex-row flex-wrap" style="font-size: 120%">
                    <dt class="col-4 col-md-3">名稱</dt>
                    <dd class="col-8 col-md-9">{{ $user->name }}</dd>

                    <dt class="col-4 col-md-3">Email</dt>
                    <dd class="col-8 col-md-9">
                        {{ $user->email }}
                        @if (!$user->isConfirmed)
                        <i class="fa fa-exclamation-triangle text-danger" aria-hidden="true" title="尚未完成信箱驗證"></i>
                        @endif
                    </dd>

                    <dt class="col-4 col-md-3">角色</dt>
                    <dd class="col-8 col-md-9">
                        @foreach($user->roles as $role)
                        {{ $role->display_name }}<br />
                        @endforeach
                    </dd>

                    <dt class="col-4 col-md-3">註冊時間</dt>
                    <dd class="col-8 col-md-9">{{ $user->register_at }}</dd>

                    <dt class="col-4 col-md-3">註冊IP</dt>
                    <dd class="col-8 col-md-9">{{ $user->register_ip }}</dd>

                    <dt class="col-4 col-md-3">最後登入時間</dt>
                    <dd class="col-8 col-md-9">{{ $user->last_login_at }}</dd>

                    <dt class="col-4 col-md-3">最後登入IP</dt>
                    <dd class="col-8 col-md-9">{{ $user->last_login_ip }}</dd>
                </dl>
            </div>
            <div class="card-body text-center">
                <a href="{{ route('user.edit', $user) }}"
                    class="px-4 text-sm text-white rounded cursor-pointer py-1.5 bg-mainCyanDark hover:bg-teal-400">
                    <i class="w-3 h-3 i-fa6-solid-pen-to-square" aria-hidden="true"></i> 編輯資料
                </a>
                {!! Form::open(['route' => ['user.destroy', $user], 'style' => 'display: inline', 'method' => 'DELETE',
                'onSubmit' => "return confirm('確定要刪除此會員嗎？');"]) !!}
                <button type="submit" class="btn btn-danger">
                    <i class="fa fa-trash" aria-hidden="true"></i> 刪除會員
                </button>
                {!! Form::close() !!}
            </div>
        </div>
    </div>
</div>
@endsection