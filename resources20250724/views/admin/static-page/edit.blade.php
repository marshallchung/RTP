@extends('admin.layouts.dashboard', [
'heading' => '編輯靜態頁面',
'breadcrumbs' => [
['靜態頁面', route('admin.static-page.index')],
'編輯'
]
])

@section('title', $staticPage->title)

@section('inner_content')
<div x-data="{
    showDeleteModel:false,
}" class="flex flex-row items-start justify-start w-full">
    {!! Form::model($staticPage, ['method' => 'PUT', 'files' => true, 'route' => ['admin.static-page.update',
    $staticPage->id],'class'=>'flex flex-row w-full items-start justify-start px-4 px-4']) !!}
    @include('admin.static-page.partials.form', ['showDelete' => true])
    {!! Form::close() !!}

    {!! Form::model($staticPage, ['method' => 'DELETE', 'route' => ['admin.static-page.destroy', $staticPage->id]]) !!}
    @include('admin.layouts.partials.genericDeleteForm')
    {!! Form::close() !!}
</div>
@endsection