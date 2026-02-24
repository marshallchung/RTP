<div class="flex flex-col items-start justify-center w-full text-lg whitespace-nowrap max-w-[100vw]">
    @foreach ($items as $item)
    @if($item->link)
    <a class="pr-6 pl-[4.5rem] sm:pl-6 sm:text-sm py-2 text-white sm:text-left transition-colors w-full duration-300 border-l border-r sm:border-l-0 sm:border-r-0 border-white hover:text-mainYellow hover:no-underline whitespace-nowrapm sm:whitespace-normal"
        href="{!! $item->url() !!}">{!! $item->title
        !!}</a>
    @else
    <div class="px-6 py-2 sm:text-left">
        {!! $item->title !!}
    </div>
    @endif

    @if($item->hasChildren())
    @include('components.navbar-subitems', ['items' => $item->children()])
    @endif
    @endforeach
</div>