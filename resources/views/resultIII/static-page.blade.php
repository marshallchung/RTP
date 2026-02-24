@extends('resultIII.layouts.static-page-base')

@section('page-content')
    <div class="section m-0 bg-transparent">
        <div class="container clearfix">
            <h2>{{ $pageTitle ?? '' }}</h2>
			<h4>
			@if (  $pageTitle  == '深耕美談')
				分享在推動深耕第3期計畫這5年來，發生許多印象深刻的事件及令人感動的小故事。
			@elseif(  $pageTitle  == '細述深耕')
				說明深耕第3期計畫推動的背景和運作的模式、中央與地方政府、以及協力團隊間的合作。
			@elseif(  $pageTitle  == '碩果深耕')
				透過案例的方式分享深耕第3期計畫各工作項目如何協助縣市、公所、村里、社區解決防災面臨的問題。
			@elseif(  $pageTitle  == '卓越深耕')
				分享各縣市在推動中有哪些與眾不同的方式，透過創新來推動防救災工作。
			@elseif(  $pageTitle  == '暢敘深耕')
				由參與深耕計畫的人員，分享深耕計畫推動的點點滴滴。
			@elseif(  $pageTitle  == '回顧深耕')
				以一張張的照片分享深耕第3期計畫中的各項工作或活動，包括訪視、表揚、座談會等。
			@elseif(  $pageTitle  == '深耕集錦')
				以一張張的照片分享深耕第3期計畫中的各項工作或活動，包括訪視、表揚、座談會等。								
			@endif
			</h4>
            <div class="p-3" style="border: lightgrey 2pt dashed">
                {!! $staticPage->content !!}
            </div>
        </div>
    </div>
@endsection
