@extends('admin.layouts.dashboard', [
'heading' => '新增圖資',
'breadcrumbs' => [
'成果網功能',
['防救災圖資', route('admin.image-data.index')],
'新增圖資'
]
])

@section('title', '新增圖資')

@section('inner_content')
<div class="flex flex-row flex-wrap">
	{!! Form::open(['method' => 'POST', 'route' => 'admin.image-data.store', 'files' => true]) !!}
	@include('admin.image-data.partials.form')
	{!! Form::close() !!}
</div>
@endsection