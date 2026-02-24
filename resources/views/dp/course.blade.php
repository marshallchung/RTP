@extends('layouts.app')

@section('title', '防災士培訓')
@section('subtitle', '培訓課程查詢')

@section('css')
@endsection

@section('content')
<div x-data="{
        getData(page){
            const urlParams = new URLSearchParams(window.location.search);
            const advance = urlParams.get('advance');
            var url = '/dp/course?advance=' + advance;
            if(page){
                url += '&page=' + encodeURIComponent(page);
            }
            window.location.href = url;
        },
    }" class="flex flex-row items-start justify-center w-full pb-8">
    <div class="flex flex-col items-start justify-start w-full space-y-6 overflow-x-scroll sm:items-center">
        <div class="flex flex-row items-center justify-center">
            {{ $data->links() }}
        </div>
        <span class="w-full text-2xl text-left">防災士培訓相關課程請洽
            <a href="https://www.nfa.gov.tw/cht/index.php?code=list&ids=84"
                class=" hover:no-underline hover:text-mainBlue">各縣市政府消防局</a>及
            <a href="https://rtp.nfa.gov.tw/dp/training-institution"
                class=" hover:no-underline hover:text-mainBlue">防災士培訓機構</a>。
        </span>
        <div class="flex flex-row flex-wrap items-center justify-center w-full">
            <a href="/dp/course?advance="
                class="flex-1 h-11 max-w-[8rem] first:rounded-l-md last:rounded-r-md border flex justify-center items-center {{ (request('advance')===null || request('advance')==='')?'bg-lime-600 text-white':'bg-white text-mainAdminTextGrayDark' }}">全部</a>
            <a href="/dp/course?advance=0"
                class="flex-1 h-11 max-w-[8rem] first:rounded-l-md last:rounded-r-md border flex justify-center items-center {{ request('advance')==='0'?'bg-lime-600 text-white':'bg-white text-mainAdminTextGrayDark' }}">一般課程</a>
            <a href="/dp/course?advance=1"
                class="flex-1 h-11 max-w-[8rem] first:rounded-l-md last:rounded-r-md border flex justify-center items-center {{ request('advance')==='1'?'bg-lime-600 text-white':'bg-white text-mainAdminTextGrayDark' }}">進階課程</a>
        </div>
        <table class="w-full bg-white border shadow-lg text-mainAdminTextGrayDark min-w-[72rem]">
            <thead>
                <tr class="text-white border-b rounded-t bg-mainGrayDark">
                    <th class="w-1/4 p-2 font-bold border-r last:border-r-0">主辦單位</th>
                    <th class="w-1/4 p-2 font-bold border-r last:border-r-0">課程名稱</th>
                    <th class="p-2 font-bold border-r last:border-r-0">連絡電話</th>
                    <th class="p-2 font-bold border-r last:border-r-0">E-mail</th>
                    <th class="p-2 font-bold border-r last:border-r-0">開課日期</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($data as $item)
                <tr class="bg-white border-b odd:bg-gray-100 last:border-b-0 last:rounded-b">
                    <td class="p-2 text-center border-r last:border-r-0">
                        {{
                        $item->organizer=='消防署'?'內政部消防署':(preg_match('/(市|縣)$/',$item->organizer)?$item->organizer.'政府':$item->organizer)
                        }}
                    </td>
                    <td class="p-2 text-center border-r last:border-r-0">{!! Html::linkroute('dp.courseShow',
                        $item->name, $item->id) !!}</td>
                    <td class="p-2 text-center border-r last:border-r-0">{{ $item->phone }}</td>
                    <td class="p-2 text-center border-r last:border-r-0">{{ $item->email }}</td>
                    <td class="p-2 text-center border-r last:border-r-0">
                        <div class="flex flex-col items-start justify-start space-y-1">
                            <span class="whitespace-nowrap">
                                {{ $item->date_from }} ~ {{ $item->date_to }}
                            </span>
                            <div class="flex flex-row items-center justify-between w-full">
                                <span
                                    class="p-1 text-xs rounded-md text-white {{ $item->advance?'bg-amber-600':'bg-mainBlue' }}">{{
                                    $item->advance?'進階課程':'一般課程' }}</span>
                                @if((new Carbon\Carbon($item->date_from))->gt(today()))
                                <span class="p-1 text-xs text-white rounded-md bg-mainBlue">尚未開始</span>
                                @elseif((new Carbon\Carbon($item->date_to))->lt(today()))
                                <span class="p-1 text-xs text-white rounded-md bg-mainGrayDark">已結束</span>
                                @else
                                <span class="p-1 text-xs text-white rounded-md bg-lime-600">進行中</span>
                                @endif
                            </div>
                        </div>


                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

    </div>
</div>
@endsection

@section('js')

@endsection