<?php
$title = request('title', '成果資料展示');
?>

@extends('admin.layouts.dashboard', [
'heading' => $title,
'breadcrumbs' => [
[$title, route('admin.reports.index')],
'維修'
]
])

@section('title', $title)

@section('inner_content')
<div class="flex flex-row flex-wrap">
    <div class="col-sm-12">
        <div class="relative mb-6 bg-white border border-gray-200 rounded-sm">
            <div class="p-5 m-0 bg-white">
                將來源 Topic 中，上傳日期為 2019 的檔案，移到目標 Topic
                {{ Form::open(['route' => 'admin.reports.fix']) }}
                <div class="flex flex-row flex-wrap text-center">
                    {{ Form::label('oldTopic', '來源：（2018）') }}
                    {{ Form::select('oldTopic', $oldTopicOptions, null, ['required']) }}
                </div>
                <div class="flex flex-row flex-wrap text-center">
                    {{ Form::label('newTopic', '目標：（2019）') }}
                    {{ Form::select('newTopic', $newTopicOptions, null, ['required']) }}
                </div>
                <div class="flex flex-row flex-wrap text-center">
                    {{ Form::label('type', '類型') }}
                    {{ Form::select('type', [null => null, 'county' => '縣市', 'district' => '分區'], null, ['required']) }}
                </div>
                {{ Form::submit('送出', ['class' => 'btn btn-lg btn-primary']) }}
                {{ Form::close() }}
            </div>
        </div>
    </div>
</div>
@stop

@section('scripts')
<script src="{{ asset('scripts/reportsIndex.js') }}"></script>
@endsection