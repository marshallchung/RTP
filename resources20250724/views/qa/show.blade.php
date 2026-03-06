@extends('layouts.app')

@section('title', "{$qa->title} - {$qa->sort}")

@section('content')
<div class="flex flex-col pb-3 mt-3 space-y-2">
    <h2>{{ $qa->title }}</h2>
    <div class="flex flex-row items-center justify-start space-x-2">
        <a href="{{ route('qa.index') }}"
            class="flex items-center justify-center px-4 py-2 text-white rounded-md bg-mainGrayDark border-mainTextGrayDark">
            <i class="w-4 h-4 mr-1 i-fa6-solid-arrow-left"></i>
            <span>QA 專區</span>
        </a>
        <a href="{{ route('qa.index', ['sort' => $qa->sort]) }}"
            class="flex items-center justify-center px-4 py-2 text-white rounded-md bg-mainGrayDark border-mainTextGrayDark">
            <i class="w-4 h-4 mr-1 i-fa6-solid-arrow-left"></i>
            <span>{{ $qa->sort }}</span>
        </a>
    </div>
    <div class="mt-1 card">
        <div class="flex-auto p-5 border rounded">
            {!! $qa->content !!}
        </div>
    </div>
    @if($qa->files->count())
    <div class="mt-2 card">
        <div class="first:rounded-t-[calc(.25rem - 1px)] py-3 px-5 mb-0 bg-gray-100 border-b-black/10">
            附加檔案
        </div>
        <ul class="flex flex-col pl-0 mb-0">
            @foreach($qa->files as $file)
            <li class="first:border-t-0 border-x-0 rounded-none relative block py-3 px-5 -mb-[1px] bg-white border">
                <a href="{{ url($file->file_path) }}" target="_blank">
                    <i class="i-fa6-solid-file" aria-hidden="true"></i> {{ $file->name }}
                </a>
            </li>
            @endforeach
        </ul>
    </div>
    @endif
</div>
@endsection