@extends('admin.layouts.dashboard', [
'heading' => '編輯韌性社區',
'breadcrumbs' => [
['推動韌性社區', route('admin.dc-units.index')],
'編輯'
]
])

@section('title', $data->title)

@section('inner_content')
<div x-data="{
    countyList:{{ json_encode($countyList) }},
}" class="md:w-67/100 xl:w-75/100">
    <div class="relative mb-6 bg-white border border-gray-200 rounded-sm">
        <div class="relative px-5 pt-3 pb-2 bg-gray-100 border-b-2 border-gray-200 text-mainAdminTextGrayDark">新增</div>
        <div class="p-5 m-0 bg-white">
            {!! Form::model($data, ['route' => ['admin.dc-units.update', $data->id], 'method' => 'put', 'files' =>
            true]) !!}
            <div class="flex flex-row flex-wrap text-center">
                {!! Form::label('name', '社區名稱') !!}
                {!! Form::text('name', null, ['class' => 'h-12 px-4 border-gray-300 rounded-md shadow-sm
                focus:border-cyan-300 focus:ring focus:ring-cyan-200 focus:ring-opacity-50 w-full', 'required',
                'disabled']) !!}
            </div>

            <div class="flex flex-row flex-wrap text-center">
                {!! Form::label('population', '社區居住人數') !!}
                {!! Form::text('population', null, ['class' => 'h-12 px-4 border-gray-300 rounded-md shadow-sm
                focus:border-cyan-300 focus:ring focus:ring-cyan-200 focus:ring-opacity-50 w-full', 'required',
                'disabled']) !!}
            </div>

            <div class="flex flex-row flex-wrap text-center">
                {!! Form::label('pattern', '社區類型') !!}
                {!! Form::select('pattern', [
                null => null,
                '都市型' => '都市型',
                '鄉村型' => '鄉村型',
                ], null, ['class' => 'h-12 px-4 border-gray-300 rounded-md shadow-sm focus:border-cyan-300 focus:ring
                focus:ring-cyan-200 focus:ring-opacity-50 w-full', 'disabled']) !!}
            </div>

            <div class="flex flex-row flex-wrap text-center">
                {!! Form::label('county_id', '所在縣市') !!}
                {!! Form::select('county_id', $counties, $data->county_id, ['class' => 'h-12 px-4 border-gray-300
                rounded-md shadow-sm focus:border-cyan-300 focus:ring focus:ring-cyan-200 focus:ring-opacity-50 w-full',
                'disabled']) !!}
            </div>

            <div class="flex flex-row flex-wrap text-center">
                {!! Form::label('township', '鄉鎮市區') !!}
                {!! Form::text('township', null, $data->township, ['class' => 'h-12 px-4 border-gray-300
                rounded-md shadow-sm focus:border-cyan-300 focus:ring focus:ring-cyan-200 focus:ring-opacity-50 w-full',
                'disabled']) !!}
            </div>

            <div class="flex flex-row flex-wrap text-center">
                {!! Form::label('village', '村里') !!}
                {!! Form::text('village', null, $data->village, ['class' => 'h-12 px-4 border-gray-300
                rounded-md shadow-sm focus:border-cyan-300 focus:ring focus:ring-cyan-200 focus:ring-opacity-50 w-full',
                'disabled']) !!}
            </div>

            <div class="flex flex-row flex-wrap text-center">
                {!! Form::label('location', '社區概略範圍') !!}
                {!! Form::text('location', null, ['class' => 'h-12 px-4 border-gray-300 rounded-md shadow-sm
                focus:border-cyan-300 focus:ring focus:ring-cyan-200 focus:ring-opacity-50 w-full', 'required',
                'disabled']) !!}
            </div>

            <div class="flex flex-row flex-wrap text-center">
                {!! Form::label('files[]', '社區概略範圍示意圖') !!}
                @if(class_basename($data->files) === 'Collection')
                {!! Form::hidden('removed_files', '[]', ['id' => 'js-removed-files']) !!}
                <div class="mb-6 xl:w-full">
                    @foreach($data->files as $file)
                    @if ($file->memo == 'dc-location')
                    <div class="flex flex-row items-center justify-start w-full p-2 bg-mainLight"
                        data-id="{{ $file->id }}">
                        <a href="/{{ $file->path }}" target="_blank" class="flex-1 text-left text-mainBlueDark">{{
                            $file->name }}</a>
                    </div>
                    @endif
                    @endforeach
                </div>
                @endif
            </div>

            <div class="flex flex-row flex-wrap text-center">
                {!! Form::label('is_experienced', '過去是否曾推動過防災社區') !!}
                <div class="flex flex-row flex-wrap text-center">
                    <?php
                        $true = false;
                        $false = false;
                        if ($data->is_experienced == '1') $male = true;
                        if ($data->is_experienced == '1') $female = true;
                        ?>
                    <label class="radio-inline">{!! Form::radio('is_experienced', '1', $true, ['disabled','class' =>
                        'border-gray-300 rounded-full bg-white text-mainCyanDark']) !!}
                        是</label>
                    <label class="radio-inline">{!! Form::radio('is_experienced', '0', $true, ['disabled','class' =>
                        'border-gray-300 rounded-full bg-white text-mainCyanDark']) !!}
                        否</label>
                </div>
            </div>

            <div class="flex flex-row flex-wrap text-center">
                {!! Form::label('environment', '社區環境概述') !!}
                {!! Form::text('environment', null, ['class' => 'h-12 px-4 border-gray-300 rounded-md shadow-sm
                focus:border-cyan-300 focus:ring focus:ring-cyan-200 focus:ring-opacity-50 w-full', 'required',
                'disabled']) !!}
            </div>

            <div class="flex flex-row flex-wrap text-center">
                {!! Form::label('risk', '社區災害潛勢與風險概述') !!}
                {!! Form::text('risk', null, ['class' => 'h-12 px-4 border-gray-300 rounded-md shadow-sm
                focus:border-cyan-300 focus:ring focus:ring-cyan-200 focus:ring-opacity-50 w-full', 'disabled']) !!}
            </div>

            <div class="flex flex-row flex-wrap text-center">
                {!! Form::label('manager', '韌性社區負責人姓名') !!}
                {!! Form::text('manager', null, ['class' => 'h-12 px-4 border-gray-300 rounded-md shadow-sm
                focus:border-cyan-300 focus:ring focus:ring-cyan-200 focus:ring-opacity-50 w-full', 'required',
                'disabled']) !!}
            </div>

            <div class="flex flex-row flex-wrap text-center">
                {!! Form::label('phone', '韌性社區負責人電話') !!}
                {!! Form::text('phone', null, ['class' => 'h-12 px-4 border-gray-300 rounded-md shadow-sm
                focus:border-cyan-300 focus:ring focus:ring-cyan-200 focus:ring-opacity-50 w-full', 'required',
                'disabled']) !!}
            </div>

            <div class="flex flex-row flex-wrap text-center">
                {!! Form::label('email', '韌性社區負責人Email') !!}
                {!! Form::text('email', null, ['class' => 'h-12 px-4 border-gray-300 rounded-md shadow-sm
                focus:border-cyan-300 focus:ring focus:ring-cyan-200 focus:ring-opacity-50 w-full', 'disabled']) !!}
            </div>

            <div class="flex flex-row flex-wrap text-center">
                {!! Form::label('manager_address', '韌性社區負責人地址') !!}
                {!! Form::text('manager_address', null, ['class' => 'h-12 px-4 border-gray-300 rounded-md shadow-sm
                focus:border-cyan-300 focus:ring focus:ring-cyan-200 focus:ring-opacity-50 w-full', 'required',
                'disabled']) !!}
            </div>

            <div class="flex flex-row flex-wrap text-center">
                {!! Form::label('manager_position', '擔任社區職務') !!}
                {!! Form::text('manager_position', null, ['class' => 'h-12 px-4 border-gray-300 rounded-md shadow-sm
                focus:border-cyan-300 focus:ring focus:ring-cyan-200 focus:ring-opacity-50 w-full', 'required',
                'disabled']) !!}
            </div>

            <div class="flex flex-row flex-wrap text-center">
                {!! Form::label('dp_name', '防災士姓名') !!}
                {!! Form::text('dp_name', null, ['class' => 'h-12 px-4 border-gray-300 rounded-md shadow-sm
                focus:border-cyan-300 focus:ring focus:ring-cyan-200 focus:ring-opacity-50 w-full', 'disabled']) !!}
            </div>

            <div class="flex flex-row flex-wrap text-center">
                {!! Form::label('dp_phone', '防災士電話') !!}
                {!! Form::text('dp_phone', null, ['class' => 'h-12 px-4 border-gray-300 rounded-md shadow-sm
                focus:border-cyan-300 focus:ring focus:ring-cyan-200 focus:ring-opacity-50 w-full', 'disabled']) !!}
            </div>

            <div class="flex flex-row flex-wrap text-center">
                {!! Form::label('rank', '星等') !!}
                {!! Form::select('rank', $ranks, null, ['class' => 'h-12 px-4 border-gray-300 rounded-md shadow-sm
                focus:border-cyan-300 focus:ring focus:ring-cyan-200 focus:ring-opacity-50 w-full', 'required',
                'disabled']) !!}
            </div>

            <div class="flex flex-row flex-wrap text-center">
                {!! Form::label('rank_started_date', '星等生效日期') !!}
                {!! Form::date('rank_started_date', null, ['class' => 'h-12 px-4 border-gray-300 rounded-md shadow-sm
                focus:border-cyan-300 focus:ring focus:ring-cyan-200 focus:ring-opacity-50 w-full', 'disabled']) !!}
            </div>
            <div class="flex flex-row flex-wrap text-center">
                <span>星等截止日期：{{ $rank_started_date }}</span>
            </div>

            {!! Form::close() !!}
        </div>
    </div>
</div>

<script src="{{ asset('scripts/genericPostForm.js') }}"></script>
@endsection