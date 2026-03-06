@extends('admin.layouts.dashboard', [
'heading' => '防災士培訓 > 培訓課程管理',
'header_btn' => ['新增', route('admin.dp-courses.create')],
'breadcrumbs' => ['培訓課程管理']
])

@section('title', '培訓課程管理')

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
        location.href = this.makUrl('{{ route('admin.dp-courses.index') }}',page);
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
    editRoute:'{{ route('admin.dp-courses.edit',99999999) }}',
    updateRoute:'{{ route('admin.dp-courses.update', 99999999) }}',
 }" class="flex flex-col items-end justify-start w-full p-4 space-y-4">
    <table class="w-full bg-white border shadow-lg text-mainAdminTextGrayDark">
        <thead>
            <tr class="border-b bg-mainLight">
                <th class="p-2 font-normal text-left border-r w-14 last:border-r-0">上線</th>
                <th class="p-2 font-normal text-left border-r last:border-r-0">管理單位</th>
                <th class="p-2 font-normal text-left border-r last:border-r-0">主辦單位</th>
                <th class="p-2 font-normal text-left border-r last:border-r-0">培訓名稱</th>
                <th class="p-2 font-normal text-left border-r last:border-r-0">聯絡人</th>
                <th class="p-2 font-normal text-left border-r last:border-r-0">連絡電話</th>
                <th class="p-2 font-normal text-left border-r last:border-r-0">E-mail</th>
                <th class="p-2 font-normal text-left border-r w-44 last:border-r-0">日期</th>
            </tr>
        </thead>
        <tbody class="text-content text-mainAdminTextGrayDark">
            <template x-for="(data_item, index) in items">
                <tr class="border-b last:border-b-0 odd:bg-white even:bg-mainLight">
                    <td class="p-2 text-center border-r last:border-r-0">
                        @if (Auth::user()->hasPermission('admin-permissions')||
                        Auth::user()->hasPermission('NFA-permissions')||
                        Auth::user()->hasPermission('DEP-permissions'))
                        <div class="flex items-center justify-center w-full h-full">
                            <button @click="updateActive" type="button"
                                class="flex items-center justify-center w-5 h-5 text-xs text-white rounded"
                                :class="{'bg-green-500':data_item.active,'bg-black/30':!data_item.active}"
                                x-bind:data-route="updateRoute.replace('99999999',data_item.id)"
                                x-text="data_item.active?'是':'否'"></button>
                            @else
                            <span class="flex items-center justify-center w-5 h-5 text-xs text-white rounded"
                                :class="{'bg-green-500':data_item.active,'bg-black/30':!data_item.active}"
                                x-text="data_item.active?'是':'否'"></span>
                            @endif
                        </div>
                    </td>
                    <td class="p-2 border-r last:border-r-0" x-text="data_item.county?data_item.county.name:''">
                    </td>
                    <td class="p-2 border-r last:border-r-0"
                        x-text="data_item.organizer=='消防署'?'內政部消防署':(data_item.organizer.length==3?data_item.organizer+'政府':data_item.organizer)">
                    </td>
                    <td class="p-2 border-r last:border-r-0">
                        <a :href="editRoute.replace('99999999',data_item.id)" class="text-mainBlueDark"
                            x-text="data_item.name"></a>
                    </td>
                    <td class="p-2 border-r last:border-r-0" x-text="data_item.contact_person">
                    </td>
                    <td class="p-2 border-r last:border-r-0" x-text="data_item.phone">
                    </td>
                    <td class="p-2 border-r last:border-r-0" x-text="data_item.email">
                    </td>
                    <td class="p-2 border-r last:border-r-0"
                        x-text="(new Date(data_item.created_at)).toLocaleString('chinese',{hour12:false})">
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
@endsection