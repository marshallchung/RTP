@extends('admin.layouts.dashboard', [
'heading' => '編輯宣導影片及文宣專區',
'breadcrumbs' => [
['宣導影片及文宣專區', route('admin.video.index')],
'編輯'
]
])

@section('title', $video->title)

@section('inner_content')
<div x-data="{
    showDeleteModel:false,
}" class="flex flex-row items-start justify-start w-full">
    {!! Form::model($video, ['method' => 'PUT', 'files' => true, 'route' => ['admin.video.update',
    $video->id],'class'=>'flex
    flex-row w-full items-start justify-start px-4 px-4']) !!}
    @include('admin.video.partials.form', ['showDelete' => true, 'files' => $video->files])
    {!! Form::close() !!}

    {!! Form::model($video, ['method' => 'DELETE', 'route' => ['admin.video.destroy', $video->id]]) !!}
    @include('admin.layouts.partials.genericDeleteForm')
    {!! Form::close() !!}
</div>
@endsection