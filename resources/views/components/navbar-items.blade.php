@foreach($items as $itemIndex=>$item)
<li class="relative flex flex-col items-start justifi-start w-fit min-w-[14rem] sm:min-w-0 hover:bg-mainGrayDark sm:hover:bg-transparent sm:hover:text-mainYellow"
    :class="{'flex sm:hidden':('{!! $item->title !!}'=='業務人員版' || '{!! $item->title !!}'=='韌性社區登出' || '{!! $item->title !!}'=='韌性社區登入'),'flex':!('{!! $item->title !!}'=='業務人員版' || '{!! $item->title !!}'=='韌性社區登出' || '{!! $item->title !!}'=='韌性社區登入')}">
    @if($item->link)
    @if($item->hasChildren())
    <button type="button"
        class="w-full px-6 py-3 text-white transition-all duration-300 border-t border-r border-white sm:w-auto sm:border-t-0 sm:border-r-0 whitespace-nowrap hover:no-underline last:border-b sm:last:border-b-0"
        :class="{'bg-mainGrayDark sm:bg-transparent sm:hover:text-mainYellow border-l sm:border-l-0':showMenu==={{ $itemIndex }},'bg-transparent sm:hover:text-mainYellow border-l sm:border-l-0':showMenu!=={{ $itemIndex }}}"
        @click="showMenu==={{ $itemIndex }}?showMenu=null:showMenu={{ $itemIndex }}"
        @click.outside="showMenu==={{ $itemIndex }}">
        {!! $item->title !!}
    </button>
    <div :class="{'flex':showMenu==={{ $itemIndex }},'hidden sm:flex':showMenu!=={{ $itemIndex }}}"
        class="flex-col hidden border-white border-y sm:border-y-0 whitespace-nowrap max-w-[100%] w-fit min-w-[14rem] sm:min-w-0 max-h-[60vh] overflow-auto text-white z-10 sm:text-base left-auto right-0 bg-mainGrayDark sm:bg-transparent">
        <div class="flex-col w-full">
            @foreach($item->children() as $subIndex=>$subItem)
            @if($subItem->link)
            @if($subItem->hasChildren())
            <button @click="showSubMenu=showSubMenu==={{ $subIndex }}?showSubMenu=null:showSubMenu={{ $subIndex }}"
                class="flex flex-row items-center justify-start w-full px-6 py-3 space-x-2 text-white transition-all duration-300 border-l border-r border-white sm:w-auto hover:text-mainYellow sm:border-l-0 sm:border-r-0 whitespace-nowrap sm:whitespace-normal hover:no-underline sm:first:border-r-0">
                <span class="text-left">{!! $subItem->title !!}</span>
                <i class="w-2 h-2 transition-all duration-200 i-fa6-solid-angle-right sm:hidden"
                    :class="{'rotate-90':showSubMenu==={{ $subIndex }},'rotate-0':showSubMenu!=={{ $subIndex }}}"></i>
            </button>
            @else
            <a class="flex flex-row items-center justify-start w-full px-6 py-3 space-x-2 text-white transition-all duration-300 border-l border-r border-white sm:w-auto sm:border-l-0 sm:border-r-0 whitespace-nowrap sm:whitespace-normal hover:text-mainYellow hover:no-underline sm:first:border-r-0"
                href="{!! $subItem->url() !!}">
                <span class="text-left">{!! $subItem->title !!}</span>
            </a>
            @endif
            @else
            {!! $subItem->title !!}
            @endif

            @if($subItem->hasChildren())
            <div :class="{'block':showSubMenu==={{ $subIndex }},'hidden sm:bl0ck':showSubMenu!=={{ $subIndex }}}">
                @include('components.navbar-subitems', ['items' => $subItem->children()])
            </div>
            @endif

            @if($subItem->divider)
            <div class="dropdown-divider"></div>
            @endif
            @endforeach
        </div>
    </div>
    @else
    <a class="w-full px-6 py-3 text-center text-white bg-transparent border border-b-0 border-l border-white sm:w-auto sm:border-0 sm:border-l-0 whitespace-nowrap hover:no-underline last:border-b sm:last:border-b-0 hover:bg-mainGrayDark hover:text-white sm:hover:bg-transparent sm:hover:text-mainYellow"
        href="{!! $item->url() !!}">
        {!! $item->title !!}
    </a>
    @endif
    @else
    {!! $item->title !!}
    @endif
</li>
{{--@if($item->divider)--}}
{{--<li{!! Lavary\Menu\Builder::attributes($item->divider) !!}></li>--}}
    {{--@endif--}}
    @endforeach