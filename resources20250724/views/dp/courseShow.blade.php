@extends('layouts.app')

@section('title', '防災士培訓 - 培訓課程查詢')

@section('content')
<div class="flex flex-row items-start justify-center sm:px-20">
    <div class="flex flex-col items-center justify-start w-full max-w-6xl pb-12">
        <div class="flex-auto p-5">
            <div class="flex flex-row flex-wrap text-center">
                <label class="p-1 text-secondary">課程名稱</label>
                <span class="p-1 h5">{{ $data->name }}</span>
            </div>

            <div class="flex flex-row flex-wrap text-center">
                <label class="p-1 text-secondary">主辦單位</label>
                <span class="p-1 h5">{{ $data->organizer }}</span>
            </div>

            <div class="form-row">
                <div class="form-group col-md-6">
                    <label class="p-1 text-secondary">課程期間（起）</label>
                    <span class="p-1 h5">{{ date('Y-m-d', strtotime($data->date_from)) }}</span>
                </div>
                <div class="form-group col-md-6">
                    <label class="p-1 text-secondary">課程期間（迄）</label>
                    <span class="p-1 h5">{{ date('Y-m-d', strtotime($data->date_to)) }}</span>
                </div>
            </div>

            <div class="flex flex-row flex-wrap text-center">
                <label class="p-1 text-secondary">連絡電話</label>
                <span class="p-1 h5">{{ $data->phone }}</span>
            </div>

            <div class="flex flex-row flex-wrap text-center">
                <label class="p-1 text-secondary">電子郵件</label>
                <span class="p-1 h5">{{ $data->email }}</span>
            </div>

            <div class="flex flex-row flex-wrap text-center">
                <label class="p-1 text-secondary">報名連結</label>
                <span class="p-1 h5"><a href="{{ $data->url }}" target="_blank">{{ $data->url }}</a></span>
            </div>

            <div class="flex flex-row flex-wrap text-center">
                @if ($data->advance)
                @if ($data['course_subjects'])
                <table class="my-8 border rounded ">
                    <thead>
                        <tr class="text-white bg-mainGrayDark">
                            <th class="p-2 font-normal text-center border-r last:border-r-0">進階防災士課程名稱</th>
                            <th class="p-2 font-normal text-center border-r last:border-r-0">時數</th>
                            <th class="p-2 font-normal text-center border-r last:border-r-0">授課日期</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($data['course_subjects'] as $dpSubject)
                        @if (!empty($dpSubject->start_date))
                        <tr>
                            <td class="p-2 text-sm text-left border-r last:border-r-0">{{ $dpSubject->name
                                }}&nbsp;&nbsp;</td>
                            <td class="p-2 border-r last:border-r-0">
                                {{ $dpSubject->hour }}小時
                            </td>
                            <td class="p-2 border-r last:border-r-0">
                                {{ $dpSubject->start_date }}
                            </td>
                        </tr>
                        @endif
                        @endforeach
                    </tbody>
                </table>
                @endif
                @else
                <label class="p-1 text-secondary">內容說明</label>
                <div>
                    {!! $data->content !!}
                </div>
                @endif
            </div>
            @if ($data->files)
            <div class="flex flex-row flex-wrap text-center">
                <label class="text-secondary">附件</label>
            </div>

            @foreach($data->files as $file)
            <div class="flex flex-row items-center justify-start w-full p-2 bg-mainLight" data-id="{{ $file->id }}">
                <a href="/{{ $file->path }}" target="_blank" class="flex-1 text-left text-mainBlueDark">{{
                    $file->name }}</a>
            </div>
            @endforeach
            @endif
        </div>
    </div>
</div>

<script src="{{ asset('scripts/genericPostForm.js') }}"></script>
@endsection
