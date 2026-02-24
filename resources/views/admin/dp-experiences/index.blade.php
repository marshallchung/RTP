@extends('admin.layouts.dashboard', [
'heading' => '防災士培訓 > 防災士參與防災工作',
'breadcrumbs' => [
['防災士參與防災工作', route('admin.dp-scores.index')],
'申請'
]
])

@section('title', '防災士參與防災工作')

@section('inner_content')
<div x-data="{
    loading:false,
    dp_student_id:null,
    county:'',
    name:'',
    mobile:'',
    TID:'',
    edit_id:null,
    deleteFiles:[],
    createList:[],
    experiences:[],
    experiences_url:'{{ route('admin.dp-experiences.getStudent') }}',
    add_url:'{{ route('admin.dp-experiences.store') }}',
    del_url:'{{ route('admin.dp-experiences.delete',99999999) }}',
    deleteItem(id){
        if(confirm('確定要刪除嗎？')){
            var This=this;
            this.loading=true;
            var url = this.del_url.replace('99999999',id);
            var token='{{ csrf_token() }}';
            fetch(url,{
                method:'DELETE',
                headers: {
                    'X-CSRF-TOKEN': token,
                    'X-Requested-With':'XMLHttpRequest',
                },
            })
            .then((response) => {
                if(response.status===200){
                    This.TIDChange();
                    alert('資料已刪除');
                }
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
        }
    },
    showEdit(index){
        this.deleteFiles=[];
        this.edit_id=index;
        this.createList=[];
        var onedata={
            id:this.experiences[index].id,
            name:this.experiences[index].name,
            date:this.experiences[index].date,
            work_hours:this.experiences[index].work_hours,
            unit:this.experiences[index].unit,
            document_code:this.experiences[index].document_code,
            old_files:this.experiences[index].files,
            files:'',
        };
        this.createList.push(onedata);
    },
    deleteFile(id,index){
        this.deleteFiles.push(id);
        this.createList[0].old_files.splice(index,1);
    },
    addOne(){
        var onedata={
            name:'',
            date:'',
            work_hours:'',
            unit:'',
            document_code:'',
            files:'',
        };
        this.createList.push(onedata);
    },
    saveNew(e){
        var This = this;
        var token='{{ csrf_token() }}';
        const data = new FormData();
        data.append('dp_student_id', this.dp_student_id);
        this.loading=true;
        if(this.deleteFiles.length>0){
            data.append('removed_files', JSON.stringify(this.deleteFiles));
        }
        this.createList.forEach((value,index) => {
            if(value.hasOwnProperty('id')){
                data.append('id[]', value['id']);
            }
            data.append('name[]', value['name']);
            data.append('date[]', value['date']);
            data.append('work_hours[]', value['work_hours']);
            data.append('unit[]', value['unit']);
            data.append('document_code[]', value['document_code']);
            data.append('files_' + index, value['files']);
        });
        this.deleteFiles=[];
        this.edit_id=null;
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
            This.TIDChange();
            This.createList=[];
            alert(json.msg);
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
    TIDChange(){
        if (this.TID.length === 10) {
            this.experiences=[];
            this.dp_student_id=null;
            this.name='';
            this.mobile='';
            this.county='';
            this.loading=true;
            var This = this;
            this.loading=true;
            var url = this.experiences_url + '?TID=' + encodeURIComponent(this.TID);
            fetch(url,{
                method:'get',
                headers: {
                    'X-Requested-With':'XMLHttpRequest',
                    'Accept': '*/*',
                },
            })
            .then((response) => {
                if(response.status===200){
                    return response.json();
                }
            })
            .then(function (json) {
                This.dp_student_id=json.id;
                This.name=json.name;
                This.county=json.county.name;
                This.mobile=json.mobile;
                This.experiences=json.dp_experiences;
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
        }
    },
}" class="flex flex-col items-start justify-start w-full p-4 text-mainAdminTextGrayDark">
    <div class="flex flex-col items-start justify-start w-full pace-y-4">
        <div class="relative w-full mb-6 bg-white border border-gray-200 rounded-sm">
            <div class="relative px-5 pt-3 pb-2 bg-gray-100 border-b-2 border-gray-200 text-mainAdminTextGrayDark">
                防災士基本資料
            </div>
            <div id="divStudentData" class="flex flex-col w-full p-5 space-y-6 bg-white">
                <div class="flex items-center w-full space-x-2 form-row">
                    <div class="w-1/3 text-center">
                        {!! Form::label('TID', '身分證字號') !!}
                    </div>
                    <div class="flex-1">
                        {!! Form::text('TID', null, ['@change'=>'TIDChange','x-model'=>'TID','class' => 'h-12 px-4
                        border-gray-300 rounded-md shadow-sm
                        focus:border-cyan-300 focus:ring focus:ring-cyan-200 focus:ring-opacity-50 w-full', 'required'])
                        !!}
                    </div>
                </div>

                <div class="flex items-center w-full space-x-2 form-row">
                    <div class="w-1/3 text-center">
                        {!! Form::label('county', '所屬縣市') !!}
                    </div>
                    <div class="flex-1">
                        {!! Form::text('county', null, ['x-model'=>'county','class' => 'h-12 px-4 border-gray-300
                        rounded-md shadow-sm
                        focus:border-cyan-300 focus:ring focus:ring-cyan-200 focus:ring-opacity-50 w-full bg-mainLight',
                        'disabled'])
                        !!}
                    </div>
                </div>

                <div class="flex items-center w-full space-x-2 form-row">
                    <div class="w-1/3 text-center">
                        {!! Form::label('name', '姓名') !!}
                    </div>
                    <div class="flex-1">
                        {!! Form::text('name', null, ['x-model'=>'name','class' => 'h-12 px-4 border-gray-300 rounded-md
                        shadow-sm
                        focus:border-cyan-300 focus:ring focus:ring-cyan-200 focus:ring-opacity-50 w-full bg-mainLight',
                        'disabled'])
                        !!}
                    </div>
                </div>

                <div class="flex items-center w-full space-x-2 form-row">
                    <div class="w-1/3 text-center">
                        {!! Form::label('mobile', '行動電話') !!}
                    </div>
                    <div class="flex-1">
                        {!! Form::text('mobile', null, ['x-model'=>'mobile','class' => 'h-12 px-4 border-gray-300
                        rounded-md shadow-sm
                        focus:border-cyan-300 focus:ring focus:ring-cyan-200 focus:ring-opacity-50 w-full bg-mainLight',
                        'disabled'])
                        !!}
                    </div>
                </div>
            </div>
        </div>

        <div class="relative w-full mb-6 bg-white border border-gray-200 rounded-sm">
            <div class="relative px-5 pt-3 pb-2 bg-gray-100 border-b-2 border-gray-200 text-mainAdminTextGrayDark">已參與紀錄
            </div>
            <div class="w-full p-5 m-0 bg-white">
                <table id="tableData" class="w-full bg-white border shadow-lg text-mainAdminTextGrayDark">
                    <thead>
                        <tr class="border-b bg-mainLight">
                            <th class="p-2 font-normal text-left border-r last:border-r-0">名稱</th>
                            <th class="p-2 font-normal text-left border-r last:border-r-0">參與日期</th>
                            <th class="p-2 font-normal text-left border-r last:border-r-0">參與時數(小時)</th>
                            <th class="p-2 font-normal text-left border-r last:border-r-0">發予證明單位</th>
                            <th class="p-2 font-normal text-left border-r last:border-r-0">證明文件編號</th>
                            <th class="p-2 font-normal text-left border-r last:border-r-0">文件檔案</th>
                            <th class="p-2 font-normal text-left border-r last:border-r-0"></th>
                        </tr>
                    </thead>
                    <tbody class="text-content text-mainAdminTextGrayDark">
                        <template x-for="(data_item, index) in experiences">
                            <tr class="border-b last:border-b-0 odd:bg-white even:bg-mainLight">
                                <td class="p-2 border-r last:border-r-0">
                                    <template x-if="index != edit_id">
                                        <span x-text="data_item.name"></span>
                                    </template>
                                    <template x-if="index == edit_id">
                                        <input
                                            class="w-full h-12 px-4 border-gray-300 rounded-md shadow-sm focus:border-cyan-300 focus:ring focus:ring-cyan-200 focus:ring-opacity-50"
                                            x-model="createList[0].name" type="text" required>
                                    </template>
                                </td>
                                <td class="p-2 border-r last:border-r-0">
                                    <template x-if="index != edit_id">
                                        <span x-text="data_item.date"></span>
                                    </template>
                                    <template x-if="index == edit_id">
                                        <input
                                            class="w-full h-12 px-4 border-gray-300 rounded-md shadow-sm focus:border-cyan-300 focus:ring focus:ring-cyan-200 focus:ring-opacity-50"
                                            x-model="createList[0].date" type="date" required>
                                    </template>
                                </td>
                                <td class="p-2 border-r last:border-r-0">
                                    <template x-if="index != edit_id">
                                        <span x-text="data_item.work_hours"></span>
                                    </template>
                                    <template x-if="index == edit_id">
                                        <input
                                            class="w-full h-12 px-4 border-gray-300 rounded-md shadow-sm focus:border-cyan-300 focus:ring focus:ring-cyan-200 focus:ring-opacity-50"
                                            x-model="createList[0].work_hours" type="number" min="0" step="1" required>
                                    </template>
                                </td>
                                <td class="p-2 border-r last:border-r-0">
                                    <template x-if="index != edit_id">
                                        <span x-text="data_item.unit"></span>
                                    </template>
                                    <template x-if="index == edit_id">
                                        <input
                                            class="w-full h-12 px-4 border-gray-300 rounded-md shadow-sm focus:border-cyan-300 focus:ring focus:ring-cyan-200 focus:ring-opacity-50"
                                            x-model="createList[0].unit" type="text" required>
                                    </template>
                                </td>
                                <td class="p-2 border-r last:border-r-0">
                                    <template x-if="index != edit_id">
                                        <span x-text="data_item.document_code"></span>
                                    </template>
                                    <template x-if="index == edit_id">
                                        <input
                                            class="w-full h-12 px-4 border-gray-300 rounded-md shadow-sm focus:border-cyan-300 focus:ring focus:ring-cyan-200 focus:ring-opacity-50"
                                            x-model="createList[0].document_code" type="text">
                                    </template>
                                </td>
                                <td class="p-2 border-r last:border-r-0">
                                    <template x-if="index != edit_id">
                                        <div class="flex flex-col w-full space-y-2">
                                            <template x-for="(file, file_idx) in data_item.files">
                                                <a :href="file.path" class="text-mainBlueDark" x-text="file.name"></a>
                                            </template>
                                        </div>
                                    </template>
                                    <template x-if="index == edit_id">
                                        <div class="flex flex-col w-full space-y-2">
                                            <template x-for="(file, file_idx) in createList[0].old_files">
                                                <div class="flex flex-row items-center justify-start space-x-2">
                                                    <button type="button" @click="deleteFile(file.id,file_idx)"
                                                        class="flex items-center justify-center w-10 h-8 text-sm text-white bg-orange-600 rounded cursor-pointer hover:bg-orange-500">
                                                        <i class="w-2.5 h-2.5 mx-1 i-fa6-solid-trash"
                                                            aria-hidden="true"></i>
                                                    </button>
                                                    <a :href="file.path" class="text-mainBlueDark"
                                                        x-text="file.name"></a>
                                                </div>
                                            </template>
                                            <input multiple="false"
                                                @change="createList[0].files = $event.target.files[0]" type="file">
                                        </div>
                                    </template>
                                </td>
                                <td class="p-2 border-r last:border-r-0">
                                    <template x-if="index != edit_id">
                                        <div class="flex flex-row">
                                            <button type="button" @click="deleteItem(data_item.id)"
                                                class="flex items-center justify-center h-8 px-2 m-1 space-x-1 text-sm text-white bg-orange-600 rounded cursor-pointer hover:bg-orange-500">
                                                <i class="w-6 h-2.5 i-fa6-solid-trash" aria-hidden="true"></i>
                                                <span class="text-sm whitespace-nowrap">刪除</span>
                                            </button>
                                            <button type="button" @click="showEdit(index)"
                                                class="flex items-center justify-center h-8 px-2 m-1 space-x-1 text-sm text-white rounded cursor-pointer bg-mainCyanDark hover:bg-cyan-400">
                                                <i class="w-6 h-2.5 i-fa6-solid-pen-to-square" aria-hidden="true"></i>
                                                <span class="text-sm whitespace-nowrap">修改</span>
                                            </button>
                                        </div>
                                    </template>
                                    <template x-if="index == edit_id">
                                        <div class="flex flex-row">
                                            <button type="button" @click="edit_id=null"
                                                class="flex items-center justify-center h-8 px-2 m-1 space-x-1 text-sm bg-gray-100 border border-gray-300 rounded cursor-pointer text-mainAdminTextGrayDark hover:bg-gray-50">
                                                <i class="w-6 h-2.5 i-fa6-solid-caret-left" aria-hidden="true"></i>
                                                <span class="text-sm whitespace-nowrap">取消</span>
                                            </button>
                                            <button type="button" @click="saveNew"
                                                class="flex items-center justify-center h-8 px-2 m-1 space-x-1 text-sm text-white rounded cursor-pointer bg-mainBlueDark hover:bg-mainBlue">
                                                <i class="w-6 h-2.5 i-fa6-solid-check" aria-hidden="true"></i>
                                                <span class="text-sm whitespace-nowrap">存檔</span>
                                            </button>
                                        </div>
                                    </template>
                                </td>
                            </tr>
                        </template>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="relative w-full mb-6 bg-white border border-gray-200 rounded-sm">
            <div class="relative px-5 pt-3 pb-2 bg-gray-100 border-b-2 border-gray-200 text-mainAdminTextGrayDark">
                新增參與紀錄
            </div>
            {!! Form::open(['method' => 'POST', 'route' => 'admin.dp-experiences.store', 'id' =>
            'formCreate','@submit.prevent'=>'saveNew','class'=>"flex flex-col items-center justify-center w-full"]) !!}
            <div class="w-full p-5 m-0 bg-white">
                <table id="tableCreate" class="w-full bg-white border shadow-lg text-mainAdminTextGrayDark">
                    <thead>
                        <tr class="border-b bg-mainLight">
                            <th class="p-2 font-normal text-left border-r last:border-r-0">名稱</th>
                            <th class="p-2 font-normal text-left border-r last:border-r-0">參與日期</th>
                            <th class="p-2 font-normal text-left border-r last:border-r-0">參與時數(小時)</th>
                            <th class="p-2 font-normal text-left border-r last:border-r-0">發予證明單位</th>
                            <th class="p-2 font-normal text-left border-r last:border-r-0">證明文件編號</th>
                            <th class="p-2 font-normal text-left border-r last:border-r-0">檔案上傳</th>
                        </tr>
                    </thead>
                    <tbody class="text-content text-mainAdminTextGrayDark">
                        <template x-for="(data_item, index) in createList" :key="">
                            <tr class="bg-white border-b last:border-b-0">
                                <td class="p-2 border-r last:border-r-0">
                                    <input
                                        class="w-full h-12 px-4 border-gray-300 rounded-md shadow-sm focus:border-cyan-300 focus:ring focus:ring-cyan-200 focus:ring-opacity-50"
                                        x-model="createList[index].name" type="text" required>
                                </td>
                                <td class="p-2 border-r last:border-r-0">
                                    <input
                                        class="w-full h-12 px-4 border-gray-300 rounded-md shadow-sm focus:border-cyan-300 focus:ring focus:ring-cyan-200 focus:ring-opacity-50"
                                        x-model="createList[index].date" type="date" required>
                                </td>
                                <td class="p-2 border-r last:border-r-0">
                                    <input
                                        class="w-full h-12 px-4 border-gray-300 rounded-md shadow-sm focus:border-cyan-300 focus:ring focus:ring-cyan-200 focus:ring-opacity-50"
                                        x-model="createList[index].work_hours" type="number" min="0" step="1" required>
                                </td>
                                <td class="p-2 border-r last:border-r-0">
                                    <input
                                        class="w-full h-12 px-4 border-gray-300 rounded-md shadow-sm focus:border-cyan-300 focus:ring focus:ring-cyan-200 focus:ring-opacity-50"
                                        x-model="createList[index].unit" type="text" required>
                                </td>
                                <td class="p-2 border-r last:border-r-0">
                                    <input
                                        class="w-full h-12 px-4 border-gray-300 rounded-md shadow-sm focus:border-cyan-300 focus:ring focus:ring-cyan-200 focus:ring-opacity-50"
                                        x-model="createList[index].document_code" type="text">
                                </td>
                                <td class="p-2 border-r last:border-r-0">
                                    <input multiple="false" @change="createList[index].files = $event.target.files[0]"
                                        type="file">
                                </td>
                            </tr>
                        </template>
                    </tbody>
                </table>
                <div
                    class="flex flex-col items-center justify-center w-full py-5 space-y-5 bg-white border-t rounded-bl-sm rounded-br-sm">
                    <div class="text-center form-group">
                        <button type="button" id="addOne" @click="addOne"
                            class="flex flex-row items-center justify-center h-10 space-x-1 bg-gray-100 border border-gray-300 w-44 hover:bg-gray-50"
                            :class="{'cursor-not-allowed text-mainGray hover:bg-gray-100':name.length==0,'cursor-pointer text-mainAdminTextGrayDark hover:bg-gray-50':name.length>0}"
                            x-bind:disabled="name.length==0">
                            <i class="w-3 h-3 i-fa6-solid-plus"></i>
                            <span>增加一筆</span>
                        </button>
                    </div>
                    <div class="flex flex-row flex-wrap w-full text-center">
                        <input id="btnSubmit" type="submit"
                            class="flex items-center justify-center w-full h-10 text-white rounded bg-mainCyanDark"
                            :class="{'cursor-not-allowed bg-mainGray':createList.length==0,'cursor-pointer bg-mainCyanDark':createList.length>0}"
                            value="送出" x-bind:disabled="createList.length==0">
                    </div>
                </div>
            </div>
            {!! Form::close() !!}
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