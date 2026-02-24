@extends('admin.layouts.dashboard', [
'heading' => '推動韌性社區-相關資料 > 編輯',
'breadcrumbs' => [
['推動韌性社區-相關資料', route('admin.dcDownload.index')],
'編輯'
]
])

@section('title', $news->title)

@section('inner_content')
<div x-data="{
    showDeleteModel:false,
}" class="flex flex-row flex-wrap">
    {!! Form::model($news, ['method' => 'PUT', 'files' => true, 'route' => ['admin.dcDownload.update',
    $news->id],'class'=>'flex flex-row flex-1 w-full space-x-4']) !!}
    @include('admin.layouts.partials.genericPostForm', ['showDelete' => true,'editableCategory'=>$editableCategory,
    'files' =>
    $news->files])
    {!! Form::close() !!}

    {!! Form::model($news, ['method' => 'DELETE', 'route' => ['admin.dcDownload.destroy', $news->id]]) !!}
    @include('admin.layouts.partials.genericDeleteForm')
    {!! Form::close() !!}
</div>
@endsection