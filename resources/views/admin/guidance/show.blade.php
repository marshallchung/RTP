@extends('admin.layouts.dashboard', [
'heading' => '操作教學說明文件',
'breadcrumbs' => [
['操作教學說明文件', route('admin.guidance.index')],
'檢視'
]
])

@section('title', $data->title)

@section('inner_content')
<div class="flex flex-row flex-wrap">
	@include('admin.layouts.partials.genericView', ['data' => $data, 'files' => $data->files])
</div>
@endsection