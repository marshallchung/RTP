<?php
    if (request('countyGo')) {
        $title = '縣市執行計畫';
    } else {
        $title = '成果資料';
    }
?>
@extends('admin.layouts.dashboard', [
'heading' => '管理與查詢功能',
'breadcrumbs' => [
$title,
'管理與查詢功能'
]
])

@section('title', '管理與查詢功能')

@section('inner_content')
<div x-data="{
    btnDownloadXlsx(e){
        var year = document.getElementById('year').value;
        window.location = '{{ route('admin.reports.downloadXlsx') }}?year=' + year;
    },
    filterChange(e){
        document.getElementById('form').submit();
    },
}" class="flex flex-col items-start justify-start w-full p-4 space-y-6" x-init="$nextTick(() => {
})">
    <div class="flex flex-row flex-wrap w-full">
        {!! Form::open(['method'=>'get','id'=>'form', 'class' => 'flex flex-row flex-wrap items-center w-full
        space-x-4']) !!}
        {!! Form::select('county_id', $counties, request('county_id'), ['@change'=>'filterChange','class' => 'h-12 px-4
        border-gray-300 rounded-md
        shadow-sm focus:border-cyan-300 focus:ring focus:ring-cyan-200 focus:ring-opacity-50 w-44']) !!}
        {!! Form::select('category_id', $categories, request('category_id'), ['@change'=>'filterChange','class' =>
        'h-12 px-4 border-gray-300
        rounded-md shadow-sm focus:border-cyan-300 focus:ring focus:ring-cyan-200 focus:ring-opacity-50 min-w-[12rem]'])
        !!}
        <select
            class="w-32 h-12 px-4 border-gray-300 rounded-md shadow-sm focus:border-cyan-300 focus:ring focus:ring-cyan-200 focus:ring-opacity-50"
            id="year" name="year">
            @foreach ($availableYears as $one_year)
            <option value="{{ $one_year }}" {{ $one_year==$year?'selected':'' }}>{{ intval($one_year)-1911 }}年</option>
            @endforeach
        </select>
        <div class="flex flex-row items-center justify-start py-1">
            <button type="submit"
                class="flex items-center justify-center w-20 h-10 text-white bg-mainCyanDark">搜尋</button>
            <a id="btnDownloadXlsx" @click="btnDownloadXlsx"
                class="flex items-center justify-center w-20 h-10 bg-gray-100 border border-gray-300 shadow-none cursor-pointer text-mainAdminTextGrayDark hover:bg-gray-50">匯出表單</a>
        </div>
        {!! Form::close() !!}
    </div>
    <div class="w-full max-w-[calc(100vw-280px)] overflow-scroll"
        :class="{'max-w-[calc(100vw-280px)]':openMMC,'max-w-[calc(100vw-96px)]':!openMMC}">
        <table class="w-full bg-white border shadow-lg text-mainAdminTextGrayDark min-w-fit">
            <thead>
                <tr class="border-b rounded-t bg-mainGray">
                    <th class="p-2 font-normal text-left border-r last:border-r-0 min-w-[16rem]">Topic</th>
                    @foreach ($users as $user)
                    <th class="p-2 font-normal text-left border-r last:border-r-0 min-w-[4.5rem]">{{ $user->name }}</th>
                    @endforeach
                </tr>
            </thead>
            <tbody class="text-content text-mainAdminTextGrayDark">
                <tr class="border-b last:border-b-0 odd:bg-white even:bg-mainLight">
                    <td class="p-2 border-r last:border-r-0">
                    </td>
                    @foreach ($users as $user)
                    <td class="p-2 text-center border-r last:border-r-0">
                        @if(isset($county_files[$user->id]) && !empty($county_files[$user->id]))
                        <a class="text-blue-500 "
                            href="/admin/reports/downloadFilesByCounty?year={{ $year }}&category_id={{ request('category_id') }}&user_id={{ $user->id }}">下載</a>
                        @endif
                    </td>
                    @endforeach
                </tr>
                @foreach ($topics as $topic)
                <tr class="bg-white border-b last:border-b-0">
                    <td class="p-2 border-r last:border-r-0">
                        @if (request('countyGo'))
                        <span class="text-nowrap">上傳</span>
                        @else
                        <span class="text-nowrap">{{ $topic->title }}</span>
                        @endif
                    </td>
                    @foreach ($users as $user)
                    <td class="p-2 border-r last:border-r-0">
                        @if(isset($data[$topic->id][$user->id]) && !empty($data[$topic->id][$user->id]))
                        <i class="w-6 h-6 text-lime-600 i-fa6-solid-circle-check"></i>
                        @endif
                    </td>
                    @endforeach
                </tr>
                <?php
                        if (request('countyGo')) break;
                    ?>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection

@section('scripts')
@endsection