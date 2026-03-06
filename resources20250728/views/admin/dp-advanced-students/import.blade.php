@extends('admin.layouts.dashboard', [
'heading' => '進階防災士培訓 > 進階防災士資料管理',
'breadcrumbs' => [
['進階防災士培訓', route('admin.dp-advanced-students.index')],
'匯入受訓者與防災士資料'
]
])

@section('title', '匯入受訓者與防災士')

@section('inner_content')
{!! Form::open(['method' => 'POST', 'route' => 'admin.dp-advanced-students.import', 'files' => true]) !!}
<div x-data="{
    courses:{{ json_encode($courses) }},
    organizer:null,
    course_id:null,
    onChangeOrganizer(){
        this.course_id=Object.keys(this.courses[this.organizer])[0];
    },
}" class="flex flex-col items-start justify-start w-full max-w-4xl p-4 space-y-6" x-init="$nextTick(() => {
    if(Object.keys(courses).length>0){
        organizer=Object.keys(courses)[0];
        course_id=Object.keys(courses[organizer])[0];
    }
 })">
    <div class="relative w-full mb-6 bg-white border border-gray-200 rounded-sm">
        <div class="relative px-5 pt-3 pb-2 bg-gray-100 border-b-2 border-gray-200 text-mainAdminTextGrayDark">匯入</div>
        <div class="flex flex-col w-full p-5 m-0 space-y-4 bg-white">
            <div class="alert alert-info">
                <ul>
                    <li>
                        請使用 {!! link_to_route('admin.dp-advanced-students.download-import-sample', '進階防災士公版表格.xlsx',
                        null,
                        ['class'
                        => 'alert-link']) !!}
                        格式進行匯入，
                        {!! link_to_route('admin.dp-advanced-students.download-import-sample', '範例檔下載', ['example'],
                        ['class' =>
                        'alert-link']) !!}
                    </li>
                    <li>
                        必填欄位：於上述表格中註記，若必填欄位未填寫完整，則該列將不匯入
                    </li>
                    <li>預設密碼為生日</li>
                    <li>使用者應自行核對預計上傳資料筆數與上傳後系統顯示實際匯入資料筆數是否一致。</li>
                    <li>僅有第一個工作表會被匯入</li>
                </ul>
            </div>
            @if(session('errorMessages'))
            <div class="alert alert-danger">
                <strong>發生錯誤：</strong>
                <ul class="mb-0">
                    @foreach(session('errorMessages') as $errorMessage)
                    <li>{{ $errorMessage }}</li>
                    @endforeach
                </ul>
            </div>
            @endif
            <div class="flex flex-row items-center justify-start w-full space-x-4">
                {!! Form::label('organizer', '培訓單位') !!}
                <select @change="onChangeOrganizer"
                    class="flex-1 h-12 px-4 border-gray-300 rounded-md shadow-sm focus:border-cyan-300 focus:ring focus:ring-cyan-200 focus:ring-opacity-50"
                    id="organizer" name="organizer" x-model="organizer">
                    <template x-if="organizer!==null">
                        <template x-for="[key, value] of Object.entries(courses)">
                            <option :value="key" x-text="key" :selected="value==key"></option>
                        </template>
                    </template>
                </select>
            </div>
            <div class="flex flex-row items-center justify-start w-full space-x-4 text-center">
                {!! Form::label('name', '培訓計畫') !!}
                <select
                    class="flex-1 h-12 px-4 border-gray-300 rounded-md shadow-sm focus:border-cyan-300 focus:ring focus:ring-cyan-200 focus:ring-opacity-50"
                    id="course_id" name="course_id" wire:model="course_id">
                    <template x-if="course_id!==null">
                        <template x-for="(one_course,idx) in courses[organizer]">
                            <option :value="one_course.id" x-text="one_course.name"
                                :selected="one_course.id==course_id"></option>
                        </template>
                    </template>
                </select>
            </div>
            <div class="flex flex-col items-start justify-center">
                <template x-if="course_id!==null">
                    <table class="w-full my-8 border rounded">
                        <thead>
                            <tr class="text-white bg-mainGrayDark">
                                <th class="p-2 font-normal text-center border-r last:border-r-0">進階防災士課程名稱</th>
                                <th class="p-2 font-normal text-center border-r last:border-r-0">時數</th>
                                <th class="p-2 font-normal text-center border-r last:border-r-0">授課日期</th>
                            </tr>
                        </thead>
                        <tbody>
                            <template x-for="one_subject in courses[organizer][course_id].course_subjects">
                                <tr>
                                    <td class="p-2 text-sm text-left border-r last:border-r-0"
                                        x-text="one_subject.name"></td>
                                    <td class="p-2 border-r last:border-r-0"
                                        x-text="one_subject.hour==0?'':one_subject.hour"></td>
                                    <td class="p-2 border-r last:border-r-0" x-text="one_subject.start_date"></td>
                                </tr>
                            </template>
                        </tbody>
                    </table>
                </template>
            </div>
            <div class="flex flex-col items-start justify-center pb-6 space-y-6">
                {!! Form::label('file', '檔案上傳') !!}
                {!! Form::file('import_file', ['accept' => '.xls,.xlsx', 'required']) !!}
            </div>

            {!! Form::submit('送出', ['class' => 'w-full cursor-pointer hover:bg-teal-400 text-white flex justify-center
            items-center h-10 bg-mainCyanDark
            rounded']) !!}
        </div>
    </div>
    <div class="relative w-full bg-white border border-gray-200 rounded-sm">
        <div class="relative w-full bg-white border border-gray-200 rounded-sm">
            <div class="relative px-5 pt-3 pb-2 bg-gray-100 border-b-2 border-gray-200 text-mainAdminTextGrayDark">歷史紀錄
            </div>
            <table class="w-full bg-white border shadow-lg text-mainAdminTextGrayDark min-w-fit">
                <thead>
                    <tr class="border-b rounded-t bg-mainGray">
                        <th class="p-2 font-normal text-left border-r last:border-r-0">檔名</th>
                        <th class="p-2 font-normal text-left border-r last:border-r-0">修改時間</th>
                    </tr>
                </thead>
                <tbody class="text-content text-mainAdminTextGrayDark">
                    @forelse($files as $file)
                    <tr class="bg-white border-b last:border-b-0">
                        <td class="p-2 border-r last:border-r-0">
                            <a
                                href="{{ route('admin.dp-advanced-students.download-imported-file', $file['filename']) }}">{{
                                $file['filename'] }}</a>
                        </td>
                        <td class="p-2 border-r last:border-r-0">{{ $file['mtime'] }}</td>
                    </tr>
                    @empty
                    <tr class="bg-white border-b last:border-b-0">
                        <td colspan="2" class="p-2 text-center border-r last:border-r-0">暫無</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
{!! Form::close() !!}
<script src="{{ asset('scripts/genericPostForm.js') }}"></script>
@endsection