@extends('admin.layouts.dashboard', [
'heading' => '推動韌性社區 > 執行情形管理',
'breadcrumbs' => [
['執行情形管理', route('admin.dc-schedules.index')],
'編輯'
]
])

@section('title', $schedule->title)

@section('inner_content')
<div x-data="{
    showDeleteModel:false,
}" class="flex flex-row items-start justify-start w-full">
    {!! Form::model($schedule, ['method' => 'PUT', 'files' => true, 'route' => ['admin.dc-schedules.update',
    $schedule->id],'class'=>'flex flex-row w-full items-start justify-start px-4']) !!}
    @include('admin.layouts.partials.genericPostForm', ['showDelete' => true, 'files' => $schedule->files])
    {!! Form::close() !!}

    {!! Form::model($schedule, ['method' => 'DELETE', 'route' => ['admin.dc-schedules.destroy', $schedule->id]]) !!}
    @include('admin.layouts.partials.genericDeleteForm')
    {!! Form::close() !!}
</div>
@endsection