@extends('admin.layouts.dashboard', [
'heading' => '進階防災士培訓 > 進階防災士資料管理',
'breadcrumbs' => [
['進階防災士培訓', route('admin.dp-advanced-students.index')],
'編輯受訓者與防災士資料'
]
])

@section('title', $data->title)

@section('inner_content')

<div class="md:w-67/100 xl:w-75/100" x-data="" x-init="$nextTick(() => {
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
        <div class="relative px-5 pt-3 pb-2 bg-gray-100 border-b-2 border-gray-200 text-mainAdminTextGrayDark">新增</div>
        <div class="p-5 m-0 bg-white">
            <div class="flex flex-row flex-wrap text-center">
                {!! Form::label('TID', '身份證字號（必填）') !!}
                <p>{{ $data->TID }}</p>
            </div>

            <div class="flex flex-row flex-wrap text-center">
                {!! Form::label('name', '姓名（必填）') !!}
                <p>{{ $data->name }}</p>
            </div>

            <div class="flex flex-row flex-wrap text-center">
                {!! Form::label('birth', '出生年（西元）（必填）') !!}
                <p>{{ $data->birth }}</p>
            </div>

            <div class="flex flex-row flex-wrap text-center">
                {!! Form::label('gender', '性別（必填）') !!}
                <p>{{ $data->gender }}</p>
            </div>

            <div class="flex flex-row flex-wrap text-center">
                {!! Form::label('field', '工作領域（必填）') !!}
                <p>{{ $data->field }}</p>
            </div>

            <div class="flex flex-row flex-wrap text-center">
                {!! Form::label('phone', '市內電話（必填）') !!}
                <p>{{ $data->phone }}</p>
            </div>

            <div class="flex flex-row flex-wrap text-center">
                {!! Form::label('mobile', '行動電話（必填）') !!}
                <p>{{ $data->mobile }}</p>
            </div>

            <div class="flex flex-row flex-wrap text-center">
                {!! Form::label('email', 'E-mail（必填）') !!}
                <p>{{ $data->email }}</p>
            </div>

            <div class="flex flex-row flex-wrap text-center">
                {!! Form::label('address', '居住地址（必填）') !!}
                <p>{{ $data->address }}</p>
            </div>

            <div class="flex flex-row flex-wrap text-center">
                {!! Form::label('community', '所屬村里或社區（必填）') !!}
                <p>{{ $data->community }}</p>
            </div>

            <div class="flex flex-row flex-wrap text-center">
                {!! Form::label('county_id', '所屬縣市（必填）') !!}
                <p>{{ $counties[$data->county_id] }}</p>
            </div>

            <div class="flex flex-row flex-wrap text-center">
                {!! Form::label('unit_first_course', '初訓單位') !!}
                <p>{{ $data->unit_first_course }}</p>
            </div>

            <div class="flex flex-row flex-wrap text-center">
                {!! Form::label('date_first_finish', '初訓結訓時間') !!}
                <p>{{ $data->date_first_finish }}</p>
            </div>

            <div class="flex flex-row flex-wrap text-center">
                {!! Form::label('unit_second_course', '複訓單位') !!}
                <p>{{ $data->unit_second_course }}</p>
            </div>

            <div class="flex flex-row flex-wrap text-center">
                {!! Form::label('date_second_finish', '複訓結訓時間') !!}
                <p>{{ $data->date_second_finish }}</p>
            </div>

            <div class="flex flex-row flex-wrap text-center">
                {!! Form::label('files[]', '證書') !!}
                {!! Form::hidden('removed_files', '[]', ['id' => 'js-removed-files']) !!}
                <div class="mb-6 xl:w-full">
                    @foreach($data->files as $file)
                    <div class="flex flex-row items-center justify-start w-full p-2 bg-mainLight"
                        data-id="{{ $file->id }}">
                        <a href="/{{ $file->path }}" target="_blank" class="flex-1 text-left text-mainBlueDark">{{
                            $file->name }}</a>
                        <span
                            class="px-4 text-sm text-white rounded cursor-pointer py-1.5 js-remove-file bg-rose-600 hover:bg-rose-500">刪除</span>
                    </div>
                </div>
                @endforeach
            </div>
            {!! Form::file('files[]', ['multiple' => true,'accept'=>'.pdf, .doc, .docx,
            .jpg, .jpeg, .png, .gif, .zip, .rar, .txt, .csv, .xlsx, .odf, .mp4, .mov']) !!}

            <div class="flex flex-row flex-wrap text-center">
                {!! Form::label('score_academic', '學科測驗成績') !!}
                <p>{{ $data->score_academic }}</p>
            </div>

            <div class="flex flex-row flex-wrap text-center">
                {!! Form::label('physical_pass', '術科測驗成績是是否合格') !!}
                <p>{{ $data->physical_pass }}</p>
            </div>

            {!! Form::submit('送出', ['class' => 'w-full text-white flex justify-center items-center h-10
            bg-mainCyanDark rounded']) !!}
            {!! Form::close() !!}
            <a @click="showDeleteModel=true" data-toggle="modal" data-target="#js-delete-modal"
                class="flex items-center justify-center w-full h-10 text-white bg-orange-500 rounded cursor-pointer hover:bg-orange-400">刪除</a>
        </div>
        {!! Form::model($data, ['method' => 'DELETE', 'route' => ['admin.dp-advanced-students.destroy', $data->id]]) !!}
        @include('admin.layouts.partials.genericDeleteForm')
        {!! Form::close() !!}
    </div>
</div>

<script src="{{ asset('scripts/genericPostForm.js') }}"></script>
@endsection