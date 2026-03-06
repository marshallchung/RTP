@extends('admin.layouts.dashboard', [
'heading' => '優選範本資料上傳',
'breadcrumbs' => [
['優選範本資料', route('admin.sample-report.index')],
'優選範本資料上傳',
$topic->title
]
])

@section('title', '優選範本資料上傳')

@section('inner_content')
<div class="flex flex-col items-start justify-start w-full p-4 text-mainAdminTextGrayDark" x-data="
{

}" x-init="$nextTick(() => {
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
    <div class="flex flex-col w-full max-w-3xl">
        <div class="relative mb-6 bg-white border border-gray-200 rounded-sm">
            <div class="relative px-5 pt-3 pb-2 bg-gray-100 border-b-2 border-gray-200 text-mainAdminTextGrayDark">資料上傳
            </div>
            <div class="p-5 m-0 bg-white">
                <div class="p-5 mb-4 border-l-2 border-l-sky-400 bg-sky-50/70">
                    <p class="text-content text-mainAdminTextGrayDark">資料上傳須知：</p>
                    <ul class="mb-4 ml-12 list-decimal">
                        <li>{{ trans('app.report.allowedMimes') }}</li>
                        <li>每次上傳僅能附加1個檔案。</li>
                        <li>檔案上傳容量限制為1G。</li>
                    </ul>
                </div>
                {!! Form::open(['method' => 'POST', 'route' => ['admin.sample-report.store', $topic], 'files' =>
                true,'class'=>'flex flex-col items-start justify-start w-full space-y-4'])
                !!}
                <div class="flex flex-row items-center justify-center space-x-8">
                    {!! Form::file('files[]', ['class' => '','accept'=>'.pdf, .doc, .docx,
                    .jpg, .jpeg, .png, .gif, .zip, .rar, .txt, .csv, .xlsx, .odf, .mp4, .mov']) !!}
                    {!! Form::submit('送出', ['class' => 'flex items-center justify-center w-20 h-10 text-white
                    bg-mainCyanDark hover:bg-teal-400']) !!}
                </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>
</div>
@endsection