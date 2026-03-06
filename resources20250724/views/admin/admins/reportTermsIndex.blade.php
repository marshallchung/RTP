<?php $user = Auth::user(); ?>
@extends('admin.layouts.dashboard', [
'heading' => '工作項目管理',

'breadcrumbs' => ['管理']
])

@section('title', '工作項目管理')

@section('inner_content')
<div x-data="{
    loading:false,
    showEditModal:false,
    showDeleteModal:false,
    dp_student_id:null,
    year:'{{ date('Y') }}',
    work_type:'reports',
    topicType:'',
    selectRootTopic:'',
    rootTopic_id:'',
    rootTopic:[],
    type:'',
    addList:[''],
    add_url:'{{ route('admin.admin.createReportTerms') }}',
    yearChange(e){
        var This=this;
        var url='{{ route('admin.admin.getRootTopics') }}?year=' + encodeURIComponent(this.year) + '&work_type=' + encodeURIComponent(this.work_type);
        fetch(url,{
            method:'get',
            headers: {
                'X-Requested-With':'XMLHttpRequest',
                'Accept': '*/*',
            },
        }).then((response)=>{
            if(response.status===200){
                return response.json();
            }
        })
        .then(function (json) {
            This.rootTopic=json;
        })
        .catch(function(error) {
            if (error.status == 422) {
                var errors = json.responseJSON;
                errors.forEach(value => {
                    alert(value);
                });
            }else{
                alert(' 伺服器錯誤: ' + error.message);
            }
        })
        .finally(() => {
            this.loading=false;
        });
    },
    workTypeChange(e){
        document.querySelector('#year').dispatchEvent(new Event('change', { 'bubbles' : true }));
    },
    addOne(){
        this.addList.push('');
    },
    modalData:{
        mode:'',
        show:false,
        title:'',
        type:'',
        id:'',
    },
    closeModal(){
        this.modalData.mode='';
        this.modalData.show=false;
        this.modalData.title='';
        this.modalData.type='';
        this.modalData.id='';
    },
    showModel(e){
        this.modalData.mode=e.target.dataset.mode;
        this.modalData.show=true;
        this.modalData.title=e.target.dataset.title;
        this.modalData.type=e.target.dataset.type;
        this.modalData.id=e.target.dataset.id;
    },
    saveNew(e){
        this.loading=true;
        var This=this;
        var token='{{ csrf_token() }}';
        const data=new FormData();
        this.type=document.getElementById('type').value;
        this.work_type=document.getElementById('work_type').value;
        this.selectRootTopic=document.getElementById('selectRootTopic').value;
        this.year=document.getElementById('year').value;
        this.topicType=document.getElementById('topicType').value;
        data.append('type', this.type);
        data.append('topicType', this.topicType);
        data.append('work_type', this.work_type);
        data.append('rootTopic_id', this.selectRootTopic);
        data.append('year', this.year);
        this.addList.forEach((value,index)=> {
            data.append('titles[]', value);
        });
        fetch(this.add_url,{
            method:'POST',
            body:data,
            headers: {
                'X-CSRF-TOKEN': token,
                'X-Requested-With':'XMLHttpRequest',
            },
        })
        .then((response) => {
            if(response.status===200){
                return response.json();
            }
        })
        .then(function (json) {
            alert(json.msg);
            location.reload();
        })
        .catch(function(error) {
            if (error.status == 422) {
                var errors = json.responseJSON;
                errors.forEach(value => {
                    alert(value);
                });
            }else{
                alert('伺服器錯誤: ' + error.message);
            }
        })
        .finally(() => {
            this.loading=false;
        });
    },
    }" class="flex flex-col items-start justify-start w-full p-4 text-mainAdminTextGrayDark" x-init="$nextTick(() =>
    {
        document.querySelector('#work_type').dispatchEvent(new Event('change', { 'bubbles': true }));
    })">
    {!! Form::open(['@submit.prevent'=>'saveNew','method' => 'POST', 'route' => 'admin.admin.createReportTerms', 'id' =>
    'formCreation','class'=>"flex flex-col items-start justify-start w-full max-w-5xl pace-y-4"]) !!}
    <div class="flex flex-row flex-wrap w-full">
        <div class="relative w-full mb-6 bg-white border border-gray-200 rounded-sm">
            <div class="relative px-5 pt-3 pb-2 bg-gray-100 border-b-2 border-gray-200 text-mainAdminTextGrayDark">
                指定分類和年度
            </div>
            <div id="form-body" class="flex flex-col w-full p-5 space-y-6 bg-white">
                <div class="form-row">
                    <div class="flex flex-row flex-wrap text-center">
                        <label>工作分類</label>
                        <?php
									$work_types = ['reports' => '計畫執行成果'];
									if ($user->origin_role < 5) {
										$work_types['seasonalReports'] = '縣市季進度管制表';
                                        $work_types['centralReports'] = '成果資料下載';
									}
								?>
                        {!! Form::select('work_type', $work_types, request('work_type', 'reports'),
                        ['@change'=>'workTypeChange','x-model'=>'work_type',
                        'class' => 'h-12 px-4 border-gray-300 rounded-md shadow-sm focus:border-cyan-300 focus:ring
                        focus:ring-cyan-200 focus:ring-opacity-50 w-full', 'id' => 'work_type'
                        ]) !!}
                    </div>
                </div>
                <div class="form-row">
                    <div class="flex flex-row flex-wrap text-center" :class="{'hidden':work_type=='centralReports'}">
                        <label>工作年度</label>
                        <select @change="yearChange" x-model="year"
                            class="w-full h-12 px-4 border-gray-300 rounded-md shadow-sm focus:border-cyan-300 focus:ring focus:ring-cyan-200 focus:ring-opacity-50"
                            id="year" name="year">
                            @for ($i = 2027; $i >= 2023; $i--)
                            <option value="{{ $i }}">{{
                                $i-1911 }}</option>
                            @endfor
                        </select>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="flex flex-row flex-wrap w-full">
        <div class="relative w-full mb-6 bg-white border border-gray-200 rounded-sm">
            <div class="relative px-5 pt-3 pb-2 bg-gray-100 border-b-2 border-gray-200 text-mainAdminTextGrayDark">
                增加工作項目
            </div>
            <div id="form-body" class="flex flex-col w-full p-5 space-y-6 bg-white">
                <div class="form-row">
                    <div class="flex flex-row flex-wrap text-center">
                        {!! Form::label('type', '項目類別') !!}
                        {!! Form::select('type', $types, null, ['x-model'=>'type',
                        'class' => 'h-12 px-4 border-gray-300 rounded-md shadow-sm focus:border-cyan-300 focus:ring
                        focus:ring-cyan-200 focus:ring-opacity-50 w-full',
                        'id' => 'type',
                        ]) !!}
                    </div>
                </div>
                <div x-bind:hidden="type!=='topic'" id="divRootTopic" class="form-row">
                    <div class="flex flex-row flex-wrap text-center">
                        {!! Form::label('rootTopic_id', '根項目') !!}
                        <select x-model="selectRootTopic" id="selectRootTopic" x-model="rootTopic_id"
                            class="w-full h-12 px-4 border-gray-300 rounded-md shadow-sm focus:border-cyan-300 focus:ring focus:ring-cyan-200 focus:ring-opacity-50"
                            name="rootTopic_id">
                            <option value="">-</option>
                            <template x-for="(data_item, index) in rootTopic">
                                <option :value="data_item.id" x-text="data_item.title"></option>
                            </template>
                        </select>
                    </div>
                </div>
                @if ($user->origin_role < 4) <div x-bind:hidden="(type!=='topic' || work_type==='centralReports')"
                    id="divTopicType" class="form-row">
                    <div class="flex flex-row flex-wrap text-center">
                        {!! Form::label('topicType', '工作項目歸屬') !!}
                        <div class="flex flex-row flex-wrap w-full text-center">
                            {!! Form::select('topicType', [
                            'county,district' => '通用',
                            'county' => '僅縣市',
                            'district' => '僅鄉鎮市區',
                            ], null, ['x-model'=>'topicType',
                            'class' => 'h-12 px-4 border-gray-300 rounded-md shadow-sm focus:border-cyan-300
                            focus:ring
                            focus:ring-cyan-200 focus:ring-opacity-50 w-full',
                            'required',
                            ]) !!}
                        </div>
                    </div>
            </div>
            @endif
            <div class="form-row">
                <div id="tableCreate" class="flex flex-row flex-wrap text-center">
                    {!! Form::label('titles[]', '工作項目名稱') !!}
                    <div class="flex flex-col w-full space-y-2 text-center">
                        <template x-for="(data_item, index) in addList" :key="">
                            <input
                                class="w-full h-12 px-4 border-gray-300 rounded-md shadow-sm focus:border-cyan-300 focus:ring focus:ring-cyan-200 focus:ring-opacity-50"
                                x-model="addList[index]" type="text" name="titles[]" required>
                        </template>
                    </div>
                </div>
            </div>
        </div>
        <div class="flex-col items-center justify-center w-full p-5 space-y-4 panel-footer">
            <div class="flex items-center justify-center w-full">
                <button type="button" @click="addOne"
                    class="flex flex-row items-center justify-center h-10 space-x-1 bg-gray-100 border border-gray-300 w-44 hover:bg-gray-50"
                    :class="{'cursor-not-allowed text-mainGray hover:bg-gray-100':addList.length==0,'cursor-pointer text-mainAdminTextGrayDark hover:bg-gray-50':addList.length>0}"
                    x-bind:disabled="addList.length==0">
                    <i class="w-3 h-3 i-fa6-solid-plus"></i>
                    <span>增加一筆</span>
                </button>
            </div>
            <div class="flex flex-row flex-wrap text-center">
                <input id="btnSubmit" type="submit"
                    class="flex items-center justify-center w-full h-10 text-white rounded bg-mainCyanDark"
                    :class="{'cursor-not-allowed bg-mainGray':addList.length==0,'cursor-pointer bg-mainCyanDark':addList.length>0}"
                    value="送出" x-bind:disabled="addList.length==0">
            </div>
        </div>
    </div>
    {!! Form::close() !!}

    <div class="flex flex-row flex-wrap w-full">
        <div class="relative w-full mb-6 bg-white border border-gray-200 rounded-sm">
            <div class="relative px-5 pt-3 pb-2 bg-gray-100 border-b-2 border-gray-200 text-mainAdminTextGrayDark">
                編修工作項目
            </div>
            <div class="flex flex-col w-full p-5 space-y-6 bg-white">
                <div class="flex flex-row flex-wrap text-center">
                    <table id="table" class="w-full border shadow-lg text-mainAdminTextGrayDark">
                        <thead>
                            <tr class="border-b bg-mainLight">
                                <th class="p-2 font-normal text-left border-r last:border-r-0">工作項目</th>
                                <td class="p-2 font-normal text-left border-r last:border-r-0"></td>
                            </tr>
                        </thead>
                        <tbody class="text-content text-mainAdminTextGrayDark">
                            <?php
									$topicTypes = [
										'county,district' => '',
										'county' => '（縣市）',
										'district' => '（鄉鎮市區）',
									];
								?>
                            @foreach ($data as $rootTopic)
                            <tr x-show="(year==='{{ $rootTopic->year }}' && work_type==='{{ $rootTopic->work_type }}') || (work_type==='centralReports' && work_type==='{{ $rootTopic->work_type }}')"
                                class="border-b last:border-b-0">
                                <td class="p-2 text-left border-r last:border-r-0">{{ $rootTopic->title }}</td>
                                <td class="p-2 text-left border-r last:border-r-0">
                                    <div class="flex flex-row items-center justify-center space-x-2">
                                        @if ($user->origin_role < 3) <button type="button" @click="showModel"
                                            class="flex items-center justify-center w-20 text-sm text-white h-9 bg-mainCyanDark hover:bg-teal-400"
                                            data-type="rootTopic" data-mode="edit" data-title="{{ $rootTopic->title }}"
                                            data-id="{{ $rootTopic->id }}">
                                            編輯</button>
                                            <button type="button" @click="showModel"
                                                class="flex items-center justify-center w-20 text-sm text-white h-9 bg-rose-600 hover:bg-rose-500"
                                                data-type="rootTopic" data-mode="delete"
                                                data-title="{{ $rootTopic->title }}"
                                                data-id="{{ $rootTopic->id }}">刪除</button>
                                            @endif
                                    </div>
                                </td>
                            </tr>
                            @foreach ($rootTopic->topics as $topic)
                            @if ($user->origin_role > 2 &&
                            (strpos($topic->type, $user->type) === FALSE ||
                            ($topic->user_id != $user->id &&
                            ($topic->author && $topic->author->origin_role > 2))))
                            <?php continue; ?>
                            @endif
                            <tr x-bind:hidden="(!(work_type==='centralReports' && work_type==='{{ $rootTopic->work_type }}') && !(year=='{{ $rootTopic->year }}' && work_type=='{{ $rootTopic->work_type }}'))"
                                class="border-b last:border-b-0">
                                <td class="p-2 pl-12 text-left border-r last:border-r-0">{{ $topic->title }}{{
                                    $topicTypes[$topic->type] }}
                                </td>
                                <td class="p-2 border-r last:border-r-0">
                                    <div class="flex flex-row items-center justify-center space-x-2">
                                        @if ($user->origin_role < 3 || $topic->unit_id == $user->id)
                                            <button type="button" @click="showModel" data-title="{{ $topic->title }}"
                                                class="flex items-center justify-center w-20 text-sm text-white h-9 bg-mainCyanDark hover:bg-teal-400"
                                                data-type="topic" data-mode="edit"
                                                data-id="{{ $topic->id }}">編輯</button>
                                            <button type="button" @click="showModel" data-title="{{ $topic->title }}"
                                                class="flex items-center justify-center w-20 text-sm text-white h-9 bg-rose-600 hover:bg-rose-500"
                                                data-type="topic" data-mode="delete"
                                                data-id="{{ $topic->id }}">刪除</button>
                                            @endif
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div x-show.transition="modalData.show" id="divEdit"
        class="fixed inset-0 flex-col justify-start items-center pt-[30vh] bg-black/30 z-[10050] hidden"
        :class="{'hidden':!modalData.show,'flex':modalData.show}" tabindex="-1" role="dialog">
        <div @click.away="closeModal()"
            class="z-10 flex flex-col items-center justify-center w-full max-w-xl bg-white rounded-lg shadow-lg text-mainGrayDark">
            <div class="relative flex flex-row items-center justify-center w-full px-6 py-4 rounded-t-lg bg-mainLight">
                <h5 class="flex-1 text-center">
                    <span x-text="modalData.mode === 'edit'?'修改':'刪除'"></span>
                    <span>工作項目</span>
                </h5>
                <button type="button" @click="closeModal" class="absolute top-4 right-6 text-mainAdminTextGray"
                    data-dismiss="modal" aria-label="Close">
                    <span class="btnCloseModal" aria-hidden="true">&times;</span>
                </button>
            </div>
            <template x-if="modalData.mode === 'edit'">
                <form class="flex-col items-center justify-center w-full p-5 space-y-4 lex" id="editForm" method="post"
                    action="{{ route('admin.admin.editReportTerms') }}">
                    <div class="modal-body flex justify-center items-center min-h-[6rem] w-full">
                        {{ csrf_field() }}
                        <input name="id" x-model="modalData.id" type="hidden" />
                        <input name="work_type" x-model="modalData.type" type="hidden" />
                        <input x-model="modalData.title" name="title" placeholder="分類項目"
                            class="w-full h-12 px-4 border border-gray-300 rounded-md shadow-sm placeholder:text-mainGray focus:border-cyan-300 focus:ring focus:ring-cyan-200 focus:ring-opacity-50" />
                    </div>
                    <div class="flex flex-row items-center w-full pb-4 justify-evenly">
                        <button type="submit"
                            class="px-4 text-sm text-white rounded cursor-pointer py-1.5 bg-mainCyanDark hover:bg-teal-400">修改</button>
                        <button type="button" @click="closeModal"
                            class="px-4 text-sm rounded cursor-pointer py-1.5 bg-gray-100 hover:bg-gray-50 border border-gray-300 btnCloseModal">關閉</button>
                    </div>
                </form>
            </template>
            <template x-if="modalData.mode === 'delete'">
                <form class="flex flex-col items-center justify-center w-full p-5 space-y-4" id="delForm" method="post"
                    action="{{ route('admin.admin.delReportTerms') }}">
                    {{ csrf_field() }}
                    <div class="modal-body flex justify-center items-center min-h-[6rem]">
                        確定要刪除 <strong><span id="delTitle" x-text="'『'+modalData.title+'』'"></span></strong> 嗎？
                    </div>
                    <input x-model="modalData.id" name="id" type="hidden" />
                    <input x-model="modalData.type" name="type" type="hidden" />
                    <div class="flex flex-row items-center w-full pb-4 justify-evenly">
                        <button type="submit"
                            class="px-4 text-sm text-white rounded cursor-pointer py-1.5 bg-mainCyanDark hover:bg-teal-400">刪除</button>
                        <button type="button" @click="closeModal"
                            class="px-4 text-sm rounded cursor-pointer py-1.5 bg-gray-100 hover:bg-gray-50 border border-gray-300 btnCloseModal">關閉</button>
                    </div>
                </form>
            </template>
        </div>
    </div>
    <div x-show="loading" class="absolute z-[1100] inset-0 bg-black/30 hidden justify-center items-center"
        :class="{'flex':loading,'hidden':!loading}">
        <div class="relative w-full h-full">
            <img src="/image/loading.svg" class="w-16 h-16 absolute left-1/2 top-[calc(50vh-2rem)]" alt="">
        </div>
    </div>
</div>
@endsection

@section('scripts')
@endsection