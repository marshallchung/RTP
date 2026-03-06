@extends('admin.layouts.dashboard', [
'heading' => '新增防災避難看板',
'breadcrumbs' => [
'成果網功能',
['防災避難看板', route('admin.sign-location.index')],
'新增防災避難看板'
]
])

@section('title', '新增防災避難看板')

@section('inner_content')
<div x-data="{
    showDeleteModel:false,
}" class="flex flex-row items-start justify-start w-full">
    {!! Form::open(['method' => 'POST', 'route' => 'admin.sign-location.store', 'files' => true,'class'=>'flex
    flex-row w-full items-start justify-start px-4 px-4']) !!}
    @include('admin.sign-location.partials.form')
    {!! Form::close() !!}
</div>
@endsection