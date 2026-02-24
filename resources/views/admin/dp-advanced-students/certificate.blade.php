@extends('admin.layouts.dashboard', [
'heading' => '進階防災士培訓 > 進階防災士資料管理',
'breadcrumbs' => [
['進階防災士培訓', route('admin.dp-advanced-students.index')],
'依證書日期統計'
]
])

@section('title', '依證書日期統計')

@section('inner_content')
<div class="flex flex-col items-start justify-start w-full max-w-5xl p-4 space-y-6">
    <div class="relative w-full mb-6 bg-white border border-gray-200 rounded-sm">
        <div class="relative w-full px-5 pt-3 pb-2 bg-gray-100 border-b-2 border-gray-200 text-mainAdminTextGrayDark">統計
        </div>
        <div class="w-full p-5 m-0 bg-white">
            <table class="w-full bg-white border shadow-lg text-mainAdminTextGrayDark min-w-fit">
                <thead>
                    <tr class="border-b rounded-t bg-mainGray">
                        <th class="p-2 font-normal text-left border-r last:border-r-0">證書發放日期(年月)</th>
                        <th class="p-2 font-normal text-left border-r last:border-r-0">男性人數</th>
                        <th class="p-2 font-normal text-left border-r last:border-r-0">女性人數</th>
                        <th class="p-2 font-normal text-left border-r last:border-r-0">總計人數</th>
                    </tr>
                </thead>
                <tbody class="text-content text-mainAdminTextGrayDark">
                    @foreach($statistic as $key => $row)
                    <tr class="bg-white border-b last:border-b-0">
                        <td class="p-2 border-r last:border-r-0">{{ $row['ym'] }}</td>
                        <td class="p-2 border-r last:border-r-0">{{ $row['male'] }}</td>
                        <td class="p-2 border-r last:border-r-0">{{ $row['female'] }}</td>
                        <td class="p-2 border-r last:border-r-0">{{ $row['all'] }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection