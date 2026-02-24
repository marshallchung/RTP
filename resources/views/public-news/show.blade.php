@extends('layouts.app')

@section('title', $publicNews->title)
@section('subtitle', "最新消息")

@section('content')
<div class="flex flex-row items-start justify-center w-full pb-8">
    <div class="flex flex-col items-start justify-start w-full max-w-screen-lg px-4 py-3 space-y-4">
        <h1 class="pb-1">{{ $publicNews->title }}</h1>
        <a href="{{ route('introduction.public-news.index') }}"
            class="flex flex-row items-center justify-center h-10 space-x-1 text-white rounded w-28 bg-mainTextGray">
            <i class="i-fa6-solid-arrow-left" aria-hidden="true"></i>
            <span>最新消息</span>
        </a>
        <div class="relative flex flex-col w-full mt-1 overflow-scroll bg-white border rounded">
            <div class="flex flex-row items-center justify-between p-5">
                @include('layouts.partials.social-share-buttons', [
                'shareText' => $publicNews->title,
                'shareUrl' => route('introduction.public-news.show', $publicNews)
                ])
                <div class="flex items-center justify-center ml-auto text-gray-400">
                    <i class="mr-1 i-fa6-solid-eye"></i>
                    <span>{{ $publicNews->counter_count }} 瀏覽</span>
                </div>
            </div>
            <div class="flex-auto p-5">
                {!! $publicNews->content !!}
            </div>
        </div>
        @if($publicNews->files->count())
        <div class="relative flex flex-col w-full mt-2 bg-white border rounded">
            <div class="first:rounded-t-[calc(.25rem - 1px)] py-3 px-5 mb-0 bg-gray-100 border-b-black/10">
                附加檔案
            </div>
            <ul class="flex flex-col pl-0 mb-0">
                @foreach($publicNews->files as $file)
                <li class="first:border-t-0 border-x-0 rounded-none relative block py-3 px-5 -mb-[1px] bg-white border">
                    <a href="{{ url($file->file_path) }}" target="_blank" class=" text-mainBlueDark">
                        <i class="i-fa6-solid-file" aria-hidden="true"></i> {{ $file->name }}
                    </a>
                </li>
                @endforeach
            </ul>
        </div>
        @endif
    </div>
</div>
@endsection