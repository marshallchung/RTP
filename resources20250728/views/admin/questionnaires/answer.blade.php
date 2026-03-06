<?php
if (!function_exists('fixJson')) {
    function fixJson($jsonStr)
    {
        return str_replace(['\\"', '\n', '\r', "\n", "\r"], ['\\\"', '', '', '', ''], $jsonStr);
    }
}
?>

@extends('admin.layouts.dashboard', [
'heading' => '填寫績效評估自評表',
'breadcrumbs' => [
['績效評估自評表列表', route('admin.questionnaire.index')],
preg_match('/show$/',request()->url())?'檢視':'填寫'
]
])

@section('title', $questionnaire['title'])

@section('styling')
@endsection

@section('inner_content')
<div class="flex flex-row items-start justify-start w-full" x-data="{
    loading:false,
    disableAll:{{ $disableAll?'true':'false' }},
    has_create_permission:{{ $has_create_permission?'true':'false' }},
    auth_user:{{ Auth::user()->toJson(JSON_PRETTY_PRINT) }},
    questionnaire:{{ json_encode($questionnaire) }},
    cacheClick(e){
        this.questionnaire.pivot.status=0;
        document.querySelector('#status').value=0;
        e.target.closest('form').submit();
    },
    submitClick(e){
        this.questionnaire.pivot.status=1;
        document.querySelector('#status').value=1;
        e.target.closest('form').submit();
    },
    changeScore(){
        var basic_total_score = 0;
        var advance_total_score = 0;
        this.questionnaire.questions.forEach((question,index)=>{
            if(question.type=='checkbox' || question.type=='radio'){
                var basic_score=0;
                var advance_score=0;
                var gain=question.gain;
                var score_limit=question.score_limit;
                for (const [opt_index, option] of Object.entries(question.options)) {
                    if(option.selected){
                        if(question.score_type=='basic'){
                            basic_score += (option.score * ( gain>0 ? gain : 1 ));
                        }else{
                            advance_score += (option.score * ( gain>0 ? gain : 1 ));
                        }
                    }
                }
                basic_score= (score_limit>0 && basic_score > score_limit) ? score_limit : basic_score;
                basic_total_score+=basic_score;
                advance_score= (score_limit>0 && advance_score > score_limit) ? score_limit : advance_score;
                advance_total_score+=advance_score;
                this.questionnaire.questions[index].basic_score=basic_score;
                this.questionnaire.questions[index].advance_score=advance_score;
            }else if(question.type=='text' || question.type=='textarea'){
                if(!isNaN(question.options) && !isNaN(parseFloat(question.options)) && parseFloat(question.options)>0 && question.answer.length>0){
                    console.log(JSON.stringify(question));
                    var basic_score=0;
                    var advance_score=0;
                    var gain=question.gain;
                    var score_limit=question.score_limit;
                    if(question.score_type=='basic'){
                        basic_score += (parseFloat(question.options) * ( gain>0 ? gain : 1 ));
                    }else{
                        advance_score += (parseFloat(question.options) * ( gain>0 ? gain : 1 ));
                    }
                    basic_score= (score_limit>0 && basic_score > score_limit) ? score_limit : basic_score;
                    basic_total_score+=basic_score;
                    advance_score= (score_limit>0 && advance_score > score_limit) ? score_limit : advance_score;
                    advance_total_score+=advance_score;
                    this.questionnaire.questions[index].basic_score=basic_score;
                    this.questionnaire.questions[index].advance_score=advance_score;
                }else{
                    this.questionnaire.questions[index].basic_score=0;
                    this.questionnaire.questions[index].advance_score=0;
                }
            }
        });
        this.questionnaire.basic_total_score=basic_total_score;
        this.questionnaire.advance_total_score=advance_total_score;
    },
    inputClick(e){
        var index=parseInt(e.target.dataset.index);
        var type=this.questionnaire.questions[index].type;
        var options=this.questionnaire.questions[index].options;
        var score_type=this.questionnaire.questions[index].score_type;
        var basic_score=this.questionnaire.questions[index].basic_score;
        var advance_score=this.questionnaire.questions[index].advance_score;
        var score_limit=this.questionnaire.questions[index].score_limit;
        if(type=='radio'){
            this.questionnaire.questions[index].answer;
            for (const [opt_index, option] of Object.entries(options)) {
                if(opt_index==this.questionnaire.questions[index].answer){
                    this.questionnaire.questions[index].options[opt_index].selected=true;
                }else{
                    this.questionnaire.questions[index].options[opt_index].selected=false;
                }
            }
        }
        this.changeScore();
    },
    getCommitData(){
        var data = {};
        this.questionnaire.questions.forEach((question,index)=>{
            if(question.comment_content && question.comment_content.length>0){
                console.log('question[' + question.id + '] = ' + question.comment_content);
                data[question.id] = question.comment_content;
            }
        });
        return data;
    },
    btnSubmitComments(e){
        var url = '/admin/questionnaire/' + this.account.id + '/' + this.questionnaire.id + '/submitComments';
        var data=this.getCommitData();
        data=JSON.stringify(data);
        console.log('data: ' + data);
        this.loading=true;
        fetch(url,{
            method:'POST',
            body:data,
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
            if (json.ok == 1) {
                alert(json.msg);
            }
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
    account:{{ $account->toJson(JSON_PRETTY_PRINT) }},
}" x-init="$nextTick(() => {
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
    <div class="flex flex-col items-center justify-start w-full max-w-6xl p-6 space-y-4 text-mainAdminTextGrayDark">
        <div class="flex flex-col items-center justify-center w-full">
            <h4 x-text="questionnaire.title"></h4>
            <template x-if="questionnaire.has_answer">
                <span
                    x-text="account.name + '，初次提交時間' + (new Date(questionnaire.pivot.created_at)).toLocaleString('chinese',{hour12:false}) + '，最後修改時間' + (new Date(questionnaire.pivot.updated_at)).toLocaleString('chinese',{hour12:false})"></span>
            </template>
        </div>

        <form id="formAnswers" class="flex flex-col items-start justify-start w-full max-w-5xl space-y-4" method="post"
            :action="'/admin/questionnaire/' + account.id + '/' + questionnaire.id + '/submit'"
            enctype="multipart/form-data" accept-charset="UTF-8">
            {!! csrf_field() !!}
            <input id="js-removed-files" name="removed_files" type="hidden" value="[]">
            <template x-for="(question,index) in questionnaire.questions">
                <div class="flex flex-col w-full m-1" :data-score-type="question.score_type"
                    :class="{'indent-1':question.indent===2,'indent-2':question.indent===3,'indent-3':question.indent===4,'indent-4':question.indent===5,'indent-5':question.indent===6,'indent-6':question.indent===7,}">
                    <div class="w-full">
                        <template x-if="question.type==='bigger'">
                            <h4 class="mt-5 text-mainBlueTitle"><strong x-text="question.content"></strong></h4>
                        </template>
                        <template x-if="question.type!=='bigger'">
                            <div class="flex flex-row space-x-1">
                                <span class="title" x-bind:basic_score="question.basic_score"
                                    x-bind:advance_score="question.advance_score" x-text="question.content"></span>
                                <template x-if="(question.basic_score + question.advance_score)>0">
                                    <span class="text-sm text-orange-500 whitespace-nowrap"
                                        x-bind:data-code="question.code"
                                        x-text="'(得分: ' + (question.basic_score + question.advance_score) + '分)'"></span>
                                </template>
                            </div>
                        </template>
                    </div>
                    <template x-if="question.type==='radio'">
                        <div class="flex flex-row flex-wrap mb-4 text-center">
                            <select :name="question.id" x-model='questionnaire.questions[index].answer'
                                x-bind:data-index="index" @change="inputClick"
                                class="inline-block w-full align-middle bg-white border-gray-300 rounded-md shadow-sm placeholder:text-mainTextGray focus:border-sky-300 focus:ring focus:ring-sky-200 focus:ring-opacity-50"
                                :class="{'bg-mainLight':disableAll,'bg-white':!disableAll}"
                                x-bind:disabled="disableAll">
                                <option value="">（請作答）</option>
                                <template x-for="(opt_data,opt_name) in question.options">
                                    <option :value="opt_name" x-bind:data-score="opt_data.score" x-text="opt_name"
                                        x-bind:selected="opt_data.selected"></option>
                                </template>
                            </select>

                        </div>
                    </template>
                    <template x-if="question.type==='checkbox'">
                        <div class="flex flex-row flex-wrap mb-4 space-x-6 text-center">
                            <template x-for="(opt_data,opt_name) in question.options">
                                <label class="flex flex-row items-center justify-start">
                                    <input :name="question.id + '[]'" type="checkbox" x-bind:data-index="index"
                                        @change="inputClick"
                                        x-model='questionnaire.questions[index].options[opt_name].selected'
                                        :value="opt_name" x-bind:data-score="opt_data.score"
                                        x-bind:checked="opt_data.selected"
                                        class="bg-white border-gray-300 rounded text-mainCyanDark"
                                        x-bind:disabled="disableAll">
                                    <span x-text="opt_name" class="-ml-2 text-left cursor-pointer"
                                        :class="{'text-gray-400':disableAll,'text-mainAdminTextGrayDark':!disableAll}"></span>
                                </label>
                            </template>
                        </div>
                    </template>
                    <template x-if="question.type==='text'">
                        <div class="flex flex-row flex-wrap w-full mb-4 text-center">
                            <label class="flex flex-row items-center justify-start w-full space-x-1">
                                <input :name="question.id" type="text" x-model='questionnaire.questions[index].answer'
                                    x-bind:data-index="index" @change="inputClick"
                                    class="inline-block w-full align-middle bg-white border-gray-300 rounded-md shadow-sm placeholder:text-mainTextGray focus:border-sky-300 focus:ring focus:ring-sky-200 focus:ring-opacity-50"
                                    :class="{'bg-mainLight':disableAll,'bg-white':!disableAll}"
                                    x-bind:disabled="disableAll">
                            </label>
                        </div>
                    </template>
                    <template x-if="question.type==='textarea'">
                        <div class="flex flex-row flex-wrap w-full mb-4 text-center">
                            <label class="flex flex-row items-center justify-start w-full space-x-1">
                                <textarea :name="question.id" x-model='questionnaire.questions[index].answer'
                                    x-bind:data-index="index" @change="inputClick" rows="2"
                                    class="inline-block w-full align-middle bg-white border-gray-300 rounded-md shadow-sm placeholder:text-mainTextGray focus:border-sky-300 focus:ring focus:ring-sky-200 focus:ring-opacity-50"
                                    :class="{'bg-mainLight':disableAll,'bg-white':!disableAll}"
                                    x-bind:disabled="disableAll"></textarea>
                            </label>
                        </div>
                    </template>
                    <template x-if="question.upload===1">
                        <div class="flex flex-col mb-4">
                            <div class="flex flex-row flex-wrap mb-2 text-center">
                                <label x-bind:for="'files_' + question.id + '[]'">上傳附檔</label>
                                <input multiple="true" :name="'files_' + question.id + '[]'" type="file"
                                    :id="'files_' + question.id + '[]'"
                                    accept=".pdf, .doc, .docx, .jpg, .jpeg, .png, .gif, .zip, .rar, .txt, .csv, .xlsx, .odf, .mp4, .mov"
                                    x-bind:disabled="disableAll">
                            </div>
                            <div class="flex flex-col w-full mb-6 space-y-1">
                                <template x-for="(file,file_index) in question.files">
                                    <div class="flex flex-row items-center justify-start w-full p-2 border border-gray-300 rounded bg-mainLight well filter brightness-90"
                                        x-bind:data-id="file.id">
                                        <a :href="'/' + file.path" target="_blank"
                                            class="flex-1 text-left text-mainBlueDark" x-text="file.name"></a>
                                        <template x-if="!disableAll">
                                            <span
                                                class="px-4 text-sm text-white rounded cursor-pointer py-1.5 js-remove-file bg-rose-600 hover:bg-rose-500">刪除</span>
                                        </template>
                                    </div>
                                </template>
                            </div>
                        </div>
                    </template>
                    <template x-if="question.type !=='bigger' && question.type !=='title' && question.comment===1">
                        <div class="flex flex-col items-center mb-6">
                            <label class="text-sm text-gray-400"
                                :class="{'cursor-not-allowed text-gray-400':(auth_user.origin_role > 2 && disableAll),'cursor-auto text-mainAdminTextGrayDark':!(auth_user.origin_role > 2 && disableAll)}">審查意見</label>
                            <textarea rows="2" term="comment" :name="'comment' + question.id"
                                x-model='questionnaire.questions[index].comment_content'
                                class="inline-block w-full align-middle bg-white border-gray-300 rounded-md shadow-sm disabled:bg-gray-100 disabled:cursor-not-allowed placeholder:text-mainTextGray focus:border-sky-300 focus:ring focus:ring-sky-200 focus:ring-opacity-50"
                                :class="{'cursor-not-allowed bg-mainLight':(auth_user.origin_role > 2 && disableAll),'cursor-auto bg-white':!(auth_user.origin_role > 2 && disableAll)}"
                                x-bind:disabled="!has_create_permission"></textarea>

                        </div>
                    </template>
            </template>
            <div class="flex flex-row items-center justify-center w-full space-x-6">
                <div class="flex flex-row items-center justify-center space-x-2">
                    <span>基礎指標:</span>
                    <span id="basicScoreCount" class="font-bold text-orange-600"
                        x-text="questionnaire.basic_total_score"></span>
                    <span class="text-sm">(加權值：</span>
                    <span id="basicScoreCount" class="text-sm font-bold text-orange-600"
                        x-text="questionnaire.basic_total_score + '*' + questionnaire.basic_weight + '=' + (questionnaire.basic_total_score * questionnaire.basic_weight).toFixed(1)"></span>
                    <span class="text-sm">)</span>
                </div>
                <div class="flex flex-row items-center justify-center space-x-2">
                    <span>進階指標:</span>
                    <span id="advancedScoreCount" class="font-bold text-orange-600"
                        x-text="questionnaire.advance_total_score"></span>
                    <span class="text-sm">(加權值：</span>
                    <span id="basicScoreCount" class="text-sm font-bold text-orange-600"
                        x-text="questionnaire.advance_total_score + '*' + questionnaire.advanced_weight + '=' + (questionnaire.advance_total_score * questionnaire.advanced_weight).toFixed(1)"></span>
                    <span class="text-sm">)</span>
                </div>
                <div class="flex flex-row items-center justify-center space-x-2">
                    <span>總分:</span>
                    <span id="scoreCount" class="text-orange-600"
                        x-text="questionnaire.basic_total_score + questionnaire.advance_total_score"></span>
                    <span class="text-sm">(加權值：</span>
                    <span id="basicScoreCount" class="text-sm font-bold text-orange-600"
                        x-text="((questionnaire.basic_total_score * questionnaire.basic_weight)+(questionnaire.advance_total_score * questionnaire.advanced_weight)).toFixed(1)"></span>
                    <span class="text-sm">)</span>
                </div>
            </div>
            <template x-if="!disableAll">
                <div class="flex flex-row items-center justify-center w-full mt-6">
                    <input name="status" id="status" type="hidden" x-model="questionnaire.pivot.status">
                    <template x-if="(!questionnaire.pivot.status || questionnaire.pivot.status===0)">
                        <button @click="cacheClick" id="btnCache" type="button"
                            class="flex items-center justify-center w-20 h-10 mx-2 text-white bg-mainCyanDark hover:bg-teal-400">暫存</button>
                    </template>
                    <button type="button" @click="submitClick"
                        class="flex items-center justify-center w-20 h-10 mx-2 text-white bg-mainBlueDark hover:bg-mainBlue">繳交</button>
                </div>
            </template>
            <template x-if="auth_user.origin_role <= 2">
                <div class="flex flex-row items-center justify-center w-full my-6">
                    <button id="btnSubmitComments" type="button" @click="btnSubmitComments"
                        class="flex items-center justify-center h-10 px-4 text-white bg-mainBlueDark hover:bg-mainBlue">送出審查意見</button>
                </div>
            </template>
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