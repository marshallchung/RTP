@extends('admin.layouts.dashboard', [
'heading' => '通訊錄',
'breadcrumbs' => [
'通訊錄',
'通訊錄查詢'
]
])

@section('title', '通訊錄查詢')

@section('inner_content')
<div x-data="{
    getData(page){
        var url = '{{ request()->url() }}?page=' + encodeURIComponent(page);
        location.href = url;
    },
    filterChange(e){
        if(document.getElementById('child')){
            document.getElementById('child').setAttribute('disabled','disabled');
        }
        document.getElementById('form').submit();
    },
    childChange(e){
        document.getElementById('form').submit();
    },
 }" class="flex flex-col items-end justify-start w-full p-4 space-y-4">
    <div class="flex flex-row flex-wrap w-full">
        {!! Form::open(['method'=>'get', 'class' => 'flex flex-row flex-wrap text-center', 'id' => 'form', 'class' =>
        'flex flex-row flex-wrap items-center justify-start w-full
        space-x-2']) !!}
        {!! Form::select('county_id', $counties, request('county_id'), ['@change'=>'filterChange',
        'class' => 'h-12 px-4 border-gray-300 rounded-md shadow-sm focus:border-cyan-300
        focus:ring focus:ring-cyan-200 focus:ring-opacity-50 w-36',
        'id' => 'county_id',
        ]) !!}
        @if (!empty($childrenUnits))
        {!! Form::select('child', $childrenUnits, request('child'), ['@change'=>'childChange','class' => 'h-12 px-4
        border-gray-300 rounded-md
        shadow-sm focus:border-cyan-300 focus:ring focus:ring-cyan-200 focus:ring-opacity-50', 'id' => 'child'])
        !!}
        @endif
        {{-- <button type="submit"
            class=" px-4 text-sm text-white rounded cursor-pointer py-1.5 bg-mainCyanDark hover:bg-teal-400">提交</button>--}}
        <a href="{{ route('admin.address.downloadXlsx', request()->all()) }}"
            class="flex items-center justify-center w-20 h-10 text-white bg-mainCyanDark">匯出表單</a>
        {!! Form::close() !!}
    </div>
    <div class="w-full overflow-scroll">
        <table class="w-full bg-white border shadow-lg text-mainAdminTextGrayDark">
            <thead>
                <tr class="border-b rounded-t bg-mainGray">
                    <th class="p-2 font-normal text-left border-r last:border-r-0">縣市&分區</th>
                    <th class="p-2 font-normal text-left border-r last:border-r-0">單位</th>
                    <th class="p-2 font-normal text-left border-r last:border-r-0">職稱</th>
                    <th class="p-2 font-normal text-left border-r last:border-r-0">姓名</th>
                    <th class="p-2 font-normal text-left border-r last:border-r-0">公務電話</th>
                    <th class="p-2 font-normal text-left border-r last:border-r-0">電子郵件</th>
                    <th class="p-2 font-normal text-left border-r last:border-r-0">更新時間</th>
                </tr>
            </thead>
            <tbody class="text-content text-mainAdminTextGrayDark">
                @foreach ($addresses as $address)
                <tr>
                    <td class="p-2 border-r last:border-r-0">
                        @if($address->county)
                        {{ $address->county->full_county_name }}
                        @endif
                    </td>
                    <td class="p-2 border-r last:border-r-0">{{ $address->unit }}</td>
                    <td class="p-2 border-r last:border-r-0">{{ $address->title }}</td>
                    <td class="p-2 border-r last:border-r-0">{{ $address->name }}</td>
                    <td class="p-2 border-r last:border-r-0">{{ $address->phone }}</td>
                    <td class="p-2 border-r last:border-r-0">{{ $address->email }}</td>
                    <td class="p-2 border-r last:border-r-0">{{ $address->updated_at }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div>
        {!! $addresses->render() !!}
    </div>
</div>
@endsection

@section('scripts')
@endsection