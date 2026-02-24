@extends('admin.layouts.dashboard', [
'heading' => '防救災圖資',
'breadcrumbs' => [
'成果網功能',
'防救災圖資'
]
])

@section('title', '防救災圖資')

@section('inner_content')
<div class="col-sm-12 p-0">
    <div class="flex flex-row flex-wrap">
        {!! Form::open(['method'=>'get', 'class' => 'flex flex-row flex-wrap text-center']) !!}
        {!! Form::select('county_id', $counties, request('county_id'), ['class' => 'h-12 px-4 border-gray-300 rounded-md
        shadow-sm focus:border-cyan-300 focus:ring focus:ring-cyan-200 focus:ring-opacity-50 w-full']) !!}
        {!! Form::select('image_datum_type_id', $categories, request('image_datum_type_id'), ['class' =>
        'form-control']) !!}
        {{-- {!! Form::select('year', [2017 => '2017年', 2016 => '2016年', 2015 => '2015年'], request('year', 2017),
        ['class' => 'h-12 px-4 border-gray-300 rounded-md shadow-sm focus:border-cyan-300 focus:ring focus:ring-cyan-200
        focus:ring-opacity-50 w-full']) !!}--}}
        <button type="submit" class=" flex items-center justify-center w-20 h-10 bg-mainCyanDark text-white">搜尋</button>
        {{--<a href="{{ route('admin.reports.downloadXlsx', request()->all()) }}"
            class="flex items-center justify-center w-20 h-10 bg-gray-100 border border-gray-300 text-mainAdminTextGrayDark cursor-pointer hover:bg-gray-50 ">匯出表單</a>--}}
        {!! Form::close() !!}
    </div>
    <div style="overflow: auto">
        <table class="w-full bg-white border shadow-lg text-mainAdminTextGrayDark">
            <thead>
                <tr>
                    <th class="p-2 font-normal text-left border-r last:border-r-0">Type</th>
                    @foreach ($users as $user)
                    <th class="p-2 font-normal text-left border-r last:border-r-0">{{ $user->name }}</th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                @foreach ($imageDatumTypes as $imageDatumType)
                <tr>
                    <td class="p-2 border-r last:border-r-0">
                        <span class="text-nowrap">{{ $imageDatumType->name }}</span>
                    </td>
                    @foreach ($users as $user)
                    <td class="p-2 border-r last:border-r-0">
                        @if(isset($data[$imageDatumType->id][$user->id]) && $data[$imageDatumType->id][$user->id]>0)
                        <a href="{{ route('admin.image-data.show', [$imageDatumType, $user]) }}">
                            <i class="fa fa-check-circle text-success text-lg"></i>
                        </a>
                        @elseif($hasSuperPerm || in_array($user->id, $hasPermIds))
                        <a href="{{ route('admin.image-data.create', [$imageDatumType, $user]) }}">
                            <i class="fa fa-plus-circle text-lg text-gray-400" aria-hidden="true"></i>
                        </a>
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