<?php
if (!isset($reportType)) $reportType = 'report';
?>

@extends('layouts.app')

@section('title', '成果資料')
@section('subtitle', $title)


@section('content')
<div x-data="{
        county:'{{ $county }}',
        search:'',
        pagination:'',
        centralReportList:[],
        clearSearch(){
            this.search='';
            this.getData(1);
        },
        resetSearch(){
            this.search='';
            this.county='';
            this.getData(1);
        },
        getData(page){
            var url = '/{{ $reportType }}/search/{{ $topicId }}?search=' + encodeURIComponent(this.search) + '&county=' + encodeURIComponent(this.county);
            if(page){
                url += '&page=' + encodeURIComponent(page);
            }
            fetch(url)
            .then((response)=>{
                return response.json();
            }).then((jsonData) => {
                this.pagination=jsonData.pagination;
                this.centralReportList=jsonData.centralReportList;
            }).catch(function(error) {
                console.log(error);
            });
        },
    }" class="flex flex-row items-start justify-center w-full pb-12" x-init="$nextTick(() => {
        getData();
    })">
    <div class="flex flex-col items-center justify-start w-full max-w-screen-lg px-4 space-y-6">
        <div class="flex flex-row items-start justify-around w-full space-x-4">
            <div
                class="flex {{ $reportType === 'report'?'flex-col space-y-4':'flex-row space-x-2' }} flex-wrap justify-start w-full">
                <div
                    class="flex flex-row items-center {{ $reportType === 'report'?'justify-center':'justify-start' }} flex-1 space-x-2">
                    @if(!in_array($topicId, [149, 150]))
                    <select @change=getData(1)
                        class="h-12 text-sm border-gray-300 rounded-md focus:border-sky-300 focus:ring focus:ring-sky-200 focus:ring-opacity-50 text-mainAdminTextGrayDark"
                        x-model='county'>
                        @foreach($countyOptions as $key=>$value)
                        <option value="{{ $key }}">{{ $value }}</option>
                        @endforeach
                    </select>
                    @endif
                    <div class="relative flex flex-row items-center justify-start h-12 space-x-2">
                        <label>
                            <input type="text" x-model="search" @input.debounce.500ms="getData(1)"
                                class="w-64 h-12 p-4 pr-12 border-gray-300 rounded-md shadow-sm placeholder:text-mainTextGray focus:border-sky-300 focus:ring focus:ring-sky-200 focus:ring-opacity-50"
                                name="search" placeholder="搜尋">
                        </label>
                        <div
                            class="absolute top-0 right-0 flex items-center justify-center w-12 h-12 text-white bg-mainBlue hover:bg-mainBlueDark rounded-r-md">
                            <i class="w-5 h-5 i-heroicons-magnifying-glass-20-solid"></i>
                        </div>
                        <button type="button" x-show="search.length>0" @click="clearSearch()"
                            class="absolute top-0 flex items-center justify-center w-10 h-12 right-12 rounded-r-md text-mainTextGray">
                            <i class="w-6 h-6 i-heroicons-x-circle-20-solid"></i>
                        </button>
                    </div>
                    <button type="button" @click="resetSearch()"
                        class="flex flex-row items-center justify-center w-32 h-12 space-x-2 text-white rounded-md bg-mainGrayDark hover:bg-mainTextGray">
                        <i class="i-fa6-solid-rotate" aria-hidden="true"></i>
                        <span>顯示全部</span>
                    </button>
                </div>
                <div class="flex flex-row items-center {{ $reportType === 'report'?'justify-center':'justify-start' }}"
                    x-html="pagination"></div>
            </div>
        </div>
        <table class="w-full bg-white border shadow-lg text-mainAdminTextGrayDark">
            <thead>
                <tr class="text-white border-b rounded-t bg-mainGrayDark">
                    @if ($reportType === 'report')
                    <th class="w-40 px-4 py-2 font-bold border-r last:border-r-0">縣市</th>
                    <th class="w-40 px-4 py-2 font-bold border-r last:border-r-0">地區</th>
                    @else
                    <th class="px-4 py-2 font-bold border-r w-52 last:border-r-0">日期</th>
                    @endif
                    <th class="px-4 py-2 font-bold border-r last:border-r-0">檔案</th>
                </tr>
            </thead>
            <tbody>
                <template x-if="centralReportList.length>0">
                    <template x-for="centralReport in centralReportList">
                        <tr class="bg-white border-b odd:bg-gray-100 last:border-b-0 last:rounded-b">
                            @if ($reportType === 'report')
                            <td class="px-4 py-2 text-center border-r last:border-r-0"
                                x-text="centralReport.county ? centralReport.county : centralReport.area">
                            </td>
                            <td class="px-4 py-2 text-center border-r last:border-r-0" x-text="centralReport.area">
                            </td>
                            @else
                            <td class="px-4 py-2 text-center border-r last:border-r-0" x-text="centralReport.fileDate">
                            </td>
                            @endif
                            <td
                                class="flex flex-col items-start justify-start px-4 py-2 space-y-2 text-left border-r last:border-r-0">
                                @if ($reportType === 'report')
                                <template x-for="reportFile in centralReport.files">
                                    <div class="flex flex-col items-start justify-start">
                                        <a :href="reportFile.path" class="text-mainBlueDark"
                                            x-text="reportFile.name"></a>
                                        <span class="text-sm text-mainTextGray"
                                            x-text="' - ' + (new Date(reportFile.created_at)).toLocaleString('chinese',{hour12:false})"></span>
                                    </div>
                                </template>
                                @else
                                <a :href="centralReport.path" class="text-mainBlueDark" x-text="centralReport.name"></a>
                                @endif
                            </td>
                        </tr>
                    </template>
                </template>
                <template x-if="centralReportList.length==0">
                    <tr class="bg-white border-b odd:bg-gray-100 last:border-b-0 last:rounded-b">
                        <td colspan="{{ $reportType === 'report'?3:2 }}"
                            class="px-4 py-2 text-center border-r last:border-r-0 text-mainTextGray">
                            無資料
                        </td>
                    </tr>
                </template>
            </tbody>
        </table>
    </div>
</div>
<div class="flex flex-col items-center justify-center w-full px-4 sm:flex-row">
    @foreach ([
        asset('image/live_photo/photo1.webp'),
        asset('image/live_photo/photo2.webp'),
        asset('image/live_photo/photo3.webp'),
		asset('image/live_photo/photo4.webp'),
        asset('image/live_photo/photo5.webp'),
        asset('image/live_photo/photo6.webp'),
        asset('image/live_photo/photo7.webp'),
        asset('image/live_photo/photo8.webp'),
    ] as $url)
    <div class="w-full m-1 sm:flex-1">
        <img src="{{ $url }}" class="w-full h-auto" />
    </div>
    @endforeach
</div>
@endsection

@section('js')
@endsection