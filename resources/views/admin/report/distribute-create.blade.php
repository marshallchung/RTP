@extends('admin.layouts.dashboard', [
'heading' => '新增' . $data['title'],
])

@section('title', $data['title'])

@section('inner_content')
<div x-data="{
    filterChange(e){
        document.getElementById('filterForm').submit();
    },
 }" class="flex flex-row flex-wrap">
    {!! Form::open(['method'=>'get', 'route' => ['admin.reports.distribute.index'], 'id' => 'filterForm', 'class' =>
    'flex flex-row flex-wrap items-center justify-start xl:w-75/100 md:w-67/100
    gap-[30px] px-8 py-4']) !!}
    <div class="flex items-center flex-1 space-x-2 form-row">
        <div>
            {!! Form::label('year', '年度') !!}
        </div>
        <div class="flex-1">
            {!! Form::select('year', $availableYears, $year,
            ['@change'=>'filterChange','class' => 'h-12 px-4
            border-gray-300 rounded-md shadow-sm focus:border-cyan-300 focus:ring focus:ring-cyan-200
            focus:ring-opacity-50 w-full']) !!}
        </div>
    </div>
    <div class="flex items-center flex-1 space-x-2 form-row">
        <div>
            {!! Form::label('county_id', '縣市') !!}
        </div>
        <div class="flex-1">
            {!! Form::select('county_id', $countyOptions, $county_id,
            ['@change'=>'filterChange','class' => 'h-12 px-4
            border-gray-300 rounded-md shadow-sm focus:border-cyan-300 focus:ring focus:ring-cyan-200
            focus:ring-opacity-50 w-full']) !!}
        </div>
    </div>
    {!! Form::close() !!}
    @if ($data['no_temp'])
    <div class="flex items-center justify-center pt-16 xl:w-75/100 md:w-67/100 form-row">
        <div>
            尚未建立樣板表格
        </div>
    </div>
    @else
    {!! Form::model($data, ['method' => 'POST', 'route' => ['admin.reports.distribute.update'],'class'=>'flex flex-row
    w-full items-start justify-start px-4']) !!}
    <input type="hidden" name="id" value="{{ $data['id'] }}">
    <input type="hidden" name="title" value="{{ $data['title'] }}">
    @include('admin.layouts.partials.genericPostForm', ['dontShowOnlineCheckbox'=>true, 'noTitle'
    =>true])
    {!! Form::close() !!}
    @endif
</div>
@endsection