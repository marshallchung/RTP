@extends('admin.layouts.dashboard', [
'heading' => '民眾版網頁管理 > 新增',
'breadcrumbs' => [
['民眾版網頁管理', route('admin.dcDownload.index')],
'新增'
]
])

@section('title', '新增消息')

@section('inner_content')
<div x-data="{
    showDeleteModel:false,
}" class="flex flex-row items-start justify-start w-full">
    {!! Form::open(['method' => 'POST', 'route' => 'admin.dcDownload.store', 'files' => true,'class'=>'flex flex-row
    w-full items-start justify-start px-4']) !!}
    @include('admin.layouts.partials.genericPostForm', ['files' => true,'editableCategory'=>$editableCategory])
    {!! Form::close() !!}
</div>
@endsection