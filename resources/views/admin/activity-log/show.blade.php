@extends('admin.layouts.dashboard', [
'heading' => '日誌紀錄',
'breadcrumbs' =>[
['日誌紀錄', route('admin.activity-log.index')],
'檢視'
]
])

@section('title', '檢視日誌紀錄')

@section('styling')
@endsection

@section('inner_content')
<div x-data="" class="w-full max-w-[calc(100vw-230px)] text-mainAdminTextGrayDark"
    :class="{'max-w-[calc(100vw-230px)]':openMMC,'max-w-[calc(100vw-46px)]':!openMMC}" x-init="$nextTick(() => {
    document.querySelectorAll('pre.json').forEach(function (element) {
        let temp = element.innerText;
        if (temp !== '') {
            element.innerText=JSON.stringify(JSON.parse(temp), null, 2);
        }
    });
 })">
    <div class="relative flex flex-col w-full">
        <div class="flex-col items-start justify-start w-full p-5 space-y-4">
            <h2>日誌紀錄</h2>
            <table class="w-full border border-gray-300 shadow-lg text-mainAdminTextGrayDark">
                <tr class="border-b border-gray-300 last:border-b-0">
                    <th class="w-40 px-4 py-2 font-normal text-left border-r border-gray-300 last:border-r-0 ">#</th>
                    <td class="p-2 border-r border-gray-300 last:border-r-0 ">{{ $activity->id }}</td>
                </tr>
                <tr class="border-b border-gray-300 last:border-b-0">
                    <th class="px-4 py-2 font-normal text-left border-r border-gray-300 last:border-r-0 ">Log Name</th>
                    <td class="p-2 border-r border-gray-300 last:border-r-0 ">{{ $activity->log_name }}</td>
                </tr>
                <tr class="border-b border-gray-300 last:border-b-0">
                    <th class="px-4 py-2 font-normal text-left border-r border-gray-300 last:border-r-0 ">Description
                    </th>
                    <td class="p-2 border-r border-gray-300 last:border-r-0 ">{{ $activity->description }}</td>
                </tr>
                <tr class="border-b border-gray-300 last:border-b-0">
                    <th class="px-4 py-2 font-normal text-left border-r border-gray-300 last:border-r-0 ">Subject</th>
                    <td class="p-2 border-r border-gray-300 last:border-r-0 ">
                        <pre
                            class="border min-h-[2rem] json block p-2 text-xs break-all break-words rounded-sm whitespace-pre bg-gray-50">{{ $activity->subject }}</pre>
                    </td>
                </tr>
                <tr class="border-b border-gray-300 last:border-b-0">
                    <th class="px-4 py-2 font-normal text-left border-r border-gray-300 last:border-r-0 ">Subject Id
                    </th>
                    <td class="p-2 border-r border-gray-300 last:border-r-0 ">{{ $activity->subject_id }}</td>
                </tr>
                <tr class="border-b border-gray-300 last:border-b-0">
                    <th class="px-4 py-2 font-normal text-left border-r border-gray-300 last:border-r-0 ">Subject Type
                    </th>
                    <td class="p-2 border-r border-gray-300 last:border-r-0 ">{{ $activity->subject_type }}</td>
                </tr>
                <tr class="border-b border-gray-300 last:border-b-0">
                    <th class="px-4 py-2 font-normal text-left border-r border-gray-300 last:border-r-0 ">Causer</th>
                    <td class="p-2 border-r border-gray-300 last:border-r-0 ">
                        <pre
                            class="border min-h-[2rem] json block p-2 text-xs break-all break-words rounded-sm whitespace-pre bg-gray-50">{!! $activity->causer !!}</pre>
                    </td>
                </tr>
                <tr class="border-b border-gray-300 last:border-b-0">
                    <th class="px-4 py-2 font-normal text-left border-r border-gray-300 last:border-r-0 ">Causer Id</th>
                    <td class="p-2 border-r border-gray-300 last:border-r-0 ">{{ $activity->causer_id }}</td>
                </tr>
                <tr class="border-b border-gray-300 last:border-b-0">
                    <th class="px-4 py-2 font-normal text-left border-r border-gray-300 last:border-r-0 ">Causer Type
                    </th>
                    <td class="p-2 border-r border-gray-300 last:border-r-0 ">{{ $activity->causer_type }}</td>
                </tr>
                <tr class="border-b border-gray-300 last:border-b-0">
                    <th class="px-4 py-2 font-normal text-left border-r border-gray-300 last:border-r-0 ">Properties
                    </th>
                    <td class="p-2 border-r border-gray-300 last:border-r-0 ">
                        <pre
                            class="border min-h-[2rem] json block p-2 text-xs break-all break-words rounded-sm whitespace-pre bg-gray-50">{{ $activity->properties }}</pre>
                    </td>
                </tr>
                <tr class="border-b border-gray-300 last:border-b-0">
                    <th class="px-4 py-2 font-normal text-left border-r border-gray-300 last:border-r-0 ">Created At
                    </th>
                    <td class="p-2 border-r border-gray-300 last:border-r-0 ">{{ $activity->created_at }}</td>
                </tr>
                <tr class="border-b border-gray-300 last:border-b-0">
                    <th class="px-4 py-2 font-normal text-left border-r border-gray-300 last:border-r-0 ">IP</th>
                    <td class="p-2 border-r border-gray-300 last:border-r-0 ">{{ $activity->ip }}</td>
                </tr>
            </table>
        </div>
    </div>
</div>
@endsection

@section('scripts')
@parent
@endsection