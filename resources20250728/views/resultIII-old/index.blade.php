@extends('layouts.app')

@section('title', '強韌臺灣資訊網')

@section('css')
@endsection
@section('beforeContainer')
@if(count($homePageCarouselItems))
<div id="carouselExampleCaptions" class="carousel slide" data-ride="carousel">
    <ol class="carousel-indicators">
        @foreach($homePageCarouselItems as $item)
        <li data-target="#carouselExampleCaptions" data-slide-to="{{ $loop->index }}" @if($loop->first) class="active"
            @endif></li>
        @endforeach
    </ol>
    <div class="carousel-inner">
        @foreach($homePageCarouselItems as $item)
        <div class="carousel-item @if($loop->first) active @endif">
            @if($item['url'])
            <a href="{{ $item['url'] }}" target="_blank">
                <img src="{{ $item['image_url'] }}" class="d-block w-100" alt="{{ $item['title'] }}">
            </a>
            @else
            <img src="{{ $item['image_url'] }}" class="d-block w-100" alt="{{ $item['title'] }}">
            @endif
        </div>
        @endforeach
    </div>
    <a class="carousel-control-prev" href="#carouselExampleCaptions" role="button" data-slide="prev">
        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
        <span class="sr-only">Previous</span>
    </a>
    <a class="carousel-control-next" href="#carouselExampleCaptions" role="button" data-slide="next">
        <span class="carousel-control-next-icon" aria-hidden="true"></span>
        <span class="sr-only">Next</span>
    </a>
</div>
@endif

<div class="fwidgets">
    <div class="container">
        <div class="flex flex-row flex-wrap">
            <div class="text-center col-12" style="margin-bottom: 15px">
                <h4 style="color:white">頁面介紹</h4>
            </div>
        </div>
        <div class="flex flex-row flex-wrap">
            @foreach ([
            (object)[
            'glyphicon' => 'fa-heart',
            'title' => '計畫簡介',
            'content' => '介紹災害防救深耕計畫是什麼、推動各項工作的介紹，以及推動計畫期間的成效、動人故事等，並提供計畫檔案下載。',
            ],
            (object)[
            'glyphicon' => 'fa-th-list',
            'title' => '成果資料',
            'content' => '本頁面提供深耕1、2期計畫工作成果下載，以及內政部編撰的成果書冊，這些資料都是各縣市、鄉鎮市區以及協力團隊努力的結晶。',
            ],
            (object)[
            'glyphicon' => 'fa-user',
            'title' => '防災士培訓',
            'content' => '在這個頁面將提供防災士的介紹、最新消息、各單位培訓課程開課狀況及報名方式，並陸續提供教材等資料下載，歡迎想加入防災士的夥伴瀏覽，已成為防災士的夥伴，也可登入後查詢及修改個人資料。',
            ],
            (object)[
            'glyphicon' => 'fa-home',
            'title' => '推動韌性社區',
            'content' => '在這個頁面將提供韌性社區的介紹、最新消息，並陸續提供操作手冊等資料下載，歡迎想申請韌性社區推動標章的社區夥伴瀏覽，已取得標章的社區，也可於登入後查詢上傳社區的各種防災資料。',
            ],
            (object)[
            'glyphicon' => 'fa-star',
            'title' => '相關資源與連結',
            'content' => '本頁面包含了各縣市已建置完成的深耕計畫網頁連結，並且提供了災害防救深耕計畫相關參考檔案及資源。',
            ],
            (object)[
            'glyphicon' => 'fa-edit',
            'title' => '業務人員版',
            'content' => '提供執行災害防救深耕計畫的縣市、鄉鎮市區業務人員，各種資料下載及管考作業功能，需有登入帳號密碼才可使用。',
            ],
            ] as $item)
            <div class="col-sm-4 homewidget">
                <span><i class="fa {{ $item->glyphicon }}"></i></span>
                <h4>{{ $item->title }}</h4>
                <p> {!! $item->content !!} </p>
            </div>
            @endforeach
        </div>
    </div>
</div>

<div class="container">
    <div class="section-wide">
        <div class="flex flex-row flex-wrap">
            <div class="section-title col-12">
                <h2>深耕2期計畫成果資料展示</h2>
                <p>成果資料展示集合了縣市政府與公所各項成果，使用者可以從下面的資料中，詳細看見深耕第2期計畫主要工作項目的具體成果，這些成果資料都是努力付出、不斷累積而來的結晶。</p>
            </div>

            @foreach ([
            (object)[
            'title' => '鄉（鎮、市、區）地區災害防救計畫',
            'content' => '地區災害防救計畫是防救災工作的重要依據。'
            ],
            (object)[
            'title' => '各類災害標準作業程序',
            'content' => '標準作業程序是應變時的作業流程。'
            ],
            (object)[
            'title' => '防救災教育訓練資料',
            'content' => '深耕計畫透過教育訓練提升了基層人員的知識與能力'
            ],
            (object)[
            'title' => '鄉（鎮、市、區）災害應變中心建置情形',
            'content' => '透過建置公所應變中心，強化了鄉鎮市區的災害應變能力。'
            ],
            (object)[
            'title' => '兵棋推演資料',
            'content' => '利用兵棋推演，在模擬的情境下，進行人員訓練、檢視資源，以及檢討作業流程。'
            ],
            (object)[
            'title' => '防救災圖資',
            'content' => '以分類方式呈現各鄉(鎮、市、區)災害潛勢圖等防救災圖資。'
            ],
            (object)[
            'title' => '防災避難看板',
            'content' => '利用Google map來呈現各地防災避難看板之點位、照片等資料。'
            ],
            (object)[
            'title' => '村里簡易疏散避難地圖',
            'content' => '方便瞭解住家鄰近避難收容所等相關資訊。'
            ],
            ] as $item)
            <div class="col-sm-6">
                <h4>{{ $item->title }}</h4>
                <p> {!! $item->content !!} </p>
            </div>
            @endforeach
        </div>
    </div>

    <div class="flex flex-col items-center justify-center w-full px-4 sm:flex-row">
        @foreach ([
        asset('image/live_photo/photo1.jpg'),
        asset('image/live_photo/photo2.jpg'),
        asset('image/live_photo/photo3.jpg'),
        ] as $url)
        <div class="w-full m-1 sm:flex-1">
            <img src="{{ $url }}" class="w-full h-auto" />
        </div>
        @endforeach
    </div>
</div>
@endsection
@section('content')
{{-- 固定背景備份
<div class="carousel-item active">
    <img class="d-block w-100 grayscale" style="
                    width:100%;
                    height:auto;
                    max-width:100%"
        src="http://cdn.demo.fabthemes.com/revera/files/2013/08/beautiful_poppies-wallpaper-1920x1080-1280x550.jpg"
        alt="First slide">

    <div class="hidden carousel-caption md:block" style="height:85%;
                    overflow:auto;">
        <h2>歡迎來到深耕成果資訊網！</h2><br>
        <div class="text-left">

            </ul>
        </div>
    </div>
    <div class="doverlay"></div>
</div> --}}

@endsection