@extends('admin.layouts.dashboard', [
'heading' => '培訓資源',
'breadcrumbs' => [
['培訓資源', route('admin.dp-resources.index')],
'新增'
]
])

@section('title', '新增培訓資源')

@section('inner_content')
<div class="flex flex-row flex-wrap">
    {!! Form::open(['method' => 'POST', 'route' => 'admin.dp-resources.store', 'files' => true,'class'=>'flex flex-row
    w-full
    items-start justify-start']) !!}
    @include('admin.dp-resources.partials.form')
    {!! Form::close() !!}
</div>
@endsection