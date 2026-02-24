@extends('admin.layouts.dashboard', [
'heading' => '新增最新消息(民眾版)',
'breadcrumbs' => [
['最新消息(民眾版)', route('admin.public-news.index')],
'新增'
]
])

@section('title', '新增最新消息(民眾版)')

@section('inner_content')
<div x-data="{
    showDeleteModel:false,
}" class="flex flex-row items-start justify-start w-full">
    {!! Form::open(['method' => 'POST', 'route' => 'admin.public-news.store', 'files' => true,'class'=>'flex flex-row
    w-full items-start justify-start px-4']) !!}
    @include('admin.layouts.partials.genericPostForm', [
    'files' => true,
    'sort' => $sorts,
    ])
    {!! Form::close() !!}
</div>
@endsection