@extends('admin.layouts.dashboard', [
'heading' => '新增子帳號',
'breadcrumbs' => [
['新增&重設帳號', route('admin.users.reset.index')],
'新增子帳號'
]
])

@section('title', '改密碼')

@section('inner_content')
<div class="relative w-full max-w-3xl p-4 text-content text-mainAdminTextGrayDark">
    <div class="relative flex flex-col w-full mb-6 space-y-4 bg-white border border-gray-200 rounded-sm">
        <div class="relative px-5 pt-3 pb-2 bg-gray-100 border-b-2 border-gray-200 text-mainAdminTextGrayDark">新增子帳號
        </div>
        <div class="flex flex-col w-full p-5 m-0 space-y-4 bg-white">
            {!! Form::open(['route' => ['admin.users.create-alias-user', $user], 'class' => 'flex flex-col w-full
            space-y-4']) !!}
            <div class="flex flex-col">
                {!! Form::label('username', '對應帳號',['class'=>'font-bold']) !!}
                <p class="form-control-static">{{ $user->username }}（{{ $user->name }}）</p>
            </div>
            <div class="flex flex-row flex-wrap text-center">
                {!! Form::label('username', '帳號',['class'=>'font-bold text-base']) !!}
                {!! Form::text('username', null, ['class' => 'h-12 px-4 border-gray-300 rounded-md shadow-sm
                focus:border-cyan-300 focus:ring focus:ring-cyan-200 focus:ring-opacity-50 w-full', 'required']) !!}
            </div>
            <div class="flex flex-row flex-wrap text-center">
                {!! Form::label('password', '密碼',['class'=>'font-bold text-base']) !!}
                {!! Form::password('password', ['class' => 'h-12 px-4 border-gray-300 rounded-md shadow-sm
                focus:border-cyan-300 focus:ring focus:ring-cyan-200 focus:ring-opacity-50 w-full', 'required']) !!}
            </div>
            <div class="flex flex-row justify-end">
                {!! Form::submit('送出', ['class' => 'px-4 text-sm text-white rounded cursor-pointer py-1.5
                bg-mainCyanDark hover:bg-teal-400 pull-right']) !!}
            </div>
            {!! Form::close() !!}
        </div>
    </div>
    @endsection