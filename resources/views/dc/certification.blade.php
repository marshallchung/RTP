@extends('layouts.app')

@section('title', '推動韌性社區')
@section('subtitle', '標章申請作業')

@section('content')
<div x-data="{
    removed_files:{},
    all_removed:[],
    removeFile(e){
        const group=e.target.dataset.group;
        const id=parseInt(e.target.dataset.id);
        this.all_removed.push(id);
        if(!this.removed_files.hasOwnProperty(group)){
            this.removed_files[group]=[];
        }
        this.removed_files[group].push(id);
    },
    onSubmit(e){
        var formData = new FormData(e.target);
        for (const [key, value] of Object.entries(this.removed_files)) {
            formData.append('removed_files_' + key, JSON.stringify(value));
        }
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
            }else if (data.msg) {
                alert(data.msg);
                location.reload();
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
        <form method="POST" action="{{ route('dc.certification.update') }}" accept-charset="UTF-8" id="form"
            @submit.prevent="onSubmit" enctype="multipart/form-data">
            <div class="relative flex flex-col w-full mb-6 bg-white border border-gray-200 rounded">
                <div
                    class="relative px-5 pt-3 pb-2 text-2xl bg-gray-100 border-b-2 border-gray-200 rounded-t text-mainAdminTextGrayDark">
                    韌性社區基本資料
                </div>
                <div id="divStudentData" class="flex flex-col w-full p-5 m-0 space-y-4">
                    <div class="flex flex-row items-center w-full">
                        <input name="dc_unit_id" type="hidden" value="25">
                        <div class="w-1/4 text-center">
                            <label for="dc_unit_id">社區名稱</label>
                        </div>
                        <div class="flex-1">
                            <h4>{{ $data->name }}</h4>
                        </div>
                    </div>

                    <div class="flex flex-row items-center w-full">
                        <div class="w-1/4 text-center">
                            <label for="county_id">縣市</label>
                        </div>
                        <div class="flex-1">
                            <h4>{{ $county_name }}</h4>
                        </div>
                    </div>
                </div>
            </div>
            <div class="relative mb-6 bg-white border border-gray-200 rounded-sm">
                <div
                    class="relative px-5 pt-3 pb-2 text-2xl bg-gray-100 border-b-2 border-gray-200 text-mainAdminTextGrayDark">
                    工作項目</div>
                <div class="flex flex-row flex-wrap text-center">
                    <div class="flex flex-row flex-wrap">
                        <div class="col-md-2"></div>
                        <div class="col-md-10">
                            <ul class="list-none">
                                <li>資料上傳須知：
                                    <ul>
                                        <li>請確定檔案格式為.doc, .docx, .pdf, .jpg, .jpeg, .png, .gif, .zip, .rar, .odf, .mp4,
                                            .mov
                                        </li>
                                        <li>請點選送出按鈕，將上傳檔案提交。</li>
                                    </ul>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="flex flex-col items-start justify-start flex-1 w-full overflow-scroll">
                    <table class="w-full bg-white border text-mainAdminTextGrayDark min-w-[50rem]">
                        <thead>
                            <tr class="text-white border-b rounded-t bg-mainGrayDark">
                                <th class="w-20 p-2 font-bold border-r last:border-r-0">繳交</th>
                                <th class="p-2 font-bold border-r last:border-r-0">工作項目</th>
                                <th class="p-2 font-bold border-r last:border-r-0">檔案上傳</th>
                                <th class="p-2 font-bold border-r w-28 last:border-r-0">審查結果</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach(config('dc.certification.items') as $idx => $item)
                            <tr class="bg-white border-b odd:bg-gray-100 last:border-b-0 last:rounded-b">
                                <td class="p-4 text-center border-r last:border-r-0">
                                    @if($dc_certifications->has($idx))
                                    O
                                    @else
                                    X
                                    @endif
                                </td>
                                <td class="px-4 py-2 text-left border-r last:border-r-0">
                                    {{ $item }}
                                </td>
                                <td class="px-4 py-2 text-left border-r last:border-r-0">
                                    @if($files = $dc_certifications->get($idx)->files ?? [])
                                    <div class="flex flex-col items-start justify-start">
                                        <span>附件</span>
                                        @if(class_basename($files) === 'Collection')
                                        <div class="flex flex-col items-start justify-start w-full pb-2">
                                            @foreach($files as $file)
                                            <div x-show="!all_removed.includes({{ $file->id }})"
                                                class="flex flex-row items-center justify-between w-full border-b py-1.5 well"
                                                data-id="{{ $file->id }}">
                                                <a href="/{{ $file->path }}" target="_blank"
                                                    class="flex-1 text-left align-middle text-mainBlueDark">{{
                                                    $file->name
                                                    }}</a>
                                                <button type="button" @click="removeFile" data-id="{{ $file->id }}"
                                                    data-group="{{ $idx }}"
                                                    class="px-4 py-2 ml-2 text-white rounded whitespace-nowrap js-remove-file bg-rose-600 hover:bg-rose-500">刪除</button>
                                            </div>
                                            @endforeach
                                        </div>
                                        @endif
                                        <input multiple="" name="files_{{ $idx }}[]" type="file"
                                            accept=".doc, .docx, .pdf, .jpg, .jpeg, .png, .gif, .zip, .rar, .odf, .mp4, .mov">
                                    </div>
                                    @else
                                    <input multiple="" name="files_{{ $idx }}[]" type="file"
                                        accept=".doc, .docx, .pdf, .jpg, .jpeg, .png, .gif, .zip, .rar, .odf, .mp4, .mov">
                                    @endif
                                </td>
                                <td class="px-4 py-2 text-center border-r last:border-r-0">{{
                                    $dc_certifications->get($idx)->review_result_text ?? '' }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <input class="w-full py-3 text-white bg-rose-600 hover:bg-rose-500" type="submit" value="送出">
                </div>
            </div>
        </form>
    </div>
</div>
@include('layouts.partials.loadingMask')
@endsection

@section('js')
@endsection
