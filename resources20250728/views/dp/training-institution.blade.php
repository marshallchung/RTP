@extends('layouts.app')

@section('title', '防災士培訓')
@section('subtitle', '培訓機構查詢')

@section('css')
@endsection

@section('content')
<div class="flex flex-row items-start justify-center w-full pb-8">
    <div class="flex flex-col items-start justify-start w-full space-y-6 overflow-x-scroll sm:items-center">
        <table class="w-full bg-white border shadow-lg text-mainAdminTextGrayDark min-w-[72rem]">
            <thead>
                <tr class="text-white border-b rounded-t bg-mainGrayDark">
                    <th class="p-2 font-bold border-r last:border-r-0">名稱</th>
                    <th class="w-24 p-2 font-bold border-r last:border-r-0">縣市</th>
                    <th class="p-2 font-bold border-r last:border-r-0">連絡電話</th>
                    <th class="w-1/4 p-2 font-bold border-r last:border-r-0">訓練地址</th>
                    <th class="w-24 p-2 font-bold border-r last:border-r-0">官方網站</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($data as $item)<tr class="bg-white border-b odd:bg-gray-100 last:border-b-0 last:rounded-b">
                    <td class="p-2 text-center border-r last:border-r-0">{{ $item->name }}</td>
                    <td class="p-2 text-center border-r last:border-r-0">{{ $item->county->name ?? '' }}</td>
                    <td class="p-2 text-center border-r last:border-r-0">{{ $item->phone }}</td>
                    <td class="p-2 text-center border-r last:border-r-0">{{ $item->address }}</td>
                    <td class="p-2 text-center border-r last:border-r-0">
                        @if($item->url)
                        <a href="{{ $item->url }}" target="_blank"><i class=" text-mainBlue i-fa6-solid-link"></i></a>
                        @endif
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