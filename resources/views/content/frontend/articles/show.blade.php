@php
$configData = Helper::appClasses();
use Detection\MobileDetect;
$detect = new MobileDetect;
use Illuminate\Support\Str;
$database = session('database', 'jo');
$pageTitle = $article->title;

// تحديد نوع الملف والأيقونة
$file = $article->files->first();
$fileType = $file ? $file->file_type : 'default';

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
    'icon' => 'ti ti-article',
    'color' => 'linear-gradient(135deg, #6366f1, #4f46e5)',
    'bg' => 'rgba(99, 102, 241, 0.1)',
    'text' => 'مقال'
  ]
};
@endphp

@vite([
    'resources/assets/vendor/scss/home.scss'
])

@extends('layouts/layoutFront')

@section('title', $pageTitle)
@section('meta_title', $pageTitle . ' - ' . ($article->meta_title ?? config('settings.meta_title')))

@section('meta')
<meta name="keywords" content="{{ implode(',', $article->keywords->pluck('keyword')->toArray()) }}">

<meta name="description" content="{{ $article->meta_description }}">

<link rel="canonical" href="{{ url()->current() }}">
<meta property="og:title" content="{{ $article->title }}" />
<meta property="og:description" content="{{ $article->meta_description }}" />
<meta property="og:type" content="article" />
<meta property="og:url" content="{{ url()->current() }}" />
<meta property="og:image" content="{{ $article->image_url ?? asset('assets/img/front-pages/icons/articles_default_image.jpg') }}" />
<meta property="og:image:width" content="800" />
<meta property="og:image:height" content="600" />
<meta property="og:locale" content="ar_AR" />
<meta property="og:site_name" content="{{ config('settings.site_name') ? config('settings.site_name') : 'site_name' }}" />
<meta property="article:published_time" content="{{ $article->created_at->toIso8601String() }}" />
<meta property="article:modified_time" content="{{ $article->updated_at->toIso8601String() }}" />
@if ($author)
<meta property="article:author" content="{{ $author->name }}" />
@else
<meta property="article:author" content="Unknown Author" />
@endif

<meta property="article:section" content="{{ $subject->subject_name }}" />
<meta property="article:tag" content="{{ implode(',', $article->keywords->pluck('keyword')->toArray()) }}" />

<meta name="twitter:card" content="summary_large_image" />
<meta name="twitter:title" content="{{ $article->title }}" />
<meta name="twitter:description" content="{{ $article->meta_description }}" />
<meta name="twitter:image" content="{{ $article->image_url ?? asset('assets/img/front-pages/icons/articles_default_image.jpg') }}" />
<meta name="twitter:site" content="{{ config('settings.twitter') }}" />
@if ($author && $author->twitter_handle)
<meta name="twitter:creator" content="{{ $author->twitter_handle }}" />
@else
<meta name="twitter:creator" content="@YourDefaultTwitterHandle" />
@endif
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
      <div class="col-lg-10 mx-auto">
        <div class="row align-items-center">
          <!-- Article Info -->
          <div class="col-lg-8">
            <!-- File Type Badge -->
            <div class="mb-3">
              <span class="badge px-3 py-2" style="background: {{ $fileConfig['bg'] }}; color: #1e293b; font-size: 0.9rem; border-radius: 20px; border: 1px solid rgba(255,255,255,0.3);">
                <i class="{{ $fileConfig['icon'] }} me-2"></i>
                {{ $fileConfig['text'] }}
              </span>
            </div>

            <!-- Article Title -->
            <h1 class="display-5 fw-bold text-white mb-3" style="text-shadow: 0 4px 20px rgba(0,0,0,0.3); line-height: 1.2;">
              {{ $article->title }}
            </h1>

            <!-- Article Meta -->
            <div class="d-flex flex-wrap align-items-center gap-4 mb-4" style="color: rgba(255,255,255,0.9);">
              <div class="d-flex align-items-center">
                <i class="ti ti-book-2 me-2"></i>
                <span>{{ $subject->subject_name }}</span>
              </div>
              <div class="d-flex align-items-center">
                <i class="ti ti-calendar me-2"></i>
                <span>{{ $semester->semester_name }}</span>
              </div>
              <div class="d-flex align-items-center">
                <i class="ti ti-clock me-2"></i>
                <span>{{ $article->created_at->format('M d, Y') }}</span>
              </div>
              @if($article->visit_count > 0)
              <div class="d-flex align-items-center">
                <i class="ti ti-eye me-2"></i>
                <span>{{ number_format($article->visit_count) }} مشاهدة</span>
              </div>
              @endif
            </div>

            <!-- Article Description -->
            @if($article->meta_description)
            <p class="lead text-white mb-4" style="opacity: 0.9; font-size: 1.1rem; line-height: 1.6;">
              {{ Str::limit($article->meta_description, 150) }}
            </p>
            @endif
          </div>

          <!-- File Icon -->
          <div class="col-lg-4 text-center">
            <div class="position-relative">
              <!-- Main Icon Circle -->
              <div class="d-inline-flex align-items-center justify-content-center mx-auto" style="width: 150px; height: 150px; background: rgba(255,255,255,0.15); backdrop-filter: blur(20px); border: 2px solid rgba(255,255,255,0.3); border-radius: 50%; box-shadow: 0 20px 60px rgba(0,0,0,0.2);">
                <i class="{{ $fileConfig['icon'] }} text-white" style="font-size: 4rem;"></i>
              </div>

              <!-- Floating Particles -->
              <div class="position-absolute" style="top: 10%; right: 10%; width: 20px; height: 20px; background: rgba(255,255,255,0.3); border-radius: 50%; animation: float 3s ease-in-out infinite;"></div>
              <div class="position-absolute" style="bottom: 15%; left: 15%; width: 15px; height: 15px; background: rgba(255,255,255,0.3); border-radius: 50%; animation: float 4s ease-in-out infinite reverse;"></div>
              <div class="position-absolute" style="top: 30%; left: 5%; width: 12px; height: 12px; background: rgba(255,255,255,0.3); border-radius: 50%; animation: float 5s ease-in-out infinite;"></div>
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
        <a href="{{ route('frontend.lesson.index', ['database' => $database ?? session('database', 'default_database')]) }}" class="text-decoration-none" style="color: #1f36ad;">
          {{ __('Classes') }}
        </a>
      </li>
      <li class="breadcrumb-item">
        <a href="{{ route('frontend.lesson.show', ['database' => $database ?? session('database', 'default_database'), 'id' => $subject->schoolClass->id]) }}" class="text-decoration-none" style="color: #1f36ad;">
          {{ $subject->schoolClass->grade_name }}
        </a>
      </li>
      <li class="breadcrumb-item">
        <a href="{{ route('frontend.subjects.show', ['database' => $database ?? session('database', 'default_database'), 'subject' => $subject->id]) }}" class="text-decoration-none" style="color: #1f36ad;">
          {{ $subject->subject_name }}
        </a>
      </li>
      <li class="breadcrumb-item">
        <a href="{{ route('frontend.subject.articles', ['database' => $database ?? session('database', 'default_database'), 'subject' => $subject->id, 'semester' => $semester->id, 'category' => $category]) }}" class="text-decoration-none" style="color: #1f36ad;">
          {{ __($category) }} - {{ $semester->semester_name }}
        </a>
      </li>
      <li class="breadcrumb-item active" aria-current="page" style="color: #6c757d;">
        {{ Str::limit($article->title, 50) }}
      </li>
    </ol>
  </nav>
</div>

<!-- Main Content Section -->
<section class="py-5" style="background: #f8fafc;">
  <!-- Ads Section -->
  @if(config('settings.google_ads_desktop_article') || config('settings.google_ads_mobile_article'))
  <div class="container mb-4">
    <div class="text-center">
      @if($detect->isMobile())
      {!! config('settings.google_ads_mobile_article') !!}
      @else
      {!! config('settings.google_ads_desktop_article') !!}
      @endif
    </div>
  </div>
  @endif

  <div class="container">
    <div class="row">
      <!-- Main Content -->
      <div class="col-lg-8">
        <!-- Article Info Card -->
        <div class="card border-0 shadow-sm mb-4" style="border-radius: 20px; overflow: hidden;">
          <div class="card-body p-4">
            <!-- Article Stats -->
            <div class="row g-3 mb-4">
              <div class="col-6 col-md-3">
                <div class="text-center p-3" style="background: linear-gradient(135deg, #3b82f6, #1d4ed8); border-radius: 15px;">
                  <i class="ti ti-eye text-white mb-2" style="font-size: 1.5rem;"></i>
                  <div class="text-white fw-bold">{{ number_format($article->visit_count) }}</div>
                  <small class="text-white opacity-75">مشاهدة</small>
                </div>
              </div>
              <div class="col-6 col-md-3">
                <div class="text-center p-3" style="background: linear-gradient(135deg, #10b981, #059669); border-radius: 15px;">
                  <i class="ti ti-download text-white mb-2" style="font-size: 1.5rem;"></i>
                  <div class="text-white fw-bold">{{ $file ? number_format($file->download_count) : '0' }}</div>
                  <small class="text-white opacity-75">تحميل</small>
                </div>
              </div>
              <div class="col-6 col-md-3">
                <div class="text-center p-3" style="background: linear-gradient(135deg, #f59e0b, #d97706); border-radius: 15px;">
                  <i class="ti ti-calendar text-white mb-2" style="font-size: 1.5rem;"></i>
                  <div class="text-white fw-bold">{{ $article->created_at->format('d') }}</div>
                  <small class="text-white opacity-75">{{ $article->created_at->format('M Y') }}</small>
                </div>
              </div>
              <div class="col-6 col-md-3">
                <div class="text-center p-3" style="background: linear-gradient(135deg, #8b5cf6, #7c3aed); border-radius: 15px;">
                  <i class="ti ti-tag text-white mb-2" style="font-size: 1.5rem;"></i>
                  <div class="text-white fw-bold">{{ $article->keywords->count() }}</div>
                  <small class="text-white opacity-75">كلمة مفتاحية</small>
                </div>
              </div>
            </div>

            <!-- Article Details Table -->
            <div class="table-responsive">
              <table class="table table-borderless">
                <tbody>
                  <tr>
                    <td class="fw-bold" style="color: #1f36ad; width: 30%;">{{ __('grade') }}</td>
                    <td>{{ $grade_level }}</td>
                  </tr>
                  <tr>
                    <td class="fw-bold" style="color: #1f36ad;">{{ __('subject') }}</td>
                    <td>{{ $subject->subject_name }}</td>
                  </tr>
                  <tr>
                    <td class="fw-bold" style="color: #1f36ad;">{{ __('semester') }}</td>
                    <td>{{ $semester->semester_name }}</td>
                  </tr>
                  <tr>
                    <td class="fw-bold" style="color: #1f36ad;">{{ __('content_type') }}</td>
                    <td>
                      <span class="badge px-3 py-2" style="background: {{ $fileConfig['bg'] }}; color: #1e293b; border-radius: 20px;">
                        <i class="{{ $fileConfig['icon'] }} me-1"></i>
                        @switch($category)
                        @case('plans')
                        {{ __('study_plans') }}
                        @break
                        @case('papers')
                        {{ __('worksheets') }}
                        @break
                        @case('tests')
                        {{ __('tests') }}
                        @break
                        @case('books')
                        {{ __('school_books') }}
                        @break
                        @default
                        {{ __('articles') }}
                        @endswitch
                      </span>
                    </td>
                  </tr>
                  @if($article->keywords->count() > 0)
                  <tr>
                    <td class="fw-bold" style="color: #1f36ad;">{{ __('keywords') }}</td>
                    <td>
                      <div class="d-flex flex-wrap gap-2">
                        @foreach($article->keywords as $keyword)
                        <a href="{{ route('keywords.indexByKeyword', ['database' => $database, 'keywords' => $keyword->keyword]) }}" class="badge text-decoration-none" style="background: rgba(31, 54, 173, 0.1); color: #1f36ad; padding: 0.5rem 1rem; border-radius: 20px; border: 1px solid rgba(31, 54, 173, 0.2);">
                          {{ $keyword->keyword }}
                        </a>
                        @endforeach
                      </div>
                    </td>
                  </tr>
                  @endif
                </tbody>
              </table>
            </div>
          </div>
        </div>

        <!-- Article Content Card -->
        <div class="card border-0 shadow-sm mb-4" style="border-radius: 20px; overflow: hidden;">
          <div class="card-body p-4">
            <h3 class="mb-4" style="color: #1f36ad; font-weight: 600;">محتوى المقال</h3>

            @php
            switch($database) {
              case 'sa':
                $defaultImageUrl = asset('assets/img/front-pages/icons/articles_saudi_image.jpg');
                break;
              case 'eg':
                $defaultImageUrl = asset('assets/img/front-pages/icons/articles_egypt_image.jpg');
                break;
              case 'ps':
                $defaultImageUrl = asset('assets/img/front-pages/icons/articles_palestine_image.jpg');
                break;
              default:
                $defaultImageUrl = asset('assets/img/front-pages/icons/articles_default_image.jpg');
                break;
            }

            if (!function_exists('insertDefaultImageIfNeeded')) {
              function insertDefaultImageIfNeeded($content, $defaultImageUrl, $articleTitle) {
                preg_match('/<img[^>]+src="([^">]+)"/', $content, $matches);
                if (!isset($matches[1])) {
                  $content = '<img src="' . $defaultImageUrl . '" alt="' . e($articleTitle) . '" class="article-default-image" style="max-width:100%; height:auto; border-radius: 15px; margin-bottom: 1rem;">' . $content;
                }
                return $content;
              }
            }

            $articleContent = isset($article->content) ? insertDefaultImageIfNeeded($article->content, $defaultImageUrl, $article->title) : '';
            @endphp

            <div class="article-content" style="line-height: 1.8; color: #374151;">
              {!! $articleContent !!}
            </div>

            @if($article->meta_description)
            <div class="mt-4 p-3" style="background: rgba(31, 54, 173, 0.05); border-radius: 15px; border-left: 4px solid #1f36ad;">
              <h5 style="color: #1f36ad; margin-bottom: 0.5rem;">وصف المقال</h5>
              <p class="mb-0" style="color: #6b7280;">{!! $article->meta_description !!}</p>
            </div>
            @endif

            <!-- Second Ads Section -->
            @if(config('settings.google_ads_desktop_article_2') || config('settings.google_ads_mobile_article_2'))
            <div class="text-center my-4">
              @if($detect->isMobile())
              {!! config('settings.google_ads_mobile_article_2') !!}
              @else
              {!! config('settings.google_ads_desktop_article_2') !!}
              @endif
            </div>
            @endif
          </div>
        </div>

        <!-- Download Files Card -->
        @if($article->files->count() > 0)
        <div class="card border-0 shadow-sm mb-4" style="border-radius: 20px; overflow: hidden;">
          <div class="card-body p-4">
            <h3 class="mb-4" style="color: #1f36ad; font-weight: 600;">
              <i class="ti ti-download me-2"></i>تحميل الملفات
            </h3>

            <div class="row g-3">
              @foreach ($article->files as $file)
              @php
              $fileConfig = match($file->file_type) {
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
                  'text' => 'ملف'
                ]
              };
              @endphp

              <div class="col-md-6">
                <div class="d-flex align-items-center p-3" style="background: {{ $fileConfig['bg'] }}; border-radius: 15px; border: 1px solid rgba(0,0,0,0.05);">
                  <div class="me-3">
                    <div class="d-flex align-items-center justify-content-center" style="width: 50px; height: 50px; background: {{ $fileConfig['color'] }}; border-radius: 12px;">
                      <i class="{{ $fileConfig['icon'] }} text-white" style="font-size: 1.5rem;"></i>
                    </div>
                  </div>
                  <div class="flex-grow-1">
                    <h6 class="mb-1" style="color: #1e293b;">{{ $fileConfig['text'] }} ملف</h6>
                    <small class="text-muted">{{ number_format($file->download_count) }} تحميل</small>
                  </div>
                  <a href="{{ route('download.page', ['file' => $file->id]) }}" class="btn btn-sm" style="background: {{ $fileConfig['color'] }}; color: white; border-radius: 10px; padding: 0.5rem 1rem;" target="_blank">
                    <i class="ti ti-download me-1"></i>تحميل
                  </a>
                </div>
              </div>
              @endforeach
            </div>
          </div>
        </div>
        @endif

        <!-- Social Share Card -->
        <div class="card border-0 shadow-sm mb-4" style="border-radius: 20px; overflow: hidden;">
          <div class="card-body p-4">
            <h5 class="mb-3" style="color: #1f36ad; font-weight: 600;">
              <i class="ti ti-share me-2"></i>شارك المقال
            </h5>
            <div class="d-flex gap-3 justify-content-center">
              <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(request()->fullUrl()) }}" target="_blank" class="btn" style="background: linear-gradient(135deg, #1877f2, #166fe5); color: white; border-radius: 15px; padding: 0.75rem 1.5rem;">
                <i class="ti ti-brand-facebook me-2"></i>Facebook
              </a>
              <a href="https://twitter.com/intent/tweet?url={{ urlencode(request()->fullUrl()) }}" target="_blank" class="btn" style="background: linear-gradient(135deg, #1da1f2, #0d8bd9); color: white; border-radius: 15px; padding: 0.75rem 1.5rem;">
                <i class="ti ti-brand-twitter me-2"></i>Twitter
              </a>
              <a href="https://www.linkedin.com/shareArticle?mini=true&url={{ urlencode(request()->fullUrl()) }}" target="_blank" class="btn" style="background: linear-gradient(135deg, #0077b5, #005885); color: white; border-radius: 15px; padding: 0.75rem 1.5rem;">
                <i class="ti ti-brand-linkedin me-2"></i>LinkedIn
              </a>
            </div>
          </div>
        </div>

        <!-- Comments Section -->
        @auth
        <div id="add-comment-form" class="card border-0 shadow-sm mb-4" style="border-radius: 20px; overflow: hidden; border: 2px solid #1f36ad;">
          <div class="card-body p-4">
            <div class="d-flex align-items-center mb-3">
              <div class="me-3">
                <div class="d-flex align-items-center justify-content-center" style="width: 50px; height: 50px; background: linear-gradient(135deg, #1f36ad, #286aad); border-radius: 50%;">
                  <i class="ti ti-user text-white" style="font-size: 1.5rem;"></i>
                </div>
              </div>
              <div>
                <h5 class="mb-1" style="color: #1f36ad; font-weight: 600;">
                  مرحباً {{ auth()->user()->name }}
                </h5>
                <p class="mb-0 text-muted">شارك رأيك حول هذا المقال</p>
              </div>
            </div>
            <form action="{{ route('frontend.comments.store', ['database' => $database ?? session('database')]) }}" method="POST">
              @csrf
              <input type="hidden" name="commentable_id" value="{{ $article->id }}">
              <input type="hidden" name="commentable_type" value="App\Models\Article">
              <div class="mb-3">
                <textarea class="form-control" name="body" rows="4" placeholder="اكتب تعليقك هنا..." required style="border-radius: 15px; border: 2px solid #e5e7eb; resize: none;"></textarea>
              </div>
              <button type="submit" class="btn" style="background: linear-gradient(135deg, #1f36ad, #286aad); color: white; border-radius: 15px; padding: 0.75rem 2rem;">
                <i class="ti ti-send me-2"></i>نشر التعليق
              </button>
            </form>
          </div>
        </div>
        @else
        <!-- Guest User Section -->
        <div class="card border-0 shadow-sm mb-4" style="border-radius: 20px; overflow: hidden; border: 2px solid #f59e0b;">
          <div class="card-body p-4 text-center">
            <div class="mb-3">
              <div class="d-inline-flex align-items-center justify-content-center" style="width: 60px; height: 60px; background: linear-gradient(135deg, #f59e0b, #d97706); border-radius: 50%;">
                <i class="ti ti-login text-white" style="font-size: 2rem;"></i>
              </div>
            </div>
            <h5 class="mb-3" style="color: #d97706; font-weight: 600;">
              هل تريد مشاركة رأيك؟
            </h5>
            <p class="text-muted mb-4">
              سجل دخولك للموقع وشارك في النقاش حول هذا المقال والمقالات الأخرى
            </p>
            <div class="d-flex gap-2 justify-content-center">
              <a href="{{ route('login') }}" class="btn btn-warning">
                <i class="ti ti-login me-2"></i>
                تسجيل الدخول
              </a>
              <a href="{{ route('register') }}" class="btn btn-outline-warning">
                <i class="ti ti-user-plus me-2"></i>
                إنشاء حساب جديد
              </a>
            </div>
          </div>
        </div>
        @endauth

        <!-- Comments List -->
        @php
        $comments = $article->comments ?? collect();
        $commentsCount = $comments->count() ?? 0;
        @endphp

        @if($commentsCount > 0)
          <div class="card border-0 shadow-sm mb-4" style="border-radius: 20px; overflow: hidden;">
            <div class="card-body p-4">
              <h5 class="mb-4" style="color: #1f36ad; font-weight: 600;">
                <i class="ti ti-messages me-2"></i>التعليقات ({{ $commentsCount }})
              </h5>

              @foreach($comments as $comment)
                @php
                // Safely get reactions with multiple fallbacks
                $reactions = null;
                try {
                    $reactions = $comment->reactions ?? collect();
                } catch (Exception $e) {
                    $reactions = collect();
                }

                // Safely count reactions with fallbacks
                $likeCount = 0;
                $loveCount = 0;
                $laughCount = 0;

                if ($reactions && method_exists($reactions, 'where')) {
                    try {
                        $likeCount = $reactions->where('type', 'like')->count() ?? 0;
                        $loveCount = $reactions->where('type', 'love')->count() ?? 0;
                        $laughCount = $reactions->where('type', 'laugh')->count() ?? 0;
                    } catch (Exception $e) {
                        // Keep default values of 0
                    }
                }
                @endphp

                <div class="comment-item p-3 mb-3" style="background: #f8fafc; border-radius: 15px; border-left: 4px solid #1f36ad;">
                  <div class="d-flex align-items-center mb-2">
                    <div class="me-3">
                      <div class="d-flex align-items-center justify-content-center" style="width: 40px; height: 40px; background: linear-gradient(135deg, #1f36ad, #286aad); border-radius: 50%;">
                        <i class="ti ti-user text-white"></i>
                      </div>
                    </div>
                    <div>
                      @php
                        $user = $comment->user ?? (object)['name' => __('Anonymous')];
                        $roleColor = isset($user->roles) && $user->hasRole('Admin') ? '#dc2626' :
                                    (isset($user->roles) && $user->hasRole('Supervisor') ? '#f59e0b' : '#1f36ad');
                      @endphp
                      <h6 class="mb-0" style="color: {{ $roleColor }};">
                        {{ $user->name ?? __('Anonymous') }}
                      </h6>
                      <small class="text-muted">
                        {{ $comment->created_at->diffForHumans() }}
                      </small>
                    </div>
                  </div>
                  <p class="mb-2">
                    {{ $comment->body ?? __('No content available') }}
                  </p>
                  <!-- Reactions -->
                  @if($reactions && $reactions->count() > 0)
                    <div class="d-flex align-items-center gap-2">
                      @foreach($reactions as $reaction)
                        <span class="badge bg-{{ $reaction->type }}-soft">
                          <i class="ti ti-{{ $reaction->icon }} me-1"></i>
                          {{ $reaction->count ?? 0 }}
                        </span>
                      @endforeach
                    </div>
                  @endif

                  <!-- Reaction Buttons -->
                  <div class="d-flex gap-2 mt-3">
                    <form action="{{ route('frontend.reactions.store', ['database' => $database ?? session('database')]) }}" method="POST" class="d-inline">
                      @csrf
                      <input type="hidden" name="comment_id" value="{{ $comment->id }}">
                      <input type="hidden" name="type" value="like">
                      <button type="submit" class="btn btn-sm" style="background: rgba(59, 130, 246, 0.1); color: #3b82f6; border: 1px solid rgba(59, 130, 246, 0.2); border-radius: 10px;">
                        <i class="ti ti-thumb-up me-1"></i>إعجاب
                        <span class="badge" style="background: #3b82f6; color: white;">{{ $likeCount }}</span>
                      </button>
                    </form>

                    <form action="{{ route('frontend.reactions.store', ['database' => $database ?? session('database')]) }}" method="POST" class="d-inline">
                      @csrf
                      <input type="hidden" name="comment_id" value="{{ $comment->id }}">
                      <input type="hidden" name="type" value="love">
                      <button type="submit" class="btn btn-sm" style="background: rgba(239, 68, 68, 0.1); color: #ef4444; border: 1px solid rgba(239, 68, 68, 0.2); border-radius: 10px;">
                        <i class="ti ti-heart me-1"></i>حب
                        <span class="badge" style="background: #ef4444; color: white;">{{ $loveCount }}</span>
                      </button>
                    </form>

                    <form action="{{ route('frontend.reactions.store', ['database' => $database ?? session('database')]) }}" method="POST" class="d-inline">
                      @csrf
                      <input type="hidden" name="comment_id" value="{{ $comment->id }}">
                      <input type="hidden" name="type" value="laugh">
                      <button type="submit" class="btn btn-sm" style="background: rgba(245, 158, 11, 0.1); color: #f59e0b; border: 1px solid rgba(245, 158, 11, 0.2); border-radius: 10px;">
                        <i class="ti ti-mood-happy me-1"></i>ضحك
                        <span class="badge" style="background: #f59e0b; color: white;">{{ $laughCount }}</span>
                      </button>
                    </form>
                    
                    @if(auth()->check() && auth()->id() === $comment->user_id)
                    <form action="{{ route('frontend.comments.destroy', ['database' => $database ?? session('database'), 'id' => $comment->id]) }}" method="POST" class="d-inline ms-2">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('هل أنت متأكد من رغبتك في حذف التعليق؟')" style="border-radius: 10px;">
                            <i class="ti ti-trash me-1"></i>حذف
                        </button>
                    </form>
                    @endif
                  </div>
                </div>
              @endforeach
            </div>
          </div>
        @endif
      </div>

      <!-- Sidebar -->
      <div class="col-lg-4">
        <!-- Related Articles -->
        @if(isset($relatedArticles) && $relatedArticles->count() > 0)
        <div class="card border-0 shadow-sm mb-4" style="border-radius: 20px; overflow: hidden;">
          <div class="card-body p-4">
            <h5 class="mb-4" style="color: #1f36ad; font-weight: 600;">
              <i class="ti ti-article me-2"></i>مقالات ذات صلة
            </h5>

            @foreach($relatedArticles->take(5) as $relatedArticle)
            <div class="d-flex align-items-center mb-3 p-3" style="background: #f8fafc; border-radius: 15px; transition: all 0.3s ease;" onmouseover="this.style.background='#e5e7eb'" onmouseout="this.style.background='#f8fafc'">
              <div class="me-3">
                @php
                $relatedFile = $relatedArticle->files->first();
                $relatedFileType = $relatedFile ? $relatedFile->file_type : 'default';
                $relatedFileConfig = match($relatedFileType) {
                  'pdf' => ['icon' => 'ti ti-file-type-pdf', 'color' => '#dc2626'],
                  'doc', 'docx' => ['icon' => 'ti ti-file-type-doc', 'color' => '#2563eb'],
                  'xls', 'xlsx' => ['icon' => 'ti ti-file-spreadsheet', 'color' => '#059669'],
                  'ppt', 'pptx' => ['icon' => 'ti ti-presentation', 'color' => '#ea580c'],
                  default => ['icon' => 'ti ti-article', 'color' => '#6366f1']
                };
                @endphp
                <div class="d-flex align-items-center justify-content-center" style="width: 40px; height: 40px; background: {{ $relatedFileConfig['color'] }}15; border-radius: 10px;">
                  <i class="{{ $relatedFileConfig['icon'] }}" style="color: {{ $relatedFileConfig['color'] }}; font-size: 1.2rem;"></i>
                </div>
              </div>
              <div class="flex-grow-1">
                <a href="{{ route('frontend.articles.show', ['database' => $database, 'id' => $relatedArticle->id]) }}" class="text-decoration-none">
                  <h6 class="mb-1" style="color: #1e293b; font-size: 0.9rem; line-height: 1.4;">{{ Str::limit($relatedArticle->title, 60) }}</h6>
                </a>
                <small class="text-muted">
                  <i class="ti ti-eye me-1"></i>{{ number_format($relatedArticle->visit_count) }}
                </small>
              </div>
            </div>
            @endforeach
          </div>
        </div>
        @endif

        <!-- Ads Sidebar -->
        @if(config('settings.google_ads_desktop_2') || config('settings.google_ads_mobile_2'))
        <div class="card border-0 shadow-sm mb-4" style="border-radius: 20px; overflow: hidden;">
          <div class="card-body p-4 text-center">
            @if($detect->isMobile())
            {!! config('settings.google_ads_mobile_2') !!}
            @else
            {!! config('settings.google_ads_desktop_2') !!}
            @endif
          </div>
        </div>
        @endif

        <!-- Quick Stats -->
        <div class="card border-0 shadow-sm" style="border-radius: 20px; overflow: hidden;">
          <div class="card-body p-4">
            <h5 class="mb-4" style="color: #1f36ad; font-weight: 600;">
              <i class="ti ti-chart-bar me-2"></i>إحصائيات سريعة
            </h5>

            <div class="row g-3">
              <div class="col-6">
                <div class="text-center p-3" style="background: linear-gradient(135deg, #3b82f6, #1d4ed8); border-radius: 15px;">
                  <i class="ti ti-article text-white mb-2" style="font-size: 1.5rem;"></i>
                  <div class="text-white fw-bold">{{ $subject->articles ? $subject->articles->count() : 0 }}</div>
                  <small class="text-white opacity-75">مقال</small>
                </div>
              </div>
              <div class="col-6">
                <div class="text-center p-3" style="background: linear-gradient(135deg, #10b981, #059669); border-radius: 15px;">
                  <i class="ti ti-file text-white mb-2" style="font-size: 1.5rem;"></i>
                  @php
                  $totalFiles = 0;
                  if ($subject->articles) {
                      foreach ($subject->articles as $a) {
                          if ($a->files) {
                              $totalFiles += $a->files->count();
                          }
                      }
                  }
                  @endphp
                  <div class="text-white fw-bold">{{ $totalFiles }}</div>
                  <small class="text-white opacity-75">ملف</small>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

@php
$country = session('database', 'jordan');

switch($country) {
case 'sa':
$defaultImageUrl = asset('assets/img/front-pages/icons/articles_saudi_image.jpg');
break;
case 'eg':
$defaultImageUrl = asset('assets/img/front-pages/icons/articles_egypt_image.jpg');
break;
case 'ps':
$defaultImageUrl = asset('assets/img/front-pages/icons/articles_palestine_image.jpg');
break;
default:
$defaultImageUrl = asset('assets/img/front-pages/icons/articles_default_image.jpg');
break;
}

if (!function_exists('getFirstImageFromContent')) {
function getFirstImageFromContent($content, $defaultImageUrl) {
preg_match('/<img[^>]+src="([^">]+)"/', $content, $matches);
  return $matches[1] ?? $defaultImageUrl;
  }
  }

  $firstImageUrl = getFirstImageFromContent($article->content, $defaultImageUrl);
  @endphp

  <script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@type": "Article",
      "mainEntityOfPage": {
        "@type": "WebPage",
        "@id": "{{ url()->current() }}"
      },
      "headline": "{{ $article->title }}",
      "description": "{{ $article->meta_description }}",
      "author": {
        "@type": "Person",
        "name": "{{ $author ? $author->name : 'Anonymous' }}"
      },
      "datePublished": "{{ $article->created_at->toIso8601String() }}",
      "dateModified": "{{ $article->updated_at->toIso8601String() }}",
      "publisher": {
        "@type": "Organization",
        "name": "{{ config('settings.site_name') ? config('settings.site_name') : 'site_name' }}",
        "logo": {
          "@type": "ImageObject",
          "url": "{{ asset('storage/' . config('settings.site_logo')) }}"
        }
      },
      "image": {
        "@type": "ImageObject",
        "url": "{{ $firstImageUrl }}",
        "width": 800,
        "height": 600
      }
    }
  </script>

  <script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@type": "BreadcrumbList",
      "itemListElement": [{
          "@type": "ListItem",
          "position": 1,
          "name": "{{ __('Home') }}",
          "item": "{{ url('/') }}"
        },
        {
          "@type": "ListItem",
          "position": 2,
          "name": "{{ __('Classes') }}",
          "item": "{{ route('frontend.lesson.index', ['database' => $database ?? session('database', 'default_database')]) }}"
        },
        {
          "@type": "ListItem",
          "position": 3,
          "name": "{{ $subject->subject_name }}",
          "item": "{{ route('frontend.subjects.show', ['database' => $database ?? session('database', 'default_database'),'subject' => $subject->id]) }}"
        },
        {
          "@type": "ListItem",
          "position": 4,
          "name": "{{ $article->title }}",
          "item": "{{ url()->current() }}"
        }
      ]
    }
  </script>

  @endsection
