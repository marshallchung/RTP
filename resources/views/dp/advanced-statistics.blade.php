@extends('layouts.app')

@section('title', '防災士培訓')
@section('subtitle', ' 進階防災士統計')

@section('content')
<div x-data="{
}" class="flex items-center justify-center w-full px-2 py-4 sm:px-4" x-init="$nextTick(() => {

    })">
    <div
        class="flex flex-col items-center justify-start w-full max-w-6xl px-0 py-4 sm:px-4 sm:flex-row sm:justify-center sm:items-stretch">
        <div class="flex flex-col items-center justify-center w-full pb-8 space-y-4 sm:pb-0 sm:w-72">
            <img src="{{ asset('image/DRV_logo.png') }}" class="m-2" alt="防災士" width=125px height=125px>
            <a href="../resource/advanceMap" class="underline text-mainBlue underline-offset-4">全台進階防災士分布圖</a>
            <span style="font-weight:bold;">進階防災士總人數：{{ $dpStudentStatistics['advanced_total'] }} </span>
            <span style="font-weight:bold;">男性：{{ $dpStudentStatistics['advanced_male_count'] }} （{{
                $dpStudentStatistics['advanced_county_male_percentage']
                }}%）</span>
            <span style="font-weight:bold;">女性：{{ $dpStudentStatistics['advanced_female_count'] }} （{{
                $dpStudentStatistics['advanced_county_female_percentage']
                }}%）</span>
            <span class="text-rose-600">（截至 {{ $end_year }} 年 {{
                $end_month }} 月）</span>
            <div class="w-40 mx-auto overflow-hidden">
                <canvas data-te-chart="pie" data-te-labels="['男' , '女']"
                    data-te-dataset-data="[{{ $dpStudentStatistics['advanced_male_count'] }}, {{ $dpStudentStatistics['advanced_female_count'] }}]"
                    data-te-dataset-background-color="['rgba(129, 198, 206, 1)', 'rgba(233, 148, 160, 1)']">
                </canvas>
            </div>
            <a target="_blank" href="/dp/advanced-statistics/export"
                class="flex items-center justify-center p-2 text-white rounded-md w-36 bg-mainBlue hover:opacity-80">匯出</a>
        </div>
        <div class="flex flex-col items-center justify-start w-full space-y-4 sm:flex-1">
            <div class="flex flex-row w-full px-2 space-x-2 sm:px-0">
                <a href="/dp/advanced-statistics?county_area=0"
                    class="flex items-center justify-center flex-1 rounded-md p-2 {{ $county_area==='0'?'bg-mainBlueDark hover:bg-mainBlue text-white':'bg-gray-200 hover:bg-gray-100 text-mainAdminTextGrayDark' }}">北部</a>
                <a href="/dp/advanced-statistics?county_area=1"
                    class="flex items-center justify-center flex-1 rounded-md p-2 {{ $county_area==='1'?'bg-mainBlueDark hover:bg-mainBlue text-white':'bg-gray-200 hover:bg-gray-100 text-mainAdminTextGrayDark' }}">中部</a>
                <a href="/dp/advanced-statistics?county_area=2"
                    class="flex items-center justify-center flex-1 rounded-md p-2 {{ $county_area==='2'?'bg-mainBlueDark hover:bg-mainBlue text-white':'bg-gray-200 hover:bg-gray-100 text-mainAdminTextGrayDark' }}">南部</a>
                <a href="/dp/advanced-statistics?county_area=3"
                    class="flex items-center justify-center flex-1 rounded-md p-2 {{ $county_area==='3'?'bg-mainBlueDark hover:bg-mainBlue text-white':'bg-gray-200 hover:bg-gray-100 text-mainAdminTextGrayDark' }}">東部</a>
                <a href="/dp/advanced-statistics?county_area=4"
                    class="flex items-center justify-center flex-1 rounded-md p-2 {{ $county_area==='4'?'bg-mainBlueDark hover:bg-mainBlue text-white':'bg-gray-200 hover:bg-gray-100 text-mainAdminTextGrayDark' }}">離島地區</a>
            </div>
            <table class="w-full border">
                <thead>
                    <tr class="text-white border-b bg-mainGrayDark">
                        <th class="w-32 px-4 py-2 font-bold border-r last:border-r-0">縣市名稱</th>
                        <th class="w-40 px-4 py-2 font-bold border-r last:border-r-0">總人數(縣市比率)</th>
                        <th class="hidden font-bold border-r last:border-r-0 sm:table-cell">縣市男女人數(縣市男女性比率)</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($students as $student)
                    <tr class="bg-white border-b odd:bg-gray-100 last:border-b-0 last:rounded-b">
                        <td colspan="2">
                            <div class="flex flex-col w-full h-full">
                                <div class="flex flex-row items-stretch justify-center flex-1 w-full">
                                    <div class="w-32 px-4 py-2 text-center border-r">
                                        {{ $student->name }}
                                    </div>
                                    <div class="w-40 px-4 py-2 text-center border-r">
                                        {{ $student->county_count }}
                                        ({{ number_format( $student->total_percentage, 2) }}%)
                                    </div>
                                </div>
                                <div class="flex flex-col p-2 space-y-2 sm:hidden">
                                    <div class="relative w-full h-6">
                                        <div
                                            class="absolute top-0 left-0 h-6 text-sm flex justify-center items-center text-white rounded-r-md bg-mainBlue w-{{ round($student->county_male_percentage) }}/100">
                                            男{{ $student->county_male_percentage>35 ? ('：' . $student->male_count . ' ('
                                            .
                                            number_format(
                                            $student->county_male_percentage, 2)
                                            . '%)') : '' }}
                                        </div>
                                        <span
                                            class="absolute top-0 right-0 text-left pl-2 text-mainAdminTextGrayDark {{ $student->county_male_percentage>35 ? 'hidden' : 'block' }} h-6 w-{{ 100-round($student->county_male_percentage) }}/100">
                                            男：{{$student->male_count . ' (' . number_format(
                                            $student->county_male_percentage, 2) . '%)' }}
                                        </span>
                                    </div>
                                    <div class="relative w-full h-6" aria-valuenow="25" aria-valuemin="0"
                                        aria-valuemax="100">
                                        <div
                                            class="absolute top-0 left-0 h-6 text-sm flex justify-center items-center text-white rounded-r-md bg-mainPink w-{{ round($student->county_female_percentage) }}/100">
                                            女{{ $student->county_female_percentage>35 ? ('：' .$student->female_count . '
                                            ('
                                            .
                                            number_format(
                                            $student->county_female_percentage,
                                            2) . '%)') : '' }}
                                        </div>
                                        <span
                                            class="absolute top-0 right-0 text-left pl-2 text-mainAdminTextGrayDark {{ $student->county_female_percentage>35 ? 'hidden' : 'block' }} h-6 w-{{ 100-round($student->county_female_percentage) }}/100">
                                            女：{{$student->female_count . ' (' . number_format(
                                            $student->county_female_percentage, 2) . '%)'
                                            }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td class="hidden px-4 py-2 text-right border-r last:border-r-0 sm:table-cell">
                            <div class="flex flex-col space-y-2">
                                <div class="relative w-full h-6">
                                    <div
                                        class="absolute top-0 left-0 h-6 text-sm text-center align-middle text-white rounded-r-md bg-mainBlue w-{{ round($student->county_male_percentage) }}/100">
                                        男{{ $student->county_male_percentage>35 ? ('：' . $student->male_count . ' (' .
                                        number_format(
                                        $student->county_male_percentage, 2)
                                        . '%)') : '' }}
                                    </div>
                                    <span
                                        class="absolute top-0 right-0 text-left pl-2 text-mainAdminTextGrayDark {{ $student->county_male_percentage>35 ? 'hidden' : 'block' }} h-6 w-{{ 100-round($student->county_male_percentage) }}/100">
                                        男：{{$student->male_count . ' (' . number_format(
                                        $student->county_male_percentage, 2) . '%)' }}
                                    </span>
                                </div>
                                <div class="relative w-full h-6" aria-valuenow="25" aria-valuemin="0"
                                    aria-valuemax="100">
                                    <div
                                        class="absolute top-0 left-0 h-6 text-sm text-center align-middle text-white rounded-r-md bg-mainPink w-{{ round($student->county_female_percentage) }}/100">
                                        女{{ $student->county_female_percentage>35 ? ('：' . $student->female_count . ' ('
                                        .
                                        number_format(
                                        $student->county_female_percentage,
                                        2) . '%)') : '' }}
                                    </div>
                                    <span
                                        class="absolute top-0 right-0 text-left pl-2 text-mainAdminTextGrayDark {{ $student->county_female_percentage>35 ? 'hidden' : 'block' }} h-6 w-{{ 100-round($student->county_female_percentage) }}/100">
                                        女：{{$student->female_count . ' (' . number_format(
                                        $student->county_female_percentage, 2) . '%)'
                                        }}
                                    </span>
                                </div>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                    @if (count($students)==0)
                    <tr class="bg-white border-b odd:bg-gray-100 last:border-b-0 last:rounded-b">
                        <td colspan="3" class="px-4 py-2 text-center border-r last:border-r-0">無資料</td>
                    </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@section('js')

@endsection