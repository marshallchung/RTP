@extends('admin.layouts.dashboard', [
'heading' => '社區防災計畫書清單',
'breadcrumbs' => ['社區防災計畫書清單']
])

@section('title', '社區防災計畫書清單')

@section('inner_content')

<div x-data="{
    loading:false,
    user:{{ json_encode(Auth::user()) }},
    makUrl(url,page){
        var param='';
        const queryString = window.location.search;
        const urlParams = new URLSearchParams(queryString);
        if(page){
            param += param.length>0 ? '&' : '?';
            param += 'page=' + encodeURIComponent(page);
        }else{
            if(urlParams.has('page')){
                const page = urlParams.get('page');
                param += param.length>0 ? '&' : '?';
                param += 'page=' + encodeURIComponent(page);
            }
        }
        if(urlParams.has('is_close_to_expired_date_or_expired')){
            param += param.length>0 ? '&' : '?';
            param += 'is_close_to_expired_date_or_expired' + encodeURIComponent(urlParams.get('is_close_to_expired_date_or_expired'));
        }
        if(urlParams.has('name')){
            param += param.length>0 ? '&' : '?';
            param += 'name=' + encodeURIComponent(urlParams.get('name'));
        }
        if(urlParams.has('filter_within_plan')){
            param += param.length>0 ? '&' : '?';
            param += 'filter_within_plan=' + encodeURIComponent(urlParams.get('filter_within_plan'));
        }
        if(urlParams.has('filter_native')){
            param += param.length>0 ? '&' : '?';
            param += 'filter_native=' + encodeURIComponent(urlParams.get('filter_native'));
        }
        if(urlParams.has('county_id')){
            param += param.length>0 ? '&' : '?';
            param += 'county_id=' + encodeURIComponent(urlParams.get('county_id'));
        }
        if(urlParams.has('rank')){
            param += param.length>0 ? '&' : '?';
            param += 'rank=' + encodeURIComponent(urlParams.get('rank'));
        }
        if(urlParams.has('Year')){
            param += param.length>0 ? '&' : '?';
            param += 'Year=' + encodeURIComponent(urlParams.get('Year'));
        }
        if(urlParams.has('pass')){
            param += param.length>0 ? '&' : '?';
            param += 'pass=' + encodeURIComponent(urlParams.get('pass'));
        }
        return url+param;
    },
    getData(page){
        location.href = this.makUrl('{{ request()->url() }}',page);
    },
    pagination: {{ json_encode($pagination) }},
 }" class="flex flex-col items-end justify-start w-full p-4 space-y-4 text-content text-mainAdminTextGrayDark">

 <div id="js-token" class="hidden">{{ csrf_token() }}</div>

    <div class="w-full">
        <table class="w-full bg-white border shadow-lg text-mainAdminTextGrayDark">
            <thead>
                <tr class="border-b even:bg-mainLight">
                    <th class="p-2 font-normal text-left border-r last:border-r-0">縣市</th>
                    <th class="p-2 font-normal text-left border-r last:border-r-0">社區名稱</th>
                    <th class="p-2 font-normal text-left border-r last:border-r-0">建立時間</th>
                    <th class="p-2 font-normal text-left border-r last:border-r-0">更新時間</th>
                    <th class="p-2 font-normal text-left border-r last:border-r-0">檔案</th>
                </tr>
            </thead>
            <tbody class="text-content text-mainAdminTextGrayDark">
                @foreach($list as $data)
                    <tr class="border-b last:border-b-0 odd:bg-white even:bg-mainLight">
                        <td class="p-2 text-center border-r last:border-r-0">{{$data->county->name}}</td>
                        <td class="p-2 text-center border-r last:border-r-0">{{$data->name}}</td>
                        <td class="p-2 text-center border-r last:border-r-0">{{$data->created_at}}</td>
                        <td class="p-2 text-center border-r last:border-r-0">{{$data->updated_at}}</td>
                        <td class="p-2 text-center border-r last:border-r-0">
                            @foreach($data->files->sortByDesc('created_at')->take(2) as $file)
                                <div class="mb-1">
                                    <a href="{{ $file->path }}" target="_blank" class="text-blue-600 hover:underline text-l" style="color: blue">
                                        {{ $file->name }}
                                    </a>
                                </div>
                            @endforeach
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{-- 分頁容器 --}}
    <div id="pagination-container" class="mt-4">
        {!! $list->links() !!}
    </div>

    {{-- 頁數資訊 --}}
    <div id="page-info" class="text-center mt-2 text-sm text-gray-600">
        第 {{ $list->currentPage() }} 頁 / 共 {{ $list->lastPage() }} 頁（共 {{ $list->total() }} 筆）
    </div>

    </div>


@include('admin.layouts.partials.loadingMask')
@endsection

