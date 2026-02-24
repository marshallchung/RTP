@extends('admin.layouts.dashboard', [
'heading' => '防災士培訓 > 防災士資料管理',
'breadcrumbs' => [
['防災士培訓', route('admin.dp-students.index')],
'編輯受訓者與防災士資料'
]
])

@section('title', $data->title)

@section('inner_content')

<div class="flex flex-row items-start justify-start w-full" x-data="{
    showDeleteModel:false,
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
    <div class="flex flex-row items-start justify-start w-full px-4">
        <div class="relative w-full max-w-4xl">
            <div class="relative w-full mb-6 bg-white border border-gray-200 rounded-sm">
                <div class="relative px-5 pt-3 pb-2 bg-gray-100 border-b-2 border-gray-200 text-mainAdminTextGrayDark">
                    編輯
                </div>
                <div class="flex flex-col w-full space-y-6 bg-white">
                    {!! Form::model($data, ['route' => ['admin.dp-students.update', $data->id], 'method' => 'put',
                    'files' =>
                    true,'class'=>'flex flex-col w-full p-5 m-0 space-y-6 bg-white']) !!}
                    <div class="flex flex-row flex-wrap text-center">
                        {!! Form::label('TID', '身份證字號（必填）') !!}
                        @if ($origin_role==1 || $origin_role==2 || $origin_role==6)
                        {!! Form::text('TID', null, ['class' => 'h-12 px-4 border-gray-300 rounded-md shadow-sm
                        focus:border-cyan-300 focus:ring focus:ring-cyan-200 focus:ring-opacity-50 w-full', 'required'])
                        !!}
                        @else
                        {!! Form::text('TID', null, ['class' => 'h-12 px-4 w-full border-none', 'readonly'])
                        !!}
                        @endif

                    </div>

                    <div class="flex flex-row flex-wrap text-center">
                        {!! Form::label('name', '姓名（必填）') !!}
                        @if ($origin_role==1 || $origin_role==2 || $origin_role==6)
                        {!! Form::text('name', null, ['class' => 'h-12 px-4 border-gray-300 rounded-md shadow-sm
                        focus:border-cyan-300 focus:ring focus:ring-cyan-200 focus:ring-opacity-50 w-full', 'required'])
                        !!}
                        @else
                        {!! Form::text('name', null, ['class' => 'h-12 px-4 w-full border-none',
                        'readonly'])
                        !!}
                        @endif

                    </div>

                    <div class="flex flex-row flex-wrap text-center">
                        {!! Form::label('certificate', '證書編號') !!}
                        @if ($origin_role==1 || $origin_role==2 || $origin_role==6)
                        {!! Form::text('certificate', null, ['class' => 'h-12 px-4 border-gray-300 rounded-md shadow-sm
                        focus:border-cyan-300 focus:ring focus:ring-cyan-200 focus:ring-opacity-50 w-full']) !!}
                        @else
                        {!! Form::text('certificate', null, ['class' => 'h-12 px-4 w-full border-none',
                        'readonly']) !!}
                        @endif

                    </div>

                    <div class="flex flex-row flex-wrap text-center">
                        {!! Form::label('birth', '出生年月日（必填）') !!}
                        @if ($origin_role==1 || $origin_role==2 || $origin_role==6)
                        {!! Form::text('birth', null, [
                        'class' => 'h-12 px-4 border-gray-300 rounded-md shadow-sm focus:border-cyan-300 focus:ring
                        focus:ring-cyan-200 focus:ring-opacity-50 w-full',
                        'placeholder' => '範例：19880606',
                        'required',
                        ]) !!}
                        @else
                        {!! Form::text('birth', null, ['class' => 'h-12 px-4 w-full border-none',
                        'readonly']) !!}
                        @endif

                    </div>

                    <div class="flex flex-row flex-wrap text-center">
                        {!! Form::label('gender', '性別（必填）') !!}
                        <div class="flex flex-row flex-wrap text-center">
                            <?php
                        $checked = $data->gender == '男'?true:false;
                        ?>
                            @if ($origin_role==1 || $origin_role==2 || $origin_role==6)
                            <label class="radio-inline">{!! Form::radio('gender', '男', $checked, ['class' =>
                                'border-gray-300
                                rounded-full bg-white text-mainCyanDark']) !!}男</label>
                            <label class="radio-inline">{!! Form::radio('gender', '女', !$checked, ['class' =>
                                'border-gray-300
                                rounded-full bg-white text-mainCyanDark']) !!}女</label>
                            @else
                            {!!
                            Form::text('gender', null, ['class' => 'h-12 px-4 w-full border-none',
                            'readonly'])
                            !!}
                            @endif

                        </div>
                    </div>

                    <div class="flex flex-row flex-wrap text-center">
                        {!! Form::label('field', '工作領域') !!}
                        {!! Form::select('field', $fields, $data->field, [
                        'class' => 'h-12 px-4 border-gray-300 rounded-md shadow-sm focus:border-cyan-300 focus:ring
                        focus:ring-cyan-200 focus:ring-opacity-50 w-full']) !!}
                    </div>

                    <div class="flex flex-row flex-wrap text-center">
                        {!! Form::label('education', '最高學歷（選填）') !!}
                        @if ($origin_role==1 || $origin_role==2 || $origin_role==6)
                        {!! Form::text('education', null, ['class' => 'h-12 px-4 border-gray-300 rounded-md shadow-sm
                        focus:border-cyan-300 focus:ring focus:ring-cyan-200 focus:ring-opacity-50 w-full','required'])
                        !!}
                        @else
                        {!! Form::text('education', null, ['class' => 'h-12 px-4 w-full border-none',
                        'readonly'])
                        !!}
                        @endif

                    </div>

                    <div class="flex flex-row flex-wrap text-center">
                        {!! Form::label('service', '服務單位（選填）') !!}
                        {!! Form::text('service', null, ['class' => 'h-12 px-4 border-gray-300 rounded-md shadow-sm
                        focus:border-cyan-300 focus:ring focus:ring-cyan-200 focus:ring-opacity-50 w-full'])
                        !!}
                    </div>

                    <div class="flex flex-row flex-wrap text-center">
                        {!! Form::label('title', '職稱（選填）') !!}
                        {!! Form::text('title', null, ['class' => 'h-12 px-4 border-gray-300 rounded-md shadow-sm
                        focus:border-cyan-300 focus:ring focus:ring-cyan-200 focus:ring-opacity-50 w-full'])
                        !!}
                    </div>

                    <div class="flex flex-row flex-wrap text-center">
                        {!! Form::label('Yesno', '參與防災意願&nbsp;&nbsp;&nbsp;') !!}
                        <div class="flex flex-row flex-wrap text-center">
                            <?php
                    $checked = $data->willingness?true:false;
                        ?>
                            <label class="radio-inline">{!! Form::radio('willingness', '1', $checked, ['class' =>
                                'border-gray-300
                                rounded-full bg-white text-mainCyanDark']) !!}有&nbsp;</label>
                            <label class="radio-inline">{!! Form::radio('willingness', '0', !$checked, ['class' =>
                                'border-gray-300
                                rounded-full bg-white text-mainCyanDark']) !!}無</label>
                        </div>
                    </div>

                    <div class="flex flex-row flex-wrap text-center">
                        {!! Form::label('phone', '市內電話（選填）') !!}
                        {!! Form::text('phone', null, [
                        'class' => 'h-12 px-4 border-gray-300 rounded-md shadow-sm focus:border-cyan-300 focus:ring
                        focus:ring-cyan-200 focus:ring-opacity-50 w-full',
                        'placeholder' => '範例：02-81234567']) !!}
                    </div>


                    <div class="flex flex-row flex-wrap text-center">
                        {!! Form::label('mobile', '行動電話（選填）') !!}
                        {!! Form::text('mobile', null, [
                        'class' => 'h-12 px-4 border-gray-300 rounded-md shadow-sm focus:border-cyan-300 focus:ring
                        focus:ring-cyan-200 focus:ring-opacity-50 w-full',
                        'placeholder' => '範例：0912-345678']) !!}
                    </div>

                    <div class="flex flex-row flex-wrap text-center">
                        {!! Form::label('email', 'E-mail（選填）') !!}
                        {!! Form::text('email', null, ['class' => 'h-12 px-4 border-gray-300 rounded-md shadow-sm
                        focus:border-cyan-300 focus:ring focus:ring-cyan-200 focus:ring-opacity-50 w-full']) !!}
                        <label>{!! Form::checkbox('resend_email', 1, null, ['class' => 'border-gray-300 rounded-sm
                            bg-white
                            text-mainCyanDark']) !!} 重新發送</label>
                    </div>

                    <div class="flex flex-row flex-wrap text-center">
                        {!! Form::label('address', '居住地址（必填）') !!}
                        {!! Form::text('address', null, ['class' => 'h-12 px-4 border-gray-300 rounded-md shadow-sm
                        focus:border-cyan-300 focus:ring focus:ring-cyan-200 focus:ring-opacity-50 w-full']) !!}
                    </div>

                    <div class="flex flex-row flex-wrap text-center">
                        {!! Form::label('addressId', '地址識別碼（選填）') !!}
                        {!! Form::text('addressId', null, ['class' => 'h-12 px-4 border-gray-300 rounded-md shadow-sm
                        focus:border-cyan-300 focus:ring focus:ring-cyan-200 focus:ring-opacity-50 w-full']) !!}
                    </div>

                    <div class="flex flex-row flex-wrap text-center">
                        {!! Form::label('community', '所屬村里或社區（選填）') !!}
                        {!! Form::text('community', null, ['class' => 'h-12 px-4 border-gray-300 rounded-md shadow-sm
                        focus:border-cyan-300 focus:ring focus:ring-cyan-200 focus:ring-opacity-50 w-full']) !!}
                    </div>

                    <div class="flex flex-row flex-wrap text-center">
                        {!! Form::label('county_id', '所屬縣市（必填）') !!}
                        @if ($origin_role==1 || $origin_role==2 || $origin_role==6)
                        {!! Form::select('county_id', $counties, request('county_id'), ['class' => 'h-12 px-4
                        border-gray-300
                        rounded-md shadow-sm focus:border-cyan-300 focus:ring focus:ring-cyan-200 focus:ring-opacity-50
                        w-full','required']) !!}
                        @else
                        <p class="w-full pt-4 pl-2 text-left">{{ $counties[$data->county_id] }}</p>
                        <input type="hidden" name="county_id" value="{{ $data->county_id }}">
                        @endif

                    </div>

                    <div class="flex flex-row flex-wrap text-center">
                        {!! Form::label('unit_first_course', '受訓單位（必填）') !!}
                        @if ($origin_role==1 || $origin_role==2 || $origin_role==6)
                        {!! Form::text('unit_first_course', null, ['class' => 'h-12 px-4 border-gray-300 rounded-md
                        shadow-sm
                        focus:border-cyan-300 focus:ring focus:ring-cyan-200 focus:ring-opacity-50 w-full','required'])
                        !!}
                        @else
                        {!! Form::text('unit_first_course', null, ['class' => 'h-12 px-4 w-full',
                        'readonly'])
                        !!}
                        @endif

                    </div>

                    <div class="flex flex-row flex-wrap text-center">
                        {!! Form::label('date_first_finish', '證書發放日期（必填）') !!}
                        @if ($origin_role==1 || $origin_role==2 || $origin_role==6)
                        {!! Form::text('date_first_finish', date('Y-m-d', strtotime($data->date_first_finish)), [
                        'class' => 'h-12 px-4 border-gray-300 rounded-md shadow-sm focus:border-cyan-300 focus:ring
                        focus:ring-cyan-200 focus:ring-opacity-50 w-full',
                        'placeholder' => '格式範例：2018-06-01','required']) !!}
                        @else
                        {!! Form::text('date_first_finish', date('Y-m-d', strtotime($data->date_first_finish)), [
                        'class' => 'h-12 px-4 border-gray-300 w-full border-none', 'readonly']) !!}
                        @endif

                    </div>

                    <div class="flex flex-row flex-wrap text-center" style="display:none">
                        {!! Form::label('unit_second_course', '證書有效期限') !!}
                        @if ($origin_role==1 || $origin_role==2 || $origin_role==6)
                        {!! Form::text('unit_second_course', date('Y-m-d', strtotime($data->unit_second_course)), [
                        'class' => 'h-12 px-4 border-gray-300 rounded-md shadow-sm focus:border-cyan-300 focus:ring
                        focus:ring-cyan-200 focus:ring-opacity-50 w-full',
                        'placeholder' => '格式範例：2018-06-01']) !!}
                        @else
                        {!! Form::text('unit_second_course', date('Y-m-d', strtotime($data->unit_second_course)), [
                        'class' => 'h-12 px-4 w-full border-none', 'readonly']) !!}
                        @endif

                    </div>

                    <div class="flex flex-row flex-wrap text-center">
                        {!! Form::label('files[]', '證書') !!}
                        @if ($origin_role==1 || $origin_role==2 || $origin_role==6)
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
                        @else
                        <div class="mb-6 xl:w-full">
                            @foreach($data->files as $file)
                            <div class="flex flex-row items-center justify-start w-full p-2 bg-mainLight well"
                                data-id="{{ $file->id }}">
                                <a href="/{{ $file->path }}" target="_blank"
                                    class="flex-1 text-left text-mainBlueDark">{{
                                    $file->name }}</a>
                            </div>
                            @endforeach
                        </div>
                        @endif
                    </div>

                    <div class="flex flex-row flex-wrap text-center">
                        {!! Form::label('plan', '培訓計畫名稱（必填）') !!}
                        @if ($origin_role==1 || $origin_role==2 || $origin_role==6)
                        {!! Form::text('plan', null, ['class' => 'h-12 px-4 border-gray-300 rounded-md shadow-sm
                        focus:border-cyan-300 focus:ring focus:ring-cyan-200 focus:ring-opacity-50 w-full', 'required'])
                        !!}
                        @else
                        {!! Form::text('plan', null, ['class' => 'h-12 px-4 w-full border-none',
                        'readonly'])
                        !!}
                        @endif

                    </div>

                    <div class="flex flex-row flex-wrap text-center">
                        {!! Form::label('score_academic', '學科測驗成績（必填）') !!}
                        @if ($origin_role==1 || $origin_role==2 || $origin_role==6)
                        {!! Form::input('number', 'score_academic', null, ['class' => 'h-12 px-4 border-gray-300
                        rounded-md
                        shadow-sm focus:border-cyan-300 focus:ring focus:ring-cyan-200 focus:ring-opacity-50
                        w-full','required']) !!}
                        @else
                        {!! Form::input('number', 'score_academic', null, ['class' => 'h-12 px-4 w-full',
                        'readonly']) !!}
                        @endif

                    </div>

                    <div class="flex flex-row flex-wrap text-center">
                        {!! Form::label('physical_pass', '術科測驗成績是否合格&nbsp;&nbsp;&nbsp;') !!}
                        @if ($origin_role==1 || $origin_role==2 || $origin_role==6)
                        <div class="flex flex-row flex-wrap text-center">
                            <label class="radio-inline">{!! Form::radio('physical_pass', 1, null, ['class' =>
                                'border-gray-300
                                rounded-full bg-white text-mainCyanDark'])
                                !!}合格&nbsp;</label>
                            <label class="radio-inline">{!! Form::radio('physical_pass', 0, null, ['class' =>
                                'border-gray-300
                                rounded-full bg-white text-mainCyanDark'])
                                !!}不合格</label>
                        </div>
                        @else
                        <p class="w-full pt-4 pl-2 text-left">{{ $data->physical_pass==1?'合格':'不合格' }}</p>
                        <input type="hidden" name="physical_pass" value="{{ $data->physical_pass }}">
                        @endif

                    </div>

                    <div class="flex flex-row flex-wrap text-center">
                        {!! Form::label('pass', '認證結果是否合格&nbsp;&nbsp;&nbsp;') !!}
                        @if ($origin_role==1 || $origin_role==2 || $origin_role==6)
                        <div class="flex flex-row flex-wrap text-center">
                            <label class="radio-inline">{!! Form::radio('pass', 1, null, ['class' =>
                                'border-gray-300
                                rounded-full bg-white text-mainCyanDark'])
                                !!}合格&nbsp;</label>
                            <label class="radio-inline">{!! Form::radio('pass', 0, null, ['class' =>
                                'border-gray-300
                                rounded-full bg-white text-mainCyanDark'])
                                !!}不合格</label>
                        </div>
                        @else
                        <p class="w-full pt-4 pl-2 text-left">{{ $data->pass==1?'合格':'不合格' }}</p>
                        <input type="hidden" name="pass" value="{{ $data->pass }}">
                        @endif

                    </div>

                    <div class="flex flex-row flex-wrap text-center">
                        {!! Form::label('dp_subjects[]', '參訓情形') !!}
                        <table>
                            <thead>
                                <tr>
                                    <th class="p-2 font-normal text-left border-r last:border-r-0">科目</th>
                                    <th class="p-2 font-normal text-left border-r last:border-r-0">參訓</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($dpSubjects as $dpSubject)
                                <tr>
                                    <td class="p-2 border-r last:border-r-0">{{ $dpSubject->name }}&nbsp;&nbsp;</td>
                                    <td class="p-2 border-r last:border-r-0">
                                        @if ($origin_role==1 || $origin_role==2 || $origin_role==6)
                                        {!! Form::checkbox('dp_subjects[]',
                                        $dpSubject->id,
                                        $data->dpStudentSubjects->keyBy('dp_subject_id')->has($dpSubject->id), ['class'
                                        =>
                                        'border-gray-300 rounded-sm bg-white
                                        text-mainCyanDark']) !!}
                                        @else
                                        {!! Form::checkbox('dp_subjects[]',
                                        $dpSubject->id,
                                        $data->dpStudentSubjects->keyBy('dp_subject_id')->has($dpSubject->id), [
                                        'class' => 'border-gray-300 rounded-sm bg-white text-mainCyanDark'])
                                        !!}
                                        @endif

                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    {!! Form::submit('送出', ['class' => 'w-full cursor-pointer hover:bg-teal-400 text-white flex
                    justify-center
                    items-center h-10 bg-mainCyanDark rounded']) !!}
                    @if(Auth::user()->origin_role < 4) <a @click="showDeleteModel=true" data-toggle="modal"
                        data-target="#js-delete-modal"
                        class="flex items-center justify-center w-full h-10 text-white bg-orange-500 rounded cursor-pointer hover:bg-orange-400">
                        刪除</a>
                        @endif
                        {!! Form::close() !!}
                        @if(Auth::user()->origin_role < 4) {!! Form::model($data, ['method'=> 'DELETE', 'route' =>
                            ['admin.dp-students.destroy', $data->id]])
                            !!}
                            @include('admin.layouts.partials.genericDeleteForm')
                            {!! Form::close() !!}
                            @endif
                </div>
            </div>
        </div>
    </div>
    @endsection
