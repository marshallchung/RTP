@extends('admin.layouts.dashboard', [
'heading' => '編輯' . $data->title,

])

@section('title', $data->title)

@section('inner_content')
<div x-data="{
    showDeleteModel:false,
}" class="flex flex-row items-start justify-start w-full">
    {!! Form::model($data, ['method' => 'PUT', 'files' => true, 'route' => ['admin.front-introduction.update',
    $data->id],'class'=>'flex flex-row w-full items-start justify-start px-4']) !!}
    @include('admin.layouts.partials.genericPostForm', ['showDelete' => false, 'files' => $data->files, 'noTitle' =>
    true])
    {!! Form::close() !!}

    @include('admin.layouts.partials.genericDeleteForm')
    {!! Form::close() !!}
</div>
@endsection