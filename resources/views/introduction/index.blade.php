@extends('layouts.app')

@section('title', '深耕介紹')
@section('subtitle', $introductionType->name)

@section('content')
<div x-data="{
        search:'',
        pagination:'',
        introductionList:[],
        introductionType:{{ $introductionType->id }},
        clearSearch(){
            this.search='';
            this.getData(1);
        },
        getData(page){
            var url = '/introduction/search/{{ $introductionType->id }}?search=' + encodeURIComponent(this.search);
            if(page){
                url += '&page=' + encodeURIComponent(page);
            }
            fetch(url)
            .then((response)=>{
                return response.json();
            }).then((jsonData) => {
                this.pagination=jsonData.pagination;
                this.introductionList=jsonData.introductionList;
                this.introductionType=jsonData.introductionType;
            }).catch(function(error) {
                console.log(error);
            });
        },
    }" class="flex flex-row items-start justify-center w-full pb-12" x-init="$nextTick(() => {
        getData();
    })">
    <div class="flex flex-col items-center justify-start w-full max-w-screen-lg px-4 space-y-6">
        <div class="flex flex-row items-start justify-around w-full space-x-4">
            <div class="flex flex-row items-center justify-center flex-1">
                <div class="relative flex flex-row items-center justify-start h-12 space-x-2">
                    <label class="flex flex-row items-center">
                        <span class="mr-2 whitespace-nowrap">搜尋</span>
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
            </div>
            <div class="flex flex-row items-center justify-center flex-1" x-html="pagination"></div>
        </div>
        <table class="w-full max-w-3xl bg-white border shadow-lg text-mainAdminTextGrayDark">
            <thead>
                <tr class="border-b rounded-t">
                    <th class="px-4 py-2 font-bold text-left text-white border-r last:border-r-0 bg-mainGrayDark">標題
                    </th>
                </tr>
            </thead>
            <tbody>
                <template x-if="introductionList.length>0">
                    <template x-for="introduction in introductionList" :key="'introduction' + introduction.id">
                        <tr class="bg-white border-b odd:bg-gray-100 last:border-b-0 last:rounded-b">
                            <td class="px-4 py-2 text-left border-r last:border-r-0">
                                <a :href="'/introduction/show/' + introduction.id" class="text-mainBlueDark"
                                    x-text="introduction.title"></a>
                            </td>
                        </tr>
                    </template>
                </template>
                <template x-if="introductionList.length==0">
                    <tr class="bg-white border-b odd:bg-gray-100 last:border-b-0 last:rounded-b">
                        <td class="px-4 py-2 text-center border-r last:border-r-0 text-mainTextGray">
                            無資料
                        </td>
                    </tr>
                </template>
            </tbody>
        </table>
    </div>
</div>
<div class="flex flex-col items-center justify-center w-full px-4 sm:flex-row">
    @foreach ([
        asset('image/live_photo/photo1.webp'),
        asset('image/live_photo/photo2.webp'),
        asset('image/live_photo/photo3.webp'),
		asset('image/live_photo/photo4.webp'),
        asset('image/live_photo/photo5.webp'),
        asset('image/live_photo/photo6.webp'),
        asset('image/live_photo/photo7.webp'),
        asset('image/live_photo/photo8.webp'),
    ] as $url)
    <div class="w-full m-1 sm:flex-1">
        <img src="{{ $url }}" class="w-full h-auto" />
    </div>
    @endforeach
</div>
@endsection

@section('js')
@endsection