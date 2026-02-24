@extends('admin.layouts.dashboard', [
'heading' => '縣市季進度管制表',
'breadcrumbs' => [
['計畫執行成果', route('admin.reports.index')],
'展示'
]
])

@section('title', '縣市季進度管制表上傳')

@section('inner_content')
<div class="flex flex-col items-start justify-start w-full p-4 space-y-6">
    <div class="flex flex-row flex-wrap w-full">
        <div class="relative mb-6 bg-white border border-gray-200 rounded-sm">
            <div class="p-5 m-0 bg-white">
                <div class="note note-info">
                    <p>資料上傳須知：</p>
                    <ol>
                        <li>{{ trans('app.report.allowedMimes') }}</li>
                        <li>請點選送出按鈕，將上傳檔案提交</li>
                    </ol>
                </div>
                <div class="table-light">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th class="text-center align-middle col-lg-1" style="width: 47px;">繳交</th>
                                <th class="align-middle">工作項目</th>
                                <th class="align-middle col-lg-1">檔案上傳</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($report as $category)
                            <tr>
                                <td colspan="3"><strong>{{ $category->name }}</strong></td>
                            </tr>
                            @foreach ($category->items as $item)
                            <tr>
                                <td class="p-2 text-center border-r last:border-r-0">
                                    @if (isset($item->reports) && count($item->reports->files))
                                    <i class="w-6 h-6 text-lime-600 i-fa6-solid-circle-check"></i>
                                    @endif
                                </td>
                                <td class="p-2 border-r last:border-r-0">
                                    <p class="p-0 m-0 text-base"><strong>{{ $item->title }}</strong>
                                    </p>
                                    @if(isset($fileList[$item->id]))
                                    @foreach($fileList[$item->id] as $year => $files)
                                    <h4>{{ $year }}年度</h4>
                                    @foreach($files as $file)
                                    <p class="p-0 m-0">
                                        <a href="{{ url($file->path) }}">{{ $file->name }}</a>
                                        <span class="text-mainAdminTextGray">&ndash; {{ $file->created_at->format('n/j/Y
                                            G:i')
                                            }}</span>
                                    </p>
                                    @endforeach
                                    @endforeach
                                    @endif
                                </td>
                                <td class="align-middle">
                                    {!! Html::linkroute('admin.seasonalReports.create', '上傳檔案', $item->id, ['class' =>
                                    'btn btn-info']) !!}
                                </td>
                            </tr>
                            @endforeach
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@stop