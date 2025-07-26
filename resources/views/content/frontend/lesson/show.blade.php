@php
$configData = Helper::appClasses();
use Detection\MobileDetect;

// Create MobileDetect object
$detect = new MobileDetect;

// Array of available colors
$colors = ['primary', 'secondary', 'success', 'danger', 'warning', 'info', 'dark'];
$colorCount = count($colors);

// أيقونات الصفوف حسب المستوى التعليمي
$gradeIcons = [
  // رياض الأطفال
  'رياض' => 'ti ti-baby-carriage',
  'روضة' => 'ti ti-baby-carriage', 
  'تمهيدي' => 'ti ti-baby-carriage',
  'kg' => 'ti ti-baby-carriage',
  
  // المرحلة الابتدائية
  '1' => 'ti ti-number-1',
  '2' => 'ti ti-number-2', 
  '3' => 'ti ti-number-3',
  '4' => 'ti ti-number-4',
  '5' => 'ti ti-number-5',
  '6' => 'ti ti-number-6',
  'اول' => 'ti ti-number-1',
  'ثاني' => 'ti ti-number-2',
  'ثالث' => 'ti ti-number-3',
  'رابع' => 'ti ti-number-4',
  'خامس' => 'ti ti-number-5',
  'سادس' => 'ti ti-number-6',
  
  // المرحلة المتوسطة  
  '7' => 'ti ti-certificate',
  '8' => 'ti ti-certificate-2',
  '9' => 'ti ti-award',
  'سابع' => 'ti ti-certificate',
  'ثامن' => 'ti ti-certificate-2', 
  'تاسع' => 'ti ti-award',
  
  // المرحلة الثانوية
  '10' => 'ti ti-school',
  '11' => 'ti ti-book-2',
  '12' => 'ti ti-graduation-cap',
  '13' => 'ti ti-trophy',
  'عاشر' => 'ti ti-school',
  'حادي' => 'ti ti-book-2',
  'ثاني عشر' => 'ti ti-graduation-cap',
  'ثالث عشر' => 'ti ti-trophy',
  
  'default' => 'ti ti-book'
];

// تحديد الأيقونة المناسبة للصف الحالي
$currentIcon = 'ti ti-book'; // الأيقونة الافتراضية
if (isset($lesson->grade_name)) {
  $gradeName = strtolower($lesson->grade_name);
  
  foreach ($gradeIcons as $key => $icon) {
    if (str_contains($gradeName, $key)) {
      $currentIcon = $icon;
      break;
    }
  }
}

// تحديد اللون المناسب للصف
$gradeColorClass = 'grade-1'; // افتراضي
if (isset($lesson->id)) {
  if (str_contains(strtolower($lesson->grade_name ?? ''), 'رياض')) {
    $gradeColorClass = 'grade-kg';
  } elseif ($lesson->id >= 12) {
    $gradeColorClass = 'grade-' . $lesson->id;
  } else {
    $gradeColorClass = 'grade-' . $lesson->id;
  }
}

$icons = $gradeIcons;
@endphp

@extends('layouts/layoutFront')

@section('title', $lesson->grade_name ?? 'Grade Name')
@section('meta_title', ($lesson->grade_name ?? 'Grade Name') . ' - ' . ($lesson->meta_title ?? config('settings.meta_title')))

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
            <div class="d-flex align-items-center mb-4">
              <div class="edu-grade-card {{ $gradeColorClass }} me-4" style="background: rgba(255,255,255,0.15); backdrop-filter: blur(10px); border: 1px solid rgba(255,255,255,0.2); padding: 1rem; border-radius: 1rem; min-width: auto;">
                <div class="edu-icon-circle" style="width: 4rem; height: 4rem; margin: 0;">
                  <i class="{{ $currentIcon }} text-white" style="font-size: 2rem;"></i>
                </div>
              </div>
              <div>
                <h1 class="display-4 fw-bold mb-0" style="color: white; line-height: 1.2;">
                  {{ $lesson->grade_name }}
                </h1>
                <p class="text-white-50 mb-0">المرحلة التعليمية</p>
              </div>
            </div>
            <p class="lead mb-4" style="color: rgba(255, 255, 255, 0.9); font-size: 1.25rem;">
              استكشف المواد الدراسية والمحتوى التعليمي المتميز لهذا الصف
            </p>
            <div class="d-flex flex-wrap gap-3 mb-4">
              @auth
                <a href="#subjects" class="btn btn-light btn-lg px-4 py-3 rounded-pill" style="font-weight: 600;">
                  <i class="ti ti-book-2 me-2"></i>استكشف المواد
                </a>
                <a href="{{ route('home') }}" class="btn btn-outline-light btn-lg px-4 py-3 rounded-pill" style="font-weight: 600;">
                  <i class="ti ti-home me-2"></i>العودة للرئيسية
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
                        <div class="edu-icon-circle mx-auto mb-2" style="background: linear-gradient(135deg, #10b981, #059669); border: 1px solid rgba(255,255,255,0.3); width: 3rem; height: 3rem;">
                          <i class="ti ti-books text-white" style="font-size: 1.2rem;"></i>
                        </div>
                        <p class="small mb-1" style="color: rgba(255,255,255,0.9); font-size: 0.75rem;">المواد</p>
                        <p class="h5 fw-bold mb-0" style="color: white;">{{ $lesson->subjects ? $lesson->subjects->count() : 0 }}</p>
                      </div>
                    </div>
                    <div class="col-4">
                      <div class="text-center">
                        <div class="edu-icon-circle mx-auto mb-2" style="background: linear-gradient(135deg, #3b82f6, #1d4ed8); border: 1px solid rgba(255,255,255,0.3); width: 3rem; height: 3rem;">
                          <i class="ti ti-files text-white" style="font-size: 1.2rem;"></i>
                        </div>
                        <p class="small mb-1" style="color: rgba(255,255,255,0.9); font-size: 0.75rem;">الملفات</p>
                        <p class="h5 fw-bold mb-0" style="color: white;">{{ $lesson->subjects ? $lesson->subjects->sum(function($subject) { return $subject->articles ? $subject->articles->sum(function($article) { return $article->files ? $article->files->count() : 0; }) : 0; }) : 0 }}</p>
                      </div>
                    </div>
                    <div class="col-4">
                      <div class="text-center">
                        <div class="edu-icon-circle mx-auto mb-2" style="background: linear-gradient(135deg, #f59e0b, #d97706); border: 1px solid rgba(255,255,255,0.3); width: 3rem; height: 3rem;">
                          <i class="ti ti-medal text-white" style="font-size: 1.2rem;"></i>
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
          <li class="breadcrumb-item"><a href="{{ route('frontend.lesson.index', ['database' => $database ?? session('database', 'default_database')])}}" class="text-decoration-none">{{ __('Lessons') }}</a></li>
          <li class="breadcrumb-item active" aria-current="page">{{ $lesson->grade_name }}</li>
        </ol>
      </nav>
    </div>
  </section>

  <!-- Subjects Section -->
  <section class="py-5" id="subjects" style="background: var(--edu-light);">
    <div class="container">
      <div class="text-center mb-5">
        <h2 class="edu-section-title mb-3" style="color: var(--edu-primary);">المواد الدراسية</h2>
        <p class="text-muted mb-0">اختر المادة التي تريد دراستها</p>
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
        @forelse($lesson->subjects ?? [] as $index => $subject)
        @php
          // أيقونات المواد الدراسية
          $subjectIcons = [
            'عربي' => 'ti ti-language',
            'لغة' => 'ti ti-language',
            'رياضيات' => 'ti ti-calculator',
            'حساب' => 'ti ti-calculator',
            'علوم' => 'ti ti-atom',
            'فيزياء' => 'ti ti-atom-2',
            'كيمياء' => 'ti ti-flask',
            'أحياء' => 'ti ti-dna',
            'تاريخ' => 'ti ti-clock-hour-4',
            'جغرافيا' => 'ti ti-world',
            'إنجليزي' => 'ti ti-language-katakana',
            'english' => 'ti ti-language-katakana',
            'دين' => 'ti ti-mosque',
            'إسلامية' => 'ti ti-mosque',
            'فنية' => 'ti ti-palette',
            'رياضة' => 'ti ti-ball-football',
            'موسيقى' => 'ti ti-music',
            'حاسوب' => 'ti ti-device-laptop',
            'تقنية' => 'ti ti-device-laptop',
            'default' => 'ti ti-book-2'
          ];
          
          // تحديد الأيقونة المناسبة للمادة
          $subjectIcon = 'ti ti-book-2';
          $subjectName = strtolower($subject->subject_name ?? '');
          
          foreach ($subjectIcons as $key => $iconClass) {
            if (str_contains($subjectName, $key)) {
              $subjectIcon = $iconClass;
              break;
            }
          }
          
          $icon = $subjectIcon;
          $color = $colors[$index % $colorCount];
          $database = session('database', 'jo');
          
          // Get real file count for this subject
          $fileCount = \App\Models\File::on($database)
              ->whereHas('article', function($query) use ($subject) {
                  $query->where('subject_id', $subject->id);
              })
              ->count();
          
          // Calculate completion percentage (placeholder until user progress system is implemented)
          $completedFiles = 0;
          $completionPercentage = 0;
          
          if (Auth::check() && $fileCount > 0) {
              // TODO: Implement user progress tracking system
              // For now, show random progress for demonstration
              $completedFiles = rand(0, min($fileCount, 5));
              $completionPercentage = $fileCount > 0 ? round(($completedFiles / $fileCount) * 100) : 0;
          }
        @endphp
        <div class="col-xl-4 col-lg-6 col-md-6">
          <a href="{{ route('frontend.subjects.show', ['database' => $database, 'id' => $lesson->id, 'subject' => $subject->id]) }}" class="text-decoration-none">
            <div class="edu-subject-card p-4">
              <div class="d-flex align-items-center justify-content-between mb-3">
                <div class="d-flex align-items-center">
                  <div class="edu-icon-circle me-3" style="background: var(--edu-{{ $color === 'primary' ? 'blue' : ($color === 'success' ? 'green' : 'purple') }}); width: 3rem; height: 3rem;">
                    <i class="{{ $icon }} text-white"></i>
                  </div>
                  <div>
                    <h5 class="fw-bold mb-1" style="color: var(--edu-dark);">{{ $subject->subject_name }}</h5>
                    <p class="text-muted small mb-0">{{ $fileCount }} ملف متاح</p>
                  </div>
                </div>
                <i class="ti ti-arrow-left" style="color: var(--edu-primary); font-size: 1.2rem;"></i>
              </div>
              <div class="edu-progress-bar mb-2">
                <div class="edu-progress-fill" style="width: {{ $completionPercentage }}%; background: var(--edu-{{ $color === 'primary' ? 'blue' : ($color === 'success' ? 'green' : 'purple') }});"></div>
              </div>
              <p class="text-muted small mb-0">
                @if($fileCount > 0)
                  {{ $completionPercentage }}% مكتمل ({{ $completedFiles }}/{{ $fileCount }})
                @else
                  لا توجد ملفات
                @endif
              </p>
            </div>
          </a>
        </div>
        @empty
        <div class="col-12">
          <div class="edu-card p-4 text-center">
            <i class="ti ti-book-off text-muted mb-3" style="font-size: 3rem;"></i>
            <p class="text-muted mb-0">{{ __('No subjects available.') }}</p>
          </div>
        </div>
        @endforelse
      </div>

      <!-- Additional Ads -->
      @if(config('settings.google_ads_desktop_classes_2') || config('settings.google_ads_mobile_classes_2'))
      <div class="mt-4">
        <div class="edu-card p-3 text-center">
          @if($detect->isMobile())
            {!! config('settings.google_ads_mobile_classes_2') !!}
          @else
            {!! config('settings.google_ads_desktop_classes_2') !!}
          @endif
        </div>
      </div>
      @endif
    </div>
  </section>

</div>

@endsection
