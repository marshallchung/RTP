@extends('admin.layouts.dashboard', [
'heading' => '新增宣導影片及文宣專區',
'breadcrumbs' => [
['宣導影片及文宣專區', route('admin.video.index')],
'新增'
]
])

@section('title', '新增宣導影片及文宣專區')

@section('inner_content')
<div x-data="{
    showDeleteModel:false,
}" class="flex flex-row items-start justify-start w-full">
    {!! Form::open(['method' => 'POST', 'route' => 'admin.video.store', 'files' => true,'class'=>'flex
    flex-row w-full items-start justify-start px-4 px-4']) !!}
    @include('admin.video.partials.form', [
    'files' => true,
    'sort' => $sorts,
    ])
    {!! Form::close() !!}
</div>
@endsection