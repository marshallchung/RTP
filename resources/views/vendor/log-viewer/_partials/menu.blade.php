<div class="relative mb-6 bg-white border-gray-200 rounded-sm border">
    <div class="text-mainAdminTextGrayDark bg-gray-100 border-gray-200 border-b-2 pb-2 px-5 pt-3 relative"><i
            class="fa fa-fw fa-flag"></i> Levels</div>
    <ul class="text-mainAdminGrayDark text-sm">
        @foreach($log->menu() as $level => $item)
        @if ($item['count'] === 0)
        <a href="#" class="list-group-item disabled">
            <span class="badge">
                {{ $item['count'] }}
            </span>
            {!! $item['icon'] !!} {{ $item['name'] }}
        </a>
        @else
        <a href="{{ $item['url'] }}" class="list-group-item {{ $level }}">
            <span class="badge level-{{ $level }}">
                {{ $item['count'] }}
            </span>
            <span class="level level-{{ $level }}">
                {!! $item['icon'] !!} {{ $item['name'] }}
            </span>
        </a>
        @endif
        @endforeach
    </ul>
</div>