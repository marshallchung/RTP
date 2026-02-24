@extends('resultIII.layouts.static-page-base')

@section('page-content')
    <div class="section m-0 bg-transparent">
        <div class="container clearfix">
            <h2><a href="{{ route('resultIII.overview', $overviewType) }}">{{ $overviewType }}</a> > {{ $staticPage->user->full_county_name }}</h2>
            <div class="p-3" style="border: lightgrey 2pt dashed">
                {!! $staticPage->content !!}
            </div>
        </div>
    </div>
@endsection
