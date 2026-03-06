@extends('admin.layouts.dashboard', [
'heading' => '防災避難看板',
'header_btn' => ['新增', route('admin.sign-location.create'),
'地圖', route('admin.sign-location.map')],
'breadcrumbs' => [
'成果網功能',
'防災避難看板'
]
])

@section('title', '防災避難看板')

@section('inner_content')
<div x-data="{
    showDeleteModel:false,
}" class="flex flex-col items-end justify-start w-full p-4 space-y-4">
    <div class="flex flex-row flex-wrap w-full">
        {!! Form::open(['method'=>'get', 'class' => 'flex flex-row flex-wrap text-center', 'id' => 'form', 'class' =>
        'flex flex-row flex-wrap items-center justify-start w-full
        space-x-2']) !!}
        {!! Form::select('county_id', $counties, request('county_id'), ['class' => 'h-12 px-4 border-gray-300 rounded-md
        shadow-sm focus:border-cyan-300
        focus:ring focus:ring-cyan-200 focus:ring-opacity-50 w-36']) !!}
        <button type="submit" class="flex items-center justify-center w-20 h-10 text-white bg-mainCyanDark">搜尋</button>
        <a href="{{ route('admin.sign-location.downloadXlsx', request()->all()) }}"
            class="flex items-center justify-center h-10 px-4 bg-gray-100 border border-gray-300 cursor-pointer text-mainAdminTextGrayDark hover:bg-gray-50">依搜尋結果匯出表單</a>
        {!! Form::close() !!}
    </div>
    <table class="w-full bg-white border shadow-lg text-mainAdminTextGrayDark">
        <thead>
            <tr class="border-b rounded-t bg-mainGray">
                <th class="w-20 p-2 font-normal text-left border-r last:border-r-0">單位</th>
                <th class="p-2 font-normal text-left border-r last:border-r-0">簡介</th>
                <th class="p-2 font-normal text-left border-r last:border-r-0">緯度</th>
                <th class="p-2 font-normal text-left border-r last:border-r-0">經度</th>
                <th class="p-2 font-normal text-left border-r last:border-r-0">檔案</th>
            </tr>
        </thead>
        <tbody class="text-content text-mainAdminTextGrayDark">
            @foreach ($signLocations as $signLocation)
            <tr>
                <td class="p-2 border-r last:border-r-0">{{ $signLocation->user->name }}</td>
                <td class="p-2 border-r last:border-r-0">{!! Html::linkroute('admin.sign-location.show',
                    $signLocation->description ?: '(無)', $signLocation->id)
                    !!}</td>
                <td class="p-2 border-r last:border-r-0">{{ $signLocation->latitude }}</td>
                <td class="p-2 border-r last:border-r-0">{{ $signLocation->longitude }}</td>
                <td class="p-2 border-r last:border-r-0">
                    @foreach($signLocation->files as $file)
                    <a href="/{{ $file->path }}" target="_blank" class="flex-1 text-left text-mainBlueDark">{{
                        $file->name
                        }}</a><br />
                    @endforeach
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <div class="">
        @if (request('county_id'))
        {!! $signLocations->appends(['county_id' => request('county_id')])->render() !!}
        @else
        {!! $signLocations->render() !!}
        @endif
    </div>
</div>
@endsection