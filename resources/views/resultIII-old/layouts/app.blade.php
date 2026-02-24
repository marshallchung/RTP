<!DOCTYPE html>
<html lang="zh-Hant-TW">

<head>
    {{-- Metatag --}}
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta http-equiv="x-ua-compatible" content="ie=edge">

    <meta property="og:title" content="@yield('title') - {{ config('app.cht_name') }}">
    <meta property="og:url" content="{{ URL::current() }}">
    <meta property="og:image" content="{{ asset('img/hacker.png') }}">
    <meta property="og:description" content="{{ config('app.cht_name') }}">

    <link rel="icon" type="image/ico" href="{{ asset('favicon.ico') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('apple-touch-icon.png') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('favicon-16x16.png') }}">
    <link rel="manifest" href="{{ asset('site.webmanifest') }}">

    <title>@yield('title') - {{ config('app.cht_name') }}</title>

    {{-- CSRF Token --}}
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @yield('css')
</head>

<body x-data="{scrollTop:0}" @scroll.window="scrollTop=window.pageYOffset" x-init="$nextTick(() => {
    window.Laravel = {'csrfToken':'{{ csrf_token() }}'}
     })">
    <div class="relative flex flex-col min-h-screen" id="app">
        {{-- Navbar --}}
        @include('components.navbar')

        {{-- Before Container --}}
        @yield('beforeContainer')

        {{-- Content --}}
        <div class="container relative flex-grow block" id="app1">
            <button type="button" @click="window.scrollTo({top: 0, behavior: 'smooth'})" x-show="scrollTop>300"
                x-transition.duration.500ms
                class="fixed items-center justify-center hidden w-20 h-20 text-white rounded-full bg-mainBlue hover:bg-mainBlueDark bottom-36 right-8"
                :class="{'flex':scrollTop>300,'hidden':scrollTop<=300}">
                <span class="text-lg ">Top</span>
            </button>
            @include('flash::message')
            @yield('content')
        </div>

        {{-- Footer --}}
        @include('components.footer')
    </div>
    <script>
        //CSRF Token
    window.Laravel = @json([
        'csrfToken' => csrf_token(),
    ]);
    </script>
    {{--<script src="{{ asset('js/app.js') }}"></script>--}}
    <script>
        // Google分析
        @if(env('GOOGLE_ANALYSIS'))
        (function (i, s, o, g, r, a, m) {
            i['GoogleAnalyticsObject'] = r;
            i[r] = i[r] || function () {
                (i[r].q = i[r].q || []).push(arguments)
            }, i[r].l = 1 * new Date();
            a = s.createElement(o),
                m = s.getElementsByTagName(o)[0];
            a.async = 1;
            a.src = g;
            m.parentNode.insertBefore(a, m)
        })(window, document, 'script', 'https://www.google-analytics.com/analytics.js', 'ga');
        ga('create', '{{ env('GOOGLE_ANALYSIS') }}', 'auto');
        ga('send', 'pageview');
        @endif
    });
    </script>
    @yield('js')

</body>

</html>