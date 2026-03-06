@extends('resultIII.layouts.base')

@section('title')
    {{ $pageTitle }} - 三期計畫成果網
@endsection

@section('style')
    <style>
        .custom-page-menu > div:not(:first-child) {
            border-left: 1px solid #F2F2F2;
        }
    </style>
@endsection

@section('content')
    @if(isset($showMenu) && $showMenu)
        <div class="section m-0 bg-transparent">
            <div class="container">
                <div class="custom-page-menu row">
                    @foreach($menu[$showMenu] as $menuItem)
                        <div class="col-lg">
                            <div class="feature-box fbox-center mb-0">
                                <div class="fbox-icon mb-4">
                                    <a href="{{ $menuItem['url'] }}"><img src="{{ $menuItem['icon'] }}"
                                         class="rounded-0 bg-transparent mx-auto" alt="Image" style="width: 52px;"></a>
                                </div>
                                <div class="fbox-content">
                                    <h3 class="fw-medium"><a href="{{ $menuItem['url'] }}">{{ $menuItem['name'] }}</a></h3>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @endif
    @yield('page-content')
@endsection
