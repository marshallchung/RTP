@extends('admin.layouts.dashboard', [
'heading' => '推動韌性社區 > 查詢與管理韌性社區資料',
'breadcrumbs' => [
['查詢與管理韌性社區資料', route('admin.dc-units.index')],
'匯入'
]
])

@section('title', '匯入韌性社區資料')

@section('inner_content')
{!! Form::open(['method' => 'POST', 'route' => 'admin.dc-units.import', 'files' => true]) !!}
<div class="flex flex-col items-start justify-start w-full max-w-4xl p-4 space-y-6">
    <div class="relative w-full mb-6 bg-white border border-gray-200 rounded-sm">
        <div class="relative px-5 pt-3 pb-2 bg-gray-100 border-b-2 border-gray-200 text-mainAdminTextGrayDark">匯入</div>
        <div class="p-5 m-0 bg-white">
            <div class="alert alert-info">
                <ul>
                    <li>
                        請使用 {!! link_to_route('admin.dc-units.download-import-sample', '韌性社區公版格式.xlsx', null, ['class'
                        => 'alert-link']) !!}
                        格式進行匯入，
                        {!! link_to_route('admin.dc-units.download-import-sample', '範例檔下載', ['example'], ['class' =>
                        'alert-link']) !!}
                    </li>
                    <li>"所在縣市"及"過去是否曾推動過防災社區"欄位為點選下拉選單之選項，請勿手動填寫其他答案。</li>
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
            <div class="flex flex-col items-start justify-center pb-6 space-y-6">
                {!! Form::label('file', '檔案上傳') !!}
                {!! Form::file('import_file', ['accept' => '.xls,.xlsx', 'required']) !!}
            </div>

            {!! Form::submit('送出', ['class' => 'w-full cursor-pointer hover:bg-teal-400 text-white flex justify-center
            items-center h-10 bg-mainCyanDark
            rounded']) !!}
        </div>
    </div>
</div>
{!! Form::close() !!}
<script src="{{ asset('scripts/genericPostForm.js') }}"></script>
@endsection