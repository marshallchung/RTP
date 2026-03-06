@extends('layouts.app')

@section('title', '成果資料')
@section('subtitle', '防救災圖資 - ' . $countyUser->name)

@section('content')
<div x-data="{
        search:'',
        type:'',
        district:'',
        pagination:'',
        imageDataList:[],
        clearSearch(){
            this.type='';
            this.district='';
            this.getData(1);
        },
        getData(page){
            var url = '/image-data/search/{{ $countyUser->name }}?type=' + encodeURIComponent(this.type)+ '&district=' + encodeURIComponent(this.district)+ '&search=' + encodeURIComponent(this.search);
            if(page){
                url += '&page=' + encodeURIComponent(page);
            }
            fetch(url)
            .then((response)=>{
                return response.json();
            }).then((jsonData) => {
                this.pagination=jsonData.pagination;
                this.imageDataList=jsonData.imageDataList;
            }).catch(function(error) {
                console.log(error);
            });
        },
    }" class="flex flex-row items-start justify-center w-full pb-12" x-init="$nextTick(() => {
    getData();
    })">
    <div class="flex flex-col items-start justify-start w-full max-w-screen-lg px-4 py-3 space-y-4">
        <h1 class="pb-1">{{ $countyUser->name }}</h1>
        <a href="{{ route('image-data.index') }}"
            class="flex flex-row items-center justify-center h-10 space-x-1 text-white rounded w-28 bg-mainTextGray">
            <i class="i-fa6-solid-arrow-left" aria-hidden="true"></i>
            <span>防救災圖資</span>
        </a>
        <div class="flex flex-row items-start justify-around w-full space-x-4">
            <div class="flex flex-col justify-start w-full space-y-4">
                <div class="flex flex-row items-center justify-center flex-1 space-x-2">
                    <select @change="getData(1)"
                        class="h-12 text-sm border-gray-300 rounded-md focus:border-sky-300 focus:ring focus:ring-sky-200 focus:ring-opacity-50 text-mainAdminTextGrayDark"
                        x-model='district'>
                        @foreach($districtOptions as $key=>$value)
                        <option value="{{ $key }}">{{ $value }}</option>
                        @endforeach
                    </select>
                    <select @change="getData(1)"
                        class="h-12 text-sm border-gray-300 rounded-md focus:border-sky-300 focus:ring focus:ring-sky-200 focus:ring-opacity-50 text-mainAdminTextGrayDark"
                        x-model='type'>
                        @foreach($typeOptions as $key=>$value)
                        <option value="{{ $key }}">{{ $value }}</option>
                        @endforeach
                    </select>
                    <div class="relative flex flex-row items-center justify-start h-12 space-x-2">
                        <label>
                            <input type="text" x-model="search" @input.debounce.500ms="getData(1)"
                                class="w-64 h-12 p-4 pr-12 border-gray-300 rounded-md shadow-sm placeholder:text-mainTextGray focus:border-sky-300 focus:ring focus:ring-sky-200 focus:ring-opacity-50"
                                name="search" placeholder="搜尋">
                        </label>
                        <div
                            class="absolute top-0 right-0 flex items-center justify-center w-12 h-12 text-white bg-mainBlue hover:bg-mainBlueDark rounded-r-md">
                            <i class="w-5 h-5 i-heroicons-magnifying-glass-20-solid"></i>
                        </div>
                        <button type="button" x-show="search.length>0" @click="clearSearch()"
                            class="absolute top-0 flex items-center justify-center w-10 h-12 right-12 rounded-r-md text-mainTextGray">
                            <i class="w-6 h-6 i-heroicons-x-circle-20-solid"></i>
                        </button>
                    </div>
                    <button type="button" @click="clearSearch()"
                        class="flex flex-row items-center justify-center w-32 h-12 space-x-2 text-white rounded-md bg-mainGrayDark hover:bg-mainTextGray">
                        <i class="i-fa6-solid-rotate" aria-hidden="true"></i>
                        <span>顯示全部</span>
                    </button>
                </div>
                <div class="flex flex-row items-center justify-center" x-html="pagination"></div>
            </div>
        </div>
        <table class="w-full bg-white border shadow-lg text-mainAdminTextGrayDark">
            <thead>
                <tr class="text-white border-b rounded-t bg-mainGrayDark">
                    <th class="w-40 px-4 py-2 font-bold border-r last:border-r-0">地區</th>
                    <th class="w-40 px-4 py-2 font-bold border-r last:border-r-0">類型</th>
                    <th class="px-4 py-2 font-bold border-r last:border-r-0">檔案</th>
                </tr>
            </thead>
            <tbody>
                <template x-if="imageDataList.length>0">
                    <template x-for="imageData in imageDataList" :key="imageData.image_list">
                        <tr class="bg-white border-b odd:bg-gray-100 last:border-b-0 last:rounded-b">
                            <td class="px-4 py-2 text-center border-r last:border-r-0" x-text="imageData.name">
                            </td>
                            <td class="px-4 py-2 text-center border-r last:border-r-0" x-text="imageData.type_name">
                            </td>
                            <td
                                class="flex flex-col items-start justify-start px-4 py-2 space-y-2 text-left border-r last:border-r-0">
                                <template x-for="imageFile in imageData.files">
                                    <div class="flex flex-col items-start justify-start">
                                        <a :href="imageFile.path" class="text-mainBlueDark" x-text="imageFile.name"></a>
                                        <span class="text-sm text-mainTextGray"
                                            x-text="' - ' + (new Date(imageFile.created_at)).toLocaleString('chinese',{hour12:false})"></span>
                                    </div>
                                </template>
                            </td>
                        </tr>
                    </template>
                </template>
                <template x-if="imageDataList.length==0">
                    <tr class="bg-white border-b odd:bg-gray-100 last:border-b-0 last:rounded-b">
                        <td colspan="4" class="px-4 py-2 text-center border-r last:border-r-0 text-mainTextGray">
                            無資料
                        </td>
                    </tr>
                </template>
            </tbody>
        </table>
    </div>
</div>
@endsection

@section('js')
@endsection