<div class="bg-white border-[#e9e9e9] border-b py-6 px-[18px] flex flex-row justify-between items-center w-full">
    @if(isset($heading))
    <h3 class="text-[#666] font-light text-header whitespace-nowrap">{{ $heading }}</h3>
    @endif
    @if(isset($header_btn))
    <div class="flex flex-row flex-wrap items-center justify-end">
        @for($i = 0; $i < count($header_btn); $i+=2) <a href="{{ $header_btn[$i+1] }}"
            class="flex items-center justify-center h-10 px-4 m-1 text-sm text-white rounded bg-mainCyanDark hover:bg-teal-400">
            {{
            $header_btn[$i] }}</a>
            @endfor
    </div>
    @endif
</div>
@if(isset($breadcrumbs))
<ul class="mt-[18px] py-2 px-4 list-none rounded-sm text-sm text-mainTextGray flex flex-row items-center space-x-8">
    @foreach($breadcrumbs as $index => $breadcrumb)
    <li
        class="before:text-xs before:content-['>'] before:font-extrabold before:text-mainTextGray before:w-4 before:h-4 relative before:absolute before:-left-[1.125rem] before:top-1.5 first:before:content-['']">
        @if(is_array($breadcrumb))
        <a href="{{ $breadcrumb[1] }}">{{ $breadcrumb[0] }}</a>
        @else
        {{ $breadcrumb }}
        @endif
    </li>
    @endforeach
</ul>
@endif