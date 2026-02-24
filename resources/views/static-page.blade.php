@extends('layouts.app')

@section('title', $staticPage->title)

@section('content')
<div class="flex flex-row items-start justify-center w-full pb-8">
    <div class="flex flex-col items-center justify-start w-full max-w-screen-lg px-4 py-3">
        <div class="relative flex flex-col bg-white">
            <div class="">
                {!! $staticPage->content !!}
            </div>
        </div>
    </div>
</div>
@endsection