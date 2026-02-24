@extends('admin.layouts.dashboard', [
'heading' => '防災士培訓 > 防災士資料管理',
'breadcrumbs' => [
['防災士培訓', route('admin.dp-students.index')],
'編輯受訓者與防災士資料'
]
])

@section('title', '防災士資料管理')

@section('inner_content')
<div class="flex flex-col items-start justify-start w-full p-4 space-y-4">
    <div class="md:w-67/100 xl:w-75/100" x-data="" x-init="$nextTick(() => {
     })">
        <div class="relative mb-6 bg-white border border-gray-200 rounded-sm">
            <div class="relative px-5 pt-3 pb-2 bg-gray-100 border-b-2 border-gray-200 text-mainAdminTextGrayDark">檢視
            </div>
            <div class="flex flex-col p-5 space-y-8 bg-white">
                <div class="flex flex-col space-y-2">
                    <label class="font-bold">身份證字號</label>
                    <p>{{ $data->TID }}</p>
                </div>

                <div class="flex flex-col space-y-2">
                    <label class="font-bold">姓名</label>
                    <p>{{ $data->name }}</p>
                </div>

                <div class="flex flex-col space-y-2">
                    <label class="font-bold">證書編號</label>
                    <p>{{ $data->certificate }}</p>
                </div>

                <div class="flex flex-col space-y-2">
                    <label class="font-bold">出生年（西元）</label>
                    <p>{{ $data->birth }}</p>
                </div>

                <div class="flex flex-col space-y-2">
                    <label class="font-bold">性別</label>
                    <p>{{ $data->gender }}</p>
                </div>

                <div class="flex flex-col space-y-2">
                    <label class="font-bold">工作領域</label>
                    <p>{{ $data->field }}</p>
                </div>

                <div class="flex flex-col space-y-2">
                    <label class="font-bold">最高學歷</label>
                    <p>{{ $data->education }}</p>
                </div>

                <div class="flex flex-col space-y-2">
                    <label class="font-bold">服務單位</label>
                    <p>{{ $data->service }}</p>
                </div>

                <div class="flex flex-col space-y-2">
                    <label class="font-bold">職稱</label>
                    <p>{{ $data->title }}</p>
                </div>

                <div class="flex flex-col space-y-2">
                    <label class="font-bold">參與防災意願</label>
                    <p>{{ $data->willingness?'有':'無' }}</p>
                </div>

                <div class="flex flex-col space-y-2">
                    <label class="font-bold">市內電話</label>
                    <p>{{ $data->phone }}</p>
                </div>

                <div class="flex flex-col space-y-2">
                    <label class="font-bold">行動電話</label>
                    <p>{{ $data->mobile }}</p>
                </div>

                <div class="flex flex-col space-y-2">
                    <label class="font-bold">E-mail</label>
                    <p>{{ $data->email }}</p>
                </div>

                <div class="flex flex-col space-y-2">
                    <label class="font-bold">居住地址</label>
                    <p>{{ $data->address }}</p>
                </div>

                <div class="flex flex-col space-y-2">
                    <label class="font-bold">所屬村里或社區</label>
                    <p>{{ $data->community }}</p>
                </div>

                <div class="flex flex-col space-y-2">
                    <label class="font-bold">所屬縣市</label>
                    <p>{{ $counties[$data->county_id] }}</p>
                </div>

                <div class="flex flex-col space-y-2">
                    <label class="font-bold">受訓單位</label>
                    <p>{{ $data->unit_first_course }}</p>
                </div>

                <div class="flex flex-col space-y-2">
                    <label class="font-bold">證書發放日期</label>
                    <p>{{ substr($data->date_first_finish,0,10) }}</p>
                </div>

                <div class="flex flex-col space-y-2">
                    <label class="font-bold">證書有效期限</label>
                    <p>{{ $data->unit_second_course }}</p>
                </div>

                <div class="flex flex-col space-y-2">
                    <label class="font-bold">證書</label>
                    <div class="mb-6 xl:w-full">
                        @foreach($data->files as $file)
                        <div class="flex flex-row items-center justify-start w-full p-2 bg-mainLight"
                            data-id="{{ $file->id }}">
                            <a href="/{{ $file->path }}" target="_blank" class="flex-1 text-left text-mainBlueDark">{{
                                $file->name }}</a>
                            <span
                                class="px-4 text-sm text-white rounded cursor-pointer py-1.5 js-remove-file bg-rose-600 hover:bg-rose-500">刪除</span>
                        </div>
                        @endforeach
                    </div>
                </div>

                <div class="flex flex-col space-y-2">
                    <label class="font-bold">培訓計畫名稱</label>
                    <p>{{ $data->plan }}</p>
                </div>

                <div class="flex flex-col space-y-2">
                    <label class="font-bold">學科測驗成績</label>
                    <p>{{ $data->score_academic }}</p>
                </div>

                <div class="flex flex-col space-y-2">
                    <label class="font-bold">術科測驗成績是是否合格</label>
                    <p>{{ $data->physical_pass?'合格':'不合格' }}</p>
                </div>

                <div class="flex flex-col space-y-2">
                    <label class="font-bold">參訓情形</label>
                    <table>
                        <thead>
                            <tr>
                                <th class="p-2 font-normal text-left border-r last:border-r-0">科目</th>
                                <th class="p-2 font-normal text-left border-r last:border-r-0">參訓</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($dpSubjects as $dpSubject)
                            <tr>
                                <td class="p-2 border-r last:border-r-0">{{ $dpSubject->name }}&nbsp;&nbsp;</td>
                                <td class="p-2 border-r last:border-r-0">
                                    {!! Form::checkbox('dp_subjects[]',
                                    $dpSubject->id,
                                    $data->dpStudentSubjects->keyBy('dp_subject_id')->has($dpSubject->id),
                                    ['class'=>'border-gray-300 rounded-sm bg-white text-mainCyanDark', 'readonly',
                                    'disabled'])
                                    !!}

                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="{{ asset('scripts/genericPostForm.js') }}"></script>
@endsection