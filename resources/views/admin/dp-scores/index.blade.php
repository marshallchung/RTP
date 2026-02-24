@extends('admin.layouts.dashboard', [
'heading' => '防災士培訓 > 成績登錄',

'breadcrumbs' => ['成績登錄']
])

@section('title', '成績登錄')

@section('inner_content')
<div class="col-sm-12 p-0">
    <div class="flex flex-row flex-wrap">
        {!! Form::open(['method'=>'get', 'class' => 'flex flex-row flex-wrap text-center']) !!}
        {!! Form::label('formControl_name', '課程名稱') !!}
        {!! Form::select('formControl_name', $courses, request('course_id'), ['class' => 'h-12 px-4 border-gray-300
        rounded-md shadow-sm focus:border-cyan-300 focus:ring focus:ring-cyan-200 focus:ring-opacity-50 w-full']) !!}
        <a id="addStudent" href="" class="btn btn-lg btn-primary" disabled>登錄成績</a>
        {!! Form::close() !!}
    </div>

    <div style="overflow: auto">
        <table id="tableData" class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th class="p-2 font-normal text-left border-r last:border-r-0">學員身份證字號</th>
                    <th class="p-2 font-normal text-left border-r last:border-r-0">姓名</th>
                    <th class="p-2 font-normal text-left border-r last:border-r-0">課程成績</th>
                    <th class="p-2 font-normal text-left border-r last:border-r-0">登錄時間</th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>


    <div id="js-token" class="hidden">{{ csrf_token() }}</div>
</div>
@endsection

@section('scripts')
<script src="{{ asset('scripts/generalIndex.js') }}"></script>
<script type="text/javascript">
    function getRows (studentsData) {
            //console.log(studentsData);
            var html = '';
            $.each(studentsData, function(idx, el) {
                var tr = '<tr>'+
                    '<td class="p-2 border-r last:border-r-0">'+el.dp_student.TID+'</td>'+
                    '<td class="p-2 border-r last:border-r-0">'+el.dp_student.name+'</td>'+
                    '<td class="p-2 border-r last:border-r-0">'+el.score+'</td>'+
                    '<td class="p-2 border-r last:border-r-0">'+el.created_at+'</td>'+
                '</tr>';
                html += tr;
            });
            return html;
        }

        $('#formControl_name').on('change', function() {
            var course_id = $(this).val();
            $.ajax({
                type: 'get',
                dataType: 'json',
                url: '{{ route("admin.dp-scores.getStudents") }}',
                data: {
                    course_id: course_id
                },
                success: function (data) {
                    if (data.msg) {
                        alert(data.msg);
                        return;
                    }
                    $('#addStudent').removeAttr('disabled');
                    $('#addStudent').attr('href', '{{ route("admin.dp-scores.create") }}?course_id=' + course_id);

                    var tbody = $('#tableData').find('tbody');
                        tbody.html('');
                        tbody.html(getRows(data));
                },
                error: function (error) {
                    console.log(error);
                }
            })
        });

        $('#formControl_name').change();
</script>
@endsection