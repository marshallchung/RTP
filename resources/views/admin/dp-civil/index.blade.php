@extends('admin.layouts.dashboard', [
'heading' => '防災士培訓 > 社團法人臺灣防災教育訓練學會',
'header_btn' => [
// '匯出', route('admin.dp-students.export', request()->input()),
'新增', route('admin.dp-civil.create')
],
'breadcrumbs' => ['社團法人臺灣防災教育訓練學會']
])

@section('title', '社團法人臺灣防災教育訓練學會')

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
        return url+param;
    },
    getData(page){
        location.href = this.makUrl('{{ route('admin.news.index') }}',page);
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
    items: {{ json_encode($data->items()) }},
 }" class="flex flex-col items-end justify-start w-full p-4 space-y-4">
    <div x-show="loading" class="absolute z-[1100] inset-0 bg-black/30 hidden justify-center items-center"
        :class="{'flex':loading,'hidden':!loading}">
        <div class="relative w-full h-full">
            <img src="/image/loading.svg" class="w-16 h-16 absolute left-1/2 top-[calc(50vh-2rem)]" alt="">
        </div>
    </div>
    {{-- @include('admin.dp-civil.partials.filter') --}}
    <table class="w-full bg-white border shadow-lg text-mainAdminTextGrayDark">
        <thead>
            <tr class="border-b bg-mainLight">
                <th class="p-2 font-normal text-left border-r w-14 last:border-r-0">上線</th>
                <th class="p-2 font-normal text-left border-r last:border-r-0">名稱</th>
                <th class="p-2 font-normal text-left border-r last:border-r-0">連絡電話</th>
                <th class="p-2 font-normal text-left border-r last:border-r-0">機構地址</th>
                <th class="p-2 font-normal text-left border-r last:border-r-0">官方網址</th>
            </tr>
        </thead>
        <tbody class="text-content text-mainAdminTextGrayDark">
            <template x-for="(data_item, index) in items">
                <tr class="border-b last:border-b-0 odd:bg-white even:bg-mainLight">
                    <td class="p-2 text-center border-r last:border-r-0">
                        @if ($data_item->active)
                        <span class="js-toggle-active-btn label label-success"
                            data-route="{{ route('admin.dp-civil.update', $data_item->id) }}">是</span>
                        @else
                        <span class="js-toggle-active-btn label"
                            data-route="{{ route('admin.dp-civil.update', $data_item->id) }}">否</span>
                        @endif
                    </td>
                    <td class="p-2 border-r last:border-r-0">{!! Html::linkroute('admin.dp-civil.edit',
                        $data_item->name,
                        $data_item->id) !!}</td>
                    <td class="p-2 border-r last:border-r-0">{{ $data_item->phone }}</td>
                    <td class="p-2 border-r last:border-r-0">{{ $data_item->address }}</td>
                    <td class="p-2 border-r last:border-r-0">
                        @if($data_item->url)
                        <a href="{{ $data_item->url }}" target="_blank">{{ $data_item->url }}</a>
                        @endif
                    </td>
                </tr>
            </template>
            @foreach ($data as $data_item)
            <tr>
                <td class="p-2 text-center border-r last:border-r-0">
                    @if ($data_item->active)
                    <span class="js-toggle-active-btn label label-success"
                        data-route="{{ route('admin.dp-civil.update', $data_item->id) }}">是</span>
                    @else
                    <span class="js-toggle-active-btn label"
                        data-route="{{ route('admin.dp-civil.update', $data_item->id) }}">否</span>
                    @endif
                </td>
                <td class="p-2 border-r last:border-r-0">{!! Html::linkroute('admin.dp-civil.edit', $data_item->name,
                    $data_item->id) !!}</td>
                <td class="p-2 border-r last:border-r-0">{{ $data_item->phone }}</td>
                <td class="p-2 border-r last:border-r-0">{{ $data_item->address }}</td>
                <td class="p-2 border-r last:border-r-0">
                    @if($data_item->url)
                    <a href="{{ $data_item->url }}" target="_blank">{{ $data_item->url }}</a>
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <div class="">{!! $data->appends(request()->input())->render() !!}</div>
    <div id="js-token" class="hidden">{{ csrf_token() }}</div>
</div>
@endsection

@section('scripts')
<script src="{{ asset('scripts/generalIndex.js') }}"></script>
@endsection