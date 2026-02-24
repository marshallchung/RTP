@extends('admin.layouts.dashboard', [
'heading' => "計畫執行成果展示 - {$fullName} ({$year})",
'breadcrumbs' => [
['計畫執行成果', route('admin.reports.index')],
['展示', route('admin.reports.index')],
"{$fullName} ({$year})"
]
])

@section('title', "計畫執行成果展示 - {$fullName} ({$year})")

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
                        <th class="w-12 p-2 font-bold border-r last:border-r-0">公開</th>
                        <th class="w-12 p-2 font-bold border-r last:border-r-0">繳交</th>
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
                            @foreach($fileList[$item->id] as $year => $files)
                            <h5>-</h5>
                            @foreach($files as $file)
                            <p class="p-0 m-0">
                                @if(is_null(auth()->user()->type))
                                <a href="{{ route('admin.reports.toggle', $file->id) }}" class="text-mainBlueDark">
                                    {{ ($file->opendata)? '公開' : 'Ｘ' }}
                                </a>
                                @else
                                {{ ($file->opendata)? '公開' : 'Ｘ' }}
                                @endif
                            </p>
                            @endforeach
                            @endforeach
                            @endif
                        </td>

                        <!-- 繳交 -->
                        <td class="p-2 text-center border-r last:border-r-0">
                            @if (isset($item->reports) && count($item->reports->files))
                            <i class="w-6 h-6 text-lime-600 i-fa6-solid-circle-check"></i>
                            @endif
                        </td>

                        <!-- 工作項目 -->
                        <td class="p-2 border-r last:border-r-0">
                            <p class="p-0 m-0 text-lg font-normal">{{ $item->title }}</p>
                            @if(isset($fileList[$item->id]))
                            @foreach($fileList[$item->id] as $year => $files)
                            <h5>{{ $year }}年度</h5>
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
                        </td>
                    </tr>
                    @endforeach
                    @endforeach
                </tbody>
            </table>
            {{--
            @if ($hasFiles)
            <div class="text-right panel-footer">
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