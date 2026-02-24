@extends('admin.layouts.dashboard', [
'heading' => '近期重點工作',
'breadcrumbs' => [
'首頁'
]
])

@section('title', '近期重點工作')

@section('inner_content')
<div class="flex flex-row flex-wrap w-full p-4">
    <div class="relative w-full mb-6 bg-white border border-gray-200 rounded-sm">
        <div
            class="relative flex flex-row items-center justify-between px-4 py-2.5 border-b-2 border-gray-200 bg-mainLight1 text-mainAdminTextGrayDark">
            <span class="my-0 text-sm text-mainAdminGrayDark">最新消息</span>
            @include('admin.pagination.simple', ['paginator' => $news])
        </div>
        <ul class="list-group">
            @forelse ($news as $newsItem)
            <li class="relative block px-5 py-8 bg-white border border-t-0 rounded-none border-x-0">
                <h5 class="pb-2"><strong>{{ $newsItem->title }}</strong></h5>
                <p class="text-xs text-mainAdminTextGray">
                    發表於{{ $newsItem->author->name }} {{$newsItem->created_at->format('Y-m-d') }}</p>
                <div class="text-xs default-list">
                    {!! $newsItem->content !!}
                </div>
                @if (count($newsItem->files))
                <h6 class="mt-3 text-sm"><strong>附件檔</strong></h6>
                @foreach ($newsItem->files as $file)
                <div class="w-full p-3 mt-2 text-xs rounded bg-mainLight text-mainBlueDark hover:text-cyan-700"><a
                        href="{{ $file->path }}">{{
                        $file->name }}</a></div>
                @endforeach
                @endif
            </li>
            @empty
            <li class="first:border-t-0 border-x-0 rounded-none relative block py-3 px-5 -mb-[1px] bg-white border">
                無消息</li>
            @endforelse
        </ul>
    </div>
</div>
@stop