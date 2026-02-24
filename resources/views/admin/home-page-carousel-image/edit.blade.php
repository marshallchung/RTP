@extends('admin.layouts.dashboard', [
'heading' => '編輯',
'breadcrumbs' => [
['首頁輪播設定', route('admin.home-page-carousel-image.index')],
'編輯'
]
])

@section('title', $homePageCarouselImage->title)

@section('inner_content')
<div x-data="{
    showDeleteModel:false,
}" class="flex flex-row items-start justify-start w-full">
    {!! Form::model($homePageCarouselImage, ['method' => 'PUT', 'files' => true, 'route' =>
    ['admin.home-page-carousel-image.update', $homePageCarouselImage->id],'class'=>'flex
    flex-row w-full items-start justify-start px-4 px-4']) !!}
    @include('admin.home-page-carousel-image.partials.form', ['showDelete' => true, 'files' =>
    $homePageCarouselImage->files])
    {!! Form::close() !!}

    {!! Form::model($homePageCarouselImage, ['method' => 'DELETE', 'route' => ['admin.home-page-carousel-image.destroy',
    $homePageCarouselImage->id]]) !!}
    @include('admin.layouts.partials.genericDeleteForm')
    {!! Form::close() !!}
</div>
@endsection