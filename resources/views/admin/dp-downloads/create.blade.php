@extends('admin.layouts.dashboard', [
'heading' => '防災士培訓-相關資料下載 > 新增',
'breadcrumbs' => [
['防災士培訓-相關資料', route('admin.dpDownload.index')],
'新增'
]
])

@section('title', '新增消息')

@section('inner_content')
<div class="flex flex-row w-full">
    {!! Form::open(['method' => 'POST', 'route' => 'admin.dpDownload.store', 'files' => true,'class'=>'flex flex-row
    flex-1 w-full space-x-4']) !!}
    @include('admin.layouts.partials.genericPostForm', ['files' => true,'editableCategory'=>$editableCategory])
    {!! Form::close() !!}
</div>
@endsection