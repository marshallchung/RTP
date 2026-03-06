<!DOCTYPE html>
<!--[if IE 8]>
<html class="ie8"> <![endif]-->
<!--[if IE 9]>
<html class="ie9 gt-ie8"> <![endif]-->
<!--[if gt IE 9]><!-->
<html class="gt-ie8 gt-ie9 not-ie" lang="en">
<!--<![endif]-->

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="referrer" content="strict-origin-when-cross-origin" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <title>內政部消防署 &ndash; @yield('title')</title>

    <link rel="icon" type="image/ico" href="{{ asset('favicon.ico') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('apple-touch-icon.png') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('favicon-16x16.png') }}">
    <link rel="manifest" href="{{ asset('site.webmanifest') }}">
    @vite(['resources/css/app.css'])
    @livewireStyles(['nonce' => csp_nonce()])

    @yield('styles')
</head>

<body class="theme-admin">
    <div x-data="{
    openMMC:true,
    toggleMMC(){
        this.openMMC=!this.openMMC;
    },
}" class="relative w-screen min-h-screen bg-mainLight text-mainAdminTextGrayDark" x-init="$nextTick(() => {
    @if (Auth::user() && Auth::user()->change_default)
    document.addEventListener('click', function(event) {
        console.log(event.target.tagName);
        if((event.target.tagName=='A' || event.target.tagName=='SPAN') && event.target.innerText!=='改密碼'){
            event.preventDefault();
            event.stopPropagation();
            alert('請先變更預設密碼');
        }
    }, true);
    @endif
    })">
        @yield('content')
    </div>
    @yield('scripts')
    @livewireScripts(['nonce'=> csp_nonce()])
    @vite('resources/js/app.js')
    <script src="https://www.google.com/recaptcha/api.js" async defer>
    </script>
</body>

</html>