@extends('layouts.app')

@section('title', '推動韌性社區')
@section('subtitle', '簡介')

@section('content')
<div class="flex items-center justify-center w-full">
    <div class="w-full max-w-4xl">
        {!! $intro->content !!}
    </div>
</div>
@endsection

@section('js')

@endsection