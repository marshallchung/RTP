@extends('admin.layouts.dashboard', [
'heading' => '編輯相關資源與連結',
'breadcrumbs' => [
['相關資源與連節結', route('admin.frontDownload.index')],
'編輯'
]
])

@section('title', $news->title)

@section('inner_content')
<div x-data="{
    showDeleteModel:false,
}" class="flex flex-row items-start justify-start w-full">
    {!! Form::model($news, ['method' => 'PUT', 'files' => true, 'route' => ['admin.frontDownload.update',
    $news->id],'class'=>'flex flex-row w-full items-start justify-start px-4'])
    !!}
    @include('admin.layouts.partials.genericPostForm', ['showDelete' => true, 'files' => $news->files])
    {!! Form::close() !!}

    {!! Form::model($news, ['method' => 'DELETE', 'route' => ['admin.frontDownload.destroy', $news->id]]) !!}
    @include('admin.layouts.partials.genericDeleteForm')
    {!! Form::close() !!}
</div>
@endsection