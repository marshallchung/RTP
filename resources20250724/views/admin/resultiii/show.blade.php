<?php
$title = request('title', '成果資料（三期）');
?>

@extends('admin.layouts.dashboard', [
'heading' => $title . " - {$fullName}",
'breadcrumbs' => [
[$title, route('admin.resultiii.index')],
['展示', route('admin.resultiii.index')],
"{$fullName}"
]
])

@section('title', $title . " - {$fullName}")

@section('scripts')
<script>
    $(document).ready(function () {
            $('.file-toggle-btn').click(function (event) {
                event.preventDefault();
                var fileId = $(this).data('file-id');
            });
        });
        $(function () {
            $('#filter_year').change(function () {
                // Construct URLSearchParams object instance from current URL querystring.
                let queryParams = new URLSearchParams(window.location.search);
                let year = $(this).val();
                if (year) {
                    // Set new or modify existing parameter value.
                    queryParams.set('filter_year', year);
                } else {
                    queryParams.delete('filter_year')
                }
                // Replace current querystring with the new one.
                // history.replaceState(null, null, '?' + queryParams.toString());
                // Redirect with the new one
                window.location.search = queryParams.toString();
            })
            $('#filter_recommend').change(function () {
                // Construct URLSearchParams object instance from current URL querystring.
                let queryParams = new URLSearchParams(window.location.search);
                let recommend = $(this).val();
                if (recommend) {
                    // Set new or modify existing parameter value.
                    queryParams.set('filter_recommend', recommend);
                } else {
                    queryParams.delete('filter_recommend')
                }
                // Replace current querystring with the new one.
                // history.replaceState(null, null, '?' + queryParams.toString());
                // Redirect with the new one
                window.location.search = queryParams.toString();
            })
        })
</script>
@endsection

@section('inner_content')
<div class="flex flex-row flex-wrap">
    <div class="col-lg-8 col-md-10">
        <div class="border-gray-200 mb-6 relative bg-white border rounded-sm">
            <div class="bg-white m-0 p-5">
                <div class="table-light">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th class="col-lg-1 text-center align-middle" style="width: 47px;">繳交</th>
                                <th class="align-middle">工作項目</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($report as $category)
                            <tr>
                                <td colspan="5">
                                    <strong>{{ $category->name }}</strong>
                                </td>
                            </tr>
                            @foreach ($category->items as $item)
                            <tr>
                                <!-- 繳交 -->
                                <td class="text-center p-2 border-r last:border-r-0">
                                    <?php
                                            $hasFile = false;
                                            if ($reports = $item->reports) {
                                                foreach ($reports as $report) {
                                                    if ($report->user_id == $user->id && count($report->files)) {
                                                        $hasFile = true;
                                                        break;
                                                    }
                                                }
                                            }
                                            ?>
                                    @if ($hasFile)
                                    <i class="fa fa-check-circle text-success text-lg"></i>
                                    @endif
                                </td>

                                <!-- 工作項目 -->
                                <td class="p-2 border-r last:border-r-0">
                                    <p class="text-base p-0 m-0">{{ $item->title }}</p>
                                    @if(isset($fileList[$item->id]))
                                    @foreach($fileList[$item->id] as $checkYear => $files)
                                    <h4>{{ $checkYear }}年度</h4>
                                    @foreach($files as $file)
                                    <p class="p-0 m-0">
                                        <a href="{{ url($file->path) }}">{{ $file->name }}</a>
                                        <span class="text-mainAdminTextGray">&ndash; {{ $file->created_at->format('n/j/Y
                                            G:i')
                                            }}</span>
                                    </p>
                                    @endforeach
                                    @endforeach
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@stop