@extends('admin.layouts.dashboard', [
'heading' => '編輯QA',
'breadcrumbs' => [
['QA', route('admin.qas.index')],
'編輯'
]
])

@section('title', $qa->title)

@section('inner_content')
<div x-data="{
    showDeleteModel:false,
}" class="flex flex-row items-start justify-start w-full">
    {!! Form::model($qa, ['method' => 'PUT', 'files' => true, 'route' => ['admin.qas.update', $qa->id],'class'=>'flex
    flex-row w-full items-start justify-start px-4']) !!}
    @include('admin.layouts.partials.genericPostForm', [
    'showDelete' => true,
    'sort' => $sorts,
    'files' => $qa->files,
    'showPublishCheckbox' => true,
    ])
    {!! Form::close() !!}

    {!! Form::model($qa, ['method' => 'DELETE', 'route' => ['admin.qas.destroy', $qa->id]]) !!}
    @include('admin.layouts.partials.genericDeleteForm')
    {!! Form::close() !!}
</div>
@endsection