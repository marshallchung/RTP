<?php
if (Auth::user()->hasPermission('DP-students-create')) {
    $headerButton = [
        '身份證字號查詢', route('admin.dp-students.inquire'),
		'依證書日期統計', route('admin.dp-students.certificate'),
        '依計畫名稱統計', route('admin.dp-students.statistics'),
        '匯入', route('admin.dp-students.import'),
        '匯出', route('admin.dp-students.export', request()->input()),
        '新增', route('admin.dp-students.create')
    ];
/*} elseif(Auth::user()->hasPermission('DEP-permissions') ) {  //For 輔導機構
    $headerButton = [
        '身份證字號查詢', route('admin.dp-students.inquire'),
        '依證書日期統計', route('admin.dp-students.certificate'),
        '依計畫名稱統計', route('admin.dp-students.statistics'),
        '匯出', route('admin.dp-students.export', request()->input())
    ];
} elseif(Auth::user()->hasPermission('County-permissions')){
    $headerButton = [
        '身份證字號查詢', route('admin.dp-students.inquire'),
        '依證書日期統計', route('admin.dp-students.certificate'),
        '依計畫名稱統計', route('admin.dp-students.statistics'),
        '匯出', route('admin.dp-students.export', request()->input())
    ];*/
} else{
    $headerButton = [
        '身份證字號查詢', route('admin.dp-students.inquire'),
        '依證書日期統計', route('admin.dp-students.certificate'),
        '依計畫名稱統計', route('admin.dp-students.statistics'),
        '匯出', route('admin.dp-students.export', request()->input())
    ];
}
?>
@extends('admin.layouts.dashboard', [
'heading' => '防災士培訓 > 防災士資料管理',
'header_btn' => $headerButton,
'breadcrumbs' => ['防災士資料管理']
])

@section('title', '防災士資料管理')

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
        if(urlParams.has('certificate')){
            param += param.length>0 ? '&' : '?';
            param += 'certificate' + encodeURIComponent(urlParams.get('certificate'));
        }
        if(urlParams.has('name')){
            param += param.length>0 ? '&' : '?';
            param += 'name=' + encodeURIComponent(urlParams.get('name'));
        }
        if(urlParams.has('gender')){
            param += param.length>0 ? '&' : '?';
            param += 'gender=' + encodeURIComponent(urlParams.get('gender'));
        }
        if(urlParams.has('unit_first_course')){
            param += param.length>0 ? '&' : '?';
            param += 'unit_first_course=' + encodeURIComponent(urlParams.get('unit_first_course'));
        }
        if(urlParams.has('county_id')){
            param += param.length>0 ? '&' : '?';
            param += 'county_id=' + encodeURIComponent(urlParams.get('county_id'));
        }
        if(urlParams.has('start_at')){
            param += param.length>0 ? '&' : '?';
            param += 'start_at=' + encodeURIComponent(urlParams.get('start_at'));
        }
        if(urlParams.has('end_at')){
            param += param.length>0 ? '&' : '?';
            param += 'end_at=' + encodeURIComponent(urlParams.get('end_at'));
        }
        if(urlParams.has('pass')){
            param += param.length>0 ? '&' : '?';
            param += 'pass=' + encodeURIComponent(urlParams.get('pass'));
        }
        if(urlParams.has('checkbox_willingness')){
            param += param.length>0 ? '&' : '?';
            param += 'checkbox_willingness=' + encodeURIComponent(urlParams.get('checkbox_willingness'));
        }
        return url+param;
    },
    getData(page){
        location.href = this.makUrl('{{ route('admin.dp-students.index') }}',page);
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
                    This.items=json;
                    This.preDragOrder=json;
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
    updateWillingness(e){
        var This=this;
        var activeButton = e.target;
        var url=this.makUrl(activeButton.dataset.route);
        var token='{{ csrf_token() }}';
        var willingness=activeButton.innerText=='是'?'0':'1';
        var data = '_method=PUT&_token=' + token+ '&willingness=' + willingness + '&response_json=1';
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
    editRoute:'{{ route('admin.dp-students.edit',99999999) }}',
    destroyRoute:'{{ route('admin.dp-students.destroy',99999999) }}',
    updateRoute:'{{ route('admin.dp-students.update', 99999999) }}',
 }" class="flex flex-col items-end justify-start w-full p-4 space-y-4 text-content text-mainAdminTextGrayDark">
    <div class="flex flex-row flex-wrap items-center justify-start w-full text-sm" id="search-form">
        <form method="GET" action="{{ request()->url() }}" accept-charset="UTF-8"
            class="flex flex-row flex-wrap items-center justify-between w-full">
            <label class="flex flex-row items-center justify-start m-1 space-x-2">
                <input type="checkbox" name="checkbox_willingness" value='1' {{
                    request('checkbox_willingness')?'checked':'' }}
                    class="bg-white border-gray-300 rounded text-mainCyanDark">
                <span>有意願參加防災工作</span>
            </label>
            <input
                class="h-10 px-4 m-1 text-sm border-gray-300 rounded-md shadow-sm focus:border-cyan-300 focus:ring focus:ring-cyan-200 focus:ring-opacity-50 placeholder:text-sm placeholder:text-mainTextGray"
                placeholder="證書編號" name="certificate" type="text" value="{{ request('certificate') }}">
            <input
                class="h-10 px-4 m-1 text-sm border-gray-300 rounded-md shadow-sm focus:border-cyan-300 focus:ring focus:ring-cyan-200 focus:ring-opacity-50 placeholder:text-sm placeholder:text-mainTextGray"
                placeholder="姓名" name="name" type="text" value="{{ request('name') }}">
            <select
                class="h-10 px-4 m-1 text-sm border-gray-300 rounded-md shadow-sm focus:border-cyan-300 focus:ring focus:ring-cyan-200 focus:ring-opacity-50 placeholder:text-sm placeholder:text-mainTextGray"
                name="gender">
                @foreach ($genderOptions as $gender_id=>$gender)
                <option value="{{ $gender_id }}" {{ request('gender')===$gender_id ?'selected':'' }}>{{ $gender }}
                </option>
                @endforeach
            </select>
            <input
                class="h-10 px-4 m-1 text-sm border-gray-300 rounded-md shadow-sm focus:border-cyan-300 focus:ring focus:ring-cyan-200 focus:ring-opacity-50 placeholder:text-sm placeholder:text-mainTextGray"
                placeholder="培訓單位" name="unit_first_course" type="text" value="{{ request('unit_first_course') }}">
            <select
                class="h-10 px-4 m-1 text-sm border-gray-300 rounded-md shadow-sm focus:border-cyan-300 focus:ring focus:ring-cyan-200 focus:ring-opacity-50 placeholder:text-sm placeholder:text-mainTextGray"
                name="county_id">
                @foreach ($counties as $county_id=>$county)
                <option value="{{ $county_id }}" {{ intval(request('county_id'))===$county_id ?'selected':'' }}>{{
                    $county }}
                </option>
                @endforeach
            </select>
            <div class="flex flex-row items-center justify-start" id="datepicker-range">
                <input
                    class="h-10 px-4 m-1 text-sm border-gray-300 rounded-md shadow-sm focus:border-cyan-300 focus:ring focus:ring-cyan-200 focus:ring-opacity-50 placeholder:text-sm placeholder:text-mainTextGray"
                    placeholder="授證查詢
            起始日期" name="start_at" type="date">
                <span class="m-1">～</span>
                <input
                    class="h-10 px-4 m-1 text-sm border-gray-300 rounded-md shadow-sm focus:border-cyan-300 focus:ring focus:ring-cyan-200 focus:ring-opacity-50 placeholder:text-sm placeholder:text-mainTextGray"
                    placeholder="授證查詢
            結束日期" name="end_at" type="date">
            </div>
            <div class="flex flex-row items-center justify-start">
                <select
                    class="h-10 px-4 m-1 text-sm border-gray-300 rounded-md shadow-sm focus:border-cyan-300 focus:ring focus:ring-cyan-200 focus:ring-opacity-50 placeholder:text-sm placeholder:text-mainTextGray"
                    name="pass">
                    @foreach ($passOptions as $pass_id=>$pass)
                    <option value="{{ $pass_id }}" {{ strval(request('pass'))===strval($pass_id) ?'selected':'' }}>{{
                        $pass }}
                    </option>
                    @endforeach
                </select>
                <button type="submit"
                    class="flex items-center justify-center w-20 h-10 m-1 text-white bg-mainCyanDark">搜尋</button>
                <a href="{{ request()->url() }}"
                    class="flex items-center justify-center w-20 h-10 m-1 border border-gray-200 bg-gray-50 hover:bg-gray-100 text-mainAdminTextGrayDark">清空</a>
            </div>
        </form>
    </div>
    <div>合格：{{ $passCount[1] ?? 0 }}，不合格：{{ $passCount[0] ?? 0 }}</div>
    <div class="w-full max-w-full overflow-auto border shadow-lg">
        <table class="w-full bg-white text-mainAdminTextGrayDark">
            <thead>
                <tr class="border-b bg-mainLight">
                    @if (Auth::user()->hasPermission('admin-permissions')||
                    Auth::user()->hasPermission('NFA-permissions')||
                    Auth::user()->hasPermission('DEP-permissions'))
                    <th class="p-2 font-normal text-left border-r w-14 last:border-r-0">刪除</th>
                    @endif
                    <th class="p-2 font-normal text-left border-r w-14 last:border-r-0">上線</th>
                    <th class="p-2 font-normal text-left border-r w-14 last:border-r-0">意願</th>
                    <th class="p-2 font-normal text-left border-r last:border-r-0">證書編號</th>
                    <th class="p-2 font-normal text-left border-r last:border-r-0">姓名</th>
                    <th class="p-2 font-normal text-left border-r last:border-r-0">性別</th>
                    <th class="p-2 font-normal text-left border-r last:border-r-0">縣市</th>
                    <th class="p-2 font-normal text-left border-r last:border-r-0">連絡電話</th>
                    <th class="p-2 font-normal text-left border-r last:border-r-0">E-mail</th>
                    <th class="p-2 font-normal text-left border-r last:border-r-0">培訓單位</th>
                    <th class="p-2 font-normal text-left border-r last:border-r-0">授證日期</th>
                    <th class="p-2 font-normal text-left border-r last:border-r-0">認證合格</th>
                </tr>
            </thead>
            <tbody class="text-content text-mainAdminTextGrayDark">
                <template x-for="(data_item, index) in items">
                    <tr class="border-b last:border-b-0 even:bg-white odd:bg-mainLight">
                        @if (Auth::user()->hasPermission('admin-permissions')||
                        Auth::user()->hasPermission('NFA-permissions')||
                        Auth::user()->hasPermission('DEP-permissions'))
                        <td class="p-2 text-center border-r last:border-r-0">
                            <button type="button" @click="deleteItem(destroyRoute.replace('99999999',data_item.id))"
                                class="flex items-center justify-center w-10 h-8 text-sm text-white bg-orange-600 rounded cursor-pointer hover:bg-orange-500">
                                <i class="w-2.5 h-2.5 i-fa6-solid-trash" aria-hidden="true"></i>
                            </button>
                        </td>
                        @endif
                        <td class="p-2 text-center border-r last:border-r-0">
                            <div class="flex items-center justify-center w-full h-full">
                                @if (Auth::user()->hasPermission('admin-permissions')||
                                Auth::user()->hasPermission('NFA-permissions')||
                                Auth::user()->hasPermission('DEP-permissions'))
                                <button @click="updateActive" type="button"
                                    class="flex items-center justify-center w-5 h-5 text-xs text-white rounded"
                                    :class="{'bg-green-500':data_item.active,'bg-black/30':!data_item.active}"
                                    x-bind:data-route="updateRoute.replace('99999999',data_item.id)"
                                    x-text="data_item.active?'是':'否'"></button>
                                @else
                                <span class="flex items-center justify-center w-5 h-5 text-xs text-white rounded"
                                    :class="{'bg-green-500':data_item.active,'bg-black/30':!data_item.active}"
                                    x-bind:data-role="{{ Auth::user()->origin_role }}"
                                    x-text="data_item.active?'是':'否'"></span>
                                @endif
                            </div>
                        </td>
                        <td class="p-2 text-center border-r last:border-r-0">
                            <div class="flex items-center justify-center w-full h-full">
                                @if (Auth::user()->hasPermission('admin-permissions')||
                                Auth::user()->hasPermission('NFA-permissions')||
                                Auth::user()->hasPermission('DEP-permissions'))
                                <button @click="updateWillingness" type="button"
                                    class="flex items-center justify-center w-5 h-5 text-xs text-white rounded"
                                    :class="{'bg-green-500':data_item.willingness,'bg-black/30':!data_item.willingness}"
                                    x-bind:data-route="updateRoute.replace('99999999',data_item.id)"
                                    x-text="data_item.willingness?'是':'否'"></button>
                                @else
                                <span class="flex items-center justify-center w-5 h-5 text-xs text-white rounded"
                                    :class="{'bg-green-500':data_item.willingness,'bg-black/30':!data_item.willingness}"
                                    x-bind:data-role="{{ Auth::user()->origin_role }}"
                                    x-text="data_item.willingness?'是':'否'"></span>
                                @endif
                            </div>
                        </td>
                        <td class="p-2 border-r last:border-r-0" x-text="data_item.certificate"></td>
                        <td class="p-2 border-r last:border-r-0">
                            @if (Auth::user()->hasPermission('admin-permissions')||
                            Auth::user()->hasPermission('NFA-permissions')||
                            Auth::user()->hasPermission('DEP-permissions'))
                            <a :href="editRoute.replace('99999999',data_item.id)" class="text-mainBlueDark"
                                x-text="data_item.name"></a>
                            @else
                            <a :href="'/admin/dp-students/show/' + data_item.id" x-text="data_item.name"
                                class="text-mainBlueDark"></a>
                            @endif
                        </td>
                        <td class="p-2 border-r last:border-r-0" x-text="data_item.gender"></td>
                        <td class="p-2 border-r last:border-r-0" x-text="data_item.county?data_item.county.name:''">
                        </td>
                        <td class="p-2 border-r last:border-r-0" x-text="data_item.mobile"></td>
                        <td class="p-2 border-r last:border-r-0" x-text="data_item.email"></td>
                        <td class="p-2 border-r last:border-r-0" x-text="data_item.unit_first_course"></td>
                        <td class="p-2 border-r last:border-r-0"
                            x-text="data_item.date_first_finish?data_item.date_first_finish.substring(0,10):''">
                        </td>
                        <td class="p-2 border-r last:border-r-0" x-text="data_item.pass?'合格':'不合格'"></td>
                    </tr>
                </template>
            </tbody>
        </table>
    </div>
    <div class="pull-right">{!! $data->appends(request()->input())->render() !!}</div>
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