@extends('admin.layouts.dashboard', [
'heading' => '優良範本資料',
'breadcrumbs' => [
['優良範本資料', route('admin.reports.index')],
'優良範本資料審核'
]
])

@section('title', '優良範本資料')

@section('inner_content')
@include('admin.report.partials.sample-filter')
<table x-data="{
    showMemo(e){
        let original_memo = e.target.querySelector('input[name=memo]').value;
        let memo = prompt('請輸入新評論？\n\n留空則移除評論', original_memo);
        if (memo === null) {
            return false;
        }
        e.target.querySelector('input[name=memo]').value = memo;
    }
}" class="w-full bg-white border shadow-lg text-mainAdminTextGrayDark">
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
                {!! Form::open(['route' => ['admin.reports.post-sample-review-update-is-sample', $file], 'class' =>
                'flex flex-row flex-wrap text-center', 'style' => 'display: inline-block']) !!}
                @if($file->is_sample)
                {!! Form::hidden('is_sample', 0) !!}
                <button type="submit" class="h-6 text-xs text-white w-14 bg-lime-500">優良範本</button>
                @else
                {!! Form::hidden('is_sample', 1) !!}
                <button type="submit"
                    class="h-6 text-xs bg-gray-100 border border-gray-300 text-mainAdminTextGrayDark w-14">推薦名單</button>
                @endif
                {!! Form::close() !!}
                {!! Form::open(['@submit'=>'showMemo','route' => ['admin.reports.post-sample-review-update-memo',
                $file], 'class' => 'update-memo flex flex-row flex-wrap text-center', 'style' => 'display:
                inline-block']) !!}
                {!! Form::hidden('memo', $file->memo) !!}
                <button type="submit" class="h-6 px-1 text-xs text-white bg-sky-500">編輯優良事蹟</button>
                {!! Form::close() !!}
                @if($file->memo)
                <br />
                <span
                    class="flex items-center justify-center h-6 px-1 text-xs text-white rounded-full bg-sky-500 whitespace-nowrap">優良事蹟</span>
                <span class="text-xs text-rose-600">{{ $file->memo }}</span>
                @endif
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
<script>
    $('form.update-memo').on('submit', function () {
            let original_memo = $(this).find('[name=memo]').val();
            //確認視窗＆填寫評語
            let memo = prompt('請輸入新評論？\n\n留空則移除評論', original_memo);
            //點擊取消
            if (memo === null) {
                return false;
            }
            //替換表單內容
            $(this).find('[name=memo]').val(memo);
        })
</script>
@endsection