@extends('admin.layouts.dashboard', [
'heading' => "推動韌性社區 > " . Auth::user()->hasPermission('DC-certifications-review')?'標章申請表審查':'韌性社區標章申請表填寫及上傳',
'breadcrumbs' => [Auth::user()->hasPermission('DC-certifications-review')?'標章申請表審查':'韌性社區標章申請表填寫及上傳']
])

@section('title', Auth::user()->hasPermission('DC-certifications-review')?'標章申請表審查':'韌性社區標章申請表填寫及上傳')

@section('inner_content')
<div id="js-token" class="hidden">{{ csrf_token() }}</div>
<div class="flex flex-col items-start justify-start w-full p-4 text-mainAdminTextGrayDark" x-data="{
    reviewModal:{show:false,title:'',id:null,review_result:'',review_comment:''},
    loading:false,
    hasPermission:{{ Auth::user()->hasPermission('DC-certifications-review')?'true':'false' }},
    selectedTab:'1',
    county_id:'{{ request('county_id') }}',
    dc_unit_id:'{{ request('dc_unit_id') }}',
    dc_unit_name:'{{ request('dc_unit_name') }}',
    item:{{ $dc_certifications->toJson(JSON_PRETTY_PRINT) }},
    filterChange(){
        var url='{{ route('admin.dc-certifications.index') }}?county_id=' + encodeURIComponent(this.county_id) + '&dc_unit_id=' + encodeURIComponent(this.dc_unit_id) + '&dc_unit_name=' + encodeURIComponent(this.dc_unit_name);
        window.location = url;
    },
    reviewClick(e){
        var term = e.target.dataset.term;
        if(this.item.hasOwnProperty(term)){
            this.reviewModal.id=this.item[term].id;
            this.reviewModal.title=e.target.dataset.title;
            this.reviewModal.review_result=this.item[term].review_result;
            this.reviewModal.review_comment=this.item[term].review_comment;
            this.reviewModal.show=true;
            tinymce.activeEditor.setContent(this.item[term].review_comment);
        }
    },
    hiddenReviewModal(){
        this.reviewModal.id=null;
        this.reviewModal.title='';
        this.reviewModal.review_result=0;
        this.reviewModal.review_comment='';
        this.reviewModal.show=false;
    },
    reviewSubmit(e){
        var url=this.update_url.replace('99999999',this.reviewModal.id);
        var review_comment=tinymce.activeEditor.getContent();
        document.querySelector('#content-filter').value=review_comment;
        document.querySelector('#review_comment').value=review_comment;
        var This=this;
        var token='{{ csrf_token() }}';
        var formData = new FormData(e.target);
        formData.append('is_json', 1);
        this.loading=true;
        this.hiddenReviewModal();
        fetch(url,{
            method:'POST',
            body:formData,
            headers: {
                'Accept': 'application.json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
            },
        })
        .then((response) => {
            if(response.status===200){
                return response.json();
            }
        })
        .then(function (json) {
            location.reload();
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
    update_url:'{{ route('admin.dc-certifications-review.update',99999999) }}',
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
    tinymce.init({
        content_css: '/css/tinymce.css',
        selector: '.js-wysiwyg',
        language: 'zh_TW',
        branding: false,
        plugins: 'autolink image link media table hr advlist lists help anchor wordcount searchreplace visualblocks visualchars charmap emoticons code paste',
        toolbar1: 'formatselect fontselect | bold italic underline strikethrough forecolor backcolor | link image media | alignleft aligncenter alignright alignjustify | numlist bullist outdent indent | removeformat',
        image_advtab: true,
        height: 300,
        font_formats: '新細明體=PMingLiU; 標楷體=DFKai-sb; 微軟正黑體=Microsoft JhengHei; Andale Mono=andale mono,times; Arial=arial,helvetica,sans-serif; Arial Black=arial black,avant garde; Book Antiqua=book antiqua,palatino; Comic Sans MS=comic sans ms,sans-serif; Courier New=courier new,courier; Georgia=georgia,palatino; Helvetica=helvetica; Impact=impact,chicago; Symbol=symbol; Tahoma=tahoma,arial,helvetica,sans-serif; Terminal=terminal,monaco; Times New Roman=times new roman,times; Trebuchet MS=trebuchet ms,geneva; Verdana=verdana,geneva; Webdings=webdings; Wingdings=wingdings,zapf dingbats',
        paste_data_images: true,
        paste_as_text: true,
    });
 })">
    <div class="flex flex-col items-start justify-start w-full pace-y-4">
        @if ($dc_unit === null)
        {!! Form::open(['method' => 'POST', 'route' => 'admin.dc-certifications.store', 'id' => 'form', 'files' =>
        true,'class'=>'flex flex-col items-start justify-start w-full space-y-4'])
        !!}
        @else
        {!! Form::model($dc_unit, ['route' => ['admin.dc-certifications.store'], 'id' => 'form', 'method' => 'POST',
        'files' => true,'class'=>'flex flex-col items-start justify-start w-full space-y-4']) !!}
        @endif
        <div class="relative w-full mb-6 bg-white border border-gray-200 rounded-sm">
            <div class="relative px-5 pt-3 pb-2 bg-gray-100 border-b-2 border-gray-200 text-mainAdminTextGrayDark">
                韌性社區基本資料
            </div>
            <div id="divStudentData" class="flex flex-col w-full p-5 space-y-6 bg-white">
                <div class="flex items-center w-full space-x-2 form-row">
                    <div class="w-1/3 text-center">
                        {!! Form::label('county_id', '縣市') !!}
                    </div>
                    <div class="flex-1">
                        @if($lockCounty)
                        {!! Form::select('county_id', $counties, $lockCounty->id,
                        ['x-model'=>'county_id','@change'=>'filterChange','class' => 'h-12 px-4 border-gray-300
                        rounded-md shadow-sm focus:border-cyan-300 focus:ring focus:ring-cyan-200 focus:ring-opacity-50
                        w-full', 'disabled'])
                        !!}
                        @else
                        {!! Form::select('county_id', $counties, request('county_id'),
                        ['x-model'=>'county_id','@change'=>'filterChange','class' => 'h-12 px-4
                        border-gray-300 rounded-md shadow-sm focus:border-cyan-300 focus:ring focus:ring-cyan-200
                        focus:ring-opacity-50 w-full']) !!}
                        @endif
                    </div>
                </div>
                <div class="flex items-center w-full space-x-2 form-row">
                    <div class="w-1/3 text-center">
                        {!! Form::label('dc_unit_name', '搜尋社區') !!}
                    </div>
                    <div class="relative flex-1">
                        {!! Form::text('dc_unit_name', '搜尋社區',
                        ['x-model'=>'dc_unit_name','@change'=>'filterChange','class' => 'h-12 px-4
                        border-gray-300 rounded-md shadow-sm focus:border-cyan-300 focus:ring focus:ring-cyan-200 pr-12
                        focus:ring-opacity-50 w-full']) !!}
                        <button type="button" @click="filterChange"
                            class="absolute top-0 right-0 flex items-center justify-center w-12 h-12 border rounded">
                            <i class="w-6 h-6 text-gray-400 i-gg-search"></i>
                        </button>
                    </div>
                </div>
                <div class="flex items-center w-full space-x-2 form-row">
                    <div class="w-1/3 text-center">
                        {!! Form::label('dc_unit_id', '社區名稱') !!}
                    </div>
                    <div class="flex-1">
                        {!! Form::select('dc_unit_id', $dc_units, request('dc_unit_id'),
                        ['x-model'=>'dc_unit_id','@change'=>'filterChange','class' => 'h-12 px-4
                        border-gray-300 rounded-md shadow-sm focus:border-cyan-300 focus:ring focus:ring-cyan-200
                        focus:ring-opacity-50 w-full']) !!}
                    </div>
                </div>

                <div class="flex items-center w-full space-x-2 form-row">
                    <div class="w-1/3 text-center">
                        {!! Form::label('user_id', '申請人') !!}
                    </div>
                    <div class="flex-1">
                        {!! Form::text('user_name', Auth::user()->name, ['class' => 'h-12 px-4 border-gray-300
                        rounded-md
                        shadow-sm focus:border-cyan-300 focus:ring focus:ring-cyan-200 focus:ring-opacity-50 w-full
                        bg-gray-100 cursor-not-allowed',
                        'disabled']) !!}
                    </div>
                </div>

                <div class="flex items-center w-full space-x-2 form-row">
                    <div class="w-1/3 text-center">
                        {!! Form::label('user_id', '申請時間') !!}
                    </div>
                    <div class="flex-1">
                        {!! Form::text('created_at', '資料提交之系統時間', ['class' => 'h-12 px-4 border-gray-300 rounded-md
                        shadow-sm focus:border-cyan-300 focus:ring focus:ring-cyan-200 focus:ring-opacity-50 w-full
                        bg-gray-100 cursor-not-allowed',
                        'disabled']) !!}
                    </div>
                </div>
            </div>
        </div>

        <div class="relative w-full mb-6 bg-white border border-gray-200 rounded-sm">
            <div class="relative px-5 pt-3 pb-2 bg-gray-100 border-b-2 border-gray-200 text-mainAdminTextGrayDark">工作項目
            </div>
            <div class="p-5 m-0 bg-white">
                <div class="flex flex-row flex-wrap">
                    <div class="w-full">
                        <ul class="list-none ">
                            <li class="p-1">資料上傳須知：
                                <ul class="ml-4 list-disc list-inside">
                                    <li class="p-1">請確定檔案格式為pdf, doc, docx, jpg, jpeg, png, gif, zip, rar, txt, csv,
                                        xlsx, odf, mp4, mov</li>
                                    <li class="p-1">請點選送出按鈕，將上傳檔案提交。</li>
                                </ul>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="p-5 m-0 bg-white">
                <div class="flex flex-row items-center justify-start space-x-2">
                    <button type="button" @click="selectedTab='1'" class="flex items-center justify-center h-10 px-4"
                        :class="{'bg-mainCyanDark border-0 text-white':selectedTab==='1','bg-gray-100 border text-mainAdminTextGrayDark border-b-0':selectedTab!=='1'}">
                        <span>一星社區申請上傳</span>
                    </button>
                    <button type="button" @click="selectedTab='2'" class="flex items-center justify-center h-10 px-4"
                        :class="{'bg-mainCyanDark border-0 text-white':selectedTab==='2','bg-gray-100 border text-mainAdminTextGrayDark border-b-0':selectedTab!=='2'}">
                        <span>二星社區申請上傳</span>
                    </button>
                    <button type="button" @click="selectedTab='3'" class="flex items-center justify-center h-10 px-4"
                        :class="{'bg-mainCyanDark border-0 text-white':selectedTab==='3','bg-gray-100 border text-mainAdminTextGrayDark border-b-0':selectedTab!=='3'}">
                        <span>三星社區申請上傳</span>
                    </button>
                </div>

                <div class="panel-foot">
                    <div class="flex flex-row flex-wrap">
                        <div class="w-full star-panel" x-show="selectedTab==='1'">
                            <table class="w-full bg-white border shadow-lg text-mainAdminTextGrayDark">
                                <thead>
                                    <tr class="border-b bg-mainLight">
                                        <th class="p-2 font-normal text-left border-r w-14 last:border-r-0">繳交</th>
                                        <th class="w-48 p-2 font-normal text-left border-r last:border-r-0">一星社區工作項目
                                        </th>
                                        <th class="w-48 p-2 font-normal text-left border-r last:border-r-0">檔案上傳</th>
                                        <th class="w-24 p-2 font-normal text-left border-r last:border-r-0">審查結果</th>
                                        <th class="w-24 p-2 font-normal text-left border-r last:border-r-0">審查意見</th>
                                        <th class="w-24 p-2 font-normal text-left border-r last:border-r-0">審查時間</th>
                                    </tr>
                                </thead>
                                <tbody class="text-content text-mainAdminTextGrayDark">
                                    @foreach(config('dc.certification.items') as $idx => $item )
                                    @if($idx <= 14) <tr class="border-b last:border-b-0 odd:bg-white even:bg-mainLight">
                                        <td class="p-2 text-center border-r last:border-r-0">
                                            {{ $dc_certifications->has($idx)?'O':'X' }}
                                        </td>
                                        <td class="p-2 border-r last:border-r-0">
                                            {{ $item }}
                                        </td>
                                        <td class="p-2 border-r last:border-r-0">
                                            @if(array_key_exists($idx,$file_list))
                                            <div class="flex flex-row flex-wrap text-center">
                                                {!! Form::label('files[]', '附件') !!}
                                                {!! Form::hidden("removed_files_${idx}", '[]', ['id' =>
                                                'js-removed-files'])
                                                !!}
                                                <div
                                                    class="flex flex-col items-start justify-start w-full mt-2 mb-6 space-y-2">
                                                    @foreach($file_list[$idx] as $file)
                                                    <div data-id="{{ $file['id'] }}"
                                                        class="flex flex-row flex-wrap items-center justify-start w-full well">
                                                        @if (preg_match("/\.pdf$/",$file['name']))
                                                        <a href="/{{ preg_replace(" /^uploads/","stream",$file['path'])
                                                            }}" target="_blank"
                                                            class="flex-1 text-left text-mainBlueDark">
                                                            <span>{{ $file['name'] }}</span>
                                                            @if(Auth::user()->hasPermission('DC-certifications-review'))
                                                            <span class="text-sm text-mainAdminGrayDark">（上傳時間：{{
                                                                str_replace(['T','.000000Z'],
                                                                [' ',''],
                                                                $file['created_at']) }}）</span>
                                                            @endif
                                                        </a>
                                                        @else
                                                        <a href="/{{ $file['path'] }}" target="_blank"
                                                            class="flex-1 text-left text-mainBlueDark">
                                                            <span>{{ $file['name'] }}</span>
                                                            @if(Auth::user()->hasPermission('DC-certifications-review'))
                                                            <span class="text-sm text-mainAdminGrayDark">（上傳時間：{{
                                                                str_replace(['T','.000000Z'],
                                                                [' ',''],
                                                                $file['created_at']) }}）</span>
                                                            @endif
                                                        </a>
                                                        @endif
                                                        @if (!Auth::user()->hasPermission('DC-certifications-review'))
                                                        <span
                                                            class="px-4 text-sm text-white rounded cursor-pointer py-1.5 js-remove-file bg-rose-600 hover:bg-rose-500">刪除</span>
                                                        @endif
                                                    </div>
                                                    @endforeach
                                                </div>
                                                {!! Form::file("files_${idx}[]", ['multiple' => true,'accept'=>'.pdf,
                                                .doc,
                                                .docx, .jpg, .jpeg, .png, .gif, .zip, .rar, .txt, .csv, .xlsx, .odf,
                                                .mp4, .mov'])
                                                !!}
                                            </div>
                                            @else
                                            {!! Form::file("files_${idx}[]", ['multiple' => true,'accept'=>'.pdf, .doc,
                                            .docx,
                                            .jpg, .jpeg, .png, .gif, .zip, .rar, .txt, .csv, .xlsx, .odf, .mp4, .mov'])
                                            !!}
                                            @endif
                                        </td>
                                        <td class="p-2 text-center border-r last:border-r-0">
                                            @if(Auth::user()->hasPermission('DC-certifications-review'))
                                            <button type="button" data-title="{{ $item }}" data-term="{{ $idx }}"
                                                @click="reviewClick"
                                                class="flex items-center justify-center w-20 h-10 text-sm text-white bg-mainCyanDark">
                                                {{
                                                $dc_certifications->has($idx)?$dc_certifications->get($idx)->review_result_text:'未審查'
                                                }}</button>
                                            @else
                                            {{ $dc_certifications->get($idx)->review_result_text ?? '' }}
                                            @endif
                                        </td>
                                        <td class="p-2 border-r last:border-r-0">{!!
                                            $dc_certifications->get($idx)->review_comment ?? '' !!}
                                        </td>
                                        <td class="p-2 border-r last:border-r-0">
                                            {{ $dc_certifications->get($idx)->review_at ?? '' }}
                                        </td>
                                        </tr>
                                        @endif

                                        @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="w-full star-panel" x-show="selectedTab==='2'">
                            <table class="w-full bg-white border shadow-lg text-mainAdminTextGrayDark">
                                <thead>
                                    <tr class="border-b bg-mainLight">
                                        <th class="p-2 font-normal text-left border-r w-14 last:border-r-0">繳交</th>
                                        <th class="p-2 font-normal text-left border-r last:border-r-0">二星社區工作項目</th>
                                        <th class="w-40 p-2 font-normal text-left border-r last:border-r-0">檔案上傳</th>
                                        <th class="w-24 p-2 font-normal text-left border-r last:border-r-0">審查結果</th>
                                        <th class="w-24 p-2 font-normal text-left border-r last:border-r-0">審查意見</th>
                                        <th class="w-24 p-2 font-normal text-left border-r last:border-r-0">審查時間</th>
                                    </tr>
                                </thead>
                                <tbody class="text-content text-mainAdminTextGrayDark">
                                    @foreach(config('dc.certification.items') as $idx => $item)
                                    @if($idx > 14 && $idx <= 30) <tr
                                        class="border-b last:border-b-0 odd:bg-white even:bg-mainLight">
                                        <td class="p-2 text-center border-r last:border-r-0">
                                            {{ $dc_certifications->has($idx)?'O':'X' }}
                                        </td>
                                        <td class="p-2 border-r last:border-r-0">
                                            {{ $item }}
                                        </td>
                                        <td class="p-2 border-r last:border-r-0">
                                            @if(array_key_exists($idx,$file_list))
                                            <div class="flex flex-row flex-wrap text-center">
                                                {!! Form::label('files[]', '附件') !!}
                                                {!! Form::hidden("removed_files_${idx}", '[]', ['id' =>
                                                'js-removed-files'])
                                                !!}
                                                <div
                                                    class="flex flex-col items-start justify-start w-full mt-2 mb-6 space-y-2">
                                                    @foreach($file_list[$idx] as $file)
                                                    <div data-id="{{ $file['id'] }}"
                                                        class="flex flex-row flex-wrap items-center justify-start w-full well">
                                                        @if (preg_match("/\.pdf$/",$file['name']))
                                                        <a href="/{{ preg_replace(" /^uploads/","stream",$file['path'])
                                                            }}" target="_blank"
                                                            class="flex-1 text-left text-mainBlueDark">
                                                            <span>{{ $file['name'] }}</span>
                                                            @if(Auth::user()->hasPermission('DC-certifications-review'))
                                                            <span class="text-sm text-mainAdminGrayDark">（上傳時間：{{
                                                                str_replace(['T','.000000Z'],
                                                                [' ',''],
                                                                $file['created_at']) }}）</span>
                                                            @endif
                                                        </a>
                                                        @else
                                                        <a href="/{{ $file['path'] }}" target="_blank"
                                                            class="flex-1 text-left text-mainBlueDark">
                                                            <span>{{ $file['name'] }}</span>
                                                            @if(Auth::user()->hasPermission('DC-certifications-review'))
                                                            <span class="text-sm text-mainAdminGrayDark">（上傳時間：{{
                                                                str_replace(['T','.000000Z'],
                                                                [' ',''],
                                                                $file['created_at']) }}）</span>
                                                            @endif
                                                        </a>
                                                        @endif
                                                        @if (!Auth::user()->hasPermission('DC-certifications-review'))
                                                        <span
                                                            class="px-4 text-sm text-white rounded cursor-pointer py-1.5 js-remove-file bg-rose-600 hover:bg-rose-500">刪除</span>
                                                        @endif
                                                    </div>
                                                    @endforeach
                                                </div>
                                                {!! Form::file("files_${idx}[]", ['multiple' => true,'accept'=>'.pdf,
                                                .doc,
                                                .docx, .jpg, .jpeg, .png, .gif, .zip, .rar, .txt, .csv, .xlsx, .odf,
                                                .mp4, .mov'])
                                                !!}
                                            </div>
                                            @else
                                            {!! Form::file("files_${idx}[]", ['multiple' => true,'accept'=>'.pdf, .doc,
                                            .docx,
                                            .jpg, .jpeg, .png, .gif, .zip, .rar, .txt, .csv, .xlsx, .odf, .mp4, .mov'])
                                            !!}
                                            @endif
                                        </td>
                                        <td class="p-2 text-center border-r last:border-r-0">
                                            @if(Auth::user()->hasPermission('DC-certifications-review'))
                                            <button type="button" data-title="{{ $item }}" data-term="{{ $idx }}"
                                                @click="reviewClick"
                                                class="flex items-center justify-center w-20 h-10 text-sm text-white bg-mainCyanDark">
                                                {{
                                                $dc_certifications->has($idx)?$dc_certifications->get($idx)->review_result_text:'未審查'
                                                }}</button>
                                            @else
                                            {{ $dc_certifications->get($idx)->review_result_text ?? '' }}
                                            @endif
                                        </td>
                                        <td class="p-2 border-r last:border-r-0">
                                            {!! $dc_certifications->get($idx)->review_comment ?? '' !!}
                                        </td>
                                        <td class="p-2 border-r last:border-r-0">
                                            {{ $dc_certifications->get($idx)->review_at ?? '' }}
                                        </td>
                                        </tr>
                                        @endif
                                        @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="w-full star-panel" x-show="selectedTab==='3'">
                            <table class="w-full bg-white border shadow-lg text-mainAdminTextGrayDark">
                                <thead>
                                    <tr class="border-b bg-mainLight">
                                        <th class="p-2 font-normal text-left border-r w-14 last:border-r-0">繳交</th>
                                        <th class="p-2 font-normal text-left border-r last:border-r-0">三星社區工作項目</th>
                                        <th class="w-40 p-2 font-normal text-left border-r last:border-r-0">檔案上傳</th>
                                        <th class="w-24 p-2 font-normal text-left border-r last:border-r-0">審查結果</th>
                                        <th class="w-24 p-2 font-normal text-left border-r last:border-r-0">審查意見</th>
                                        <th class="w-24 p-2 font-normal text-left border-r last:border-r-0">審查時間</th>
                                    </tr>
                                </thead>
                                <tbody class="text-content text-mainAdminTextGrayDark">
                                    @foreach(config('dc.certification.items') as $idx => $item)
                                    @if($idx >26)
                                    <tr class="border-b last:border-b-0 odd:bg-white even:bg-mainLight">
                                        <td class="p-2 text-center border-r last:border-r-0">
                                            {{ $dc_certifications->has($idx)?'O':'X' }}
                                        </td>
                                        <td class="p-2 border-r last:border-r-0">
                                            {{ $item }}
                                        </td>
                                        <td class="p-2 border-r last:border-r-0">
                                            @if(array_key_exists($idx,$file_list))
                                            <div class="flex flex-row flex-wrap text-center">
                                                {!! Form::label('files[]', '附件') !!}
                                                {!! Form::hidden("removed_files_${idx}", '[]', ['id' =>
                                                'js-removed-files'])
                                                !!}
                                                <div
                                                    class="flex flex-col items-start justify-start w-full mt-2 mb-6 space-y-2">
                                                    @foreach($file_list[$idx] as $file)
                                                    <div data-id="{{ $file['id'] }}"
                                                        class="flex flex-row flex-wrap items-center justify-start w-full well">
                                                        @if (preg_match("/\.pdf$/",$file['name']))
                                                        <a href="/{{ preg_replace(" /^uploads/","stream",$file['path'])
                                                            }}" target="_blank"
                                                            class="flex-1 text-left text-mainBlueDark">
                                                            <span>{{ $file['name'] }}</span>
                                                            @if(Auth::user()->hasPermission('DC-certifications-review'))
                                                            <span class="text-sm text-mainAdminGrayDark">（上傳時間：{{
                                                                str_replace(['T','.000000Z'],
                                                                [' ',''],
                                                                $file['created_at'])
                                                                }}
                                                                ）</span>
                                                            @endif
                                                        </a>
                                                        @else
                                                        <a href="/{{ $file['path'] }}" target="_blank"
                                                            class="flex-1 text-left text-mainBlueDark">
                                                            <span>{{ $file['name'] }}</span>
                                                            @if(Auth::user()->hasPermission('DC-certifications-review'))
                                                            <span class="text-sm text-mainAdminGrayDark">（上傳時間：{{
                                                                str_replace(['T','.000000Z'],
                                                                [' ',''],
                                                                $file['created_at']) }}）</span>
                                                            @endif
                                                        </a>
                                                        @endif
                                                        @if (!Auth::user()->hasPermission('DC-certifications-review'))
                                                        <span
                                                            class="px-4 text-sm text-white rounded cursor-pointer py-1.5 js-remove-file bg-rose-600 hover:bg-rose-500">刪除</span>
                                                        @endif
                                                    </div>
                                                    @endforeach
                                                </div>
                                                {!! Form::file("files_${idx}[]", ['multiple' => true,'accept'=>'.pdf,
                                                .doc,
                                                .docx, .jpg, .jpeg, .png, .gif, .zip, .rar, .txt, .csv, .xlsx, .odf,
                                                .mp4, .mov'])
                                                !!}
                                            </div>
                                            @else
                                            {!! Form::file("files_${idx}[]", ['multiple' => true,'accept'=>'.pdf, .doc,
                                            .docx,
                                            .jpg, .jpeg, .png, .gif, .zip, .rar, .txt, .csv, .xlsx, .odf, .mp4, .mov'])
                                            !!}
                                            @endif
                                        </td>
                                        <td class="p-2 text-center border-r last:border-r-0">
                                            @if(Auth::user()->hasPermission('DC-certifications-review'))
                                            <button type="button" data-title="{{ $item }}" data-term="{{ $idx }}"
                                                @click="reviewClick"
                                                class="flex items-center justify-center w-20 h-10 text-sm text-white bg-mainCyanDark">
                                                {{
                                                $dc_certifications->has($idx)?$dc_certifications->get($idx)->review_result_text:'未審查'
                                                }}</button>
                                            @else
                                            {{ $dc_certifications->get($idx)->review_result_text ?? '' }}
                                            @endif
                                        </td>
                                        <td class="p-2 border-r last:border-r-0">
                                            {!! $dc_certifications->get($idx)->review_comment ?? '' !!}
                                        </td>
                                        <td class="p-2 border-r last:border-r-0">
                                            {{ $dc_certifications->get($idx)->review_at ?? '' }}
                                        </td>
                                    </tr>
                                    @endif
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                    </div>
                </div>
            </div>
            {!! Form::submit('送出', ['class' => 'w-full cursor-pointer hover:bg-teal-400 text-white flex justify-center
            items-center h-10 bg-mainCyanDark
            rounded']) !!}
            <div class="flex flex-row items-center justify-center pt-4 pb-16 space-x-12 w-fill">
                @if ($has_1_star)
                <a href="/admin/dc-certifications/export?dc_unit_id={{ request('dc_unit_id') }}&star=1"
                    class="text-lg text-mainBlueDark">一星社區打包下載</a>
                @endif
                @if ($has_2_star)
                <a href="/admin/dc-certifications/export?dc_unit_id={{ request('dc_unit_id') }}&star=2"
                    class="text-lg text-mainBlueDark">二星社區打包下載</a>
                @endif
                @if ($has_3_star)
                <a href="/admin/dc-certifications/export?dc_unit_id={{ request('dc_unit_id') }}&star=3"
                    class="text-lg text-mainBlueDark">三星社區打包下載</a>
                @endif
            </div>
        </div>
        {!! Form::close() !!}
    </div>
    <div x-show="loading" class="absolute z-[1100] inset-0 bg-black/30 hidden justify-center items-center"
        :class="{'flex':loading,'hidden':!loading}">
        <div class="relative w-full h-full">
            <img src="/image/loading.svg" class="w-16 h-16 absolute left-1/2 top-[calc(50vh-2rem)]" alt="">
        </div>
    </div>
    <div x-show="reviewModal.show" x-transition
        class="overflow-auto overflow-y-scroll fixed hidden inset-0 z-[1050] outline-0 bg-black/30 justify-center items-center"
        :class="{'flex':reviewModal.show,'hidden':!reviewModal.show}">
        <div class="flex flex-col items-start justify-start w-full max-w-3xl text-mainGrayDark">
            <div class="relative flex flex-col items-start justify-start w-full p-5 bg-white rounded shadow-lg">
                <h5 class="w-full text-center">標章申請表審查</h5>
                <form @submit.prevent="reviewSubmit"
                    class="flex flex-col items-start justify-start w-full py-6 rounded-bl rounded-br modal-footer">
                    <input type="hidden" name="_method" value="PUT">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <span class="mb-1 font-bold">工作項目</span>
                    <span class="mb-4" x-text="reviewModal.title"></span>
                    <span class="mb-1 font-bold">審查結果</span>
                    <div class="flex flex-row items-center justify-start w-full mb-4 space-x-4">
                        <label class="flex flex-row items-center space-x-2">
                            <input type="radio" name="review_result" value="1" x-model="reviewModal.review_result"
                                class="bg-white border-gray-300 rounded-full text-mainCyanDark">
                            <span>通過</span>
                        </label>
                        <label class="flex flex-row items-center space-x-2">
                            <input type="radio" name="review_result" value="0" x-model="reviewModal.review_result"
                                class="bg-white border-gray-300 rounded-full text-mainCyanDark">
                            <span>不通過</span>
                        </label>
                    </div>
                    <span class="mb-1 font-bold">審查意見</span>
                    <input name="content-filter" id="content-filter" type="hidden">
                    <textarea name="review_comment" id="review_comment"
                        class="w-full p-4 mb-8 border-gray-300 rounded-md shadow-sm js-wysiwyg focus:border-cyan-300 focus:ring focus:ring-cyan-200 focus:ring-opacity-50"
                        rows="10"></textarea>
                    <div class="flex flex-row items-center w-full mt-6 justify-evenly">
                        <button type="button" @click="hiddenReviewModal"
                            class="flex items-center justify-center w-20 h-10 text-sm border border-gray-300 rounded bg-mainLight text-mainTextGrayDark">取消</button>
                        <button type="submit"
                            class="flex items-center justify-center w-20 h-10 text-sm text-white border rounded bg-mainBlueDark hover:bg-teal-400">確定</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
@endsection