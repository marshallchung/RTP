@extends('admin.layouts.dashboard', [
'heading' => '編輯韌性社區',
'breadcrumbs' => [
['推動韌性社區', route('admin.dc-units.index')],
'編輯'
]
])

@section('title', $data->title)

@section('inner_content')
<div x-data="{
    countyList:{{ json_encode($countyList) }},
    townList:{},
    villageList:{},
    township:'{{ $data->township }}',
    village:'{{ $data->village }}',
    rank:'{{ $data->rank }}',
    rank_started_date:'{{ $data->rank_started_date }}',
    rank_year:'{{ $data->rank_year }}',
    date_extension:{{ $data->date_extension == '1'?'true':'false' }},
    extension_date:'{{ $data->extension_date===null?'':$data->extension_date }}',
    showDeleteModel:false,
    showExpiredDate:false,
    expired_date:'',
    showExtendDate:false,
    extend_date:'',
    showDate(){
        if(!isNaN(Date.parse(this.rank_started_date))){
            this.showExpiredDate=true;
            var rank_year = parseInt(this.rank_year);
            var date_array=this.rank_started_date.split('-');
            this.expired_date = (parseInt(date_array[0])+rank_year) + '/' + date_array[1] + '/' + date_array[2];
            if(this.date_extension){
                this.showExtendDate=true;
                if(this.extension_date.length>=10){
                    date_array=this.extension_date.split('-');
                    this.extend_date = (parseInt(date_array[0])+3) + '/' + date_array[1] + '/' + date_array[2];
                }else{
                    this.extend_date = (parseInt(date_array[0])+rank_year+3) + '/' + date_array[1] + '/' + date_array[2];
                }
                console.log('extend_date: ' + this.extend_date);
            }
        }else{
            this.showExpiredDate=false;
            this.showExtendDate=false;
        }
    },
    getTownLst(renew=false){
        if(renew){
            this.township='';
            this.village='';
        }
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
                if(This.township!=''){
                    setTimeout(function(){
                        document.getElementById('township').value=This.township;
                        This.getVillageLst();
                    },50);
                }
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
                    if(This.village!=''){
                        setTimeout(function(){
                            document.getElementById('village').value=This.village;
                        },50);
                    }
                });
            }
        }
    },
}" class="flex flex-row items-start justify-start w-full" x-init="$nextTick(() => {
    getTownLst();
    showDate();
    const removeFiles=document.querySelectorAll('.js-remove-file');
    if(removeFiles && removeFiles.length>0){
        for(fileIdx in removeFiles){
            removeFiles[fileIdx].onclick = function(event){
                event.preventDefault();
                var removedFilesInput = document.getElementById('js-removed-files');
                var removedFiles = JSON.parse(removedFilesInput.value);
                var file = event.target.closest('.well');
                var id = file.dataset.id;
                removedFiles.push(id);
                removedFilesInput.value=JSON.stringify(removedFiles);
                return file.remove();
            };
        }
    }
 })">
    <div class="px-4 md:w-67/100 xl:w-75/100">
        <div class="relative mb-6 bg-white border border-gray-200 rounded-sm">
            <div class="relative px-5 pt-3 pb-2 bg-gray-100 border-b-2 border-gray-200 text-mainAdminTextGrayDark">編輯
            </div>
            <div class="p-5 m-0 bg-white">
                {!! Form::model($data, ['route' => ['admin.dc-units.update', $data->id], 'method' => 'put', 'files' =>
                true,'class'=>'flex flex-col space-y-6 -mt-6 w-full items-start justify-start']) !!}
                <div class="flex flex-row flex-wrap w-full text-center">
                    {!! Form::label('name', '社區名稱') !!}
                    {!! Form::text('name', null, ['class' => 'h-12 px-4 border-gray-300 rounded-md shadow-sm
                    focus:border-cyan-300 focus:ring focus:ring-cyan-200 focus:ring-opacity-50 w-full', 'required']) !!}
                </div>

                <div class="flex flex-row flex-wrap w-full text-center">
                    <label for="population">社區居住人數</label>
                    <input
                        class="w-full h-12 px-4 border-gray-300 rounded-md shadow-sm focus:border-cyan-300 focus:ring focus:ring-cyan-200 focus:ring-opacity-50"
                        required="" name="population" type="number" id="population" value="{{ $data->population }}">
                </div>

                <div class="flex flex-row flex-wrap w-full text-center">
                    {!! Form::label('pattern', '社區類型') !!}
                    {!! Form::select('pattern', [
                    null => null,
                    '都市型' => '都市型',
                    '鄉村型' => '鄉村型',
                    ], null, ['class' => 'h-12 px-4 border-gray-300 rounded-md shadow-sm focus:border-cyan-300
                    focus:ring
                    focus:ring-cyan-200 focus:ring-opacity-50 w-full']) !!}
                </div>

                <div class="flex flex-row flex-wrap w-full text-center">
                    {!! Form::label('county_id', '所在縣市') !!}
                    {!! Form::select('county_id', $counties, $data->county_id, ['@change'=>'getTownLst','class' =>
                    'h-12 px-4 border-gray-300
                    rounded-md shadow-sm focus:border-cyan-300 focus:ring focus:ring-cyan-200 focus:ring-opacity-50
                    w-full']) !!}
                </div>

                <div class="flex flex-row flex-wrap w-full text-center">
                    <label for="township">鄉鎮市區</label>
                    <select @change="getVillageLst"
                        class="w-full h-12 px-4 border-gray-300 rounded-md shadow-sm focus:border-cyan-300 focus:ring focus:ring-cyan-200 focus:ring-opacity-50"
                        id="township" name="township" value="{{ $data->township }}" required>
                        <option value="">鄉鎮市區</option>
                        <template x-for="(town_name, town_code) in townList" :key="'town_' + town_code">
                            <option :value="town_name" x-text="town_name"></option>
                        </template>
                    </select>
                </div>

                <div class="flex flex-row flex-wrap w-full text-center">
                    <label for="village">村里</label>
                    <select
                        class="w-full h-12 px-4 border-gray-300 rounded-md shadow-sm focus:border-cyan-300 focus:ring focus:ring-cyan-200 focus:ring-opacity-50"
                        id="village" name="village" value="{{ $data->village }}" required>
                        <option value="">村里</option>
                        <template x-for="(village_name, village_code) in villageList" :key="'village_' + village_code">
                            <option :value="village_name" x-text="village_name"></option>
                        </template>
                    </select>
                </div>

                <div class="flex flex-row flex-wrap w-full text-center">
                    {!! Form::label('location', '社區概略範圍(選填)') !!}
                    {!! Form::text('location', null, ['class' => 'h-12 px-4 border-gray-300 rounded-md shadow-sm
                    focus:border-cyan-300 focus:ring focus:ring-cyan-200 focus:ring-opacity-50 w-full']) !!}
                </div>

                <div class="flex flex-row flex-wrap w-full text-center">
                    {!! Form::label('files[]', '社區概略範圍示意圖') !!}
                    @if(class_basename($data->files) === 'Collection')
                    {!! Form::hidden('removed_files', '[]', ['id' => 'js-removed-files']) !!}
                    <div class="mb-6 xl:w-full">
                        @foreach($data->files as $file)
                        @if ($file->memo == 'dc-location')
                        <div class="flex flex-row items-center justify-start w-full p-2 bg-mainLight well"
                            data-id="{{ $file->id }}">
                            <a href="/{{ $file->path }}" target="_blank" class="flex-1 text-left text-mainBlueDark">{{
                                $file->name }}</a>
                            <span
                                class="px-4 text-sm text-white rounded cursor-pointer py-1.5 js-remove-file bg-rose-600 hover:bg-rose-500">刪除</span>
                        </div>
                        @endif
                        @endforeach
                    </div>
                    @endif
                    {!! Form::file('files[]', ['multiple' => true,'accept'=>'.pdf, .doc, .docx,
                    .jpg, .jpeg, .png, .gif, .zip, .rar, .txt, .csv, .xlsx, .odf, .mp4, .mov']) !!}
                </div>

                <div class="flex flex-row flex-wrap w-full space-x-6 text-center">
                    {!! Form::label('is_experienced', '過去是否曾推動過防災社區') !!}
                    <div class="flex flex-row flex-wrap space-x-2 text-center">
                        <?php
                        $checked = $data->is_experienced == '1'?true:false;
                        ?>
                        <label class="radio-inline">{!! Form::radio('is_experienced', '1', $checked, ['class' =>
                            'border-gray-300 rounded-full bg-white text-mainCyanDark']) !!} 是</label>
                        <label class="radio-inline">{!! Form::radio('is_experienced', '0', !$checked, ['class' =>
                            'border-gray-300 rounded-full bg-white text-mainCyanDark']) !!} 否</label>
                    </div>
                </div>

                <div class="flex flex-row flex-wrap w-full space-x-6 text-center">
                    {!! Form::label('within_plan', '計畫內') !!}
                    <div class="flex flex-row flex-wrap space-x-2 text-center">
                        <?php
                        $checked = $data->within_plan == '1'?true:false;
                        ?>
                        <label class="radio-inline">{!! Form::radio('within_plan', '1', $checked, ['class' =>
                            'border-gray-300 rounded-full bg-white text-mainCyanDark', 'required']) !!} 是</label>
                        <label class="radio-inline">{!! Form::radio('within_plan', '0', !$checked, ['class' =>
                            'border-gray-300 rounded-full bg-white text-mainCyanDark', 'required']) !!} 否</label>
                    </div>
                </div>
                <div class="flex flex-row flex-wrap w-full space-x-6 text-center">
                    {!! Form::label('native', '原民地區') !!}
                    <div class="flex flex-row flex-wrap space-x-2 text-center">
                        <?php
                            $checked = $data->native == '1'?true:false;
                        ?>
                        <label class="radio-inline">{!! Form::radio('native', '1', $checked, ['class' =>
                            'border-gray-300 rounded-full bg-white text-mainCyanDark']) !!} 是</label>
                        <label class="radio-inline">{!! Form::radio('native', '0', !$checked, ['class' =>
                            'border-gray-300 rounded-full bg-white text-mainCyanDark']) !!} 否</label>
                    </div>
                </div>
                <div class="flex flex-row flex-wrap w-full text-center">
                    {!! Form::label('environment', '社區環境概述(選填)') !!}
                    {!! Form::text('environment', null, ['class' => 'h-12 px-4 border-gray-300 rounded-md shadow-sm
                    focus:border-cyan-300 focus:ring focus:ring-cyan-200 focus:ring-opacity-50 w-full']) !!}
                </div>

                <div class="flex flex-row flex-wrap w-full text-center">
                    {!! Form::label('risk', '社區災害潛勢與風險概述(選填)') !!}
                    {!! Form::text('risk', null, ['class' => 'h-12 px-4 border-gray-300 rounded-md shadow-sm
                    focus:border-cyan-300 focus:ring focus:ring-cyan-200 focus:ring-opacity-50 w-full']) !!}
                </div>

                <div class="flex flex-row flex-wrap w-full text-center">
                    {!! Form::label('manager', '韌性社區負責人姓名') !!}
                    {!! Form::text('manager', null, ['class' => 'h-12 px-4 border-gray-300 rounded-md shadow-sm
                    focus:border-cyan-300 focus:ring focus:ring-cyan-200 focus:ring-opacity-50 w-full', 'required']) !!}
                </div>

                <div class="flex flex-row flex-wrap w-full text-center">
                    {!! Form::label('phone', '韌性社區負責人電話') !!}
                    {!! Form::text('phone', null, ['class' => 'h-12 px-4 border-gray-300 rounded-md shadow-sm
                    focus:border-cyan-300 focus:ring focus:ring-cyan-200 focus:ring-opacity-50 w-full', 'required']) !!}
                </div>

                <div class="flex flex-row flex-wrap w-full text-center">
                    {!! Form::label('email', '韌性社區負責人Email') !!}
                    {!! Form::text('email', null, ['class' => 'h-12 px-4 border-gray-300 rounded-md shadow-sm
                    focus:border-cyan-300 focus:ring focus:ring-cyan-200 focus:ring-opacity-50 w-full']) !!}
                </div>

                <div class="flex flex-row flex-wrap w-full text-center">
                    {!! Form::label('manager_address', '韌性社區負責人地址') !!}
                    {!! Form::text('manager_address', null, ['class' => 'h-12 px-4 border-gray-300 rounded-md shadow-sm
                    focus:border-cyan-300 focus:ring focus:ring-cyan-200 focus:ring-opacity-50 w-full', 'required']) !!}
                </div>

                <div class="flex flex-row flex-wrap w-full text-center">
                    {!! Form::label('manager_position', '擔任社區職務') !!}
                    {!! Form::text('manager_position', null, ['class' => 'h-12 px-4 border-gray-300 rounded-md shadow-sm
                    focus:border-cyan-300 focus:ring focus:ring-cyan-200 focus:ring-opacity-50 w-full', 'required']) !!}
                </div>

                <div class="flex flex-row flex-wrap w-full text-center">
                    {!! Form::label('dp_name', '防災士姓名') !!}
                    {!! Form::text('dp_name', null, ['class' => 'h-12 px-4 border-gray-300 rounded-md shadow-sm
                    focus:border-cyan-300 focus:ring focus:ring-cyan-200 focus:ring-opacity-50 w-full']) !!}
                </div>

                <div class="flex flex-row flex-wrap w-full text-center">
                    {!! Form::label('dp_phone', '防災士電話') !!}
                    {!! Form::text('dp_phone', null, ['class' => 'h-12 px-4 border-gray-300 rounded-md shadow-sm
                    focus:border-cyan-300 focus:ring focus:ring-cyan-200 focus:ring-opacity-50 w-full']) !!}
                </div>

                <div class="flex flex-row flex-wrap w-full text-center">
                    {!! Form::label('rank', '星等') !!}
                    @if (Auth::user()->origin_role>2)
                    {!! Form::text('rank', null, ['class' => 'h-12 px-4 border-gray-300 rounded-md shadow-sm
                    focus:border-cyan-300 focus:ring focus:ring-cyan-200 focus:ring-opacity-50 w-full', 'disabled']) !!}
                    @else
                    {!! Form::select('rank', $ranks, null, ['class' => 'h-12 px-4 border-gray-300 rounded-md shadow-sm
                    focus:border-cyan-300 focus:ring focus:ring-cyan-200 focus:ring-opacity-50
                    w-full','x-model'=>'rank','@change'=>'showDate']) !!}
                    @endif
                </div>

                <div class="flex flex-row flex-wrap text-center">
                    {!! Form::label('rank_started_date', '星等生效日期') !!}
                    @if (Auth::user()->origin_role>2)
                    <input
                        class="w-full h-12 px-4 border-gray-300 rounded-md shadow-sm focus:border-cyan-300 focus:ring focus:ring-cyan-200 focus:ring-opacity-50"
                        x-model="rank_started_date" name="rank_started_date" type="date" id="rank_started_date"
                        disabled>
                    @else
                    <input
                        class="w-full h-12 px-4 border-gray-300 rounded-md shadow-sm focus:border-cyan-300 focus:ring focus:ring-cyan-200 focus:ring-opacity-50"
                        x-model="rank_started_date" @change="showDate" name="rank_started_date" type="date"
                        id="rank_started_date" x-bind:required="rank!=='未審查'">
                    @endif
                </div>
                <div class="flex flex-row flex-wrap text-center">
                    {!! Form::label('rank_year', '星等有效年限') !!}
                    @if (Auth::user()->origin_role>2)
                    {!! Form::text('rank_year', null, ['class' => 'h-12 px-4 border-gray-300 rounded-md shadow-sm
                    focus:border-cyan-300 focus:ring focus:ring-cyan-200 focus:ring-opacity-50 w-full', 'disabled']) !!}
                    @else
                    {!! Form::select('rank_year', [1=>1,2=>2,3=>3,4=>4,5=>5], null, ['class' => 'h-12 px-4
                    border-gray-300 rounded-md
                    shadow-sm
                    focus:border-cyan-300 focus:ring focus:ring-cyan-200 focus:ring-opacity-50
                    w-full','x-model'=>'rank_year','@change'=>'showDate']) !!}
                    @endif
                </div>
                <div x-show="showExpiredDate" class="flex flex-row flex-wrap text-center text-rose-600">
                    <span>星等截止日期：</span>
                    <span x-text="expired_date"></span>
                </div>
                <div class="flex flex-row flex-wrap space-x-6 text-center">
                    {!! Form::label('date_extension', '星等生效日期是否展延') !!}
                    <div class="flex flex-row flex-wrap space-x-2 text-center">
                        <?php
                            $checked = $data->date_extension == '1'?true:false;
                        ?>
                        @if (Auth::user()->origin_role>2)
                        <label class="radio-inline">{!! Form::radio('date_extension', '1', $checked, ['class'
                            =>
                            'border-gray-300 rounded-full bg-white
                            text-mainCyanDark','disabled','x-model'=>'date_extension']) !!} 是</label>
                        <label class="radio-inline">{!! Form::radio('date_extension', '0', !$checked, ['class'
                            =>
                            'border-gray-300 rounded-full bg-white
                            text-mainCyanDark','disabled','x-model'=>'date_extension']) !!} 否</label>
                        @else
                        <label class="radio-inline">{!! Form::radio('date_extension', '1', $checked, ['class' =>
                            'border-gray-300 rounded-full bg-white
                            text-mainCyanDark','@change'=>'showDate','x-model'=>'date_extension']) !!} 是</label>
                        <label class="radio-inline">{!! Form::radio('date_extension', '0', !$checked, ['class' =>
                            'border-gray-300 rounded-full bg-white
                            text-mainCyanDark','@change'=>'showDate','x-model'=>'date_extension']) !!} 否</label>
                        @endif

                    </div>
                </div>
                <div x-show="showExtendDate" class="flex flex-row flex-wrap text-center">
                    {!! Form::label('extension_date', '星等展延生效日期') !!}
                    @if (Auth::user()->origin_role>2)
                    <input
                        class="w-full h-12 px-4 border-gray-300 rounded-md shadow-sm focus:border-cyan-300 focus:ring focus:ring-cyan-200 focus:ring-opacity-50"
                        x-model="extension_date" name="extension_date" type="date" id="extension_date" disabled>
                    @else
                    <input
                        class="w-full h-12 px-4 border-gray-300 rounded-md shadow-sm focus:border-cyan-300 focus:ring focus:ring-cyan-200 focus:ring-opacity-50"
                        x-model="extension_date" @change="showDate" name="extension_date" type="date"
                        id="extension_date">
                    @endif

                </div>
                <div x-show="showExtendDate" class="flex flex-row flex-wrap text-center text-rose-600">
                    <span>星等展延日期：</span>
                    <span x-text="extend_date"></span>
                </div>
                {!! Form::submit('送出', ['class' => 'w-full text-white flex justify-center items-center h-10
                bg-mainCyanDark
                rounded']) !!}
                {!! Form::close() !!}

                @if(!auth()->user()->type || auth()->user()->type == 'civil')
                <a @click="showDeleteModel=true" data-toggle="modal" data-target="#js-delete-modal"
                    class="flex items-center justify-center w-full h-10 text-white bg-orange-500 rounded cursor-pointer hover:bg-orange-400">刪除</a>
                @endif
            </div>
            @if(!auth()->user()->type || auth()->user()->type == 'civil')
            {!! Form::model($data, ['method' => 'DELETE', 'route' => ['admin.dc-units.destroy', $data->id]]) !!}
            @include('admin.layouts.partials.genericDeleteForm')
            {!! Form::close() !!}
            @endif
        </div>
    </div>
</div>
@endsection