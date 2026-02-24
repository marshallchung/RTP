@extends('admin.layouts.dashboard', [
'heading' => '防災士培訓 > 防災士培訓課程抵免申請',
'breadcrumbs' => [
['防災士培訓課程抵免', route('admin.dp-scores.index')],
'申請'
]
])

@section('title', '防災士培訓課程抵免申請')

@section('inner_content')
<div class="col-sm-9 col-lg-10">
    <div class="relative mb-6 bg-white border-gray-200 rounded-sm border">
        <div class="text-mainAdminTextGrayDark bg-gray-100 border-gray-200 border-b-2 pb-2 px-5 pt-3 relative">申請人基本資料
        </div>
        <div id="divStudentData" class="bg-white m-0 p-5">
            <div class="form-row">
                <div class="form-group col-md-4 text-center">
                    {!! Form::label('TID', '身分證字號') !!}
                </div>
                <div class="form-group col-md-8">
                    {!! Form::text('TID', null, ['class' => 'h-12 px-4 border-gray-300 rounded-md shadow-sm
                    focus:border-cyan-300 focus:ring focus:ring-cyan-200 focus:ring-opacity-50 w-full', 'required']) !!}
                </div>
            </div>

            <div class="form-row">
                <div class="form-group col-md-4 text-center">
                    {!! Form::label('county', '所屬縣市') !!}
                </div>
                <div class="form-group col-md-8">
                    {!! Form::text('county', null, ['class' => 'h-12 px-4 border-gray-300 rounded-md shadow-sm
                    focus:border-cyan-300 focus:ring focus:ring-cyan-200 focus:ring-opacity-50 w-full', 'disabled']) !!}
                </div>
            </div>

            <div class="form-row">
                <div class="form-group col-md-4 text-center">
                    {!! Form::label('name', '姓名') !!}
                </div>
                <div class="form-group col-md-8">
                    {!! Form::text('name', null, ['class' => 'h-12 px-4 border-gray-300 rounded-md shadow-sm
                    focus:border-cyan-300 focus:ring focus:ring-cyan-200 focus:ring-opacity-50 w-full', 'disabled']) !!}
                </div>
            </div>

            <div class="form-row">
                <div class="form-group col-md-4 text-center">
                    {!! Form::label('mobile', '行動電話') !!}
                </div>
                <div class="form-group col-md-8">
                    {!! Form::text('mobile', null, ['class' => 'h-12 px-4 border-gray-300 rounded-md shadow-sm
                    focus:border-cyan-300 focus:ring focus:ring-cyan-200 focus:ring-opacity-50 w-full', 'disabled']) !!}
                </div>
            </div>

            <div class="form-row">
                <div class="form-group col-md-4 text-center">
                    {!! Form::label('address', '聯絡地址') !!}
                </div>
                <div class="form-group col-md-8">
                    {!! Form::text('address', null, ['class' => 'h-12 px-4 border-gray-300 rounded-md shadow-sm
                    focus:border-cyan-300 focus:ring focus:ring-cyan-200 focus:ring-opacity-50 w-full', 'disabled']) !!}
                </div>
            </div>
        </div>
    </div>

    <div class="relative mb-6 bg-white border-gray-200 rounded-sm border">
        <div class="text-mainAdminTextGrayDark bg-gray-100 border-gray-200 border-b-2 pb-2 px-5 pt-3 relative">已抵免課程
        </div>
        <div class="bg-white m-0 p-5">
            <table id="tableData" class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th class="p-2 font-normal text-left border-r last:border-r-0">欲抵免防災士課程內容</th>
                        <th class="p-2 font-normal text-left border-r last:border-r-0">參與其他防災課程名稱</th>
                        <th class="p-2 font-normal text-left border-r last:border-r-0">辦理單位</th>
                        <th class="p-2 font-normal text-left border-r last:border-r-0">佐證資料上傳</th>
                        <th class="p-2 font-normal text-left border-r last:border-r-0">辦理時間</th>
                        <th class="p-2 font-normal text-left border-r last:border-r-0">審查結果</th>
                        <th class="p-2 font-normal text-left border-r last:border-r-0">審查意見</th>
                        <th class="p-2 font-normal text-left border-r last:border-r-0">審查時間</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>

    <div class="relative mb-6 bg-white border-gray-200 rounded-sm border">
        <div class="text-mainAdminTextGrayDark bg-gray-100 border-gray-200 border-b-2 pb-2 px-5 pt-3 relative">新增抵免資料
        </div>
        {!! Form::open(['method' => 'POST', 'route' => 'admin.dp-waivers.store', 'id' => 'formCreate']) !!}
        {!! Form::hidden('dp_student_id', 0) !!}
        <div class="bg-white m-0 p-5">
            <table id="tableCreate" class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th class="p-2 font-normal text-left border-r last:border-r-0">欲抵免防災士課程內容</th>
                        <th class="p-2 font-normal text-left border-r last:border-r-0">參與其他防災課程名稱</th>
                        <th class="p-2 font-normal text-left border-r last:border-r-0">辦理單位</th>
                        <th class="p-2 font-normal text-left border-r last:border-r-0">佐證資料上傳</th>
                        <th class="p-2 font-normal text-left border-r last:border-r-0">辦理時間</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
            <div class="border-t py-2.5 px-5 rounded-br-sm rounded-bl-sm bg-white">
                <div class="form-group text-center">
                    <div id="addOne" class="btn btn-default btn-lg">
                        <span class="glyphicon glyphicon-plus"></span> 增加一筆
                    </div>
                </div>
                <div class="flex flex-row flex-wrap text-center">
                    {!! Form::submit('送出', ['class' => 'w-full text-white flex justify-center items-center h-10
                    bg-mainCyanDark rounded']) !!}
                </div>
            </div>
            {!! Form::close() !!}
        </div>
    </div>
    <script src="{{ asset('scripts/genericPostForm.js') }}"></script>
    @endsection

    @section('scripts')
    <script type="text/javascript">
        function row (data) {
            var html = '';
            $.each (data.dp_waivers, function(idx, el) {
                html += '<tr>'+
                    '<td class="p-2 border-r last:border-r-0">'+el.dp_score.dp_course.name+'</td>'+
                    '<td class="p-2 border-r last:border-r-0">'+el.name+'</td>'+
                    '<td class="p-2 border-r last:border-r-0">'+el.dp_score.author.name+'</td>'+
                    '<td class="p-2 border-r last:border-r-0">';
                $.each(el.files, function(idx2, file) {
                    html += '<div class="flex flex-row flex-wrap">'+
                        '<div class="well well-sm mb-5 col-lg-7" data-id="'+file.id+'">'+
                            '<a href="'+file.path+'" target="_blank">'+file.name+'</a>'+
                        '</div>'+
                    '</div>';
                });
                html += '</td>'+
                    '<td class="p-2 border-r last:border-r-0">'+el.created_at+'</td>'+
                    '<td class="p-2 border-r last:border-r-0">'+el.review_result_text+'</td>'+
                    '<td class="p-2 border-r last:border-r-0">'+(el.review_comment ? el.review_comment : '')+'</td>'+
                    '<td class="p-2 border-r last:border-r-0">'+(el.review_at ? el.review_at : '')+'</td>'+
                '</tr>';
            });
            return html;
        }

        $('#TID').on('keyup, change', function() {
            var TID = $(this).val();
            if (TID.length === 10) {
                var formCreate_student_id = $('#formCreate').find('input[name="dp_student_id"]');
                    formCreate_student_id.val('');

                var tbody = $('#tableData').find('tbody');
                    tbody.html('');

                $('#divStudentData').find('input').not('#TID').val('');

                $.ajax({
                    url: "{{ route('admin.dp-waivers.getStudent') }}",
                    type: 'get',
                    dataType: 'json',
                    data: {
                        TID: TID
                    },
                    success: function (data) {
                        console.log(data);
                        formCreate_student_id.val(data.id);

                        $('#county').val(data.county.name);
                        $('#name').val(data.name);
                        $('#mobile').val(data.mobile);
                        $('#address').val(data.address);
                        tbody.html(row(data));
                    },
                    error: function (json) {
                        formCreate_student_id.val(0);

                        if (json.status === 422) {
                            var errors = json.responseJSON;
                            $.each(json.responseJSON, function (key, value) {
                                alert(value);
                            });

                        } else {
                           alert('unexpected');
                        }
                    }
                });
            }
        });

        $('#addOne').on('click', function() {
            var seq = $('#tableCreate tr').length - 1;

            var course_id = '{!! Form::select("dp_course_id[]", $courses, null, ["class" => "form-control", "required"]) !!}';
            var waiverName = '{!! Form::text("waiverName[]", null, ["class" => "form-control", "required"]) !!}';
            var author = '{{ Auth::user()->name }}';
            var created_at = '系統時間';
            var file = '<input multiple="1" name="files_'+seq+'[]" type="file">';

            $('#tableCreate tbody').append(
                 '<tr>'+
                    '<td class="p-2 border-r last:border-r-0">'+course_id+'</td>'+
                    '<td class="p-2 border-r last:border-r-0">'+waiverName+'</td>'+
                    '<td class="p-2 border-r last:border-r-0">'+author+'</td>'+
                    '<td class="p-2 border-r last:border-r-0">'+file+'</td>'+
                    '<td class="p-2 border-r last:border-r-0">'+created_at+'</td>'+
                '</tr>'
            );
        });

        $('#formCreate').ajaxForm({
            success: function (data) {
                alert(data.msg);
                $('#TID').change();
                $('#tableCreate').find('tbody').html('');
            },
            error: function (json) {
                if (json.status === 422) {
                    var errors = json.responseJSON;
                    $.each(json.responseJSON, function (key, value) {
                        alert(value);
                    });

                } else {
                   alert('unexpected');
                }
            }
        });
    </script>
    @endsection