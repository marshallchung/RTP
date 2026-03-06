@extends('admin.layouts.dashboard', [
'heading' => '編輯最新消息(民眾版)',
'breadcrumbs' => [
['最新消息(民眾版)', route('admin.public-news.index')],
'編輯'
]
])

@section('title', $publicNews->title)

@section('inner_content')
<div x-data="{
    showDeleteModel:false,
}" class="flex flex-row items-start justify-start w-full">
    {!! Form::model($publicNews, ['method' => 'PUT', 'files' => true, 'route' => ['admin.public-news.update',
    $publicNews->id],'class'=>'flex
    flex-row w-full items-start justify-start px-4 px-4']) !!}
    @include('admin.public-news.partials.form', ['showDelete' => true, 'files' => $publicNews->files])
    {!! Form::close() !!}

    {!! Form::model($publicNews, ['method' => 'DELETE', 'route' => ['admin.public-news.destroy', $publicNews->id]]) !!}
    @include('admin.layouts.partials.genericDeleteForm')
    {!! Form::close() !!}
</div>
@endsection