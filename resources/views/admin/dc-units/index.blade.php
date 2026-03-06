@extends('admin.layouts.dashboard', [
'heading' => '推動韌性社區 > 查詢與管理韌性社區資料',
'header_btn' => [
'社區標章統計表' , route('admin.dc-units.report'),
'匯入', route('admin.dc-units.import'),
'匯出統計表', route('admin.dc-units.exportRe', request()->all()),
'匯出', route('admin.dc-units.export', request()->all()),
'匯出社區帳號', route('admin.dc-units.export-dc-user', request()->all()),
'新增', route('admin.dc-units.create')
],
'breadcrumbs' => ['查詢與管理韌性社區資料']
])

@section('title', '查詢與管理韌性社區資料')

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
    deleteItem(url){
        if(confirm('確定要刪除嗎？')){
            var This=this;
            url=this.makUrl(url);
            var token='{{ csrf_token() }}';
            var data = '_method=DELETE&_token=' + token + '&response_json=1';
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
                }else{
                    alert('伺服器錯誤: ' + response.status);
                    return false;
                }
            })
            .then(function (json) {
                if(json!==false){
                    This.items=json.data;
                    This.pagination=json.pagination;
                    This.withinPlanCount=json.withinPlanCount;

                }
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
        }
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
            }else{
                alert('伺服器錯誤: ' + response.status);
                return false;
            }
        })
        .then(function (json) {
            if(json!==false){
                This.items=json.data;
                This.pagination=json.pagination;
                This.withinPlanCount=json.withinPlanCount;
            }
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
    updateNative(e){
        var This=this;
        var activeButton = e.target;
        var url=this.makUrl(activeButton.dataset.route);
        var token='{{ csrf_token() }}';
        var native=activeButton.innerText=='是'?'0':'1';
        var data = '_method=PUT&_token=' + token+ '&native=' + native + '&response_json=1';
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
            }else{
                alert('伺服器錯誤: ' + response.status);
                return false;
            }
        })
        .then(function (json) {
            if(json!==false){
                This.items=json.data;
                This.pagination=json.pagination;
                This.withinPlanCount=json.withinPlanCount;
            }
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
    updateWithinPlan(e){
        var This=this;
        var activeButton = e.target;
        var url=this.makUrl(activeButton.dataset.route);
        var token='{{ csrf_token() }}';
        var within_plan=activeButton.innerText=='是'?'0':'1';
        var data = '_method=PUT&_token=' + token+ '&within_plan=' + within_plan + '&response_json=1';
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
            }else{
                alert('伺服器錯誤: ' + response.status);
                return false;
            }
        })
        .then(function (json) {
            if(json!==false){
                This.items=json.data;
                This.pagination=json.pagination;
                This.withinPlanCount=json.withinPlanCount;
            }
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
    items: {{ json_encode($data) }},
    pagination: {{ json_encode($pagination) }},
    withinPlanCount: {{ $withinPlanCount }},
    editRankRoute:'{{ route('admin.dc-units.edit-rank', 99999999) }}',
    editUserRoute:'{{ route('admin.dc-units.create-dc-user', 99999999) }}',
    editRoute:'{{ route('admin.dc-units.edit',99999999) }}',
    destroyRoute:'{{ route('admin.dc-units.destroy',99999999) }}',
    updateRoute:'{{ route('admin.dc-units.update', 99999999) }}',
 }" class="flex flex-col items-end justify-start w-full p-4 space-y-4 text-content text-mainAdminTextGrayDark">
    <div class="flex flex-row flex-wrap items-center justify-between w-full mb-1 ml-auto mr-auto" id="search-form">
        <form method="GET" action="{{ request()->url() }}" accept-charset="UTF-8"
            class="flex flex-row flex-wrap items-center justify-end w-full">
            <label class="flex flex-row items-center justify-start m-1 space-x-2">
                <input name="is_close_to_expired_date_or_expired" type="checkbox" value="1" {{
                    request('is_close_to_expired_date_or_expired')?'checked':'' }}
                    class="bg-white border-gray-300 rounded text-mainCyanDark">
                <span>星等即將到期(前11個月)</span>
            </label>
            <label class="flex flex-row items-center justify-start m-1 space-x-2">
                <input type="checkbox" name="filter_within_plan" value="1" {{ request('filter_within_plan')?'checked':''
                    }} class="bg-white border-gray-300 rounded text-mainCyanDark">
                <span>計畫內</span>
                <span class="px-2 py-1 ml-2 bg-white rounded text-center min-w-[4rem] text-mainAdminTextGray"
                    x-text="withinPlanCount"></span>
            </label>

            <label class="flex flex-row items-center justify-start m-1 space-x-2">
                <input type="checkbox" name="filter_native" value="1" {{ request('filter_native')?'checked':'' }}
                    class="bg-white border-gray-300 rounded text-mainCyanDark">
                <span>原民地區</span>
            </label>
            <select
                class="h-10 px-4 m-1 text-sm border-gray-300 rounded-md shadow-sm w-28 focus:border-cyan-300 focus:ring focus:ring-cyan-200 focus:ring-opacity-50 placeholder:text-sm placeholder:text-mainTextGray"
                name="county_id">
                @foreach ($counties as $county_id=>$county_name)
                <option value="{{ $county_id }}" {{ strval($county_id)===request('county_id')?'selected':''}}>
                    {{ $county_name }}</option>
                @endforeach
            </select>
            <input
                class="h-10 px-4 m-1 text-sm border-gray-300 rounded-md shadow-sm focus:border-cyan-300 focus:ring focus:ring-cyan-200 focus:ring-opacity-50 placeholder:text-sm placeholder:text-mainTextGray"
                placeholder="社區名稱" name="name" type="text" value="{{ request('name') }}">
            <select
                class="h-10 px-4 m-1 text-sm border-gray-300 rounded-md shadow-sm w-28 focus:border-cyan-300 focus:ring focus:ring-cyan-200 focus:ring-opacity-50 placeholder:text-sm placeholder:text-mainTextGray"
                name="rank">
                @foreach ($ranks as $rank_id=>$rank_name)
                <option value="{{ $rank_id }}" {{ strval($rank_id)===request('rank')?'selected':''}}>
                    {{ $rank_name }}</option>
                @endforeach
            </select>
            <select name="Year"
                class="h-10 px-4 m-1 text-sm border-gray-300 rounded-md shadow-sm w-28 focus:border-cyan-300 focus:ring focus:ring-cyan-200 focus:ring-opacity-50 placeholder:text-sm placeholder:text-mainTextGray">
                　 <option value="" {{ ""===request('Year')?'selected':''}}>效期</option>
                　 <option value="1" {{ "1"===request('Year')?'selected':''}}>效期內</option>
                <option value="0" {{ "0"===request('Year')?'selected':''}}>已過期</option>
            </select>
            <div class="flex flex-row items-center justify-start">
                <select name="pass"
                    class="h-10 px-4 m-1 text-sm border-gray-300 rounded-md shadow-sm w-36 focus:border-cyan-300 focus:ring focus:ring-cyan-200 focus:ring-opacity-50 placeholder:text-sm placeholder:text-mainTextGray">
                    　 <option value="" {{ ""===request('pass')?'selected':''}}>審查狀態</option>
                    　 <option value="1" {{ "1"===request('pass')?'selected':''}}>通過</option>
                    <option value="0" {{ "0"===request('pass')?'selected':''}}>未通過</option>
                </select>
                <button type="submit"
                    class="flex items-center justify-center w-20 h-10 text-white bg-mainCyanDark">搜尋</button>
                <a href="{{ request()->url() }}"
                    class="flex items-center justify-center w-20 h-10 bg-gray-100 border border-gray-300 cursor-pointer text-mainAdminTextGrayDark hover:bg-gray-50 ">清空</a>
            </div>
        </form>
    </div>
    <div class="flex flex-row justify-start w-full">
        未審查：{{ $rankCount['未審查'] ?? 0 }}，
        1星：{{ $rankCount['一星'] ?? 0 }}，
        二星：{{ $rankCount['二星'] ?? 0 }}，
        三星：{{ $rankCount['三星'] ?? 0 }}，
        計畫內：{{ $withinPlanCount ?? 0 }}，
        原民地區：{{ $nativeCount ?? 0 }}，
        展延狀態：{{ $dateExtensionCount ?? 0 }}，
        逾期狀態：{{ $expireCount ?? 0 }}
    </div>
    <div class="w-full">
        <table class="w-full bg-white border shadow-lg text-mainAdminTextGrayDark">
            <thead>
                <tr class="border-b bg-mainLight">
                    @if(!auth()->user()->type || auth()->user()->type == 'civil')
                    <th class="p-2 font-normal text-left border-r last:border-r-0">刪除</th>
                    @endif
                    <th class="p-2 font-normal text-left border-r last:border-r-0">上線</th>
                    <th class="p-2 font-normal text-left border-r last:border-r-0">計畫內</th>
                    <th class="p-2 font-normal text-left border-r last:border-r-0">原民地區</th>
                    <th class="p-2 font-normal text-left border-r last:border-r-0">所在縣市</th>
                    <th class="p-2 font-normal text-left border-r last:border-r-0">社區名稱</th>
                    <th class="p-2 font-normal text-left border-r last:border-r-0">聯絡人姓名</th>
                    <th class="w-40 p-2 font-normal text-left border-r last:border-r-0">聯絡電話</th>
                    <th class="p-2 font-normal text-left border-r last:border-r-0">防災士姓名</th>
                    <th class="p-2 font-normal text-left border-r last:border-r-0">防災士電話</th>
                    <th class="p-2 font-normal text-left border-r last:border-r-0">星等</th>
                    <th class="p-2 font-normal text-left border-r last:border-r-0">帳號</th>
                </tr>
            </thead>
            <tbody class="text-content text-mainAdminTextGrayDark">
                <template x-for="(data_item, index) in items">
                    <tr class="border-b last:border-b-0 odd:bg-white even:bg-mainLight">
                        <template x-if="!user.type || user.type==='civil'">
                            <td class="p-2 text-center border-r last:border-r-0">
                                <button type="button" @click="deleteItem(destroyRoute.replace('99999999',data_item.id))"
                                    class="flex items-center justify-center w-10 h-8 text-sm text-white bg-orange-600 rounded cursor-pointer hover:bg-orange-500">
                                    <i class="w-2.5 h-2.5 i-fa6-solid-trash" aria-hidden="true"></i>
                                </button>
                            </td>
                        </template>
                        <td class="p-2 text-center border-r last:border-r-0">
                            <template x-if="!user.type || user.type!=='county'">
                                <div class="flex items-center justify-center w-full h-full">
                                    <button @click="updateActive" type="button"
                                        class="flex items-center justify-center w-5 h-5 text-xs text-white rounded"
                                        :class="{'bg-green-500':data_item.active,'bg-black/30':!data_item.active}"
                                        x-bind:data-route="updateRoute.replace('99999999',data_item.id)"
                                        x-text="data_item.active?'是':'否'"></button>
                                </div>
                            </template>
                        </td>
                        <td class="p-2 text-center border-r last:border-r-0">
                            <div class="flex items-center justify-center w-full h-full">
                                <button @click="updateWithinPlan" type="button"
                                    class="flex items-center justify-center w-5 h-5 text-xs text-white rounded"
                                    :class="{'bg-green-500':data_item.within_plan,'bg-black/30':!data_item.within_plan}"
                                    x-bind:data-route="updateRoute.replace('99999999',data_item.id)"
                                    x-text="data_item.within_plan?'是':'否'"></button>
                            </div>
                        </td>
                        <td class="p-2 text-center border-r last:border-r-0">
                            <div class="flex items-center justify-center w-full h-full">
                                <button @click="updateNative" type="button"
                                    class="flex items-center justify-center w-5 h-5 text-xs text-white rounded"
                                    :class="{'bg-green-500':data_item.native,'bg-black/30':!data_item.native}"
                                    x-bind:data-route="updateRoute.replace('99999999',data_item.id)"
                                    x-text="data_item.native?'是':'否'"></button>
                            </div>
                        </td>
                        <td class="p-2 border-r last:border-r-0" x-text="data_item.county?data_item.county.name:''">
                        </td>
                        <td class="p-2 border-r last:border-r-0">
                            <a :href="editRoute.replace('99999999',data_item.id)" class="text-mainBlueDark"
                                x-text="data_item.name"></a>
                        </td>
                        <td class="p-2 border-r last:border-r-0" x-text="data_item.manager"></td>
                        <td class="p-2 border-r last:border-r-0" x-text="data_item.phone"></td>
                        <td class="p-2 border-r last:border-r-0" x-text="data_item.dp_name"></td>
                        <td class="p-2 border-r last:border-r-0" x-text="data_item.dp_phone"></td>
                        <td class="p-2 border-r last:border-r-0">
                            <div class="flex flex-col items-center justify-center w-full h-full">
                                <span x-text="data_item.rank"></span>
                                <template x-if="data_item.rank_expired_date">
                                    <span class="text-sm text-mainAdminTextGrayDark"
                                        :class="{'text-rose-500':data_item.is_expired,'text-mainAdminTextGrayDark':!data_item.is_expired}"
                                        x-text="'(有效期限：' + data_item.rank_expired_date + ')'"></span>
                                </template>
                                <template x-if="user.origin_role==1 || user.origin_role==6 || user.origin_role==2">
                                    <a :href="editRankRoute.replace('99999999',data_item.id)"
                                        class="flex items-center justify-center w-20 text-sm text-white h-9 bg-mainCyanDark hover:bg-teal-400">編輯</a>
                                </template>
                            </div>
                        </td>
                        <td class="p-2 border-r last:border-r-0">
                            <template x-if="data_item.dcUser">
                                <div class="flex flex-col items-center justify-center space-y-2">
                                    <span x-text="data_item.dcUser.username"></span>
                                    <template x-if="data_item.hasPermOfUser">
                                        <a :href="editUserRoute.replace('99999999',data_item.id)"
                                            class="flex items-center justify-center w-20 text-sm text-white h-9 bg-mainCyanDark hover:bg-teal-400">編輯</a>
                                    </template>
                                </div>
                            </template>
                            <template x-if="!data_item.dcUser">
                                <div class="flex flex-col items-center justify-center space-y-2">
                                    <a :href="editUserRoute.replace('99999999',data_item.id)"
                                        class="flex items-center justify-center w-20 text-sm text-white h-9 bg-mainCyanDark hover:bg-teal-400">新增</a>
                                </div>
                            </template>
                        </td>
                    </tr>
                </template>
            </tbody>
        </table>
    </div>
    <div class="" x-html="pagination"></div>
    <div id="js-token" class="hidden">{{ csrf_token() }}</div>
    <div x-show="loading" class="absolute z-[1100] inset-0 bg-black/30 hidden justify-center items-center"
        :class="{'flex':loading,'hidden':!loading}">
        <div class="relative w-full h-full">
            <img src="/image/loading.svg" class="w-16 h-16 absolute left-1/2 top-[calc(50vh-2rem)]" alt="">
        </div>
    </div>
</div>
@endsection
