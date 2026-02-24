<?php
if (!isset($reportType)) $reportType = 'report';
?>

@extends('layouts.app')

@section('title', '直轄市、縣(市)政府歷年成果統計')
@section('subtitle', "{$countyOptions[$county]['name']}政府歷年成果統計")


@section('content')
<div x-data="{
    }" class="flex flex-row items-start justify-center w-full pb-12" x-init="$nextTick(() => {
    })">
    <div class="flex flex-col items-center justify-start w-full max-w-6xl px-4 space-y-6">
        <form method="GET" action="/report" class="flex flex-col items-start justify-start w-full space-y-4">
            <div class="flex flex-col justify-start w-full space-y-4">
                <div class="relative flex flex-row items-center justify-start flex-1 space-x-2">
                    <span class="text-lg">選擇統計年份</span>
                    <select @change="$refs.reportSubmit.click()" name="year"
                        class="h-12 text-sm border-gray-300 rounded-md focus:border-sky-300 focus:ring focus:ring-sky-200 focus:ring-opacity-50 text-mainAdminTextGrayDark">
                        <option value="2023" {{ $year==2023?'selected':'' }}>112年</option>
                        <option value="2024" {{ $year==2024?'selected':'' }}>113年</option>
                        <option value="2025" {{ $year==2025?'selected':'' }}>114年</option>
                        <option value="2026" {{ $year==2026?'selected':'' }}>115年</option>
                        <option value="2027" {{ $year==2027?'selected':'' }}>116年</option>
                    </select>
                    <button type="submit" x-ref="reportSubmit" class="opacity-0 pointer-events-none"></button>
                    @if (!empty($data))
                    <a target="_blank" href="/report/export/{{ $year }}/{{ $county }}"
                        class="absolute right-10 text-mainBlueDark">匯出</a>
                    @endif
                </div>
            </div>
            <div class="flex flex-row flex-wrap items-start justify-start w-full">
                @foreach($countyOptions as $key=>$value)
                <label class="rounded-lg  {{ $county==$key?'text-white
                    bg-mainBlueDark':'text-mainAdminTextGrayDark bg-gray-100' }} hover:bg-mainBlue h-9 w-28 text-lg flex justify-center m-2
                    items-center">
                    <input type="radio" name="county" value="{{ $key }}" @click="$refs.reportSubmit.click()"
                        class="hidden">
                    <span>{{ $value['name'] }}</span>
                </label>
                @endforeach
            </div>
        </form>
        <div class="w-full bg-white text-mainAdminTextGrayDark">
            @if ($data)
            {!! $data->content !!}
            @else
            <div class="flex items-center justify-center w-full py-16">
                <span>尚未發佈成果統計表</span>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection

@section('js')
@endsection