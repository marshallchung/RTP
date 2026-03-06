@extends('admin.layouts.dashboard', [
'heading' => '防災士培訓 > 師資資料庫管理',
'breadcrumbs' => [
['師資資料庫管理', route('admin.dp-teachers.index')],
'檢視'
]
])

@section('title', $data->title)

@section('inner_content')
<div class="flex flex-col items-start justify-start w-full p-4 space-y-4">
    <div class="md:w-67/100 xl:w-75/100">
        <div class="relative mb-6 bg-white border border-gray-200 rounded-sm">
            <div class="relative px-5 pt-3 pb-2 bg-gray-100 border-b-2 border-gray-200 text-mainAdminTextGrayDark">檢視
            </div>
            <div class="flex flex-col p-5 space-y-8 bg-white">
                <div class="flex flex-col space-y-2">
                    <label class="font-bold">姓名</label>
                    <p>{{ $data->name }}</p>
                </div>

                <div class="flex flex-col space-y-2">
                    <label class="font-bold">服務單位</label>
                    <p>{{ $data->belongsTo }}</p>
                </div>

                <div class="flex flex-col space-y-2">
                    <label class="font-bold">居住縣市</label>
                    <p>{{ $data->location }}</p>
                </div>

                <div class="flex flex-col space-y-2">
                    <label class="font-bold">職別</label>
                    <p>{{ $data->title }}</p>
                </div>

                <div class="flex flex-col space-y-2">
                    <label class="font-bold">電話</label>
                    <p>{{ $data->phone }}</p>
                </div>

                <div class="flex flex-col space-y-2">
                    <label class="font-bold">電子郵件</label>
                    <p>{{ $data->email }}</p>
                </div>

                <div class="flex flex-col space-y-2">
                    <label class="font-bold">教授科目</label>
                    <ul>
                        @foreach($data->dpTeacherSubjects as $dpTeacherSubjects)
                        <li>
                            {{ $dpTeacherSubjects->dpSubject->name }}
                            （{{ $dpTeacherSubjects->type }}）
                        </li>
                        @endforeach
                    </ul>
                </div>

                <div class="flex flex-col space-y-2">
                    <label class="font-bold">學經歷專長</label>
                    <div>{!! $data->content !!}</div>
                </div>
                <div class="flex flex-col space-y-2">
                    @if(isset($data->files))
                    <div class="flex flex-col space-y-2">
                        @foreach($data->files as $file)
                        <div class="flex flex-row items-center justify-start w-full p-2 bg-mainLight"
                            data-id="{{ $file->id }}">
                            <a href="/{{ $file->path }}" target="_blank" class="flex-1 text-left text-mainBlueDark">{{
                                $file->name }}</a>
                        </div>
                        @endforeach
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<script src="{{ asset('scripts/genericPostForm.js') }}"></script>
@endsection