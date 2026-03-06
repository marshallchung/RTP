@extends('admin.layouts.dashboard', [
'heading' => '檢視績效評估自評表',
'breadcrumbs' => [
['績效評估自評表列表', route('admin.questionnaire.index')],
'管理'
]
])

@section('title', '檢視績效評估自評表')

@section('inner_content')

<div x-data="{
    loading:false,
    selectedTab:0,
    selectedYear:'',
    selectedQuestionnaire:'{{ $questionnaire_id }}',
    questionnaires:{{ $questionnaires->toJson(JSON_PRETTY_PRINT) }},
    yearChange(e){
        document.querySelector('#questionnaire_id').dispatchEvent(new Event('change', { 'bubbles' : true }));
    },
    questionnaireChange(e){
        window.location = '{{ route('admin.questionnaire.panel') }}/' + this.selectedQuestionnaire;
    },
    statusClick(e){
        var This = this;
        var url='{{ route('admin.questionnaire.updateStatus') }}?questionnaire_user_id=' + encodeURIComponent(e.target.dataset.rel) + '&user_id=' + encodeURIComponent(e.target.dataset.id) + '&status=' + encodeURIComponent(e.target.dataset.status);
        fetch(url,{
            method:'get',
            headers: {
                'X-Requested-With':'XMLHttpRequest',
                'Accept': '*/*',
            },
        })
        .then((response)=>{
            if(response.status===200){
                alert('更新成功');
                if (e.target.dataset.status == '2') {
                    e.target.setAttribute('data-status','1');
                    e.target.innerText='特別開放中';
                    e.target.classList.remove('bg-gray-100');
                    e.target.classList.remove('hover:bg-gray-50');
                    e.target.classList.remove('text-mainAdminTextGrayDark');
                    e.target.classList.add('hover:bg-orange-500');
                    e.target.classList.add('text-white');
                    e.target.classList.add('bg-orange-600');
                    /*obj.removeClass('btn-default').addClass('btn-warning').text('特別開放中').attr('changingStatus', 1);*/
                } else {
                    e.target.setAttribute('data-status','2');
                    e.target.innerText='已繳交';
                    e.target.classList.remove('bg-orange-600');
                    e.target.classList.remove('text-white');
                    e.target.classList.remove('hover:bg-orange-500');
                    e.target.classList.add('text-mainAdminTextGrayDark');
                    e.target.classList.add('hover:bg-gray-50');
                    e.target.classList.add('bg-gray-100');
                    /*obj.removeClass('btn-warning').addClass('btn-default').text('已繳交').attr('changingStatus', 2);*/
                }
            }
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
}" class="flex flex-col w-full max-w-4xl p-4 space-y-4 text-mainAdminTextGrayDark">
    <div x-show="loading" class="absolute z-[1100] inset-0 bg-black/30 hidden justify-center items-center"
        :class="{'flex':loading,'hidden':!loading}">
        <div class="relative w-full h-full">
            <img src="/image/loading.svg" class="w-16 h-16 absolute left-1/2 top-[calc(50vh-2rem)]" alt="">
        </div>
    </div>
    <div class="flex flex-row items-center justify-end w-full space-x-4">
        <span>年份</span>
        <select @change="yearChange" x-model="selectedYear" id="year"
            class="h-12 text-sm border-gray-300 rounded-md focus:border-sky-300 focus:ring focus:ring-sky-200 focus:ring-opacity-50 text-mainAdminTextGrayDark"
            name="year">
            <option value="">全部</option>
            @foreach ($availableYears as $availableYear)
            <option value="{{ $availableYear }}" {{
                (isset($year)?intval($year):'')===intval($availableYear)?'selected':'' }}>{{
                intval($availableYear)-1911 }} 年
            </option>
            @endforeach
        </select>
        <span>問卷</span>
        <select id="questionnaire_id" x-model="selectedQuestionnaire" @change="questionnaireChange"
            class="h-12 text-sm border-gray-300 rounded-md focus:border-sky-300 focus:ring focus:ring-sky-200 focus:ring-opacity-50 text-mainAdminTextGrayDark"
            style="display: inline-block; width: auto; margin-left: 8px;">
            <option value="" class="inline" :class="{'hidden':selectedYear!='','inline':selectedYear==''}">
                顯示全部</option>
            <template x-for="[key, value] of Object.entries(questionnaires)">
                <template x-if="(selectedYear==value.year || selectedYear=='')">
                    <option :value="key" x-bind:selected="key=={{ $questionnaire_id?$questionnaire_id:'null' }}"
                        x-text="value.title">
                    </option>
                </template>
            </template>
        </select>
        <input type="hidden" value="{{ route('admin.questionnaire.index') }}" id="js-current-route">
    </div>
    <div class="w-full p-5 bg-white border">
        <div class="w-full">
            <div class="w-full">
                <div id="js-select-county" class="flex flex-col w-full">
                    <ul class="flex flex-row items-center justify-start w-full space-x-2 list-none list-inside">
                        @foreach ($accounts as $index => $area)
                        <li
                            :class="{'bg-mainCyanDark border-0 text-white':selectedTab==={{ $index }},'bg-gray-100 border text-mainAdminTextGrayDark border-b-0':selectedTab!=={{ $index }}}">
                            <button type="button" @click="selectedTab={{ $index }}"
                                class="flex items-center justify-center w-20 h-10">{{
                                $area['name'] }}</button>
                        </li>
                        @endforeach
                    </ul>
                    <div class="w-full p-4 border">
                        @foreach ($accounts as $index => $area)
                        <div x-show="selectedTab==={{ $index }}" id="area-tab-{{ $index }}"
                            class="flex flex-col space-y-2 bg-white">
                            @foreach ($area['accounts'] as $account)
                            @if (Auth::user()->origin_role > 2 && Auth::user()->county_id != $account->id &&
                            Auth::user()->id != $account->id)
                            <?php continue; ?> @endif
                            <div x-data="{panelOpen:false}" class="flex flex-col border rounded">
                                <div class="relative px-5 pt-3 pb-2 text-mainAdminTextGrayDark bg-mainLight">
                                    <button type="button" @click="panelOpen=!panelOpen"
                                        class="flex flex-row items-center justify-between w-full">
                                        <span>{{ $account->name }}</span>
                                        <div class="flex items-center justify-center w-5 h-5 rounded bg-mainGrayDark">
                                            <i class="w-3 h-3 text-white"
                                                :class="{'i-heroicons-minus':panelOpen,'i-heroicons-plus':!panelOpen}"></i>
                                        </div>
                                    </button>
                                </div>
                                <div x-show="panelOpen" x-transition id="county-{{ $account->id }}"
                                    class="flex-col hidden" :class="{'flex':panelOpen,'hidden':!panelOpen}">
                                    <a class="relative block px-5 py-3 text-left border-t">{{ $account->name }}政府</a>
                                    @if (Auth::user()->origin_role < 5) {!!
                                        App\Http\Controllers\Admin\QuestionnaireController::parseBtn($account,
                                        Auth::user()->
                                        origin_role <= 2, $filteredQuestionnaire) !!} @endif @foreach ($account->
                                            districts[1] as $district)
                                            <?php
                                                            if (Auth::user()->origin_role > 4 && Auth::user()->id != $district->id)
                                                                continue;
                                                            ?>
                                            <a class="list-group-item">
                                                &nbsp;&nbsp;&ndash;&nbsp;&nbsp;{{ $district->name }}</a>
                                            {!!
                                            App\Http\Controllers\Admin\QuestionnaireController::parseBtn($district,
                                            Auth::user()->origin_role <= 2, $filteredQuestionnaire) !!} @endforeach
                                                @foreach ($account->
                                                districts[2] as $district)
                                                <?php
                                                            if (Auth::user()->origin_role > 4 && Auth::user()->id != $district->id)
                                                                continue;
                                                            ?>
                                                <a class="list-group-item">
                                                    &nbsp;&nbsp;&ndash;&nbsp;&nbsp;{{ $district->name }}</a>
                                                {!!
                                                App\Http\Controllers\Admin\QuestionnaireController::parseBtn($district,
                                                Auth::user()->origin_role <= 2, $filteredQuestionnaire) !!} @endforeach
                                                    </div>
                                </div>
                                @endforeach
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('scripts')

@endsection