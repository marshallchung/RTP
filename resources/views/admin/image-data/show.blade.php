@extends('admin.layouts.dashboard', [
'heading' => '檢視圖資',
'breadcrumbs' => [
'成果網功能',
['防救災圖資', route('admin.image-data.index')],
'檢視圖資'
]
])

@section('title', '檢視圖資')

@section('inner_content')
<div class="flex flex-row flex-wrap">
    <div class="md:w-67/100 xl:w-75/100">
        <div class="relative mb-6 bg-white border border-gray-200 rounded-sm">
            <div class="relative px-5 pt-3 pb-2 bg-gray-100 border-b-2 border-gray-200 text-mainAdminTextGrayDark">檢視
            </div>
            <div class="p-5 m-0 bg-white">
                <div class="flex flex-row flex-wrap text-center">
                    {!! Form::label('user_id', '地區') !!}
                    <p class="form-control-static">{{ $imageDatum->user->name }}</p>
                </div>
                <div class="flex flex-row flex-wrap text-center">
                    {!! Form::label('image_datum_type_id', '類型') !!}
                    <p class="form-control-static">{{ $imageDatum->imageDatumType->name }}</p>
                </div>

                <div class="flex flex-row flex-wrap text-center">
                    <label>檔案</label>

                    @if (isset($imageDatum))
                    <div class="xl:w-full">
                        @foreach($imageDatum->files as $file)
                        <div class="flex flex-row items-center justify-start w-full p-2 bg-mainLight"
                            data-id="{{ $file->id }}">
                            <a href="/{{ $file->path }}" class="flex-1 text-left text-mainBlueDark">{{ $file->name
                                }}</a>
                        </div>
                        @endforeach
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    <div class="relative xl:w-25/100 xl:float-left md:w-1/3">
        <div class="relative mb-6 bg-white border border-gray-200 rounded-sm">
            <div class="relative px-5 pt-3 pb-2 bg-gray-100 border-b-2 border-gray-200 text-mainAdminTextGrayDark">編輯
            </div>
            <div class="p-5 m-0 bg-white">
                @if($hasPermission)
                <a href="{{ route('admin.image-data.edit', [$imageDatum->imageDatumType->id, $imageDatum->user->id]) }}"
                    class="btn btn-lg btn-primary" style="width: 100%;">
                    編輯
                </a>
                @else
                <a href="javascript:void(0)" class="btn btn-lg btn-primary disabled" style="width: 100%;">
                    編輯
                </a>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection