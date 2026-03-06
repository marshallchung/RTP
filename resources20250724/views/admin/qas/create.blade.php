@extends('admin.layouts.dashboard', [
'heading' => 'QA專區 > 新增',
'breadcrumbs' => [
['QA', route('admin.qas.index')],
'新增'
]
])

@section('title', '新增')

@section('inner_content')
<div x-data="{
    showDeleteModel:false,
}" class="flex flex-row items-start justify-start w-full">
    {!! Form::open(['method' => 'POST', 'route' => 'admin.qas.store', 'files' => true,'class'=>'flex flex-row w-full
    items-start justify-start px-4']) !!}
    @include('admin.layouts.partials.genericPostForm', [
    'files' => true,
    'titleAttr' => '問題',
    'contentAttr' => '回答',
    'sort' => $sorts,
    'showPublishCheckbox' => true,
    ])
    {!! Form::close() !!}
</div>
@endsection