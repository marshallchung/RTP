@extends('layouts.app')

@section('title', '推動韌性社區')
@section('subtitle', '韌性社區名單查詢')

@section('css')
@endsection

@section('content')
<div x-data="{
    startIndex:1,
    county:'',
    search:'',
    rank:'',
    service:null,
    map:null,
    pagination:'',
    unitDataList:[],
    rankCount:[],
    clearSearch(){
        this.search='';
        this.getData(1);
    },
    resetSearch(){
        this.search='';
        this.county='';
        this.rank='';
        this.getData(1);
    },
    getData(page){
        var url = '/dc/search-unit?search=' + encodeURIComponent(this.search)+ '&county=' + encodeURIComponent(this.county)+ '&rank=' + encodeURIComponent(this.rank);
        if(page){
            url += '&page=' + encodeURIComponent(page);
        }
        fetch(url)
        .then((response)=>{
            return response.json();
        }).then((jsonData) => {
            this.pagination=jsonData.pagination;
            this.startIndex=jsonData.startIndex;
            this.unitDataList=jsonData.unitDataList;
            this.rankCount=jsonData.rankCount;
        }).catch(function(error) {
            console.log(error);
        });
    },
    exportExcel(){
        var url = '/dc/search-unit?export=true&search=' + encodeURIComponent(this.search)+ '&county=' + encodeURIComponent(this.county)+
        '&rank=' + encodeURIComponent(this.rank);
        window.open(url,'_blank');
    },
    initMap(){
        const mapOptions = {
            center: { lat: 23.69781, lng: 120.960514 },
            zoom: 7,
            streetViewControl: false
        };
        loader
        .importLibrary('maps')
        .then(({Map}) => {
            this.map = new Map(document.getElementById('map'), mapOptions);
            this.service = new google.maps.places.PlacesService(this.map);
        })
        .catch((e) => {
            console.log(e);
        });
    },
    searchMap(keyword) {
        const request = {
            query: keyword,
            fields: ['name', 'geometry'],
        };
        const bounds = new google.maps.LatLngBounds();
        this.service.findPlaceFromQuery(request, (results, status) => {
            if (status !== google.maps.places.PlacesServiceStatus.OK || !results) {
                return;
            }
            let geometry = results[0].geometry;
            this.map.setCenter(geometry.location);
            if (geometry.viewport) {
                // Only geocodes have viewport.
                bounds.union(geometry.viewport);
            } else {
                bounds.extend(geometry.location);
            }
            this.map.fitBounds(bounds);
        });
    },
    initData(){
        this.initMap();
        this.getData();
    },
}" class="flex flex-row items-start justify-center w-full pb-12" x-init="$nextTick(() => {
    initData();
})">
    <div class="flex flex-col items-center justify-start w-full max-w-screen-xl px-4 space-y-6">
        <div class="flex flex-col items-center justify-center w-full space-y-2 sm:space-y-0 sm:flex-row">
            <div class="flex flex-row items-center justify-center flex-1 w-full space-x-2">
                <select @change=getData(1)
                    class="flex-1 h-12 text-sm border-gray-300 rounded-md sm:flex-initial focus:border-sky-300 focus:ring focus:ring-sky-200 focus:ring-opacity-50 text-mainAdminTextGrayDark"
                    x-model='county'>
                    @foreach($countyOptions as $key=>$value)
                    <option value="{{ $key }}">{{ $value }}</option>
                    @endforeach
                </select>
                <div class="relative flex-row items-center justify-start hidden h-12 space-x-2 sm:flex">
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
                <select @change=getData(1)
                    class="flex-1 h-12 text-sm border-gray-300 rounded-md sm:flex-initial focus:border-sky-300 focus:ring focus:ring-sky-200 focus:ring-opacity-50 text-mainAdminTextGrayDark"
                    x-model='rank'>
                    <option value="">星等</option>
                    <option value="一星">一星</option>
                    <option value="二星">二星</option>
                    <option value="三星">三星</option>
                </select>
                <button type="button" @click="resetSearch()"
                    class="flex-row items-center justify-center hidden w-32 h-12 space-x-2 text-white rounded-md sm:flex bg-mainGrayDark hover:bg-mainTextGray">
                    <i class="i-fa6-solid-rotate" aria-hidden="true"></i>
                    <span>顯示全部</span>
                </button>
            </div>
            <div class="flex flex-row items-center justify-center flex-1 w-full space-x-2 sm:hidden">
                <div class="relative flex flex-row items-center justify-start flex-1 h-12 space-x-2">
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
                <button type="button" @click="resetSearch()"
                    class="flex flex-row items-center justify-center w-32 h-12 space-x-2 text-white rounded-md bg-mainGrayDark hover:bg-mainTextGray">
                    <i class="i-fa6-solid-rotate" aria-hidden="true"></i>
                    <span>顯示全部</span>
                </button>
            </div>
        </div>
        <div class="flex flex-col w-full space-x-0 space-y-6 sm:flex-row sm:space-y-0 sm:space-x-6">
            <div class="flex flex-col items-start justify-start flex-1 w-full">
                <div class="flex flex-row items-center justify-between w-full pb-2">
                    <button type="button" @click="exportExcel()"
                        class="flex flex-row items-center justify-center w-32 h-10 text-sm border rounded-md border-mainTextGray text-mainAdminTextGrayDark bg-mainLight hover:bg-gray-200">
                        <span>匯出名冊</span>
                    </button>
                    <div class="flex flex-row space-x-4 text-mainAdminTextGrayDark">
                        <div class="flex flex-row space-x-2">
                            <span>一星：</span>
                            <span x-text="rankCount.hasOwnProperty('一星')?rankCount['一星']:0"></span>
                        </div>
                        <div class="flex flex-row space-x-2">
                            <span>二星：</span>
                            <span x-text="rankCount.hasOwnProperty('二星')?rankCount['二星']:0"></span>
                        </div>
                        <div class="flex flex-row space-x-2">
                            <span>三星：</span>
                            <span x-text="rankCount.hasOwnProperty('三星')?rankCount['三星']:0"></span>
                        </div>
                    </div>
                </div>
                <table class="w-full bg-white border shadow-lg text-mainAdminTextGrayDark" id="unitTable">
                    <thead>
                        <tr class="text-white border-b rounded-t bg-mainGrayDark">
                            <th class="p-2 font-bold border-r last:border-r-0">序號</th>
                            <th class="p-2 font-bold border-r last:border-r-0">所在縣市</th>
                            <th class="p-2 font-bold border-r last:border-r-0">社區名稱 \ 地圖查詢 <i
                                    class="fas fa-search-location"></i></th>
                            <th class="p-2 font-bold border-r last:border-r-0">星等</th>
                        </tr>
                    </thead>
                    <tbody>
                        <template x-if="unitDataList.length>0">
                            <template x-for="(unitData,unitIndex) in unitDataList">
                                <tr class="bg-white border-b odd:bg-gray-100 last:border-b-0 last:rounded-b">
                                    <td class="px-4 py-2 text-center border-r last:border-r-0"
                                        x-text="startIndex + unitIndex">
                                    </td>
                                    <td class="px-4 py-2 text-center border-r last:border-r-0"
                                        x-text="unitData.county ? unitData.county.name : ''">
                                    </td>
                                    <td class="px-4 py-2 text-center border-r last:border-r-0">
                                        <div class="flex flex-row space-x-2 text-mainBlueDark">
                                            <a :href="'/dc/show-unit/' + unitData.id" x-text="unitData.name"></a>
                                            <button type="button" @click="searchMap(unitData.name)"
                                                class="text-xs text-white bg-mainBlueDark py-0.5 px-2 rounded flex space-x-1 flex-row justify-center items-center">
                                                <i class="ml-1 i-fa6-solid-magnifying-glass-location"></i>
                                                <span>查詢</span>
                                            </button>
                                        </div>
                                    </td>
                                    <td class="px-4 py-2 text-center border-r last:border-r-0" x-text="unitData.rank">
                                    </td>
                                </tr>
                            </template>
                        </template>
                        <template x-if="unitDataList.length==0">
                            <tr class="bg-white border-b odd:bg-gray-100 last:border-b-0 last:rounded-b">
                                <td colspan="3"
                                    class="px-4 py-2 text-center border-r last:border-r-0 text-mainTextGray">
                                    無資料
                                </td>
                            </tr>
                        </template>
                    </tbody>
                </table>
            </div>
            <div class="flex-1 w-full">
                <div class="relative flex flex-col overflow-hidden bg-white border rounded">
                    <div class="p-4 bg-mainLight text-mainAdminTextGrayDark">
                        地圖
                    </div>
                    <div id="map" class="w-full h-[70vh] rounded-b" style="width: 100%; height: 70vh;"></div>
                </div>
            </div>
        </div>
        <div class="flex justify-center w-full" x-html="pagination">
        </div>
    </div>
</div>

@endsection

@section('js')
@endsection