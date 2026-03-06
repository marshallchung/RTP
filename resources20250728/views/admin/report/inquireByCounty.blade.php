@extends('admin.layouts.dashboard', [
'heading' => '管理與查詢功能',
'breadcrumbs' => [
'成果資料展示',
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
        rounded-md shadow-sm focus:border-cyan-300 focus:ring focus:ring-cyan-200 focus:ring-opacity-50 w-72']) !!}
        <select
            class="h-12 px-4 border-gray-300 rounded-md shadow-sm focus:border-cyan-300 focus:ring focus:ring-cyan-200 focus:ring-opacity-50 w-44"
            id="year" name="year">
            @foreach ($availableYears as $one_year)
            <option value="{{ $one_year }}" {{ $one_year==request('year')?'selected':'' }}>{{ intval($one_year)-1911 }}年
            </option>
            @endforeach
        </select>
        <button type="submit"
            class="flex items-center justify-center w-20 h-10 text-white bg-mainCyanDark hover:bg-teal-400">提交</button>
        <a href="{{ route('admin.reports.downloadXlsxByCounty', request()->all()) }}"
            class="flex items-center justify-center w-20 h-10 bg-gray-100 border border-gray-300 cursor-pointer text-mainAdminTextGrayDark hover:bg-gray-50">匯出表單</a>
        {!! Form::close() !!}
    </div>
    <div class="border shadow-lg" style="overflow: auto">
        <table class="w-full min-w-[40rem] bg-white text-mainAdminTextGrayDark">
            <thead>
                <tr class="bg-gray-200">
                    <th class="p-2 font-normal text-left border-r last:border-r-0">Topic</th>
                    @foreach ($users as $user)
                    <th class="w-24 p-2 font-normal text-left border-r last:border-r-0">{{ $user->name }}</th>
                    @endforeach
                </tr>
            </thead>
            <tbody class="text-content text-mainAdminTextGrayDark">
                <tr class="border-b last:border-b-0 odd:bg-white even:bg-mainLight">
                    <td class="p-2 border-r last:border-r-0">
                    </td>
                    @foreach ($users as $user)
                    <td class="p-2 text-center border-r last:border-r-0">
                        @if(isset($county_files[$user->id]) && !empty($county_files[$user->id]))
                        <a class="text-blue-500 "
                            href="/admin/reports/downloadFilesByCounty?year={{ $year }}&category_id={{ request('category_id') }}&user_id={{ $user->id }}">下載</a>
                        @endif
                    </td>
                    @endforeach
                </tr>
                @foreach ($topics as $topic)
                <tr class="border-b last:border-b-0 odd:bg-white even:bg-mainLight">
                    <td class="p-2 border-r last:border-r-0">
                        <span class="text-nowrap">{{ $topic->title }}</span>
                    </td>
                    @foreach ($users as $user)
                    <td class="p-2 text-center border-r last:border-r-0">
                        @if(isset($data[$topic->id][$user->id]) && $data[$topic->id][$user->id]>0)
                        <i class="w-6 h-6 text-lime-600 i-fa6-solid-circle-check"></i>
                        @endif
                    </td>
                    @endforeach
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection