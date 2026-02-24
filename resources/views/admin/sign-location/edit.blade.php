@extends('admin.layouts.dashboard', [
'heading' => '編輯防災避難看板',
'breadcrumbs' => [
'成果網功能',
['防災避難看板', route('admin.sign-location.index')],
'編輯防災避難看板'
]
])

@section('title', '編輯防災避難看板')

@section('inner_content')
<div x-data="{
    showDeleteModel:false,
}" class="flex flex-row items-start justify-start w-full">
    {!! Form::model($signLocation, ['method' => 'PUT', 'files' => true, 'route' => ['admin.sign-location.update',
    $signLocation->id],'class'=>'flex
    flex-row w-full items-start justify-start px-4 px-4']) !!}
    @include('admin.sign-location.partials.form', ['showDelete' => true])
    {!! Form::close() !!}

    {!! Form::model($signLocation, ['method' => 'DELETE', 'route' => ['admin.sign-location.destroy',
    $signLocation->id]]) !!}
    @include('admin.layouts.partials.genericDeleteForm')
    {!! Form::close() !!}
</div>
@endsection