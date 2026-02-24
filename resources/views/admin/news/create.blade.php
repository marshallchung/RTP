@extends('admin.layouts.dashboard', [
'heading' => '新增近期重點工作',
'breadcrumbs' => [
['近期重點工作', route('admin.news.index')],
'新增'
]
])

@section('title', '新增近期重點工作')

@section('inner_content')
<div x-data="{
    showDeleteModel:false,
}" class="flex flex-row items-start justify-start w-full">
    {!! Form::open(['method' => 'POST', 'route' => 'admin.news.store', 'files' => true,'class'=>'flex flex-row w-full
    items-start justify-start px-4']) !!}
    @include('admin.layouts.partials.genericPostForm', [
    'files' => true,
    'sort' => $sorts,
    ])
    {!! Form::close() !!}
</div>
@endsection