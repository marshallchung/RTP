@extends('admin.layouts.dashboard', [
'heading' => $title,
'breadcrumbs' => [
'成果網功能',
$title,
]
])

@section('title', $title)

@section('inner_content')
<input type="hidden" value="{{ route('admin.showing.index', $topic) }}" id="js-current-route">
<div class="flex flex-col w-full max-w-2xl p-4 text-content text-mainAdminTextGrayDark">
    <div class="relative mb-6 bg-white border border-gray-200 rounded-sm">
        <div class="p-5 m-0 bg-white">
            <div id="js-select-county">
                @if (Auth::user()->origin_role <= 2) <ul class="nav nav-tabs">
                    @foreach ($accounts as $index => $area)
                    <li class="{{ $index !== 0 ?: 'active' }}">
                        <a href="#area-tab-{{ $index }}" data-toggle="tab">{{ $area['name'] }}</a>
                    </li>
                    @endforeach
                    </ul>
                    @endif
                    <div class="tab-content tab-content-bordered">
                        @foreach ($accounts as $index => $area)
                        <div id="area-tab-{{ $index }}" class="tab-pane {{ $index !== 0 ?: 'active' }}">
                            <div class="list-group m-0-b">
                                @foreach ($area['accounts'] as $account)
                                <div class="panel-group">
                                    @if (Auth::user()->origin_role > 2 &&
                                    Auth::user()->id != $account->id &&
                                    Auth::user()->county_id != $account->id)
                                    <?php continue; ?>
                                    @endif
                                    <div class="relative mb-6 bg-white border border-gray-200 rounded-sm">
                                        <div
                                            class="relative px-5 pt-3 pb-2 bg-gray-100 border-b-2 border-gray-200 text-mainAdminTextGrayDark">
                                            <a href="#county-{{ $account->id }}" class="accordion-toggle collapsed"
                                                data-toggle="collapse">{{ $account->name }}</a>
                                        </div>
                                        <div id="county-{{ $account->id }}" class="panel-collapse collapse list-group">
                                            <a href="{{ route('admin.showing.show', [$topic, $account->id]) }}"
                                                class="first:border-t-0 border-x-0 rounded-none relative block py-3 px-5 -mb-[1px] bg-white border">{{
                                                $account->name }}政府</a>
                                            @foreach ($account->districts['1'] as $district)
                                            @if (Auth::user()->origin_role == 5 && $district->id !=
                                            Auth::user()->id)
                                            <?php continue; ?>
                                            @endif
                                            <a href="{{ route('admin.showing.show', [$topic, $district->id]) }}"
                                                class="first:border-t-0 border-x-0 rounded-none relative block py-3 px-5 -mb-[1px] bg-white border">
                                                &nbsp;&nbsp;&ndash;&nbsp;&nbsp;{{ $district->name }}</a>
                                            @endforeach
                                            @foreach ($account->districts['2'] as $district)
                                            @if (Auth::user()->origin_role == 5 && $district->id !=
                                            Auth::user()->id)
                                            <?php continue; ?>
                                            @endif
                                            <a href="{{ route('admin.showing.show', [$topic, $district->id]) }}"
                                                class="first:border-t-0 border-x-0 rounded-none relative block py-3 px-5 -mb-[1px] bg-white border">
                                                &nbsp;&nbsp;&ndash;&nbsp;&nbsp;{{ $district->name }}</a>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                        @endforeach
                    </div>
            </div>
        </div>
    </div>
</div>
@stop

@section('scripts')
<script src="{{ asset('scripts/reportsIndex.js') }}"></script>
@if (Auth::user()->origin_role > 2)
<script type="text/javascript">
    $(function() {
                $('.collapse').collapse('show');
            });
</script>
@endif
@endsection