@extends('admin.layouts.dashboard', [
'heading' => '防災士培訓 > 成績登錄',
'breadcrumbs' => [
['防災士培訓', route('admin.dp-scores.index')],
'新增成績'
]
])

@section('title', '成績登錄')

@section('inner_content')
{!! Form::open(['method' => 'POST', 'route' => 'admin.dp-scores.store']) !!}
{!! Form::hidden('course_id', $course->id) !!}
<div class="md:w-67/100 xl:w-75/100">
    <div class="relative mb-6 bg-white border-gray-200 rounded-sm border">
        <div class="text-mainAdminTextGrayDark bg-gray-100 border-gray-200 border-b-2 pb-2 px-5 pt-3 relative">新增</div>
        <div id="form-body" class="bg-white m-0 p-5">
            <div class="flex flex-row flex-wrap text-center">
                <div>
                    <span>課程名稱 - </span>
                    <span><strong>{{ $course->name }}</strong></span>
                </div>
                <div>
                    <span>課程期間 - </span>
                    <span><strong>{{ $course->date_from }}～{{ $course->date_to }}</strong></span>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-md-6">
                    {!! Form::text('TID[]', null, [
                    'class' => 'h-12 px-4 border-gray-300 rounded-md shadow-sm focus:border-cyan-300 focus:ring
                    focus:ring-cyan-200 focus:ring-opacity-50 w-full',
                    'placeholder' => '身份證字號'
                    ], 'required') !!}
                </div>
                <div class="form-group col-md-6">
                    {!! Form::text('score[]', null, [
                    'class' => 'h-12 px-4 border-gray-300 rounded-md shadow-sm focus:border-cyan-300 focus:ring
                    focus:ring-cyan-200 focus:ring-opacity-50 w-full',
                    'placeholder' => '課程成績'
                    ], 'required') !!}
                </div>
            </div>
        </div>
        <div class="border-t py-2.5 px-5 rounded-br-sm rounded-bl-sm bg-white">
            <div class="form-group text-center">
                <btn id="addOne" class="btn btn-default btn-lg">
                    <span class="glyphicon glyphicon-plus"></span> 增加一筆
                </btn>
            </div>
            <div class="flex flex-row flex-wrap text-center">
                {!! Form::submit('送出', ['class' => 'w-full text-white flex justify-center items-center h-10
                bg-mainCyanDark rounded']) !!}
            </div>
        </div>
    </div>
</div>
</div>
{!! Form::close() !!}
<script src="{{ asset('scripts/genericPostForm.js') }}"></script>
@endsection

@section('scripts')
<script type="text/javascript">
    $('#addOne').on('click', function() {
            var tidInput = '{!! Form::text("TID[]", null, ["class" => "form-control", "placeholder" => "身份證字號"], "required") !!}';
            var scoreInput = '{!! Form::text("score[]", null, ["class" => "form-control", "placeholder" => "課程成績"], "required") !!}';

            $('#form-body').append(
                 '<div class="form-row">'+
                        '<div class="form-group col-md-6">'+
                            tidInput+
                        '</div>'+
                        '<div class="form-group col-md-6">'+
                            scoreInput+
                        '</div>'+
                    '</div>'
            );
        });
</script>
@endsection