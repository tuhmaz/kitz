@php
$configData = Helper::appClasses();
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Detection\MobileDetect;

$detect = new MobileDetect;
@endphp

@extends('layouts/layoutFront')

@section('title', __('نتائج البحث'))

@section('page-style')
@vite([
    'resources/assets/vendor/scss/home.scss'
])
@endsection

@section('content')
<div class="edu-gradient-bg" style="padding-top: 80px;">

  <!-- Welcome Section -->
  <section class="edu-welcome-section">
    <div class="container">
      <div class="edu-welcome-content">
        <div class="row align-items-center">
          <div class="col-lg-8">
            <h1 class="display-4 fw-bold mb-4" style="color: white; line-height: 1.2;">
              نتائج البحث
            </h1>
            <p class="lead mb-4" style="color: rgba(255,255,255,0.9); font-size: 1.25rem;">
              اكتشف المقالات والملفات التعليمية ذات الصلة
            </p>
          </div>
          <div class="col-lg-4 d-none d-lg-block">
            <div class="row g-3">
              <div class="col-12">
                <div class="text-center p-3" style="background: rgba(255,255,255,0.1); backdrop-filter: blur(15px); border: 1px solid rgba(255,255,255,0.2); border-radius: 1rem;">
                  <div class="row g-3">
                    <div class="col-6">
                      <div class="text-center">
                        <div class="edu-icon-circle mb-2" style="background: rgba(255,255,255,0.2); width: 2.5rem; height: 2.5rem; margin: 0 auto;">
                          <i class="ti ti-article text-white"></i>
                        </div>
                        <p class="small mb-1" style="color: rgba(255,255,255,0.8);">المقالات</p>
                        <p class="h5 fw-bold mb-0" style="color: white;">{{ ($articles ? $articles->count() : 0) }}</p>
                      </div>
                    </div>
                    <div class="col-6">
                      <div class="text-center">
                        <div class="edu-icon-circle mb-2" style="background: rgba(255,255,255,0.2); width: 2.5rem; height: 2.5rem; margin: 0 auto;">
                          <i class="ti ti-file text-white"></i>
                        </div>
                        <p class="small mb-1" style="color: rgba(255,255,255,0.8);">الملفات</p>
                        <p class="h5 fw-bold mb-0" style="color: white;">{{ ($files ? $files->count() : 0) }}</p>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
  <!-- Main Content Section -->
  <section class="py-5" style="background: var(--edu-light);">
    <div class="container">
      <!-- Breadcrumb -->
      <div class="mb-4">
        <nav aria-label="breadcrumb">
          <ol class="breadcrumb mb-0" style="background: transparent; padding: 0;">
            <li class="breadcrumb-item">
              <a href="{{ route('home') }}" class="text-decoration-none" style="color: var(--edu-primary);">
                <i class="ti ti-home me-1"></i>الرئيسية
              </a>
            </li>
            <li class="breadcrumb-item active" style="color: var(--edu-dark);">نتائج البحث</li>
          </ol>
        </nav>
      </div>

      <!-- Articles Section -->
      @if($articles && $articles->count() > 0)
      <div class="mb-5">
        <div class="d-flex align-items-center justify-content-between mb-4">
          <div>
            <h2 class="edu-section-title mb-2" style="color: var(--edu-primary);">المقالات</h2>
            <p class="text-muted mb-0">{{ $articles->count() }} مقال متاح</p>
          </div>
          <div class="edu-icon-circle" style="background: linear-gradient(135deg, var(--edu-primary), var(--edu-secondary)); width: 3rem; height: 3rem;">
            <i class="ti ti-article text-white"></i>
          </div>
        </div>
        <!-- Ads Section -->
        @if(config('settings.google_ads_desktop_classes') || config('settings.google_ads_mobile_classes'))
        <div class="mb-4">
          <div class="edu-card p-3 text-center">
            @if($detect->isMobile())
              {!! config('settings.google_ads_mobile_classes') !!}
            @else
              {!! config('settings.google_ads_desktop_classes') !!}
            @endif
          </div>
        </div>
        @endif

        <div class="row g-4">
          @foreach($articles as $article)
          <div class="col-lg-4 col-md-6">
            <a href="{{ route('frontend.articles.show', ['database' => $database, 'article' => $article->id]) }}" class="text-decoration-none">
              <div class="edu-article-card h-100">
                @if($article->image_path)
                <div class="position-relative mb-3" style="height: 200px; border-radius: 0.75rem; overflow: hidden;">
                  <img src="{{ Storage::url($article->image_path) }}" alt="{{ $article->title }}" class="w-100 h-100 object-fit-cover">
                  <div class="position-absolute top-0 start-0 w-100 h-100" style="background: linear-gradient(45deg, rgba(31, 54, 173, 0.1), rgba(59, 130, 246, 0.1));"></div>
                </div>
                @else
                <div class="d-flex align-items-center justify-content-center mb-3" style="height: 200px; background: linear-gradient(135deg, var(--edu-primary), var(--edu-secondary)); border-radius: 0.75rem;">
                  <i class="ti ti-article text-white" style="font-size: 3rem;"></i>
                </div>
                @endif
                
                <div class="p-3">
                  <h5 class="fw-semibold mb-2" style="color: var(--edu-dark);">{{ $article->title }}</h5>
                  <p class="text-muted mb-3">{{ Str::limit(strip_tags($article->description), 100) }}</p>
                  
                  <div class="d-flex justify-content-between align-items-center">
                    <div class="d-flex align-items-center">
                      <i class="ti ti-calendar me-1 text-muted"></i>
                      <small class="text-muted">{{ $article->created_at->format('Y-m-d') }}</small>
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
        </div>
        @endif

      <!-- Files Section -->
      @if($files && $files->count() > 0)
      <div class="mb-5">
        <div class="d-flex align-items-center justify-content-between mb-4">
          <div>
            <h2 class="edu-section-title mb-2" style="color: var(--edu-success);">الملفات</h2>
            <p class="text-muted mb-0">{{ $files->count() }} ملف متاح</p>
          </div>
          <div class="edu-icon-circle" style="background: linear-gradient(135deg, var(--edu-success), var(--edu-info)); width: 3rem; height: 3rem;">
            <i class="ti ti-files text-white"></i>
          </div>
        </div>

        <!-- Ads Section -->
        @if(config('settings.google_ads_desktop_classes_2') || config('settings.google_ads_mobile_classes_2'))
        <div class="mb-4">
          <div class="edu-card p-3 text-center">
            @if($detect->isMobile())
              {!! config('settings.google_ads_mobile_classes_2') !!}
            @else
              {!! config('settings.google_ads_desktop_classes_2') !!}
            @endif
          </div>
        </div>
        @endif
        <div class="row g-4">
          @foreach($files as $file)
          @php
            $fileIcon = 'file-text';
            $fileColor = 'var(--edu-primary)';
            $fileGradient = 'linear-gradient(135deg, var(--edu-primary), var(--edu-secondary))';
            
            if (in_array($file->file_type, ['pdf'])) {
              $fileIcon = 'file-type-pdf';
              $fileColor = '#e74c3c';
              $fileGradient = 'linear-gradient(135deg, #e74c3c, #c0392b)';
            } elseif (in_array($file->file_type, ['doc', 'docx'])) {
              $fileIcon = 'file-type-doc';
              $fileColor = '#2980b9';
              $fileGradient = 'linear-gradient(135deg, #2980b9, #3498db)';
            } elseif (in_array($file->file_type, ['xls', 'xlsx'])) {
              $fileIcon = 'file-type-xls';
              $fileColor = '#27ae60';
              $fileGradient = 'linear-gradient(135deg, #27ae60, #2ecc71)';
            } elseif (in_array($file->file_type, ['ppt', 'pptx'])) {
              $fileIcon = 'presentation';
              $fileColor = '#f39c12';
              $fileGradient = 'linear-gradient(135deg, #f39c12, #e67e22)';
            }
          @endphp
          
          <div class="col-lg-4 col-md-6">
            <div class="edu-card h-100 p-4">
              <div class="d-flex align-items-center mb-3">
                <div class="edu-icon-circle me-3" style="background: {{ $fileGradient }}; width: 3rem; height: 3rem;">
                  <i class="ti ti-{{ $fileIcon }} text-white"></i>
                </div>
                <div class="flex-grow-1">
                  <h5 class="fw-semibold mb-1" style="color: var(--edu-dark);">{{ $file->file_Name }}</h5>
                  <span class="badge" style="background: {{ $fileColor }}; color: white;">{{ strtoupper($file->file_type) }}</span>
                </div>
              </div>
              
              @if($file->description)
              <p class="text-muted mb-3">{{ Str::limit($file->description, 100) }}</p>
              @endif
              
              <div class="d-flex justify-content-between align-items-center mb-3">
                <div class="d-flex align-items-center">
                  <i class="ti ti-file-size me-1 text-muted"></i>
                  <small class="text-muted">{{ number_format($file->file_size / 1024 / 1024, 2) }} MB</small>
                </div>
                <div class="d-flex align-items-center">
                  <i class="ti ti-calendar me-1 text-muted"></i>
                  <small class="text-muted">{{ $file->created_at->format('Y-m-d') }}</small>
                </div>
              </div>
              
              <a href="{{ route('download.page', ['file' => $file->id, 'database' => $database]) }}" 
                 class="btn w-100" style="background: {{ $fileGradient }}; color: white; border: none; font-weight: 600;">
                <i class="ti ti-download me-2"></i>تحميل
              </a>
            </div>
          </div>
          @endforeach
        </div>
      </div>
      @endif

      <!-- Empty State -->
      @if((!$articles || !$articles->count()) && (!$files || !$files->count()))
      <div class="text-center py-5">
        <div class="edu-icon-circle mx-auto mb-4" style="background: linear-gradient(135deg, var(--edu-gray-400), var(--edu-gray-500)); width: 5rem; height: 5rem;">
          <i class="ti ti-search-off text-white" style="font-size: 2rem;"></i>
        </div>
        <h3 class="fw-bold mb-3" style="color: var(--edu-dark);">لم يتم العثور على نتائج</h3>
        <p class="text-muted mb-4">جرب استخدام كلمات بحث مختلفة أو تصفح الفئات</p>
        <a href="{{ route('home') }}" class="btn btn-primary px-4 py-2 rounded-pill" style="font-weight: 600;">
          <i class="ti ti-home me-2"></i>العودة للرئيسية
        </a>
      </div>
      @endif
    </div>
  </section>

</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
  // Add smooth scrolling and animations
  const observerOptions = {
    threshold: 0.1,
    rootMargin: '0px 0px -50px 0px'
  };

  const observer = new IntersectionObserver(function(entries) {
    entries.forEach(entry => {
      if (entry.isIntersecting) {
        entry.target.style.opacity = '1';
        entry.target.style.transform = 'translateY(0)';
      }
    });
  }, observerOptions);

  // Observe all cards for animation
  document.querySelectorAll('.edu-card, .edu-article-card').forEach(card => {
    card.style.opacity = '0';
    card.style.transform = 'translateY(20px)';
    card.style.transition = 'all 0.6s ease';
    observer.observe(card);
  });
});
</script>
@endpush

@endsection
