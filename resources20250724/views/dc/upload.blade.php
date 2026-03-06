@extends('layouts.app')
@section('title', '推動韌性社區')
@section('subtitle', '上傳與管理檔案')
@section('content')

<div x-data="{
    stage:'',
    removed_files:[],
    dc_unit_id:{{ $data->id }},
    removeFile(e){
        this.removed_files.push(parseInt(e.target.dataset.id));
    },
    getQUeryStage(){
        const queryString = window.location.search;
        const urlParams = new URLSearchParams(queryString);
        this.stage = urlParams.get('stage');
    },
    changeStage(){
        window.location = '{{ route('dc.upload') }}?&stage=' + this.stage;
    },
    onSubmit(e){
        var formData = new FormData(e.target);
        formData.append('removed_files', JSON.stringify(this.removed_files));
        fetch(e.target.action,{
            method:'POST',
            body:formData,
            headers: {
                'Accept': 'application.json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
            },
        })
        .then((res) => {
            return res.json();
        })
        .then((data) => {
            if (data.error) {
                alert(data.error);
                return;
            }else if (data.ok) {
                alert('修改成功!');
                location.reload();
            }else if (data.attempting_page) {
                window.location = data.attempting_page;
            } else {
                window.location = '{{ url('/') }}';
            }
        }).catch(function(error) {
            if (error.status == 429) {
                alert('嘗試登入次數過多，請稍後再試。');
            }else if (error.status == 419) {
                alert('頁面逾期，請重新輸入');
                location.reload();
            }else{
                alert('伺服器錯誤: ' + error.message);
            }
        });
    }
}" class="flex flex-row items-center justify-center w-full" x-init="$nextTick(() => {
    getQUeryStage();
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
    <div class="flex flex-col flex-1 w-full max-w-5xl pb-16 space-y-12">
        {!! Form::model($data, [
        'id' => 'form',
        'route' => ['dc.upload.update'],
        'method' => 'post', 'files' => true]) !!}

        <div class="relative mb-6 bg-white border border-gray-200 rounded-sm">
            <div
                class="relative px-5 pt-3 pb-2 text-2xl bg-gray-100 border-b-2 border-gray-200 rounded-t text-mainAdminTextGrayDark">
                韌性社區基本資料
            </div>
            <div id="divStudentData" class="flex flex-col w-full p-5 m-0 space-y-4">
                <div class="flex flex-row items-center w-full">
                    {!! Form::hidden('dc_unit_id', $data->id) !!}
                    <div class="w-1/4 text-center">
                        {!! Form::label('dc_unit_id', '社區名稱') !!}
                    </div>
                    <div class="flex-1">
                        <h4>{{ $data->name }}</h4>
                    </div>
                </div>
                <div class="flex flex-row items-center w-full">
                    <div class="w-1/4 text-center">
                        {!! Form::label('created_at', '提報時間') !!}
                    </div>
                    <div class="flex-1">
                        {!! Form::text('created_at', '資料提交之系統時間', ['class' => 'w-full px-4 bg-gray-100 border
                        border-gray-300 rounded-md shadow-sm h-11 placeholder:text-mainTextGray
                        focus:border-gray-300 focus:ring focus:ring-transparent focus:ring-opacity-0', 'disabled']) !!}
                    </div>
                </div>
                <div class="flex flex-row items-center w-full">
                    <div class="w-1/4 text-center">
                        {!! Form::label('stage', '提報階段') !!}
                    </div>
                    <div class="flex-1">
                        <select x-model="stage" @change="changeStage"
                            class="w-full px-4 bg-white border border-gray-300 rounded-md shadow-sm h-11 placeholder:text-mainTextGray focus:border-rose-300 focus:ring focus:ring-rose-200 focus:ring-opacity-50"
                            id="stage" name="stage">
                            <option value="" selected="selected">--</option>
                            <option value="1">第1階段</option>
                            <option value="2">第2階段</option>
                            <option value="3">第3階段</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
        <div class="relative mb-6 bg-white border border-gray-200 rounded-sm">
            <div
                class="relative px-5 pt-3 pb-2 text-2xl bg-gray-100 border-b-2 border-gray-200 rounded-t text-mainAdminTextGrayDark">
                工作項目</div>
            <div class="flex flex-row flex-wrap text-center">
                <div class="flex flex-row flex-wrap">
                    <div class="col-md-2"></div>
                    <div class="col-md-10">
                        <ul class="list-none">
                            <li>資料上傳須知：
                                <ul>
                                    <li>請確定檔案格式為.doc, .docx, .pdf, .jpg, .jpeg, .png, .gif, .odf, .mp4, .mov</li>
                                    <li>請點選送出按鈕，將上傳檔案提交。</li>
                                </ul>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="flex flex-col items-start justify-start flex-1 w-full overflow-scroll">
                <table class="w-full bg-white border text-mainAdminTextGrayDark min-w-fit">
                    <thead>
                        <tr class="text-white border-b rounded-t bg-mainGrayDark">
                            <th class="w-20 p-2 font-bold border-r last:border-r-0">繳交</th>
                            <th class="w-64 p-2 font-bold border-r last:border-r-0">工作項目</th>
                            <th class="p-2 font-bold border-r last:border-r-0">檔案上傳</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="bg-white border-b odd:bg-gray-100 last:border-b-0 last:rounded-b">
                            <td class="px-4 py-2 text-center border-r last:border-r-0">
                                <?php $done = false; ?>
                                @if ($files)
                                @foreach($files as $file)
                                @if (substr($file->name, 2, 1) == '1')
                                O
                                <?php $done = true; break; ?>
                                @endif
                                @endforeach
                                @endif
                                @if ($done === false) x @endif
                            </td>
                            <td class="px-4 py-2 text-left border-r last:border-r-0">
                                1-1鼓勵社區民眾參與<br>
                                1-2調查與彙整參與民眾資料<br>
                                1-3結合社區組織<br>
                                1-4派員參與防災士訓練
                            </td>
                            <td class="px-4 py-2 text-center border-r last:border-r-0">
                                @if(isset($files) && $files)
                                <div class="flex flex-row flex-wrap text-center">
                                    {!! Form::label('files[]', '附件') !!}
                                    @if(class_basename($files) === 'Collection')
                                    <div class="flex flex-col items-start justify-start w-full pb-2">
                                        @foreach ($files as $file)
                                        @if (substr($file->name, 2, 1) == '1')
                                        <div x-show="!removed_files.includes({{ $file->id }})"
                                            class="flex flex-row items-center justify-between w-full border-b py-1.5 well"
                                            data-id="{{ $file->id }}">
                                            <a href="/{{ $file->path }}"
                                                class="flex-1 text-left align-middle text-mainBlueDark">{{
                                                substr($file->name, 4)
                                                }}</a> &nbsp;
                                            <button type="button" @click="removeFile" data-id="{{ $file->id }}"
                                                class="px-4 py-2 ml-2 text-white rounded cursor-pointer whitespace-nowrap js-remove-file bg-rose-600 hover:bg-rose-500">刪除</button>
                                        </div>
                                        @endif
                                        @endforeach
                                    </div>
                                    @endif
                                    {!! Form::file('files[]', ['multiple' => true, 'term' => 1,'accept'=>'.doc, .docx,
                                    .pdf, .jpg, .jpeg, .png, .gif, .odf, .mp4, .mov']) !!}
                                </div>
                                @else
                                {!! Form::file('files[]', ['multiple' => true, 'term' => 1,'accept'=>'.doc, .docx, .pdf,
                                .jpg, .jpeg, .png, .gif, .odf, .mp4, .mov']) !!}
                                @endif
                            </td>
                        </tr>
                        <tr class="bg-white border-b odd:bg-gray-100 last:border-b-0 last:rounded-b">
                            <td class="px-4 py-2 text-center border-r last:border-r-0">
                                <?php $done = false; ?>
                                @if ($files)
                                @foreach($files as $file)
                                @if (substr($file->name, 2, 1) == '2')
                                O
                                <?php $done = true; break; ?>
                                @endif
                                @endforeach
                                @endif
                                @if ($done === false) x @endif
                            </td>
                            <td class="px-4 py-2 text-left border-r last:border-r-0">
                                2-1提升社區災害風險意識<br>
                                2-2歷史災害調查<br>
                                2-3潛勢分析<br>
                                2-4評估風險
                            </td>
                            <td class="px-4 py-2 text-center border-r last:border-r-0">
                                @if(isset($files) && $files)
                                <div class="flex flex-row flex-wrap text-center">
                                    {!! Form::label('files[]', '附件') !!}
                                    @if(class_basename($files) === 'Collection')
                                    <div class="flex flex-col items-start justify-start w-full pb-2">
                                        @foreach ($files as $file)
                                        @if (substr($file->name, 2, 1) == '2')
                                        <div x-show="!removed_files.includes({{ $file->id }})"
                                            class="flex flex-row items-center justify-between w-full border-b py-1.5 well"
                                            data-id="{{ $file->id }}">
                                            <a href="/{{ $file->path }}"
                                                class="flex-1 text-left align-middle text-mainBlueDark">{{
                                                substr($file->name, 4)
                                                }}</a> &nbsp;
                                            <button type="button" @click="removeFile" data-id="{{ $file->id }}"
                                                class="px-4 py-2 ml-2 text-white rounded cursor-pointer whitespace-nowrap js-remove-file bg-rose-600 hover:bg-rose-500">刪除</button>
                                        </div>
                                        @endif
                                        @endforeach
                                    </div>
                                    @endif
                                    {!! Form::file('files[]', ['multiple' => true, 'term' => 2,'accept'=>'.pdf, .doc,
                                    .docx,
                                    .jpg, .jpeg, .png, .gif, .zip, .rar, .txt, .csv, .xlsx, .odf, .mp4, .mov']) !!}
                                </div>
                                @else
                                {!! Form::file('files[]', ['multiple' => true, 'term' => 2,'accept'=>'.pdf, .doc, .docx,
                                .jpg, .jpeg, .png, .gif, .zip, .rar, .txt, .csv, .xlsx, .odf, .mp4, .mov']) !!}
                                @endif
                            </td>
                        </tr>
                    </tbody>
                </table>
                {!! Form::submit('送出', ['class' => 'w-full bg-rose-600 hover:bg-rose-500 text-white py-3']) !!}
            </div>
        </div>
        {!! Form::close() !!}
    </div>
</div>
@include('layouts.partials.loadingMask')
@endsection

@section('js')
<!--<script src="{{ url('scripts/genericPostForm.js') }}"></script>
<script src="{{ url('scripts/uploadForm.js') }}"></script>
<script type="text/javascript">
    $(function() {

		    $('#form').on('submit', function (event) {
	            event.preventDefault();
	            var jqForm = $(this);

	            execWithLoading(function(hideLoadingMask) {
	                var stage = jqForm.find('[name="stage"]').val();

	                var formData = new FormData();
	                    formData.append('active', 1);
	                    formData.append('dc_unit_id', jqForm.find('[name="dc_unit_id"]').val());

	                    var removed_files = '';
	                    $.each(jqForm.find('[term="js-removed-files"]'), function(idx, el) {
	                    	removed_files += $(el).val();
	                    });
	                    removed_files = removed_files.replace('[]', '');
	                    removed_files = removed_files.replace('][', ',');
	                    formData.append('removed_files', removed_files);

	                var files_1 = jqForm.find('[term="1"]').get(0).files;
	                $.each(files_1, function(idx, el) {
	                    var newFileName = stage + '-1_' + el.name;
	                    formData.append('files[]', el, newFileName);
	                });

	                var files_2 = jqForm.find('[term="2"]').get(0).files;
	                $.each(files_2, function(idx, el) {
	                    var newFileName = stage + '-2_' + el.name;
	                    formData.append('files[]', el, newFileName);
	                });

	                $.ajaxSetup({
	                    headers: {
	                        'X-CSRF-Token': jqForm.find('[name="_token"]').val()
	                    }
	                });

	                $.ajax({
	                    url: '{{ route("dc.upload.update") }}',
	                    type: 'post',
	                    dataType: 'json',
	                    data: formData,
	                    success: function (data) {
	                        hideLoadingMask();
	                        alert(data.msg);
	                        location.reload();
	                    },
	                    error: function (json) {
	                        hideLoadingMask();
	                        if (json.status === 422) {
	                            var errors = json.responseJSON;
	                            $.each(json.responseJSON, function (key, value) {
	                                alert(value);
	                            });

	                        } else {
	                           alert('unexpected');
	                        }
	                        return false;
	                    },
	                    cache: false,
	                    contentType: false,
	                    processData: false
	                });
	            });
	        });
		});
</script>!-->
@endsection