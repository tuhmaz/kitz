@php
$configData = Helper::appClasses();
use Detection\MobileDetect;

$detect = new MobileDetect;

// Array of available colors
$colors = ['primary', 'secondary', 'success', 'danger', 'warning', 'info', 'dark'];
$colorCount = count($colors);

$icons = [
'plans' => 'ti ti-calendar',
'papers' => 'ti ti-file-text',
'tests' => 'ti ti-clipboard-check',
'books' => 'ti ti-book',
'default' => 'ti ti-file'
];
@endphp

@extends('layouts.layoutFront')

@section('title', ($subject->schoolClass->grade_name ?? 'Grade Name') . ' - ' . ($subject->subject_name ?? 'Subject Name'))

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
              {{ $subject->subject_name }} 📚
            </h1>
            <p class="lead mb-4" style="color: rgba(255, 255, 255, 0.9); font-size: 1.25rem;">
              استكشف المحتوى التعليمي والمواد الدراسية لمادة {{ $subject->subject_name }}
            </p>
            <div class="d-flex flex-wrap gap-3 mb-4">
              @auth
                <a href="#semesters" class="btn btn-light btn-lg px-4 py-3 rounded-pill" style="font-weight: 600;">
                  <i class="ti ti-calendar me-2"></i>استكشف الفصول
                </a>
                <a href="{{ route('frontend.lesson.show', ['database' => session('database', 'jo'), 'id' => $subject->schoolClass->id]) }}" class="btn btn-outline-light btn-lg px-4 py-3 rounded-pill" style="font-weight: 600;">
                  <i class="ti ti-arrow-left me-2"></i>العودة للصف
                </a>
              @else
                <a href="{{ route('login') }}" class="btn btn-light btn-lg px-4 py-3 rounded-pill" style="font-weight: 600;">
                  <i class="ti ti-user-plus me-2"></i>تسجيل الدخول
                </a>
                <a href="{{ route('register') }}" class="btn btn-outline-light btn-lg px-4 py-3 rounded-pill" style="font-weight: 600;">
                  <i class="ti ti-user-check me-2"></i>إنشاء حساب
                </a>
              @endauth
            </div>
          </div>
          <div class="col-lg-4 d-none d-lg-block">
            <div class="row g-3">
              <div class="col-12">
                <div class="text-center p-3" style="background: rgba(255,255,255,0.1); backdrop-filter: blur(15px); border: 1px solid rgba(255,255,255,0.2); border-radius: 1rem;">
                  <div class="row g-3">
                    <div class="col-4">
                      <div class="text-center">
                        <div class="edu-icon-circle mx-auto mb-2" style="background: rgba(255,255,255,0.2); backdrop-filter: blur(10px); border: 1px solid rgba(255,255,255,0.3); width: 3rem; height: 3rem;">
                          <i class="ti ti-calendar text-white" style="font-size: 1.2rem;"></i>
                        </div>
                        <p class="small mb-1" style="color: rgba(255,255,255,0.9); font-size: 0.75rem;">الفصول</p>
                        <p class="h5 fw-bold mb-0" style="color: white;">2</p>
                      </div>
                    </div>
                    <div class="col-4">
                      <div class="text-center">
                        <div class="edu-icon-circle mx-auto mb-2" style="background: rgba(255,255,255,0.2); backdrop-filter: blur(10px); border: 1px solid rgba(255,255,255,0.3); width: 3rem; height: 3rem;">
                          <i class="ti ti-file text-white" style="font-size: 1.2rem;"></i>
                        </div>
                        <p class="small mb-1" style="color: rgba(255,255,255,0.9); font-size: 0.75rem;">الملفات</p>
                        @php
                          $totalFiles = 0;
                          if (isset($subject->articles) && $subject->articles) {
                              foreach ($subject->articles as $article) {
                                  if (isset($article->files) && $article->files) {
                                      $totalFiles += $article->files->count();
                                  }
                              }
                          }
                        @endphp
                        <p class="h5 fw-bold mb-0" style="color: white;">{{ number_format($totalFiles) }}</p>
                      </div>
                    </div>
                    <div class="col-4">
                      <div class="text-center">
                        <div class="edu-icon-circle mx-auto mb-2" style="background: rgba(255,255,255,0.2); backdrop-filter: blur(10px); border: 1px solid rgba(255,255,255,0.3); width: 3rem; height: 3rem;">
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
        </div>
      </div>
    </div>
  </section>

  <!-- Breadcrumb Section -->
  <section class="py-3" style="background: var(--edu-gray-50);">
    <div class="container">
      <nav aria-label="breadcrumb">
        <ol class="breadcrumb mb-0">
          <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-decoration-none"><i class="ti ti-home me-1"></i>{{ __('Home') }}</a></li>
          <li class="breadcrumb-item"><a href="{{ route('frontend.lesson.index', ['database' => $database ?? session('database', 'default_database')])}}" class="text-decoration-none">{{ __('Classes') }}</a></li>
          <li class="breadcrumb-item">
            <a href="{{ route('frontend.lesson.show', ['database' => $database ?? session('database'),'id' => $subject->schoolClass->id]) }}" class="text-decoration-none">
              {{ $subject->schoolClass->grade_name }}
            </a>
          </li>
          <li class="breadcrumb-item active" aria-current="page">{{ $subject->subject_name }}</li>
        </ol>
      </nav>
    </div>
  </section>

  <!-- Semesters Section -->
  <section class="py-5" id="semesters" style="background: var(--edu-light);">
    <div class="container">
      <div class="text-center mb-5">
        <h2 class="edu-section-title mb-3" style="color: var(--edu-primary);">الفصول الدراسية</h2>
        <p class="text-muted mb-0">اختر الفصل الدراسي ونوع المحتوى</p>
      </div>

      <!-- Ads Section -->
      @if(config('settings.google_ads_desktop_subject') || config('settings.google_ads_mobile_subject'))
      <div class="mb-4">
        <div class="edu-card p-3 text-center">
          @if($detect->isMobile())
            {!! config('settings.google_ads_mobile_subject') !!}
          @else
            {!! config('settings.google_ads_desktop_subject') !!}
          @endif
        </div>
      </div>
      @endif

      <div class="row g-4">
        @foreach(['الفصل الدراسي الأول', 'الفصل الدراسي الثاني'] as $semesterIndex => $semester_name)
        <div class="col-12">
          <div class="edu-semester-card p-4 mb-4">
            <div class="text-center mb-4">
              <div class="edu-icon-circle mx-auto mb-3" style="background: var(--edu-{{ $semesterIndex == 0 ? 'primary' : 'secondary' }}); width: 4rem; height: 4rem;">
                <i class="ti ti-calendar text-white" style="font-size: 1.5rem;"></i>
              </div>
              <h3 class="fw-bold mb-2" style="color: var(--edu-dark);">{{ $semester_name }}</h3>
              <p class="text-muted mb-0">{{ $subject->subject_name }}</p>
            </div>
            
            @foreach($semesters->where('semester_name', $semester_name)->where('grade_level', $subject->grade_level) as $semester)
            <div class="row g-3">
              @php
                $categories = [
                  'plans' => ['name' => __('Study Plans'), 'color' => 'secondary', 'icon' => 'ti ti-calendar'],
                  'papers' => ['name' => __('Worksheets'), 'color' => 'success', 'icon' => 'ti ti-file-text'],
                  'tests' => ['name' => __('Tests'), 'color' => 'danger', 'icon' => 'ti ti-clipboard-check'],
                  'books' => ['name' => __('School Books'), 'color' => 'warning', 'icon' => 'ti ti-book']
                ];
              @endphp
              
              @foreach($categories as $categoryKey => $category)
              @php
                $database = session('database', 'jo');
                // Get file count for this category, subject and semester
                $fileCount = \App\Models\File::on($database)
                    ->where('file_category', $categoryKey)
                    ->whereHas('article', function($query) use ($subject, $semester) {
                        $query->where('subject_id', $subject->id)
                             ->where('semester_id', $semester->id);
                    })
                    ->count();
              @endphp
              <div class="col-xl-3 col-lg-6 col-md-6">
                <a href="{{ route('frontend.subject.articles', ['database' => $database,'subject' => $subject->id, 'semester' => $semester->id, 'category' => $categoryKey]) }}" class="text-decoration-none">
                  <div class="edu-category-card category-{{ $categoryKey }} p-4 h-100">
                    <div class="d-flex align-items-center mb-3">
                      <div class="edu-icon-circle me-3">
                        <i class="{{ $category['icon'] }} fs-4"></i>
                      </div>
                      <div>
                        <h6 class="mb-1 fw-bold">{{ $category['name'] }}</h6>
                        <small class="text-muted">{{ $fileCount }} ملف</small>
                      </div>
                    </div>
                    <div class="d-flex justify-content-between align-items-center">
                      <span class="badge bg-light text-dark">متاح الآن</span>
                      <i class="ti ti-arrow-left text-primary"></i>
                    </div>
                  </div>
                </a>
              </div>
              @endforeach
            </div>
            @endforeach
          </div>
        </div>
        @endforeach
      </div>
    </div>
  </section>

</div>
@endsection
