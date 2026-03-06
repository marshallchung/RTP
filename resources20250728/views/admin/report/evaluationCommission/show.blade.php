<?php
$title = request('title', '成果資料展示');
?>

@extends('admin.layouts.dashboard', [
'heading' => $title . " - {$fullName} ({$year})",
'header_btn' => [
'匯出', route('admin.reports.evaluationCommission.export', ['id' => request()->route('id'), 'year' =>
request()->route('year')])
],
'breadcrumbs' => [
[$title, route('admin.reports.index')],
['管考作業', route('admin.reports.index')],
"{$fullName} ({$year})"
]
])

@section('title', $title . " - {$fullName} ({$year})")

@section('inner_content')
<div x-data="{}" class="flex flex-col items-end justify-start w-full p-4 space-y-4">
    <table class="w-full bg-white border shadow-lg text-mainAdminTextGrayDark">
        <thead>
            <tr class="border-b rounded-t bg-mainGray">
                <!-- 2016新增部分 -->
                <th class="p-2 font-normal text-left border-r w-14 last:border-r-0">編號</th>
                <th class="w-24 p-2 font-normal text-left border-r last:border-r-0">單位名稱</th>
                <th class="w-48 p-2 font-normal text-left border-r last:border-r-0">上傳日期</th>
                <th class="p-2 font-normal text-left border-r last:border-r-0">上傳項目</th>
                <th class="p-2 font-normal text-left border-r last:border-r-0">檔案名稱</th>
            </tr>
        </thead>
        <tbody class="text-content text-mainAdminTextGrayDark">
            @foreach($files as $key => $file)
            <tr class="border-b last:border-b-0 odd:bg-white even:bg-mainLight">
                <td class="p-2 text-center border-r last:border-r-0">{{ $key+1 }}</td>
                <td class="p-2 text-center border-r last:border-r-0">{{ $file->post->user->name }}</td>
                <td class="p-2 text-left border-r last:border-r-0">{{ $file->created_at }}</td>
                <td class="p-2 text-left border-r last:border-r-0">{{ $file->post->topic->title ??
                    $file->post->topic_id }}</td>
                <td class="p-2 text-left border-r last:border-r-0"><a href="{{ url($file->path) }}"
                        class="text-mainBlueDark">{{
                        $file->name }}</a></td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@stop