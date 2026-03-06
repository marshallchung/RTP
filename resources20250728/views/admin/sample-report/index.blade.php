@extends('admin.layouts.dashboard', [
'heading' => '優選範本資料',
'breadcrumbs' => [
['優選範本資料', route('admin.reports.index')],
'優選範本資料展示'
]
])

@section('title', '優選範本資料')

@section('inner_content')
<div x-data="{
    loading:false,
    getData(page){
        location.href = '{{ route('admin.news.index') }}?page=' + encodeURIComponent(page);
    },
    showMemo(e){
        let original_memo = e.target.querySelector('input[name=memo]').value;
        let memo = prompt('請輸入新評論？\n\n留空則移除評論', original_memo);
        if (memo === null) {
            return false;
        }
        e.target.querySelector('input[name=memo]').value = memo;
    },
 }" class="flex flex-col items-end justify-start w-full p-4 space-y-4">
    <div x-show="loading" class="absolute z-[1100] inset-0 bg-black/30 hidden justify-center items-center"
        :class="{'flex':loading,'hidden':!loading}">
        <div class="relative w-full h-full">
            <img src="/image/loading.svg" class="w-16 h-16 absolute left-1/2 top-[calc(50vh-2rem)]" alt="">
        </div>
    </div>
    @include('admin.sample-report.partials.filter')
    <table class="w-full bg-white border shadow-lg text-mainAdminTextGrayDark">
        <thead>
            <tr class="border-b bg-mainLight">
                <th class="p-2 font-normal text-left border-r last:border-r-0">年度</th>
                <th class="p-2 font-normal text-left border-r last:border-r-0">工作項目</th>
                <th class="p-2 font-normal text-left border-r last:border-r-0">檔案</th>
            </tr>
        </thead>
        <tbody class="text-content text-mainAdminTextGrayDark">
            @foreach ($rootTopics['data'] as $rootTopic)
            <tr>
                <td class="p-2 text-lg font-bold align-top border-r last:border-r-0">
                    <span class="whitespace-nowrap">
                        {{ intval($rootTopic['year'])-1911 }}年
                    </span>
                </td>
                <td colspan="2" class="p-2 text-lg font-bold align-top border-r last:border-r-0">
                    {{ $rootTopic['title'] }}
                </td>
            </tr>
            @foreach ($rootTopic['topics'] as $topic)
            <tr class="border border-b">
                <td class="p-2 align-top border-r last:border-r-0"></td>
                <td class="p-2 pl-8 break-all text-left align-top border-r last:border-r-0 max-w-[50%]">
                    {{ $topic['title'] }}
                </td>
                <td class="p-2 border-r last:border-r-0">
                    <ul class="list-none">
                        @foreach($topic['sample_reports'] as $sampleReport)
                        @if($isAbleToReviewReportSample || $authUser->owns($sampleReport) ||
                        $sampleReport['is_sample'])
                        <li class="flex flex-row flex-wrap items-center justify-start py-1">
                            @if ($isAbleToReviewReportSample)
                            {!! Form::open(['route' => ['admin.sample-report.sample-review-update-is-sample',
                            $sampleReport['id']], 'class' => 'flex flex-row items-center justify-start']) !!}
                            @if($sampleReport['is_sample'])
                            {!! Form::hidden('is_sample', 0) !!}
                            <button type="submit" class="h-6 text-xs text-white w-14 bg-lime-500">優選範本</button>
                            @else
                            {!! Form::hidden('is_sample', 1) !!}
                            <button type="submit"
                                class="h-6 text-xs bg-gray-100 border border-gray-300 text-mainAdminTextGrayDark w-14">推薦名單</button>
                            @endif
                            {!! Form::close() !!}
                            @else
                            @if($sampleReport['is_sample'])
                            <a href="javascript:void(0)"
                                class="flex items-center justify-center h-6 m-1 text-xs text-white w-14 bg-lime-500 disabled">優選範本</a>
                            @else
                            <a href="javascript:void(0)"
                                class="flex items-center justify-center h-6 m-1 text-xs bg-gray-100 border border-gray-300 text-mainAdminTextGrayDark w-14 disabled">推薦名單</a>
                            @endif
                            @endif
                            @if($authUser->owns($sampleReport))
                            {!! Form::open(['method' => 'DELETE', 'route' => ['admin.sample-report.destroy',
                            $sampleReport['id']],
                            'class' => 'flex flex-row flex-wrap text-center', 'style' => 'display: inline-block'
                            ,'onSubmit'
                            => "return
                            confirm('確定要刪除嗎？');"]) !!}
                            <button type="submit"
                                class="flex items-center justify-center w-6 h-6 text-sm text-white bg-orange-600 rounded cursor-pointer hover:bg-orange-500">
                                <i class="w-2.5 h-2.5 i-fa6-solid-trash"></i>
                            </button>
                            {!! Form::close() !!}
                            @endif
                            <span class="m-1">{{ $sampleReport['user']['full_county_name'] }}</span>
                            @foreach($sampleReport['files'] as $file)
                            <a href="{{ url($file['path']) }}" target="_blank" title="{{ $file['memo'] }}"
                                class="m-1 text-mainBlueDark">
                                <i class="i-fa6-solid-file" aria-hidden="true"></i> {{ $file['name'] }}
                            </a>
                            <span class="text-sm text-gray-400 whitespace-nowrap">（{{ date('Y-m-d
                                H:i:s',strtotime($file['created_at'])) }}）</span>
                            @endforeach
                            @if ($isAbleToReviewReportSample)
                            {!! Form::open(['@submit'=>'showMemo','route' =>
                            ['admin.sample-report.sample-review-update-memo', $sampleReport['id']],
                            'class' => 'update-memo flex flex-row flex-wrap text-center']) !!}
                            {!! Form::hidden('memo', $sampleReport['memo']) !!}
                            <button type="submit"
                                class="h-6 px-1 m-1 text-xs text-white bg-sky-500 whitespace-nowrap">編輯優良事蹟</button>
                            {!! Form::close() !!}
                            @endif
                            @if($sampleReport['memo'])
                            <br />
                            <span
                                class="flex items-center justify-center h-6 px-1 m-1 text-xs text-white rounded-full bg-sky-500 whitespace-nowrap">優良事蹟</span>
                            <span class="text-xs text-rose-600">{{ $sampleReport['memo'] }}</span>
                            @endif
                        </li>
                        @endif
                        @endforeach
                    </ul>
                    @if ($isAbleToCreateReports)
                    <a href="{{ route('admin.sample-report.create', $topic['id']) }}"
                        class='flex items-center justify-center w-24 h-8 text-sm text-white bg-sky-500 hover:bg-sky-400'>上傳檔案</a>
                    @endif
                </td>
            </tr>
            @endforeach
            @endforeach
        </tbody>
    </table>
    <div class="">{!! $pagination !!}</div>
    <div id="js-token" class="hidden">{{ csrf_token() }}</div>
</div>
@endsection

@section('scripts')
@endsection