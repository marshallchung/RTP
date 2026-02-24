<?php
$title = request('title', '成果資料（三期）');
if (!isset($reportType)) $reportType = 'resultiii';
?>

@extends('admin.layouts.dashboard', [
'heading' => $topic->title,
'breadcrumbs' => [
$title,
'資料上傳',
$topic->title
]
])

@section('title', "{$topic->title}資料上傳")

@section('inner_content')
<div class="flex flex-row items-start justify-start w-full" x-data="" x-init="$nextTick(() => {
    const removeFiles=document.querySelectorAll('.js-remove-file');
    if(removeFiles && removeFiles.length>0){
        for(fileIdx in removeFiles){
            removeFiles[fileIdx].onclick = function(event){
                event.preventDefault();
                var removedFilesInput = document.getElementById('js-removed-files');
                var removedFiles = JSON.parse(removedFilesInput.value);
                var file = event.target.closest('.well');
                var id = file.dataset.id;
                removedFiles.push(id);
                removedFilesInput.value=JSON.stringify(removedFiles);
                return file.remove();
            };
        }
    }
 })">
    <div class="relative mb-6 bg-white border border-gray-200 rounded-sm">
        <div class="relative px-5 pt-3 pb-2 bg-gray-100 border-b-2 border-gray-200 text-mainAdminTextGrayDark">資料上傳
        </div>
        <div class="p-5 m-0 bg-white">
            <div class="note note-info">
                <p>資料上傳須知：</p>
                <ul>
                    <li>{{ trans('app.report.allowedMimes') }}</li>
                </ul>
            </div>
            {!! Form::open(['method' => 'POST', 'route' => 'admin.' . $reportType . '.store', 'files' => true]) !!}
            {!! Form::hidden('title', $title) !!}
            {!! Form::hidden('year', request('year')) !!}
            {!! Form::hidden('removed_files', '[]', ['id' => 'js-removed-files']) !!}
            @if (isset($report))
            {!! Form::hidden('report_id', $report->id) !!}
            @else
            {!! Form::hidden('topic_id', $topic->id) !!}
            @endif
            {!! Form::hidden('topic_id_for_validation', $topic->id) !!}

            <div class="flex flex-row flex-wrap text-center">
                @if (isset($report))
                <div class="mb-6 xl:w-full">
                    @foreach($report->files as $file)
                    <div class="flex flex-row items-center justify-start w-full p-2 bg-mainLight well"
                        data-id="{{ $file->id }}">
                        <a href="#" class="flex-1 text-left text-mainBlueDark">{{ $file->name }}</a>
                        <span class=" whitespace-nowrap">&ndash; {{ $file->created_at->format('Y-m-d H:i') }}</span>
                        <span
                            class="px-4 text-sm text-white rounded cursor-pointer py-1.5 js-remove-file bg-rose-600 hover:bg-rose-500">刪除</span>
                    </div>
                    @endforeach
                </div>
                @endif

                {!! Form::file('files[]', ['multiple' => true, 'class' => 'mt-20 mb-30','accept'=>'.pdf, .doc, .docx,
                .jpg, .jpeg, .png, .gif, .zip, .rar, .txt, .csv, .xlsx, .odf, .mp4, .mov']) !!}
                {!! Form::submit('送出', ['class' => 'btn btn-lg btn-primary']) !!}
            </div>
            {!! Form::close() !!}
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="{{ asset('scripts/genericPostForm.js') }}"></script>
@endsection