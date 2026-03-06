<!DOCTYPE html>
<html lang="zh-Hant-TW">

<head>
    <meta http-equiv="content-type" content="text/html; charset=utf-8" />
    <meta name="author" content="SemiColonWeb" />

    <!-- Stylesheets
    ============================================= -->
    <link href="https://fonts.googleapis.com/css?family=Roboto:300,400,400i,700|Istok+Web:400,700&display=swap"
        rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="{{ asset('canvas/css/bootstrap.css') }}" type="text/css" />
    <link rel="stylesheet" href="{{ asset('canvas/style.css') }}" type="text/css" />

    <link rel="stylesheet" href="{{ asset('canvas/css/dark.css') }}" type="text/css" />
    <link rel="stylesheet" href="{{ asset('canvas/css/font-icons.css') }}" type="text/css" />
    <link rel="stylesheet" href="{{ asset('canvas/css/animate.css') }}" type="text/css" />
    <link rel="stylesheet" href="{{ asset('canvas/css/magnific-popup.css') }}" type="text/css" />

    <link rel="stylesheet" href="{{ asset('canvas/css/components/ion.rangeslider.css') }}" type="text/css" />

    <link rel="stylesheet" href="{{ asset('canvas/css/custom.css') }}" type="text/css" />
    <meta name='viewport' content='initial-scale=1, viewport-fit=cover'>

    <!-- Hosting Demo Specific Stylesheet -->
    <link rel="stylesheet" href="{{ asset('canvas/css/colors.php?color=44aaac') }}" type="text/css" />
    <!-- Theme Color -->
    <link rel="stylesheet" href="{{ asset('canvas/demos/hosting/css/fonts.css') }}" type="text/css" />
    <link rel="stylesheet" href="{{ asset('canvas/demos/hosting/hosting.css') }}" type="text/css" />
    <!-- / -->

    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.4/css/all.css" crossorigin="anonymous">

    {{-- Override style --}}
    <style>
        .menu-link {
            font-size: 1rem;
        }
    </style>

    @yield('style')

    {{-- Metatag --}}
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta http-equiv="x-ua-compatible" content="ie=edge">

    <meta property="og:title" content="@yield('title') - {{ config('app.cht_name') }}">
    <meta property="og:url" content="{{ URL::current() }}">
    <meta property="og:image" content="{{ asset('image/logo.png') }}">
    <meta property="og:description" content="{{ config('app.cht_name') }}">

    <link rel="icon" type="image/ico" href="{{ asset('favicon.ico') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('apple-touch-icon.png') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('favicon-16x16.png') }}">
    <link rel="manifest" href="{{ asset('site.webmanifest') }}">

    <!-- Document Title
    ============================================= -->
    <title>@yield('title') - {{ config('app.cht_name') }}</title>

</head>

<body class="stretched">

    <!-- Document Wrapper
============================================= -->
    <div id="wrapper" class="clearfix">

        <!-- Header
    ============================================= -->
        <header id="header" class="full-header @yield('header_class')" data-sticky-class="not-dark"
            data-responsive-class="not-dark">
            <div id="header-wrap">
                <div class="container">
                    <div class="header-row">

                        <!-- Logo
                    ============================================= -->
                        <div id="logo" style="margin-right: 0;">
                            <a href="{{ route('resultIII.index') }}" class="standard-logo"
                                data-dark-logo="{{ asset('image/logo.png') }}"><img src="{{ asset('image/logo.png') }}"
                                    alt="PDMCB Logo"></a>
                            <a href="{{ route('resultIII.index') }}" class="retina-logo"
                                data-dark-logo="{{ asset('image/logo.png') }}"><img src="{{ asset('image/logo.png') }}"
                                    alt="PDMCB Logo"></a>
                        </div><!-- #logo end -->

                        <div class="flex align-items-center" style="margin-right: auto;">
                            <h1 class="mb-0 mx-3">@yield('title')</h1>
                        </div>

                        <div id="primary-menu-trigger">
                            <svg class="svg-trigger" viewBox="0 0 100 100">
                                <path
                                    d="m 30,33 h 40 c 3.722839,0 7.5,3.126468 7.5,8.578427 0,5.451959 -2.727029,8.421573 -7.5,8.421573 h -20">
                                </path>
                                <path d="m 30,50 h 40"></path>
                                <path
                                    d="m 70,67 h -40 c 0,0 -7.5,-0.802118 -7.5,-8.365747 0,-7.563629 7.5,-8.634253 7.5,-8.634253 h 20">
                                </path>
                            </svg>
                        </div>

                        <!-- Primary Navigation
                    ============================================= -->
                        <nav class="primary-menu not-dark with-arrows">

                            <ul class="menu-container not-dark">
                                <li class="menu-item @if(route_is('resultIII.index'))current @endif"><a
                                        class="menu-link" href="{{ route('resultIII.index') }}">
                                        <div>首頁</div>
                                    </a></li>
                                <li class="menu-item mega-menu"><a class="menu-link" href="javascript:void(0)">
                                        <div>深耕概要</div>
                                    </a>
                                    <div class="mega-menu-content mega-menu-style-2 border-top-0">
                                        <div class="container">
                                            <div class="flex flex-row flex-wrap">
                                                @foreach($menu['overview'] as $menuItem)
                                                <ul class="sub-menu-container mega-menu-column col-lg">
                                                    <li class="menu-item">
                                                        <div class="widget">
                                                            <div class="feature-box not-dark fbox-center mb-0">
                                                                <div class="fbox-icon mb-4">
                                                                    <a href="{{ $menuItem['url'] }}"> <img
                                                                            src="{{ $menuItem['icon'] }}"
                                                                            class="rounded-0 bg-transparent mx-auto"
                                                                            alt="Image" style="width: 52px;"> </a>
                                                                </div>
                                                                <div class="fbox-content">
                                                                    <h3 class="fw-medium"><a
                                                                            href="{{ $menuItem['url'] }}">{{
                                                                            $menuItem['name'] }}</a></h3>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </li>
                                                </ul>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                </li>
                                <li class="menu-item mega-menu"><a class="menu-link" href="javascript:void(0)">
                                        <div>成果展示</div>
                                    </a>
                                    <div class="mega-menu-content mega-menu-style-2 border-top-0">
                                        <div class="container">
                                            <div class="flex flex-row flex-wrap">
                                                @foreach($menu['achievement'] as $menuItem)
                                                <ul class="sub-menu-container mega-menu-column col-lg">
                                                    <li class="menu-item">
                                                        <div class="widget">
                                                            <div class="feature-box not-dark fbox-center mb-0">
                                                                <div class="fbox-icon mb-4">
                                                                    <a href="{{ $menuItem['url'] }}"><img
                                                                            src="{{ $menuItem['icon'] }}"
                                                                            class="rounded-0 bg-transparent mx-auto"
                                                                            alt="Image" style="width: 52px;"> </a>
                                                                </div>
                                                                <div class="fbox-content">
                                                                    <h3 class="fw-medium"><a
                                                                            href="{{ $menuItem['url'] }}">{{
                                                                            $menuItem['name'] }}</a></h3>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </li>
                                                </ul>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                </li>
                                <li class="menu-item @if(route_is('resultIII.lookahead'))current @endif"><a
                                        class="menu-link" href="{{ route('resultIII.lookahead') }}">
                                        <div>遠望深耕</div>
                                    </a></li>
                                <li class="menu-item"><a class="menu-link" href="https://bear.emic.gov.tw/MY/#/home/map"
                                        target="_blank" rel="noreferrer noopener">
                                        <div>簡易疏散避難地圖</div>
                                    </a></li>
                                <li class="menu-item"><a class="menu-link"
                                        href="https://pdmcb.nfa.gov.tw/uploads/2022/12/a3b7e3134586ef29443e10f9fea85f41"
                                        target="_blank" rel="noreferrer noopener">
                                        <div>深耕3期成果冊</div>
                                    </a></li>
                            </ul>

                        </nav><!-- #primary-menu end -->

                    </div>
                </div>
            </div>
            <div class="header-wrap-clone"></div>
        </header><!-- #header end -->

        @yield('slider')

        <!-- Content
    ============================================= -->
        <section id="content">
            <div class="content-wrap py-0">
                @yield('content')
            </div>
        </section><!-- #content end -->

        <!-- Footer
    ============================================= -->
        <footer id="footer" class="dark">
            <div class="container">

                <!-- Footer Widgets
            ============================================= -->
                <div class="footer-widgets-wrap pb-4 clearfix">

                    <div class="flex flex-row flex-wrap">
                        <div class="col-md-4 col-sm-6 mb-0 mb-sm-4 mb-md-0">
                            <div class="widget clearfix">
                                <h4>About Us</h4>
                                <div
                                    style="background: url('{{ asset('canvas/images/world-map.png') }}') no-repeat left center; background-size: auto 100%;">
                                    <address>
                                        <strong>地址：</strong>
                                        231007 新北市新店區北新路3段200號8樓
                                    </address>
                                    <abbr title="Phone Number"><strong>客服專線:</strong></abbr>02-81966123、02-81966122 <br>
                                    <abbr
                                        title="Email Address"><strong>Email:</strong></abbr>hsuyaya@nfa.gov.tw、jimmychiu@nfa.gov.tw
                                </div>
                            </div>
                        </div>

                        <div class="col-md-3 col-6 mt-5 mt-sm-0">
                            <div class="widget clearfix">
                                <h4>深耕概要</h4>
                                <ul class="list-none iconlist ms-0">
                                    <li><a href="https://pdmcb.nfa.gov.tw/resultiii/detail">細述深耕</a></li>
                                    <li><a href="https://pdmcb.nfa.gov.tw/resultiii/overview/暢敘深耕">暢敘深耕</a></li>
                                    <li><a href="https://pdmcb.nfa.gov.tw/resultiii/overview/卓越深耕">卓越深耕</a></li>
                                    <li><a href="https://pdmcb.nfa.gov.tw/resultiii/overview/深耕美談">深耕美談</a></li>
                                    <li><a href="https://pdmcb.nfa.gov.tw/resultiii/highlights">深耕集錦</a></li>
                                    <li><a href="https://pdmcb.nfa.gov.tw/resultiii/overview/碩果深耕">碩果深耕</a></li>
                                </ul>
                            </div>
                        </div>

                        <div class="col-md-3 col-6 mt-5 mt-sm-0">
                            <div class="widget clearfix">
                                <h4>成果展示</h4>

                                <ul class="list-none iconlist">
                                    <li><a
                                            href="https://pdmcb.nfa.gov.tw/resultiii/achievement/1.災害潛勢及防災地圖">1.災害潛勢及防災地圖</a>
                                    </li>
                                    <li><a href="https://pdmcb.nfa.gov.tw/resultiii/achievement/2.防災教育訓練及講習">
                                            2.防災教育訓練及講習</a></li>
                                    <li><a href="https://pdmcb.nfa.gov.tw/resultiii/achievement/3.防災觀摩及表揚活動">
                                            3.防災觀摩及表揚活動</a></li>
                                    <li><a href="https://pdmcb.nfa.gov.tw/resultiii/achievement/4.災害防救區域治理">
                                            4.災害防救區域治理</a></li>
                                    <li><a href="https://pdmcb.nfa.gov.tw/resultiii/achievement/5.兵棋推演及演練">
                                            5.兵棋推演及演練</a></li>
                                    <li><a href="https://pdmcb.nfa.gov.tw/resultiii/achievement/6.韌性社區及防災士">
                                            6.韌性社區及防災士</a></li>
                                    <li><a href="https://pdmcb.nfa.gov.tw/resultiii/achievement/7.推廣普及防災知識">
                                            7.推廣普及防災知識</a></li>
                                    <li><a href="https://pdmcb.nfa.gov.tw/resultiii/achievement/8.防災合作夥伴"> 8.防災合作夥伴</a>
                                    </li>
                                </ul>
                            </div>
                        </div>

                        <div class="col-md-2 col-sm-6 mt-4 mt-sm-0">
                            <div class="widget clearfix">
                                <h4>相關連結</h4>
                                <ul class="list-none iconlist ms-0">
                                    <li><a href="https://pdmcb.nfa.gov.tw">深耕計畫資訊網民眾版</a></li>
                                    <li><a href="https://bear.emic.gov.tw/MY/#/home/map">簡易疏散避難地圖</a></li>
                                </ul>
                            </div>
                        </div>

                    </div>

                </div><!-- .footer-widgets-wrap end -->

                <div class="line line-sm m-0"></div>

            </div>


            <!-- Copyrights

        <div id="copyrights" class="bg-transparent">
            <div class="container clearfix">

                <div class="row justify-content-between col-mb-30">
                    <div class="col-12 col-md-auto text-center text-md-start">
                        Copyrights &copy; 2020 All Rights Reserved by Canvas Inc.<br>
                        <div class="copyright-links"><a href="javascript:void(0)">Terms of Use</a> / <a href="javascript:void(0)">Privacy Policy</a></div>
                    </div>

                    <div class="col-12 col-md-auto text-center text-md-end">
                        <div class="copyrights-menu copyright-links clearfix">
                            <a href="javascript:void(0)">Home</a>/<a href="javascript:void(0)">About Us</a>/<a href="javascript:void(0)">Team</a>/<a
                                href="javascript:void(0)">Clients</a>/<a href="javascript:void(0)">FAQs</a>/<a
                                href="javascript:void(0)">Contact</a>
                        </div>
                    </div>
                </div>

            </div>
        </div><!-- #copyrights end -->
        </footer><!-- #footer end -->

    </div><!-- #wrapper end -->

    <!-- Go To Top
============================================= -->
    <div id="gotoTop" class="icon-angle-up"></div>

    <!-- JavaScripts
============================================= -->
    <script src="{{ asset('canvas/js/jquery.js') }}"></script>
    <script src="{{ asset('canvas/js/plugins.min.js') }}"></script>

    <script src="{{ asset('canvas/js/jquery.hotspot.js') }}"></script>
    <script src="{{ asset('canvas/js/components/rangeslider.min.js') }}"></script>

    <!-- Footer Scripts
============================================= -->
    <script src="{{ asset('canvas/js/functions.js') }}"></script>

    <script>
        jQuery(document).ready(function () {
        var pricingCPU = 1,
            pricingRAM = 1,
            pricingStorage = 10,
            elementCPU = $(".range-slider-cpu"),
            elementRAM = $(".range-slider-ram"),
            elementStorage = $(".range-slider-storage");

        elementCPU.ionRangeSlider({
            grid: false,
            values: [1, 2, 4, 6, 8],
            postfix: ' Core',
            onStart: function (data) {
                pricingCPU = data.from_value;
            }
        });

        elementCPU.on('change', function () {
            pricingCPU = $(this).prop('value');
            calculatePrice(pricingCPU, pricingRAM, pricingStorage);
        });

        elementRAM.ionRangeSlider({
            grid: false,
            step: 1,
            min: 1,
            from: 1,
            max: 32,
            postfix: ' GB',
            onStart: function (data) {
                pricingRAM = data.from;
                console.log(data);
            }
        });

        elementRAM.on('onStart change', function () {
            pricingRAM = $(this).prop('value');
            calculatePrice(pricingCPU, pricingRAM, pricingStorage);
        });

        elementStorage.ionRangeSlider({
            grid: false,
            step: 10,
            min: 10,
            max: 100,
            postfix: ' GB',
            onStart: function (data) {
                pricingStorage = data.from;
            }
        });

        elementStorage.on('change', function () {
            pricingStorage = $(this).prop('value');
            calculatePrice(pricingCPU, pricingRAM, pricingStorage);
        });

        calculatePrice(pricingCPU, pricingRAM, pricingStorage);

        function calculatePrice(cpu, ram, storage) {
            var pricingValue = (Number(cpu) * 10) + (Number(ram) * 8) + (Number(storage) * 0.5);
            jQuery('.cpu-value').html(pricingCPU);
            jQuery('.ram-value').html(pricingRAM);
            jQuery('.storage-value').html(pricingStorage);
            jQuery('.cpu-price').html('$' + pricingCPU * 10);
            jQuery('.ram-price').html('$' + pricingRAM * 8);
            jQuery('.storage-price').html('$' + pricingStorage * 0.5);
            jQuery('.pricing-price').html('$' + pricingValue);
        }
    });

    jQuery(window).on('load', function () {
        $('#hotspot-img').hotSpot();
    });
    </script>

</body>

</html>