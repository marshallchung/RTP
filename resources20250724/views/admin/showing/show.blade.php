@extends('admin.layouts.dashboard', [
'heading' => "{$title} - {$fullName}",
'breadcrumbs' => [
'成果網功能',
[$title, route('admin.showing.index', $topic)],
$fullName,
]
])

@section('title', "{$title} - {$fullName}")

@section('scripts')
<script>
    $(document).ready(function () {
            $('.file-toggle-btn').click(function (event) {
                event.preventDefault();
                var fileId = $(this).data('file-id');
            });
        });
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
                                <!-- 2016新增部分 -->
                                <th class="col-lg-1 text-center align-middle" style="width: 47px;">繳交</th>
                                <th class="align-middle">工作項目</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($report as $category)
                            <tr>
                                <td colspan="3"><strong>{{ $category->name }}</strong></td>
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
                                    @foreach($fileList[$item->id] as $year => $files)
                                    <h4>{{ $year }}年度</h4>
                                    @foreach($files as $file)
                                    <p class="p-0 m-0">
                                        @if($canToggle)
                                        <a href="{{ route('admin.showing.toggle', $file->id) }}"
                                            class="btn btn-xs {{ ($file->opendata)?'btn-primary':'btn-default' }}">
                                            {{ ($file->opendata)? '成果網' : '未公開' }}
                                        </a>
                                        @else
                                        <span
                                            class="btn btn-xs disabled {{ ($file->opendata)?'btn-primary':'btn-default' }}">
                                            {{ ($file->opendata)? '成果網' : '未公開' }}
                                        </span>
                                        @endif
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
            {{--
            @if ($hasFiles)
            <div class="panel-footer text-right">
                {!! Html::linkroute('admin.reports.download', '下載', [$user->name, $year], ['class' => 'px-4 text-sm
                text-white rounded cursor-pointer py-1.5 bg-mainCyanDark hover:bg-teal-400
                btn-lg']) !!}
            </div>
            @endif
            --}}
        </div>
    </div>
</div>
@stop