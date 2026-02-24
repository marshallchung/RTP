<?php
/**
 * @var  Arcanedev\LogViewer\Entities\Log            $log
 * @var  Illuminate\Pagination\LengthAwarePaginator  $entries
 * @var  string|null                                 $query
 */
?>

@extends('log-viewer::bootstrap-3._master')

@section('content')
<h1 class="page-header">Log [{{ $log->date }}]</h1>

<div class="flex flex-row flex-wrap">
    <div class="col-md-2">
        <div class="relative mb-6 bg-white border border-gray-200 rounded-sm">
            <div class="relative px-5 pt-3 pb-2 bg-gray-100 border-b-2 border-gray-200 text-mainAdminTextGrayDark"><i
                    class="fa fa-fw fa-flag"></i> Levels</div>
            <ul class="text-sm text-mainAdminGrayDark">
                @foreach($log->menu() as $levelKey => $item)
                @if ($item['count'] === 0)
                <a href="#" class="list-group-item disabled">
                    <span class="badge">
                        {{ $item['count'] }}
                    </span>
                    {!! $item['icon'] !!} {{ $item['name'] }}
                </a>
                @else
                <a href="{{ $item['url'] }}" class="list-group-item {{ $levelKey }}">
                    <span class="badge level-{{ $levelKey }}">
                        {{ $item['count'] }}
                    </span>
                    <span class="level level-{{ $levelKey }}">
                        {!! $item['icon'] !!} {{ $item['name'] }}
                    </span>
                </a>
                @endif
                @endforeach
            </ul>
        </div>
    </div>
    <div class="col-md-10">
        {{-- Log Details --}}
        <div class="relative mb-6 bg-white border border-gray-200 rounded-sm">
            <div class="relative px-5 pt-3 pb-2 bg-gray-100 border-b-2 border-gray-200 text-mainAdminTextGrayDark">
                Log info :

                <div class="group-btns pull-right">
                    <a href="{{ route('log-viewer::logs.download', [$log->date]) }}" class="btn btn-xs btn-success">
                        <i class="fa fa-download"></i> DOWNLOAD
                    </a>
                    <a href="#delete-log-modal"
                        class="flex items-center justify-center w-6 h-6 text-sm text-white bg-orange-600 rounded cursor-pointer hover:bg-orange-500"
                        data-toggle="modal">
                        <i class="w-2.5 h-2.5 i-fa6-solid-trash"></i> DELETE
                    </a>
                </div>
            </div>
            <div class="table-responsive">
                <table class="table table-condensed">
                    <thead>
                        <tr>
                            <td class="p-2 border-r last:border-r-0">File path :</td>
                            <td colspan="5">{{ $log->getPath() }}</td>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="p-2 border-r last:border-r-0">Log entries :</td>
                            <td class="p-2 border-r last:border-r-0">
                                <span class="label label-primary">{{ $entries->total() }}</span>
                            </td>
                            <td class="p-2 border-r last:border-r-0">Size :</td>
                            <td class="p-2 border-r last:border-r-0">
                                <span class="label label-primary">{{ $log->size() }}</span>
                            </td>
                            <td class="p-2 border-r last:border-r-0">Created at :</td>
                            <td class="p-2 border-r last:border-r-0">
                                <span class="label label-primary">{{ $log->createdAt() }}</span>
                            </td>
                            <td class="p-2 border-r last:border-r-0">Updated at :</td>
                            <td class="p-2 border-r last:border-r-0">
                                <span class="label label-primary">{{ $log->updatedAt() }}</span>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="border-t py-2.5 px-5 rounded-br-sm rounded-bl-sm bg-white">
                {{-- Search --}}
                <form action="{{ route('log-viewer::logs.search', [$log->date, $level]) }}" method="GET">
                    <div class=form-group">
                        <div class="input-group">
                            <input id="query" name="query"
                                class="inline-block w-auto align-middle bg-white border-gray-300 rounded-md shadow-sm placeholder:text-mainTextGray focus:border-sky-300 focus:ring focus:ring-sky-200 focus:ring-opacity-50"
                                value="{{ $query }}" placeholder="Type here to search">
                            <span class="input-group-btn">
                                @unless (is_null($query))
                                <a href="{{ route('log-viewer::logs.show', [$log->date]) }}"
                                    class="flex items-center justify-center w-20 h-10 bg-gray-100 border border-gray-300 text-mainAdminTextGrayDark cursor-pointer hover:bg-gray-50 ">
                                    ({{ $entries->count() }} results) <span class="glyphicon glyphicon-remove"></span>
                                </a>
                                @endunless
                                <button id="search-btn"
                                    class="px-4 text-sm text-white rounded cursor-pointer py-1.5 bg-mainCyanDark hover:bg-teal-400">
                                    <span class="glyphicon glyphicon-search"></span>
                                </button>
                            </span>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        {{-- Log Entries --}}
        <div class="relative mb-6 bg-white border border-gray-200 rounded-sm">
            @if ($entries->hasPages())
            <div class="relative px-5 pt-3 pb-2 bg-gray-100 border-b-2 border-gray-200 text-mainAdminTextGrayDark">
                {{ $entries->appends(compact('query'))->render() }}

                <span class="label label-info pull-right">
                    Page {{ $entries->currentPage() }} of {{ $entries->lastPage() }}
                </span>
            </div>
            @endif

            <div class="table-responsive">
                <table id="entries" class="table table-condensed">
                    <thead>
                        <tr>
                            <th class="p-2 font-normal text-left border-r last:border-r-0">ENV</th>
                            <th style="width: 120px;">Level</th>
                            <th style="width: 65px;">Time</th>
                            <th class="p-2 font-normal text-left border-r last:border-r-0">Header</th>
                            <th class="text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($entries as $key => $entry)
                        <?php /** @var  Arcanedev\LogViewer\Entities\LogEntry  $entry */ ?>
                        <tr>
                            <td class="p-2 border-r last:border-r-0">
                                <span class="label label-env">{{ $entry->env }}</span>
                            </td>
                            <td class="p-2 border-r last:border-r-0">
                                <span class="level level-{{ $entry->level }}">{!! $entry->level() !!}</span>
                            </td>
                            <td class="p-2 border-r last:border-r-0">
                                <span class="label label-default">
                                    {{ $entry->datetime->format('H:i:s') }}
                                </span>
                            </td>
                            <td class="p-2 border-r last:border-r-0">
                                <p>{{ $entry->header }}</p>
                            </td>
                            <td class="text-right">
                                @if ($entry->hasStack())
                                <a class="btn btn-xs btn-default" role="button" data-toggle="collapse"
                                    href="#log-stack-{{ $key }}" aria-expanded="false"
                                    aria-controls="log-stack-{{ $key }}">
                                    <i class="fa fa-toggle-on"></i> Stack
                                </a>
                                @endif
                            </td>
                        </tr>
                        @if ($entry->hasStack())
                        <tr>
                            <td colspan="5" class="stack">
                                <div class="stack-content collapse" id="log-stack-{{ $key }}">
                                    {!! $entry->stack() !!}
                                </div>
                            </td>
                        </tr>
                        @endif
                        @empty
                        <tr>
                            <td colspan="5" class="text-center">
                                <span class="label label-default">{{ trans('log-viewer::general.empty-logs') }}</span>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($entries->hasPages())
            <div class="border-t py-2.5 px-5 rounded-br-sm rounded-bl-sm bg-white">
                {!! $entries->appends(compact('query'))->render() !!}

                <span class="label label-info pull-right">
                    Page {{ $entries->currentPage() }} of {{ $entries->lastPage() }}
                </span>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection

@section('modals')
{{-- DELETE MODAL --}}
<div id="delete-log-modal" class="modal fade">
    <div class="modal-dialog">
        <form id="delete-log-form" action="{{ route('log-viewer::logs.delete') }}" method="POST">
            <input type="hidden" name="_method" value="DELETE">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <input type="hidden" name="date" value="{{ $log->date }}">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <h4 class="modal-title">DELETE LOG FILE</h4>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to <span class="label label-danger">DELETE</span> this log file <span
                            class="label label-primary">{{ $log->date }}</span> ?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-sm btn-default pull-left" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-sm btn-danger" data-loading-text="Loading&hellip;">DELETE
                        FILE</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
    $(function () {
            var deleteLogModal = $('div#delete-log-modal'),
                deleteLogForm  = $('form#delete-log-form'),
                submitBtn      = deleteLogForm.find('button[type=submit]');

            deleteLogForm.on('submit', function(event) {
                event.preventDefault();
                submitBtn.button('loading');

                $.ajax({
                    url:      $(this).attr('action'),
                    type:     $(this).attr('method'),
                    dataType: 'json',
                    data:     $(this).serialize(),
                    success: function(data) {
                        submitBtn.button('reset');
                        if (data.result === 'success') {
                            deleteLogModal.modal('hide');
                            location.replace("{{ route('log-viewer::logs.list') }}");
                        }
                        else {
                            alert('OOPS ! This is a lack of coffee exception !')
                        }
                    },
                    error: function(xhr, textStatus, errorThrown) {
                        alert('AJAX ERROR ! Check the console !');
                        console.error(errorThrown);
                        submitBtn.button('reset');
                    }
                });

                return false;
            });

            @unless (empty(log_styler()->toHighlight()))
                @php
                    $htmlHighlight = version_compare(PHP_VERSION, '7.4.0') >= 0
                        ? join('|', log_styler()->toHighlight())
                        : join(log_styler()->toHighlight(), '|');
                @endphp
                $('.stack-content').each(function() {
                    var $this = $(this);
                    var html = $this.html().trim()
                        .replace(/({!! $htmlHighlight !!})/gm, '<strong>$1</strong>');

                    $this.html(html);
                });
            @endunless
        });
</script>
@endsection