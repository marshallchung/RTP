<?php
$title = request('title', '成果資料展示');
?>

@extends('admin.layouts.dashboard', [
'heading' => $title . " - {$fullName} ({$year})",
'breadcrumbs' => [
[$title, route('admin.reports.index')],
['展示', route('admin.reports.index')],
"{$fullName} ({$year})"
]
])

@section('title', $title . " - {$fullName} ({$year})")

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
<div x-data="{}" class="flex flex-row items-center justify-start w-full p-4" x-init="$nextTick(() => {
})">
    <div class="flex flex-col flex-1 w-full max-w-3xl pb-16 space-y-12">
        @if($isHistoricalReferenceArea)
        <div class="flex items-start justify-start w-full">
            <div class="flex flex-row items-center justify-start space-x-2">
                <span>年份</span>
                {!! Form::select('filter_year', $availableYears, request('filter_year'), ['id' => 'filter_year',
                'class'
                =>
                "inline-block w-auto ml-2 align-middle bg-white border-gray-300 rounded-md shadow-sm text-content
                text-mainAdminTextGrayDark placeholder:text-mainTextGray focus:border-sky-300 focus:ring
                focus:ring-sky-200
                focus:ring-opacity-50"]) !!}
            </div>
        </div>
        @endif
        <div class="flex flex-row w-full">
            <table class="w-full bg-white border text-mainAdminTextGrayDark">
                <thead>
                    <tr class="border-b rounded-t bg-mainGray">
                        <th class="w-12 p-2 font-bold border-r last:border-r-0">繳交</th>
                        <th class="w-20 p-2 font-bold text-left border-r last:border-r-0">工作項目</th>
                    </tr>
                </thead>
                <tbody class="text-content text-mainAdminTextGrayDark">
                    @foreach ($report as $category)
                    <tr class="bg-white border-b last:border-b-0 last:rounded-b">
                        <td colspan="{{ $isHistoricalReferenceArea ? 3 : 5 }}" class="p-2 border-r last:border-r-0">
                            <strong>{{ $category->name }}</strong>
                        </td>
                    </tr>
                    @foreach ($category->items as $item)
                    <tr class="bg-white border-b last:border-b-0 last:rounded-b">
                        <!-- 繳交 -->
                        <td class="p-2 text-center align-top border-r last:border-r-0">
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
                            <i class="w-6 h-6 text-lime-600 i-fa6-solid-circle-check"></i>
                            @endif
                        </td>

                        <!-- 工作項目 -->
                        <td class="p-2 border-r last:border-r-0">
                            <div class="flex flex-col items-start justify-start w-full pb-1 space-y-1 text-sm">
                                <p class="p-0 m-0 text-lg font-normal">{{ $item->title }}</p>
                                @if(isset($fileList[$item->id]))
                                @foreach($fileList[$item->id] as $checkYear => $files)
                                <h5>{{ $checkYear }}年度</h5>
                                @foreach($files as $file)
                                <div class="flex flex-row w-full items-stretchjustify-start">
                                    <a href="{{ url($file->path) }}" class="text-left align-middle text-mainBlueDark">{{
                                        $file->name }}</a>
                                    <span class="ml-2 text-mainAdminTextGray min-w-fit">&ndash; {{
                                        $file->created_at->format('n/j/Y
                                        G:i')
                                        }}</span>
                                </div>
                                @endforeach
                                @endforeach
                                @endif
                            </div>
                        </td>
                    </tr>
                    @endforeach
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@stop