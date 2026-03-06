@extends('admin.layouts.dashboard', [
'heading' => '執行進度管制表',
'breadcrumbs' => [
['執行進度管制表', route('admin.seasonalReports.index')],
'展示'
]
])

@section('title', '參考資料管理展示')

@section('inner_content')
<input type="hidden" value="{{ $year }}" id="js-year-input">
<input type="hidden" value="{{ $season }}" id="js-season-input">
<input type="hidden" value="{{ route('admin.seasonalReports.index') }}" id="js-current-route">
<div x-data="{
    year:'{{ $year }}',
    season:'{{ $season }}',
    changeFilter(e){
        url='/admin/seasonalReports/' + this.year;
        if(this.season){
            url+='/' + this.season;
        }
        window.location.href = url;
    },
}" class="flex flex-col w-full max-w-2xl p-4 text-content text-mainAdminTextGrayDark">
    <div class="flex flex-row items-center justify-end mb-4">
        <span>{{ trans('app.report.changeYear') }}</span>
        <select id="js-year-select" @change="changeFilter" x-model="year"
            class="inline-block w-auto ml-2 mr-4 align-middle bg-white border-gray-300 rounded-md shadow-sm text-content text-mainAdminTextGrayDark placeholder:text-mainTextGray focus:border-sky-300 focus:ring focus:ring-sky-200 focus:ring-opacity-50 ">
            @foreach ($availableYears as $availableYear)
            <option value="{{ $availableYear }}">{{ intval($availableYear)-1911 }} 年</option>
            @endforeach
        </select>
        <span>期別</span>
        <select id="js-season-select" @change="changeFilter" x-model="season"
            class="inline-block w-auto ml-2 align-middle bg-white border-gray-300 rounded-md shadow-sm text-content text-mainAdminTextGrayDark placeholder:text-mainTextGray focus:border-sky-300 focus:ring focus:ring-sky-200 focus:ring-opacity-50 ">
            <option value="">全部</option>
            <option value="1">期初</option>
            <option value="2">期中</option>
            <option value="3">期末</option>
        </select>
    </div>
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
                @if (Auth::user()->origin_role > 2)
                <div class="flex flex-col space-y-2 bg-white">
                    <div
                        class="relative flex flex-row justify-between px-5 pt-3 pb-2 bg-gray-100 border-gray-200 text-mainAdminTextGrayDark">
                        <a href="{{ route('admin.seasonalReports.show', [
                                            Auth::user()->id, $year, $season])
                                        }}" class="flex-1 text-left text-mainAdminTextGrayDark">{{ Auth::user()->name
                            }}</a>
                        <div class="flex items-center justify-center w-5 h-5 rounded bg-mainGrayDark">
                            <i class="w-3 h-3 text-white i-heroicons-plus"></i>
                        </div>
                    </div>
                </div>
                @else
                @foreach ($accounts as $index => $area)
                <div x-show="selectedTab==={{ $index }}" id="area-tab-{{ $index }}"
                    class="flex flex-col space-y-2 bg-white">
                    @foreach ($area['accounts'] as $account)
                    <div class="relative bg-white border border-gray-200 rounded-sm">
                        <div
                            class="relative flex flex-row justify-between px-5 pt-3 pb-2 bg-gray-100 border-gray-200 text-mainAdminTextGrayDark">
                            <a href="{{ route('admin.seasonalReports.show', [ $account->id, $year, $season]) }}"
                                class="flex-1 text-left text-mainAdminTextGrayDark">
                                {{ $account->name }}
                            </a>
                            <div class="flex items-center justify-center w-5 h-5 rounded bg-mainGrayDark">
                                <i class="w-3 h-3 text-white i-heroicons-plus"></i>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
                @endforeach
                @endif
            </div>
        </div>
    </div>
</div>
@stop

@section('scripts')
<script src="{{ asset('scripts/reportsIndex.js') }}"></script>
@endsection