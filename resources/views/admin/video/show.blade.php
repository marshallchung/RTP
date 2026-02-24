@extends('admin.layouts.dashboard', [
'heading' => '檢視宣導影片及文宣專區',
'breadcrumbs' => [
['宣導影片及文宣專區', route('admin.video.index')],
'檢視'
]
])

@section('title', $video->title)

@section('inner_content')
<div class="flex flex-row flex-wrap">
    @include('admin.layouts.partials.genericView', ['data' => $video, 'files' => $video->files])
</div>
@endsection