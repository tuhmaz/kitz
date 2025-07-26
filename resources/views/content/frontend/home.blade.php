@php
$configData = Helper::appClasses();
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Detection\MobileDetect;
$detect = new MobileDetect;
$colors = ['primary', 'secondary', 'success', 'danger', 'warning', 'info', 'dark'];
$colorCount = count($colors);

$icons = [
'1' => 'ti ti-number-0',
'2' => 'ti ti-number-1',
'3' => 'ti ti-number-2',
'4' => 'ti ti-number-3',
'5' => 'ti ti-number-4',
'6' => 'ti ti-number-5',
'7' => 'ti ti-number-6',
'8' => 'ti ti-number-7',
'9' => 'ti ti-number-8',
'10' => 'ti ti-number-9',
'11' => 'ti ti-number-10-small',
'12' => 'ti ti-number-11-small',
'13' => 'ti ti-number-12-small',
'default' => 'ti ti-book',
];
@endphp

@extends('layouts/layoutFront')

@section('title', __('Home'))

@section('page-style')
@vite([
  'resources/assets/vendor/scss/but.scss',
  'resources/assets/vendor/scss/calendar.scss',
  'resources/assets/vendor/scss/home.scss'
])
@endsection

@section('page-script')
@vite([
  'resources/assets/vendor/js/filterhome.js',
  'resources/assets/vendor/js/but.js',
  'resources/assets/vendor/js/calendar.js'
])

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Add smooth transition to progress bars
    const progressBars = document.querySelectorAll('.edu-progress-fill');
    progressBars.forEach(bar => {
        bar.style.transition = 'width 0.5s ease-in-out';
    });

    // Function to update statistics
    async function updateStatistics() {
        try {
            const response = await fetch('/api/statistics');
            const data = await response.json();

            // Update daily activity
            if (document.querySelector('.daily-hours')) {
                document.querySelector('.daily-hours').textContent = data.daily_activity.hours;
                document.querySelector('.daily-progress').style.width = `${data.daily_activity.percentage}%`;
            }

            // Update weekly goal
            if (document.querySelector('.weekly-percentage')) {
                document.querySelector('.weekly-percentage').textContent = `${Math.round(data.weekly_goal.percentage)}%`;
                document.querySelector('.weekly-progress').style.width = `${data.weekly_goal.percentage}%`;
            }

            // Update exam days
            if (document.querySelector('.exam-days')) {
                document.querySelector('.exam-days').textContent = data.exam_days_left;
                const examProgress = (data.exam_days_left / 30) * 100;
                document.querySelector('.exam-progress').style.width = `${examProgress}%`;
            }

            // Update file availability
            if (document.querySelector('.file-availability-percentage')) {
                document.querySelector('.file-availability-percentage').textContent = `${Math.round(data.file_availability.percentage)}%`;
                document.querySelector('.file-availability-progress').style.width = `${data.file_availability.percentage}%`;

                // Update file count display
                if (document.querySelector('.file-count-display')) {
                    document.querySelector('.file-count-display').textContent = `${data.file_availability.total_files} ملف متاح`;
                }
            }

            // Update achievement points
            if (document.querySelector('.achievement-points')) {
                document.querySelector('.achievement-points').textContent = data.achievement_points;
                const achievementProgress = (data.achievement_points / 5000) * 100;
                document.querySelector('.achievement-progress').style.width = `${achievementProgress}%`;
            }
        } catch (error) {
            console.error('Error updating statistics:', error);
        }
    }

    // Update statistics immediately and every 5 minutes
    updateStatistics();
    setInterval(updateStatistics, 5 * 60 * 1000);
});
</script>
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
              مرحباً بك، {{ Auth::check() ? (Auth::user()->first_name ?? Auth::user()->name ?? 'الطالب') : 'الزائر' }}! 👋
            </h1>
            <p class="lead mb-4" style="color: rgba(255, 255, 255, 0.9); font-size: 1.25rem;">
              استكمل رحلتك التعليمية واكتشف المحتوى الجديد مع أحدث الأدوات التفاعلية
            </p>
            <div class="d-flex flex-wrap gap-3 mb-4">
              <a href="#courses" class="btn btn-light btn-lg px-4 py-3 rounded-pill" style="font-weight: 600;">
                <i class="ti ti-book-2 me-2"></i>استكشف الدورات
              </a>
              <a href="{{ route('login') }}" class="btn btn-outline-light btn-lg px-4 py-3 rounded-pill" style="font-weight: 600;">
                <i class="ti ti-chart-line me-2"></i>تتبع التقدم
              </a>
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
                          <i class="ti ti-book-2 text-white" style="font-size: 1.2rem;"></i>
                        </div>
                        <p class="small mb-1" style="color: rgba(255,255,255,0.9); font-size: 0.75rem;">الدورات</p>
                        <p class="h5 fw-bold mb-0" style="color: white;">12</p>
                      </div>
                    </div>
                    <div class="col-4">
                      <div class="text-center">
                        <div class="edu-icon-circle mx-auto mb-2" style="background: rgba(255,255,255,0.2); backdrop-filter: blur(10px); border: 1px solid rgba(255,255,255,0.3); width: 3rem; height: 3rem;">
                          <i class="ti ti-trophy text-white" style="font-size: 1.2rem;"></i>
                        </div>
                        <p class="small mb-1" style="color: rgba(255,255,255,0.9); font-size: 0.75rem;">الإنجازات</p>
                        <p class="h5 fw-bold mb-0" style="color: white;">8</p>
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

  <!-- Quick Access Cards -->
  <section class="edu-quick-access">
    <div class="container">
      <div class="text-center mb-5">
        <h2 class="fw-bold text-white mb-3">إحصائياتك التعليمية</h2>
        <p class="text-white-50 mb-0">تتبع تقدمك وإنجازاتك في رحلتك التعليمية</p>
      </div>
      <div class="row g-4">
        <div class="col-lg-3 col-md-6">
          <div class="edu-stats-card">
            <div class="edu-icon-circle mb-3" style="background: linear-gradient(135deg, var(--edu-primary), var(--edu-secondary)); width: 3.5rem; height: 3.5rem;">
              <i class="ti ti-activity text-white" style="font-size: 1.5rem;"></i>
            </div>
            <h5 class="fw-semibold mb-2" style="color: var(--edu-primary);">النشاط اليومي</h5>
            <p class="h2 fw-bold mb-1 daily-hours" style="color: var(--edu-dark);">0</p>
            <p class="small text-muted mb-0">ساعات تعلم</p>
            <div class="edu-progress-bar mt-3">
              <div class="edu-progress-fill daily-progress" style="width: 0%;"></div>
            </div>
          </div>
        </div>
        <div class="col-lg-3 col-md-6">
          <div class="edu-stats-card">
            <div class="edu-icon-circle mb-3" style="background: linear-gradient(135deg, var(--edu-success), var(--edu-info)); width: 3.5rem; height: 3.5rem;">
              <i class="ti ti-target text-white" style="font-size: 1.5rem;"></i>
            </div>
            <h5 class="fw-semibold mb-2" style="color: var(--edu-success);">الهدف الأسبوعي</h5>
            <p class="h2 fw-bold mb-1 weekly-percentage" style="color: var(--edu-dark);">0%</p>
            <p class="small text-muted mb-0">مكتمل</p>
            <div class="edu-progress-bar mt-3">
              <div class="edu-progress-fill weekly-progress" style="width: 0%; background: linear-gradient(90deg, var(--edu-success), var(--edu-info));"></div>
            </div>
          </div>
        </div>
        <div class="col-lg-3 col-md-6">
          <div class="edu-stats-card">
            <div class="edu-icon-circle mb-3" style="background: linear-gradient(135deg, var(--edu-warning), var(--edu-accent)); width: 3.5rem; height: 3.5rem;">
              <i class="ti ti-file text-white" style="font-size: 1.5rem;"></i>
            </div>
            <h5 class="fw-semibold mb-2" style="color: var(--edu-warning);">ملفات الصفوف</h5>
            <p class="h2 fw-bold mb-1 file-availability-percentage" style="color: var(--edu-dark);">0%</p>
            <p class="small text-muted mb-1 file-count-display">0 ملف متاح</p>
            <div class="edu-progress-bar mt-3">
              <div class="edu-progress-fill file-availability-progress" style="width: 0%; background: linear-gradient(90deg, var(--edu-warning), var(--edu-accent));"></div>
            </div>
          </div>
        </div>

        <div class="col-lg-3 col-md-6">
          <div class="edu-stats-card">
            <div class="edu-icon-circle mb-3" style="background: linear-gradient(135deg, var(--edu-secondary), var(--edu-primary)); width: 3.5rem; height: 3.5rem;">
              <i class="ti ti-bolt text-white" style="font-size: 1.5rem;"></i>
            </div>
            <h5 class="fw-semibold mb-2" style="color: var(--edu-secondary);">نقاط الإنجاز</h5>
            <p class="h2 fw-bold mb-1 achievement-points" style="color: var(--edu-dark);">0</p>
            <p class="small text-muted mb-0">نقطة</p>
            <div class="edu-progress-bar mt-3">
              <div class="edu-progress-fill achievement-progress" style="width: 0%; background: linear-gradient(90deg, var(--edu-secondary), var(--edu-primary));"></div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- Search Section -->
  <section class="py-5" style="background: var(--edu-gray-50);">
    <div class="container">
      <div class="text-center mb-5">
        <h2 class="edu-section-title">بحث سريع</h2>
        <p class="text-muted mb-0">ابحث عن المواد والملفات التي تحتاجها</p>
      </div>
      <div class="edu-card p-5">
        <div class="row align-items-center mb-4">
          <div class="col-md-8">
            <h4 class="fw-bold mb-2" style="color: var(--edu-primary);">ابحث عن ما تريد</h4>
            <p class="text-muted mb-0">اختر الصف والمادة للعثور على المحتوى المناسب</p>
          </div>
          <div class="col-md-4 text-end">
            <div class="edu-icon-circle d-inline-flex" style="background: linear-gradient(135deg, var(--edu-primary), var(--edu-secondary)); width: 4rem; height: 4rem;">
              <i class="ti ti-search text-white" style="font-size: 1.5rem;"></i>
            </div>
          </div>
        </div>
        <form id="filter-form" method="GET" action="{{ route('files.filter') }}">
          @csrf
          <div class="row g-4">
            <div class="col-lg-3 col-md-6">
              <label for="class-select" class="form-label fw-semibold" style="color: var(--edu-dark);">اختر الصف</label>
              <select id="class-select" name="class_id" class="form-select border-2" style="border-color: var(--edu-gray-300); border-radius: 0.75rem; padding: 0.75rem 1rem;">
                <option value="">اختر الصف</option>
                @foreach($classes as $class)
                <option value="{{ $class->id }}">{{ $class->grade_name }}</option>
                @endforeach
              </select>
            </div>
            <div class="col-lg-3 col-md-6">
              <label for="subject-select" class="form-label fw-semibold" style="color: var(--edu-dark);">اختر المادة</label>
              <select id="subject-select" name="subject_id" class="form-select border-2" style="border-color: var(--edu-gray-300); border-radius: 0.75rem; padding: 0.75rem 1rem;" disabled>
                <option value="">اختر المادة</option>
              </select>
            </div>
            <div class="col-lg-3 col-md-6">
              <label for="semester-select" class="form-label fw-semibold" style="color: var(--edu-dark);">اختر الفصل</label>
              <select id="semester-select" name="semester_id" class="form-select border-2" style="border-color: var(--edu-gray-300); border-radius: 0.75rem; padding: 0.75rem 1rem;" disabled>
                <option value="">اختر الفصل</option>
              </select>
            </div>
            <div class="col-lg-3 col-md-6">
              <label for="file_category" class="form-label fw-semibold" style="color: var(--edu-dark);">نوع الملف</label>
              <select class="form-select border-2" id="file_category" name="file_category" style="border-color: var(--edu-gray-300); border-radius: 0.75rem; padding: 0.75rem 1rem;">
                <option value="">اختر نوع الملف</option>
                <option value="plans">خطط دراسية</option>
                <option value="papers">أوراق عمل</option>
                <option value="tests">اختبارات</option>
                <option value="books">كتب</option>
              </select>
            </div>
          </div>
          <div class="text-center mt-5">
            <button type="submit" class="btn btn-primary btn-lg px-5 py-3 me-3 rounded-pill" style="font-weight: 600; box-shadow: 0 8px 25px -8px rgba(31, 54, 173, 0.4);">
              <i class="ti ti-search me-2"></i>بحث في الملفات
            </button>
            <button type="reset" class="btn btn-outline-primary btn-lg px-5 py-3 rounded-pill" style="font-weight: 600; border-width: 2px;">
              <i class="ti ti-refresh me-2"></i>إعادة تعيين
            </button>
          </div>
        </form>
      </div>
    </div>
  </section>

  <!-- Main Content Section -->
  <section class="py-5" style="background: var(--edu-light);">
    <div class="container">
      <div class="row g-4">

        <!-- Grades Section -->
        <div class="col-lg-8">
          <div class="d-flex align-items-center justify-content-between mb-5">
            <div>
              <h2 class="edu-section-title mb-2" style="color: var(--edu-primary);">صفوفي الدراسية</h2>
              <p class="text-muted mb-0">استعرض وادرس محتوى صفوفك الدراسية</p>
            </div>
            <button class="btn btn-outline-primary px-4 py-2 rounded-pill" style="font-weight: 600; border-width: 2px;">
              <i class="ti ti-star me-2"></i>عرض المفضلة
            </button>
          </div>

          <!-- Ads Section -->
          @if(config('settings.google_ads_desktop_home') || config('settings.google_ads_mobile_home'))
          <div class="mb-4">
            <div class="edu-card p-3 text-center">
              @if($detect->isMobile())
                {!! config('settings.google_ads_mobile_home') !!}
              @else
                {!! config('settings.google_ads_desktop_home') !!}
              @endif
            </div>
          </div>
          @endif

          <div class="row g-4">
            @forelse($classes as $index => $class)
            @php
              $icon = $icons[$class->grade_level] ?? $icons['default'];
              $routeName = request()->is('dashboard/*') ? 'dashboard.class.show' : 'frontend.lesson.show';
              $color = $colors[$index % $colorCount];
              $database = session('database', 'jo');
              
              // Get real file count for this class through articles
              $fileCount = \App\Models\File::on($database)
                  ->whereHas('article', function($query) use ($class) {
                      $query->where('grade_level', $class->id);
                  })
                  ->count();
              
              // Calculate completion percentage (placeholder until user progress system is implemented)
              $totalFiles = $fileCount;
              $completedFiles = 0;
              if (Auth::check() && $totalFiles > 0) {
                  // TODO: Implement user progress tracking system
                  // For now, show random progress for demonstration
                  $completedFiles = rand(0, min($totalFiles, 10));
              }
              $completionPercentage = $totalFiles > 0 ? round(($completedFiles / $totalFiles) * 100) : 0;
              
              // Determine grade class for styling
              $gradeClass = 'grade-' . $class->id;
              if (str_contains(strtolower($class->grade_name), 'رياض')) {
                  $gradeClass = 'grade-kg';
              } elseif ($class->id >= 12) {
                  $gradeClass = 'grade-' . $class->id; // للصفوف 12 و 13
              }
            @endphp
            <div class="col-xl-6 col-lg-6 col-md-6">
              <a href="{{ route($routeName, ['database' => $database, 'id' => $class->id]) }}" class="text-decoration-none">
                <div class="edu-grade-card {{ $gradeClass }} p-4">
                  <div class="d-flex align-items-center justify-content-between mb-3">
                    <div class="d-flex align-items-center">
                      <div class="edu-icon-circle me-3" style="background: var(--edu-{{ $color === 'primary' ? 'blue' : ($color === 'success' ? 'green' : 'purple') }}); width: 3rem; height: 3rem;">
                        <i class="{{ $icon }} text-white"></i>
                      </div>
                      <div>
                        <h5 class="fw-bold mb-1" style="color: var(--edu-dark);">{{ $class->grade_name }}</h5>
                        <p class="text-muted small mb-0">{{ $fileCount }} ملف متاح</p>
                      </div>
                    </div>
                    <span class="badge bg-success">{{ $completionPercentage }}%</span>
                  </div>
                  <div class="edu-progress-bar mb-2">
                    <div class="edu-progress-fill" style="width: {{ $completionPercentage }}%;"></div>
                  </div>
                  <p class="text-muted small mb-0">مكتمل {{ $completionPercentage }}%</p>
                </div>
              </a>
            </div>
            @empty
            <div class="col-12">
              <div class="edu-card p-4 text-center">
                <i class="ti ti-book-off text-muted mb-3" style="font-size: 3rem;"></i>
                <p class="text-muted mb-0">{{ __('No classes available.') }}</p>
              </div>
            </div>
            @endforelse
          </div>

          <!-- Additional Ads -->
          @if(config('settings.google_ads_desktop_home_2') || config('settings.google_ads_mobile_home_2'))
          <div class="mt-4">
            <div class="edu-card p-3 text-center">
              @if($detect->isMobile())
                {!! config('settings.google_ads_mobile_home_2') !!}
              @else
                {!! config('settings.google_ads_desktop_home_2') !!}
              @endif
            </div>
          </div>
          @endif
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
          <!-- Calendar -->
          <div class="calendar-wrapper mb-4">
            <div class="calendar">
              <div class="month-year">
                <button class="nav-btn" onclick="prevMonth()">
                  <i class="fas fa-chevron-right"></i>
                </button>
                <span id="currentMonthYear"></span>
                <button class="nav-btn" onclick="nextMonth()">
                  <i class="fas fa-chevron-left"></i>
                </button>
              </div>
              <div class="days">
                @foreach(['الأحد', 'الإثنين', 'الثلاثاء', 'الأربعاء', 'الخميس', 'الجمعة', 'السبت'] as $day)
                <div class="day-label">{{ $day }}</div>
                @endforeach

                @foreach($calendar as $date => $events)
                @php
                $dateObj = \Carbon\Carbon::parse($date);
                $isToday = $dateObj->isToday();
                $hasEvents = count($events) > 0;
                $isDull = $dateObj->month != $currentMonth;
                @endphp

                <div class="day {{ $isToday ? 'today' : '' }}
                                            {{ $hasEvents ? 'event' : '' }}
                                            {{ $isDull ? 'dull' : '' }}"
                  @if($hasEvents)
                  data-bs-toggle="modal"
                  data-bs-target="#eventModal"
                  data-title="{{ $events[0]['title'] }}"
                  data-description="{{ $events[0]['description'] }}"
                  data-date="{{ $date }}"
                  @endif>
                  <div class="content">{{ $dateObj->day }}</div>
                </div>
                @endforeach
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- Recent Activity Section -->
  <section class="py-5" style="background: white;">
    <div class="container">
      <h2 class="edu-section-title text-center mb-5">نشاطك الأخير</h2>

      <div class="row g-4">
        <!-- Recent Articles -->
        <div class="col-lg-6">
          <h3 class="h4 fw-semibold mb-4 d-flex align-items-center" style="color: var(--edu-dark);">
            <i class="ti ti-book-2 me-2" style="color: var(--edu-blue);"></i>
            المقالات الأخيرة
          </h3>
          <div class="row g-3">
            @if(isset($articles) && $articles->count() > 0)
              @foreach($articles->take(3) as $article)
              <div class="col-12">
                <a href="{{ route('frontend.articles.show', ['database' => session('database', 'jo'), 'article' => $article->id]) }}" class="text-decoration-none">
                  <div class="edu-article-card">
                    <div class="d-flex align-items-start">
                      <div class="edu-icon-circle me-3" style="background: var(--edu-blue); width: 3rem; height: 3rem;">
                        <i class="ti ti-article text-white"></i>
                      </div>
                      <div class="flex-grow-1">
                        <h6 class="fw-semibold mb-1" style="color: var(--edu-dark);">{{ $article->title ?? 'عنوان المقال' }}</h6>
                        <p class="text-muted small mb-2">{{ Str::limit(strip_tags($article->content ?? $article->description ?? 'محتوى المقال'), 80) }}</p>
                        <div class="d-flex justify-content-between align-items-center">
                          <p class="text-muted small mb-0">{{ isset($article->created_at) ? $article->created_at->diffForHumans() : 'منذ ساعة' }}</p>
                          <span class="badge bg-primary-subtle text-primary">اقرأ المزيد</span>
                        </div>
                      </div>
                    </div>
                  </div>
                </a>
              </div>
              @endforeach
            @else
              @for($i = 0; $i < 3; $i++)
              <div class="col-12">
                <a href="#" class="text-decoration-none">
                  <div class="edu-article-card">
                    <div class="d-flex align-items-start">
                      <div class="edu-icon-circle me-3" style="background: var(--edu-blue); width: 3rem; height: 3rem;">
                        <i class="ti ti-article text-white"></i>
                      </div>
                      <div class="flex-grow-1">
                        <h6 class="fw-semibold mb-1" style="color: var(--edu-dark);">مقال تعليمي {{ $i + 1 }}</h6>
                        <p class="text-muted small mb-2">محتوى تعليمي مفيد للطلاب في جميع المراحل الدراسية</p>
                        <div class="d-flex justify-content-between align-items-center">
                          <p class="text-muted small mb-0">منذ {{ $i + 1 }} ساعة</p>
                          <span class="badge bg-primary-subtle text-primary">اقرأ المزيد</span>
                        </div>
                      </div>
                    </div>
                  </div>
                </a>
              </div>
              @endfor
            @endif
          </div>
        </div>

        <!-- Recent News -->
        <div class="col-lg-6">
          <h3 class="h4 fw-semibold mb-4 d-flex align-items-center" style="color: var(--edu-dark);">
            <i class="ti ti-trending-up me-2" style="color: var(--edu-purple);"></i>
            الأخبار الحديثة
          </h3>
          <div class="row g-3">
            @if(isset($news) && $news->count() > 0)
              @foreach($news->take(3) as $newsItem)
              <div class="col-12">
                <a href="{{ route('content.frontend.news.show', ['database' => session('database', 'jo'), 'id' => $newsItem->id]) }}" class="text-decoration-none">
                  <div class="edu-article-card">
                    <div class="d-flex align-items-start">
                      <div class="edu-icon-circle me-3" style="background: var(--edu-purple); width: 3rem; height: 3rem; overflow: hidden;">
                        @if($newsItem->image)
                          <img src="{{ Storage::url($newsItem->image) }}" alt="{{ $newsItem->title }}" class="w-100 h-100 object-fit-cover">
                        @else
                          <i class="ti ti-news text-white"></i>
                        @endif
                      </div>
                      <div class="flex-grow-1">
                        <h6 class="fw-semibold mb-1" style="color: var(--edu-dark);">{{ $newsItem->title }}</h6>
                        <p class="text-muted small mb-2">{{ Str::limit(strip_tags($newsItem->description), 80) }}</p>
                        <div class="d-flex justify-content-between align-items-center">
                          <p class="text-muted small mb-0">{{ $newsItem->created_at->diffForHumans() }}</p>
                          <span class="badge bg-primary-subtle text-primary">اقرأ المزيد</span>
                        </div>
                      </div>
                    </div>
                  </div>
                </a>
              </div>
              @endforeach
            @else
              @for($i = 0; $i < 3; $i++)
              <div class="col-12">
                <div class="edu-article-card">
                  <div class="d-flex align-items-start">
                    <div class="edu-icon-circle me-3" style="background: var(--edu-purple); width: 3rem; height: 3rem; overflow: hidden;">
                      <i class="ti ti-news text-white"></i>
                    </div>
                    <div class="flex-grow-1">
                      <h6 class="fw-semibold mb-1" style="color: var(--edu-dark);">خبر تعليمي {{ $i + 1 }}</h6>
                      <p class="text-muted small mb-2">أحدث الأخبار التعليمية والتطورات في المناهج الدراسية</p>
                      <p class="text-muted small mb-0">منذ {{ $i + 2 }} ساعات</p>
                    </div>
                  </div>
                </div>
              </div>
              @endfor
            @endif
          </div>
        </div>
      </div>
    </div>
  </section>

</div>

<!-- Event Modal -->
<div class="modal fade" id="eventModal" tabindex="-1" aria-labelledby="eventModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="eventModalLabel"></h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <p id="eventDescription"></p>
        <p id="eventDate" class="text-muted"></p>
      </div>
    </div>
  </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
  // Calendar functionality
  const calendarEl = document.getElementById('calendar');
  if (calendarEl) {
    const calendar = new FullCalendar.Calendar(calendarEl, {
      initialView: 'dayGridMonth',
      locale: 'ar',
      events: {
        url: '/dashboard/calendar-events',
        method: 'GET',
        failure: function(error) {
          console.error('Failed to fetch events:', error);
        }
      },
      eventDidMount: function(info) {
        console.log('Rendered event:', info.event);
      }
    });
    calendar.render();
  }

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
  document.querySelectorAll('.edu-card, .edu-grade-card, .edu-article-card').forEach(card => {
    card.style.opacity = '0';
    card.style.transform = 'translateY(20px)';
    card.style.transition = 'all 0.6s ease';
    observer.observe(card);
  });
});
</script>
@endpush

@endsection
