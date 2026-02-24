@extends('layouts.app')
@section('title', $page->title)
@section('subtitle', $page->subtitle)

@section('css')
@endsection
@section('content')
<div x-data="{
        QASList:[],
        subSortOptions:[],
        sort:'{{ $selectedSortId }}',
        sub_sort:'',
        getData(page){
            var url = '/resource/searchvideo?sort=' + encodeURIComponent(this.sort)+ '&sub_sort=' + encodeURIComponent(this.sub_sort);
            if(page){
                url += '&page=' + encodeURIComponent(page);
            }
            fetch(url)
            .then((response)=>{
                return response.json();
            }).then((jsonData) => {
                this.QASList=jsonData.data;
                this.subSortOptions=jsonData.subSortOptions;
            }).catch(function(error) {
                console.log(error);
            });
        },
        setSort(sort){
            this.sort=sort;
            this.sub_sort='';
            this.getData(1);
        },
        setSubSort(sub_sort){
            this.sub_sort=sub_sort;
            this.getData(1);
        },
    }" class="flex flex-row items-start justify-center w-full pb-12" x-init="$nextTick(() => {
        getData();
    })">
    <div class="flex flex-col items-center justify-start flex-1 max-w-screen-lg px-4 space-y-6">
        <div class="flex flex-row flex-wrap items-center justify-center w-full">
            <button type="button" @click="setSort('')"
                class="flex-1 h-11 px-2 min-w-[6rem] max-w-[8rem] first:rounded-l-md last:rounded-r-md border flex justify-center items-center"
                :class="{' bg-lime-600 text-white':sort==='','bg-white text-mainAdminTextGrayDark':sort!==''}">全部類別</button>
            @foreach($sorts as $key =>$sort)
            <button type="button" @click="setSort('{{ $key }}')"
                class="flex-1 h-11 px-2 min-w-[6rem] max-w-[8rem] first:rounded-l-md last:rounded-r-md border flex justify-center items-center"
                :class="{' bg-lime-600 text-white':sort==='{{ $key }}','bg-white text-mainAdminTextGrayDark':sort!=='{{ $key }}'}">{{
                $sort['name']
                }}</button>
            @endforeach
        </div>
        <template x-if="Object.keys(subSortOptions).length>0">
            <div class="flex flex-row flex-wrap items-center justify-center w-full">
                <button type="button" @click="setSubSort('')"
                    class="flex-1 h-8 px-2 min-w-[4rem] max-w-[6rem] first:rounded-l-md last:rounded-r-md border text-xs flex justify-center items-center"
                    :class="{' bg-lime-600 text-white':sub_sort==='','bg-gray-50 text-mainAdminTextGrayDark':sub_sort!==''}">全部</button>
                <template x-for="QASindex in Object.keys(subSortOptions)">
                    <button type="button" @click="setSubSort(QASindex)"
                        class="flex-1 h-8 px-2 min-w-[4rem] max-w-[6rem] first:rounded-l-md last:rounded-r-md border text-xs flex justify-center items-center"
                        :class="{' bg-lime-600 text-white':sub_sort===QASindex,'bg-gray-50 text-mainAdminTextGrayDark':sub_sort!==QASindex}"
                        x-text="subSortOptions[QASindex]"></button>
                </template>
            </div>
        </template>
        <div class="flex flex-row flex-wrap items-stretch justify-start w-full max-w-screen-lg">
            <template x-if="QASList.length>0">
                <template x-for="(QAS,QASindex) in QASList">
                    <div class="flex flex-col w-full p-2 sm:w-1/2 md:w-1/3 lg:w-1/4">
                        <div class="relative flex flex-col w-full h-full overflow-hidden bg-white border rounded-md">
                            <div class="flex items-center justify-center w-full">
                                <a :href="'/resource/video/' + QAS.id" target="_blank" class="w-full">
                                    <img :src="QAS.thumbnail_url" class="object-cover w-full h-auto" alt="">
                                </a>
                            </div>
                            <div class="flex flex-col flex-1 w-full p-4">
                                <h6 class="justify-start flex-1 text-mainBlueDark">
                                    <a :href="'/resource/video/' + QAS.id" target="_blank" x-text="QAS.title"></a>
                                </h6>
                                <div class="text-sm text-right text-gray-400 card-text">
                                    <i class="mr-1 i-fa6-solid-eye text-mainBlueDark"></i>
                                    <span x-text="QAS.counter.count + ' 瀏覽'"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </template>
            </template>
            <template x-if="QASList.length==0">
                <div class="w-full bg-white border-b odd:bg-gray-100 last:border-b-0 last:rounded-b">
                    <div class="w-full px-4 py-4 text-center border-r last:border-r-0 text-mainAdminTextGrayDark">
                        無資料
                    </div>
                </div>
            </template>
        </div>
    </div>
</div>
@endsection

@section('js')
@endsection