@extends('admin.layouts.dashboard', [
'heading' => '執行計畫書',
'breadcrumbs' => [

[Auth::user()->name . "資料上傳", route('admin.plans.create')],
]
])

@section('title', Auth::user()->name . "資料上傳")

@section('inner_content')
<div x-data="{
    year:'{{ $year }}',
    changeYear(){
        location.href = '/admin/plans/submit?year=' + this.year;
    },
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
 })" class="flex flex-col items-start justify-start w-full p-4 space-y-6">
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
                {!! Form::open(['method' => 'POST', 'route' => 'admin.plans.store', 'files' => true]) !!}
                {!! Form::hidden('removed_files', '[]', ['id' => 'js-removed-files']) !!}
                {!! Form::hidden('year', request('year')) !!}

                <div class="flex flex-row items-center justify-center w-full pt-8 space-x-4">
                    <select name="Year" x-model='year' @change="changeYear"
                        class='h-12 px-4 border-gray-300 rounded-md shadow-sm focus:border-cyan-300 focus:ring focus:ring-cyan-200 focus:ring-opacity-50 w-44'>
                        <option value="2027">116年</option>
                        <option value="2026">115年</option>
                        <option value="2025">114年</option>
                        <option value="2024">113年</option>
                        <option value="2023">112年</option>
                    </select>
                    @if(auth()->user()->hasPermission('create-plans-for-county'))
                    {!! Form::select('county_id', $countyOptions, null, ['required','class' => 'h-12 px-4
                    border-gray-300 rounded-md
                    shadow-sm focus:border-cyan-300 focus:ring focus:ring-cyan-200 focus:ring-opacity-50 w-44']) !!}
                    @endif
                </div>

                <div class="flex flex-col items-center justify-center py-16">
                    <div class="flex flex-col items-center justify-center w-full mb-4">
                        @if (!empty($files))
                        @foreach($files as $file)
                        <div class="flex flex-row items-center justify-start w-full p-2 bg-mainLight well"
                            data-id="{{ $file->id }}">
                            <a href="#" class="flex-1 text-left text-mainBlueDark">{{ $file->name }}</a>
                            <span class="px-4 whitespace-nowrap">&ndash; {{ $file->created_at->format('Y-m-d H:i')
                                }}</span>
                            <span
                                class="px-4 text-sm text-white rounded cursor-pointer py-1.5 js-remove-file bg-rose-600 hover:bg-rose-500">刪除</span>
                        </div>
                        @endforeach
                        @endif
                    </div>

                    {!! Form::file('files[]', ['multiple' => true, 'class' => 'mb-12 mx-auto','accept'=>'.pdf, .doc,
                    .docx,
                    .jpg, .jpeg, .png, .gif, .zip, .rar, .txt, .csv, .xlsx, .odf, .mp4, .mov']) !!}
                    {!! Form::submit('送出', ['class' => 'cursor-pointer hover:bg-teal-400 text-white flex justify-center
                    items-center h-10 bg-mainCyanDark rounded w-36']) !!}
                </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
@endsection