@extends('admin.layouts.dashboard', [
'heading' => '相關資源與連結 > 新增檔案',
'breadcrumbs' => [
['消息', route('admin.frontDownload.index')],
'新增'
]
])

@section('title', '新增消息')

@section('inner_content')
<div x-data="{
    showDeleteModel:false,
}" class="flex flex-row items-start justify-start w-full">
    {!! Form::open(['method' => 'POST', 'route' => 'admin.frontDownload.store', 'files' => true,'class'=>'flex flex-row
    w-full items-start justify-start px-4']) !!}
    @include('admin.layouts.partials.genericPostForm', ['files' => true,'editableCategory'=>$editableCategory])
    {!! Form::close() !!}
</div>
@endsection