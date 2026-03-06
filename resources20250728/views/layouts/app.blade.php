<!DOCTYPE html>
<html lang="zh-Hant-TW">

<head>
    {{-- Metatag --}}
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="referrer" content="strict-origin-when-cross-origin" />
    <meta property="og:title" content="@yield('title') - {{ config('app.cht_name') }}">
    <meta property="og:url" content="{{ URL::current() }}">
    <meta property="og:image" content="{{ asset('image/logo.jpg') }}">
    <meta property="og:description" content="{{ config('app.cht_name') }}">

    <link rel="icon" type="image/ico" href="{{ asset('favicon.ico') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('apple-touch-icon.png') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('favicon-16x16.png') }}">
    <link rel="manifest" href="{{ asset('site.webmanifest') }}">

    <title>@yield('title') - {{ config('app.cht_name') }}</title>

    {{-- CSRF Token --}}
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @vite(['resources/css/app.css'])
    @livewireStyles(['nonce' => csp_nonce()])
    @yield('css')
</head>

<body x-data="{scrollTop:0}" @scroll.window="scrollTop=window.pageYOffset" x-init="$nextTick(() => {
    window.Laravel = {'csrfToken':'{{ csrf_token() }}'};
    scrollTop=window.pageYOffset;
     })">
    <div class="flex flex-col items-center justify-start w-screen min-h-screen mx-auto" id="app">
        {{-- Navbar --}}
        @include('components.navbar')

        {{-- Before Container --}}
        @yield('beforeContainer')

        {{-- Content --}}
        <div class="@yield('container_class', 'container') block flex-grow px-4 lg:px-0" id="app1">
            <button type="button" @click="window.scrollTo({top: 0, behavior: 'smooth'})" x-show="scrollTop>300"
                x-transition.duration.500ms
                class="fixed z-50 items-center justify-center hidden w-20 h-20 text-white border border-white rounded-full bg-mainBlue hover:bg-mainBlueDark bottom-44 right-8"
                :class="{'flex':scrollTop>300,'hidden':scrollTop<=300}">
                <span class="text-2xl ">Top</span>
            </button>
            <a href="https://www.facebook.com/groups/bousaiTW" x-transition.duration.500ms target="_blank"
                class="fixed z-50 flex items-center justify-center w-20 h-20 bg-white rounded-full text-mainBlue hover:text-mainBlueDark bottom-20 right-8">
                <i class="w-full h-full i-fa6-brands-facebook"></i>
            </a>
            @include('flash::message')
            @yield('content')
        </div>

        {{-- Footer --}}
        @include('components.footer')
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
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
        }, false);
    </script>
    @yield('js')
    @livewireScripts(['nonce'=> csp_nonce()])
    @vite('resources/js/app.js')
    <script src="https://www.google.com/recaptcha/api.js" async defer>
    </script>
</body>

</html>