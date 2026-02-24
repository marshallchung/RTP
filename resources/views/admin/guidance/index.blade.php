@extends('admin.layouts.dashboard', [
'heading' => '操作教學說明文件',
'breadcrumbs' => [
['操作教學說明文件', route('admin.guidance.index')],
'所有'
]
])

@section('title', '所有')

<?php
    $canCreate = Auth::user()->hasPermission('create-guidance');
?>

@section('inner_content')
<div x-data="{
    loading:false,
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
        return url+param;
    },
    getData(page){
        location.href = this.makUrl('{{ route('admin.guidance.index') }}',page);
    },
    updateActive(e){
        var This=this;
        var activeButton = e.target;
        var url=this.makUrl(activeButton.dataset.route);
        var token='{{ csrf_token() }}';
        var active=activeButton.innerText=='是'?'0':'1';
        var data = '_method=PUT&_token=' + token+ '&active=' + active + '&response_json=1';
        this.loading=true;
        fetch(url,{
            method:'POST',
            body:data,
            headers: {
                'X-CSRF-TOKEN': token,
                'X-Requested-With':'XMLHttpRequest',
                'Accept': '*/*',
                'Content-Type': 'application/x-www-form-urlencoded; chartset=UTF-8',
            },
        })
        .then((response) => {
            if(response.status===200){
                return response.json();
            }
        })
        .then(function (json) {
            This.items=json;
        })
        .catch(function(error) {
            if (error.status == 429) {
                alert('嘗試登入次數過多，請稍後再試。');
            }else if (error.status == 419) {
                alert('頁面逾期，請重新輸入');
                location.reload();
            }else{
                alert('伺服器錯誤: ' + error.message);
            }
        })
        .finally(() => {
            this.loading=false;
        });
    },
    items: {{ $data->makeHidden('content')->toJson(JSON_PRETTY_PRINT) }},
    editRoute:'{{ route('admin.guidance.'. $routeName,99999999) }}',
    updateRoute:'{{ route('admin.guidance.update', 99999999) }}',
 }" class="flex flex-col items-end justify-start w-full p-4 space-y-4">
    <table class="w-full bg-white border shadow-lg text-mainAdminTextGrayDark">
        <thead>
            <tr class="border-b bg-mainLight">
                @if (Auth::user()->origin_role < 4) <th class="p-2 font-normal text-left border-r w-14 last:border-r-0">
                    上線</th>
                    @endif
                    <th class="p-2 font-normal text-left border-r last:border-r-0">標題</th>
                    @if ($canCreate)
                    {{-- <th class="p-2 font-normal text-left border-r last:border-r-0">功能表</th> --}}
                    @endif
            </tr>
        </thead>
        <tbody class="text-content text-mainAdminTextGrayDark">
            <template x-for="(data_item, index) in items">
                <tr class="border-b last:border-b-0 odd:bg-white even:bg-mainLight">
                    @if (Auth::user()->origin_role < 4) <td class="p-2 text-center border-r last:border-r-0">
                        <div class="flex items-center justify-center w-full h-full">
                            <button @click="updateActive" type="button"
                                class="flex items-center justify-center w-5 h-5 text-xs text-white rounded"
                                :class="{'bg-green-500':data_item.active,'bg-black/30':!data_item.active}"
                                x-bind:data-route="updateRoute.replace('99999999',data_item.id)"
                                x-text="data_item.active?'是':'否'"></button>
                        </div>
                        </td>
                        @endif
                        <td class="p-2 border-r last:border-r-0">
                            <a :href="editRoute.replace('99999999',data_item.id)" class="text-mainBlueDark"
                                x-text="data_item.title"></a>
                        </td>
                </tr>
            </template>
        </tbody>
    </table>
    <div class="">{!! $data->render() !!}</div>
    <div id="js-token" class="hidden">{{ csrf_token() }}</div>
    <div x-show="loading" class="absolute z-[1100] inset-0 bg-black/30 hidden justify-center items-center"
        :class="{'flex':loading,'hidden':!loading}">
        <div class="relative w-full h-full">
            <img src="/image/loading.svg" class="w-16 h-16 absolute left-1/2 top-[calc(50vh-2rem)]" alt="">
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="{{ asset('scripts/generalIndex.js') }}"></script>
@endsection
