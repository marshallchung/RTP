@extends('admin.layouts.dashboard', [
'heading' => '檢視最新消息(民眾版)',
'breadcrumbs' => [
['最新消息(民眾版)', route('admin.public-news.index')],
'檢視'
]
])

@section('title', $publicNews->title)

@section('inner_content')
<div class="flex flex-row flex-wrap">
    @include('admin.layouts.partials.genericView', ['data' => $publicNews, 'files' => $publicNews->files])
</div>
@endsection