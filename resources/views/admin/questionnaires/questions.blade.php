<?php
if (!function_exists('fixJson')) {
    function fixJson($jsonStr)
    {
        return str_replace(['\\"', '\n', '\r', "\n", "\r"], ['\\\"', '', '', '', ''], $jsonStr);
    }
}
?>

@extends('admin.layouts.dashboard', [
'heading' => '設定績效評估自評表題目',
'breadcrumbs' => [
['績效評估自評表問卷', route('admin.questionnaire.index')],
'編輯'
]
])

@section('title', $questionnaire['title'])

@section('styling')
@endsection

@section('inner_content')
<div class="flex flex-row items-start justify-start w-full" x-data="{
    can_edit:{{ $can_edit?'true':'false' }},
    questionnaire:{{ json_encode($questionnaire) }},
    loading:false,
    saveQuestion(e){
        var url=e.target.action;
        this.loading=true;
        fetch(url,{
            method:'POST',
            body:JSON.stringify(this.questionnaire),
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'X-Requested-With':'XMLHttpRequest',
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            },
        })
        .then((response) => {
            if(response.status===200){
                return response.json();
            }else{
                alert('伺服器錯誤: ' + response.status);
                return false;
            }
        })
        .then(function (json) {
            this.questionnaire=json.questionnaire;
            alert(json.msg);
        })
        .catch(function(error) {
            if (error.status == 429) {
                alert('嘗試登入次數過多，請稍後再試。');
            }else if (error.status == 419) {
                alert('頁面逾期，請重新輸入');
                location.reload();
            }else{
                alert('伺服器錯誤: ' + error.message);
            }
        })
        .finally(() => {
            this.loading=false;
        });
    },
    seq:1,
    setCodeAndIndent(obj){
        var level_id=1;
        var now_indent,start_level,child_list;
        if(obj){
            child_list=obj.child;
            now_indent=obj.indent+1;
            start_level=obj.code;
        }else{
            child_list=this.questionnaire.questions;
            now_indent=1;
            start_level='';
        }
        console.log('now_indent: ' + now_indent + ',start_level: ' + start_level + ',child_list: ' + child_list.length);
        if(child_list){
            for (const [child_index, child_element] of Object.entries(child_list)) {
                if((child_element.type=='text') || (child_element.type=='textarea') || (child_element.type=='title')){
                    if(isNaN(child_element.options) || isNaN(parseFloat(child_element.options))){
                        child_element.options='0';
                    }
                }
                child_element.seq=this.seq;
                this.seq++;
                child_element.indent=now_indent;
                child_element.code=start_level?(start_level+'.'+level_id):level_id.toString();
                level_id++;
                if(child_element.child){
                    this.setCodeAndIndent(child_element);
                }
            }
        }
    },
    setChildScoreType(parent){
        var child_list=parent.child?parent.child:parent.questions;
        if(child_list){
            for (const [child_index, child] of Object.entries(child_list)) {
                if(child.type!=='bigger' && child.type!=='basic' && child.type!=='advanced'){
                    child.score_type=parent.score_type;
                }
                if(child.type!=='basic' && child.type!=='advanced' && child.child && child.child.length>0){
                    this.setChildScoreType(child);
                }
            }
        }
    },
    scoreChange(e){
        var last_index,element;
        var level=e.target.dataset.level;
        console.log('scoreChange level: ' + level);
        const level_array = level.split(',');
        if(level_array.length===1){
            level_id=parseInt(level_array[0]);
            element = this.questionnaire.questions[level_id];
        }else{
            level_array.forEach(level_id => {
                level_id=parseInt(level_id);
                if(element){
                    element=element.child[level_id];
                }else{
                    element=this.questionnaire.questions[level_id]
                }
            });
        }
        console.log('element: ' + JSON.stringify(element));
        if(element){
            if(element.type==='radio' || element.type==='checkbox'){
                console.log('1. element type: ' + element.type + ' - ' + element.options);
                if(element.options.match(/[^=]+==[\d]+/) && element.score_type==''){
                    alert('項目上層沒有設定基本指標或是進階指標，此項目將不會計算分數');
                }
            }else if(element.type==='text' || element.type==='textarea' || element.type==='title'){
                console.log('2. element type: ' + element.type + ' - ' + element.options);
                console.log(!isNaN(element.options));
                console.log(!isNaN(parseFloat(element.options)));
                console.log(parseInt(element.options));
                console.log(element.score_type);
                if(!isNaN(element.options) && !isNaN(parseFloat(element.options)) && parseInt(element.options)>0 && element.score_type==''){
                    alert('項目上層沒有設定基本指標或是進階指標，此項目將不會計算分數');
                }
            }
        }
    },
    itemChange(e){
        var last_index,element;
        var level=e.target.dataset.level;
        const level_array = level.split(',');
        if(level_array.length===1){
            level_id=parseInt(level_array[0]);
            element = this.questionnaire.questions[level_id];
        }else{
            level_array.forEach(level_id => {
                level_id=parseInt(level_id);
                if(element){
                    element=element.child[level_id];
                }else{
                    element=this.questionnaire.questions[level_id]
                }
            });
        }
        if(element.type==='basic'){
            element.content='基本指標';
            element.score_type='basic';
            this.setChildScoreType(element);
        }else if(element.type==='advanced'){
            element.content='進階指標';
            element.score_type='advanced';
            this.setChildScoreType(element);
        }else if(element.type==='radio' || element.type==='checkbox'){
            if(element.options!= null && element.options.length>0 && !element.options.match(/[^=]+==[\d]+/)){
                element.options='';
            }
        }
    },
    addItem(e){
        var last_element,last_index,parent;
        var level=e.target.dataset.level;

        const level_array = level.split(',');
        if(level_array.length===1){
            last_index = level_array[0];
            parent = this.questionnaire.questions;
            last_element=parent[last_index];
        }else{
            last_index=level_array[level_array.length-1];
            level_array.splice(-1);
            level_array.forEach(level_id => {
                level_id=parseInt(level_id);
                if(parent){
                    parent=parent.child[level_id];
                }else{
                    parent=this.questionnaire.questions[level_id]
                }
            });
        }
        if(parent){
            if(!last_element){
                last_element=parent.child[last_index];
            }
            const code_array = last_element.code.split('.');
            const last_code = parseInt(code_array[code_array.length-1])+1;
            code_array.splice(-1);

            var new_code=code_array.toString();
            if(new_code){
                new_code += '.';
            }
            new_code += last_code;
            var new_item={
                id:null,
                questionnaire_id: this.questionnaire.id,
                seq: 1,
                code: new_code.replaceAll(',','.'),
                indent: last_element.indent,
                type: 'title',
                score_type: parent.score_type,
                content: '',
                options: '',
                upload: 0,
                gain: 1,
                extra_gain: 1,
                score_limit: 0,
                comment: 1,
            }
            if(code_array.length>0){
                parent.child.push(new_item);
            }else{
                parent.push(new_item);
            }
        }
        this.seq=1;
        this.setCodeAndIndent();
        console.log(JSON.stringify(this.questionnaire));
    },
    deleteBlock(level){
        var last_index,parent;
        level+='';
        const level_array = level.split(',');
        if(level_array.length===1){
            last_index = parseInt(level_array[0]);
            this.questionnaire.questions.splice(last_index, 1);
        }else{
            last_index=parseInt(level_array[level_array.length-1]);
            level_array.splice(-1);
            level_array.forEach(level_id => {
                level_id=parseInt(level_id);
                if(parent){
                    parent=parent.child[level_id];
                }else{
                    parent=this.questionnaire.questions[level_id]
                }
            });
            parent.child.splice(last_index, 1);
        }
        if(this.questionnaire.questions.length==0){
            var new_item={
                id:null,
                questionnaire_id: this.questionnaire.id,
                seq: 1,
                code: '1',
                indent: 1,
                type: 'bigger',
                score_type: '',
                content: '',
                options: '',
                upload: 0,
                gain: 0,
                extra_gain: 0,
                score_limit: 0,
                comment: 1,
            }
            this.questionnaire.questions.push(new_item);
        }
        this.seq=1;
        this.setCodeAndIndent();
        console.log(JSON.stringify(this.questionnaire));
    },
    addSub(e){
        var last_element,parent,new_code;
        var level=e.target.dataset.level;

        const level_array = level.split(',');
        level_array.forEach(level_id => {
            level_id=parseInt(level_id);
            if(parent){
                parent=parent.child[level_id];
            }else{
                parent=this.questionnaire.questions[level_id]
            }
        });
        if(parent){
            new_code = parent.child ? (parent.code + '.' + parent.child.length+1) : (parent.code + '.1');
            var new_item={
                id:null,
                questionnaire_id: this.questionnaire.id,
                seq: 1,
                code: new_code,
                indent: parent.indent+1,
                type: 'title',
                score_type: parent.score_type,
                content: '',
                options: '',
                upload: 0,
                gain: 0,
                extra_gain: 0,
                score_limit: 0,
                comment: 1,
            }
            if(!parent.child){
                parent.child=[];
            }
            parent.child.push(new_item);
        }
        this.seq=1;
        this.setCodeAndIndent();
        console.log(JSON.stringify(this.questionnaire));
    },
}" x-init="$nextTick(() => {
 })">
    <div class="flex flex-col items-center justify-start w-full p-6 space-y-4 text-mainAdminTextGrayDark">
        <div class="flex flex-col items-center justify-center w-full">
            <h4 x-text="questionnaire.title"></h4>
        </div>

        <form id="formAnswers" class="flex flex-col items-start justify-start w-full space-y-4" method="post"
            @submit.prevent="saveQuestion" :action="'/admin/questions/update'" enctype="multipart/form-data"
            accept-charset="UTF-8">
            {!! csrf_field() !!}
            <template x-for="(question,index) in questionnaire.questions">
                <div class="flex flex-col w-full pb-8 m-1 mb-6 bg-white border rounded indent-2">
                    <div class="flex flex-col items-start justify-start w-full p-4 pb-8 space-y-2">
                        <div class="flex flex-row items-center justify-start w-full space-x-6">
                            <select @change="itemChange" x-model='question.type' x-bind:data-level="index"
                                x-bind:data-code="question.code"
                                class="inline-block align-middle bg-white border-gray-300 rounded-md shadow-sm disabled:bg-gray-100 disabled:cursor-not-allowed w-52 placeholder:text-mainTextGray focus:border-sky-300 focus:ring focus:ring-sky-200 focus:ring-opacity-50">
                                <option value="bigger" data-score="">大標題</option>
                                <option value="title" data-score="parent">小標題</option>
                                <option value="basic" data-score="basic">基本指標</option>
                                <option value="advanced" data-score="basic">進階指標</option>
                            </select>
                            <template x-if="question.score_type">
                                <span class="text-sm text-mainTextGray"
                                    x-text="question.score_type=='basic'?'基本指標':question.score_type=='advanced'?'進階指標':''"></span>
                            </template>
                        </div>
                        <div class="flex items-center justify-center w-full">
                            <template
                                x-if="question.type==='bigger' || question.type==='title' || question.type==='basic' || question.type==='advanced'">
                                <input type="text" placeholder="請輸入標題" x-model="question.content"
                                    x-bind:data-level="index" x-bind:data-code="question.code"
                                    :class="{'text-mainAdminTextGrayDark text-base':question.type=='title','text-mainBlueTitle text-2xl':question.type!='title'}"
                                    class="w-full bg-white border-gray-300 rounded-md shadow-sm text-mainBlueTitle placeholder:text-mainTextGray focus:border-sky-300 focus:ring focus:ring-sky-200 focus:ring-opacity-50">
                            </template>
                        </div>
                    </div>
                    <template x-if="question.child">
                        <template x-for="(child1,child1_index) in question.child">
                            <div class="flex flex-col w-full pb-8 m-1 border rounded bg-gray-50 indent-2">
                                <div class="flex flex-col items-start justify-start w-full p-4 pb-8 space-y-2"
                                    :class="{'flex-row':(child1.type==='radio' || child1.type==='checkbox'),'flex-col':!(child1.type==='radio' || child1.type==='checkbox')}">
                                    <div class="flex flex-row items-center justify-start w-full space-x-6"
                                        :class="{'flex-col items-start space-x-0 space-y-4 flex-1':(child1.type==='radio' || child1.type==='checkbox'),'flex-row w-full items-center space-x-6 space-y-0':!(child1.type==='radio' || child1.type==='checkbox')}">
                                        <div class="flex flex-row items-center justify-start space-x-2">
                                            <select x-model='child1.type' @change="itemChange"
                                                x-bind:data-level="index + ',' + child1_index"
                                                x-bind:data-code="child1.code" x-bind:data-type="child1.type"
                                                class="inline-block align-middle bg-white border-gray-300 rounded-md shadow-sm disabled:bg-gray-100 disabled:cursor-not-allowed w-52 placeholder:text-mainTextGray focus:border-sky-300 focus:ring focus:ring-sky-200 focus:ring-opacity-50">
                                                <option value="bigger" data-score="">
                                                    大標題
                                                </option>
                                                <option value="basic" data-score="basic">
                                                    基本指標</option>
                                                <option value="advanced" data-score="basic">
                                                    進階指標</option>
                                                <option value="title" data-score="parent">小標題</option>
                                                <template x-if="child1.score_type">
                                                    <option value="radio" data-score="parent"
                                                        x-bind:hidden="!child1.score_type">
                                                        單選題</option>
                                                </template>
                                                <template x-if="child1.score_type">
                                                    <option value="checkbox" data-score="parent"
                                                        x-bind:hidden="!child1.score_type">
                                                        多選題</option>
                                                </template>
                                                <template x-if="child1.score_type">
                                                    <option value="text" data-score="parent"
                                                        x-bind:hidden="!child1.score_type">
                                                        填空題</option>
                                                </template>
                                                <template x-if="child1.score_type">
                                                    <option value="textarea" data-score="parent"
                                                        x-bind:hidden="!child1.score_type">
                                                        多行文字</option>
                                                </template>
                                            </select>
                                            <template x-if="child1.score_type">
                                                <span class="text-sm text-mainTextGray"
                                                    x-text="child1.score_type=='basic'?'基本指標':child1.score_type=='advanced'?'進階指標':''"></span>
                                            </template>
                                            <template x-if="child1.type==='text' || child1.type==='textarea'">
                                                <div class="flex flex-row items-center justify-start space-x-4">
                                                    <label class="flex flex-row items-center justify-start space-x-1">
                                                        <span>得分</span>
                                                        <input type="number"
                                                            x-bind:data-level="index + ',' + child1_index"
                                                            x-bind:data-code="child1.code" placeholder="得分" step="0.1"
                                                            min="0" x-model="child1.options" @change="scoreChange"
                                                            class="w-20 bg-white border-gray-300 rounded-md shadow-sm placeholder:text-mainTextGray focus:border-sky-300 focus:ring focus:ring-sky-200 focus:ring-opacity-50">
                                                    </label>
                                                    <label class="flex flex-row items-center justify-start space-x-1">
                                                        <span>加成</span>
                                                        <input type="number"
                                                            x-bind:data-level="index + ',' + child1_index"
                                                            x-bind:data-code="child1.code" placeholder="加成" step="0.1"
                                                            min="0" x-model="child1.gain"
                                                            class="w-20 bg-white border-gray-300 rounded-md shadow-sm placeholder:text-mainTextGray focus:border-sky-300 focus:ring focus:ring-sky-200 focus:ring-opacity-50">
                                                    </label>
                                                    <label class="flex flex-row items-center justify-start space-x-1">
                                                        <span>分數上限</span>
                                                        <input type="number"
                                                            x-bind:data-level="index + ',' + child1_index"
                                                            x-bind:data-code="child1.code" placeholder="分數上限" step="0.1"
                                                            min="0" x-model="child1.score_limit"
                                                            class="w-20 bg-white border-gray-300 rounded-md shadow-sm placeholder:text-mainTextGray focus:border-sky-300 focus:ring focus:ring-sky-200 focus:ring-opacity-50">
                                                    </label>
                                                    <label class="flex flex-row items-center justify-start space-x-1">
                                                        <input type="checkbox" value="1"
                                                            x-bind:data-level="index + ',' + child1_index"
                                                            x-bind:data-code="child1.code" x-model="child1.upload"
                                                            class="bg-white border-gray-300 rounded text-mainCyanDark"
                                                            x-bind:checked="child1.upload">
                                                        <span>上傳附件</span>
                                                    </label>
                                                    <label class="flex flex-row items-center justify-start space-x-1">
                                                        <input type="checkbox" value="1"
                                                            x-bind:data-level="index + ',' + child1_index"
                                                            x-bind:data-code="child1.code" x-model="child1.comment"
                                                            class="bg-white border-gray-300 rounded text-mainCyanDark"
                                                            x-bind:checked="child1.comment">
                                                        <span>審查</span>
                                                    </label>
                                                </div>
                                            </template>
                                        </div>
                                        <template x-if="child1.type==='radio' || child1.type==='checkbox'">
                                            <div class="flex flex-col w-full space-y-4">
                                                <input type="text" x-bind:data-level="index + ',' + child1_index"
                                                    x-bind:data-code="child1.code" placeholder="請輸入問題說明"
                                                    x-model="child1.content"
                                                    class="w-full bg-white border-gray-300 rounded-md shadow-sm placeholder:text-mainTextGray focus:border-sky-300 focus:ring focus:ring-sky-200 focus:ring-opacity-50">
                                                <label
                                                    class="flex flex-row items-center justify-start w-full space-x-1">
                                                    <span>加成</span>
                                                    <input type="number" x-bind:data-level="index + ',' + child1_index"
                                                        x-bind:data-code="child1.code" placeholder="加成" step="0.1"
                                                        min="0" x-model="child1.gain"
                                                        class="flex-1 bg-white border-gray-300 rounded-md shadow-sm placeholder:text-mainTextGray focus:border-sky-300 focus:ring focus:ring-sky-200 focus:ring-opacity-50">
                                                </label>
                                                <label
                                                    class="flex flex-row items-center justify-start w-full space-x-1">
                                                    <span>分數上限</span>
                                                    <input type="number" x-bind:data-level="index + ',' + child1_index"
                                                        x-bind:data-code="child1.code" placeholder="分數上限" step="0.1"
                                                        min="0" x-model="child1.score_limit"
                                                        class="flex-1 bg-white border-gray-300 rounded-md shadow-sm placeholder:text-mainTextGray focus:border-sky-300 focus:ring focus:ring-sky-200 focus:ring-opacity-50">
                                                </label>
                                                <div class="flex flex-row w-full justify-evenly">
                                                    <label
                                                        class="flex flex-row items-center justify-start w-full space-x-1">
                                                        <input type="checkbox" value="1"
                                                            x-bind:data-level="index + ',' + child1_index"
                                                            x-bind:data-code="child1.code" x-model="child1.upload"
                                                            class="bg-white border-gray-300 rounded text-mainCyanDark"
                                                            x-bind:checked="child1.upload">
                                                        <span>上傳附件</span>
                                                    </label>
                                                    <label
                                                        class="flex flex-row items-center justify-start w-full space-x-1">
                                                        <input type="checkbox" value="1"
                                                            x-bind:data-level="index + ',' + child1_index"
                                                            x-bind:data-code="child1.code" x-model="child1.comment"
                                                            class="bg-white border-gray-300 rounded text-mainCyanDark"
                                                            x-bind:checked="child1.comment">
                                                        <span>審查</span>
                                                    </label>
                                                </div>
                                                <div class="w-full text-left text-mainTextGray">
                                                    設定方式：選項名稱==分數，中間用兩個等號連接，每一個斷行為一個答案</div>
                                            </div>
                                        </template>
                                    </div>
                                    <div class="flex items-center justify-center flex-1 w-full">
                                        <template
                                            x-if="child1.type==='bigger' || child1.type==='title' || child1.type==='basic' || child1.type==='advanced'">
                                            <input type="text" placeholder="請輸入標題"
                                                x-bind:data-level="index + ',' + child1_index"
                                                x-bind:data-code="child1.code" x-model="child1.content"
                                                :class="{'text-mainAdminTextGrayDark text-base':child1.type=='title','text-mainBlueTitle text-2xl':child1.type!='title'}"
                                                class="w-full bg-white border-gray-300 rounded-md shadow-sm text-mainBlueTitle placeholder:text-mainTextGray focus:border-sky-300 focus:ring focus:ring-sky-200 focus:ring-opacity-50">
                                        </template>
                                        <template x-if="child1.type==='radio' || question.type==='checkbox'">
                                            <div class="flex flex-row flex-wrap pl-4 text-center">
                                                <textarea placeholder="請輸入選項：選項名稱==分數，每一個斷行為一個答案"
                                                    x-bind:data-level="index + ',' + child1_index"
                                                    x-bind:data-code="child1.code" x-model="child1.options"
                                                    @change="scoreChange"
                                                    class="w-full whitespace-pre min-h-[19rem] bg-white border-gray-300 rounded-md shadow-sm placeholder:text-mainTextGray focus:border-sky-300 focus:ring focus:ring-sky-200 focus:ring-opacity-50"></textarea>

                                            </div>
                                        </template>
                                        <template x-if="child1.type==='text' || child1.type==='textarea'">
                                            <div class="flex flex-row flex-wrap w-full mb-4 text-center">
                                                <input type="text" placeholder="請輸入問題說明"
                                                    x-bind:data-level="index + ',' + child1_index"
                                                    x-bind:data-code="child1.code" x-model="child1.content"
                                                    class="w-full bg-white border-gray-300 rounded-md shadow-sm placeholder:text-mainTextGray focus:border-sky-300 focus:ring focus:ring-sky-200 focus:ring-opacity-50">
                                            </div>
                                        </template>
                                    </div>
                                </div>
                                <template x-if="child1.child">
                                    <template x-for="(child2,child2_index) in child1.child">
                                        <div class="flex flex-col w-full pb-8 m-1 bg-gray-100 border rounded indent-2">
                                            <div class="flex flex-col items-start justify-start w-full p-4 pb-8 space-y-2"
                                                :class="{'flex-row':(child2.type==='radio' || child2.type==='checkbox'),'flex-col':!(child2.type==='radio' || child2.type==='checkbox')}">
                                                <div class="flex flex-row items-center justify-start w-full space-x-6"
                                                    :class="{'flex-col items-start space-x-0 space-y-4 flex-1':(child2.type==='radio' || child2.type==='checkbox'),'flex-row w-full items-center space-x-6 space-y-0':!(child2.type==='radio' || child2.type==='checkbox')}">
                                                    <div class="flex flex-row items-center justify-start space-x-2">
                                                        <select x-model='child2.type' @change="itemChange"
                                                            x-bind:data-level="index + ',' + child1_index + ',' + child2_index"
                                                            x-bind:data-code="child2.code"
                                                            class="inline-block align-middle bg-white border-gray-300 rounded-md shadow-sm w-52 disabled:bg-gray-100 disabled:cursor-not-allowed placeholder:text-mainTextGray focus:border-sky-300 focus:ring focus:ring-sky-200 focus:ring-opacity-50">
                                                            <option value="bigger" data-score=""
                                                                x-bind:hidden="child2.score_type">
                                                                大標題</option>
                                                            <option value="basic" data-score="basic"
                                                                x-bind:hidden="child2.score_type">
                                                                基本指標</option>
                                                            <option value="advanced" data-score="basic"
                                                                x-bind:hidden="child2.score_type">
                                                                進階指標</option>
                                                            <option value="title" data-score="parent">小標題</option>
                                                            <template x-if="child2.score_type">
                                                                <option value="radio" data-score="parent"
                                                                    x-bind:hidden="!child2.score_type">單選題
                                                                </option>
                                                            </template>
                                                            <template x-if="child2.score_type">
                                                                <option value="checkbox" data-score="parent"
                                                                    x-bind:hidden="!child2.score_type">多選題
                                                                </option>
                                                            </template>
                                                            <template x-if="child2.score_type">
                                                                <option value="text" data-score="parent"
                                                                    x-bind:hidden="!child2.score_type">填空題</option>
                                                            </template>
                                                            <template x-if="child2.score_type">
                                                                <option value="textarea" data-score="parent"
                                                                    x-bind:hidden="!child2.score_type">多行文字
                                                                </option>
                                                            </template>
                                                        </select>
                                                        <template x-if="child2.score_type">
                                                            <span class="text-sm text-mainTextGray"
                                                                x-text="child2.score_type=='basic'?'基本指標':child2.score_type=='advanced'?'進階指標':''"></span>
                                                        </template>
                                                        <template
                                                            x-if="child2.type==='text' || child2.type==='textarea'">
                                                            <div
                                                                class="flex flex-row items-center justify-start space-x-4">
                                                                <label
                                                                    class="flex flex-row items-center justify-start space-x-1">
                                                                    <span>得分</span>
                                                                    <input type="number"
                                                                        x-bind:data-level="index + ',' + child1_index + ',' + child2_index"
                                                                        x-bind:data-code="child2.code" placeholder="得分"
                                                                        step="0.1" min="0" x-model="child2.options"
                                                                        @change="scoreChange"
                                                                        class="w-20 bg-white border-gray-300 rounded-md shadow-sm placeholder:text-mainTextGray focus:border-sky-300 focus:ring focus:ring-sky-200 focus:ring-opacity-50">
                                                                </label>
                                                                <label
                                                                    class="flex flex-row items-center justify-start space-x-1">
                                                                    <span>加成</span>
                                                                    <input type="number"
                                                                        x-bind:data-level="index + ',' + child1_index + ',' + child2_index"
                                                                        x-bind:data-code="child2.code" placeholder="加成"
                                                                        step="0.1" min="0" x-model="child2.gain"
                                                                        class="w-20 bg-white border-gray-300 rounded-md shadow-sm placeholder:text-mainTextGray focus:border-sky-300 focus:ring focus:ring-sky-200 focus:ring-opacity-50">
                                                                </label>
                                                                <label
                                                                    class="flex flex-row items-center justify-start space-x-1">
                                                                    <span>分數上限</span>
                                                                    <input type="number"
                                                                        x-bind:data-level="index + ',' + child1_index + ',' + child2_index"
                                                                        x-bind:data-code="child2.code"
                                                                        placeholder="分數上限" step="0.1" min="0"
                                                                        x-model="child2.score_limit"
                                                                        class="w-20 bg-white border-gray-300 rounded-md shadow-sm placeholder:text-mainTextGray focus:border-sky-300 focus:ring focus:ring-sky-200 focus:ring-opacity-50">
                                                                </label>
                                                                <label
                                                                    class="flex flex-row items-center justify-start space-x-1">
                                                                    <input type="checkbox" value="1"
                                                                        x-bind:data-level="index + ',' + child1_index + ',' + child2_index"
                                                                        x-bind:data-code="child2.code"
                                                                        x-model="child2.upload"
                                                                        class="bg-white border-gray-300 rounded text-mainCyanDark"
                                                                        x-bind:checked="child2.upload">
                                                                    <span>上傳附件</span>
                                                                </label>
                                                                <label
                                                                    class="flex flex-row items-center justify-start space-x-1">
                                                                    <input type="checkbox" value="1"
                                                                        x-bind:data-level="index + ',' + child1_index + ',' + child2_index"
                                                                        x-bind:data-code="child2.code"
                                                                        x-model="child2.comment"
                                                                        class="bg-white border-gray-300 rounded text-mainCyanDark"
                                                                        x-bind:checked="child2.comment">
                                                                    <span>審查</span>
                                                                </label>
                                                            </div>
                                                        </template>
                                                    </div>
                                                    <template x-if="child2.type==='radio' || child2.type==='checkbox'">
                                                        <div class="flex flex-col w-full space-y-4">
                                                            <input type="text" data-check="test1"
                                                                x-bind:data-level="index + ',' + child1_index + ',' + child2_index"
                                                                x-bind:data-code="child2.code" placeholder="請輸入問題說明"
                                                                x-model="child2.content"
                                                                class="w-full bg-white border-gray-300 rounded-md shadow-sm placeholder:text-mainTextGray focus:border-sky-300 focus:ring focus:ring-sky-200 focus:ring-opacity-50">
                                                            <label
                                                                class="flex flex-row items-center justify-start w-full space-x-1">
                                                                <span>加成</span>
                                                                <input type="number"
                                                                    x-bind:data-level="index + ',' + child1_index + ',' + child2_index"
                                                                    x-bind:data-code="child2.code" placeholder="加成"
                                                                    step="0.1" min="0" x-model="child2.gain"
                                                                    class="flex-1 bg-white border-gray-300 rounded-md shadow-sm placeholder:text-mainTextGray focus:border-sky-300 focus:ring focus:ring-sky-200 focus:ring-opacity-50">
                                                            </label>
                                                            <label
                                                                class="flex flex-row items-center justify-start w-full space-x-1">
                                                                <span>分數上限</span>
                                                                <input type="number"
                                                                    x-bind:data-level="index + ',' + child1_index + ',' + child2_index"
                                                                    x-bind:data-code="child2.code" placeholder="分數上限"
                                                                    step="0.1" min="0" x-model="child2.score_limit"
                                                                    class="flex-1 bg-white border-gray-300 rounded-md shadow-sm placeholder:text-mainTextGray focus:border-sky-300 focus:ring focus:ring-sky-200 focus:ring-opacity-50">
                                                            </label>
                                                            <div class="flex flex-row w-full justify-evenly">
                                                                <label
                                                                    class="flex flex-row items-center justify-start w-full space-x-1">
                                                                    <input type="checkbox" value="1"
                                                                        x-bind:data-level="index + ',' + child1_index + ',' + child2_index"
                                                                        x-bind:data-code="child2.code"
                                                                        x-model="child2.upload"
                                                                        class="bg-white border-gray-300 rounded text-mainCyanDark"
                                                                        x-bind:checked="child2.upload">
                                                                    <span>上傳附件</span>
                                                                </label>
                                                                <label
                                                                    class="flex flex-row items-center justify-start w-full space-x-1">
                                                                    <input type="checkbox" value="1"
                                                                        x-bind:data-level="index + ',' + child1_index + ',' + child2_index"
                                                                        x-bind:data-code="child2.code"
                                                                        x-model="child2.comment"
                                                                        class="bg-white border-gray-300 rounded text-mainCyanDark"
                                                                        x-bind:checked="child2.comment">
                                                                    <span>審查</span>
                                                                </label>
                                                            </div>
                                                            <div class="w-full text-left text-mainTextGray">
                                                                設定方式：選項名稱==分數，中間用兩個等號連接，每一個斷行為一個答案
                                                            </div>
                                                        </div>
                                                    </template>
                                                </div>
                                                <div class="flex items-center justify-center flex-1 w-full ">
                                                    <template
                                                        x-if="child2.type==='bigger' || child2.type==='title' || child2.type==='basic' || child2.type==='advanced'">
                                                        <input type="text" placeholder="請輸入標題"
                                                            x-bind:data-level="index + ',' + child1_index + ',' + child2_index"
                                                            x-bind:data-code="child2.code"
                                                            :class="{'text-mainAdminTextGrayDark text-base':child2.type=='title','text-mainBlueTitle text-2xl':child2.type!='title'}"
                                                            x-model="child2.content"
                                                            class="w-full bg-white border-gray-300 rounded-md shadow-sm text-mainBlueTitle placeholder:text-mainTextGray focus:border-sky-300 focus:ring focus:ring-sky-200 focus:ring-opacity-50">
                                                    </template>
                                                    <template x-if="child2.type==='radio' || child2.type==='checkbox'">
                                                        <div class="flex flex-row w-full pl-4 text-center">
                                                            <textarea placeholder="請輸入選項：選項名稱==分數，每一個斷行為一個答案"
                                                                x-bind:data-level="index + ',' + child1_index + ',' + child2_index"
                                                                x-bind:data-code="child2.code" x-model="child2.options"
                                                                @change="scoreChange"
                                                                class="w-full bg-white border-gray-300 min-h-[19rem] rounded-md shadow-sm placeholder:text-mainTextGray focus:border-sky-300 focus:ring focus:ring-sky-200 focus:ring-opacity-50"></textarea>

                                                        </div>
                                                    </template>
                                                    <template x-if="child2.type==='text' || child2.type==='textarea'">
                                                        <div class="flex flex-row flex-wrap w-full mb-4 text-center">
                                                            <input type="text" placeholder="請輸入問題說明"
                                                                x-bind:data-level="index + ',' + child1_index + ',' + child2_index"
                                                                x-bind:data-code="child2.code" x-model="child2.content"
                                                                class="w-full bg-white border-gray-300 rounded-md shadow-sm placeholder:text-mainTextGray focus:border-sky-300 focus:ring focus:ring-sky-200 focus:ring-opacity-50">
                                                        </div>
                                                    </template>
                                                </div>
                                            </div>
                                            <template x-if="child2.child">
                                                <template x-for="(child3,child3_index) in child2.child">
                                                    <div
                                                        class="flex flex-col w-full pb-8 m-1 bg-gray-200 border rounded indent-2">
                                                        <div class="flex flex-col items-start justify-start w-full p-4 pb-8 space-y-2"
                                                            :class="{'flex-row':(child3.type==='radio' || child3.type==='checkbox'),'flex-col':!(child3.type==='radio' || child3.type==='checkbox')}">
                                                            <div class="flex flex-row items-center justify-start w-full space-x-6"
                                                                :class="{'flex-col items-start space-x-0 space-y-4 flex-1':(child3.type==='radio' || child3.type==='checkbox'),'flex-row w-full items-center space-x-6 space-y-0':!(child3.type==='radio' || child3.type==='checkbox')}">
                                                                <div
                                                                    class="flex flex-row items-center justify-start space-x-2">
                                                                    <select @change="itemChange" x-model='child3.type'
                                                                        x-bind:data-level="index + ',' + child1_index + ',' + child2_index + ',' + child3_index"
                                                                        x-bind:data-code="child3.code"
                                                                        class="inline-block align-middle bg-white border-gray-300 rounded-md shadow-sm w-52 disabled:bg-gray-100 disabled:cursor-not-allowed placeholder:text-mainTextGray focus:border-sky-300 focus:ring focus:ring-sky-200 focus:ring-opacity-50">
                                                                        <template x-if="!child3.score_type">
                                                                            <option value="basic" data-score="basic"
                                                                                x-bind:hidden="child3.score_type">
                                                                                基本指標
                                                                            </option>
                                                                        </template>
                                                                        <template x-if="!child3.score_type">
                                                                            <option value="advanced" data-score="basic"
                                                                                x-bind:hidden="child3.score_type">
                                                                                進階指標
                                                                            </option>
                                                                        </template>
                                                                        <option value="title" data-score="parent">小標題
                                                                        </option>
                                                                        <template x-if="child3.score_type">
                                                                            <option value="radio" data-score="parent"
                                                                                x-bind:hidden="!child3.score_type">
                                                                                單選題
                                                                            </option>
                                                                        </template>
                                                                        <template x-if="child3.score_type">
                                                                            <option value="checkbox" data-score="parent"
                                                                                x-bind:hidden="!child3.score_type">
                                                                                多選題
                                                                            </option>
                                                                        </template>
                                                                        <template x-if="child3.score_type">
                                                                            <option value="text" data-score="parent"
                                                                                x-bind:hidden="!child3.score_type">
                                                                                填空題
                                                                            </option>
                                                                        </template>
                                                                        <template x-if="child3.score_type">
                                                                            <option value="textarea" data-score="parent"
                                                                                x-bind:hidden="!child3.score_type">
                                                                                多行文字
                                                                            </option>
                                                                        </template>
                                                                    </select>
                                                                    <template x-if="child3.score_type">
                                                                        <span class="text-sm text-mainTextGray"
                                                                            x-text="child3.score_type=='basic'?'基本指標':child3.score_type=='advanced'?'進階指標':''"></span>
                                                                    </template>
                                                                    <template
                                                                        x-if="child3.type==='text' || child3.type==='textarea'">
                                                                        <div
                                                                            class="flex flex-row items-center justify-start space-x-4">
                                                                            <label
                                                                                class="flex flex-row items-center justify-start space-x-1">
                                                                                <span>得分</span>
                                                                                <input type="number"
                                                                                    x-bind:data-level="index + ',' + child1_index + ',' + child2_index + ',' + child3_index"
                                                                                    x-bind:data-code="child3.code"
                                                                                    placeholder="得分" step="0.1" min="0"
                                                                                    x-model="child3.options"
                                                                                    @change="scoreChange"
                                                                                    class="w-20 bg-white border-gray-300 rounded-md shadow-sm placeholder:text-mainTextGray focus:border-sky-300 focus:ring focus:ring-sky-200 focus:ring-opacity-50">
                                                                            </label>
                                                                            <label
                                                                                class="flex flex-row items-center justify-start space-x-1">
                                                                                <span>加成</span>
                                                                                <input type="number"
                                                                                    x-bind:data-level="index + ',' + child1_index + ',' + child2_index + ',' + child3_index"
                                                                                    x-bind:data-code="child3.code"
                                                                                    placeholder="加成" step="0.1" min="0"
                                                                                    x-model="child3.gain"
                                                                                    class="w-20 bg-white border-gray-300 rounded-md shadow-sm placeholder:text-mainTextGray focus:border-sky-300 focus:ring focus:ring-sky-200 focus:ring-opacity-50">
                                                                            </label>
                                                                            <label
                                                                                class="flex flex-row items-center justify-start space-x-1">
                                                                                <span>分數上限</span>
                                                                                <input type="number"
                                                                                    x-bind:data-level="index + ',' + child1_index + ',' + child2_index + ',' + child3_index"
                                                                                    x-bind:data-code="child3.code"
                                                                                    placeholder="分數上限" step="0.1"
                                                                                    min="0" x-model="child3.score_limit"
                                                                                    class="w-20 bg-white border-gray-300 rounded-md shadow-sm placeholder:text-mainTextGray focus:border-sky-300 focus:ring focus:ring-sky-200 focus:ring-opacity-50">
                                                                            </label>
                                                                            <label
                                                                                class="flex flex-row items-center justify-start space-x-1">
                                                                                <input type="checkbox" value="1"
                                                                                    x-bind:data-level="index + ',' + child1_index + ',' + child2_index + ',' + child3_index"
                                                                                    x-bind:data-code="child3.code"
                                                                                    x-model="child3.upload"
                                                                                    class="bg-white border-gray-300 rounded text-mainCyanDark"
                                                                                    x-bind:checked="child3.upload">
                                                                                <span>上傳附件</span>
                                                                            </label>
                                                                            <label
                                                                                class="flex flex-row items-center justify-start space-x-1">
                                                                                <input type="checkbox" value="1"
                                                                                    x-bind:data-level="index + ',' + child1_index + ',' + child2_index + ',' + child3_index"
                                                                                    x-bind:data-code="child3.code"
                                                                                    x-model="child3.comment"
                                                                                    class="bg-white border-gray-300 rounded text-mainCyanDark"
                                                                                    x-bind:checked="child3.comment">
                                                                                <span>審查</span>
                                                                            </label>
                                                                        </div>
                                                                    </template>
                                                                </div>
                                                                <template
                                                                    x-if="child3.type==='radio' || child3.type==='checkbox'">
                                                                    <div class="flex flex-col w-full space-y-4">
                                                                        <input type="text"
                                                                            x-bind:data-level="index + ',' + child1_index + ',' + child2_index + ',' + child3_index"
                                                                            x-bind:data-code="child3.code"
                                                                            placeholder="請輸入問題說明"
                                                                            x-model="child3.content"
                                                                            class="w-full bg-white border-gray-300 rounded-md shadow-sm placeholder:text-mainTextGray focus:border-sky-300 focus:ring focus:ring-sky-200 focus:ring-opacity-50">
                                                                        <label
                                                                            class="flex flex-row items-center justify-start w-full space-x-1">
                                                                            <span>加成</span>
                                                                            <input type="number"
                                                                                x-bind:data-level="index + ',' + child1_index + ',' + child2_index + ',' + child3_index"
                                                                                x-bind:data-code="child3.code"
                                                                                placeholder="加成" step="0.1" min="0"
                                                                                x-model="child3.gain"
                                                                                class="flex-1 bg-white border-gray-300 rounded-md shadow-sm placeholder:text-mainTextGray focus:border-sky-300 focus:ring focus:ring-sky-200 focus:ring-opacity-50">
                                                                        </label>
                                                                        <label
                                                                            class="flex flex-row items-center justify-start w-full space-x-1">
                                                                            <span>分數上限</span>
                                                                            <input type="number"
                                                                                x-bind:data-level="index + ',' + child1_index + ',' + child2_index + ',' + child3_index"
                                                                                x-bind:data-code="child3.code"
                                                                                placeholder="分數上限" step="0.1" min="0"
                                                                                x-model="child3.score_limit"
                                                                                class="flex-1 bg-white border-gray-300 rounded-md shadow-sm placeholder:text-mainTextGray focus:border-sky-300 focus:ring focus:ring-sky-200 focus:ring-opacity-50">
                                                                        </label>
                                                                        <div
                                                                            class="flex flex-row w-full justify-evenly">
                                                                            <label
                                                                                class="flex flex-row items-center justify-start w-full space-x-1">
                                                                                <input type="checkbox" value="1"
                                                                                    x-bind:data-level="index + ',' + child1_index + ',' + child2_index + ',' + child3_index"
                                                                                    x-bind:data-code="child3.code"
                                                                                    x-model="child3.upload"
                                                                                    class="bg-white border-gray-300 rounded text-mainCyanDark"
                                                                                    x-bind:checked="child3.upload">
                                                                                <span>上傳附件</span>
                                                                            </label>
                                                                            <label
                                                                                class="flex flex-row items-center justify-start w-full space-x-1">
                                                                                <input type="checkbox" value="1"
                                                                                    x-bind:data-level="index + ',' + child1_index + ',' + child2_index + ',' + child3_index"
                                                                                    x-bind:data-code="child3.code"
                                                                                    x-model="child3.comment"
                                                                                    class="bg-white border-gray-300 rounded text-mainCyanDark"
                                                                                    x-bind:checked="child3.comment">
                                                                                <span>審查</span>
                                                                            </label>
                                                                        </div>
                                                                        <div class="w-full text-left text-mainTextGray">
                                                                            設定方式：選項名稱==分數，中間用兩個等號連接，每一個斷行為一個答案
                                                                        </div>
                                                                    </div>
                                                                </template>
                                                            </div>
                                                            <div class="flex items-center justify-center flex-1 w-full">
                                                                <template
                                                                    x-if="child3.type==='title' || child3.type==='bigger'">
                                                                    <input type="text" placeholder="請輸入標題"
                                                                        x-bind:data-level="index + ',' + child1_index + ',' + child2_index + ',' + child3_index"
                                                                        x-bind:data-code="child3.code"
                                                                        x-model="child3.content"
                                                                        class="w-full bg-white border-gray-300 rounded-md shadow-sm placeholder:text-mainTextGray focus:border-sky-300 focus:ring focus:ring-sky-200 focus:ring-opacity-50">
                                                                </template>
                                                                <template
                                                                    x-if="child3.type==='radio' || child3.type==='checkbox'">
                                                                    <div class="flex flex-col w-full pl-4 text-center">
                                                                        <textarea
                                                                            placeholder="請輸入選項：選項名稱==分數，每一個斷行為一個答案"
                                                                            x-bind:data-level="index + ',' + child1_index + ',' + child2_index + ',' + child3_index"
                                                                            x-bind:data-code="child3.code"
                                                                            x-model="child3.options"
                                                                            @change="scoreChange"
                                                                            class="w-full whitespace-pre bg-white min-h-[19rem] border-gray-300 rounded-md shadow-sm placeholder:text-mainTextGray focus:border-sky-300 focus:ring focus:ring-sky-200 focus:ring-opacity-50"></textarea>
                                                                    </div>
                                                                </template>
                                                                <template
                                                                    x-if="child3.type==='text' || child3.type==='textarea'">
                                                                    <div
                                                                        class="flex flex-row flex-wrap w-full mb-4 text-center">
                                                                        <input type="text" placeholder="請輸入問題說明"
                                                                            x-bind:data-level="index + ',' + child1_index + ',' + child2_index + ',' + child3_index"
                                                                            x-bind:data-code="child3.code"
                                                                            x-model="child3.content"
                                                                            class="w-full bg-white border-gray-300 rounded-md shadow-sm placeholder:text-mainTextGray focus:border-sky-300 focus:ring focus:ring-sky-200 focus:ring-opacity-50">
                                                                    </div>
                                                                </template>
                                                            </div>
                                                        </div>
                                                        <template x-if="child3.child">
                                                            <template x-for="(child4,child4_index) in child3.child">
                                                                <div
                                                                    class="flex flex-col w-full m-1 bg-gray-300 border rounded indent-2">
                                                                    <div class="flex flex-col items-start justify-start w-full p-4 pb-8 space-y-2"
                                                                        :class="{'flex-row':(child4.type==='radio' || child4.type==='checkbox'),'flex-col':!(child4.type==='radio' || child4.type==='checkbox')}">
                                                                        <div class="flex flex-row items-center justify-start w-full space-x-6"
                                                                            :class="{'flex-col items-start space-x-0 space-y-4 flex-1':(child4.type==='radio' || child4.type==='checkbox'),'flex-row w-full items-center space-x-6 space-y-0':!(child4.type==='radio' || child4.type==='checkbox')}">
                                                                            <div
                                                                                class="flex flex-row items-center justify-start space-x-2">
                                                                                <select @change="itemChange"
                                                                                    x-model='child4.type'
                                                                                    x-bind:data-level="index + ',' + child1_index + ',' + child2_index + ',' + child3_index + ',' + child4_index"
                                                                                    x-bind:data-code="child4.code"
                                                                                    class="inline-block align-middle bg-white border-gray-300 rounded-md shadow-sm w-52 disabled:bg-gray-100 disabled:cursor-not-allowed placeholder:text-mainTextGray focus:border-sky-300 focus:ring focus:ring-sky-200 focus:ring-opacity-50">
                                                                                    <option value="title"
                                                                                        data-score="parent">
                                                                                        小標題</option>
                                                                                    <option value="radio"
                                                                                        data-score="parent">
                                                                                        單選題</option>
                                                                                    <option value="checkbox"
                                                                                        data-score="parent">
                                                                                        多選題</option>
                                                                                    <option value="text"
                                                                                        data-score="parent">
                                                                                        填空題</option>
                                                                                    <option value="textarea"
                                                                                        data-score="parent">
                                                                                        多行文字
                                                                                    </option>
                                                                                </select>
                                                                                <template x-if="child4.score_type">
                                                                                    <span
                                                                                        class="text-sm text-mainTextGray"
                                                                                        x-text="child4.score_type=='basic'?'基本指標':child4.score_type=='advanced'?'進階指標':''"></span>
                                                                                </template>
                                                                                <template
                                                                                    x-if="child4.type==='textarea'">
                                                                                    <div
                                                                                        class="flex flex-row items-center justify-start space-x-4">
                                                                                        <label
                                                                                            class="flex flex-row items-center justify-start space-x-1">
                                                                                            <span>得分</span>
                                                                                            <input type="number"
                                                                                                x-bind:data-level="index + ',' + child1_index + ',' + child2_index + ',' + child3_index + ',' + child4_index"
                                                                                                x-bind:data-code="child4.code"
                                                                                                placeholder="得分"
                                                                                                step="0.1" min="0"
                                                                                                x-model="child4.options"
                                                                                                @change="scoreChange"
                                                                                                class="w-20 bg-white border-gray-300 rounded-md shadow-sm placeholder:text-mainTextGray focus:border-sky-300 focus:ring focus:ring-sky-200 focus:ring-opacity-50">
                                                                                        </label>
                                                                                        <label
                                                                                            class="flex flex-row items-center justify-start space-x-1">
                                                                                            <span>加成</span>
                                                                                            <input type="number"
                                                                                                x-bind:data-level="index + ',' + child1_index + ',' + child2_index + ',' + child3_index + ',' + child4_index"
                                                                                                x-bind:data-code="child4.code"
                                                                                                placeholder="加成"
                                                                                                step="0.1" min="0"
                                                                                                x-model="child4.gain"
                                                                                                class="w-20 bg-white border-gray-300 rounded-md shadow-sm placeholder:text-mainTextGray focus:border-sky-300 focus:ring focus:ring-sky-200 focus:ring-opacity-50">
                                                                                        </label>
                                                                                        <label
                                                                                            class="flex flex-row items-center justify-start space-x-1">
                                                                                            <span>分數上限</span>
                                                                                            <input type="number"
                                                                                                x-bind:data-level="index + ',' + child1_index + ',' + child2_index + ',' + child3_index + ',' + child4_index"
                                                                                                x-bind:data-code="child4.code"
                                                                                                placeholder="分數上限"
                                                                                                step="0.1" min="0"
                                                                                                x-model="child4.score_limit"
                                                                                                class="w-20 bg-white border-gray-300 rounded-md shadow-sm placeholder:text-mainTextGray focus:border-sky-300 focus:ring focus:ring-sky-200 focus:ring-opacity-50">
                                                                                        </label>
                                                                                        <label
                                                                                            class="flex flex-row items-center justify-start space-x-1">
                                                                                            <input type="checkbox"
                                                                                                value="1"
                                                                                                x-bind:data-level="index + ',' + child1_index + ',' + child2_index + ',' + child3_index + ',' + child4_index"
                                                                                                x-bind:data-code="child4.code"
                                                                                                x-model="child4.upload"
                                                                                                class="bg-white border-gray-300 rounded text-mainCyanDark"
                                                                                                x-bind:checked="child4.upload">
                                                                                            <span>上傳附件</span>
                                                                                        </label>
                                                                                        <label
                                                                                            class="flex flex-row items-center justify-start space-x-1">
                                                                                            <input type="checkbox"
                                                                                                value="1"
                                                                                                x-bind:data-level="index + ',' + child1_index + ',' + child2_index + ',' + child3_index + ',' + child4_index"
                                                                                                x-bind:data-code="child4.code"
                                                                                                x-model="child4.comment"
                                                                                                class="bg-white border-gray-300 rounded text-mainCyanDark"
                                                                                                x-bind:checked="child4.comment">
                                                                                            <span>審查</span>
                                                                                        </label>
                                                                                    </div>
                                                                                </template>
                                                                            </div>
                                                                            <template
                                                                                x-if="child4.type==='radio' || child4.type==='checkbox'">
                                                                                <div
                                                                                    class="flex flex-col w-full space-y-4">
                                                                                    <input type="text"
                                                                                        x-bind:data-level="index + ',' + child1_index + ',' + child2_index + ',' + child3_index + ',' + child4_index"
                                                                                        x-bind:data-code="child4.code"
                                                                                        placeholder="請輸入問題說明"
                                                                                        x-model="child4.content"
                                                                                        class="w-full bg-white border-gray-300 rounded-md shadow-sm placeholder:text-mainTextGray focus:border-sky-300 focus:ring focus:ring-sky-200 focus:ring-opacity-50">
                                                                                    <label
                                                                                        class="flex flex-row items-center justify-start w-full space-x-1">
                                                                                        <span>加成</span>
                                                                                        <input type="number"
                                                                                            x-bind:data-level="index + ',' + child1_index + ',' + child2_index + ',' + child3_index + ',' + child4_index"
                                                                                            x-bind:data-code="child4.code"
                                                                                            placeholder="加成" step="0.1"
                                                                                            min="0"
                                                                                            x-model="child4.gain"
                                                                                            class="flex-1 bg-white border-gray-300 rounded-md shadow-sm placeholder:text-mainTextGray focus:border-sky-300 focus:ring focus:ring-sky-200 focus:ring-opacity-50">
                                                                                    </label>
                                                                                    <label
                                                                                        class="flex flex-row items-center justify-start w-full space-x-1">
                                                                                        <span>分數上限</span>
                                                                                        <input type="number"
                                                                                            x-bind:data-level="index + ',' + child1_index + ',' + child2_index + ',' + child3_index + ',' + child4_index"
                                                                                            x-bind:data-code="child4.code"
                                                                                            placeholder="分數上限"
                                                                                            step="0.1" min="0"
                                                                                            x-model="child4.score_limit"
                                                                                            class="flex-1 bg-white border-gray-300 rounded-md shadow-sm placeholder:text-mainTextGray focus:border-sky-300 focus:ring focus:ring-sky-200 focus:ring-opacity-50">
                                                                                    </label>
                                                                                    <div
                                                                                        class="flex flex-row w-full justify-evenly">
                                                                                        <label
                                                                                            class="flex flex-row items-center justify-start w-full space-x-1">
                                                                                            <input type="checkbox"
                                                                                                value="1"
                                                                                                x-bind:data-level="index + ',' + child1_index + ',' + child2_index + ',' + child3_index + ',' + child4_index"
                                                                                                x-bind:data-code="child4.code"
                                                                                                x-model="child4.upload"
                                                                                                class="bg-white border-gray-300 rounded text-mainCyanDark"
                                                                                                x-bind:checked="child4.upload">
                                                                                            <span>上傳附件</span>
                                                                                        </label>
                                                                                        <label
                                                                                            class="flex flex-row items-center justify-start w-full space-x-1">
                                                                                            <input type="checkbox"
                                                                                                value="1"
                                                                                                x-bind:data-level="index + ',' + child1_index + ',' + child2_index + ',' + child3_index + ',' + child4_index"
                                                                                                x-bind:data-code="child4.code"
                                                                                                x-model="child4.comment"
                                                                                                class="bg-white border-gray-300 rounded text-mainCyanDark"
                                                                                                x-bind:checked="child4.comment">
                                                                                            <span>審查</span>
                                                                                        </label>
                                                                                    </div>
                                                                                    <div
                                                                                        class="w-full text-left text-mainTextGray">
                                                                                        設定方式：選項名稱==分數，中間用兩個等號連接，每一個斷行為一個答案
                                                                                    </div>
                                                                                </div>
                                                                            </template>
                                                                        </div>
                                                                        <div
                                                                            class="flex items-center justify-center flex-1 w-full">
                                                                            <template x-if="child4.type==='title'">
                                                                                <input type="text" placeholder="請輸入小標題"
                                                                                    x-bind:data-level="index + ',' + child1_index + ',' + child2_index + ',' + child3_index + ',' + child4_index"
                                                                                    x-bind:data-code="child4.code"
                                                                                    x-model="child4.content"
                                                                                    class="w-full bg-white border-gray-300 rounded-md shadow-sm placeholder:text-mainTextGray focus:border-sky-300 focus:ring focus:ring-sky-200 focus:ring-opacity-50">
                                                                            </template>
                                                                            <template
                                                                                x-if="child4.type==='radio' || child4.type==='checkbox'">
                                                                                <div
                                                                                    class="flex flex-col w-full pl-4 text-center">
                                                                                    <textarea
                                                                                        x-bind:data-level="index + ',' + child1_index + ',' + child2_index + ',' + child3_index + ',' + child4_index"
                                                                                        x-bind:data-code="child4.code"
                                                                                        placeholder="請輸入選項：選項名稱==分數，每一個斷行為一個答案"
                                                                                        x-model="child4.options"
                                                                                        @change="scoreChange"
                                                                                        class="w-full whitespace-pre min-h-[19rem] bg-white border-gray-300 rounded-md shadow-sm placeholder:text-mainTextGray focus:border-sky-300 focus:ring focus:ring-sky-200 focus:ring-opacity-50"></textarea>

                                                                                </div>
                                                                            </template>
                                                                            <template
                                                                                x-if="child4.type==='text' || child4.type==='textarea'">
                                                                                <div
                                                                                    class="flex flex-row flex-wrap w-full mb-4 text-center">
                                                                                    <input type="text"
                                                                                        x-bind:data-level="index + ',' + child1_index + ',' + child2_index + ',' + child3_index + ',' + child4_index"
                                                                                        x-bind:data-code="child4.code"
                                                                                        placeholder="請輸入問題說明"
                                                                                        x-model="child4.content"
                                                                                        class="w-full bg-white border-gray-300 rounded-md shadow-sm placeholder:text-mainTextGray focus:border-sky-300 focus:ring focus:ring-sky-200 focus:ring-opacity-50">
                                                                                </div>
                                                                            </template>
                                                                        </div>
                                                                    </div>
                                                                    <template
                                                                        x-if="(can_edit || ((index===questionnaire.questions.length-1)
                                                                     && (question.child && child1_index===question.child.length-1)
                                                                     && (child1.child && child2_index===child1.child.length-1)
                                                                     && (child2.child && child3_index===child2.child.length-1)
                                                                     && (child3.child && child4_index===child3.child.length-1)))">
                                                                        <div
                                                                            class="flex flex-row justify-between w-full">
                                                                            <button @click="addItem" type="button"
                                                                                x-bind:data-level="index + ',' + child1_index + ',' + child2_index + ',' + child3_index + ',' + child4_index"
                                                                                x-bind:data-code="child4.code"
                                                                                class="flex items-center justify-center w-8 hover:w-40 hover:px-4 after:whitespace-nowrap after:text-sm after:overflow-scroll hover:before:hidden after:hidden after:content-['新增第五階項目'] hover:after:block h-8 -ml-12 -mt-8 mb-2 before:content-['+'] before:text-2xl before:font-bold text-white transition-all rounded-full bg-mainCyanDark hover:bg-teal-400"></button>
                                                                            <span class="mt-2 text-xs text-mainTextGray"
                                                                                x-text="(child4.type=='bigger'?'大標題':(child4.type=='title'?'小標題':(child4.type=='advanced'?'進階指標':(child4.type=='basic'?'基本指標':(child4.type=='radio'?'單選題':(child4.type=='checkbox'?'多選題':(child4.type=='textarea'?'多行文字':(child4.type=='text'?'填空題':child4.type))))))))+'：'+child4.content"></span>
                                                                            <button type="button"
                                                                                @click="deleteBlock(index + ',' + child1_index + ',' + child2_index + ',' + child3_index + ',' + child4_index)"
                                                                                x-bind:data-code="child4.code"
                                                                                class="flex items-center justify-center w-8 h-8 mb-2 mr-5 -mt-8 text-white transition-all rounded-full bg-rose-500 hover:bg-rose-400">
                                                                                <i
                                                                                    class="w-3 h-3 i-fa6-solid-trash"></i>
                                                                            </button>
                                                                        </div>
                                                                    </template>
                                                                </div>
                                                            </template>
                                                        </template>
                                                        <template
                                                            x-if="(can_edit || ((index===questionnaire.questions.length-1)
                                                            && (question.child && child1_index===question.child.length-1)
                                                            && (child1.child && child2_index===child1.child.length-1)
                                                            && (child2.child && child3_index===child2.child.length-1)))">
                                                            <div class="flex flex-row justify-between w-full">
                                                                <div class="flex flex-row space-x-6">
                                                                    <button @click="addItem" type="button"
                                                                        x-bind:data-level="index + ',' + child1_index + ',' + child2_index + ',' + child3_index"
                                                                        x-bind:data-code="child3.code"
                                                                        class="flex items-center justify-center w-8 hover:w-40 hover:px-4 after:whitespace-nowrap after:text-sm after:overflow-scroll hover:before:hidden after:hidden after:content-['新增第四階項目'] hover:after:block h-8 -ml-12 -mb-6 before:content-['+'] before:text-2xl before:font-bold text-white transition-all rounded-full bg-mainCyanDark hover:bg-teal-400"></button>
                                                                    <button @click="addSub" type="button"
                                                                        x-bind:data-level="index + ',' + child1_index + ',' + child2_index + ',' + child3_index"
                                                                        x-bind:data-code="child3.code"
                                                                        class="flex items-center justify-center w-8 hover:w-40 hover:px-4 after:whitespace-nowrap after:text-sm after:overflow-scroll hover:before:hidden after:hidden after:content-['新增第五階項目'] hover:after:block h-8 -ml-12 -mb-6 before:content-['▹'] before:text-2xl before:font-bold text-white transition-all rounded-full bg-mainBlueDark hover:bg-mainBlue"></button>
                                                                </div>
                                                                <span class="mt-2 text-xs text-mainTextGray"
                                                                    x-text="(child3.type=='bigger'?'大標題':(child3.type=='title'?'小標題':(child3.type=='advanced'?'進階指標':(child3.type=='basic'?'基本指標':(child3.type=='radio'?'單選題':(child3.type=='checkbox'?'多選題':(child3.type=='textarea'?'多行文字':(child3.type=='text'?'填空題':child3.type))))))))+'：'+child3.content"></span>
                                                                <button type="button"
                                                                    @click="deleteBlock(index + ',' + child1_index + ',' + child2_index + ',' + child3_index)"
                                                                    class="flex items-center justify-center w-8 h-8 mr-4 -mb-6 text-white transition-all rounded-full bg-rose-500 hover:bg-rose-400">
                                                                    <i class="w-3 h-3 i-fa6-solid-trash"></i>
                                                                </button>
                                                            </div>
                                                        </template>
                                                    </div>
                                                </template>
                                            </template>
                                            <template x-if="(can_edit || ((index===questionnaire.questions.length-1)
                                                && (question.child && child1_index===question.child.length-1)
                                                && (child1.child && child2_index===child1.child.length-1)))">
                                                <div class="flex flex-row justify-between w-full">
                                                    <div class="flex flex-row space-x-6">
                                                        <button @click="addItem" type="button"
                                                            x-bind:data-level="index + ',' + child1_index + ',' + child2_index"
                                                            x-bind:data-code="child2.code"
                                                            class="flex items-center justify-center w-8 hover:w-40 hover:px-4 after:whitespace-nowrap after:text-sm after:overflow-scroll hover:before:hidden after:hidden after:content-['新增第三階項目'] hover:after:block h-8 -ml-12 -mb-6 before:content-['+'] before:text-2xl before:font-bold text-white transition-all rounded-full bg-mainCyanDark hover:bg-teal-400"></button>
                                                        <button @click="addSub" type="button"
                                                            x-bind:data-level="index + ',' + child1_index + ',' + child2_index"
                                                            x-bind:data-code="child2.code"
                                                            class="flex items-center justify-center w-8 hover:w-40 hover:px-4 after:whitespace-nowrap after:text-sm after:overflow-scroll hover:before:hidden after:hidden after:content-['新增第四階項目'] hover:after:block h-8 -ml-12 -mb-6 before:content-['▹'] before:text-2xl before:font-bold text-white transition-all rounded-full bg-mainBlueDark hover:bg-mainBlue"></button>
                                                    </div>
                                                    <span class="mt-2 text-xs text-mainTextGray"
                                                        x-text="(child2.type=='bigger'?'大標題':(child2.type=='title'?'小標題':(child2.type=='advanced'?'進階指標':(child2.type=='basic'?'基本指標':(child2.type=='radio'?'單選題':(child2.type=='checkbox'?'多選題':(child2.type=='textarea'?'多行文字':(child2.type=='text'?'填空題':child2.type))))))))+'：'+child2.content"></span>
                                                    <button type="button"
                                                        @click="deleteBlock(index + ',' + child1_index + ',' + child2_index)"
                                                        class="flex items-center justify-center w-8 h-8 mr-[0.85rem] -mb-6 text-white transition-all rounded-full bg-rose-500 hover:bg-rose-400">
                                                        <i class="w-3 h-3 i-fa6-solid-trash"></i>
                                                    </button>
                                                </div>
                                            </template>
                                        </div>
                                    </template>
                                </template>
                                <template x-if="(can_edit ||((index===questionnaire.questions.length-1)
                                    && (question.child && child1_index===question.child.length-1)))">
                                    <div class="flex flex-row justify-between w-full">
                                        <div class="flex flex-row space-x-6">
                                            <button type="button" @click="addItem"
                                                x-bind:data-level="index + ',' + child1_index"
                                                x-bind:data-code="child1.code"
                                                class="flex items-center justify-center w-8 hover:w-40 hover:px-4 after:whitespace-nowrap after:text-sm after:overflow-scroll hover:before:hidden after:hidden after:content-['新增第二階項目'] hover:after:block h-8 -ml-12 -mb-6 before:content-['+'] before:text-2xl before:font-bold text-white transition-all rounded-full bg-mainCyanDark hover:bg-teal-400"></button>
                                            <button type="button" @click="addSub"
                                                x-bind:data-level="index + ',' + child1_index"
                                                x-bind:data-code="child1.code"
                                                class="flex items-center justify-center w-8 hover:w-40 hover:px-4 after:whitespace-nowrap after:text-sm after:overflow-scroll hover:before:hidden after:hidden after:content-['新增第三階項目'] hover:after:block h-8 -ml-12 -mb-6 before:content-['▹'] before:text-2xl before:font-bold text-white transition-all rounded-full bg-mainBlueDark hover:bg-mainBlue"></button>
                                        </div>
                                        <span class="mt-2 text-xs text-mainTextGray"
                                            x-text="(child1.type=='bigger'?'大標題':(child1.type=='title'?'小標題':(child1.type=='advanced'?'進階指標':(child1.type=='basic'?'基本指標':(child1.type=='radio'?'單選題':(child1.type=='checkbox'?'多選題':(child1.type=='textarea'?'多行文字':(child1.type=='text'?'填空題':child1.type))))))))+'：'+child1.content"></span>
                                        <button type="button" @click="deleteBlock(index + ',' + child1_index)"
                                            class="flex items-center justify-center w-8 h-8 mr-2.5 -mb-6 text-white transition-all rounded-full bg-rose-500 hover:bg-rose-400">
                                            <i class="w-3 h-3 i-fa6-solid-trash"></i>
                                        </button>
                                    </div>
                                </template>
                            </div>
                        </template>
                    </template>
                    <template x-if="(can_edit || (index===questionnaire.questions.length-1))">
                        <div class="flex flex-row justify-between w-full">
                            <div class="flex flex-row space-x-6">
                                <button type="button" @click="addItem" x-bind:data-level="index"
                                    x-bind:data-code="question.code"
                                    class="flex items-center justify-center w-8 hover:w-40 hover:px-4 after:whitespace-nowrap after:text-sm after:overflow-scroll hover:before:hidden after:hidden after:content-['新增第一階項目'] hover:after:block h-8 -ml-12 -mb-6 before:content-['+'] before:text-2xl before:font-bold text-white transition-all rounded-full bg-mainCyanDark hover:bg-teal-400"></button>
                                <button type="button" @click="addSub" x-bind:data-level="index"
                                    x-bind:data-code="question.code"
                                    class="flex items-center justify-center w-8 hover:w-40 hover:px-4 after:whitespace-nowrap after:text-sm after:overflow-scroll hover:before:hidden after:hidden after:content-['新增第二階項目'] hover:after:block h-8 -ml-12 -mb-6 before:content-['▹'] before:text-2xl before:font-bold text-white transition-all rounded-full bg-mainBlueDark hover:bg-mainBlue"></button>
                            </div>
                            <span class="mt-2 text-xs text-mainTextGray"
                                x-text="(question.type=='bigger'?'大標題':(question.type=='title'?'小標題':(question.type=='advanced'?'進階指標':(question.type=='basic'?'基本指標':(question.type=='radio'?'單選題':(question.type=='checkbox'?'多選題':(question.type=='textarea'?'多行文字':(question.type=='text'?'填空題':question.type))))))))+'：'+question.content"></span>
                            <button type="button" @click="deleteBlock(index)"
                                class="flex items-center justify-center w-8 h-8 mr-2 -mb-6 text-white transition-all rounded-full bg-rose-500 hover:bg-rose-400">
                                <i class="w-3 h-3 i-fa6-solid-trash"></i>
                            </button>
                        </div>
                    </template>
                </div>
            </template>
            <div class="flex items-center justify-center w-full p-4">
                <button type="submit"
                    class="flex items-center justify-center h-10 text-sm text-white rounded cursor-pointer w-28 bg-mainCyanDark hover:bg-teal-400">儲存</button>
            </div>
        </form>
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