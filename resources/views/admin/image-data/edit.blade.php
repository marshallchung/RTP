@extends('admin.layouts.dashboard', [
'heading' => '編輯圖資',
'breadcrumbs' => [
'成果網功能',
['防救災圖資', route('admin.image-data.index')],
'編輯圖資'
]
])

@section('title', '編輯圖資')

@section('inner_content')
<div class="flex flex-row flex-wrap">
    {!! Form::model($imageDatum, ['method' => 'PUT', 'files' => true, 'route' => ['admin.image-data.update',
    $imageDatum->id]]) !!}
    @include('admin.image-data.partials.form', ['showDelete' => true])
    {!! Form::close() !!}

    {!! Form::model($imageDatum, ['method' => 'DELETE', 'route' => ['admin.image-data.destroy', $imageDatum->id]]) !!}
    @include('admin.layouts.partials.genericDeleteForm')
    {!! Form::close() !!}
</div>
@endsection