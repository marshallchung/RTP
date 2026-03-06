@extends('admin.layouts.dashboard', [
'heading' => '編輯操作教學說明文件',
'breadcrumbs' => [
['操作教學說明文件', route('admin.guidance.index')],
'編輯'
]
])

@section('title', $data->title)

@section('inner_content')
<div x-data="{
    showDeleteModel:false,
}" class="flex flex-row items-start justify-start w-full">
    {!! Form::model($data, ['method' => 'PUT', 'files' => true, 'route' => ['admin.guidance.update',
    $data->id],'class'=>'flex flex-row w-full items-start justify-start px-4']) !!}
    @include('admin.layouts.partials.genericPostForm', ['showDelete' => true, 'files' => $data->files])
    {!! Form::close() !!}

    {!! Form::model($data, ['method' => 'DELETE', 'route' => ['admin.guidance.destroy', $data->id]]) !!}
    @include('admin.layouts.partials.genericDeleteForm')
    {!! Form::close() !!}
</div>
@endsection