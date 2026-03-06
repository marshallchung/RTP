@extends('layouts.app')

@section('title', '防災士培訓')
@section('subtitle', '社團法人臺灣防災教育訓練學會查詢')

@section('content')
{{-- <div class="flex flex-row flex-wrap">--}}
    {{-- <div class="xl:w-full col-md-12">--}}
        {{-- <table class="w-full bg-white border shadow-lg text-mainAdminTextGrayDark">--}}
            {{-- <thead>--}}
                {{-- <tr>--}}
                    {{-- <th class="p-2 font-normal text-left border-r last:border-r-0">機構名稱</th>--}}
                    {{-- <th class="p-2 font-normal text-left border-r last:border-r-0">網址</th>--}}
                    {{-- </tr>--}}
                {{-- </thead>--}}
            {{-- <tbody>--}}
                {{-- @foreach ($data as $item)--}}
                {{-- <tr>--}}
                    {{-- <td class="p-2 border-r last:border-r-0">{!! Html::linkroute('dp.civilShow', $item->name,
                        $item->id) !!}</td>--}}
                    {{-- <td class="p-2 border-r last:border-r-0"><a href="{{ $item->url }}" target="_blank">{{
                            $item->url }}</a></td>--}}
                    {{-- </tr>--}}
                {{-- @endforeach--}}
                {{-- </tbody>--}}
            {{-- </table>--}}
        {{-- </div>--}}
    {{-- </div>--}}
@foreach ($data as $item)
@include('dp.civilInfo', ['data'=>$item])
@endforeach
@endsection

@section('js')

@endsection