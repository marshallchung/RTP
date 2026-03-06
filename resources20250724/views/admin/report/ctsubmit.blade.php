<?php
$title = request('title', '成果資料展示');
if (!isset($reportType)) $reportType = 'reports';
?>

@extends('admin.layouts.dashboard', [
'heading' => $title,
'breadcrumbs' => [
[$title, route('admin.' . $reportType . '.index')],
'上傳'
]
])

@section('title', $title . '資料上傳')

@section('inner_content')
<div x-data="{
    year:'{{ $year }}',
    changeYear(e){
        location.href = '/admin/reports/submit/' + this.year;
    }
}" class="relative w-full max-w-3xl p-4 text-content text-mainAdminTextGrayDark">
    <div class="relative mb-6 bg-white border border-gray-200 rounded-sm">
        <div class="p-5 m-0 bg-white">
            <div class="p-5 mb-4 border-l-2 border-l-sky-400 bg-sky-50/70">
                <p class="text-content text-mainAdminTextGrayDark">資料上傳須知：</p>
                <ol class="mb-4 ml-12 list-decimal">
                    <li class="text-content text-mainAdminTextGrayDark">{{ trans('app.report.allowedMimes') }}</li>
                    <li class="text-content text-mainAdminTextGrayDark">請點選送出按鈕，將上傳檔案提交</li>
                </ol>
            </div>
            <table class="w-full text-sm bg-white border shadow-lg text-mainAdminTextGrayDark">
                <thead>
                    <tr class="border-b bg-mainLight">
                        <th class="w-12 p-2 font-normal text-center border-r last:border-r-0">繳交</th>
                        <th class="p-2 font-normal text-left border-r last:border-r-0">工作項目</th>
                        <th class="p-2 font-normal text-center border-r w-28 last:border-r-0">檔案上傳</th>
                    </tr>
                </thead>
                <tbody class="text-content text-mainAdminTextGrayDark">
                    @foreach ($report as $category)
                    <tr class="bg-white border-b last:border-b-0">
                        <td colspan="3" class="p-2"><strong>{{ $category->name }}</strong></td>
                    </tr>
                    @foreach ($category->items as $item)
                    <tr class="bg-white border-b last:border-b-0">
                        <td class="p-2 text-center align-top border-r last:border-r-0">
                            <?php
                                    $hasFile = false;
                                    if ($reports = $item->reports) {
                                        foreach ($reports as $report) {
                                            if ($report->user_id == Auth::user()->id && count($report->files)) {
                                                $hasFile = true;
                                            }
                                        }
                                    }
                                    ?>
                            @if ($hasFile)
                            <i class="w-6 h-6 i-fa6-solid-circle-check text-lime-500"></i>
                            @endif
                        </td>
                        <td class="p-2 align-middle border-r last:border-r-0">
                            <p class="p-0 m-0 text-base"><strong>{{ $item->title }}</strong>
                            </p>
                            @if(isset($fileList[$item->id]))
                            @foreach($fileList[$item->id] as $eachYear => $files)
                            <div x-data="{
                                showCollapse:false,
                                deleteItem(e){
                                    if(confirm('確定要刪除嗎？')){
                                        var url=e.target.action;
                                        var token='{{ csrf_token() }}';
                                        var data = '_method=DELETE&_token=' + token + '&response_json=1';
                                        fetch(url,{
                                            method:'POST',
                                            body:data,
                                            headers: {
                                                'X-CSRF-TOKEN': token,
                                                'X-Requested-With':'XMLHttpRequest',
                                                'Accept': '*/*',
                                                'Content-Type': 'application/x-www-form-urlencoded; chartset=UTF-8',
                                            },
                                        })
                                        .then((response) => {
                                            if(response.status===200){
                                                location.reload();
                                            }else{
                                                alert('伺服器錯誤: ' + response.status);
                                                return false;
                                            }
                                        })
                                        .catch(function(error) {
                                            if (error.status == 429) {
                                                alert('嘗試登入次數過多，請稍後再試。');
                                            }else if (error.status == 419) {
                                                alert('頁面逾期，請重新輸入');
                                                location.reload();
                                            }else{
                                                alert('伺服器錯誤: ' + error.message);
                                            }
                                        })
                                    }
                                }
                            }" class="flex flex-col space-y-2">
                                <h6 class="flex flex-row items-center space-x-1">
                                    <button type="button" @click="showCollapse=!showCollapse"
                                        class="text-mainBlueDark">[+]</button>
                                    <span>{{ intval($eachYear)-1911 }} 年度</span>
                                </h6>
                                <div x-show="showCollapse" class="flex-col hidden space-y-1"
                                    :class="{'flex':showCollapse,'hidden':!showCollapse}">
                                    @foreach($files as $file)
                                    <div class="p-0 m-0">
                                        {!! Form::open(['method' => 'delete', 'route' =>
                                        ['admin.central.delete-file-in-submit-page', $file], 'class' =>
                                        'flex flex-row flex-wrap text-center', 'class' => 'inline-block',
                                        '@submit.prevent' => 'deleteItem']) !!}
                                        <button type="submit"
                                            class=" px-4 text-xs text-white rounded cursor-pointer py-1.5 bg-rose-600 hover:bg-rose-500">
                                            刪除
                                        </button>
                                        {!! Form::close() !!}
                                        <a href="{{ url($file->path) }}">{{ $file->name }}</a>
                                        <span class="text-mainAdminTextGray">&ndash; {{
                                            $file->created_at->format('n/j/Y
                                            G:i') }}</span>
                                        @if($file->memo)
                                        <span class="badge badge-info">優良事蹟</span>
                                        <span class="text-danger">{{ $file->memo }}</span>
                                        @endif
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                            @endforeach
                            @endif
                        </td>
                        <td class="text-center align-middle">
                            {!! Html::linkRoute('admin.'. $reportType . '.create',
                            '上傳檔案',
                            [$item->id, 'title' => $title, 'year' => $year],
                            ['class' => 'flex items-center justify-center w-24 h-8 text-sm text-white bg-sky-500
                            hover:bg-sky-400']) !!}
                        </td>
                    </tr>
                    @endforeach
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@stop