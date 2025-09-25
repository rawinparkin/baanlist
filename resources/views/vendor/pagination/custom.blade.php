@if ($paginator->hasPages())


<ul>
    {{-- Previous Page Link --}}
    @if (!$paginator->onFirstPage())
    <li>
        <a href="{{ $paginator->previousPageUrl() }}" rel="prev" aria-label="@lang('pagination.previous')"><i
                class="sl sl-icon-arrow-left"></i></a>
    </li>


    @endif

    {{-- Pagination Elements --}}
    @foreach ($elements as $element)
    {{-- "Three Dots" Separator --}}
    @if (is_string($element))
    <li class="disabled" aria-disabled="true"><span>{{ $element }}</span></li>
    @endif

    {{-- Array Of Links --}}
    @if (is_array($element))
    @foreach ($element as $page => $url)
    @if ($page == $paginator->currentPage())
    <li class="current-page active" aria-current="page"><a href="{{$url}}" class="current-page">{{$page}}</a></li>
    @else
    <li><a href="{{ $url }}">{{ $page }}</a></li>
    @endif
    @endforeach
    @endif
    @endforeach

    {{-- Next Page Link --}}
    @if ($paginator->hasMorePages())
    <li>
        <a href="{{ $paginator->nextPageUrl() }}" rel="next" aria-label="@lang('pagination.next')"><i
                class="sl sl-icon-arrow-right"></i></a>
    </li>
    @else

    @endif







</ul>


@endif