@extends('admin.layouts.dashboard', [
'heading' => $topic->title,
'breadcrumbs' => [
'資料上傳',
$topic->title
]
])

@section('title', "{$topic->title}資料上傳")

@section('inner_content')
<div class="flex flex-col items-start justify-start w-full p-4 text-mainAdminTextGrayDark" x-data="
{
    year:'{{ $year }}',
    season:'',
    files:{{ $report ? json_encode($report->files):'[]' }},
    changeSelect(e){

    }
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
                        <li class="text-content text-mainAdminTextGrayDark">{{ trans('app.report.allowedMimes') }}</li>
                    </ul>
                </div>
                {!! Form::open(['method' => 'POST', 'route' => 'admin.seasonalReports.store', 'files' =>
                true,'class'=>'flex flex-col items-start justify-start w-full space-y-4']) !!}
                {!! Form::hidden('removed_files', '[]', ['id' => 'js-removed-files']) !!}
                @if (isset($report))
                {!! Form::hidden('report_id', $report->id) !!}
                @else
                {!! Form::hidden('topic_id', $topic->id) !!}
                @endif
                {!! Form::hidden('topic_id_for_validation', $topic->id) !!}

                <div class="flex flex-row items-center justify-center space-x-8">
                    <div class="flex flex-row items-center justify-center space-x-2">
                        {!! Form::label('year', '年度') !!}
                        <select
                            class="h-12 px-4 border-gray-300 rounded-md shadow-sm focus:border-cyan-300 focus:ring focus:ring-cyan-200 focus:ring-opacity-50 w-44"
                            @change="changeSelect" x-model="year" id="year" name="year">
                            @foreach ($years as $year)
                            <option value="{{ $year }}">{{ intval($year)-1911 }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="flex flex-row items-center justify-center space-x-2">
                        {!! Form::label('season', '期別') !!}
                        {!! Form::select('season', [
                        '' => '--',
                        1 => '期初',
                        2 => '期中',
                        3 => '期末',], '', ['class' => 'h-12 px-4 border-gray-300
                        rounded-md shadow-sm focus:border-cyan-300 focus:ring focus:ring-cyan-200 focus:ring-opacity-50
                        w-44','@change'=>'changeSelect','x-model'=>'season','required']) !!}
                    </div>
                </div>
                <div class="flex flex-row items-center justify-center space-x-8">
                    {!! Form::file('files[]', ['multiple' => true, 'class' => '','accept'=>'.pdf, .doc,
                    .docx,
                    .jpg, .jpeg, .png, .gif, .zip, .rar, .txt, .csv, .xlsx, .odf, .mp4, .mov']) !!}
                    {!! Form::submit('送出', ['class' => 'flex items-center justify-center w-20 h-10 text-white
                    bg-mainCyanDark hover:bg-teal-400']) !!}
                </div>
                <div class="w-full mb-6">
                    <template x-for="(file,index) in files">
                        <template x-if="file.memo.substring(0,4)==year && file.memo.substring(5)==season">
                            <div class="flex flex-row items-center justify-start w-full p-2 bg-mainLight well"
                                x-bind:data-id="file.id">
                                <div class="flex flex-row items-center justify-start flex-1 space-x-2">
                                    <a :href="'/' + file.path" target="_blank"
                                        class="flex flex-row items-center justify-start space-x-1 text-mainBlueDark">
                                        <span x-text="file.memo.substring(0,4) + '年'"></span>
                                        <span
                                            x-text="file.memo.substring(5)=='1'?'期初':(file.memo.substring(5)=='2'?'期中':'期末')"></span>
                                        <span x-text="file.name"></span>
                                    </a>
                                    <span class="text-sm whitespace-nowrap text-mainAdminTextGrayDark"
                                        x-text="' - ' + (new Date(file.created_at)).toLocaleString('chinese',{hour12:false})"></span>
                                </div>
                                <span
                                    class="px-4 text-sm text-white rounded cursor-pointer py-1.5 js-remove-file bg-rose-600 hover:bg-rose-500">刪除</span>
                            </div>
                        </template>
                    </template>
                </div>
            </div>
            {!! Form::close() !!}
        </div>
    </div>
</div>
</div>
@endsection

@section('scripts')
@endsection