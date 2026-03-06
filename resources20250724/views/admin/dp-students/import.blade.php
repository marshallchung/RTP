@extends('admin.layouts.dashboard', [
'heading' => '防災士培訓 > 防災士資料管理',
'breadcrumbs' => [
['防災士培訓', route('admin.dp-students.index')],
'匯入受訓者與防災士資料'
]
])

@section('title', '匯入受訓者與防災士')

@section('inner_content')
{!! Form::open(['method' => 'POST', 'route' => 'admin.dp-students.import', 'files' => true]) !!}
<div class="flex flex-col items-start justify-start w-full max-w-4xl p-4 space-y-6">
    <div class="relative w-full mb-6 bg-white border border-gray-200 rounded-sm">
        <div class="relative px-5 pt-3 pb-2 bg-gray-100 border-b-2 border-gray-200 text-mainAdminTextGrayDark">匯入</div>
        <div class="p-5 m-0 bg-white">
            <div class="alert alert-info">
                <ul>
                    <li>
                        請使用 {!! link_to_route('admin.dp-students.download-import-sample', '防災士公版表格.xlsx', null, ['class'
                        => 'alert-link']) !!}
                        格式進行匯入，
                        {!! link_to_route('admin.dp-students.download-import-sample', '範例檔下載', ['example'], ['class' =>
                        'alert-link']) !!}
                    </li>
                    <li>
                        必填欄位：於上述表格中註記，若必填欄位未填寫完整，則該列將不匯入
                    </li>
                    <li>預設密碼為生日</li>
                    <li>使用者應自行核對預計上傳資料筆數與上傳後系統顯示實際匯入資料筆數是否一致。</li>
                    <li>僅有第一個工作表會被匯入</li>
                </ul>
            </div>
            @if(session('errorMessages'))
            <div class="alert alert-danger">
                <strong>發生錯誤：</strong>
                <ul class="mb-0">
                    @foreach(session('errorMessages') as $errorMessage)
                    <li>{{ $errorMessage }}</li>
                    @endforeach
                </ul>
            </div>
            @endif
            {{-- <div class="flex flex-row flex-wrap text-center">--}}
                {{-- {!! Form::label('county_id', '所屬縣市（必填）') !!}--}}
                {{-- {!! Form::select('county_id', $counties, null, ['class' => 'h-12 px-4 border-gray-300 rounded-md
                shadow-sm focus:border-cyan-300 focus:ring focus:ring-cyan-200 focus:ring-opacity-50 w-full',
                'required']) !!}--}}
                {{-- </div>--}}
            <div class="flex flex-col items-start justify-center pb-6 space-y-6">
                {!! Form::label('file', '檔案上傳') !!}
                {!! Form::file('import_file', ['accept' => '.xls,.xlsx', 'required']) !!}
                {!! Form::text('Multiple', request('Multiple'), ['class' => 'h-12 px-4 border-gray-300 rounded-md
                shadow-sm focus:border-cyan-300 focus:ring focus:ring-cyan-200 focus:ring-opacity-50 w-full',
                'placeholder' => '多元化防災士'])
                !!}
            </div>

            {!! Form::submit('送出', ['class' => 'w-full cursor-pointer hover:bg-teal-400 text-white flex justify-center
            items-center h-10 bg-mainCyanDark
            rounded']) !!}
        </div>
    </div>
    <div class="relative w-full bg-white border border-gray-200 rounded-sm">
        <div class="relative w-full bg-white border border-gray-200 rounded-sm">
            <div class="relative px-5 pt-3 pb-2 bg-gray-100 border-b-2 border-gray-200 text-mainAdminTextGrayDark">歷史紀錄
            </div>
            <table class="w-full bg-white border shadow-lg text-mainAdminTextGrayDark min-w-fit">
                <thead>
                    <tr class="border-b rounded-t bg-mainGray">
                        <th class="p-2 font-normal text-left border-r last:border-r-0">檔名</th>
                        <th class="p-2 font-normal text-left border-r last:border-r-0">修改時間</th>
                    </tr>
                </thead>
                <tbody class="text-content text-mainAdminTextGrayDark">
                    @forelse($files as $file)
                    <tr class="bg-white border-b last:border-b-0">
                        <td class="p-2 border-r last:border-r-0">
                            <a href="{{ route('admin.dp-students.download-imported-file', $file['filename']) }}">{{
                                $file['filename'] }}</a>
                        </td>
                        <td class="p-2 border-r last:border-r-0">{{ $file['mtime'] }}</td>
                    </tr>
                    @empty
                    <tr class="bg-white border-b last:border-b-0">
                        <td colspan="2" class="p-2 text-center border-r last:border-r-0">暫無</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
{!! Form::close() !!}
<script src="{{ asset('scripts/genericPostForm.js') }}"></script>
@endsection