@extends('admin.layouts.dashboard', [
'heading' => '檢視績效評估自評表',
'header_btn' => [
'批次打包匯出', route('admin.questionnaire.batch-export-form', request()->all()),
'匯出', route('admin.questionnaire.statistic.export', request()->all())
],
'breadcrumbs' => [
['績效評估自評表列表', route('admin.questionnaire.index')],
'分數統計表'
]
])
@section('title', '分數統計表')

@section('inner_content')
<div x-data="{
    loading:false,
 }" class="flex flex-col items-end justify-start w-full p-4 space-y-4">
    <div x-show="loading" class="absolute z-[1100] inset-0 bg-black/30 hidden justify-center items-center"
        :class="{'flex':loading,'hidden':!loading}">
        <div class="relative w-full h-full">
            <img src="/image/loading.svg" class="w-16 h-16 absolute left-1/2 top-[calc(50vh-2rem)]" alt="">
        </div>
    </div>
    @include('admin.questionnaires.partials.filter')
    <table class="w-full bg-white border shadow-lg text-mainAdminTextGrayDark">
        <thead>
            <tr class="border-b bg-mainLight">
                <th class="p-2 font-normal text-left border-r last:border-r-0"></th>
                <th class="p-2 font-normal text-left border-r last:border-r-0">績效評估</th>
                <th class="p-2 font-normal text-left border-r last:border-r-0">基本指標</th>
                <th class="p-2 font-normal text-left border-r last:border-r-0">進階指標</th>
                <th class="p-2 font-normal text-left border-r last:border-r-0">總分</th>
                <th class="p-2 font-normal text-left border-r last:border-r-0">加權總分</th>
            </tr>
        </thead>
        <tbody class="text-content text-mainAdminTextGrayDark sortable">
            @foreach ($data as $item)
            @foreach($item['questionnaires'] as $questionnaire)
            <tr class="border-b last:border-b-0 odd:bg-white even:bg-mainLight">
                <td class="p-2 border-r last:border-r-0">{{ $item['account'] }}</td>
                <td class="p-2 border-r last:border-r-0">
                    <div class="flex flex-row items-center justify-start space-x-2">
                        <a href="{{ route('admin.questionnaire.show', [$item['author_id'],
                    $questionnaire['id']]) }}" class="text-mainBlueDark">{{ $questionnaire['title'] }}</a>
                        <a href="{{ route('admin.questionnaire.export', ['account_id' => $item['author_id'], 'questionnaire_id' => $questionnaire['id']]) }}"
                            class="flex items-center justify-center w-20 h-10 bg-gray-100 border border-gray-300 cursor-pointer text-mainAdminTextGrayDark hover:bg-gray-50 ">打包匯出</a>
                    </div>
                </td>
                <td class="p-2 border-r last:border-r-0">{{ $questionnaire['basic_score'] }}</td>
                <td class="p-2 border-r last:border-r-0">{{ $questionnaire['advanced_score'] }}</td>
                <td class="p-2 border-r last:border-r-0">{{ $questionnaire['score'] }}</td>
                <td class="p-2 border-r last:border-r-0">{{ $questionnaire['weighted_score'] }}</td>
            </tr>
            @endforeach
            @endforeach
        </tbody>
    </table>
</div>
@endsection