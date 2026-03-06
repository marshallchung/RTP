@extends('admin.layouts.dashboard', [
'heading' => '編輯課程',
'breadcrumbs' => [
['消息', route('admin.dp-students.index')],
'編輯'
]
])

@section('title', $data->title)

@section('inner_content')
{!! Form::model($data, ['route' => ['admin.dp-students.update', $data->id], 'method' => 'put', 'files' => true]) !!}
<div class="md:w-67/100 xl:w-75/100">
    <div class="relative mb-6 bg-white border border-gray-200 rounded-sm">
        <div class="relative px-5 pt-3 pb-2 bg-gray-100 border-b-2 border-gray-200 text-mainAdminTextGrayDark">新增</div>
        <div class="p-5 m-0 bg-white">

            <div class="flex flex-row flex-wrap text-center">
                {!! Form::label('TID', '身份證字號（必填）') !!}
                {!! Form::text('TID', null, ['class' => 'h-12 px-4 border-gray-300 rounded-md shadow-sm
                focus:border-cyan-300 focus:ring focus:ring-cyan-200 focus:ring-opacity-50 w-full'], 'required') !!}
            </div>

            <div class="flex flex-row flex-wrap text-center">
                {!! Form::label('name', '姓名（必填）') !!}
                {!! Form::text('name', null, ['class' => 'h-12 px-4 border-gray-300 rounded-md shadow-sm
                focus:border-cyan-300 focus:ring focus:ring-cyan-200 focus:ring-opacity-50 w-full'], 'required') !!}
            </div>

            <div class="flex flex-row flex-wrap text-center">
                {!! Form::label('birth', '出生年（西元）（必填）') !!}
                {!! Form::text('birth', null, ['class' => 'h-12 px-4 border-gray-300 rounded-md shadow-sm
                focus:border-cyan-300 focus:ring focus:ring-cyan-200 focus:ring-opacity-50 w-full'], 'required') !!}
            </div>

            <div class="flex flex-row flex-wrap text-center">
                {!! Form::label('gender', '性別（必填）') !!}
                <div class="flex flex-row flex-wrap text-center">
                    <?php
                    $checked = $data->gender == '男'?true:false;
                            ?>
                    <label class="radio-inline">{!! Form::radio('gender', '男', $checked, ['class' => 'border-gray-300
                        rounded-full bg-white text-mainCyanDark']) !!}男</label>
                    <label class="radio-inline">{!! Form::radio('gender', '女', !$checked, ['class' => 'border-gray-300
                        rounded-full bg-white text-mainCyanDark']) !!}女</label>
                </div>
            </div>

            <div class="flex flex-row flex-wrap text-center">
                {!! Form::label('field', '工作領域（必填）') !!}
                {!! Form::select('field', [
                '勞工' => '勞工',
                ], $data->field, [
                'class' => 'h-12 px-4 border-gray-300 rounded-md shadow-sm focus:border-cyan-300 focus:ring
                focus:ring-cyan-200 focus:ring-opacity-50 w-full'
                ]) !!}
            </div>

            <div class="flex flex-row flex-wrap text-center">
                {!! Form::label('phone', '市內電話（必填）') !!}
                {!! Form::text('phone', null, ['class' => 'h-12 px-4 border-gray-300 rounded-md shadow-sm
                focus:border-cyan-300 focus:ring focus:ring-cyan-200 focus:ring-opacity-50 w-full']) !!}
            </div>

            <div class="flex flex-row flex-wrap text-center">
                {!! Form::label('mobile', '行動電話（必填）') !!}
                {!! Form::text('mobile', null, ['class' => 'h-12 px-4 border-gray-300 rounded-md shadow-sm
                focus:border-cyan-300 focus:ring focus:ring-cyan-200 focus:ring-opacity-50 w-full']) !!}
            </div>

            <div class="flex flex-row flex-wrap text-center">
                {!! Form::label('email', 'E-mail（必填）') !!}
                {!! Form::text('email', null, ['class' => 'h-12 px-4 border-gray-300 rounded-md shadow-sm
                focus:border-cyan-300 focus:ring focus:ring-cyan-200 focus:ring-opacity-50 w-full']) !!}
            </div>

            <div class="flex flex-row flex-wrap text-center">
                {!! Form::label('address', '居住地址（必填）') !!}
                {!! Form::text('address', null, ['class' => 'h-12 px-4 border-gray-300 rounded-md shadow-sm
                focus:border-cyan-300 focus:ring focus:ring-cyan-200 focus:ring-opacity-50 w-full']) !!}
            </div>

            <div class="flex flex-row flex-wrap text-center">
                {!! Form::label('community', '所屬村里或社區（必填）') !!}
                {!! Form::text('community', null, ['class' => 'h-12 px-4 border-gray-300 rounded-md shadow-sm
                focus:border-cyan-300 focus:ring focus:ring-cyan-200 focus:ring-opacity-50 w-full']) !!}
            </div>

            <div class="flex flex-row flex-wrap text-center">
                {!! Form::label('county_id', '所屬縣市') !!}
                {!! Form::select('county_id', $counties, request('county_id'), ['class' => 'h-12 px-4 border-gray-300
                rounded-md shadow-sm focus:border-cyan-300 focus:ring focus:ring-cyan-200 focus:ring-opacity-50
                w-full']) !!}
            </div>

            {!! Form::submit('送出', ['class' => 'w-full cursor-pointer hover:bg-teal-400 text-white flex justify-center
            items-center h-10 bg-mainCyanDark
            rounded']) !!}
            {!! Form::close() !!}
            <a @click="showDeleteModel=true" data-toggle="modal" data-target="#js-delete-modal"
                class="flex items-center justify-center w-full h-10 text-white bg-orange-500 rounded cursor-pointer hover:bg-orange-400">刪除</a>
        </div>
        {!! Form::model($data, ['method' => 'DELETE', 'route' => ['admin.dp-students.destroy', $data->id]]) !!}
        @include('admin.layouts.partials.genericDeleteForm')
        {!! Form::close() !!}
    </div>
</div>
@endsection