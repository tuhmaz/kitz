@php
    use Illuminate\Support\Facades\Storage;
    use Illuminate\Support\Str;
    use Detection\MobileDetect;
    $detect = new MobileDetect;

    // Get the selected database from the session
    $database = session('database', 'jo');
    
    // فحص وجود البيانات
    $lesson = $lesson ?? collect();
    
    // تحديد الألوان والأيقونات للصفوف
    $gradeColors = [
        '1' => ['color' => 'linear-gradient(135deg, #ff6b6b, #ee5a52)', 'bg' => 'rgba(255, 107, 107, 0.1)', 'icon' => 'ti ti-number-1'],
        '2' => ['color' => 'linear-gradient(135deg, #4ecdc4, #44a08d)', 'bg' => 'rgba(78, 205, 196, 0.1)', 'icon' => 'ti ti-number-2'],
        '3' => ['color' => 'linear-gradient(135deg, #45b7d1, #3498db)', 'bg' => 'rgba(69, 183, 209, 0.1)', 'icon' => 'ti ti-number-3'],
        '4' => ['color' => 'linear-gradient(135deg, #f9ca24, #f0932b)', 'bg' => 'rgba(249, 202, 36, 0.1)', 'icon' => 'ti ti-number-4'],
        '5' => ['color' => 'linear-gradient(135deg, #6c5ce7, #5f3dc4)', 'bg' => 'rgba(108, 92, 231, 0.1)', 'icon' => 'ti ti-number-5'],
        '6' => ['color' => 'linear-gradient(135deg, #fd79a8, #e84393)', 'bg' => 'rgba(253, 121, 168, 0.1)', 'icon' => 'ti ti-number-6'],
        '7' => ['color' => 'linear-gradient(135deg, #00b894, #00a085)', 'bg' => 'rgba(0, 184, 148, 0.1)', 'icon' => 'ti ti-number-7'],
        '8' => ['color' => 'linear-gradient(135deg, #e17055, #d63031)', 'bg' => 'rgba(225, 112, 85, 0.1)', 'icon' => 'ti ti-number-8'],
        '9' => ['color' => 'linear-gradient(135deg, #a29bfe, #6c5ce7)', 'bg' => 'rgba(162, 155, 254, 0.1)', 'icon' => 'ti ti-number-9'],
        '10' => ['color' => 'linear-gradient(135deg, #fd7f6f, #d63031)', 'bg' => 'rgba(253, 127, 111, 0.1)', 'icon' => 'ti ti-number-10-small'],
        '11' => ['color' => 'linear-gradient(135deg, #7bed9f, #2ed573)', 'bg' => 'rgba(123, 237, 159, 0.1)', 'icon' => 'ti ti-number-11-small'],
        '12' => ['color' => 'linear-gradient(135deg, #70a1ff, #5352ed)', 'bg' => 'rgba(112, 161, 255, 0.1)', 'icon' => 'ti ti-number-12-small'],
        'default' => ['color' => 'linear-gradient(135deg, #1f36ad, #286aad)', 'bg' => 'rgba(31, 54, 173, 0.1)', 'icon' => 'ti ti-book']
    ];
@endphp

@vite([
    'resources/assets/vendor/scss/home.scss'
])

@extends('layouts.layoutFront')

@section('title', __('Our Classes'))

@section('page-style')
    @vite(['resources/assets/vendor/js/but.js'])
@endsection

@section('meta')
    <meta name="description" content="{{ __('Explore our comprehensive collection of educational classes') }}">
    <meta name="keywords" content="education, classes, learning, {{ $database }}">
    <link rel="canonical" href="{{ url()->current() }}">
@endsection

@section('content')

<!-- Hero Section -->
<section class="position-relative overflow-hidden" style="background: linear-gradient(135deg, #1f36ad 0%, #286aad 50%, #3b82f6 100%); min-height: 70vh; display: flex; align-items: center;">
  <!-- Background Effects -->
  <div class="position-absolute w-100 h-100" style="background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grain" width="100" height="100" patternUnits="userSpaceOnUse"><circle cx="50" cy="50" r="1" fill="%23ffffff" opacity="0.1"/></pattern></defs><rect width="100" height="100" fill="url(%23grain)"/></svg>'); opacity: 0.3;"></div>
  
  <!-- Floating Elements -->
  <div class="position-absolute" style="top: 15%; right: 10%; width: 120px; height: 120px; background: rgba(255,255,255,0.1); border-radius: 50%; animation: float 6s ease-in-out infinite;"></div>
  <div class="position-absolute" style="bottom: 25%; left: 15%; width: 80px; height: 80px; background: rgba(255,255,255,0.1); border-radius: 50%; animation: float 4s ease-in-out infinite reverse;"></div>
  
  <div class="container position-relative">
    <div class="row align-items-center">
      <div class="col-lg-8">
        <!-- Main Title -->
        <h1 class="display-4 fw-bold text-white mb-4" style="text-shadow: 0 4px 20px rgba(0,0,0,0.3); line-height: 1.2;">
          {{ __('welcome_school_classes') }}
        </h1>
        
        <!-- Subtitle -->
        <p class="lead text-white mb-4" style="opacity: 0.9; font-size: 1.3rem; line-height: 1.6;">
          اكتشف مجموعتنا الشاملة من الصفوف التعليمية
        </p>
        
        <!-- Stats Card -->
        <div class="d-inline-block p-4 mb-4" style="background: rgba(255,255,255,0.15); backdrop-filter: blur(20px); border: 1px solid rgba(255,255,255,0.3); border-radius: 20px;">
          <div class="row g-4 text-center">
            <div class="col-4">
              <div class="text-white">
                <div class="h3 fw-bold mb-1">{{ $lesson->count() }}</div>
                <small style="opacity: 0.8;">صف دراسي</small>
              </div>
            </div>
            <div class="col-4">
              <div class="text-white">
                <div class="h3 fw-bold mb-1">{{ $lesson->sum(function($class) { return $class->subjects ? $class->subjects->count() : 0; }) }}</div>
                <small style="opacity: 0.8;">مادة دراسية</small>
              </div>
            </div>
            <div class="col-4">
              <div class="text-white">
                <div class="h3 fw-bold mb-1">{{ $lesson->sum(function($class) { return $class->subjects ? $class->subjects->sum(function($subject) { return $subject->articles ? $subject->articles->count() : 0; }) : 0; }) }}</div>
                <small style="opacity: 0.8;">مقال تعليمي</small>
              </div>
            </div>
          </div>
        </div>
        
        @guest
        <!-- Call to Action Buttons -->
        <div class="d-flex gap-3">
          <a href="{{ route('login') }}" class="btn btn-lg" style="background: rgba(255,255,255,0.2); color: white; border: 2px solid rgba(255,255,255,0.3); border-radius: 15px; padding: 0.8rem 2rem; backdrop-filter: blur(10px);">
            <i class="ti ti-user-plus me-2"></i>{{ __('Get Started') }}
          </a>
          <a href="#classes-section" class="btn btn-lg" style="background: rgba(255,255,255,0.1); color: white; border: 2px solid rgba(255,255,255,0.2); border-radius: 15px; padding: 0.8rem 2rem; backdrop-filter: blur(10px);">
            <i class="ti ti-arrow-down me-2"></i>استعراض الصفوف
          </a>
        </div>
        @endguest
      </div>
      
      <!-- Hero Icon -->
      <div class="col-lg-4 text-center">
        <div class="position-relative">
          <!-- Main Icon Circle -->
          <div class="d-inline-flex align-items-center justify-content-center mx-auto" style="width: 200px; height: 200px; background: rgba(255,255,255,0.15); backdrop-filter: blur(20px); border: 2px solid rgba(255,255,255,0.3); border-radius: 50%; box-shadow: 0 20px 60px rgba(0,0,0,0.2);">
            <i class="ti ti-school text-white" style="font-size: 5rem;"></i>
          </div>
          
          <!-- Floating Particles -->
          <div class="position-absolute" style="top: 10%; right: 10%; width: 25px; height: 25px; background: rgba(255,255,255,0.3); border-radius: 50%; animation: float 3s ease-in-out infinite;"></div>
          <div class="position-absolute" style="bottom: 15%; left: 15%; width: 20px; height: 20px; background: rgba(255,255,255,0.3); border-radius: 50%; animation: float 4s ease-in-out infinite reverse;"></div>
          <div class="position-absolute" style="top: 30%; left: 5%; width: 15px; height: 15px; background: rgba(255,255,255,0.3); border-radius: 50%; animation: float 5s ease-in-out infinite;"></div>
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
      <li class="breadcrumb-item active" aria-current="page" style="color: #6c757d;">
        <i class="ti ti-school me-1"></i>{{ __('Classes') }}
      </li>
    </ol>
  </nav>
</div>

<!-- إعلانات (اختياري) -->
<div class="container py-4">
    @if(config('settings.google_ads_desktop_classes') || config('settings.google_ads_mobile_classes'))
        <div class="ads-container text-center">
            @if($detect->isMobile())
                {!! config('settings.google_ads_mobile_classes') !!}
            @else
                {!! config('settings.google_ads_desktop_classes') !!}
            @endif
        </div>
    @endif
</div>

    <!-- Classes Section -->
    <section id="classes-section" class="py-5">
      <div class="container">
        @if($lesson->count() > 0)
          <div class="row g-4">
            @foreach($lesson as $index => $class)
              @php
                $gradeKey = $class->grade_name ?? 'default';
                $gradeStyle = $gradeColors[$gradeKey] ?? $gradeColors['default'];
              @endphp
              
              <div class="col-12 col-md-6 col-lg-4">
                <div class="edu-card h-100" style="background: {{ $gradeStyle['bg'] }}; border: 2px solid rgba(255,255,255,0.8); border-radius: 25px; overflow: hidden; transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1); position: relative;">
                  
                  <!-- Background Pattern -->
                  <div class="position-absolute w-100 h-100" style="background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="dots" width="20" height="20" patternUnits="userSpaceOnUse"><circle cx="10" cy="10" r="1" fill="%23ffffff" opacity="0.1"/></pattern></defs><rect width="100" height="100" fill="url(%23dots)"/></svg>'); top: 0; left: 0;"></div>
                  
                  <!-- Card Header -->
                  <div class="p-4 text-center position-relative" style="background: {{ $gradeStyle['color'] }}; color: white;">
                    <!-- Grade Icon -->
                    <div class="edu-icon-circle mx-auto mb-3" style="width: 80px; height: 80px; background: rgba(255,255,255,0.2); border: 3px solid rgba(255,255,255,0.3); border-radius: 50%; display: flex; align-items: center; justify-content: center; backdrop-filter: blur(10px);">
                      <i class="{{ $gradeStyle['icon'] }}" style="font-size: 2.5rem;"></i>
                    </div>
                    
                    <!-- Grade Name -->
                    <h4 class="fw-bold mb-2" style="text-shadow: 0 2px 10px rgba(0,0,0,0.3);">{{ $class->lesson_name }}</h4>
                    <p class="mb-0" style="opacity: 0.9; font-size: 0.95rem;">الصف {{ $class->grade_name ?? 'الدراسي' }}</p>
                  </div>
                  
                  <!-- Card Body -->
                  <div class="p-4 position-relative">
                    <!-- Statistics Grid -->
                    <div class="row g-3 mb-4">
                      <div class="col-4 text-center">
                        <div class="p-3" style="background: rgba(255,255,255,0.7); border-radius: 15px; backdrop-filter: blur(10px);">
                          <div class="fw-bold h5 mb-1" style="color: #1f36ad;">{{ $class->subjects ? $class->subjects->count() : 0 }}</div>
                          <small class="text-muted">مادة</small>
                        </div>
                      </div>
                      <div class="col-4 text-center">
                        <div class="p-3" style="background: rgba(255,255,255,0.7); border-radius: 15px; backdrop-filter: blur(10px);">
                          <div class="fw-bold h5 mb-1" style="color: #1f36ad;">{{ $class->subjects ? $class->subjects->sum(function($subject) { return $subject->articles ? $subject->articles->count() : 0; }) : 0 }}</div>
                          <small class="text-muted">مقال</small>
                        </div>
                      </div>
                      <div class="col-4 text-center">
                        <div class="p-3" style="background: rgba(255,255,255,0.7); border-radius: 15px; backdrop-filter: blur(10px);">
                          <div class="fw-bold h5 mb-1" style="color: #1f36ad;">{{ $class->subjects ? $class->subjects->sum(function($subject) { return $subject->articles ? $subject->articles->sum(function($article) { return $article->files ? $article->files->count() : 0; }) : 0; }) : 0 }}</div>
                          <small class="text-muted">ملف</small>
                        </div>
                      </div>
                    </div>
                    
                    <!-- Progress Section -->
                    <div class="mb-4">
                      <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="fw-medium" style="color: #1f36ad;">نسبة الإنجاز</span>
                        <span class="fw-bold" style="color: #1f36ad;">{{ rand(70, 95) }}%</span>
                      </div>
                      <div class="progress" style="height: 8px; border-radius: 10px; background: rgba(255,255,255,0.5);">
                        <div class="progress-bar" role="progressbar" style="width: {{ rand(70, 95) }}%; background: {{ $gradeStyle['color'] }}; border-radius: 10px;" aria-valuenow="{{ rand(70, 95) }}" aria-valuemin="0" aria-valuemax="100"></div>
                      </div>
                    </div>
                    
                    <!-- Action Button -->
                    <a href="{{ route('frontend.lesson.show', ['database' => session('database', 'jo'), 'id' => $class->id]) }}" class="btn w-100 py-3" style="background: {{ $gradeStyle['color'] }}; color: white; border: none; border-radius: 15px; font-weight: 600; font-size: 1.1rem; box-shadow: 0 8px 25px rgba(0,0,0,0.15); transition: all 0.3s ease;">
                      <i class="ti ti-arrow-right me-2"></i>استعراض المواد
                    </a>
                  </div>
                  
                  <!-- Floating Elements -->
                  <div class="position-absolute" style="top: 15px; right: 15px; width: 20px; height: 20px; background: rgba(255,255,255,0.3); border-radius: 50%; animation: float 4s ease-in-out infinite;"></div>
                  <div class="position-absolute" style="bottom: 20px; left: 20px; width: 15px; height: 15px; background: rgba(255,255,255,0.3); border-radius: 50%; animation: float 6s ease-in-out infinite reverse;"></div>
                </div>
              </div>
            @endforeach
          </div>
        @else
          <!-- Empty State -->
          <div class="text-center py-5">
            <div class="mb-4">
              <div class="d-inline-flex align-items-center justify-content-center" style="width: 150px; height: 150px; background: linear-gradient(135deg, #f8f9fa, #e9ecef); border-radius: 50%; box-shadow: 0 10px 30px rgba(0,0,0,0.1);">
                <i class="ti ti-school" style="font-size: 4rem; color: #6c757d;"></i>
              </div>
            </div>
            <h3 class="fw-bold mb-3" style="color: #1f36ad;">لا توجد صفوف متاحة حالياً</h3>
            <p class="text-muted mb-4 lead">سيتم إضافة الصفوف قريباً. يرجى المحاولة لاحقاً.</p>
            <a href="{{ route('home') }}" class="btn btn-lg" style="background: linear-gradient(135deg, #1f36ad, #286aad); color: white; border: none; border-radius: 15px; padding: 0.8rem 2rem; box-shadow: 0 8px 25px rgba(31, 54, 173, 0.3);">
              <i class="ti ti-arrow-left me-2"></i>العودة للصفحة الرئيسية
            </a>
          </div>
        @endif
      </div>
    </section>

<!-- إعلانات ثانية (اختياري) -->
<div class="container py-4">
    @if(config('settings.google_ads_desktop_classes_2') || config('settings.google_ads_mobile_classes_2'))
        <div class="ads-container text-center">
            @if($detect->isMobile())
                {!! config('settings.google_ads_mobile_classes_2') !!}
            @else
                {!! config('settings.google_ads_desktop_classes_2') !!}
            @endif
        </div>
    @endif
</div>
@endsection
