@extends('admin.layouts.dashboard', [
'heading' => '優良範本資料',
'breadcrumbs' => [
['優良範本資料', route('admin.reports.index')],
'優良範本資料展示'
]
])

@section('title', '優良範本資料')

@section('inner_content')
@include('admin.report.partials.sample-filter')
<table class="w-full bg-white border shadow-lg text-mainAdminTextGrayDark">
    <thead>
        <tr>
            <th class="p-2 font-normal text-left border-r last:border-r-0">年度</th>
            <th class="p-2 font-normal text-left border-r last:border-r-0">類型</th>
            <th class="p-2 font-normal text-left border-r last:border-r-0">工作項目</th>
            <th class="p-2 font-normal text-left border-r last:border-r-0">檔案</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($topics as $topic)
        <tr>
            <td class="p-2 border-r last:border-r-0">{{ $topic->rootTopic->year }}</td>
            <td class="p-2 border-r last:border-r-0">
                @if($topic->type == 'county')
                縣市
                @elseif($topic->type == 'district')
                公所
                @elseif($topic->type == 'county,district')
                縣市及公所
                @endif
            </td>
            <td class="p-2 border-r last:border-r-0">{{ $topic->title }}</td>
            <td class="p-2 border-r last:border-r-0">
                @foreach($topic->reports as $report)
                @foreach($report->files as $file)
                {{ $report->user->full_county_name }}
                <a href="{{ url($file->file_path) }}" target="_blank" title="{{ $file->memo }}">
                    <i class="i-fa6-solid-file" aria-hidden="true"></i> {{ $file->name }}
                </a>
                <span class="text-gray-400">（{{ $file->created_at }}）</span>
                <br />
                @endforeach
                @endforeach
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
<div class="pull-right">{!! $topics->render() !!}</div>
<div id="js-token" class="hidden">{{ csrf_token() }}</div>
@endsection

@section('scripts')
<script src="{{ asset('scripts/generalIndex.js') }}"></script>
@endsection