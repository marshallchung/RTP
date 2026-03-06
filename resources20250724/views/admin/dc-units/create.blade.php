@extends('admin.layouts.dashboard', [
'heading' => '推動韌性社區 > 查詢與管理韌性社區資料',
'breadcrumbs' => [
['查詢與管理韌性社區資料', route('admin.dc-units.index')],
'新增'
]
])

@section('title', '查詢與管理韌性社區資料')

@section('inner_content')
<div x-data="{
    countyList:{{ json_encode($countyList) }},
    townList:{},
    villageList:{},
    showDeleteModel:false,
    getTownLst(){
        let This=this;
        this.townList={};
        this.villageList={};
        var city = document.getElementById('county_id');
        city = city.options[city.selectedIndex].text;
        if(city!==''){
            fetch('https://api.nlsc.gov.tw/other/ListTown1/' + this.countyList[city])
            .then(res => res.text())
            .then(res => {
                let data = JSON.parse(xml2json(parseXml(res), ''));
                data.townItems.townItem.forEach((one_town) => {
                    This.townList[one_town.towncode01]=one_town.townname;
                });
            });
        }
    },
    getVillageLst(){
        let This=this;
        this.villageList={};
        var city = document.getElementById('county_id');
        var township = document.getElementById('township');
        township = township.options[township.selectedIndex].text;
        if(township!==''){
            var town_code='';
            for (const [key, value] of Object.entries(this.townList)) {
                if(value==township){
                    town_code=key;
                }
            }
            if(town_code!==''){
                city = city.options[city.selectedIndex].text;
                fetch('https://api.nlsc.gov.tw/other/ListVillage/' + this.countyList[city] + '/' + town_code)
                .then(res => res.text())
                .then(res => {
                    let data = JSON.parse(xml2json(parseXml(res), ''));
                    data.villages.village.forEach((one_village) => {
                        This.villageList[one_village.villageId]=one_village.villageName;
                    });
                });
            }
        }
    },
}" class="flex flex-row items-start justify-start w-full" x-init="$nextTick(() => {
    getTownLst();
    getVillageLst();
 })">
    {!! Form::open(['method' => 'POST', 'route' => 'admin.dc-units.store', 'files' => true,'class'=>'flex flex-row
    w-full items-start justify-start px-4']) !!}
    <div class="relative w-full max-w-4xl">
        <div class="relative w-full mb-6 bg-white border border-gray-200 rounded-sm">
            <div class="relative px-5 pt-3 pb-2 bg-gray-100 border-b-2 border-gray-200 text-mainAdminTextGrayDark">新增
            </div>
            <div class="flex flex-col w-full p-5 m-0 space-y-6 bg-white">
                <div class="flex flex-row flex-wrap text-center">
                    {!! Form::label('name', '社區名稱') !!}
                    {!! Form::text('name', null, ['class' => 'h-12 px-4 border-gray-300 rounded-md shadow-sm
                    focus:border-cyan-300 focus:ring focus:ring-cyan-200 focus:ring-opacity-50 w-full', 'required']) !!}
                </div>

                <div class="flex flex-row flex-wrap text-center">
                    <label for="population">社區居住人數</label>
                    <input
                        class="w-full h-12 px-4 border-gray-300 rounded-md shadow-sm focus:border-cyan-300 focus:ring focus:ring-cyan-200 focus:ring-opacity-50"
                        required="" name="population" type="number" id="population" value="{{ request('population') }}">
                </div>

                <div class="flex flex-row flex-wrap text-center">
                    {!! Form::label('pattern', '社區類型') !!}
                    {!! Form::select('pattern', [
                    null => null,
                    '都市型' => '都市型',
                    '鄉村型' => '鄉村型',
                    ], null, ['class' => 'h-12 px-4 border-gray-300 rounded-md shadow-sm focus:border-cyan-300
                    focus:ring
                    focus:ring-cyan-200 focus:ring-opacity-50 w-full']) !!}
                </div>

                <div class="flex flex-row flex-wrap text-center">
                    {!! Form::label('county_id', '所在縣市') !!}
                    {!! Form::select('county_id', $counties, request('county_id'), ['@change'=>'getTownLst','class' =>
                    'h-12 px-4
                    border-gray-300
                    rounded-md shadow-sm focus:border-cyan-300 focus:ring focus:ring-cyan-200 focus:ring-opacity-50
                    w-full', 'required']) !!}
                </div>

                <div class="flex flex-row flex-wrap text-center">
                    <label for="township">鄉鎮市區</label>
                    <select @change="getVillageLst"
                        class="w-full h-12 px-4 border-gray-300 rounded-md shadow-sm focus:border-cyan-300 focus:ring focus:ring-cyan-200 focus:ring-opacity-50"
                        id="township" name="township" required>
                        <option value="">鄉鎮市區</option>
                        <template x-for="(town_name, town_code) in townList" :key="'town_' + town_code">
                            <option :value="town_name" x-text="town_name"></option>
                        </template>
                    </select>
                </div>

                <div class="flex flex-row flex-wrap text-center">
                    <label for="village">村里</label>
                    <select
                        class="w-full h-12 px-4 border-gray-300 rounded-md shadow-sm focus:border-cyan-300 focus:ring focus:ring-cyan-200 focus:ring-opacity-50"
                        id="village" name="village" required>
                        <option value="">村里</option>
                        <template x-for="(village_name, village_code) in villageList" :key="'village_' + village_code">
                            <option :value="village_name" x-text="village_name"></option>
                        </template>
                    </select>
                </div>

                <div class="flex flex-row flex-wrap text-center">
                    {!! Form::label('location', '社區概略範圍') !!}
                    {!! Form::text('location', null, ['class' => 'h-12 px-4 border-gray-300 rounded-md shadow-sm
                    focus:border-cyan-300 focus:ring focus:ring-cyan-200 focus:ring-opacity-50 w-full']) !!}
                </div>

                <div class="flex flex-row flex-wrap text-center">
                    {!! Form::label('files[]', '社區概略範圍示意圖') !!}
                    {!! Form::file('files[]', ['multiple' => true,'accept'=>'.pdf, .doc, .docx,
                    .jpg, .jpeg, .png, .gif, .zip, .rar, .txt, .csv, .xlsx, .odf, .mp4, .mov']) !!}
                </div>

                <div class="flex flex-row flex-wrap space-x-6 text-center">
                    {!! Form::label('is_experienced', '過去是否曾推動過防災社區') !!}
                    <div class="flex flex-row flex-wrap space-x-2 text-center">
                        <label class="radio-inline">{!! Form::radio('is_experienced', '1',false, ['class' =>
                            'border-gray-300
                            rounded-full bg-white text-mainCyanDark']) !!}是</label>
                        <label class="radio-inline">{!! Form::radio('is_experienced', '0',false, ['class' =>
                            'border-gray-300
                            rounded-full bg-white text-mainCyanDark']) !!}否</label>
                    </div>
                </div>

                <div class="flex flex-row flex-wrap space-x-6 text-center">
                    {!! Form::label('within_plan', '計畫內') !!}
                    <div class="flex flex-row flex-wrap space-x-2 text-center">
                        <label class="radio-inline">{!! Form::radio('within_plan', '1',false, ['class' =>
                            'border-gray-300
                            rounded-full bg-white text-mainCyanDark', 'required']) !!}是</label>
                        <label class="radio-inline">{!! Form::radio('within_plan', '0',false, ['class' =>
                            'border-gray-300
                            rounded-full bg-white text-mainCyanDark', 'required']) !!}否</label>
                    </div>
                </div>

                <div class="flex flex-row flex-wrap space-x-6 text-center">
                    {!! Form::label('native', '原民地區') !!}
                    <div class="flex flex-row flex-wrap space-x-2 text-center">
                        <label class="radio-inline">{!! Form::radio('native', '1',false, ['class' => 'border-gray-300
                            rounded-full bg-white text-mainCyanDark']) !!}是</label>
                        <label class="radio-inline">{!! Form::radio('native', '0',false, ['class' => 'border-gray-300
                            rounded-full bg-white text-mainCyanDark']) !!}否</label>
                    </div>
                </div>

                <div class="flex flex-row flex-wrap text-center">
                    {!! Form::label('environment', '社區環境概述') !!}
                    {!! Form::text('environment', null, ['class' => 'h-12 px-4 border-gray-300 rounded-md shadow-sm
                    focus:border-cyan-300 focus:ring focus:ring-cyan-200 focus:ring-opacity-50 w-full']) !!}
                </div>

                <div class="flex flex-row flex-wrap text-center">
                    {!! Form::label('risk', '社區災害潛勢與風險概述') !!}
                    {!! Form::text('risk', null, ['class' => 'h-12 px-4 border-gray-300 rounded-md shadow-sm
                    focus:border-cyan-300 focus:ring focus:ring-cyan-200 focus:ring-opacity-50 w-full']) !!}
                </div>

                <div class="flex flex-row flex-wrap text-center">
                    {!! Form::label('manager', '韌性社區負責人姓名') !!}
                    {!! Form::text('manager', null, ['class' => 'h-12 px-4 border-gray-300 rounded-md shadow-sm
                    focus:border-cyan-300 focus:ring focus:ring-cyan-200 focus:ring-opacity-50 w-full', 'required']) !!}
                </div>

                <div class="flex flex-row flex-wrap text-center">
                    {!! Form::label('phone', '韌性社區負責人電話') !!}
                    {!! Form::text('phone', null, ['class' => 'h-12 px-4 border-gray-300 rounded-md shadow-sm
                    focus:border-cyan-300 focus:ring focus:ring-cyan-200 focus:ring-opacity-50 w-full', 'required']) !!}
                </div>

                <div class="flex flex-row flex-wrap text-center">
                    {!! Form::label('email', '韌性社區負責人Email') !!}
                    {!! Form::text('email', null, ['class' => 'h-12 px-4 border-gray-300 rounded-md shadow-sm
                    focus:border-cyan-300 focus:ring focus:ring-cyan-200 focus:ring-opacity-50 w-full']) !!}
                </div>

                <div class="flex flex-row flex-wrap text-center">
                    {!! Form::label('manager_address', '韌性社區負責人地址') !!}
                    {!! Form::text('manager_address', null, ['class' => 'h-12 px-4 border-gray-300 rounded-md shadow-sm
                    focus:border-cyan-300 focus:ring focus:ring-cyan-200 focus:ring-opacity-50 w-full', 'required']) !!}
                </div>

                <div class="flex flex-row flex-wrap text-center">
                    {!! Form::label('manager_position', '擔任社區職務') !!}
                    {!! Form::text('manager_position', null, ['class' => 'h-12 px-4 border-gray-300 rounded-md shadow-sm
                    focus:border-cyan-300 focus:ring focus:ring-cyan-200 focus:ring-opacity-50 w-full', 'required']) !!}
                </div>

                <div class="flex flex-row flex-wrap text-center">
                    {!! Form::label('dp_name', '防災士姓名') !!}
                    {!! Form::text('dp_name', null, ['class' => 'h-12 px-4 border-gray-300 rounded-md shadow-sm
                    focus:border-cyan-300 focus:ring focus:ring-cyan-200 focus:ring-opacity-50 w-full']) !!}
                </div>

                <div class="flex flex-row flex-wrap text-center">
                    {!! Form::label('dp_phone', '防災士電話') !!}
                    {!! Form::text('dp_phone', null, ['class' => 'h-12 px-4 border-gray-300 rounded-md shadow-sm
                    focus:border-cyan-300 focus:ring focus:ring-cyan-200 focus:ring-opacity-50 w-full']) !!}
                </div>

                <div class="flex flex-row flex-wrap text-center">
                    {!! Form::label('rank', '星等') !!}
                    @if (Auth::user()->origin_role>2)
                    {!! Form::text('rank', null, ['class' => 'h-12 px-4 border-gray-300 rounded-md shadow-sm
                    focus:border-cyan-300 focus:ring focus:ring-cyan-200 focus:ring-opacity-50 w-full', 'disabled']) !!}
                    @else
                    {!! Form::select('rank', $ranks, null, ['class' => 'h-12 px-4 border-gray-300 rounded-md shadow-sm
                    focus:border-cyan-300 focus:ring focus:ring-cyan-200 focus:ring-opacity-50 w-full', 'required']) !!}
                    @endif
                </div>

                <div class="flex flex-row flex-wrap text-center">
                    {!! Form::label('rank_started_date', '星等生效日期') !!}
                    @if (Auth::user()->origin_role>2)
                    {!! Form::text('rank_started_date', null, ['class' => 'h-12 px-4 border-gray-300 rounded-md
                    shadow-sm
                    focus:border-cyan-300 focus:ring focus:ring-cyan-200 focus:ring-opacity-50 w-full', 'disabled']) !!}
                    @else
                    {!! Form::date('rank_started_date', null, ['class' => 'h-12 px-4 border-gray-300 rounded-md
                    shadow-sm
                    focus:border-cyan-300 focus:ring focus:ring-cyan-200 focus:ring-opacity-50 w-full']) !!}
                    @endif
                </div>
                <div class="flex flex-row flex-wrap text-center">
                    {!! Form::label('rank_year', '星等有效年限') !!}
                    @if (Auth::user()->origin_role>2)
                    {!! Form::text('rank_year', null, ['class' => 'h-12 px-4 border-gray-300 rounded-md shadow-sm
                    focus:border-cyan-300 focus:ring focus:ring-cyan-200 focus:ring-opacity-50 w-full', 'disabled']) !!}
                    @else
                    {!! Form::select('rank_year', [1=>1,2=>2,3=>3,4=>4,5=>5], null, ['class' => 'h-12 px-4
                    border-gray-300 rounded-md shadow-sm focus:border-cyan-300 focus:ring focus:ring-cyan-200
                    focus:ring-opacity-50 w-full']) !!}
                    @endif
                </div>

                {!! Form::submit('送出', ['class' => 'w-full text-white flex justify-center items-center h-10
                bg-mainCyanDark rounded']) !!}
            </div>
        </div>
    </div>
</div>
{!! Form::close() !!}
@endsection