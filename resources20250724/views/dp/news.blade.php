@extends('layouts.app')

@section('title', $page->title)

@section('content')
<div class="flex flex-row w-full">
    <div class="w-full">
        <div>
            <h3>{{ $page->title }}</h3>
            @include('pagination.simple', ['paginator' => $news])
        </div>

        @forelse ($data as $item)
        <div class="relative flex flex-col bg-white border rounded" style="margin:10px 0 10px 0">
            <div class="card-header text-xs">
                <h5><strong>{{ $item->title }}</strong></h5>
                <p class="card-text text-xs text-secondary">
                    {{ $item->author->name }} 發表於 {{$item->created_at->format('Y-m-d') }}</p>
            </div>
            <div class="card-body text-xs">
                {!! $item->content !!}
                @if (count($item->files))
                <strong>附件檔</strong>
                @foreach ($item->files as $file)
                <div class="text-gray-400 card-footer text-xs">
                    <a href="{{ $file->path }}">{{ $file->name }}</a>
                </div>
                @endforeach
                @endif
            </div>
        </div>
        @empty
        <div class="relative flex flex-col bg-white border rounded">無消息</div>
        @endforelse
    </div>
</div>
@endsection

@section('js')

@endsection
