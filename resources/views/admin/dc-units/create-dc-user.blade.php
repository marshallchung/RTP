@extends('admin.layouts.dashboard', [
'heading' => '推動韌性社區 > 查詢與管理韌性社區資料 > ' . ($dcUnit->dcUser ? '編輯帳號' : '建立帳號'),
'breadcrumbs' => [
['查詢與管理韌性社區資料', route('admin.dc-units.index')],
($dcUnit->dcUser ? '編輯帳號' : '建立帳號')
]
])

@section('title', '查詢與管理韌性社區資料')

@section('inner_content')
<div class="flex flex-row items-start justify-start w-full">
    <div class="flex-1 max-w-2xl p-5">
        <div class="relative mb-6 bg-white border border-gray-200 rounded-sm">
            <div class="relative px-5 pt-3 pb-2 bg-gray-100 border-b-2 border-gray-200 text-mainAdminTextGrayDark">新增
            </div>
            <div class="p-5 m-0 bg-white">
                {!! Form::open(['route' => ['admin.dc-units.store-dc-user', $dcUnit], 'method' =>
                'post','class'=>'w-full flex flex-col space-y-4']) !!}
                {!! Form::model($dcUnit->dcUser) !!}
                <div class="flex flex-row flex-wrap text-center">
                    {!! Form::label('name', '社區名稱') !!}
                    <p class="form-control-static">{{ $dcUnit->name }}</p>
                </div>
                <div class="flex flex-row flex-wrap text-center">
                    {!! Form::label('username', '帳號') !!}
                    @if(!$dcUnit->dcUser || !auth()->user()->type || auth()->user()->type == 'civil')
                    {!! Form::text('username', null, ['class' => 'h-12 px-4 border-gray-300 rounded-md shadow-sm
                    focus:border-cyan-300 focus:ring focus:ring-cyan-200 focus:ring-opacity-50 w-full', 'required']) !!}
                    @else
                    <p class="form-control-static">{{ $dcUnit->dcUser->username }}</p>
                    {!! Form::hidden('username') !!}
                    @endif
                </div>
                <div class="flex flex-row flex-wrap text-center">
                    {!! Form::label('password', '密碼') !!}
                    {!! Form::password('password', ['class' => 'h-12 px-4 border-gray-300 rounded-md shadow-sm
                    focus:border-cyan-300 focus:ring focus:ring-cyan-200 focus:ring-opacity-50 w-full', 'required']) !!}
                </div>
                {!! Form::submit('送出', ['class' => 'w-full cursor-pointer hover:bg-teal-400 text-white flex
                justify-center
                items-center h-10 bg-mainCyanDark
                rounded']) !!}
                {!! Form::close() !!}
            </div>
        </div>
    </div>
</div>
@endsection