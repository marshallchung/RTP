@extends('admin.layouts.dashboard', [
'heading' => '防災士培訓 > 師資資料庫管理',
'breadcrumbs' => [
['師資資料庫管理', route('admin.dp-teachers.index')],
'師資統計'
]
])

@section('title', '師資統計')

@section('inner_content')
<div class="flex flex-col items-start justify-start w-full p-4 space-y-4">
    <div class="relative w-full max-w-5xl mb-6 bg-white border border-gray-200 rounded-sm">
        <div class="relative w-full px-5 pt-3 pb-2 bg-gray-100 border-b-2 border-gray-200 text-mainAdminTextGrayDark">檢視
        </div>
        <div class="flex flex-col p-5 space-y-5 bg-white">
            <span>防災士師資總人數{{ $dpTeacherCount }}人，無逾期師資人數{{ $dpTeacherNotExpiredCount }}人，逾期師資人數{{ $dpTeacherExpiredCount
                }}人</span>
            <table class="w-full bg-white border shadow-lg text-mainAdminTextGrayDark">
                <thead>
                    <tr class="border-b rounded-t bg-mainGray">
                        <th class="p-2 font-normal text-left border-r last:border-r-0">教授科目編號</th>
                        <th class="p-2 font-normal text-left border-r last:border-r-0">教授科目名稱</th>
                        <th class="p-2 font-normal text-left border-r last:border-r-0">基本師資人數</th>
                        <th class="p-2 font-normal text-left border-r last:border-r-0">種子師資人數</th>
                        <th class="p-2 font-normal text-left border-r last:border-r-0">逾期師資人數</th>
                    </tr>
                </thead>
                <tbody class="text-content text-mainAdminTextGrayDark">
                    @foreach ($dpTeacherSubjects as $dpTeacherSubject)
                    <tr class="bg-white border-b last:border-b-0">
                        <td class="p-2 border-r last:border-r-0">
                            {{ $dpTeacherSubject->dp_subject_id }}
                        </td>
                        <td class="p-2 border-r last:border-r-0">{{ $dpTeacherSubject->dpSubject->name }}</td>
                        <td class="p-2 border-r last:border-r-0">
                            {{ $dpTeacherSubject->count_basic }}
                        </td>
                        <td class="p-2 border-r last:border-r-0">
                            {{ $dpTeacherSubject->count_seed - $dpTeacherSubject->count_expired }}
                        </td>
                        <td class="p-2 border-r last:border-r-0">
                            {{ $dpTeacherSubject->count_expired }}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection