@extends('admin.layouts.dashboard', [
'heading' => '檢視績效評估自評表',
'header_btn' => [],
'breadcrumbs' => [
['績效評估自評表列表', route('admin.questionnaire.index')],
['分數統計表', route('admin.questionnaire.statistic')],
'批次打包匯出'
]
])
@section('title', '批次打包匯出')

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
            </tr>
        </thead>
        <tbody>
            @foreach ($accounts as $account)
            @foreach($questionnaires as $questionnaire)
            <tr>
                @if($loop->first)
                <td rowspan="{{ $questionnaires->count() }}">{{ $account->name }}</td>
                @endif
                <td class="p-2 border-r last:border-r-0">
                    {{ $questionnaire->title }}
                    <a href="{{ route('admin.questionnaire.batch-export', ['account_id' => $account->id, 'questionnaire_id' => $questionnaire->id]) }}"
                        class="flex items-center justify-center w-20 h-10 bg-gray-100 border border-gray-300 text-mainAdminTextGrayDark cursor-pointer hover:bg-gray-50 ">打包匯出</a>
                </td>
            </tr>
            @endforeach
            @endforeach
        </tbody>
    </table>
</div>
@endsection