@extends('resultIII.layouts.static-page-base')

@section('page-content')
<div class="section m-0 bg-transparent">
    <div class="container clearfix">
        <h2>{{ $pageTitle ?? '' }} > {{ $topic->title }}</h2>
        <div class="p-3" style="border: lightgrey 2pt dashed">
            <div class="row row-cols-6">
                @foreach($files as $file)
                <div class="col">
                    <a href="{{ url($file['file']->file_path) }}" target="_blank" title="{{ $file['file']->memo }}"
                        class="flex flex-col align-items-center">
                        <i class="far fa-file fa-5x"></i>
                        <p>{{ $file['county_name'] }} - {{ $file['file']->name }}</p>
                    </a>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection