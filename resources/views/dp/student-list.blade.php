@extends('layouts.app')

@section('title', '防災士培訓')
@section('subtitle', '防災士名冊查詢')

@section('content')
<div x-data="{
        search:'',
        pagination:'',
        dpStudents:[],
        clearSearch(){
            this.search='';
            this.getData(1);
        },
        getData(page){
            var url = '/dp/student-search?search=' + encodeURIComponent(this.search);
            if(page){
                url += '&page=' + encodeURIComponent(page);
            }
            fetch(url)
            .then((response)=>{
                return response.json();
            }).then((jsonData) => {
                this.pagination=jsonData.pagination;
                this.dpStudents=jsonData.dpStudents;
            }).catch(function(error) {
                console.log(error);
            });
        },
    }" class="flex flex-row items-start justify-center w-full pb-12" x-init="$nextTick(() => {
        getData();
    })">
    <div class="flex flex-col items-center justify-start w-full px-4 space-y-6">
        <div class="flex flex-row items-start justify-around w-full space-x-4">
            <div class="flex flex-row items-center justify-between">
                <div class="relative flex flex-row items-center justify-start h-12 space-x-2">
                    <label>
                        <span class="mr-2">姓名</span>
                        <input type="text" x-model="search" @input.debounce.500ms="getData(1)"
                            class="w-64 h-12 p-4 pr-12 border-gray-300 rounded-md shadow-sm placeholder:text-mainTextGray focus:border-sky-300 focus:ring focus:ring-sky-200 focus:ring-opacity-50"
                            name="name" placeholder="姓名">
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
            <div class="flex flex-row items-center justify-center" x-html="pagination"></div>
        </div>
        <table class="w-full max-w-3xl bg-white border shadow-lg text-mainAdminTextGrayDark">
            <thead>
                <tr class="text-white border-b rounded-t bg-mainGrayDark">
                    <th class="p-2 font-bold border-r last:border-r-0">證書編號</th>
                    <th class="p-2 font-bold border-r last:border-r-0">姓名</th>
                    <th class="p-2 font-bold border-r last:border-r-0">完訓日期（發證日期）</th>
                    <th class="p-2 font-bold border-r last:border-r-0">培訓單位</th>
                    <th class="p-2 font-bold border-r last:border-r-0">性別</th>
                </tr>
            </thead>
            <tbody>
                <template x-if="dpStudents.length>0">
                    <template x-for="dpStudent in dpStudents" :key="'dpStudent' + dpStudent.id">
                        <tr class="bg-white border-b odd:bg-gray-100 last:border-b-0 last:rounded-b">
                            <td class="p-2 text-center border-r last:border-r-0" x-text="dpStudent.certificate"></td>
                            <td class="p-2 text-center border-r last:border-r-0" x-text="dpStudent.name"></td>
                            <td class="p-2 text-center border-r last:border-r-0"
                                x-text="dpStudent.date_first_finish.substring(0,10)">
                            </td>
                            <td class="p-2 text-center border-r last:border-r-0" x-text="dpStudent.unit_first_course">
                            </td>
                            <td class="p-2 text-center border-r last:border-r-0" x-text="dpStudent.gender"></td>
                        </tr>
                    </template>
                </template>
                <template x-if="dpStudents.length==0">
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
@endsection