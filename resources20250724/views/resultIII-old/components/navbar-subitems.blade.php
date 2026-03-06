<div class="dropdown-submenu">
    @foreach ($items as $item)
        @if($item->link)
            <a@lm-attrs($item->link) class="dropdown-item" @lm-endattrs href="{!! $item->url() !!}">{!! $item->title !!}</a>
        @else
            {!! $item->title !!}
        @endif

        @if($item->hasChildren())
            @include('components.navbar-subitems', ['items' => $item->children()])
        @endif
    @endforeach
</div>