@extends('admin.layouts.dashboard', [
'heading' => '新增',
'breadcrumbs' => [
['首頁輪播設定', route('admin.home-page-carousel-image.index')],
'新增'
]
])

@section('title', '新增')

@section('inner_content')
<div x-data="{
    showDeleteModel:false,
}" class="flex flex-row items-start justify-start w-full">
    {!! Form::open(['method' => 'POST', 'route' => 'admin.home-page-carousel-image.store', 'files' =>
    true,'class'=>'flex flex-row w-full items-start justify-start px-4']) !!}
    @include('admin.home-page-carousel-image.partials.form', ['files' => 'true'])
    {!! Form::close() !!}
</div>
@endsection