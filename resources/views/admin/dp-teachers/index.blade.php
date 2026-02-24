<?php
	$headerButton   = [];
	$headerButton[] = '匯出';
	$headerButton[] = route('admin.dp-teachers.export', request()->input());

  
if(Auth::user()->isAbleTo('DP-teachers-create')) {
	$headerButton[] = '師資統計';
	$headerButton[] =   route('admin.dp-teachers.summary');
	$headerButton[] =   '匯入';
	$headerButton[] =   route('admin.dp-teachers.import');
	$headerButton[] = '新增';
	$headerButton[] = route('admin.dp-teachers.create');
}
?>

@extends('admin.layouts.dashboard', [
'heading' => '防災士培訓 > 師資資料庫管理',
'header_btn' => $headerButton,
'breadcrumbs' => ['師資資料庫管理']
])

@section('title', '師資資料庫管理')

@section('inner_content')
<div x-data="{
    loading:false,
    expired:'{{ request('expired') }}',
    name:'{{ request('name') }}',
    dp_subject:'{{ request('dp_subject') }}',
    location:'{{ request('location') }}',
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
        if(urlParams.has('name')){
            param += param.length>0 ? '&' : '?';
            param += 'name' + encodeURIComponent(urlParams.get('name'));
        }
        if(urlParams.has('dp_subject')){
            param += param.length>0 ? '&' : '?';
            param += 'dp_subject=' + encodeURIComponent(urlParams.get('dp_subject'));
        }
        if(urlParams.has('location')){
            param += param.length>0 ? '&' : '?';
            param += 'location=' + encodeURIComponent(urlParams.get('location'));
        }
        if(urlParams.has('expired')){
            param += param.length>0 ? '&' : '?';
            param += 'expired=' + encodeURIComponent(urlParams.get('expired'));
        }
        return url+param;
    },
    getData(page){
        location.href = this.makUrl('/admin/dp-teachers',page);
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
    mailItem(url){
        var This=this;
        url=this.makUrl(url);
        var token='{{ csrf_token() }}';
        var data = '_method=POST&_token=' + token + '&response_json=1';
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
            alert(json.msg);
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
    destroyRoute:'{{ route('admin.dp-teachers.destroy',99999999) }}',
    editRoute:'{{ route('admin.dp-teachers.' . $routeName, 99999999) }}',
    mailRoute:'{{ route('admin.dp-teachers.send-profile-update-mail', 99999999) }}',
    items: {{ $data->makeHidden('updated_at')->toJson(JSON_PRETTY_PRINT) }},
    user:{{ Auth::user()->toJson(JSON_PRETTY_PRINT) }},
 }" class="flex flex-col items-end justify-start w-full p-4 space-y-4">
    <div x-show="loading" class="absolute z-[1100] inset-0 bg-black/30 hidden justify-center items-center"
        :class="{'flex':loading,'hidden':!loading}">
        <div class="relative w-full h-full">
            <img src="/image/loading.svg" class="w-16 h-16 absolute left-1/2 top-[calc(50vh-2rem)]" alt="">
        </div>
    </div>
    <div class="flex flex-row items-center justify-between w-full mb-1 ml-auto mr-auto flex-nowrap" id="search-form">
        <form method="GET" action="{{ request()->url() }}" accept-charset="UTF-8"
            class="flex flex-row items-center justify-end w-full mb-1 ml-auto mr-auto space-x-4 flex-nowrap">
            <label class="flex flex-row items-center space-x-2">
                <span>逾期師資</span>
                <select name="expired"
                    class="h-10 px-4 m-1 text-sm border-gray-300 rounded-md shadow-sm w-28 focus:border-cyan-300 focus:ring focus:ring-cyan-200 focus:ring-opacity-50 placeholder:text-sm placeholder:text-mainTextGray">
                    <option value="" {{ request('expired')==='' ?'selected':''}}>
                        全部
                    </option>
                    <option value="0" {{ request('expired')==='0' ?'selected':''}}>
                        未逾期
                    </option>
                    <option value="1" {{ request('expired')==='1' ?'selected':''}}>
                        逾期
                    </option>
                </select>
            </label>
            <input x-model="name"
                class="h-10 px-4 m-1 text-sm border-gray-300 rounded-md shadow-sm focus:border-cyan-300 focus:ring focus:ring-cyan-200 focus:ring-opacity-50 placeholder:text-sm placeholder:text-mainTextGray"
                placeholder="姓名" name="name" type="text">
            <select x-model="dp_subject"
                class="h-10 px-4 m-1 text-sm border-gray-300 rounded-md shadow-sm focus:border-cyan-300 focus:ring focus:ring-cyan-200 focus:ring-opacity-50 placeholder:text-sm placeholder:text-mainTextGray"
                name="dp_subject">
                @foreach ($dpSubjects as $subject_id=>$dp_subject)
                <option value="{{ $subject_id }}" {{ strval($subject_id)===request('dp_subject')?'selected':''}}>
                    {{ $dp_subject }}
                </option>
                @endforeach
            </select>
            <select x-model="location"
                class="h-10 px-4 m-1 text-sm border-gray-300 rounded-md shadow-sm w-28 focus:border-cyan-300 focus:ring focus:ring-cyan-200 focus:ring-opacity-50 placeholder:text-sm placeholder:text-mainTextGray"
                name="location">
                @foreach ($counties as $county_id=>$county_name)
                <option value="{{ $county_id }}" {{ strval($county_id)===request('location')?'selected':''}}>{{
                    $county_name
                    }}</option>
                @endforeach
            </select>
            <div class="flex flex-row">
                <button type="submit"
                    class="flex items-center justify-center w-20 h-10 text-white bg-mainCyanDark">搜尋</button>
                <a href="{{ request()->url() }}"
                    class="flex items-center justify-center w-20 h-10 bg-gray-100 border border-gray-300 cursor-pointer text-mainAdminTextGrayDark hover:bg-gray-50 ">清空</a>
            </div>
        </form>
    </div>
    <span class="w-full text-left">備註：種子師資認證有效期限為通過翌日起三年，顯示紅字為該科目認證已過期。</span>
    <table class="w-full bg-white border shadow-lg text-mainAdminTextGrayDark">
        <thead>
            <tr class="border-b bg-mainLight">
                @if (Auth::user()->isAbleTo('DP-teachers-create'))
                <th class="p-2 font-normal text-left border-r w-14 last:border-r-0">刪除</th>
                @endif
                <th class="p-2 font-normal text-left border-r last:border-r-0">姓名</th>
                <th class="p-2 font-normal text-left border-r last:border-r-0">市內電話</th>
                <th class="p-2 font-normal text-left border-r last:border-r-0">行動電話</th>
                <th class="p-2 font-normal text-left border-r last:border-r-0">E-mail</th>
                <th class="p-2 font-normal text-left border-r last:border-r-0">教授科目</th>
                <th class="p-2 font-normal text-left border-r last:border-r-0">居住縣市</th>
            </tr>
        </thead>
        <tbody class="text-content text-mainAdminTextGrayDark">
            <template x-for="(data_item, index) in items">
                <tr class="bg-white border-b last:border-b-0">
                    @if (Auth::user()->isAbleTo('DP-teachers-create'))
                    <td class="p-2 text-center border-r last:border-r-0">
                        <button type="button" @click="deleteItem(destroyRoute.replace('99999999',data_item.id))"
                            class="flex items-center justify-center w-6 h-6 text-sm text-white bg-orange-600 rounded cursor-pointer hover:bg-orange-500">
                            <i class="w-2.5 h-2.5 i-fa6-solid-trash"></i>
                        </button>
                    </td>
                    @endif

                    @if (Auth::user()->isAbleTo('DP-teachers-create'))
                    <td class="p-2 border-r last:border-r-0" style="width:100px">
                        <a :href="editRoute.replace('99999999',data_item.id)" class="text-mainBlueDark"
                            x-text="data_item.name"></a>
                        @else
                    <td class="p-2 border-r last:border-r-0">
                        <a :href="'/admin/dp-teachers/' + data_item.id" x-text="data_item.name"
                            class="text-mainBlueDark"></a>
                    </td>
                    @endif


                    <td class="p-2 border-r last:border-r-0" x-text="data_item.phone"></td>
                    <td class="p-2 border-r last:border-r-0" x-text="data_item.mobile"></td>
                    <td class="p-2 border-r last:border-r-0">
                        <div class="flex flex-row items-center justify-start space-x-2">
                            <span x-text="data_item.email"></span>
                            <template x-if="user.origin_role < 3 && data_item.email">
                                <button type="button" @click="mailItem(mailRoute.replace('99999999',data_item.id))"
                                    title="寄送師資資料更新連結"
                                    class="flex items-center justify-center w-6 h-6 text-sm text-white rounded cursor-pointer bg-mainCyanDark hover:bg-teal-400">
                                    <i class="w-2.5 h-2.5 i-fa6-solid-envelope"></i>
                                </button>
                            </template>
                        </div>
                    </td>
                    <td class="p-2 border-r last:border-r-0">
                        <ul class="list-disc list-inside">
                            <template x-for="(dp_teacher_subject, subject_idx) in data_item.dp_teacher_subjects">
                                <li class="mb-2 last:mb-0">
                                    <div class="flex flex-col items-start justify-start"
                                        :class="{' text-rose-600':dp_teacher_subject.is_expired}">
                                        <div class="flex flex-row items-center justify-start space-x-1 ">
                                            <span x-text="dp_teacher_subject.dp_subject.name"></span>
                                            <span x-text="dp_teacher_subject.type"></span>
                                        </div>
                                        <template x-if="dp_teacher_subject.pass_date">
                                            <span x-text="'（認證通過於：' + dp_teacher_subject.pass_date + '）'"></span>
                                        </template>
                                    </div>
                                </li>
                            </template>
                        </ul>
                    </td>
                    <td class="p-2 border-r last:border-r-0" x-text="data_item.location"></td>
                </tr>
            </template>
        </tbody>
    </table>
    <div class="">{!! $data->appends(request()->input())->render() !!}</div>
    <div id="js-token" class="hidden">{{ csrf_token() }}</div>
</div>
@endsection