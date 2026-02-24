@extends('admin.layouts.dashboard', [
'heading' => '社區防災計畫書上傳',
'breadcrumbs' => ['社區防災計畫書及相關佐證資料']
])

@section('title', '社區防災計畫書及相關佐證資料')

@section('inner_content')
<div id="js-token" class="hidden">{{ csrf_token() }}</div>
<div class="flex flex-col items-start justify-start w-full p-4 text-mainAdminTextGrayDark" x-data="{
    loading:false,
    selectedTab:'1',
    county_id:'{{ request('county_id') }}',
    dc_unit_id:'{{ request('dc_unit_id') }}',
    stage:'{{ request('stage') }}',
    dc_unit_name:'{{ request('dc_unit_name') }}',
    filterChange(){
        var url='/admin/dc-stages?county_id=' + encodeURIComponent(this.county_id)+ '&stage=' + encodeURIComponent(this.stage)+ '&dc_unit_id=' + encodeURIComponent(this.dc_unit_id)+ '&dc_unit_name=' + encodeURIComponent(this.dc_unit_name);
        window.location = url;
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
    <div class="flex flex-col items-start justify-start w-full max-w-5xl pace-y-4">
        @if ($dc_unit === null)
        {!! Form::open(['method' => 'POST', 'route' => 'admin.dc-stages.store', 'files' => true,'class'=>'flex flex-col
        items-start justify-start w-full pace-y-4']) !!}
        @else
        {!! Form::model($dc_unit, ['route' => ['admin.dc-stages.store'], 'files' => true, 'method' =>
        'POST','class'=>'flex flex-col items-start justify-start w-full pace-y-4']) !!}
        @endif
        <div class="relative mb-6 bg-white border border-gray-200 rounded-sm">
            <div class="relative px-5 pt-3 pb-2 bg-gray-100 border-b-2 border-gray-200">
                說明</div>
            <div id="divStudentData" class="p-5 m-0 bg-white text-content">
                依據第1期韌性社區推動計畫、玖、二規定，為使本部能分階段掌握韌性社區之執行現況，各韌性社區於各執行階段末擬定韌性社區防災計畫書提報本部備查。爰請於各階段結束後，上傳社區防災計畫書及相關佐證資料，除社區防災計畫書是一定要上傳的項目，佐證資料請由各直轄市、縣(市)政府及社區自行決定是否上傳。
            </div>
        </div>
        <div class="relative w-full mb-6 bg-white border border-gray-200 rounded-sm">
            <div class="relative px-5 pt-3 pb-2 bg-gray-100 border-b-2 border-gray-200 text-mainAdminTextGrayDark">
                韌性社區基本資料
            </div>
            <div id="divStudentData" class="flex flex-col w-full p-5 space-y-6 bg-white">
                <div class="flex items-center w-full space-x-2 form-row">
                    <div class="w-1/3 text-center">
                        {!! Form::label('county_id', '縣市') !!}
                    </div>
                    <div class="flex-1">
                        @if($lockCounty)
                        {!! Form::select('county_id', $counties, $lockCounty->id,
                        ['x-model'=>'county_id','@change'=>'filterChange','class' => 'h-12 px-4 border-gray-300
                        rounded-md shadow-sm focus:border-cyan-300 focus:ring focus:ring-cyan-200 focus:ring-opacity-50
                        w-full', 'disabled'])
                        !!}
                        @else
                        {!! Form::select('county_id', $counties, request('county_id'),
                        ['x-model'=>'county_id','@change'=>'filterChange','class' => 'h-12 px-4
                        border-gray-300 rounded-md shadow-sm focus:border-cyan-300 focus:ring focus:ring-cyan-200
                        focus:ring-opacity-50 w-full']) !!}
                        @endif
                    </div>
                </div>
                <div class="flex items-center w-full space-x-2 form-row">
                    <div class="w-1/3 text-center">
                        {!! Form::label('dc_unit_name', '搜尋社區') !!}
                    </div>
                    <div class="relative flex-1">
                        {!! Form::text('dc_unit_name', '搜尋社區',
                        ['x-model'=>'dc_unit_name','@change'=>'filterChange','class' => 'h-12 px-4
                        border-gray-300 rounded-md shadow-sm focus:border-cyan-300 focus:ring focus:ring-cyan-200 pr-12
                        focus:ring-opacity-50 w-full']) !!}
                        <button type="button" @click="filterChange"
                            class="absolute top-0 right-0 flex items-center justify-center w-12 h-12 border rounded">
                            <i class="w-6 h-6 text-gray-400 i-gg-search"></i>
                        </button>
                    </div>
                </div>
                <div class="flex items-center w-full space-x-2 form-row">
                    <div class="w-1/3 text-center">
                        {!! Form::label('dc_unit_id', '社區名稱') !!}
                    </div>
                    <div class="flex-1">
                        {!! Form::select('dc_unit_id', $dc_units, request('dc_unit_id'),
                        ['x-model'=>'dc_unit_id','@change'=>'filterChange','class' => 'h-12 px-4
                        border-gray-300 rounded-md shadow-sm focus:border-cyan-300 focus:ring focus:ring-cyan-200
                        focus:ring-opacity-50 w-full']) !!}
                    </div>
                </div>

                <div class="flex items-center w-full space-x-2 form-row">
                    <div class="w-1/3 text-center">
                        {!! Form::label('created_at', '提報時間') !!}
                    </div>
                    <div class="flex-1">
                        {!! Form::text('created_at', '資料提交之系統時間', ['class' => 'h-12 px-4 border-gray-300 rounded-md
                        shadow-sm focus:border-cyan-300 focus:ring focus:ring-cyan-200 focus:ring-opacity-50 w-full',
                        'disabled']) !!}
                    </div>
                </div>

                <div class="flex items-center w-full space-x-2 form-row">
                    <div class="w-1/3 text-center">
                        {!! Form::label('stage', '提報階段') !!}
                    </div>
                    <div class="flex-1">
                        {!! Form::select('stage', [
                        '' => '--',
                        1 => '第1階段',
                        2 => '第2階段',
                        3 => '第3階段',
                        ], request('stage'), ['x-model'=>'stage','@change'=>'filterChange','class' => 'h-12 px-4
                        border-gray-300 rounded-md
                        shadow-sm
                        focus:border-cyan-300 focus:ring focus:ring-cyan-200 focus:ring-opacity-50 w-full']) !!}
                    </div>
                </div>
            </div>
        </div>

        <div class="relative w-full mb-6 bg-white border border-gray-200 rounded-sm">
            <div class="relative px-5 pt-3 pb-2 bg-gray-100 border-b-2 border-gray-200 text-mainAdminTextGrayDark">工作項目
            </div>
            <div class="p-5 m-0 bg-white">
                <div class="flex flex-row flex-wrap">
                    <div class="col-md-12">
                        <ul class="list-none ">
                            <li class="p-1">資料上傳須知：
                                <ul class="ml-4 list-disc list-inside">
                                    <li class="p-1">請確定檔案格式為pdf, doc, docx, jpg, jpeg, png, gif, zip, rar, txt, csv,
                                        xlsx, odf, mp4, mov</li>
                                    <li class="p-1">請點選送出按鈕，將上傳檔案提交。</li>
                                </ul>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <?php
                $disabled = null;
                //if (request('stage') === null) $disabled = true;
                if (request('dc_unit_id') === null) $disabled = true;
            ?>
            <div class="p-5 m-0 bg-white">
                <div class="flex flex-row flex-wrap mb-6">
                    <table class="w-full bg-white border shadow-lg text-mainAdminTextGrayDark">
                        <thead>
                            <tr class="border-b bg-mainLight">
                                <th class="p-2 font-normal text-left border-r w-14 last:border-r-0">繳交</th>
                                <th class="p-2 font-normal text-left border-r last:border-r-0">工作項目</th>
                                <th class="p-2 font-normal text-left border-r last:border-r-0">檔案上傳</th>
                            </tr>
                        </thead>
                        <tbody class="text-content text-mainAdminTextGrayDark">
                            <tr class="bg-white border-b last:border-b-0">
                                <td colspan="3" class="p-2 border-r last:border-r-0">--</td>
                            </tr>
                            <tr class="bg-white border-b last:border-b-0">
                                <td class="p-2 text-center border-r last:border-r-0">x</td>
                                <td class="p-2 border-r last:border-r-0">
                                    社區防災計畫書
                                </td>
                                <td class="p-2 border-r last:border-r-0">
                                    @if($files)
                                    <div class="flex flex-row flex-wrap text-center">
                                        {!! Form::label('files[]', '附件') !!}
                                        @if(class_basename($files) === 'Collection')
                                        {!! Form::hidden('removed_files', '[]', ['id' => 'js-removed-files']) !!}
                                        <div class="mb-6 xl:w-full">
                                            @foreach($files as $file)
                                            @if (substr($file->name, 2, 1) == '1')
                                            <div class="flex flex-row flex-wrap">
                                                <div class="mb-5 well well-sm col-lg-7" data-id="{{ $file->id }}">
                                                    <a href="/{{ $file->path }}" target="_blank"
                                                        class="flex-1 text-left text-mainBlueDark">{{ $file->name
                                                        }}</a>
                                                    <span
                                                        class="px-4 text-sm text-white rounded cursor-pointer py-1.5 js-remove-file bg-rose-600 hover:bg-rose-500">刪除</span>
                                                </div>
                                            </div>
                                            @endif
                                            @endforeach
                                        </div>
                                        @endif
                                        {!! Form::file('files[]', ['multiple' => true, 'term' => 1, 'disabled' =>
                                        $disabled,'accept'=>'.pdf, .doc, .docx, .jpg, .jpeg, .png, .gif, .zip, .rar,
                                        .txt, .csv, .xlsx, .odf, .mp4, .mov']) !!}
                                    </div>
                                    @else
                                    {!! Form::file('files[]', ['multiple' => true, 'term' => 1, 'disabled' =>
                                    $disabled,'accept'=>'.pdf, .doc, .docx, .jpg, .jpeg, .png, .gif, .zip, .rar,
                                    .txt,
                                    .csv, .xlsx, .odf, .mp4, .mov']) !!}
                                    @endif
                                </td>
                            </tr>
                            <tr class="bg-white border-b last:border-b-0">
                                <td colspan="3" class="p-2 border-r last:border-r-0">--</td>
                            </tr>
                            <tr class="bg-white border-b last:border-b-0">
                                <td class="p-2 text-center border-r last:border-r-0">x</td>
                                <td class="p-2 border-r last:border-r-0">
                                    相關佐證資料
                                </td>
                                <td class="p-2 border-r last:border-r-0">
                                    @if($files)
                                    <div class="flex flex-row flex-wrap text-center">
                                        {!! Form::label('files[]', '附件') !!}
                                        @if(class_basename($files) === 'Collection')
                                        <div class="mb-6 xl:w-full">
                                            @foreach($files as $file)
                                            @if (substr($file->name, 2, 1) == '2')
                                            <div class="flex flex-row flex-wrap">
                                                <div class="mb-5 well well-sm col-lg-7" data-id="{{ $file->id }}">
                                                    <a href="/{{ $file->path }}" target="_blank"
                                                        class="flex-1 text-left text-mainBlueDark">{{ $file->name
                                                        }}</a>
                                                    <span
                                                        class="px-4 text-sm text-white rounded cursor-pointer py-1.5 js-remove-file bg-rose-600 hover:bg-rose-500">刪除</span>
                                                </div>
                                            </div>
                                            @endif
                                            @endforeach
                                        </div>
                                        @endif
                                        {!! Form::file('files[]', ['multiple' => true, 'term' => 2, 'disabled' =>
                                        $disabled,'accept'=>'.pdf, .doc, .docx, .jpg, .jpeg, .png, .gif, .zip, .rar,
                                        .txt, .csv, .xlsx, .odf, .mp4, .mov']) !!}
                                    </div>
                                    @else
                                    {!! Form::file('files[]', ['multiple' => true, 'term' => 2, 'disabled' =>
                                    $disabled,'accept'=>'.pdf, .doc, .docx, .jpg, .jpeg, .png, .gif, .zip, .rar,
                                    .txt,
                                    .csv, .xlsx, .odf, .mp4, .mov']) !!}
                                    @endif
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                {!! Form::submit('送出', ['class' => 'w-full cursor-pointer hover:bg-teal-400 text-white flex
                justify-center
                items-center h-10 bg-mainCyanDark
                rounded']) !!}
            </div>

        </div>
        {!! Form::close() !!}
    </div>
</div>
@include('admin.layouts.partials.loadingMask')
@endsection

@section('scripts')
@endsection