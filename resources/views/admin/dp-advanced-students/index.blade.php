<?php
if (Auth::user()->hasPermission('admin-permissions')||
            Auth::user()->hasPermission('NFA-permissions')||
            Auth::user()->hasPermission('DEP-permissions')) {
    $headerButton = [
        '身份證字號查詢', route('admin.dp-advanced-students.inquire'),
		'歷史紀錄', route('admin.dp-advanced-students.history'),
        '匯入上傳', route('admin.dp-advanced-students.import'),
        '匯出', route('admin.dp-advanced-students.export', request()->input())
    ];
}else{
    $headerButton = [
        '身份證字號查詢', route('admin.dp-advanced-students.inquire'),
        '匯出', route('admin.dp-advanced-students.export', request()->input())
    ];
}
?>
@extends('admin.layouts.dashboard', [
'heading' => '進階防災士培訓 > 進階防災士資料管理',
'header_btn' => $headerButton,
'breadcrumbs' => ['進階防災士資料管理']
])

@section('title', '進階防災士資料管理')

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
        if(urlParams.has('county_id')){
            param += param.length>0 ? '&' : '?';
            param += 'county_id=' + encodeURIComponent(urlParams.get('county_id'));
        }
        if(urlParams.has('training')){
            param += param.length>0 ? '&' : '?';
            param += 'training=' + encodeURIComponent(urlParams.get('training'));
        }
        if(urlParams.has('pass')){
            param += param.length>0 ? '&' : '?';
            param += 'pass=' + encodeURIComponent(urlParams.get('pass'));
        }
        return url+param;
    },
    getData(page){
        location.href = this.makUrl('{{ route('admin.dp-advanced-students.index') }}',page);
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
    editRoute:'{{ route('admin.dp-advanced-students.edit',99999999) }}',
    destroyRoute:'{{ route('admin.dp-advanced-students.destroy',99999999) }}',
    updateRoute:'{{ route('admin.dp-advanced-students.update', 99999999) }}',
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
            <select
                class="h-10 px-4 m-1 text-sm border-gray-300 rounded-md shadow-sm focus:border-cyan-300 focus:ring focus:ring-cyan-200 focus:ring-opacity-50 placeholder:text-sm placeholder:text-mainTextGray"
                name="county_id">
                @foreach ($counties as $county_id=>$county)
                <option value="{{ $county_id }}" {{ intval(request('county_id'))===$county_id ?'selected':'' }}>{{
                    $county }}
                </option>
                @endforeach
            </select>
            <select
                class="h-10 px-4 m-1 text-sm border-gray-300 rounded-md shadow-sm focus:border-cyan-300 focus:ring focus:ring-cyan-200 focus:ring-opacity-50 placeholder:text-sm placeholder:text-mainTextGray"
                name="pass">
                @foreach ($passOptions as $pass_id=>$pass)
                <option value="{{ $pass_id }}" {{ strval(request('pass'))===strval($pass_id) ?'selected':'' }}>{{
                    $pass }}
                </option>
                @endforeach
            </select>
            <div class="flex flex-row items-center justify-start">
                <select
                    class="h-10 px-4 m-1 min-w-[20rem] text-sm border-gray-300 rounded-md shadow-sm focus:border-cyan-300 focus:ring focus:ring-cyan-200 focus:ring-opacity-50 placeholder:text-sm placeholder:text-mainTextGray"
                    name="training">
                    <option value="" {{ strval(request('training'))==='' ?'selected':'' }}>- 培訓計畫名稱 -</option>
                    @foreach ($trainingOptions as $training)
                    <option value="{{ $training }}" {{ strval(request('training'))===strval($training) ?'selected':''
                        }}>{{
                        $training }}
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
    <div>合格：{{ $passCount ?? 0 }}，受訓中：{{ $traingCount ?? 0 }}，即將逾期：{{ $soonExpireCount ?? 0 }}，逾期：{{ $expireCount ?? 0
        }}
    </div>
    <div class="w-full max-w-full overflow-auto border shadow-lg">
        <table class="w-full bg-white text-mainAdminTextGrayDark">
            <thead>
                <tr class="border-b bg-mainLight">
                    <th class="p-2 font-normal text-center border-r last:border-r-0">證書編號</th>
                    <th class="p-2 font-normal text-center border-r last:border-r-0">授證日期</th>
                    <th class="p-2 font-normal text-center border-r last:border-r-0">姓名</th>
                    <th class="p-2 font-normal text-center border-r last:border-r-0">性別</th>
                    <th class="p-2 font-normal text-center border-r last:border-r-0">所屬縣市</th>
                    <th class="p-2 font-normal text-center border-r last:border-r-0">狀態</th>
                    <th class="p-2 font-normal text-center border-r last:border-r-0">課程</th>
                </tr>
            </thead>
            <tbody class="text-content text-mainAdminTextGrayDark">
                <template x-for="(data_item, index) in items">
                    <tr class="border-b last:border-b-0 even:bg-white odd:bg-mainLight">
                        <td class="p-2 text-center border-r last:border-r-0" x-text="data_item.certificate"></td>
                        <td class="p-2 text-center border-r last:border-r-0" x-text="data_item.date_first_finish"></td>
                        <td class="p-2 text-center border-r last:border-r-0" x-text="data_item.name"
                            :class="{'text-red-600':(data_item.date_first_finish!=null && data_item.date_first_finish<data_item.expire_date),'text-mainAdminTextGrayDark':!(data_item.date_first_finish!=null && data_item.date_first_finish<data_item.expire_date)}">
                        </td>
                        <td class="p-2 text-center border-r last:border-r-0" x-text="data_item.gender"></td>
                        <td class="p-2 text-center border-r last:border-r-0"
                            x-text="data_item.county?data_item.county.name:''">
                        </td>
                        <td class="p-2 text-center border-r last:border-r-0" x-text="data_item.expire_state">
                        </td>
                        <td class="border-r last:border-r-0">
                            <div class="flex flex-col items-start justify-start p-1">
                                <template x-for="(subject_item, subject_idx) in data_item.student_subjects">
                                    <span class="p-1"
                                        x-text="subject_item.name + (subject_item.start_date==null?'':('（'+ subject_item.start_date +'）'))"></span>
                                </template>
                            </div>
                        </td>
                    </tr>
                </template>
                <template x-if="items.length==0">
                    <tr class="bg-white border-b last:border-b-0">
                        <td colspan="7" class="p-2 text-center border-r last:border-r-0">無防災士資料</td>
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