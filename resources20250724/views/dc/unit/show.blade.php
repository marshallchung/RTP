@extends('layouts.app')

@section('title', '推動韌性社區 - 韌性社區名單查詢')

@section('content')
<div class="flex flex-row items-start justify-center w-full mt-4 mb-12">
    <div class="flex flex-row w-full max-w-5xl p-5 bg-white border rounded">
        <div class="flex flex-col w-full space-y-4">
            <div
                class="flex flex-col items-start justify-start pb-2 space-x-0 space-y-2 border-b sm:pb-0 sm:border-b-0 sm:flex-row sm:justify-center sm:items-stretch sm:space-x-4 sm:space-y-0">
                <span class="w-full sm:w-48 text-mainTextGray">社區名稱</span>
                <span class="w-full text-xl font-bold sm:flex-1">{{ $dcUnit->name }}</span>
            </div>

            <div
                class="flex flex-col items-start justify-start pb-2 space-x-0 space-y-2 border-b sm:pb-0 sm:border-b-0 sm:flex-row sm:justify-center sm:items-stretch sm:space-x-4 sm:space-y-0">
                <span class="w-full sm:w-48 text-mainTextGray">社區類型</span>
                <span class="w-full text-xl font-bold sm:flex-1">{{ $dcUnit->pattern }}</span>
            </div>

            <div
                class="flex flex-col items-start justify-start pb-2 space-x-0 space-y-2 border-b sm:pb-0 sm:border-b-0 sm:flex-row sm:justify-center sm:items-stretch sm:space-x-4 sm:space-y-0">
                <span class="w-full sm:w-48 text-mainTextGray">所在縣市</span>
                <span class="w-full text-xl font-bold sm:flex-1">{{ $dcUnit->county->name ?? '' }}</span>
            </div>

            <div
                class="flex flex-col items-start justify-start pb-2 space-x-0 space-y-2 border-b sm:pb-0 sm:border-b-0 sm:flex-row sm:justify-center sm:items-stretch sm:space-x-4 sm:space-y-0">
                <span class="w-full sm:w-48 text-mainTextGray">社區概略範圍</span>
                <span class="w-full text-xl font-bold sm:flex-1">{{ $dcUnit->location }}</span>
            </div>

            <div
                class="flex flex-col items-start justify-start pb-2 space-x-0 space-y-2 border-b sm:pb-0 sm:border-b-0 sm:flex-row sm:justify-center sm:items-stretch sm:space-x-4 sm:space-y-0">
                <span class="w-full sm:w-48 text-mainTextGray">社區概略範圍示意圖</span>
                <div class="w-full font-bold text-mainBlueDark sm:flex-1">
                    @foreach($dcUnit->filesOfLocation as $file)
                    <div class="flex flex-row items-center justify-start w-full p-2 bg-mainLight"
                        data-id="{{ $file->id }}">
                        <a href="/{{ $file->path }}" target="_blank" class="flex-1 text-left text-mainBlueDark">{{
                            $file->name }}</a>
                    </div>
                    @endforeach
                </div>
            </div>

            <div
                class="flex flex-col items-start justify-start pb-2 space-x-0 space-y-2 border-b sm:pb-0 sm:border-b-0 sm:flex-row sm:justify-center sm:items-stretch sm:space-x-4 sm:space-y-0">
                <span class="w-full sm:w-48 text-mainTextGray">過去是否曾推動過防災社區</span>
                <span class="w-full text-xl font-bold sm:flex-1">{{ $dcUnit->is_experienced ? '是' : '否' }}</span>
            </div>

            <div
                class="flex flex-col items-start justify-start pb-2 space-x-0 space-y-2 border-b sm:pb-0 sm:border-b-0 sm:flex-row sm:justify-center sm:items-stretch sm:space-x-4 sm:space-y-0">
                <span class="w-full sm:w-48 text-mainTextGray">社區環境概述</span>
                <span class="w-full text-xl font-bold sm:flex-1">{{ $dcUnit->environment }}</span>
            </div>

            <div
                class="flex flex-col items-start justify-start pb-2 space-x-0 space-y-2 border-b sm:pb-0 sm:border-b-0 sm:flex-row sm:justify-center sm:items-stretch sm:space-x-4 sm:space-y-0">
                <span class="w-full sm:w-48 text-mainTextGray">社區災害潛勢與風險概述</span>
                <span class="w-full text-xl font-bold sm:flex-1">{{ $dcUnit->risk }}</span>
            </div>

            <div
                class="flex flex-col items-start justify-start pb-2 space-x-0 space-y-2 border-b sm:pb-0 sm:border-b-0 sm:flex-row sm:justify-center sm:items-stretch sm:space-x-4 sm:space-y-0">
                <span class="w-full sm:w-48 text-mainTextGray"></span>
                <span class="w-full text-xl font-bold text-mainBlueDark sm:flex-1"><a
                        href="https://dmap.ncdr.nat.gov.tw/%E4%B8%BB%E9%81%B8%E5%96%AE/%E5%9C%B0%E5%9C%96%E6%9F%A5%E8%A9%A2/gis%E6%9F%A5%E8%A9%A2/"
                        target="_blank" title="另開視窗">【災害潛勢地圖查詢】</span>
            </div>
        </div>
    </div>
</div>
@endsection