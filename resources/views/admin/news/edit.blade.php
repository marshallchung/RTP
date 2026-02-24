@extends('admin.layouts.dashboard', [
'heading' => '編輯近期重點工作',
'breadcrumbs' => [
['近期重點工作', route('admin.news.index')],
'編輯'
]
])

@section('title', $news->title)

@section('inner_content')
<div x-data="{
    showDeleteModel:false,
}" class="flex flex-row items-start justify-start w-full">
    {!! Form::model($news, ['method' => 'PUT', 'files' => true, 'route' => ['admin.news.update',
    $news->id],'class'=>'flex flex-row w-full items-start justify-start px-4 px-4']) !!}
    @include('admin.news.partials.form', ['showDelete' => true, 'files' => $news->files])
    {!! Form::close() !!}

    {!! Form::model($news, ['method' => 'DELETE', 'route' => ['admin.news.destroy', $news->id]]) !!}
    @include('admin.layouts.partials.genericDeleteForm')
    {!! Form::close() !!}
</div>
@endsection