@extends('admin.layouts.dashboard', [
'heading' => '新增績效評估自評表',
'breadcrumbs' => [
['績效評估自評表列表', route('admin.questionnaire.index')],
'新增'
]
])

@section('title', '新增績效評估自評表')

@section('inner_content')
<div x-data="{
    showDeleteModel:false,
}" class="flex flex-row items-start justify-start w-full">
    {!! Form::open(['method' => 'POST', 'route' => 'admin.questionnaire.store', 'files' => true,'class'=>'flex flex-row
    w-full items-start justify-start px-4 px-4']) !!}
    <div class="flex-1 pr-6">
        <div class="relative mb-6 bg-white border border-gray-200 rounded-sm">
            <div class="relative px-5 pt-3 pb-2 bg-gray-100 border-b-2 border-gray-200 text-mainAdminTextGrayDark">新增
            </div>
            <div class="flex flex-col items-start justify-start w-full p-5 m-0 space-y-4 bg-white">
                <div class="flex flex-row flex-wrap w-full text-center">
                    {!! Form::label('title', '問卷名稱') !!}
                    {!! Form::text('title', null, ['class' => 'h-12 px-4 border-gray-300 rounded-md shadow-sm
                    focus:border-cyan-300 focus:ring focus:ring-cyan-200 focus:ring-opacity-50 w-full']) !!}
                </div>
                <div class="flex flex-row flex-wrap w-full text-center">
                    {!! Form::label('title', '問卷類型') !!}
                    {!! Form::select('type', ['4' => '縣市', '5' => '公所', '45' => '縣市及公所'],
                    null, ['class' => 'h-12 px-4 border-gray-300 rounded-md shadow-sm focus:border-cyan-300 focus:ring
                    focus:ring-cyan-200 focus:ring-opacity-50 w-full']) !!}
                </div>
                <div class="flex flex-row flex-wrap w-full text-center">
                    {!! Form::label('title', '開放時間') !!}
                    {!! Form::input('date', 'date_from', date('Y-m-d'), ['class' => 'h-12 px-4 border-gray-300
                    rounded-md shadow-sm focus:border-cyan-300 focus:ring focus:ring-cyan-200 focus:ring-opacity-50
                    w-full js-datepicker']) !!}
                </div>
                <div class="flex flex-row flex-wrap w-full text-center">
                    {!! Form::label('title', '即將逾期通知日期') !!}
                    {!! Form::input('date', 'expire_soon_date', date('Y-m-d', strtotime('+1 days')), ['class' => 'h-12
                    px-4
                    border-gray-300 rounded-md shadow-sm focus:border-cyan-300 focus:ring focus:ring-cyan-200
                    focus:ring-opacity-50 w-full js-datepicker']) !!}
                </div>
                <div class="flex flex-row flex-wrap w-full text-center">
                    {!! Form::label('title', '關閉時間') !!}
                    {!! Form::input('date', 'date_to', date('Y-m-d', strtotime('+1 days')), ['class' => 'h-12 px-4
                    border-gray-300 rounded-md shadow-sm focus:border-cyan-300 focus:ring focus:ring-cyan-200
                    focus:ring-opacity-50 w-full js-datepicker']) !!}
                </div>
                <div class="flex flex-row flex-wrap w-full text-center">
                    {!! Form::label('title', '基本指標加權') !!}
                    {!! Form::input('number', 'basic_weight', 1, ['class' => 'h-12 px-4 border-gray-300 rounded-md
                    shadow-sm focus:border-cyan-300 focus:ring focus:ring-cyan-200 focus:ring-opacity-50 w-full', 'step'
                    => 0.01]) !!}
                </div>
                <div class="flex flex-row flex-wrap w-full text-center">
                    {!! Form::label('title', '進階指標加權') !!}
                    {!! Form::input('number', 'advanced_weight', 1, ['class' => 'h-12 px-4 border-gray-300 rounded-md
                    shadow-sm focus:border-cyan-300 focus:ring focus:ring-cyan-200 focus:ring-opacity-50 w-full', 'step'
                    => 0.01]) !!}
                </div>
            </div>
        </div>
    </div>
    <div class="relative xl:w-25/100 xl:float-left md:w-1/3">
        <div class="relative mb-6 bg-white border border-gray-200 rounded-sm">
            <div class="relative px-5 pt-3 pb-2 bg-gray-100 border-b-2 border-gray-200 text-mainAdminTextGrayDark">發表
            </div>
            <div class="p-5 m-0 bg-white">
                {!! Form::submit('送出', ['class' => 'w-full text-white flex justify-center items-center h-10
                cursor-pointer
                bg-mainCyanDark rounded']) !!}
            </div>
        </div>
    </div>
    {!! Form::close() !!}
</div>
@endsection
@section('scripts')
@endsection