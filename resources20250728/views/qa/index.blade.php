@extends('layouts.app')

@section('title', 'QA專區')

@section('content')
<div x-data="{
        search:'',
        pagination:'',
        QASList:[],
        sort:'{{ request('sort') }}',
        clearSearch(){
            this.search='';
            this.getData(1);
        },
        getData(page){
            var url = '/QA/search?search=' + encodeURIComponent(this.search)+ '&sort=' + encodeURIComponent(this.sort);
            if(page){
                url += '&page=' + encodeURIComponent(page);
            }
            fetch(url)
            .then((response)=>{
                return response.json();
            }).then((jsonData) => {
                this.pagination=jsonData.pagination;
                this.QASList=jsonData.QASList;
            }).catch(function(error) {
                console.log(error);
            });
        },
        setSort(sort){
            this.sort=sort;
            this.getData(1);
        },
    }" class="flex flex-row items-start justify-center w-full pb-12" x-init="$nextTick(() => {
        getData();
    })">
    <div class="flex flex-col items-center justify-start w-full max-w-screen-lg px-4 space-y-6">
        <div class="flex flex-row flex-wrap items-center justify-center w-full">
            <button type="button" @click="setSort('')"
                class="flex-1 h-11 px-2 min-w-[6rem] max-w-[8rem] first:rounded-l-md last:rounded-r-md border flex justify-center items-center"
                :class="{' bg-lime-600 text-white':sort==='','bg-white text-mainAdminTextGrayDark':sort!==''}">全部類別</button>
            @foreach($sorts as $item)
            <button type="button" @click="setSort('{{ $item }}')"
                class="flex-1 h-11 px-2 min-w-[6rem] max-w-[8rem] first:rounded-l-md last:rounded-r-md border flex justify-center items-center"
                :class="{' bg-lime-600 text-white':sort==='{{ $item }}','bg-white text-mainAdminTextGrayDark':sort!=='{{ $item }}'}">{{
                $item
                }}</button>
            @endforeach
        </div>
        <div
            class="flex flex-col items-center justify-center w-full space-x-4 space-y-4 sm:space-y-0 sm:flex-row sm:justify-around">
            <div class="flex flex-row items-center justify-start flex-1">
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
            </div>
            <div class="flex flex-row items-center justify-center flex-1 w-full sm:justify-end" x-html="pagination">
            </div>
        </div>
        <div class="w-full">
            <table class="w-full bg-white border shadow-lg text-mainAdminTextGrayDark">
                <thead>
                    <tr class="text-white border-b rounded-t bg-mainGrayDark">
                        <th class="p-2 font-bold border-r last:border-r-0" style="width: 20%;">分類</th>
                        <th class="p-2 font-bold border-r last:border-r-0" style="width: 20%;">日期</th>
                        <th class="p-2 font-bold border-r last:border-r-0" style="width: calc(100% - 90px - 40%)%;">標題</th>
                        <th class="p-2 font-bold border-r last:border-r-0" style="width: 90px;">點擊率</th>
                    </tr>
                </thead>
                <tbody>
                    <template x-if="QASList.length>0">
                        <template x-for="QAS in QASList">
                            <tr class="bg-white border-b odd:bg-gray-100 last:border-b-0 last:rounded-b">
                                <td class="px-4 py-2 text-center border-r last:border-r-0" x-text="QAS.sort">
                                </td>
                                <td class="px-4 py-2 text-center border-r last:border-r-0"
                                    x-text="QAS.created_at.substring(0,10)">
                                </td>
                                <td class="px-4 py-2 text-center border-r last:border-r-0">
                                    <a :href="'/QA/show/'+QAS.id" x-text="QAS.title" class="text-mainBlueDark"></a>
                                </td>
                                <td class="px-4 py-2 text-center border-r last:border-r-0"
                                    x-text="QAS.counter_count?QAS.counter_count:0">
                                </td>
                            </tr>
                        </template>
                    </template>
                    <template x-if="QASList.length==0">
                        <tr class="bg-white border-b odd:bg-gray-100 last:border-b-0 last:rounded-b">
                            <td colspan="5" class="px-4 py-2 text-center border-r last:border-r-0 text-mainTextGray">
                                無資料
                            </td>
                        </tr>
                    </template>
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@section('js')
@endsection