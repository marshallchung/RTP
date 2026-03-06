<nav class="flex flex-row justify-center w-full pt-2">
    <ul class="flex flex-row border rounded h-11">
        <li class="{{ $paginator->currentPage() !== 1 ?: 'disabled' }} page-item">
            <a class="flex items-center justify-center w-20 h-full border-r hover:bg-gray-100"
                href="{{ $paginator->url($paginator->currentPage() - 1) }}">上一頁</a>
        </li>
        <li class="{{ $paginator->hasMorePages() ?: 'disabled' }} page-item">
            <a class="flex items-center justify-center w-20 h-full hover:bg-gray-100"
                href="{{ $paginator->nextPageUrl() }}">下一頁</a>
        </li>
    </ul>
</nav>