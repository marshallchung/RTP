<ul class="flex flex-row items-center justify-center space-x-2 text-xs text-mainTextGray">
    <li
        class="px-2 py-1 border rounded-full {{ $paginator->currentPage() !== 1 ?'cursor-pointer': 'cursor-not-allowed' }}">
        <a href="{{ $paginator->appends(request()->all())->previousPageUrl() }}"
            class="{{ $paginator->currentPage() !== 1 ?'pointer-events-auto': 'pointer-events-none' }}">← 上一頁</a>
    </li>
    <li class="px-2 py-1 border rounded-full {{ $paginator->hasMorePages() ?'cursor-pointer': 'cursor-not-allowed' }}">
        <a href="{{ $paginator->appends(request()->all())->nextPageUrl() }}"
            class="{{ $paginator->hasMorePages() ?'cursor-pointer': 'cursor-not-allowed' }}">下一頁 →</a>
    </li>
</ul>