<div x-data="{
        showDeleteModel:false,
    }" class="md:w-67/100 xl:w-75/100" x-data="" x-init="$nextTick(() => {
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
        <div class="relative px-5 pt-3 pb-2 bg-gray-100 border-b-2 border-gray-200 text-mainAdminTextGrayDark">編輯</div>
        <div class="p-5 m-0 bg-white">

            <div class="flex flex-row flex-wrap text-center">
                {!! Form::label('name', '名稱') !!}
                {!! Form::text('name', null, ['class' => 'h-12 px-4 border-gray-300 rounded-md shadow-sm
                focus:border-cyan-300 focus:ring focus:ring-cyan-200 focus:ring-opacity-50 w-full']) !!}
            </div>

            <div class="flex flex-col items-start justify-start w-full space-y-4">
                {!! Form::label('files[]', '檔案') !!}
                {!! Form::hidden('removed_files', '[]', ['id' => 'js-removed-files']) !!}
                <div class="mb-6 xl:w-full">
                    @if (isset($data))
                    @foreach($data->files as $file)
                    <div class="flex flex-row items-center justify-start w-full p-2 bg-mainLight"
                        data-id="{{ $file->id }}">
                        <a href="/{{ $file->path }}" target="_blank" class="flex-1 text-left text-mainBlueDark">{{
                            $file->name }}</a>
                        <span
                            class="px-4 text-sm text-white rounded cursor-pointer py-1.5 js-remove-file bg-rose-600 hover:bg-rose-500">刪除</span>
                    </div>
                    @endforeach
                    @endif
                </div>
                {!! Form::file('files[]', ['multiple' => true,'accept'=>'.pdf, .doc, .docx,
                .jpg, .jpeg, .png, .gif, .zip, .rar, .txt, .csv, .xlsx, .odf, .mp4, .mov']) !!}
            </div>
        </div>
    </div>
</div>
<div class="relative xl:w-25/100 xl:float-left md:w-1/3">
    <div class="relative mb-6 bg-white border border-gray-200 rounded-sm">
        <div class="relative px-5 pt-3 pb-2 bg-gray-100 border-b-2 border-gray-200 text-mainAdminTextGrayDark">發表</div>
        <div class="flex flex-col p-5 space-y-6 bg-white">
            <div class="flex flex-row flex-wrap text-center">
                <label class="checkbox-inline">
                    {!! Form::hidden('active', 0) !!}
                    {!! Form::checkbox('active', 1, null, ['class' => 'border-gray-300 rounded-sm bg-white
                    text-mainCyanDark']) !!}
                    <span class="text-sm text-mainAdminTextGrayDark">是否上線</span>
                </label>
            </div>
            {!! Form::submit('送出', ['class' => 'w-full cursor-pointer hover:bg-teal-400 text-white flex
            justify-center
            items-center h-10 bg-mainCyanDark
            rounded']) !!}
        </div>
        @if (isset($showDelete))
        <div class="border-t py-2.5 px-5 rounded-br-sm rounded-bl-sm bg-white">
            <a @click="showDeleteModel=true" class="cursor-pointer text-mainBlueDark">刪除</a>
        </div>
        @endif
    </div>
</div>

@section('scripts')
<script src="{{ asset('scripts/genericPostForm.js') }}"></script>
<script src="{{ asset('scripts/uploadForm.js') }}"></script>
@endsection