@extends('admin.layouts.dashboard', [
'heading' => '進階防災士培訓 > 歷史紀錄列表 > 編輯',
'breadcrumbs' => [
['進階防災士培訓', route('admin.dp-advanced-students.index')],
['歷史紀錄列表', route('admin.dp-advanced-students.history')],
'編輯'
]
])

@section('title', $data->title)

@section('inner_content')
<div class="flex flex-row items-start justify-start w-full" x-data="{
    showDeleteModel:false,
    dpcounties:{{ json_encode($dpcounties) }},
    dptraining:{{ json_encode($dptraining) }},
    option_list:{},
    organizer:null,
    selected:'county',
    typeChange(){
        if(this.selected==='training'){
            this.option_list=this.dptraining;
        }else{
            this.option_list=this.dpcounties;
        }
    },
}" x-init="$nextTick(() => {
    var hasDpcountie=false;
    var hasDptraining=false;
    for (const [key, value] of Object.entries(dpcounties)) {
        if(value=='{{ $data->organizer }}'){
            organizer=value;
            selected='county';
            option_list=dpcounties;
        }
    }
    for (const [key, value] of Object.entries(dptraining)) {
        if(value=='{{ $data->organizer }}'){
            organizer=value;
            selected='training';
            option_list=dptraining;
        }
    }
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
    <div class="flex flex-row items-start justify-start w-full px-4">
        <div class="relative w-full max-w-4xl">
            <div class="relative w-full my-6 bg-white border border-gray-200 rounded-sm">
                <div class="relative px-5 pt-3 pb-2 bg-gray-100 border-b-2 border-gray-200 text-mainAdminTextGrayDark">
                    編輯
                </div>
                <div class="flex flex-col w-full space-y-6 bg-white">
                    {!! Form::model($data, ['route' => ['admin.dp-advanced-students.history-update', $data->id],
                    'method' => 'post',
                    'files' => true,'class'=>'flex flex-col w-full p-5 m-0 space-y-6 bg-white']) !!}
                    <div class="flex flex-row flex-wrap text-center">
                        {!! Form::label('county_id', '管理單位') !!}
                        {!! Form::select('county_id', $counties, request('county_id'), ['class' => 'h-12 px-4
                        border-gray-300
                        rounded-md shadow-sm focus:border-cyan-300 focus:ring focus:ring-cyan-200 focus:ring-opacity-50
                        w-full']) !!}
                    </div>

                    <div class="flex flex-row items-center justify-start w-full space-x-4">
                        {!! Form::label('organizer', '主辦單位') !!}
                        <select x-model="selected" @change="typeChange"
                            class="h-12 px-4 border-gray-300 rounded-md shadow-sm w-36 focus:border-cyan-300 focus:ring focus:ring-cyan-200 focus:ring-opacity-50">
                            <option value="training">培訓機構</option>
                            <option value="county">縣市單位</option>
                        </select>
                        <select
                            class="flex-1 h-12 px-4 border-gray-300 rounded-md shadow-sm focus:border-cyan-300 focus:ring focus:ring-cyan-200 focus:ring-opacity-50"
                            id="organizer" name="organizer" x-model="organizer">
                            <template x-for="[key, value] of Object.entries(option_list)">
                                <option :value="value"
                                    x-text="value=='消防署'?'內政部消防署':(value.length==3?value + '政府':value)"
                                    :selected="value==organizer"></option>
                            </template>
                        </select>
                    </div>


                    <div class="flex flex-row flex-wrap text-center">
                        {!! Form::label('name', '培訓名稱') !!}
                        {!! Form::text('name', null, ['class' => 'h-12 px-4 border-gray-300 rounded-md shadow-sm
                        focus:border-cyan-300 focus:ring focus:ring-cyan-200 focus:ring-opacity-50 w-full', 'required'])
                        !!}
                    </div>

                    <div class="flex flex-row flex-wrap text-center">
                        {!! Form::label('contact_person', '聯絡人') !!}
                        {!! Form::text('contact_person', null, ['class' => 'h-12 px-4 border-gray-300 rounded-md
                        shadow-sm
                        focus:border-cyan-300 focus:ring focus:ring-cyan-200 focus:ring-opacity-50 w-full', 'required'])
                        !!}
                    </div>

                    <div class="flex flex-row flex-wrap text-center">
                        {!! Form::label('phone', '連絡電話') !!}
                        {!! Form::text('phone', null, ['class' => 'h-12 px-4 border-gray-300 rounded-md shadow-sm
                        focus:border-cyan-300 focus:ring focus:ring-cyan-200 focus:ring-opacity-50 w-full', 'required'])
                        !!}
                    </div>

                    <div class="flex flex-row flex-wrap text-center">
                        {!! Form::label('email', '電子郵件') !!}
                        {!! Form::text('email', null, ['class' => 'h-12 px-4 border-gray-300 rounded-md shadow-sm
                        focus:border-cyan-300 focus:ring focus:ring-cyan-200 focus:ring-opacity-50 w-full', 'required'])
                        !!}
                    </div>

                    <div class="flex flex-row flex-wrap text-center">
                        {!! Form::label('url', '報名連結') !!}
                        {!! Form::text('url', null, ['class' => 'h-12 px-4 border-gray-300 rounded-md shadow-sm
                        focus:border-cyan-300 focus:ring focus:ring-cyan-200 focus:ring-opacity-50 w-full', 'required'])
                        !!}
                    </div>
                    <div class="flex flex-row flex-wrap space-x-4 text-center">
                        <div class="flex flex-row items-center space-x-1">
                            {!! Form::checkbox('stop_signup', 1, null, ['class' => 'border-gray-300 rounded-sm bg-white
                            text-mainCyanDark']) !!}
                            {!! Form::label('stop_signup', '報名截止') !!}
                        </div>
                        <div class="flex flex-row items-center space-x-1">
                            {!! Form::checkbox('exclusive', 1, null, ['class' => 'border-gray-300 rounded-sm bg-white
                            text-mainCyanDark']) !!}
                            {!! Form::label('exclusive', '專班辦理') !!}
                        </div>
                    </div>
                    <div class="flex flex-row flex-wrap text-center">
                        @include('admin.common.datetimepicker')
                        <table>
                            <thead>
                                <tr>
                                    <th class="p-2 font-normal text-left border-r last:border-r-0">進階防災士課程名稱</th>
                                    <th class="p-2 font-normal text-left border-r last:border-r-0">時數</th>
                                    <th class="p-2 font-normal text-left border-r last:border-r-0">授課日期</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($data['course_subjects'] as $dpSubject)
                                <tr>
                                    <td class="p-2 text-sm text-left border-r last:border-r-0">{{ $dpSubject['name']
                                        }}&nbsp;&nbsp;</td>
                                    <td class="p-2 border-r last:border-r-0">
                                        <input type="number" name="subject_hour[]" min="0" max="999"
                                            class='w-full h-10 px-4 text-sm border-gray-300 rounded-md shadow-sm focus:border-cyan-300 focus:ring focus:ring-cyan-200 focus:ring-opacity-50'
                                            value="{{ $dpSubject['hour'] }}">
                                    </td>
                                    <td class="p-2 border-r last:border-r-0">
                                        <input type="text" @click="$dispatch('showdate',{element:$el})"
                                            class='w-full h-10 px-4 text-sm border-gray-300 rounded-md shadow-sm focus:border-cyan-300 focus:ring focus:ring-cyan-200 focus:ring-opacity-50'
                                            name="subject_start_date[]" value="{{ $dpSubject['start_date'] }}">
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="flex flex-row flex-wrap text-center">
                        {!! Form::label('files[]', '檔案上傳') !!}
                        {!! Form::hidden('removed_files', '[]', ['id' => 'js-removed-files']) !!}
                        <div class="mb-6 xl:w-full">
                            @foreach($data->files as $file)
                            <div class="flex flex-row items-center justify-start w-full p-2 bg-mainLight well"
                                data-id="{{ $file->id }}">
                                <a href="/{{ $file->path }}" target="_blank"
                                    class="flex-1 text-left text-mainBlueDark">{{
                                    $file->name }}</a>
                                <span
                                    class="px-4 text-sm text-white rounded cursor-pointer py-1.5 js-remove-file bg-rose-600 hover:bg-rose-500">刪除</span>
                            </div>
                            @endforeach
                        </div>
                        {!! Form::file('files[]', ['multiple' => true,'accept'=>'.pdf, .doc, .docx,
                        .jpg, .jpeg, .png, .gif, .zip, .rar, .txt, .csv, .xlsx, .odf, .mp4, .mov']) !!}
                    </div>

                    {!! Form::submit('送出', ['class' => 'w-full cursor-pointer hover:bg-teal-400 text-white flex
                    justify-center
                    items-center h-10 bg-mainCyanDark
                    rounded']) !!}
                    <a @click="showDeleteModel=true" data-toggle="modal" data-target="#js-delete-modal"
                        class="flex items-center justify-center w-full h-10 text-white bg-orange-500 rounded cursor-pointer hover:bg-orange-400">刪除</a>
                    {!! Form::close() !!}
                </div>
                {!! Form::model($data, ['method' => 'DELETE', 'route' => ['admin.dp-courses.destroy', $data->id]]) !!}
                @include('admin.layouts.partials.genericDeleteForm')
                {!! Form::close() !!}
            </div>
        </div>
    </div>
</div>
@endsection