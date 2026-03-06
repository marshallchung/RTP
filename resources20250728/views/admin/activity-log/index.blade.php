@extends('admin.layouts.dashboard', [
'heading' => '日誌紀錄',
'breadcrumbs' => ['日誌紀錄']
])

@section('title', '日誌紀錄')

@section('inner_content')
<div class="flex flex-col items-end justify-start w-full p-4 space-y-4">
    <table class="w-full bg-white border shadow-lg text-mainAdminTextGrayDark">
        <thead>
            <tr class="border-b bg-mainLight">
                <th class="p-2 font-normal text-left border-r last:border-r-0">#編號</th>
                <th class="p-2 font-normal text-left border-r last:border-r-0">Log名稱(Log Name)</th>
                <th class="p-2 font-normal text-left border-r last:border-r-0">說明(Description)</th>
                <th class="p-2 font-normal text-left border-r last:border-r-0">操作者ID<BR>(Causer ID)</th>
                <th class="p-2 font-normal text-left border-r last:border-r-0">操作型態(Subject Type)</th>
                <th class="p-2 font-normal text-left border-r last:border-r-0">操作事件ID<BR>(Subject ID)</th>
                <!-- <th class="p-2 font-normal text-left border-r last:border-r-0">Causer Type</th> -->
                <th class="p-2 font-normal text-left border-r last:border-r-0">Log時間<BR>(Created At)</th>
                <th class="p-2 font-normal text-left border-r last:border-r-0">操作細節<BR>(Action)</th>
            </tr>
        </thead>
        <tbody class="text-content text-mainAdminTextGrayDark">
            @foreach ($activityLogs as $activityLog)
            <tr class="p-2 text-left border-r last:border-r-0 odd:bg-white even:bg-mainLight">
                <td class="p-2 border-r last:border-r-0">{{ $activityLog->id }}</td>
                <td class="p-2 border-r last:border-r-0">{{ $activityLog->log_name }}</td>
                <td class="p-2 border-r last:border-r-0">{{ $activityLog->description }}</td>
                <td class="p-2 border-r last:border-r-0">{{ $activityLog->causer_id }}</td>
                <td class="p-2 border-r last:border-r-0">{{ $activityLog->subject_type }}</td>
                <td class="p-2 border-r last:border-r-0">{{ $activityLog->subject_id }}</td>
                <!-- <td class="p-2 border-r last:border-r-0">{{ $activityLog->causer_type }}</td> -->
                <td class="p-2 border-r last:border-r-0">{{ $activityLog->created_at }}</td>
                <td class="p-2 text-center border-r last:border-r-0">{{ link_to_route('admin.activity-log.show', '檢視',
                    $activityLog,['class'=>'text-mainBlueDark'])
                    }}
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <div class="pull-right">{!! $activityLogs->render() !!}</div>
    @endsection