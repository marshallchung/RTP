@extends('admin.layouts.dashboard', [
'heading' => '防災士培訓 > 防災士資料身分證查詢',
'breadcrumbs' => [
['防災士培訓', route('admin.dp-students.index')],
'身分證查詢'
]
])

@section('title', '防災士資料身分證查詢')

@section('inner_content')
<div class="flex flex-col items-start justify-start w-full max-w-5xl p-4 space-y-6" id="search-form">
    <div class="flex flex-row flex-wrap justify-end w-full">
        {!! Form::open(['method' => 'POST', 'route' => 'admin.dp-students.inquire', 'class' => 'flex flex-row flex-wrap
        items-center justify-end space-x-4']) !!}
        <input name="tid" placeholder="身分證字號"
            class="w-64 h-10 px-4 border-gray-300 rounded-md shadow-sm focus:border-cyan-300 focus:ring focus:ring-cyan-200 focus:ring-opacity-50"
            required>
        <button type="submit" class="flex items-center justify-center w-20 h-10 text-white bg-mainCyanDark">搜尋</button>
        {!! Form::close() !!}
        {!! Form::open(['method' => 'POST', 'route' => 'admin.dp-students.inquire', 'id' => 'file-upload-form', 'class'
        => 'flex flex-row flex-wrap items-center justify-end space-x-4', 'files' => true]) !!}
        <input type="file" name="import_file"
            accept="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel">
        <button type="submit"
            class="flex items-center justify-center w-auto h-10 px-4 text-white bg-mainCyanDark">Excel上傳</button>
        {!! Form::close() !!}
        <a href="{{ route('admin.dp-students.download-inquire-input-sample') }}"
            class="flex items-center justify-center w-auto h-10 px-4 bg-gray-100 border border-gray-300 text-mainAdminTextGrayDark hover:bg-gray-50">範例檔下載</a>
    </div>
    <div class="relative w-full mb-6 bg-white border border-gray-200 rounded-sm">
        <div class="relative px-5 pt-3 pb-2 bg-gray-100 border-b-2 border-gray-200 text-mainAdminTextGrayDark">身分證查詢
        </div>
        <div class="p-5 m-0 bg-white">
            <table class="w-full bg-white border shadow-lg text-mainAdminTextGrayDark min-w-fit">
                <thead>
                    <tr class="border-b rounded-t bg-mainGray">
                        <th class="w-1/3 p-2 font-normal text-left border-r last:border-r-0">身分證字號</th>
                        <th class="w-1/3 p-2 font-normal text-left border-r last:border-r-0">姓名</th>
                        <th class="w-1/3 p-2 font-normal text-left border-r last:border-r-0">防災士培訓狀態</th>
                    </tr>
                </thead>
                <tbody class="text-content text-mainAdminTextGrayDark">
                    @foreach($queryResults as $queryResult)
                    <tr class="bg-white border-b last:border-b-0">
                        <td class="p-2 border-r last:border-r-0">{{ $queryResult['TID'] }}</td>
                        <td class="p-2 border-r last:border-r-0">{{ $queryResult['name'] }}</td>
                        <td class="p-2 border-r last:border-r-0">{{ $queryResult['status'] }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
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