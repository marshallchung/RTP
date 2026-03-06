@extends('admin.layouts.dashboard', [
'heading' => "執行進度管制表 - {$fullName} (" . ($year-1911) . "年)",
'breadcrumbs' => [
['執行進度管制表', route('admin.seasonalReports.index')],
['展示', route('admin.seasonalReports.index')],
"{$fullName} (" . ($year-1911) . "年)"
]
])

@section('title', "執行進度管制表 - {$fullName} (" . ($year-1911) . "年)")

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
<div x-data="{}" class="flex flex-row items-center justify-start w-full p-4" x-init="$nextTick(() => {
})">
    <div class="flex flex-col flex-1 w-full max-w-3xl pb-16 space-y-12">
        <div class="flex flex-row w-full">
            <table class="w-full bg-white border text-mainAdminTextGrayDark">
                <thead>
                    <tr class="border-b rounded-t bg-mainGray">
                        <!-- 2016新增部分 -->
                        <th class="w-16 p-2 font-bold border-r last:border-r-0">公開</th>
                        <th class="w-16 p-2 font-bold border-r last:border-r-0">繳交</th>
                        <th class="p-2 font-bold border-r last:border-r-0">工作項目</th>
                    </tr>
                </thead>
                <tbody class="text-content text-mainAdminTextGrayDark">
                    @foreach ($report as $category)
                    <tr class="bg-white border-b last:border-b-0 last:rounded-b">
                        <td colspan="3" class="p-2 border-r last:border-r-0"><strong>{{ $category->name }}</strong></td>
                    </tr>
                    @foreach ($category->items as $item)
                    <tr class="bg-white border-b last:border-b-0 last:rounded-b">
                        <!--公開 -->
                        <td class="p-2 text-center align-top border-r last:border-r-0">
                            <br>
                            @if(isset($fileList[$item->id]))
                            @foreach($fileList[$item->id] as $year => $seasons)
                            <h5>-</h5>
                            @foreach ($seasons as $season => $files)
                            <h5 class="text-white">_</h5>
                            @foreach($files as $file)
                            <p class="p-0 m-0">
                                @if(is_null(auth()->user()->type))
                                <a href="{{ route('admin.seasonalReports.toggle',
                                                                        $file->id) }}" class="text-mainBlueDark">
                                    {{ ($file->opendata)? '公開' : 'Ｘ' }}
                                </a>
                                @else
                                {{ ($file->opendata)? '公開' : 'Ｘ' }}
                                @endif
                            </p>
                            @endforeach
                            @endforeach
                            @endforeach
                            @endif
                        </td>

                        <!-- 繳交 -->
                        <td class="p-2 text-center border-r last:border-r-0">
                            <?php
                                                $hasFile = false;
                                                if ($seasonalReports = $item->seasonalReports) {
                                                    foreach ($seasonalReports as $seasonalReport) {
                                                        if ($seasonalReport->user_id == $user->id && count($seasonalReport->files)
                                                        && ($season !== null && $seasonalReport->season == $season)) {
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
                            <p class="p-0 m-0 text-lg font-normal">{{ $item->title }}</p>
                            @if(isset($fileList[$item->id]))
                            @foreach($fileList[$item->id] as $year => $seasons)
                            <p class="p-0 m-0 text-base font-bold">{{ $year-1911 }}年度</p>
                            @foreach ($seasons as $season => $files)
                            <h6>{{ $season=='3'?'期末':($season=='2'?'期中':'期初') }}</h6>
                            @foreach($files as $file)
                            <div class="flex flex-row w-full items-stretchjustify-start">
                                <a href="{{ url($file->path) }}" class="text-left align-middle text-mainBlueDark">{{
                                    $file->name }}</a>
                                <span class="ml-2 text-mainAdminTextGray min-w-fit">
                                    &ndash; {{ $file->created_at->format('n/j/Y G:i') }}
                                </span>
                            </div>
                            @endforeach
                            @endforeach
                            @endforeach
                            @endif
                        </td>
                    </tr>
                    @endforeach
                    @endforeach
                </tbody>
            </table>
            {{--
            @if ($hasFiles)
            <div class="text-right panel-footer">
                {!! Html::linkroute('admin.seasonalReports.download', '下載', [$user->name, $year], ['class' => 'btn
                btn-primary btn-lg']) !!}
            </div>
            @endif
            --}}
        </div>
    </div>
</div>
@stop