@extends('admin.layouts.dashboard', [
'heading' => '新增計劃規範與相關資料',
'breadcrumbs' => [
['計劃規範與相關資料', route('admin.references.index')],
'新增'
]
])

@section('title', '新增計劃規範與相關資料')

@section('inner_content')
<div x-data="{
    showDeleteModel:false,
}" class="flex flex-row items-start justify-start w-full">
    {!! Form::open(['method' => 'POST', 'route' => 'admin.references.store', 'files' => true,'class'=>'flex flex-row
    w-full items-start justify-start px-4']) !!}
    @include('admin.layouts.partials.genericPostForm', ['files' => true, 'introductionTypes' => $introductionTypes])
    {!! Form::close() !!}
</div>
@endsection