@extends('admin.layouts.dashboard', [
'heading' => '推動韌性社區 > 執行情形管理',
'breadcrumbs' => [
['執行情形管理', route('admin.dc-schedules.index')],
'新增'
]
])

@section('title', '新增執行情形管理')

@section('inner_content')
<div x-data="{
    showDeleteModel:false,
}" class="flex flex-row items-start justify-start w-full">
    {!! Form::open(['method' => 'POST', 'route' => 'admin.dc-schedules.store', 'files' => true,'class'=>'flex flex-row
    w-full items-start justify-start px-4']) !!}
    @include('admin.layouts.partials.genericPostForm', ['files' => true])
    {!! Form::close() !!}
</div>
@endsection