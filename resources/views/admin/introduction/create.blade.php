@extends('admin.layouts.dashboard', [
'heading' => '新增簡介',
'breadcrumbs' => [
['簡介', route('admin.introduction.index')],
'新增'
]
])

@section('title', '新增簡介')

@section('inner_content')
<div x-data="{
    showDeleteModel:false,
}" class="flex flex-row items-start justify-start w-full">
    {!! Form::open(['method' => 'POST', 'route' => 'admin.introduction.store', 'files' => true,'class'=>'flex flex-row
    w-full items-start justify-start px-4']) !!}
    @include('admin.layouts.partials.genericPostForm', ['files' => true, 'introductionTypes' => $introductionTypes])
    {!! Form::close() !!}
</div>
@endsection