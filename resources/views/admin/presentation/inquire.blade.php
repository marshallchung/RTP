@extends('admin.layouts.dashboard', [
'heading' => '資料展示',
'breadcrumbs' => [
'期末簡報上傳',
'資料展示'
]
])

@section('title', '資料展示')

@section('inner_content')
<div x-data="{
    loading:false,
    year:'{{ $year }}',
    user:{{ json_encode(Auth::user()) }},
    getData(page){
        location.href = this.makUrl('{{ route('admin.news.index') }}',page);
    },
    deleteItem(url){
        if(confirm('確定要刪除嗎？')){
            var This=this;
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
    destroyRoute:'{{ route('admin.presentation.destroy-file',99999999) }}',
    items: {{ $countyUsers->toJson(JSON_PRETTY_PRINT) }},
 }" class="flex flex-col items-end justify-start w-full p-4 space-y-4">
    <div class="flex flex-row flex-wrap w-full">
        {!! Form::open(['method'=>'get', 'class' => 'flex flex-row flex-wrap items-center justify-start w-full
        space-x-4']) !!}
        <select x-model="year"
            class="h-12 px-4 border-gray-300 rounded-md shadow-sm focus:border-cyan-300 focus:ring focus:ring-cyan-200 focus:ring-opacity-50 w-36"
            name="year">
            @for ($i=date('Y');$i>=2023;$i--)
            <option value="{{ $i }}">{{ $i-1911 }}年</option>
            @endfor
        </select>
        <button type="submit" class="flex items-center justify-center w-20 h-10 text-white bg-mainCyanDark">搜尋</button>
        <a href="{{ route('admin.presentation.export', request('year')) }}"
            class="flex items-center justify-center w-20 h-10 bg-white border border-gray-300 cursor-pointer text-mainAdminTextGrayDark hover:bg-gray-50">打包下載</a>
        {!! Form::close() !!}
    </div>
    <div class="w-full max-w-[calc(100vw-280px)] overflow-scroll"
        :class="{'max-w-[calc(100vw-280px)]':openMMC,'max-w-[calc(100vw-96px)]':!openMMC}">
        <table class="w-full bg-white border shadow-lg text-mainAdminTextGrayDark">
            <thead>
                <tr class="border-b rounded-t bg-mainGray">
                    <th class="p-2 font-normal text-left border-r last:border-r-0">縣市</th>
                    <th class="p-2 font-normal text-left border-r last:border-r-0">期末簡報檔案</th>
                </tr>
            </thead>
            <tbody class="text-content text-mainAdminTextGrayDark">
                <template x-for="(countyUser, index) in items" :key="index">
                    <tr class="border-b last:border-b-0 odd:bg-white even:bg-mainLight">
                        <td class="p-2 font-bold text-left border-r last:border-r-0">
                            <span class="text-nowrap" x-text="countyUser.name"></span>
                        </td>
                        <td class="p-2 border-r last:border-r-0">
                            <div class="flex flex-col items-start w-full space-y-2 justify-stretch">
                                <template x-if="countyUser.presentation">
                                    <template x-for="(plan, plan_idx) in countyUser.presentation"
                                        :key="index + '_' + plan_idx">
                                        <template x-if="plan.files">
                                            <template x-for="(file, file_idx) in plan.files"
                                                :key="index + '_' + plan_idx + '_' + file_idx">
                                                <div class="flex flex-row items-center justify-start w-full space-x-2">
                                                    <a :href="'/' + file.path" x-text="file.name"
                                                        class=" text-mainBlueDark"></a>
                                                    <template x-if="user.hasPermission">
                                                        <button type="button"
                                                            @click="deleteItem(destroyRoute.replace('99999999',file.id))"
                                                            class="flex items-center justify-center w-10 h-8 text-sm text-white bg-orange-600 rounded cursor-pointer hover:bg-orange-500">
                                                            <i class="w-2.5 h-2.5 i-fa6-solid-trash"
                                                                aria-hidden="true"></i>
                                                        </button>
                                                    </template>
                                                    <span class="text-sm text-mainAdminTextGray"
                                                        x-text="' - ' + (new Date(file.created_at)).toLocaleString('chinese',{hour12:false})"></span>
                                                </div>
                                            </template>
                                        </template>
                                    </template>
                                </template>
                            </div>
                        </td>
                    </tr>
                </template>
            </tbody>
        </table>
    </div>
</div>
@endsection