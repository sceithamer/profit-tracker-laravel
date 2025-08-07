{{-- 
Modern Pagination Component

Usage:
@include('components.modern-pagination', [
    'paginator' => $sales,
    'showPerPage' => true,  // optional, defaults to true
    'perPage' => $perPage   // required if showPerPage is true
])
--}}

@if ($paginator->hasPages())
    @php
        $showPerPage = $showPerPage ?? true;
        $currentPage = $paginator->currentPage();
        $lastPage = $paginator->lastPage();
        $start = max(1, $currentPage - 2);
        $end = min($lastPage, $currentPage + 2);
        
        // Adjust window if at beginning or end
        if ($currentPage <= 3) {
            $end = min($lastPage, 5);
        }
        if ($currentPage > $lastPage - 3) {
            $start = max(1, $lastPage - 4);
        }
    @endphp

    <nav class="modern-pagination" aria-label="Pagination Navigation" role="navigation">
        <!-- Results Info -->
        <div class="pagination-info" aria-live="polite">
            Showing {{ $paginator->firstItem() ?? 0 }}-{{ $paginator->lastItem() ?? 0 }} of {{ $paginator->total() }} results
        </div>

        <!-- Pagination Controls -->
        <div class="pagination-controls" role="group" aria-label="Pagination controls">
            <!-- Previous Button -->
            @if ($paginator->onFirstPage())
                <span class="pagination-nav-btn disabled" aria-disabled="true">← Previous</span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}" 
                   class="pagination-nav-btn" 
                   aria-label="Go to previous page">← Previous</a>
            @endif

            <!-- Page Numbers -->
            <div class="pagination-numbers" role="group" aria-label="Page numbers">
                {{-- First Page --}}
                @if ($start > 1)
                    <a href="{{ $paginator->url(1) }}" 
                       class="pagination-page {{ $currentPage === 1 ? 'active' : '' }}"
                       aria-label="{{ $currentPage === 1 ? 'Current page, page 1' : 'Go to page 1' }}"
                       {{ $currentPage === 1 ? 'aria-current="page"' : '' }}>1</a>
                    @if ($start > 2)
                        <span class="pagination-ellipsis" aria-hidden="true">...</span>
                    @endif
                @endif

                {{-- Page Window --}}
                @for ($page = $start; $page <= $end; $page++)
                    <a href="{{ $paginator->url($page) }}" 
                       class="pagination-page {{ $currentPage === $page ? 'active' : '' }}"
                       aria-label="{{ $currentPage === $page ? 'Current page, page ' . $page : 'Go to page ' . $page }}"
                       {{ $currentPage === $page ? 'aria-current="page"' : '' }}>{{ $page }}</a>
                @endfor

                {{-- Last Page --}}
                @if ($end < $lastPage)
                    @if ($end < $lastPage - 1)
                        <span class="pagination-ellipsis" aria-hidden="true">...</span>
                    @endif
                    <a href="{{ $paginator->url($lastPage) }}" 
                       class="pagination-page {{ $currentPage === $lastPage ? 'active' : '' }}"
                       aria-label="{{ $currentPage === $lastPage ? 'Current page, page ' . $lastPage : 'Go to page ' . $lastPage }}"
                       {{ $currentPage === $lastPage ? 'aria-current="page"' : '' }}>{{ $lastPage }}</a>
                @endif
            </div>

            <!-- Next Button -->
            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}" 
                   class="pagination-nav-btn" 
                   aria-label="Go to next page">Next →</a>
            @else
                <span class="pagination-nav-btn disabled" aria-disabled="true">Next →</span>
            @endif
        </div>

        <!-- Per Page Selector -->
        @if($showPerPage)
            <div class="pagination-per-page">
                <label for="per_page_{{ uniqid() }}">Items per page:</label>
                <select id="per_page_{{ uniqid() }}" 
                        onchange="changePerPage(this.value)"
                        aria-label="Select number of items to display per page">
                    <option value="10" {{ $perPage == 10 ? 'selected' : '' }}>10</option>
                    <option value="25" {{ $perPage == 25 ? 'selected' : '' }}>25</option>
                    <option value="50" {{ $perPage == 50 ? 'selected' : '' }}>50</option>
                    <option value="100" {{ $perPage == 100 ? 'selected' : '' }}>100</option>
                </select>
            </div>
        @endif
    </nav>

@endif