@extends('admin.layouts.dashboard', [
'heading' => '操作教學說明文件 > 新增',
'breadcrumbs' => [
['操作教學說明文件', route('admin.guidance.index')],
'新增'
]
])

@section('title', '新增消息')

@section('inner_content')
<div x-data="{
    showDeleteModel:false,
}" class="flex flex-row items-start justify-start w-full">
    {!! Form::open(['method' => 'POST', 'route' => 'admin.guidance.store', 'files' => true,'class'=>'flex flex-row
    w-full items-start justify-start px-4']) !!}
    @include('admin.layouts.partials.genericPostForm', ['files' => true])
    {!! Form::close() !!}
</div>
@endsection