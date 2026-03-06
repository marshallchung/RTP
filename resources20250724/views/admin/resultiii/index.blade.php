<?php
$title = request('title', '成果資料（三期）');
?>

@extends('admin.layouts.dashboard', [
'heading' => $title,
'breadcrumbs' => [
[$title, route('admin.resultiii.index')],
'成果展示'
]
])

@section('title', $title)

@section('inner_content')
<input type="hidden" value="{{ route('admin.resultiii.index') }}" id="js-current-route">
<div class="flex flex-col w-full max-w-2xl p-4 text-content text-mainAdminTextGrayDark">
    <div class="flex flex-row flex-wrap w-full p-5 text-center bg-white">
        <div x-data="{
                    selectedTab:0,
                }" id="js-select-county" class="flex flex-col w-full">
            <ul class="flex flex-row items-center justify-start space-x-2">
                @foreach ($accounts as $index => $area)
                <li
                    :class="{'bg-mainCyanDark border-0 text-white':selectedTab==={{ $index }},'bg-gray-100 border text-mainAdminTextGrayDark border-b-0':selectedTab!=={{ $index }}}">
                    <button type="button" @click="selectedTab={{ $index }}"
                        class="flex items-center justify-center w-20 h-10">{{
                        $area['name'] }}</button>
                </li>
                @endforeach
            </ul>
            <div class="w-full p-4 border">
                @foreach ($accounts as $index => $area)
                <div x-show="selectedTab==={{ $index }}" id="area-tab-{{ $index }}"
                    class="flex flex-col space-y-2 bg-white">
                    @foreach ($area['accounts'] as $account)
                    <div x-data="{panelOpen:false}" class="flex flex-col border rounded">
                        <div class="relative px-5 pt-3 pb-2 text-mainAdminTextGrayDark bg-mainLight">
                            <button type="button" @click="panelOpen=!panelOpen"
                                class="flex flex-row items-center justify-between w-full">
                                <span>{{ $account->name }}</span>
                                <div class="flex items-center justify-center w-5 h-5 rounded bg-mainGrayDark">
                                    <i class="w-3 h-3 text-white"
                                        :class="{'i-heroicons-minus':panelOpen,'i-heroicons-plus':!panelOpen}"></i>
                                </div>
                            </button>
                        </div>
                        <div x-show="panelOpen" x-transition id="county-{{ $account->id }}" class="flex-col hidden"
                            :class="{'flex':panelOpen,'hidden':!panelOpen}">
                            <a href="{{ route('admin.resultiii.show', [$account->id, 'title' => $title]) }}"
                                class="relative block px-5 py-3 text-left border-t">{{
                                $account->name }}政府</a>
                            @foreach ($account->districts['1'] as $district)
                            <a href="{{ route('admin.resultiii.show', [$district->id, 'title' => $title]) }}"
                                class="relative block px-5 py-3 text-left border-t">
                                &nbsp;&nbsp;&ndash;&nbsp;&nbsp;{{ $district->name }}</a>
                            @endforeach
                            @foreach ($account->districts['2'] as $district)
                            <a href="{{ route('admin.resultiii.show', [$district->id, 'title' => $title]) }}"
                                class="relative block px-5 py-3 text-left border-t">
                                &nbsp;&nbsp;&ndash;&nbsp;&nbsp;{{ $district->name }}</a>
                            @endforeach
                        </div>
                    </div>
                    @endforeach
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
@stop

@section('scripts')
<script src="{{ asset('scripts/reportsIndex.js') }}"></script>
@endsection