@if ($paginator->hasPages())
    <div class="pagination-wrapper" style="background: white; border-radius: 15px; padding: 1rem; box-shadow: 0 4px 20px rgba(0,0,0,0.1); border: 1px solid #e2e8f0;">
        <!-- Pagination Info -->
        <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
            <div class="pagination-info">
                <span class="text-muted small">
                    <i class="ti ti-info-circle me-1" style="color: #1f36ad;"></i>
                    عرض {{ $paginator->firstItem() ?? 0 }} - {{ $paginator->lastItem() ?? 0 }} من {{ number_format($paginator->total()) }} نتيجة
                </span>
            </div>
            
            <!-- Quick Jump -->
            @if($paginator->lastPage() > 10)
            <div class="quick-jump d-flex align-items-center gap-2">
                <span class="text-muted small">الانتقال إلى:</span>
                <input type="number" 
                       class="form-control form-control-sm" 
                       style="width: 80px; border: 2px solid #e2e8f0; border-radius: 8px;" 
                       min="1" 
                       max="{{ $paginator->lastPage() }}" 
                       value="{{ $paginator->currentPage() }}"
                       onchange="window.location.href = '{{ $paginator->url(1) }}'.replace('page=1', 'page=' + this.value)">
                <span class="text-muted small">من {{ number_format($paginator->lastPage()) }}</span>
            </div>
            @endif
        </div>
        
        <!-- Navigation -->
        <nav aria-label="Page navigation">
            <ul class="pagination justify-content-center mb-0" style="gap: 0.25rem;">
                {{-- First Page Link --}}
                @if (!$paginator->onFirstPage())
                    <li class="page-item first">
                        <a class="page-link" href="{{ $paginator->url(1) }}" aria-label="First" 
                           style="border: 2px solid #e2e8f0; border-radius: 8px; color: #1f36ad; font-weight: 500;" 
                           title="الصفحة الأولى">
                            <i class="ti ti-chevrons-left ti-sm"></i>
                        </a>
                    </li>
                @endif

                {{-- Previous Page Link --}}
                @if ($paginator->onFirstPage())
                    <li class="page-item prev disabled" aria-disabled="true">
                        <span class="page-link" style="border: 2px solid #f1f5f9; border-radius: 8px; color: #94a3b8; background: #f8fafc;">
                            <i class="ti ti-chevron-left ti-sm"></i>
                        </span>
                    </li>
                @else
                    <li class="page-item prev">
                        <a class="page-link" href="{{ $paginator->previousPageUrl() }}" rel="prev" aria-label="Previous" 
                           style="border: 2px solid #e2e8f0; border-radius: 8px; color: #1f36ad; font-weight: 500;" 
                           title="الصفحة السابقة">
                            <i class="ti ti-chevron-left ti-sm"></i>
                        </a>
                    </li>
                @endif

                {{-- Pagination Elements --}}
                @foreach ($elements as $element)
                    {{-- "Three Dots" Separator --}}
                    @if (is_string($element))
                        <li class="page-item disabled" aria-disabled="true">
                            <span class="page-link" style="border: none; color: #64748b; background: transparent;">{{ $element }}</span>
                        </li>
                    @endif

                    {{-- Array Of Links --}}
                    @if (is_array($element))
                        @foreach ($element as $page => $url)
                            @if ($page == $paginator->currentPage())
                                <li class="page-item active" aria-current="page">
                                    <span class="page-link" 
                                          style="background: linear-gradient(135deg, #1f36ad, #3b82f6); border: 2px solid #1f36ad; border-radius: 8px; color: white; font-weight: 600; box-shadow: 0 2px 8px rgba(31, 54, 173, 0.3);">
                                        {{ $page }}
                                    </span>
                                </li>
                            @else
                                <li class="page-item">
                                    <a class="page-link" href="{{ $url }}" 
                                       style="border: 2px solid #e2e8f0; border-radius: 8px; color: #1f36ad; font-weight: 500; transition: all 0.2s ease;" 
                                       onmouseover="this.style.background='#f1f5f9'; this.style.borderColor='#1f36ad'" 
                                       onmouseout="this.style.background='white'; this.style.borderColor='#e2e8f0'">
                                        {{ $page }}
                                    </a>
                                </li>
                            @endif
                        @endforeach
                    @endif
                @endforeach

                {{-- Next Page Link --}}
                @if ($paginator->hasMorePages())
                    <li class="page-item next">
                        <a class="page-link" href="{{ $paginator->nextPageUrl() }}" rel="next" aria-label="Next" 
                           style="border: 2px solid #e2e8f0; border-radius: 8px; color: #1f36ad; font-weight: 500;" 
                           title="الصفحة التالية">
                            <i class="ti ti-chevron-right ti-sm"></i>
                        </a>
                    </li>
                @else
                    <li class="page-item next disabled" aria-disabled="true">
                        <span class="page-link" style="border: 2px solid #f1f5f9; border-radius: 8px; color: #94a3b8; background: #f8fafc;">
                            <i class="ti ti-chevron-right ti-sm"></i>
                        </span>
                    </li>
                @endif

                {{-- Last Page Link --}}
                @if ($paginator->hasMorePages())
                    <li class="page-item last">
                        <a class="page-link" href="{{ $paginator->url($paginator->lastPage()) }}" aria-label="Last" 
                           style="border: 2px solid #e2e8f0; border-radius: 8px; color: #1f36ad; font-weight: 500;" 
                           title="الصفحة الأخيرة">
                            <i class="ti ti-chevrons-right ti-sm"></i>
                        </a>
                    </li>
                @endif
            </ul>
        </nav>
        
        <!-- Page Size Options (for large datasets) -->
        @if($paginator->total() > 50)
        <div class="d-flex justify-content-center mt-3">
            <div class="btn-group" role="group" aria-label="Page size options">
                @foreach([10, 20, 50, 100] as $size)
                    @php
                        $currentPerPage = request('per_page', 20);
                        $isActive = $currentPerPage == $size;
                    @endphp
                    <a href="{{ request()->fullUrlWithQuery(['per_page' => $size, 'page' => 1]) }}" 
                       class="btn btn-sm {{ $isActive ? 'btn-primary' : 'btn-outline-primary' }}" 
                       style="border-radius: 6px; font-size: 0.75rem; padding: 0.375rem 0.75rem; {{ $isActive ? 'background: linear-gradient(135deg, #1f36ad, #3b82f6); border-color: #1f36ad;' : 'color: #1f36ad; border-color: #1f36ad;' }}">
                        {{ $size }}
                    </a>
                @endforeach
            </div>
        </div>
        @endif
    </div>
@endif
