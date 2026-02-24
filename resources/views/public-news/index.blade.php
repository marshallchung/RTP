@extends('layouts.app')

@section('title', '最新消息')
@section('subtitle', '最新消息')

@section('css')
@endsection

@section('container_class', 'flex flex-nowrap text-center justify-between w-full pr-4 pl-4 ml-auto mr-auto flex
item-center justify-between flex-nowrap w-full px-4 mx-auto')
@section('content')
<div x-data="" class="flex flex-row flex-wrap items-start justify-center w-full" x-init="$nextTick(() => {
    })">
    <div class="flex flex-col items-center justify-center w-full max-w-6xl pb-12">
        <div class="flex flex-row flex-wrap items-center justify-center w-full pb-8">
            <a href="/introduction/news?sort="
                class="px-4 min-w-[6rem] h-11 first:rounded-l-md last:rounded-r-md border flex justify-center items-center {{ empty($filteredSort)?'bg-lime-600 text-white':'bg-white text-mainAdminTextGrayDark' }}">
                全部類別</a>
            @foreach($sorts as $sort)
            <a href="/introduction/news?sort={{ urlencode($sort) }}"
                class="px-4 min-w-[6rem] h-11 first:rounded-l-md last:rounded-r-md border flex justify-center items-center {{ $filteredSort===$sort?'bg-lime-600 text-white':'bg-white text-mainAdminTextGrayDark' }}">
                {{ $sort }}
            </a>
            @endforeach
        </div>
        <div class="flex flex-col items-start justify-start flex-1 w-full space-y-8 sm:flex-row sm:space-y-0">
            <div class="flex flex-col items-start justify-start w-full sm:flex-1">
                @forelse ($data as $item)
                <div
                    class="relative flex flex-col w-full px-4 py-2 my-2 space-y-2 border rounded bg-mainLight text-mainAdminTextGrayDark">
                    <div class="flex flex-row items-center justify-start">
                        <span class="p-1 text-xs text-white rounded-full bg-mainGrayDark whitespace-nowrap">{{
                            $item->sort }}</span>
                        <span class="flex-1 px-2 text-sm text-right text-mainAdminTextGrayDark">{{ $item->author->name
                            }}
                            發表於 {{$item->created_at->format('Y-m-d') }}</span>
                        <span class="flex items-center justify-center ml-1 space-x-1 text-sm"><i
                                class="i-fa6-solid-eye"></i>
                            <span>
                                {{ $item->counter_count }} 瀏覽
                            </span>
                        </span>
                    </div>
                    <h5 class="text-left text-mainBlueDark">
                        <strong>{{ link_to_route('introduction.public-news.show', $item->title, $item)
                            }}</strong>
                    </h5>
                    {{-- <div class="text-xs card-body">--}}
                        {{-- @include('layouts.partials.social-share-buttons', [--}}
                        {{-- 'shareText' => $item->title,--}}
                        {{-- 'shareUrl' => route('introduction.public-news.show', $item)--}}
                        {{-- ])--}}
                        {{-- {!! $item->content !!}--}}
                        {{-- @if (count($item->files))--}}
                        {{-- <strong>附件檔</strong>--}}
                        {{-- @foreach ($item->files as $file)--}}
                        {{-- <div class="text-xs text-gray-400 card-footer">--}}
                            {{-- <a href="{{ url(str_replace( '\\', '/', $file->path)) }}">{{ $file->name }}</a>--}}
                            {{-- </div>--}}
                        {{-- @endforeach--}}
                        {{-- @endif--}}
                        {{-- </div>--}}
                </div>
                @empty
                <div
                    class="relative flex flex-col w-full px-4 py-2 my-2 border rounded bg-mainLight text-mainAdminTextGrayDark">
                    無消息</div>
                @endforelse
                @include('pagination.simple', ['paginator' => $data])
            </div>
            <div
                class="w-full px-0 sm:w-[22rem] sm:pl-8 flex items-center justify-center flex-col sm:justify-start space-y-8 sm:items-start">
                <div class="relative flex flex-col w-full bg-white border rounded" style="margin:10px 0 10px 0">
                    <div class="p-4 bg-mainLight text-mainAdminTextGrayDark">
                        防災士。防災事
                    </div>
                    <a href="https://www.facebook.com/groups/bousaiTW" target="_blank" class="w-full">
                        <img src="/image/fb_link.jpg" class="object-cover w-full h-auto">
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('js')
@endsection