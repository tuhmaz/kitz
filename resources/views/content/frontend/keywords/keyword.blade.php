{{-- resources/views/frontend/keyword/keyword.blade.php --}}
@extends('layouts/layoutFront')

@php
$configData = Helper::appClasses();
$pageTitle = __('content_related_to', ['keyword' => $keyword->keyword]);
use Illuminate\Support\Str;
use Detection\MobileDetect;
$detect = new MobileDetect;
@endphp

@section('title', $pageTitle)

@section('page-style')
@vite([
  'resources/assets/vendor/scss/pages/front-page-help-center.scss',
  'resources/assets/vendor/scss/home.scss'
])

@endsection

@section('meta')
<meta name="keywords" content="{{ $keyword->keyword }}">

<meta name="description" content="{{ __('find articles news related to', ['keyword' => $keyword->keyword]) }}">

<link rel="canonical" href="{{ url()->current() }}">

<meta property="og:title" content="{{ __('content_related_to', ['keyword' => $keyword->keyword]) }}" />
<meta property="og:description" content="{{ __('find articles news related to', ['keyword' => $keyword->keyword]) }}" />
<meta property="og:url" content="{{ url()->current() }}" />
<meta property="og:image" content="{{ $articles->first()->image_url ?? asset('assets/img/front-pages/icons/articles_default_image.jpg') }}" />

<meta name="twitter:card" content="summary_large_image" />
<meta name="twitter:title" content="{{ __('content_related_to', ['keyword' => $keyword->keyword]) }}" />
<meta name="twitter:description" content="{{ __('find articles news related to', ['keyword' => $keyword->keyword]) }}" />
<meta name="twitter:image" content="{{ $articles->first()->image_url ?? asset('assets/img/front-pages/icons/articles_default_image.jpg') }}" />
@endsection

@section('content')

<!-- Hero Section -->
<div class="edu-gradient-bg" style="padding-top: 80px;">
  <section class="edu-welcome-section">
    <div class="container">
      <div class="edu-welcome-content">
        <div class="row align-items-center justify-content-center">
          <div class="col-lg-8 text-center">
            <!-- Keyword Icon -->
            <div class="edu-icon-circle mx-auto mb-4" style="background: rgba(255,255,255,0.2); backdrop-filter: blur(15px); border: 1px solid rgba(255,255,255,0.3); width: 5rem; height: 5rem;">
              <i class="ti ti-tag text-white" style="font-size: 2rem;"></i>
            </div>

            <h1 class="display-5 fw-bold mb-4" style="color: white; line-height: 1.2;">
              {{ __('content_related_to') }}
            </h1>
            <div class="badge bg-light text-primary fs-5 px-4 py-2 rounded-pill mb-4">
              <i class="ti ti-hash me-2"></i>{{ $keyword->keyword }}
            </div>
            <p class="lead mb-4" style="color: rgba(255, 255, 255, 0.9); font-size: 1.1rem;">
              {{ __('find articles news related to', ['keyword' => $keyword->keyword]) }}
            </p>

            <!-- Statistics -->
            <div class="row g-3 justify-content-center">
              <div class="col-md-4">
                <div class="text-center p-3" style="background: rgba(255,255,255,0.1); backdrop-filter: blur(15px); border: 1px solid rgba(255,255,255,0.2); border-radius: 1rem;">
                  <div class="edu-icon-circle mx-auto mb-2" style="background: rgba(255,255,255,0.2); backdrop-filter: blur(10px); border: 1px solid rgba(255,255,255,0.3); width: 3rem; height: 3rem;">
                    <i class="ti ti-article text-white" style="font-size: 1.2rem;"></i>
                  </div>
                  <p class="small mb-1" style="color: rgba(255,255,255,0.9); font-size: 0.75rem;">المقالات</p>
                  <p class="h5 fw-bold mb-0" style="color: white;">{{ $articles->count() }}</p>
                </div>
              </div>
              <div class="col-md-4">
                <div class="text-center p-3" style="background: rgba(255,255,255,0.1); backdrop-filter: blur(15px); border: 1px solid rgba(255,255,255,0.2); border-radius: 1rem;">
                  <div class="edu-icon-circle mx-auto mb-2" style="background: rgba(255,255,255,0.2); backdrop-filter: blur(10px); border: 1px solid rgba(255,255,255,0.3); width: 3rem; height: 3rem;">
                    <i class="ti ti-news text-white" style="font-size: 1.2rem;"></i>
                  </div>
                  <p class="small mb-1" style="color: rgba(255,255,255,0.9); font-size: 0.75rem;">الأخبار</p>
                  <p class="h5 fw-bold mb-0" style="color: white;">{{ $news->count() }}</p>
                </div>
              </div>
              <div class="col-md-4">
                <div class="text-center p-3" style="background: rgba(255,255,255,0.1); backdrop-filter: blur(15px); border: 1px solid rgba(255,255,255,0.2); border-radius: 1rem;">
                  <div class="edu-icon-circle mx-auto mb-2" style="background: rgba(255,255,255,0.2); backdrop-filter: blur(10px); border: 1px solid rgba(255,255,255,0.3); width: 3rem; height: 3rem;">
                    <i class="ti ti-eye text-white" style="font-size: 1.2rem;"></i>
                  </div>
                  <p class="small mb-1" style="color: rgba(255,255,255,0.9); font-size: 0.75rem;">إجمالي المحتوى</p>
                  <p class="h5 fw-bold mb-0" style="color: white;">{{ $articles->count() + $news->count() }}</p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Floating Elements -->
    <div class="floating-element" style="position: absolute; top: 20%; left: 10%; width: 60px; height: 60px; background: linear-gradient(135deg, #f39c12, #e67e22); border-radius: 50%; display: flex; align-items: center; justify-content: center; animation: float 6s ease-in-out infinite;">
      <i class="ti ti-tag text-white" style="font-size: 1.5rem;"></i>
    </div>
    <div class="floating-element" style="position: absolute; top: 30%; right: 15%; width: 50px; height: 50px; background: linear-gradient(135deg, #e74c3c, #c0392b); border-radius: 50%; display: flex; align-items: center; justify-content: center; animation: float 8s ease-in-out infinite reverse;">
      <i class="ti ti-search text-white" style="font-size: 1.2rem;"></i>
    </div>
    <div class="floating-element" style="position: absolute; bottom: 20%; left: 20%; width: 45px; height: 45px; background: linear-gradient(135deg, #9b59b6, #8e44ad); border-radius: 50%; display: flex; align-items: center; justify-content: center; animation: float 7s ease-in-out infinite;">
      <i class="ti ti-hash text-white" style="font-size: 1rem;"></i>
    </div>
  </section>
</div>

<!-- Breadcrumb -->
<div class="container px-4 mt-4">
  <nav aria-label="breadcrumb">
    <ol class="breadcrumb" style="background: none; padding: 0; margin: 0;">
      <li class="breadcrumb-item">
        <a href="{{ route('home') }}" class="text-decoration-none" style="color: var(--edu-primary);">
          <i class="ti ti-home me-1"></i>{{ __('home') }}
        </a>
      </li>
      <li class="breadcrumb-item">
        <a href="{{ route('frontend.keywords.index', ['database' => $database ?? session('database', 'jo')]) }}" class="text-decoration-none" style="color: var(--edu-primary);">
          <i class="ti ti-tags me-1"></i>{{ __('keywords') }}
        </a>
      </li>
      <li class="breadcrumb-item active" aria-current="page" style="color: var(--edu-dark);">
        <i class="ti ti-hash me-1"></i>{{ $keyword->keyword }}
      </li>
    </ol>
  </nav>
</div>

<!-- Main Content -->
<section class="py-5" style="background: white;">
  <div class="container">
    @if($articles->isEmpty() && $news->isEmpty())
      <!-- Empty State -->
      <div class="text-center py-5">
        <div class="edu-icon-circle mx-auto mb-4" style="background: linear-gradient(135deg, #e74c3c, #c0392b); width: 5rem; height: 5rem;">
          <i class="ti ti-search-off text-white" style="font-size: 2rem;"></i>
        </div>
        <h3 class="fw-bold mb-3" style="color: var(--edu-dark);">{{ __('no_content_for_keyword') }}</h3>
        <p class="text-muted mb-4">لم يتم العثور على محتوى مرتبط بهذه الكلمة المفتاحية</p>
        <a href="{{ route('frontend.keywords.index', ['database' => $database ?? session('database', 'jo')]) }}" class="btn btn-primary btn-lg px-4 py-3 rounded-pill">
          <i class="ti ti-arrow-right me-2"></i>تصفح الكلمات المفتاحية الأخرى
        </a>
      </div>
    @else
      <!-- Articles Section -->
      @if($articles->count() > 0)
      <div class="mb-5">
        <h2 class="edu-section-title mb-4">
          <i class="ti ti-article me-2" style="color: var(--edu-blue);"></i>
          {{ __('articles') }}
          <span class="badge bg-primary-subtle text-primary ms-2">{{ $articles->count() }}</span>
        </h2>
        <div class="row g-4">
          @foreach($articles as $article)
          <div class="col-lg-4 col-md-6">
            <a href="{{ route('frontend.articles.show', ['database' => $database ?? session('database', 'jo'), 'article' => $article->id]) }}" class="text-decoration-none">
              <div class="edu-card h-100">
                @php
                  $fileType = 'article';
                  $fileIcon = 'ti ti-article';
                  $fileColor = 'var(--edu-blue)';

                  // Determine file type from article files if available
                  if($article->files && $article->files->count() > 0) {
                    $firstFile = $article->files->first();
                    $extension = strtolower(pathinfo($firstFile->file_name, PATHINFO_EXTENSION));

                    switch($extension) {
                      case 'pdf':
                        $fileIcon = 'ti ti-file-type-pdf';
                        $fileColor = '#e74c3c';
                        break;
                      case 'doc':
                      case 'docx':
                        $fileIcon = 'ti ti-file-type-doc';
                        $fileColor = '#2980b9';
                        break;
                      case 'xls':
                      case 'xlsx':
                        $fileIcon = 'ti ti-file-type-xls';
                        $fileColor = '#27ae60';
                        break;
                      case 'ppt':
                      case 'pptx':
                        $fileIcon = 'ti ti-file-type-ppt';
                        $fileColor = '#f39c12';
                        break;
                    }
                  }
                @endphp

                <div class="edu-card-header" style="background: linear-gradient(135deg, {{ $fileColor }}, {{ $fileColor }}dd); position: relative; overflow: hidden;">
                  <div class="edu-icon-circle" style="background: rgba(255,255,255,0.2); backdrop-filter: blur(10px); border: 1px solid rgba(255,255,255,0.3); width: 4rem; height: 4rem;">
                    <i class="{{ $fileIcon }} text-white" style="font-size: 1.8rem;"></i>
                  </div>
                  <div class="position-absolute top-0 end-0 p-3">
                    <span class="badge bg-light text-dark">مقال</span>
                  </div>
                  <!-- Background Pattern -->
                  <div class="position-absolute" style="top: -20px; right: -20px; width: 100px; height: 100px; background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%); border-radius: 50%;"></div>
                </div>

                <div class="edu-card-body">
                  <h5 class="fw-bold mb-2" style="color: var(--edu-dark); line-height: 1.4;">{{ $article->title }}</h5>
                  <p class="text-muted mb-3" style="font-size: 0.9rem; line-height: 1.5;">{{ Str::limit(strip_tags($article->content), 100) }}</p>

                  <div class="d-flex justify-content-between align-items-center">
                    <div class="d-flex align-items-center text-muted small">
                      <div class="edu-icon-circle me-2" style="background: linear-gradient(135deg, #f39c12, #e67e22); width: 1.5rem; height: 1.5rem;">
                        <i class="ti ti-calendar text-white" style="font-size: 0.7rem;"></i>
                      </div>
                      {{ $article->created_at ? $article->created_at->diffForHumans() : 'منذ وقت قريب' }}
                    </div>
                    <span class="badge bg-primary-subtle text-primary">اقرأ المزيد</span>
                  </div>
                </div>
              </div>
            </a>
          </div>
          @endforeach
        </div>
      </div>
      @endif

      <!-- News Section -->
      @if($news->count() > 0)
      <div class="mb-5">
        <h2 class="edu-section-title mb-4">
          <i class="ti ti-news me-2" style="color: var(--edu-purple);"></i>
          {{ __('news') }}
          <span class="badge bg-purple-subtle text-purple ms-2">{{ $news->count() }}</span>
        </h2>
        <div class="row g-4">
          @foreach($news as $newsItem)
          <div class="col-lg-4 col-md-6">
            <a href="{{ route('content.frontend.news.show', ['database' => $database ?? session('database', 'jo'), 'id' => $newsItem->id]) }}" class="text-decoration-none">
              <div class="edu-card h-100">
                <div class="edu-card-header" style="background: linear-gradient(135deg, var(--edu-purple), #8e44ad); position: relative; overflow: hidden;">
                  @php
                    $imagePath = $newsItem->image ? asset('storage/' . $newsItem->image) : null;
                  @endphp

                  @if($imagePath)
                    <div class="position-relative" style="height: 200px; overflow: hidden; border-radius: 1rem 1rem 0 0;">
                      <img src="{{ $imagePath }}" alt="{{ $newsItem->title }}" class="w-100 h-100" style="object-fit: cover; transition: transform 0.3s ease;">
                      <div class="position-absolute top-0 start-0 w-100 h-100" style="background: linear-gradient(45deg, rgba(155, 89, 182, 0.3), transparent);"></div>
                    </div>
                  @else
                    <div class="edu-icon-circle" style="background: rgba(255,255,255,0.2); backdrop-filter: blur(10px); border: 1px solid rgba(255,255,255,0.3); width: 4rem; height: 4rem;">
                      <i class="ti ti-news text-white" style="font-size: 1.8rem;"></i>
                    </div>
                  @endif

                  <div class="position-absolute top-0 end-0 p-3">
                    <span class="badge bg-light text-dark">خبر</span>
                  </div>
                  <!-- Background Pattern -->
                  <div class="position-absolute" style="top: -20px; right: -20px; width: 100px; height: 100px; background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%); border-radius: 50%;"></div>
                </div>

                <div class="edu-card-body">
                  <h5 class="fw-bold mb-2" style="color: var(--edu-dark); line-height: 1.4;">{{ $newsItem->title }}</h5>
                  <p class="text-muted mb-3" style="font-size: 0.9rem; line-height: 1.5;">{{ Str::limit(strip_tags($newsItem->description), 100) }}</p>

                  <div class="d-flex justify-content-between align-items-center">
                    <div class="d-flex align-items-center text-muted small">
                      <div class="edu-icon-circle me-2" style="background: linear-gradient(135deg, #f39c12, #e67e22); width: 1.5rem; height: 1.5rem;">
                        <i class="ti ti-calendar text-white" style="font-size: 0.7rem;"></i>
                      </div>
                      {{ $newsItem->created_at ? $newsItem->created_at->diffForHumans() : 'منذ وقت قريب' }}
                    </div>
                    <span class="badge bg-purple-subtle text-purple">اقرأ المزيد</span>
                  </div>
                </div>
              </div>
            </a>
          </div>
          @endforeach
        </div>
      </div>
      @endif

      <!-- Ads Section -->
      @if(config('settings.google_ads_desktop_home_2') || config('settings.google_ads_mobile_home_2'))
      <div class="mt-5">
        <div class="edu-card p-4 text-center">
          @if($detect->isMobile())
            {!! config('settings.google_ads_mobile_home_2') !!}
          @else
            {!! config('settings.google_ads_desktop_home_2') !!}
          @endif
        </div>
      </div>
      @endif
    @endif
  </div>
</section>
@endsection
