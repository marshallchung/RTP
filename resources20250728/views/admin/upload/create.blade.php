@extends('admin.layouts.dashboard', [
'heading' => '上傳計畫規範與相關資料',
'breadcrumbs' => [
['計畫規範與相關資料', route('admin.uploads.index')],
'上傳'
]
])

@section('title', '上傳檔案')

@section('inner_content')
<div class="flex flex-row items-start justify-start w-full px-4 py-8">
    {!! Form::open(['method' => 'POST', 'route' => 'admin.uploads.store', 'files' => true,'class'=>'flex flex-row w-full
    items-start justify-start']) !!}
    @include('admin.upload.partials.form')
    {!! Form::close() !!}
</div>
@endsection