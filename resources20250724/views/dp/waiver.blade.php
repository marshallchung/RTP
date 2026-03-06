@extends('layouts.app')

@section('title', '防災士培訓')
@section('subtitle', '課程抵免')

@section('content')

<div class="flex flex-row items-start justify-start w-full" x-data="" x-init="$nextTick(() => {
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
    <div class="w-full">
        <table class="w-full bg-white border shadow-lg text-mainAdminTextGrayDark">
            <thead>
                <tr>
                    <th class="p-2 font-normal text-left border-r last:border-r-0">抵免課程名稱</th>
                    <th class="p-2 font-normal text-left border-r last:border-r-0">參與其他防災課程名稱</th>
                    <th class="p-2 font-normal text-left border-r last:border-r-0">辦理單位</th>
                    <th class="p-2 font-normal text-left border-r last:border-r-0">辦理時間</th>
                    <th width="180">管理檔案</th>
                    <th width="270">上傳檔案</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($user->dpWaivers as $item)
                <tr>
                    {!! Form::open([
                    'method' => 'POST',
                    'route' => ['dp.uploadWaiverFiles', $item->id],
                    'files' => true,
                    'class' => 'flex flex-row flex-wrap text-center',
                    ]) !!}
                    <td class="p-2 border-r last:border-r-0">{{ $item->dpScore->dCourse->county->name }} - {{
                        $item->dpScore->dpCourse->name }}</td>
                    <td class="p-2 border-r last:border-r-0">{{ $item->name }}</td>
                    <td class="p-2 border-r last:border-r-0">{{ $item->dpScore->author->name }}</td>
                    <td class="p-2 border-r last:border-r-0">{{ $item->created_at }}</td>
                    <td style="padding:0.6em 0.1em">
                        @if (isset($item->files[0]))
                        {!! Form::hidden('removed_files', '[]', ['term' => 'js-removed-files']) !!}
                        @foreach ($item->files as $file)
                        <div class="xl:w-full">
                            <div class="flex flex-row items-center justify-start w-full p-2 bg-mainLight well"
                                data-id="{{ $file->id }}">
                                <a href="{{ url($file->path) }}" class="flex-1 text-left text-mainBlueDark">{{
                                    $file->name
                                    }}</a> &nbsp;
                                <span
                                    class="px-4 text-sm text-white rounded cursor-pointer py-1.5 js-remove-file bg-rose-600 hover:bg-rose-500">刪除</span>
                            </div>
                        </div>
                        @endforeach
                        @endif
                    </td>
                    <td style="padding:0.6em 0.1em">
                        {!! Form::file('files[]', ['style' => 'max-width: 202px', 'multiple' => true, 'term' =>
                        1,'accept'=>'.pdf, .doc, .docx,
                        .jpg, .jpeg, .png, .gif, .zip, .rar, .txt, .csv, .xlsx, .odf, .mp4, .mov']) !!}
                        {!! Form::submit('送出', ['class' => 'btn btn-sm btn-danger']) !!}
                    </td>
                    {!! Form::close() !!}
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection

@section('js')
<script src="{{ url('scripts/genericPostForm.js') }}"></script>
<script src="{{ url('scripts/uploadForm.js') }}"></script>
@endsection