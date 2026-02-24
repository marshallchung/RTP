<div class="md:w-67/100 xl:w-75/100">
    <div class="relative mb-6 bg-white border border-gray-200 rounded-sm">
        <div class="p-5 m-0 bg-white">
            <div class="flex flex-row flex-wrap text-center">
                <label>{{ $data->title }}</label>
            </div>

            <div class="flex flex-row flex-wrap text-center">
                <label>分類</label>
                <div>{{ $data->sort }}</div>
            </div>

            <div class="flex flex-row flex-wrap text-center">
                <label>內容</label>
                <div>{!! $data->content !!}</div>
            </div>

            @if(isset($files))
            <div class="flex flex-col items-start justify-start w-full space-y-4">
                @foreach($files as $file)
                <div class="flex flex-row items-center justify-start w-full p-2 bg-mainLight" data-id="{{ $file->id }}">
                    <a href="/{{ $file->path }}" target="_blank" class="flex-1 text-left text-mainBlueDark">{{
                        $file->name }}</a>
                </div>
                @endforeach
            </div>
            @endif
        </div>
    </div>
</div>

@section('scripts')
@endsection