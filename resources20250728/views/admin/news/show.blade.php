@extends('admin.layouts.dashboard', [
'heading' => '檢視近期重點工作',
'breadcrumbs' => [
['近期重點工作', route('admin.news.index')],
'檢視'
]
])

@section('title', $news->title)

@section('inner_content')
<div class="flex flex-row flex-wrap">
    @include('admin.layouts.partials.genericView', ['data' => $news, 'files' => $news->files])
</div>
@endsection