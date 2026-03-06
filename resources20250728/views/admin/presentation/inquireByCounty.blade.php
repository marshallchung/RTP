@extends('admin.layouts.dashboard', [
'heading' => '管理與查詢功能',
'breadcrumbs' => [
'計畫執行成果展示',
'管理與查詢功能'
]
])

@section('title', '管理與查詢功能')

@section('inner_content')
<div x-data="{ }" class="flex flex-col items-start justify-start w-full p-4 space-y-6" x-init="$nextTick(() => {
})">
    <div class="flex flex-row flex-wrap w-full">
        {!! Form::open(['method'=>'get', 'class' => 'flex flex-row flex-wrap items-center space-x-2 justify-end
        w-full']) !!}
        {!! Form::select('category_id', $categories, request('category_id'), ['class' => 'h-12 px-4 border-gray-300
        rounded-md shadow-sm focus:border-cyan-300 focus:ring focus:ring-cyan-200 focus:ring-opacity-50 w-44']) !!}
        {!! Form::select('year', [2021 => '2021年', 2020 => '2020年', 2019 => '2019年', 2018 => '2018年', 2017 => '2017年',
        2016 => '2016年', 2015 => '2015年'],
        request('year', date('Y')), ['class' => 'h-12 px-4 border-gray-300 rounded-md shadow-sm focus:border-cyan-300
        focus:ring focus:ring-cyan-200 focus:ring-opacity-50 w-44']) !!}
        <button type="submit"
            class="flex items-center justify-center w-20 h-10 text-white bg-mainCyanDark hover:bg-teal-400">提交</button>
        <a href="{{ route('admin.reports.downloadXlsxByCounty', request()->all()) }}"
            class="flex items-center justify-center w-20 h-10 bg-gray-100 border border-gray-300 cursor-pointer text-mainAdminTextGrayDark hover:bg-gray-50 ">匯出表單</a>
        {!! Form::close() !!}
    </div>
    <div style="overflow: auto">
        <table class="w-full bg-white border shadow-lg text-mainAdminTextGrayDark">
            <thead>
                <tr>
                    <th class="p-2 font-normal text-left border-r last:border-r-0">Topic</th>
                    @foreach ($users as $user)
                    <th class="p-2 font-normal text-left border-r last:border-r-0">{{ $user->name }}</th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                @foreach ($topics as $categories)
                <tr>
                    <td colspan="{{ count($users) + 1 }}">
                        <span class="text-nowrap"><strong>{{ $categories->name }}</strong></span>
                    </td>
                </tr>
                @foreach ($categories->items as $topic)
                <tr>
                    <td class="p-2 border-r last:border-r-0">
                        <span class="text-nowrap">{{ $topic->title }}</span>
                    </td>
                    @foreach ($users as $user)
                    <td class="p-2 border-r last:border-r-0">
                        @if(isset($data[$topic->id][$user->id]) && $data[$topic->id][$user->id]>0)
                        <i class="w-6 h-6 text-lime-600 i-fa6-solid-circle-check"></i>
                        @endif
                    </td>
                    @endforeach
                </tr>
                @endforeach
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection