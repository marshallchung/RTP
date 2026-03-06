@extends('admin.layouts.dashboard', [
'heading' => '進階防災士培訓 > 進階防災士資料身分證查詢',
'breadcrumbs' => [
['進階防災士培訓', route('admin.dp-advanced-students.index')],
'身分證查詢'
]
])

@section('title', '進階防災士資料身分證查詢')

@section('inner_content')
<div class="flex flex-col items-start justify-start w-full p-4 space-y-6 max-w-7xl" id="search-form">
    <div class="flex flex-row flex-wrap justify-end w-full">
        {!! Form::open(['method' => 'POST', 'route' => 'admin.dp-advanced-students.inquire', 'class' => 'flex flex-row
        flex-wrap
        items-center justify-end space-x-4']) !!}
        <input name="tid" placeholder="身分證字號"
            class="w-64 h-10 px-4 border-gray-300 rounded-md shadow-sm focus:border-cyan-300 focus:ring focus:ring-cyan-200 focus:ring-opacity-50"
            required>
        <button type="submit" class="flex items-center justify-center w-20 h-10 text-white bg-mainCyanDark">搜尋</button>
        {!! Form::close() !!}
        {!! Form::open(['method' => 'POST', 'route' => 'admin.dp-advanced-students.inquire', 'id' => 'file-upload-form',
        'class'
        => 'flex flex-row flex-wrap items-center justify-end space-x-4', 'files' => true]) !!}
        <input type="file" name="import_file"
            accept="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel">
        <button type="submit"
            class="flex items-center justify-center w-auto h-10 px-4 text-white bg-mainCyanDark">Excel上傳</button>
        {!! Form::close() !!}
        <a href="{{ route('admin.dp-advanced-students.download-inquire-input-sample') }}"
            class="flex items-center justify-center w-auto h-10 px-4 bg-gray-100 border border-gray-300 text-mainAdminTextGrayDark hover:bg-gray-50">範例檔下載</a>
    </div>
    <div class="relative w-full mb-6 bg-white border border-gray-200 rounded-sm">
        <div class="relative px-5 pt-3 pb-2 bg-gray-100 border-b-2 border-gray-200 text-mainAdminTextGrayDark">身分證查詢
        </div>
        <table class="w-full bg-white text-mainAdminTextGrayDark">
            <thead>
                <tr class="border-b bg-mainLight">
                    <th class="p-2 font-normal text-center border-r last:border-r-0 whitespace-nowrap">身分證字號</th>
                    <th class="p-2 font-normal text-center border-r last:border-r-0 whitespace-nowrap">證書編號</th>
                    <th class="p-2 font-normal text-center border-r last:border-r-0 whitespace-nowrap">授證日期</th>
                    <th class="p-2 font-normal text-center border-r last:border-r-0 whitespace-nowrap">姓名</th>
                    <th class="p-2 font-normal text-center border-r last:border-r-0 whitespace-nowrap">性別</th>
                    <th class="p-2 font-normal text-center border-r last:border-r-0 whitespace-nowrap">所屬縣市</th>
                    <th class="p-2 font-normal text-center border-r last:border-r-0 whitespace-nowrap">狀態</th>
                    <th class="p-2 font-normal text-center border-r last:border-r-0 whitespace-nowrap">課程</th>
                </tr>
            </thead>
            <tbody class="text-content text-mainAdminTextGrayDark">
                @foreach($queryResults as $queryResult)
                <tr class="border-b last:border-b-0 odd:bg-white even:bg-mainLight">
                    <td class="p-2 text-center border-r last:border-r-0">
                        {{ $queryResult['TID'] }}
                    </td>
                    <td class="p-2 text-center border-r last:border-r-0">
                        {{ $queryResult['certificate'] }}
                    </td>
                    <td class="p-2 text-center border-r last:border-r-0">
                        {{ $queryResult['date_first_finish'] }}
                    </td>
                    <td
                        class="p-2 text-center border-r last:border-r-0 {{ ($queryResult['date_first_finish']!=null && $queryResult['date_first_finish']<$queryResult['expire_date'])?'text-red-600':'text-mainAdminTextGrayDark' }}">
                        {{ $queryResult['name'] }}
                    </td>
                    <td class="p-2 text-center border-r last:border-r-0" x-text="data_item.gender">
                        {{ $queryResult['gender'] }}
                    </td>
                    <td class="p-2 text-center border-r last:border-r-0">
                        {{ $queryResult['county']?$queryResult['county']['name']:'' }}
                    </td>
                    <td class="p-2 text-center border-r last:border-r-0">
                        {{ $queryResult['state'] }}
                    </td>
                    <td class="border-r last:border-r-0">
                        <div class="flex flex-col items-start justify-start p-1">
                            @foreach($queryResult['student_subjects'] as $subject_item)
                            <span class="p-1">
                                {{
                                $subject_item['name'] .
                                ($subject_item['start_date']==null?'':('（' . $subject_item['start_date'] . '）'))
                                }}
                            </span>
                            @endforeach
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection

@section('scripts')
<script>
    $(function () {
            let last_file_change_time = 0;
            $('#file-upload-form').on('submit', function (e) {
                if (last_file_change_time < Date.now() - 1000) {
                    e.preventDefault();
                    $('input[name="import_file"]').click();
                    return false;
                }
            });
            $('input[name="import_file"]').on('change', function (e) {
                if (this.files.length > 0) {
                    last_file_change_time = new Date().getTime();
                    $('#file-upload-form').submit();
                }
            });
        });
</script>
@endsection