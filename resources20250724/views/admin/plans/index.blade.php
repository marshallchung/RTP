@extends('admin.layouts.dashboard', [
'heading' => '縣市季進度管制表',
'breadcrumbs' => [
['縣市季進度管制表', route('admin.seasonalReports.index')],
'展示111'
]
])

@section('title', '參考資料管理展示')

@section('inner_content')
<input type="hidden" value="{{ $year }}" id="js-year-input">
<input type="hidden" value="{{ route('admin.seasonalReports.index') }}" id="js-current-route">
<div class="flex flex-col w-full max-w-2xl p-4 text-content text-mainAdminTextGrayDark">
    <div x-data="{
    changeYear(e){
        window.location.href = '{{ route('admin.seasonalReports.index') }}/' + e.target.value;
    },
}" class="flex flex-row items-center justify-end mb-4">
        <span>{{ trans('app.report.changeYear') }}</span>
        <select id="js-year-select" x-model="year" @change="changeYear"
            class="inline-block w-auto ml-2 align-middle bg-white border-gray-300 rounded-md shadow-sm text-content text-mainAdminTextGrayDark placeholder:text-mainTextGray focus:border-sky-300 focus:ring focus:ring-sky-200 focus:ring-opacity-50">
            <option value="2027">116年</option>
            <option value="2026">115年</option>
            <option value="2025">114年</option>
            <option value="2024">113年</option>
            <option value="2023">112年</option>
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
                @foreach ($accounts as $index => $area)
                <div x-show="selectedTab==={{ $index }}" id="area-tab-{{ $index }}"
                    class="flex flex-col space-y-2 bg-white">
                    @foreach ($area['accounts'] as $account)
                    <div class="relative mb-6 bg-white border border-gray-200 rounded-sm">
                        <div
                            class="relative px-5 pt-3 pb-2 bg-gray-100 border-b-2 border-gray-200 text-mainAdminTextGrayDark">
                            <a href="#county-{{ $account->id }}" class="text-left accordion-toggle collapsed"
                                data-toggle="collapse">{{ $account->name }}</a>
                        </div>
                        <div id="county-{{ $account->id }}" class="panel-collapse collapse list-group">
                            <a href="{{ route('admin.seasonalReports.show', [$account->id, $year]) }}"
                                class="first:border-t-0 border-x-0 rounded-none relative block py-3 px-5 -mb-[1px] bg-white border">{{
                                $account->name }}政府</a>
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
@endsection