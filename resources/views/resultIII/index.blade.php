@extends('resultIII.layouts.base')

@section('title')
三期計畫成果網
@endsection

@section('header_class')
transparent-header dark
@endsection

@section('slider')
<!-- Slider
    ============================================= -->
<section id="slider" class="slider-element bg-angle include-header">

    <!-- Slider Background Map
        ============================================= -->
    <img class="img-map parallax position-absolute" src="{{ asset('canvas/demos/hosting/images/svg/map-light.svg') }}"
        alt="Image" data-0="opacity: 0.05;margin-top:0px" data-800="opacity: 0.5;margin-top:150px">

    <!-- Slider Background Cloud
        ============================================= -->
    <div class="cloud-wrap">
        <div class="c1">
            <div class="cloud"></div>
        </div>
        <div class="c2">
            <div class="cloud"></div>
        </div>
        <div class="c3">
            <div class="cloud"></div>
        </div>
        <div class="c4">
            <div class="cloud"></div>
        </div>
        <div class="c5">
            <div class="cloud"></div>
        </div>
    </div>

    <!-- Slider Titiles
        ============================================= -->
    <div class="vertical-middle container dark">
        <div class="row justify-content-between align-items-center">
            <div class="col-md-5">
                <div class="slider-title">
                    <h2 class="text-white text-rotater mb-3" data-separator="," data-rotate="fadeIn" data-speed="3500">
                        災害防救深耕第3期計畫<br>
                        <!-- <span class="t-rotate text-white">Awesome,Beautiful,Great</span> Website. -->
                    </h2>
                    <p>本計畫之階段性任務即將達成，本網站將彙整各直轄市、縣（市）及鄉（鎮、市、區）公所、社區自主防災、NGOs及企業參與防救災等深耕計畫執行成果、案例以及計畫推動之心路歷程等內容，與災害防救之相關單位、業務人員以及地區民眾分享。
                    </p>
                </div>
                <!--                    <a href="javascript:void(0)" class="button bg-white text-dark button-light button-rounded button-large color ms-0">Create Account <i
                            class="icon-line-arrow-right fw-semibold"></i></a>
 -->
            </div>
            <div class="col-md-5 hidden md:block">
                <img src="{{ asset('canvas/demos/hosting/images/3.png') }}" alt="Image">
            </div>
        </div>
    </div>

</section><!-- #slider end -->
@endsection

@section('content')
<div class="container clearfix">
    <!-- Slider negetive Box
        ============================================= -->
    <div class="row justify-center slider-box-wrap clearfix">
        <div class="col-10">
            <div class="slider-bottom-box">
                <div class="row align-items-center clearfix">
                    <div class="col-lg-4 mb-3 mb-lg-0">
                        <h2 class="mb-3 h1 fw-light">成果展示</h2>
                        <p class="fw-normal text-gray-400 mb-0">彙整各直轄市、縣（市）及鄉（鎮、市、區）公所之深耕計畫執行成果以及計畫推動之心路歷程等內容。</p>
                    </div>
                    <div class="col-lg-4 col-sm-6">
                        <ul class="iconlist m-0">
                            <li><img src="{{ asset('canvas/demos/hosting/images/svg/checked.svg') }}" width="20"
                                    height="20" alt="Image" class="me-2">1.災害潛勢及防災地圖
                            </li>
                            <li class="pt-3"><img src="{{ asset('canvas/demos/hosting/images/svg/checked.svg') }}"
                                    width="20" height="20" alt="Image" class="me-2">2.防災教育訓練及講習
                            </li>
                            <li class="pt-3"><img src="{{ asset('canvas/demos/hosting/images/svg/checked.svg') }}"
                                    width="20" height="20" alt="Image" class="me-2">3.防災觀摩及表揚活動
                            </li>
                            <li class="pt-3"><img src="{{ asset('canvas/demos/hosting/images/svg/checked.svg') }}"
                                    width="20" height="20" alt="Image" class="me-2">4.災害防救區域治理
                            </li>
                        </ul>
                    </div>
                    <div class="col-lg-4 col-sm-6">
                        <ul class="iconlist m-0">
                            <li class="pt-3 pt-lg-0"><img
                                    src="{{ asset('canvas/demos/hosting/images/svg/checked.svg') }}" width="20"
                                    height="20" alt="Image" class="me-2">5.兵棋推演及演練
                            </li>
                            <li class="pt-3"><img src="{{ asset('canvas/demos/hosting/images/svg/checked.svg') }}"
                                    width="20" height="20" alt="Image" class="me-2">6.韌性社區及防災士
                            </li>
                            <li class="pt-3"><img src="{{ asset('canvas/demos/hosting/images/svg/checked.svg') }}"
                                    width="20" height="20" alt="Image" class="me-2">7.推廣普及防災知識
                            </li>
                            <li class="pt-3"><img src="{{ asset('canvas/demos/hosting/images/svg/checked.svg') }}"
                                    width="20" height="20" alt="Image" class="me-2">8.防災合作夥伴
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Features Section
        ============================================= -->
    <div class="heading-block center topmargin-lg mx-auto border-bottom-0 clearfix" style="max-width: 700px">
        <h2>深耕概要</h2>
        <p>說明深耕第3期計畫推動的背景和運作的模式、中央與地方政府、以及協力團隊間的合作。</p>
    </div>
    <div class="row col-mb-50 mb-0 mt-4">
        <div class="col-lg-4 col-md-6">
            <div class="feature-box not-dark">
                <div class="fbox-icon">
                    <img src="{{ asset('canvas/demos/hosting/images/svg/web.svg') }}"
                        class="rounded-0 bg-transparent text-start" alt="Image" style="height: 52px;">
                </div>
                <div class="fbox-content">
                    <h3 class="fw-medium text-dark">細述深耕</h3>
                    <p class="fw-light">說明深耕計畫推動背景與運作機制，彙整各項成果數量。</p>
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-md-6">
            <div class="feature-box not-dark">
                <div class="fbox-icon">
                    <img src="{{ asset('canvas/demos/hosting/images/svg/web.svg') }}"
                        class="rounded-0 bg-transparent text-start" alt="Image" style="height: 52px;">
                </div>
                <div class="fbox-content">
                    <h3 class="fw-medium text-dark">碩果深耕</h3>
                    <p class="fw-light">透過案例的方式分享深耕第3期計畫各工作項目如何協助縣市、公所、村里、社區解決防災面臨的問題。</p>
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-md-6">
            <div class="feature-box not-dark">
                <div class="fbox-icon">
                    <img src="{{ asset('canvas/demos/hosting/images/svg/cloud.svg') }}"
                        class="rounded-0 bg-transparent text-start" alt="Image" style="height: 52px;">
                </div>
                <div class="fbox-content">
                    <h3 class="fw-medium text-dark">暢敘深耕</h3>
                    <p class="fw-light">由參與深耕計畫的人員，分享深耕計畫推動的點點滴滴。</p>
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-md-6">
            <div class="feature-box not-dark">
                <div class="fbox-icon">
                    <img src="{{ asset('canvas/demos/hosting/images/svg/dedicated.svg') }}"
                        class="rounded-0 bg-transparent text-start" alt="Image" style="height: 52px;">
                </div>
                <div class="fbox-content">
                    <h3 class="fw-medium text-dark">卓越深耕</h3>
                    <p class="fw-light">分享各縣市在推動中有哪些與眾不同的方式，透過創新來推動防救災工作。</p>
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-md-6">
            <div class="feature-box not-dark">
                <div class="fbox-icon">
                    <img src="{{ asset('canvas/demos/hosting/images/svg/shared.svg') }}"
                        class="rounded-0 bg-transparent text-start" alt="Image" style="height: 52px;">
                </div>
                <div class="fbox-content">
                    <h3 class="fw-medium text-dark">深耕美談</h3>
                    <p class="fw-light">分享在推動深耕第3期計畫這5年來，發生許多印象深刻的事件及令人感動的小故事。</p>
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-md-6">
            <div class="feature-box not-dark">
                <div class="fbox-icon">
                    <img src="{{ asset('canvas/demos/hosting/images/svg/domain.svg') }}"
                        class="rounded-0 bg-transparent text-start" alt="Image" style="height: 52px;">
                </div>
                <div class="fbox-content">
                    <h3 class="fw-medium text-dark">深耕集錦</h3>
                    <p class="fw-light">以一張張的照片分享深耕第3期計畫中的各項工作或活動，包括訪視、表揚、座談會等。</p>
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-md-6">
            <div class="feature-box not-dark">
                <div class="fbox-icon">
                    <img src="{{ asset('canvas/demos/hosting/images/svg/activation.svg') }}"
                        class="rounded-0 bg-transparent text-start" alt="Image" style="height: 52px;">
                </div>
                <div class="fbox-content">
                    <h3 class="fw-medium text-dark">遠望深耕</h3>
                    <p class="fw-light">深耕第3期計畫即將結束，與大家分享未來在防災工作上將如何持續加強以及新的展望。</p>
                </div>
            </div>
        </div>
    </div>

</div>



<!-- Addition Service Section

    <div class="container clearfix">
        <div class="row clearfix">
            <div class="col-md-12">
                <div class="before-heading">Other Additional Services</div>
                <h3>Highly Available Services</h3>
                <div class="row col-mb-30">
                    <div class="col-md-6">
                        <div class="feature-box p-5 media-box"
                             style="border-radius: 6px; box-shadow: 0 2px 4px rgba(3,27,78,.1); border: 1px solid #e5e8ed;">
                            <div class="fbox-media">
                                <img src="{{ asset('canvas/demos/hosting/images/svg/balancing.svg') }}" style="width: 42px;" alt="Image">
                            </div>
                            <div class="fbox-content px-0">
                                <h3 class="ls0">Load Balancing</h3>
                                <p class="mt-2">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Expedita sint explicabo quis at voluptatum
                                    autem, cupiditate officiis maxime deserunt soluta! consectetur adipisicing elit.</p>
                                <a href="javascript:void(0)" class="btn btn-link mt-3 fw-normal color p-0" style="font-size: 16px;">$15/month for Load
                                    Balancing <i
                                        class="icon-line-arrow-right position-relative" style="top: 2px"></i></a>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="feature-box p-5 media-box"
                             style="border-radius: 6px; box-shadow: 0 2px 4px rgba(3,27,78,.1); border: 1px solid #e5e8ed;">
                            <div class="fbox-media">
                                <img src="{{ asset('canvas/demos/hosting/images/svg/location.svg') }}" style="width: 42px;" alt="Image">
                            </div>
                            <div class="fbox-content px-0">
                                <h3 class="ls0">Location Zone</h3>
                                <p class="mt-2">Distinctively enhance front-end outsourcing after cross-platform synergy. Interactively implement an
                                    expanded array of collaboration and idea-sharing and innovative bandwidth.</p>
                                <a href="javascript:void(0)" class="btn btn-link mt-3 fw-normal color p-0" style="font-size: 16px;">$5/month for
                                    Location <i
                                        class="icon-line-arrow-right position-relative" style="top: 2px"></i></a>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="feature-box p-5 media-box"
                             style="border-radius: 6px; box-shadow: 0 2px 4px rgba(3,27,78,.1); border: 1px solid #e5e8ed;">
                            <div class="fbox-media">
                                <img src="{{ asset('canvas/demos/hosting/images/svg/ssl.svg') }}" style="width: 42px;" alt="Image">
                            </div>
                            <div class="fbox-content px-0">
                                <h3 class="ls0">Dedicated SSL Certificate</h3>
                                <p class="mt-2">Assertively harness stand-alone communities through front-end networks. Globally engage plug-and-play
                                    sources through multidisciplinary portals. Enthusiastically synergize orthogonal.</p>
                                <a href="javascript:void(0)" class="btn btn-link mt-3 fw-normal color p-0" style="font-size: 16px;">$7/month for SSL
                                    Certificate <i
                                        class="icon-line-arrow-right position-relative" style="top: 2px"></i></a>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="feature-box p-5 media-box"
                             style="border-radius: 6px; box-shadow: 0 2px 4px rgba(3,27,78,.1); border: 1px solid #e5e8ed;">
                            <div class="fbox-media">
                                <img src="{{ asset('canvas/demos/hosting/images/svg/team.svg') }}" style="width: 42px;" alt="Image">
                            </div>
                            <div class="fbox-content px-0">
                                <h3 class="ls0">Team Accounts</h3>
                                <p class="mt-2">Uniquely harness prospective information through long-term high-impact portals. Rapidiously enable
                                    principle-centered users rather than inexpensive sources. Distinctively enhance front-end outsourcing.</p>
                                <a href="javascript:void(0)" class="btn btn-link mt-3 fw-normal color p-0" style="font-size: 16px;">$4/month for Team <i
                                        class="icon-line-arrow-right position-relative" style="top: 2px"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="clear"></div>
    ============================================= -->


@endsection