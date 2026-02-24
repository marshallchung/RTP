@extends('admin.layouts.dashboard', [
'heading' => '編輯簡介',
'breadcrumbs' => [
['簡介', route('admin.introduction.index')],
'編輯'
]
])

@section('title', $introduction->title)

@section('inner_content')
<div x-data="{
    showDeleteModel:false,
}" class="flex flex-row items-start justify-start w-full">
    {!! Form::model($introduction, ['method' => 'PUT', 'files' => true, 'route' => ['admin.introduction.update',
    $introduction->id],'class'=>'flex flex-row w-full items-start justify-start px-4']) !!}
    @include('admin.layouts.partials.genericPostForm', ['showDelete' => true, 'files' => $introduction->files,
    'introductionTypes' => $introductionTypes])
    {!! Form::close() !!}

    {!! Form::model($introduction, ['method' => 'DELETE', 'route' => ['admin.introduction.destroy', $introduction->id]])
    !!}
    @include('admin.layouts.partials.genericDeleteForm')
    {!! Form::close() !!}
</div>
@endsection