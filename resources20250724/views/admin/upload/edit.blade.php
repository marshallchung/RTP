@extends('admin.layouts.dashboard', [
'heading' => '編輯檔案',
'breadcrumbs' => [
['檔案', route('admin.uploads.index')],
'編輯'
]
])

@section('title', $upload->name)

@section('inner_content')
<div x-data="{
    showDeleteModel:false,
}" class="flex flex-row items-start justify-start w-full">
    {!! Form::model($upload, ['method' => 'PUT', 'files' => true, 'route' => ['admin.uploads.update',
    $upload->id],'class'=>'flex flex-row w-full items-start justify-start px-4']) !!}
    @include('admin.upload.partials.form', [
    'showPosition' => true,
    'showDelete' => true,
    'customEditPermission' => 'create-uploads',
    'upload' => $upload,
    ])
    {!! Form::close() !!}

    {!! Form::model($upload, ['method' => 'DELETE', 'route' => ['admin.uploads.destroy', $upload->id]]) !!}
    @include('admin.layouts.partials.genericDeleteForm')
    {!! Form::close() !!}
</div>
@endsection