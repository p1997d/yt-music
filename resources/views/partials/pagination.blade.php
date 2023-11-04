@if ($paginator->hasPages())
    <div class="m-3 py-3 z-0">
        <nav>
            <ul class="pagination justify-content-center">
                @if ($paginator->onFirstPage())
                    <li>
                        <span class="page-item page-link item rounded mx-1 disabled"><i class="bi bi-chevron-left"></i></span>
                    </li>
                @else
                    <li>
                        <a class="page-item page-link item rounded mx-1" href="{{ $paginator->previousPageUrl() }}">
                            <i class="bi bi-chevron-left"></i>
                        </a>
                    </li>
                @endif

                @foreach ($elements as $element)
                    @if (is_string($element))
                        <li>
                            <span class="page-item page-link item rounded mx-1 disabled">{{ $element }}</span>
                        </li>
                    @endif

                    @if (is_array($element))
                        @foreach ($element as $page => $url)
                            @if ($page == $paginator->currentPage())
                                <li>
                                    <span class="page-item page-link item rounded mx-1 active">{{ $page }}</span>
                                </li>
                            @else
                                <li>
                                    <a class="page-item page-link item rounded mx-1" href="{{ $url }}">{{ $page }}</a>
                                </li>
                            @endif
                        @endforeach
                    @endif
                @endforeach

                @if ($paginator->hasMorePages())
                    <li>
                        <a class="page-item page-link item rounded mx-1" href="{{ $paginator->nextPageUrl() }}">
                            <i class="bi bi-chevron-right"></i>
                        </a>
                    </li>
                @else
                    <li>
                        <span class="page-item page-link item rounded mx-1 disabled"><i class="bi bi-chevron-right"></i></span>
                    </li>
                @endif
            </ul>
        </nav>
    </div>
@endif
