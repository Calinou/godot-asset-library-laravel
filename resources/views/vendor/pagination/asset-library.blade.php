{{-- Paginator theme using Tailwind CSS --}}

@if ($paginator->hasPages())
<nav class="text-center my-8">
  <ul class="inline-flex">
    {{-- "Previous page" link --}}
    @if ($paginator->onFirstPage())
    <li aria-disabled="true">
      <span class="pagination-item pagination-item-disabled rounded-l border-0">
        @lang('pagination.previous')
      </span>
    </li>
    @else
    <li>
      <a href="{{ url($paginator->previousPageUrl()) }}" rel="prev" class="pagination-item rounded-l border-0">
        @lang('pagination.previous')
      </a>
    </li>
    @endif

    {{-- Pagination elements --}}
    @foreach ($elements as $element)
    {{-- "Three dots" separator --}}
    @if (is_string($element))
    <li aria-disabled="true">
      <span class="pagination-item pagination-item-disabled">
        {{ $element }}
      </span>
    </li>
    @endif

    {{-- Array of links --}}
    @if (is_array($element))
    @foreach ($element as $page => $url)
    @if ($page == $paginator->currentPage())
    <li aria-current="page">
      <span class="pagination-item pagination-item-active">
        {{ $page }}
      </span>
    </li>
    @else
    <li>
      <a href="{{ url($url) }}" class="pagination-item">
        {{ $page }}
      </a>
    </li>
    @endif
    @endforeach
    @endif
    @endforeach

    {{-- "Next page" link --}}
    @if ($paginator->hasMorePages())
    <li>
      <a href="{{ url($paginator->nextPageUrl()) }}" rel="next" class="pagination-item rounded-r">
        @lang('pagination.next')
      </a>
    </li>
    @else
    <li aria-disabled="true">
      <span class="pagination-item pagination-item-disabled rounded-r">
        @lang('pagination.next')
      </span>
    </li>
    @endif
  </ul>
</nav>
@endif
