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
        <div class="relative px-5 pt-3 pb-2 bg-gray-100 border-b-2 border-gray-200 text-mainAdminTextGrayDark">編輯</div>
        <div class="p-5 m-0 bg-white">
            <div class="flex flex-row flex-wrap text-center">
                {!! Form::label('title', '單位') !!}
                {!! Form::select('user_id', $countySelectOptions, isset($signLocation->user_id) ? $signLocation->user_id
                : $authUser->id, ['class' => 'h-12 px-4 border-gray-300 rounded-md shadow-sm focus:border-cyan-300
                focus:ring focus:ring-cyan-200 focus:ring-opacity-50 w-full']) !!}
            </div>
            <div class="flex flex-row flex-wrap text-center">
                {!! Form::label('latitude', '緯度') !!}
                {!! Form::text('latitude', null, ['class' => 'h-12 px-4 border-gray-300 rounded-md shadow-sm
                focus:border-cyan-300 focus:ring focus:ring-cyan-200 focus:ring-opacity-50 w-full']) !!}
            </div>
            <div class="flex flex-row flex-wrap text-center">
                {!! Form::label('longitude', '經度') !!}
                {!! Form::text('longitude', null, ['class' => 'h-12 px-4 border-gray-300 rounded-md shadow-sm
                focus:border-cyan-300 focus:ring focus:ring-cyan-200 focus:ring-opacity-50 w-full']) !!}
            </div>
            <div class="flex flex-row flex-wrap text-center">
                {!! Form::label('location', '地址搜尋（查詢用，不寫入紀錄）') !!}
                {!! Form::text('location', null, ['class' => 'h-12 px-4 border-gray-300 rounded-md shadow-sm
                focus:border-cyan-300 focus:ring focus:ring-cyan-200 focus:ring-opacity-50 w-full']) !!}
                <div id="location-picker" style="width: 100%; height: 70vh;"></div>
            </div>
            <div class="flex flex-row flex-wrap text-center">
                {!! Form::label('description', '簡介') !!}
                {!! Form::textarea('description', null, ['class' => 'h-12 px-4 border-gray-300 rounded-md shadow-sm
                focus:border-cyan-300 focus:ring focus:ring-cyan-200 focus:ring-opacity-50 w-full', 'rows' => 10]) !!}
            </div>

            <div class="flex flex-row flex-wrap text-center">
                <label>檔案</label>

                @if (isset($signLocation))
                {!! Form::hidden('removed_files', '[]', ['id' => 'js-removed-files']) !!}
                <div class="xl:w-full">
                    @foreach($signLocation->files as $file)
                    <div class="flex flex-row items-center justify-start w-full p-2 bg-mainLight well"
                        data-id="{{ $file->id }}">
                        <a href=" /{{ $file->path }}" class="flex-1 text-left text-mainBlueDark">{{ $file->name
                            }}</a>
                        <span
                            class="px-4 text-sm text-white rounded cursor-pointer py-1.5 js-remove-file bg-rose-600 hover:bg-rose-500">刪除</span>
                    </div>
                    @endforeach
                </div>
                @endif

                <div id="js-file-input" class="mt-5">
                    {!! Form::file('files[]') !!}
                </div>
            </div>
        </div>
    </div>
</div>
<div class="relative xl:w-25/100 xl:float-left md:w-1/3">
    <div class="relative mb-6 bg-white border border-gray-200 rounded-sm">
        <div class="relative px-5 pt-3 pb-2 bg-gray-100 border-b-2 border-gray-200 text-mainAdminTextGrayDark">發表</div>
        <div class="p-5 m-0 bg-white">
            {!! Form::submit('送出', ['class' => 'w-full cursor-pointer hover:bg-teal-400 text-white flex justify-center
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
<script src="https://maps.googleapis.com/maps/api/js?key={{ config('google-map.api-key') }}&libraries=places"></script>
<script src="{{ asset('scripts/locationpicker.jquery.min.js') }}"></script>
<script>
    var latitude = {!! isset($signLocation) ? $signLocation->latitude : 23.69781 !!};
        var longitude = {!! isset($signLocation) ? $signLocation->longitude : 120.960514 !!};
        $('#location-picker').locationpicker({
            radius: 0,
            zoom: {!! isset($signLocation) ? 15 : 7 !!},
            location: {
                latitude: latitude,
                longitude: longitude
            },
            inputBinding: {
                latitudeInput: $('input[name=latitude]'),
                longitudeInput: $('input[name=longitude]'),
                locationNameInput: $('input[name=location]')
            },
            enableAutocomplete: true,
            autocompleteOptions: null,
            onchanged: function (currentLocation, radius, isMarkerDropped) {
                if ($(this).locationpicker('map').map.getZoom() < 10) {
                    $(this).locationpicker('map').map.setZoom(15);
                }
            },
        });
</script>
@endsection