@if ($paginator->hasPages())
<nav role="navigation" aria-label="{{ __('Pagination Navigation') }}" class="flex items-center justify-between">
    <div class="flex justify-between flex-1 sm:hidden">
        @if ($paginator->onFirstPage())
        <span
            class="relative inline-flex items-center px-4 py-2 text-sm font-medium leading-5 bg-gray-100 border border-gray-300 rounded-md cursor-default text-mainTextGray">
            {!! __('pagination.previous') !!}
        </span>
        @else
        <button type="button" @click="getData({{ $paginator->currentPage()-1 }})"
            class="relative inline-flex items-center px-4 py-2 text-sm font-medium leading-5 transition duration-150 ease-in-out bg-white border border-gray-300 rounded-md text-mainBlue hover:text-white hover:bg-mainBlue focus:outline-none focus:ring ring-gray-300 focus:border-blue-300 active:bg-gray-100 active:text-mainBlueDark">
            {!! __('pagination.previous') !!}
        </button>
        @endif

        @if ($paginator->hasMorePages())
        <button type="button" @click="getData({{ $paginator->currentPage()+1 }})"
            class="relative inline-flex items-center px-4 py-2 ml-3 text-sm font-medium leading-5 transition duration-150 ease-in-out bg-white border border-gray-300 rounded-md text-mainBlue hover:text-white hover:bg-mainBlue focus:outline-none focus:ring ring-gray-300 focus:border-blue-300 active:bg-gray-100 active:text-mainBlueDark">
            {!! __('pagination.next') !!}
        </button>
        @else
        <span
            class="relative inline-flex items-center px-4 py-2 ml-3 text-sm font-medium leading-5 bg-gray-100 border border-gray-300 rounded-md cursor-default text-mainTextGray">
            {!! __('pagination.next') !!}
        </span>
        @endif
    </div>

    <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">

        <div>
            <span class="relative z-0 inline-flex rounded-md shadow-sm">
                {{-- Previous Page Link --}}
                @if ($paginator->onFirstPage())
                <span aria-disabled="true" aria-label="{{ __('pagination.previous') }}">
                    <span
                        class="relative inline-flex items-center px-2 py-2 text-sm font-medium leading-5 bg-gray-100 border border-gray-300 cursor-default text-mainTextGray rounded-l-md"
                        aria-hidden="true">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z"
                                clip-rule="evenodd" />
                        </svg>
                    </span>
                </span>
                @else
                <button type="button" @click="getData({{ $paginator->currentPage()-1 }})" rel="prev"
                    class="relative inline-flex items-center px-2 py-2 text-sm font-medium leading-5 transition duration-150 ease-in-out bg-white border border-gray-300 text-mainBlueDark rounded-l-md hover:text-white hover:bg-mainBlue focus:z-10 focus:outline-none focus:ring ring-gray-300 focus:border-blue-300 active:bg-gray-100 active:text-gray-500"
                    aria-label="{{ __('pagination.previous') }}">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z"
                            clip-rule="evenodd" />
                    </svg>
                </button>
                @endif

                {{-- Pagination Elements --}}
                @foreach ($elements as $element)
                {{-- "Three Dots" Separator --}}
                @if (is_string($element))
                <span aria-disabled="true">
                    <span
                        class="relative inline-flex items-center px-4 py-2 -ml-px text-sm font-medium leading-5 bg-gray-200 border border-gray-300 cursor-default text-mainTextGray">{{
                        $element }}</span>
                </span>
                @endif

                {{-- Array Of Links --}}
                @if (is_array($element))
                @foreach ($element as $page => $url)
                @if ($page == $paginator->currentPage())
                <span aria-current="page">
                    <span
                        class="relative inline-flex items-center px-4 py-2 -ml-px text-sm font-medium leading-5 text-white border border-gray-300 cursor-default bg-mainBlue">{{
                        $page }}</span>
                </span>
                @else
                <button type="button" @click="getData({{ $page }})"
                    class="relative inline-flex items-center px-4 py-2 -ml-px text-sm font-medium leading-5 transition duration-150 ease-in-out bg-white border border-gray-300 hover:bg-mainBlue hover:text-white text-mainBlueDark focus:z-10 focus:outline-none focus:ring ring-gray-300 focus:border-blue-300 active:bg-gray-100 active:text-mainBlueDark"
                    aria-label="{{ __('Go to page :page', ['page' => $page]) }}">
                    {{ $page }}
                </button>
                @endif
                @endforeach
                @endif
                @endforeach

                {{-- Next Page Link --}}
                @if ($paginator->hasMorePages())
                <button type="button" @click="getData({{ $paginator->currentPage()+1 }})" rel="next"
                    class="relative inline-flex items-center px-2 py-2 -ml-px text-sm font-medium leading-5 transition duration-150 ease-in-out bg-white border border-gray-300 text-mainBlueDark rounded-r-md hover:bg-mainBlue hover:text-white focus:z-10 focus:outline-none focus:ring ring-gray-300 focus:border-blue-300 active:bg-gray-100 active:text-gray-500"
                    aria-label="{{ __('pagination.next') }}">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                            clip-rule="evenodd" />
                    </svg>
                </button>
                @else
                <span aria-disabled="true" aria-label="{{ __('pagination.next') }}">
                    <span
                        class="relative inline-flex items-center px-2 py-2 -ml-px text-sm font-medium leading-5 bg-gray-100 border border-gray-300 cursor-default text-mainTextGray rounded-r-md"
                        aria-hidden="true">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                                clip-rule="evenodd" />
                        </svg>
                    </span>
                </span>
                @endif
            </span>
        </div>
    </div>
</nav>
@endif