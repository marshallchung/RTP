@extends('layouts.app')

@section('title', '推動韌性社區')
@section('subtitle', '社區資料登錄')

@section('content')
<div x-data="{
    remove_file:[],
    removeFile(e){
        var id = parseInt(e.target.dataset.id);
        this.remove_file.push(id);
        var remove_element = document.getElementById('js-removed-files');
        var oldValue=JSON.parse(remove_element.value);
        if(oldValue){
            oldValue.push(id);
        }else{
            oldValue=[id];
        }
        remove_element.value=JSON.stringify(oldValue);
    }
}" class="flex flex-row items-center justify-center w-full" x-init="$nextTick(() => {
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
    <div class="flex flex-col flex-1 w-full max-w-xl pb-16 space-y-12">
        <div class="flex flex-col items-center justify-start w-full space-y-6">
            {!! Form::model($data, ['route' => ['dc.unit.update'], 'method' => 'put', 'files' => true,'class'=>'flex
            flex-col items-center justify-center w-full space-y-6']) !!}
            <div class="flex flex-col items-start justify-start w-full space-y-2">
                {!! Form::label('name', '社區名稱(必填)') !!}
                {!! Form::text('name', null, ['class' => 'w-full px-4 bg-white border border-gray-300 rounded-md
                shadow-sm h-11 placeholder:text-mainTextGray focus:border-rose-300
                focus:ring focus:ring-rose-200 focus:ring-opacity-50'], 'required') !!}
            </div>

            <div class="flex flex-col items-start justify-start w-full space-y-2">
                {!! Form::label('population', '社區居住人數') !!}
                {!! Form::text('population', null, ['class' => 'w-full px-4 bg-white border border-gray-300 rounded-md
                shadow-sm h-11 placeholder:text-mainTextGray focus:border-rose-300
                focus:ring focus:ring-rose-200 focus:ring-opacity-50'], 'required') !!}
            </div>

            <div class="flex flex-col items-start justify-start w-full space-y-2">
                {!! Form::label('pattern', '社區型態(必填)') !!}
                {!! Form::select('pattern', [
                '都市型' => '都市型',
                '鄉村型' => '鄉村型',
                ], null, ['class' => 'w-full px-4 bg-white border border-gray-300 rounded-md
                shadow-sm h-11 placeholder:text-mainTextGray focus:border-rose-300
                focus:ring focus:ring-rose-200 focus:ring-opacity-50']) !!}
            </div>

            <div class="flex flex-col items-start justify-start w-full space-y-2">
                {!! Form::label('county_id', '所在縣市/鄉鎮市區/村里(必填)') !!}
                {!! Form::select('county_id', $counties, $data->county_id, ['class' => 'w-full px-4 bg-white border
                border-gray-300 rounded-md
                shadow-sm h-11 placeholder:text-mainTextGray focus:border-rose-300
                focus:ring focus:ring-rose-200 focus:ring-opacity-50']) !!}
            </div>

            <div class="flex flex-col items-start justify-start w-full space-y-2">
                {!! Form::label('location', '社區概略範圍') !!}
                {!! Form::text('location', null, ['class' => 'w-full px-4 bg-white border border-gray-300 rounded-md
                shadow-sm h-11 placeholder:text-mainTextGray focus:border-rose-300
                focus:ring focus:ring-rose-200 focus:ring-opacity-50'], 'required') !!}
            </div>

            <div class="flex flex-col items-start justify-start w-full space-y-2">
                {!! Form::label('is_experienced', '過去是否曾推動過防災社區(必填)') !!}
                <div class="flex flex-row items-start justify-start w-full space-x-4">
                    <?php
                                    $true = false;
                                    $false = false;
                                    if ($data->is_experienced == '1') $male = true;
                                    if ($data->is_experienced == '1') $female = true;
                                ?>
                    <label class="flex flex-row items-center justify-start space-x-2">{!! Form::radio('is_experienced',
                        '1', $true, ['class' => 'border-gray-300
                        rounded-full bg-white text-mainCyanDark']) !!}<span>是</span></label>
                    <label class="flex flex-row items-center justify-start space-x-2">{!! Form::radio('is_experienced',
                        '0', $true, ['class' => 'border-gray-300
                        rounded-full bg-white text-mainCyanDark']) !!}<span>否</span></label>
                </div>
            </div>

            <div class="flex flex-col items-start justify-start w-full space-y-2">
                {!! Form::label('environment', '社區環境概述') !!}
                {!! Form::text('environment', null, ['class' => 'w-full px-4 bg-white border border-gray-300 rounded-md
                shadow-sm h-11 placeholder:text-mainTextGray focus:border-rose-300
                focus:ring focus:ring-rose-200 focus:ring-opacity-50'], 'required') !!}
            </div>

            <div class="flex flex-col items-start justify-start w-full space-y-2">
                {!! Form::label('risk', '社區災害潛勢與風險概述') !!}
                {!! Form::text('risk', null, ['class' => 'w-full px-4 bg-white border border-gray-300 rounded-md
                shadow-sm h-11 placeholder:text-mainTextGray focus:border-sky-300
                focus:ring focus:ring-sky-200 focus:ring-opacity-50']) !!}
            </div>

            <div class="flex flex-col items-start justify-start w-full space-y-2">
                {!! Form::label('files[]', '社區概略範圍示意圖') !!}
                @if(class_basename($data->files) === 'Collection')
                {!! Form::hidden('removed_files', '[]', ['id' => 'js-removed-files']) !!}
                <div class="mb-6 xl:w-full">
                    @if (isset($data->files[0]))
                    @foreach ($data->files as $file)
                    @if ($file->memo == 'dc-location')
                    <div x-show="!remove_file.includes({{ $file->id }})"
                        class="flex flex-row items-center justify-between w-full py-1.5">
                        <a class="flex-1 text-left align-middle text-mainBlueDark" href="/{{ $file->path }}">{{
                            $file->name }}</a> &nbsp;
                        <button type="button" data-id="{{ $file->id }}" @click="removeFile"
                            class="px-4 py-2 ml-2 text-white rounded cursor-pointer whitespace-nowrap js-remove-file bg-rose-600 hover:bg-rose-500">刪除</button>
                    </div>
                    @endif
                    @endforeach
                    @endif
                </div>
                @endif
                {!! Form::file('files[]', ['multiple' => true,'accept'=>'.pdf, .doc, .docx,
                .jpg, .jpeg, .png, .gif, .zip, .rar, .txt, .csv, .xlsx, .odf, .mp4, .mov']) !!}
            </div>
            <div class="flex flex-col items-start justify-start w-full space-y-2">
                {!! Form::label('manager', '韌性社區負責人姓名(必填)') !!}
                {!! Form::text('manager', null, ['class' => 'w-full px-4 bg-white border border-gray-300 rounded-md
                shadow-sm h-11 placeholder:text-mainTextGray focus:border-rose-300
                focus:ring focus:ring-rose-200 focus:ring-opacity-50'], 'required') !!}
            </div>

            <div class="flex flex-col items-start justify-start w-full space-y-2">
                {!! Form::label('phone', '韌性社區負責人電話(必填)') !!}
                {!! Form::text('phone', null, ['class' => 'w-full px-4 bg-white border border-gray-300 rounded-md
                shadow-sm h-11 placeholder:text-mainTextGray focus:border-rose-300
                focus:ring focus:ring-rose-200 focus:ring-opacity-50'], 'required') !!}
            </div>

            <div class="flex flex-col items-start justify-start w-full space-y-2">
                {!! Form::label('email', '韌性社區負責人Email(必填)') !!}
                {!! Form::text('email', null, ['class' => 'w-full px-4 bg-white border border-gray-300 rounded-md
                shadow-sm h-11 placeholder:text-mainTextGray focus:border-rose-300
                focus:ring focus:ring-rose-200 focus:ring-opacity-50'], 'required') !!}
            </div>

            <div class="flex flex-col items-start justify-start w-full space-y-2">
                {!! Form::label('manager_address', '韌性社區負責人地址(必填)') !!}
                {!! Form::text('manager_address', null, ['class' => 'w-full px-4 bg-white border border-gray-300
                rounded-md
                shadow-sm h-11 placeholder:text-mainTextGray focus:border-rose-300
                focus:ring focus:ring-rose-200 focus:ring-opacity-50'], 'required') !!}
            </div>

            <div class="flex flex-col items-start justify-start w-full space-y-2">
                {!! Form::label('manager_position', '擔任社區職務(必填)') !!}
                {!! Form::text('manager_position', null, ['class' => 'w-full px-4 bg-white border border-gray-300
                rounded-md
                shadow-sm h-11 placeholder:text-mainTextGray focus:border-rose-300
                focus:ring focus:ring-rose-200 focus:ring-opacity-50'], 'required') !!}
            </div>
            {!! Form::submit('送出', ['class' => 'w-full rounded-md cursor-pointer h-12 bg-mainBlueDark hover:bg-mainBlue
            text-white']) !!}
            {!! Form::close() !!}
            <a @click="showDeleteModel=true" data-toggle="modal" data-target="#js-delete-modal"
                class="flex items-center justify-center w-full h-10 text-white bg-orange-500 rounded cursor-pointer hover:bg-orange-400">刪除</a>
        </div>
    </div>
</div>
@endsection

@section('js')
@endsection