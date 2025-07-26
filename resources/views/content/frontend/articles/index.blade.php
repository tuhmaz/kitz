@php
$configData = Helper::appClasses();
use Detection\MobileDetect;
$detect = new MobileDetect;
use Illuminate\Support\Str;
$database = session('database', 'jo');
$subjectData = \App\Models\Subject::on($database)->find($subject->id);

// أيقونات الفئات
$categoryIcons = [
    'plans' => 'ti ti-calendar-event',
    'papers' => 'ti ti-file-text',
    'tests' => 'ti ti-clipboard-check',
    'books' => 'ti ti-book-2',
    'default' => 'ti ti-article'
];

$categoryIcon = $categoryIcons[$category] ?? $categoryIcons['default'];

// أسماء الفئات
$categoryNames = [
    'plans' => __('study_plans'),
    'papers' => __('worksheets'),
    'tests' => __('tests'),
    'books' => __('school_books'),
    'default' => __('articles')
];

$categoryName = $categoryNames[$category] ?? $categoryNames['default'];
@endphp
@vite([
    'resources/assets/vendor/scss/home.scss'
])
@extends('layouts/layoutFront')

@section('title', ($subject->schoolClass->grade_name ?? 'Grade Name') . ' - ' . ($subjectData->subject_name ?? 'Subject Name') . ' - ' . $categoryName . ' - ' . $semester->semester_name)

@section('content')

<!-- Hero Section -->
<section class="position-relative overflow-hidden" style="background: linear-gradient(135deg, #1f36ad 0%, #286aad 50%, #3b82f6 100%); min-height: 60vh; display: flex; align-items: center;">
  <!-- Background Effects -->
  <div class="position-absolute w-100 h-100" style="background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grain" width="100" height="100" patternUnits="userSpaceOnUse"><circle cx="50" cy="50" r="1" fill="%23ffffff" opacity="0.1"/></pattern></defs><rect width="100" height="100" fill="url(%23grain)"/></svg>'); opacity: 0.3;"></div>

  <!-- Floating Elements -->
  <div class="position-absolute" style="top: 10%; right: 10%; width: 100px; height: 100px; background: rgba(255,255,255,0.1); border-radius: 50%; animation: float 6s ease-in-out infinite;"></div>
  <div class="position-absolute" style="bottom: 20%; left: 15%; width: 60px; height: 60px; background: rgba(255,255,255,0.1); border-radius: 50%; animation: float 4s ease-in-out infinite reverse;"></div>

  <div class="container position-relative">
    <div class="row align-items-center">
      <div class="col-lg-8 mx-auto text-center">
        <!-- Category Icon -->
        <div class="mb-4">
          <div class="d-inline-flex align-items-center justify-content-center" style="width: 80px; height: 80px; background: rgba(255,255,255,0.2); backdrop-filter: blur(15px); border: 1px solid rgba(255,255,255,0.3); border-radius: 50%; box-shadow: 0 8px 32px rgba(0,0,0,0.1);">
            <i class="{{ $categoryIcon }} text-white" style="font-size: 2rem;"></i>
          </div>
        </div>

        <!-- Title -->
        <h1 class="display-4 fw-bold text-white mb-3" style="text-shadow: 0 4px 20px rgba(0,0,0,0.3);">
          {{ $categoryName }}
        </h1>

        <!-- Subtitle -->
        <p class="lead text-white mb-4" style="opacity: 0.9; font-size: 1.2rem;">
          {{ $subjectData->subject_name }} - {{ $semester->semester_name }}
        </p>

        <!-- Stats -->
        <div class="text-center p-3" style="background: rgba(255,255,255,0.1); backdrop-filter: blur(15px); border: 1px solid rgba(255,255,255,0.2); border-radius: 1rem; max-width: 400px; margin: 0 auto;">
          <div class="row g-3">
            <div class="col-6">
              <div class="text-center">
                <div class="edu-icon-circle mx-auto mb-2" style="background: linear-gradient(135deg, #10b981, #059669); border: 1px solid rgba(255,255,255,0.3); width: 3rem; height: 3rem;">
                  <i class="ti ti-files text-white" style="font-size: 1.2rem;"></i>
                </div>
                <p class="small mb-1" style="color: rgba(255,255,255,0.9); font-size: 0.75rem;">المحتوى</p>
                <p class="h5 fw-bold mb-0" style="color: white;">{{ $articles->total() }}</p>
              </div>
            </div>
            <div class="col-6">
              <div class="text-center">
                <div class="edu-icon-circle mx-auto mb-2" style="background: linear-gradient(135deg, #f59e0b, #d97706); border: 1px solid rgba(255,255,255,0.3); width: 3rem; height: 3rem;">
                  <i class="ti ti-star text-white" style="font-size: 1.2rem;"></i>
                </div>
                <p class="small mb-1" style="color: rgba(255,255,255,0.9); font-size: 0.75rem;">التقييم</p>
                <p class="h5 fw-bold mb-0" style="color: white;">4.8</p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- Breadcrumb Section -->
<div class="container py-4">
  <nav aria-label="breadcrumb">
    <ol class="breadcrumb mb-0" style="background: none; padding: 0;">
      <li class="breadcrumb-item">
        <a href="{{ route('home') }}" class="text-decoration-none" style="color: #1f36ad;">
          <i class="ti ti-home me-1"></i>{{ __('Home') }}
        </a>
      </li>
      <li class="breadcrumb-item">
        <a href="{{ route('frontend.lesson.index', ['database' => $database]) }}" class="text-decoration-none" style="color: #1f36ad;">
          {{ __('Classes') }}
        </a>
      </li>
      <li class="breadcrumb-item">
        <a href="{{ route('frontend.lesson.show', ['database' => $database ?? session('database', 'jo'), 'id' => $subject->schoolClass->id]) }}" class="text-decoration-none" style="color: #1f36ad;">
          {{ $subject->schoolClass->grade_name }}
        </a>
      </li>
      <li class="breadcrumb-item">
        <a href="{{ route('frontend.subjects.show', ['id' => $subjectData->schoolClass->id, 'subject' => $subjectData->id, 'database' => $database]) }}" class="text-decoration-none" style="color: #1f36ad;">
          {{ $subjectData->subject_name }}
        </a>
      </li>
      <li class="breadcrumb-item active" aria-current="page" style="color: #6c757d;">
        {{ $categoryName }} - {{ $semester->semester_name }}
      </li>
    </ol>
  </nav>
</div>


<!-- Main Content Section -->
<section class="py-5" style="background: #f8fafc;">
  <div class="container">

    <!-- Search and Filter Section -->
    <div class="mb-4">
      <div class="edu-card p-4">
        <form method="GET" action="{{ request()->url() }}" class="row g-3 align-items-end">
          <!-- البحث -->
          <div class="col-md-4">
            <label class="form-label fw-semibold" style="color: #1e293b;">
              <i class="ti ti-search me-1" style="color: #1f36ad;"></i>
              البحث في المحتوى
            </label>
            <input type="text" name="search" class="form-control" 
                   placeholder="ابحث عن عنوان أو محتوى..."
                   value="{{ request('search') }}"
                   style="border: 2px solid #e2e8f0; border-radius: 10px; padding: 0.75rem;">
          </div>
          
          <!-- الترتيب -->
          <div class="col-md-3">
            <label class="form-label fw-semibold" style="color: #1e293b;">
              <i class="ti ti-sort-ascending me-1" style="color: #1f36ad;"></i>
              ترتيب حسب
            </label>
            <select name="sort" class="form-select" style="border: 2px solid #e2e8f0; border-radius: 10px; padding: 0.75rem;">
              <option value="created_at" {{ request('sort') == 'created_at' ? 'selected' : '' }}>تاريخ الإنشاء</option>
              <option value="title" {{ request('sort') == 'title' ? 'selected' : '' }}>العنوان</option>
              <option value="visit_count" {{ request('sort') == 'visit_count' ? 'selected' : '' }}>عدد المشاهدات</option>
            </select>
          </div>
          
          <!-- اتجاه الترتيب -->
          <div class="col-md-2">
            <label class="form-label fw-semibold" style="color: #1e293b;">
              <i class="ti ti-arrows-sort me-1" style="color: #1f36ad;"></i>
              الاتجاه
            </label>
            <select name="order" class="form-select" style="border: 2px solid #e2e8f0; border-radius: 10px; padding: 0.75rem;">
              <option value="desc" {{ request('order') == 'desc' ? 'selected' : '' }}>تنازلي</option>
              <option value="asc" {{ request('order') == 'asc' ? 'selected' : '' }}>تصاعدي</option>
            </select>
          </div>
          
          <!-- عدد النتائج -->
          <div class="col-md-2">
            <label class="form-label fw-semibold" style="color: #1e293b;">
              <i class="ti ti-list-numbers me-1" style="color: #1f36ad;"></i>
              عدد النتائج
            </label>
            <select name="per_page" class="form-select" style="border: 2px solid #e2e8f0; border-radius: 10px; padding: 0.75rem;">
              <option value="10" {{ (request('per_page', 20) == 10) ? 'selected' : '' }}>10</option>
              <option value="20" {{ (request('per_page', 20) == 20) ? 'selected' : '' }}>20</option>
              <option value="50" {{ (request('per_page', 20) == 50) ? 'selected' : '' }}>50</option>
              <option value="100" {{ (request('per_page', 20) == 100) ? 'selected' : '' }}>100</option>
            </select>
          </div>
          
          <!-- أزرار التحكم -->
          <div class="col-md-1">
            <div class="d-flex gap-2">
              <button type="submit" class="btn btn-primary" 
                      style="background: linear-gradient(135deg, #1f36ad, #3b82f6); border: none; border-radius: 10px; padding: 0.75rem;">
                <i class="ti ti-search"></i>
              </button>
              <a href="{{ request()->url() }}" class="btn btn-outline-secondary" 
                 style="border: 2px solid #e2e8f0; border-radius: 10px; padding: 0.75rem;">
                <i class="ti ti-refresh"></i>
              </a>
            </div>
          </div>
        </form>
      </div>
    </div>

    <!-- Results Info -->
    @if(isset($totalCount))
    <div class="mb-4">
      <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
        <div class="d-flex align-items-center gap-3">
          <div class="badge" style="background: linear-gradient(135deg, #10b981, #059669); color: white; font-size: 0.875rem; padding: 0.5rem 1rem; border-radius: 20px;">
            <i class="ti ti-files me-1"></i>
            إجمالي النتائج: {{ number_format($totalCount) }}
          </div>
          @if($totalCount > 0)
          <div class="text-muted">
            <small>عرض {{ number_format($from ?? 0) }} - {{ number_format($to ?? 0) }} من {{ number_format($totalCount) }}</small>
          </div>
          @endif
        </div>
        
        @if($totalCount > 0)
        <div class="text-muted">
          <small>
            <i class="ti ti-file-stack me-1"></i>
            الصفحة {{ number_format($currentPage ?? 1) }} من {{ number_format($lastPage ?? 1) }}
          </small>
        </div>
        @endif
      </div>
    </div>
    @endif

    <!-- Ads Section -->
    @if(config('settings.google_ads_desktop_article') || config('settings.google_ads_mobile_article'))
    <div class="mb-4">
      <div class="edu-card p-3 text-center">
        @if($detect->isMobile())
          {!! config('settings.google_ads_mobile_article') !!}
        @else
          {!! config('settings.google_ads_desktop_article') !!}
        @endif
      </div>
    </div>
    @endif

    <!-- Articles Grid -->
    <div class="row g-4">
      @forelse ($articles as $index => $article)
        @php
          $file = $article->files->first();
          $fileType = $file ? $file->file_type : 'default';

          // تحديد الأيقونة والألوان حسب نوع الملف
          $fileConfig = match($fileType) {
            'pdf' => [
              'icon' => 'ti ti-file-type-pdf',
              'color' => 'linear-gradient(135deg, #dc2626, #b91c1c)',
              'bg' => 'rgba(220, 38, 38, 0.1)',
              'text' => 'PDF'
            ],
            'doc', 'docx' => [
              'icon' => 'ti ti-file-type-doc',
              'color' => 'linear-gradient(135deg, #2563eb, #1d4ed8)',
              'bg' => 'rgba(37, 99, 235, 0.1)',
              'text' => 'Word'
            ],
            'xls', 'xlsx' => [
              'icon' => 'ti ti-file-spreadsheet',
              'color' => 'linear-gradient(135deg, #059669, #047857)',
              'bg' => 'rgba(5, 150, 105, 0.1)',
              'text' => 'Excel'
            ],
            'ppt', 'pptx' => [
              'icon' => 'ti ti-presentation',
              'color' => 'linear-gradient(135deg, #ea580c, #c2410c)',
              'bg' => 'rgba(234, 88, 12, 0.1)',
              'text' => 'PowerPoint'
            ],
            default => [
              'icon' => 'ti ti-file',
              'color' => 'linear-gradient(135deg, #6366f1, #4f46e5)',
              'bg' => 'rgba(99, 102, 241, 0.1)',
              'text' => 'File'
            ]
          };

          $colors = [
            'linear-gradient(135deg, #667eea 0%, #764ba2 100%)',
            'linear-gradient(135deg, #f093fb 0%, #f5576c 100%)',
            'linear-gradient(135deg, #4facfe 0%, #00f2fe 100%)',
            'linear-gradient(135deg, #43e97b 0%, #38f9d7 100%)',
            'linear-gradient(135deg, #fa709a 0%, #fee140 100%)',
            'linear-gradient(135deg, #a8edea 0%, #fed6e3 100%)',
          ];
          $cardColor = $colors[$index % count($colors)];
        @endphp

        <div class="col-lg-6 col-md-6">
          <div class="edu-card h-100 position-relative overflow-hidden" style="border: none; border-radius: 1rem; box-shadow: 0 4px 20px rgba(0,0,0,0.08); transition: all 0.3s ease; cursor: pointer;"
               onmouseover="this.style.transform='translateY(-5px)'; this.style.boxShadow='0 8px 30px rgba(0,0,0,0.12)'"
               onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 20px rgba(0,0,0,0.08)'">

            <!-- Background Pattern -->
            <div class="position-absolute top-0 end-0" style="width: 100px; height: 100px; background: {{ $cardColor }}; opacity: 0.1; border-radius: 0 1rem 0 100%;">
            </div>

            <div class="card-body p-4">
              <div class="d-flex align-items-start">
                <!-- File Type Icon -->
                <div class="me-3 flex-shrink-0">
                  <div class="d-flex align-items-center justify-content-center" style="width: 60px; height: 60px; background: {{ $fileConfig['bg'] }}; border-radius: 12px; border: 2px solid rgba(255,255,255,0.8);">
                    <i class="{{ $fileConfig['icon'] }}" style="font-size: 1.5rem; background: {{ $fileConfig['color'] }}; -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;"></i>
                  </div>
                </div>

                <!-- Content -->
                <div class="flex-grow-1">
                  <h5 class="card-title mb-2" style="color: #1e293b; font-weight: 600; line-height: 1.4;">
                    {{ Str::limit($article->title, 60) }}
                  </h5>

                  <div class="d-flex align-items-center gap-3 mb-3">
                    <span class="badge" style="background: {{ $fileConfig['color'] }}; color: white; font-size: 0.75rem; padding: 0.25rem 0.75rem; border-radius: 20px;">
                      {{ $fileConfig['text'] }}
                    </span>
                    <small class="text-muted">
                      <i class="ti ti-calendar me-1"></i>
                      {{ $article->created_at->format('M d, Y') }}
                    </small>
                  </div>

                  @if($article->visit_count > 0)
                  <div class="d-flex align-items-center text-muted mb-3">
                    <i class="ti ti-eye me-1" style="font-size: 0.875rem;"></i>
                    <small>{{ number_format($article->visit_count) }} مشاهدة</small>
                  </div>
                  @endif

                  <!-- Action Button -->
                  <a href="{{ route('frontend.articles.show', ['article' => $article->id, 'database' => $database]) }}"
                     class="btn btn-sm"
                     style="background: {{ $cardColor }}; color: white; border: none; border-radius: 8px; padding: 0.5rem 1rem; font-weight: 500; text-decoration: none; transition: all 0.2s ease;"
                     onmouseover="this.style.transform='scale(1.05)'"
                     onmouseout="this.style.transform='scale(1)'">
                    <i class="ti ti-arrow-left me-1"></i>
                    عرض المحتوى
                  </a>
                </div>
              </div>
            </div>
          </div>
        </div>

      @empty
        <div class="col-12">
          <div class="text-center py-5">
            <div class="mb-4">
              <i class="ti ti-file-x" style="font-size: 4rem; color: #cbd5e1;"></i>
            </div>
            <h4 class="text-muted mb-2">لا يوجد محتوى متاح</h4>
            <p class="text-muted">لم يتم العثور على أي محتوى في هذا القسم حالياً.</p>
          </div>
        </div>
      @endforelse
    </div>

    <!-- Pagination -->
    @if($articles->hasPages())
    <div class="d-flex justify-content-center mt-5">
      <nav aria-label="Articles pagination">
        {{ $articles->links('components.pagination.custom') }}
      </nav>
    </div>
    @endif


    <!-- Bottom Ads Section -->
    @if(config('settings.google_ads_desktop_article_2') || config('settings.google_ads_mobile_article_2'))
    <div class="mt-5">
      <div class="edu-card p-3 text-center">
        @if($detect->isMobile())
          {!! config('settings.google_ads_mobile_article_2') !!}
        @else
          {!! config('settings.google_ads_desktop_article_2') !!}
        @endif
      </div>
    </div>
    @endif

  </div>
</section>

@endsection
