@php
$configData = Helper::appClasses();
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Models\News;
use App\Models\User;
use Detection\MobileDetect;

$detect = new MobileDetect;

// Get the author from the main database (jo)
$author = User::on('jo')->find($news->author_id);
$news->setRelation('author', $author);

// Fetch random news items from the selected database
$randomNews = \App\Models\News::on($database)
->inRandomOrder()
->take(5)
->get();
@endphp



@extends('layouts/layoutFront')

@section('title', $news->title)
@section('meta_title', $news->title . ' - ' . ($news->meta_title ?? config('settings.meta_title')))

@section('page-style')
@vite([
  'resources/assets/vendor/scss/home.scss'
])
@endsection

@section('meta')
<meta property="og:type" content="article" />
<meta property="og:title" content="{{ $news->title }}" />
<meta property="og:description" content="{{ $news->meta_description }}" />
<meta property="og:image" content="{{ asset('storage/images/' . $news->image) }}" />
<meta property="og:url" content="{{ request()->fullUrl() }}" />
<meta property="og:site_name" content="{{ config('settings.site_name') }}" />
<meta property="article:modified_time" content="{{ $news->updated_at->toIso8601String() }}" />
<meta property="article:published_time" content="{{ $news->created_at->toIso8601String() }}" />

<meta property="article:author" content="{{ $author->name ?? 'Unknown' }}" />

<meta property="article:section" content="{{ $news->category->name }}" />

@php
$keywordsArray = is_string($news->keywords) ? array_map('trim', explode(',', $news->keywords)) : [];
@endphp
<meta property="article:tag" content="{{ implode(',', $keywordsArray) }}" />

<meta name="twitter:card" content="summary_large_image" />
<meta name="twitter:title" content="{{ $news->title }}" />
<meta name="twitter:description" content="{{ $news->meta_description }}" />
<meta name="twitter:image" content="{{ asset('storage/images/' . $news->image) }}" />
<meta name="twitter:site" content="{{ config('settings.twitter') }}" />
@endsection

@section('content')
<div class="edu-gradient-bg" style="padding-top: 80px; position: relative; overflow: hidden;">
  <!-- Hero Section -->
  <section class="edu-hero-section" style="padding: 4rem 0; position: relative; z-index: 2;">
    <div class="container">
      <div class="row align-items-center justify-content-center">
        <div class="col-lg-10 col-xl-8">
          <!-- Hero Card -->
          <div class="hero-news-card" style="
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(20px);
            border-radius: 2rem;
            padding: 3rem 2rem;
            border: 1px solid rgba(255, 255, 255, 0.2);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
            text-align: center;
            position: relative;
            overflow: hidden;
          ">
            <!-- Card Overlay -->
            <div style="
              position: absolute;
              top: 0;
              left: 0;
              right: 0;
              bottom: 0;
              background: linear-gradient(135deg, rgba(255,255,255,0.1), rgba(255,255,255,0.05));
              border-radius: 2rem;
              pointer-events: none;
            "></div>
            
            <!-- News Category Badge -->
            @if($news->category)
            <div class="news-category-badge" style="
              display: inline-block;
              background: linear-gradient(135deg, #e74c3c, #c0392b);
              color: white;
              padding: 0.5rem 1.5rem;
              border-radius: 2rem;
              font-size: 0.9rem;
              font-weight: 600;
              margin-bottom: 1.5rem;
              box-shadow: 0 4px 15px rgba(231, 76, 60, 0.3);
              position: relative;
              z-index: 3;
            ">
              <i class="ti ti-tag me-2"></i>{{ $news->category->name }}
            </div>
            @endif
            
            <!-- News Title -->
            <h1 class="hero-title" style="
              color: white;
              font-size: clamp(1.8rem, 4vw, 2.5rem);
              font-weight: 700;
              line-height: 1.3;
              margin-bottom: 1.5rem;
              text-shadow: 0 4px 20px rgba(0,0,0,0.3);
              position: relative;
              z-index: 3;
            ">
              {{ $news->title }}
            </h1>
            
            <!-- News Meta Info -->
            <div class="hero-meta-info" style="
              display: flex;
              justify-content: center;
              flex-wrap: wrap;
              gap: 2rem;
              margin-bottom: 2rem;
              position: relative;
              z-index: 3;
            ">
              <!-- Date -->
              <div class="meta-item" style="
                display: flex;
                align-items: center;
                gap: 0.5rem;
                color: rgba(255, 255, 255, 0.9);
                font-size: 0.95rem;
              ">
                <div class="meta-icon" style="
                  width: 2.5rem;
                  height: 2.5rem;
                  background: linear-gradient(135deg, #f39c12, #e67e22);
                  border-radius: 50%;
                  display: flex;
                  align-items: center;
                  justify-content: center;
                  box-shadow: 0 4px 15px rgba(243, 156, 18, 0.3);
                ">
                  <i class="ti ti-calendar" style="color: white; font-size: 1.1rem;"></i>
                </div>
                <div class="meta-content">
                  <div style="font-size: 0.8rem; opacity: 0.8;">تاريخ النشر</div>
                  <div style="font-weight: 600;">{{ $news->created_at->format('Y/m/d') }}</div>
                </div>
              </div>
              
              <!-- Author -->
              <div class="meta-item" style="
                display: flex;
                align-items: center;
                gap: 0.5rem;
                color: rgba(255, 255, 255, 0.9);
                font-size: 0.95rem;
              ">
                <div class="meta-icon" style="
                  width: 2.5rem;
                  height: 2.5rem;
                  background: linear-gradient(135deg, #3498db, #2980b9);
                  border-radius: 50%;
                  display: flex;
                  align-items: center;
                  justify-content: center;
                  box-shadow: 0 4px 15px rgba(52, 152, 219, 0.3);
                ">
                  <i class="ti ti-user" style="color: white; font-size: 1.1rem;"></i>
                </div>
                <div class="meta-content">
                  <div style="font-size: 0.8rem; opacity: 0.8;">الكاتب</div>
                  <div style="font-weight: 600;">{{ $author->name ?? 'غير محدد' }}</div>
                </div>
              </div>
              
              <!-- Views -->
              <div class="meta-item" style="
                display: flex;
                align-items: center;
                gap: 0.5rem;
                color: rgba(255, 255, 255, 0.9);
                font-size: 0.95rem;
              ">
                <div class="meta-icon" style="
                  width: 2.5rem;
                  height: 2.5rem;
                  background: linear-gradient(135deg, #9b59b6, #8e44ad);
                  border-radius: 50%;
                  display: flex;
                  align-items: center;
                  justify-content: center;
                  box-shadow: 0 4px 15px rgba(155, 89, 182, 0.3);
                ">
                  <i class="ti ti-eye" style="color: white; font-size: 1.1rem;"></i>
                </div>
                <div class="meta-content">
                  <div style="font-size: 0.8rem; opacity: 0.8;">المشاهدات</div>
                  <div style="font-weight: 600;">{{ number_format($news->views ?? 0) }}</div>
                </div>
              </div>
            </div>
            
            <!-- Action Buttons -->
            <div class="hero-actions" style="
              display: flex;
              justify-content: center;
              gap: 1rem;
              position: relative;
              z-index: 3;
            ">
              <button class="btn btn-light" style="
                background: rgba(255, 255, 255, 0.9);
                color: var(--edu-primary);
                border: none;
                padding: 0.75rem 2rem;
                border-radius: 2rem;
                font-weight: 600;
                backdrop-filter: blur(10px);
                box-shadow: 0 4px 15px rgba(255, 255, 255, 0.2);
                transition: all 0.3s ease;
              " onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 6px 20px rgba(255, 255, 255, 0.3)';" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 15px rgba(255, 255, 255, 0.2)';">
                <i class="ti ti-bookmark me-2"></i>حفظ للقراءة لاحقاً
              </button>
              
              <button class="btn btn-outline-light" style="
                background: rgba(255, 255, 255, 0.1);
                color: white;
                border: 2px solid rgba(255, 255, 255, 0.3);
                padding: 0.75rem 2rem;
                border-radius: 2rem;
                font-weight: 600;
                backdrop-filter: blur(10px);
                transition: all 0.3s ease;
              " onmouseover="this.style.background='rgba(255, 255, 255, 0.2)'; this.style.transform='translateY(-2px)';" onmouseout="this.style.background='rgba(255, 255, 255, 0.1)'; this.style.transform='translateY(0)';">
                <i class="ti ti-share me-2"></i>مشاركة
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
    
    <!-- Enhanced Floating Elements -->
    <div class="floating-news-elements">
      <div class="floating-icon" style="
        position: absolute;
        top: 15%;
        left: 5%;
        width: 3rem;
        height: 3rem;
        background: linear-gradient(135deg, #e74c3c, #c0392b);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 1.5rem;
        animation: float 6s ease-in-out infinite;
        box-shadow: 0 4px 20px rgba(231, 76, 60, 0.3);
      ">
        <i class="ti ti-news"></i>
      </div>
      
      <div class="floating-icon" style="
        position: absolute;
        top: 25%;
        right: 8%;
        width: 2.5rem;
        height: 2.5rem;
        background: linear-gradient(135deg, #f39c12, #e67e22);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 1.2rem;
        animation: float 6s ease-in-out infinite -2s;
        box-shadow: 0 4px 20px rgba(243, 156, 18, 0.3);
      ">
        <i class="ti ti-edit"></i>
      </div>
      
      <div class="floating-icon" style="
        position: absolute;
        bottom: 30%;
        left: 8%;
        width: 2.8rem;
        height: 2.8rem;
        background: linear-gradient(135deg, #3498db, #2980b9);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 1.3rem;
        animation: float 6s ease-in-out infinite -4s;
        box-shadow: 0 4px 20px rgba(52, 152, 219, 0.3);
      ">
        <i class="ti ti-chart-bar"></i>
      </div>
      
      <div class="floating-icon" style="
        position: absolute;
        bottom: 20%;
        right: 5%;
        width: 3.2rem;
        height: 3.2rem;
        background: linear-gradient(135deg, #9b59b6, #8e44ad);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 1.4rem;
        animation: float 6s ease-in-out infinite -6s;
        box-shadow: 0 4px 20px rgba(155, 89, 182, 0.3);
      ">
        <i class="ti ti-bell"></i>
      </div>
    </div>
    
    <!-- Background Pattern -->
    <div style="
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      bottom: 0;
      background-image: radial-gradient(circle at 20% 50%, rgba(255,255,255,0.1) 1px, transparent 1px),
                        radial-gradient(circle at 80% 50%, rgba(255,255,255,0.1) 1px, transparent 1px),
                        radial-gradient(circle at 40% 20%, rgba(255,255,255,0.05) 1px, transparent 1px);
      background-size: 50px 50px, 60px 60px, 40px 40px;
      opacity: 0.5;
      z-index: 1;
    "></div>
  </section>
</div>

<!-- Breadcrumb -->
<div class="container mt-4">
  <nav aria-label="breadcrumb">
    <ol class="breadcrumb" style="background: none; padding: 0;">
      <li class="breadcrumb-item">
        <a href="{{ route('home') }}" style="color: var(--edu-primary); text-decoration: none;">
          <i class="ti ti-home me-1"></i>الرئيسية
        </a>
      </li>
      <li class="breadcrumb-item">
        <a href="{{ route('content.frontend.news.index', ['database' => $database ?? session('database', 'jo')]) }}" style="color: var(--edu-primary); text-decoration: none;">
          <i class="ti ti-news me-1"></i>الأخبار
        </a>
      </li>
      <li class="breadcrumb-item active" aria-current="page" style="color: #6c757d;">
        {{ Str::limit($news->title, 50) }}
      </li>
    </ol>
  </nav>
</div>

<!-- Main Content -->
<div class="container mt-5">
  <div class="row g-4">
    <!-- News Content -->
    <div class="col-lg-8">
      <!-- News Card -->
      <div class="edu-card">
        <div class="card-body p-4">
          <!-- News Stats -->
          <div class="row g-3 mb-4">
            <div class="col-md-3 col-6">
              <div class="edu-stat-card text-center" style="background: linear-gradient(135deg, #3498db, #2980b9);">
                <div class="edu-stat-icon">
                  <i class="ti ti-calendar" style="color: white;"></i>
                </div>
                <div class="edu-stat-info">
                  <span class="edu-stat-number" style="color: white;">{{ $news->created_at->format('d') }}</span>
                  <span class="edu-stat-label" style="color: rgba(255,255,255,0.8);">{{ $news->created_at->format('M Y') }}</span>
                </div>
              </div>
            </div>
            
            <div class="col-md-3 col-6">
              <div class="edu-stat-card text-center" style="background: linear-gradient(135deg, #e74c3c, #c0392b);">
                <div class="edu-stat-icon">
                  <i class="ti ti-user" style="color: white;"></i>
                </div>
                <div class="edu-stat-info">
                  <span class="edu-stat-number" style="color: white;">الكاتب</span>
                  <span class="edu-stat-label" style="color: rgba(255,255,255,0.8);">{{ $author->name ?? 'غير محدد' }}</span>
                </div>
              </div>
            </div>
            
            @if($news->category)
            <div class="col-md-3 col-6">
              <div class="edu-stat-card text-center" style="background: linear-gradient(135deg, #9b59b6, #8e44ad);">
                <div class="edu-stat-icon">
                  <i class="ti ti-tag" style="color: white;"></i>
                </div>
                <div class="edu-stat-info">
                  <span class="edu-stat-number" style="color: white;">التصنيف</span>
                  <span class="edu-stat-label" style="color: rgba(255,255,255,0.8);">{{ $news->category->name }}</span>
                </div>
              </div>
            </div>
            @endif
            
            <div class="col-md-3 col-6">
              <div class="edu-stat-card text-center" style="background: linear-gradient(135deg, #f39c12, #e67e22);">
                <div class="edu-stat-icon">
                  <i class="ti ti-clock" style="color: white;"></i>
                </div>
                <div class="edu-stat-info">
                  <span class="edu-stat-number" style="color: white;">منذ</span>
                  <span class="edu-stat-label" style="color: rgba(255,255,255,0.8);">{{ $news->created_at->diffForHumans() }}</span>
                </div>
              </div>
            </div>
          </div>
          
          <!-- Social Share -->
          <div class="text-center mb-4">
            <h6 class="mb-3" style="color: var(--edu-dark);">شارك الخبر</h6>
            <div class="d-flex justify-content-center gap-2">
              <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(request()->fullUrl()) }}" target="_blank" class="btn btn-facebook">
                <i class="ti ti-brand-facebook"></i> Facebook
              </a>
              <a href="https://twitter.com/intent/tweet?url={{ urlencode(request()->fullUrl()) }}" target="_blank" class="btn btn-twitter">
                <i class="ti ti-brand-twitter"></i> Twitter
              </a>
              <a href="https://www.linkedin.com/shareArticle?mini=true&url={{ urlencode(request()->fullUrl()) }}" target="_blank" class="btn btn-linkedin">
                <i class="ti ti-brand-linkedin"></i> LinkedIn
              </a>
            </div>
          </div>

          @php
            // التحقق إذا كانت الصورة موجودة أم لا
            $imagePath = $news->image ? asset('storage/' . $news->image) : asset('assets/img/news-placeholder.jpg');
            
            // معالجة محتوى الخبر لتحويل الكلمات الدلالية إلى روابط
            $processedContent = $news->content ?? $news->description;
            $keywords = is_string($news->keywords) ? array_map('trim', explode(',', $news->keywords)) : [];
          @endphp
          
          <!-- News Image -->
          @if($news->image)
          <div class="text-center mb-4">
            <img src="{{ $imagePath }}" class="img-fluid rounded" alt="{{ $news->title }}" style="max-height: 400px; width: 100%; object-fit: cover;">
          </div>
          @endif
          
          <!-- News Content -->
          <div class="news-content mb-4">
            <div class="article-content" style="font-size: 1.1rem; line-height: 1.8; color: #2c3e50;">
              {!! $processedContent !!}
            </div>
          </div>
          
          <!-- News Details -->
          @if(!empty($keywords) || $news->meta_description)
          <div class="news-details mt-4 pt-4" style="border-top: 2px solid #ecf0f1;">
            <div class="row g-4">
              @if(!empty($keywords))
              <div class="col-md-6">
                <h6 class="mb-3" style="color: var(--edu-dark); font-weight: 600;">
                  <i class="ti ti-tags me-2" style="color: var(--edu-primary);"></i>الكلمات المفتاحية
                </h6>
                <div class="keywords-container">
                  @foreach($keywords as $index => $keyword)
                    @php
                      $colors = ['primary', 'success', 'info', 'warning', 'danger', 'secondary'];
                      $color = $colors[$index % count($colors)];
                    @endphp
                    <a href="{{ route('keywords.indexByKeyword', ['database' => $database ?? session('database', 'jo'), 'keywords' => $keyword]) }}" 
                       class="badge bg-{{ $color }}-subtle text-{{ $color }} me-2 mb-2 text-decoration-none" 
                       style="font-size: 0.9rem; padding: 0.5rem 1rem;">
                      {{ $keyword }}
                    </a>
                  @endforeach
                </div>
              </div>
              @endif
              
              @if($news->meta_description)
              <div class="col-md-6">
                <h6 class="mb-3" style="color: var(--edu-dark); font-weight: 600;">
                  <i class="ti ti-file-description me-2" style="color: var(--edu-primary);"></i>وصف موجز
                </h6>
                <p class="text-muted" style="font-size: 0.95rem; line-height: 1.6;">
                  {{ $news->meta_description }}
                </p>
              </div>
              @endif
            </div>
          </div>
          @endif
          </div>
        </div>

      </div>
      
      <!-- Sidebar -->
      <div class="col-lg-4">
        <!-- Related News -->
        <div class="edu-card mb-4">
          <div class="card-body p-4">
            <h5 class="mb-4 d-flex align-items-center" style="color: var(--edu-dark); font-weight: 600;">
              <i class="ti ti-news me-2" style="color: var(--edu-primary);"></i>أخبار ذات صلة
            </h5>
            @if($randomNews && $randomNews->count() > 0)
              @foreach($randomNews->take(3) as $relatedNews)
              <div class="edu-article-card mb-3">
                <div class="d-flex align-items-start">
                  <div class="edu-icon-circle me-3" style="background: linear-gradient(135deg, #e74c3c, #c0392b); width: 3rem; height: 3rem; flex-shrink: 0;">
                    <i class="ti ti-news text-white"></i>
                  </div>
                  <div class="flex-grow-1">
                    <h6 class="fw-semibold mb-2" style="color: var(--edu-dark); line-height: 1.4;">
                      <a href="{{ route('content.frontend.news.show', ['database' => $database ?? session('database', 'jo'), 'id' => $relatedNews->id]) }}" 
                         class="text-decoration-none" style="color: inherit;">
                        {{ Str::limit($relatedNews->title, 60) }}
                      </a>
                    </h6>
                    <p class="text-muted small mb-2">{{ Str::limit(strip_tags($relatedNews->description), 80) }}</p>
                    <div class="d-flex justify-content-between align-items-center">
                      <span class="text-muted small">{{ $relatedNews->created_at->diffForHumans() }}</span>
                      <a href="{{ route('content.frontend.news.show', ['database' => $database ?? session('database', 'jo'), 'id' => $relatedNews->id]) }}" 
                         class="btn btn-sm btn-outline-primary">
                        اقرأ المزيد
                      </a>
                    </div>
                  </div>
                </div>
              </div>
              @endforeach
            @else
              <div class="text-center py-4">
                <i class="ti ti-news" style="font-size: 3rem; color: #ddd;"></i>
                <p class="text-muted mt-2">لا توجد أخبار ذات صلة</p>
              </div>
            @endif

          </div>
        </div>
        
        <!-- Ads Section -->
        @if(config('settings.google_ads_desktop_news_2') || config('settings.google_ads_mobile_news_2'))
        <div class="edu-card mt-4">
          <div class="card-body p-3 text-center">
            @if($detect->isMobile())
            {!! config('settings.google_ads_mobile_news_2') !!}
            @else
            {!! config('settings.google_ads_desktop_news_2') !!}
            @endif
          </div>
        </div>
        @endif
      </div>
    </div>
  </div>
</div>

<!-- Comments Section -->
@auth
<div class="container mt-5">
  <div class="edu-card">
    <div class="card-body p-4">
      <h4 class="mb-4 d-flex align-items-center" style="color: var(--edu-dark); font-weight: 600;">
        <i class="ti ti-message-circle me-2" style="color: var(--edu-primary);"></i>أضف تعليق
      </h4>
      <form action="{{ route('frontend.comments.store', ['database' => $database ?? session('database')]) }}" method="POST">
        @csrf
        <div class="mb-3">
          <label for="comment" class="form-label" style="color: var(--edu-dark); font-weight: 500;">تعليقك</label>
          <textarea class="form-control" id="comment" name="body" rows="4" required 
                    style="border: 2px solid #e9ecef; border-radius: 0.75rem; padding: 1rem;"
                    placeholder="شارك رأيك حول هذا الخبر..."></textarea>
        </div>
        <input type="hidden" name="commentable_id" value="{{ $news->id }}">
        <input type="hidden" name="commentable_type" value="{{ get_class($news) }}">
        <button type="submit" class="btn btn-primary" style="background: linear-gradient(135deg, var(--edu-primary), #2980b9); border: none; padding: 0.75rem 2rem;">
          <i class="ti ti-send me-2"></i>إرسال التعليق
        </button>
      </form>

      <!-- Comments Display -->
      @if($news->comments && $news->comments->count() > 0)
      <div class="mt-5">
        <h5 class="mb-4 d-flex align-items-center" style="color: var(--edu-dark); font-weight: 600;">
          <i class="ti ti-messages me-2" style="color: var(--edu-primary);"></i>التعليقات ({{ $news->comments->count() }})
        </h5>
        
        @foreach($news->comments as $comment)
        <div class="comment-item mb-4 p-3" style="background: #f8f9fa; border-radius: 1rem; border-left: 4px solid var(--edu-primary);">
          <div class="d-flex align-items-start">
            <div class="edu-icon-circle me-3" style="background: linear-gradient(135deg, #3498db, #2980b9); width: 2.5rem; height: 2.5rem; flex-shrink: 0;">
              <i class="ti ti-user text-white"></i>
            </div>
            <div class="flex-grow-1">
              <div class="d-flex justify-content-between align-items-start mb-2">
                <h6 class="mb-0" style="color: var(--edu-dark); font-weight: 600;">{{ $comment->user->name }}</h6>
                <small class="text-muted">{{ $comment->created_at->diffForHumans() }}</small>
              </div>
              <p class="mb-3" style="color: #2c3e50; line-height: 1.6;">{{ $comment->body }}</p>
              
              <!-- Reactions -->
              <div class="d-flex gap-2 flex-wrap">
                <form action="{{ route('frontend.reactions.store', ['database' => $database ?? session('database')]) }}" method="POST" class="d-inline">
                  @csrf
                  <input type="hidden" name="comment_id" value="{{ $comment->id }}">
                  <input type="hidden" name="type" value="like">
                  <button type="submit" class="btn btn-sm btn-outline-primary">
                    <i class="ti ti-thumb-up me-1"></i>إعجاب
                    <span class="badge bg-primary text-white ms-1">{{ $comment->reactions->where('type', 'like')->count() }}</span>
                  </button>
                </form>
                
                <form action="{{ route('frontend.reactions.store', ['database' => $database ?? session('database')]) }}" method="POST" class="d-inline">
                  @csrf
                  <input type="hidden" name="comment_id" value="{{ $comment->id }}">
                  <input type="hidden" name="type" value="love">
                  <button type="submit" class="btn btn-sm btn-outline-danger">
                    <i class="ti ti-heart me-1"></i>حب
                    <span class="badge bg-danger text-white ms-1">{{ $comment->reactions->where('type', 'love')->count() }}</span>
                  </button>
                </form>
                
                <form action="{{ route('frontend.reactions.store', ['database' => $database ?? session('database')]) }}" method="POST" class="d-inline">
                  @csrf
                  <input type="hidden" name="comment_id" value="{{ $comment->id }}">
                  <input type="hidden" name="type" value="laugh">
                  <button type="submit" class="btn btn-sm btn-outline-warning">
                    <i class="ti ti-mood-happy me-1"></i>ضحك
                    <span class="badge bg-warning text-white ms-1">{{ $comment->reactions->where('type', 'laugh')->count() }}</span>
                  </button>
                </form>
                
                @if(auth()->check() && $comment->user_id === auth()->id())
                <form action="{{ route('frontend.comments.destroy', ['database' => $database ?? session('database'), 'id' => $comment->id]) }}" method="POST" class="d-inline">
                  @csrf
                  @method('DELETE')
                  <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('هل أنت متأكد من حذف هذا التعليق؟')">
                    <i class="ti ti-trash me-1"></i>حذف
                  </button>
                </form>
                @endif
              </div>
            </div>
          </div>
        </div>
        @endforeach
      </div>
      @endif
    </div>
  </div>
</div>
@endauth

<style>
/* News Page Custom Styles */
.keyword-link {
    color: #3498db;
    text-decoration: none;
    font-weight: 500;
    transition: all 0.3s ease;
}
.keyword-link:hover {
    color: #2980b9;
    text-decoration: none;
    transform: translateY(-1px);
}

/* Social Share Buttons */
.btn-facebook {
    background: linear-gradient(135deg, #3b5998, #2d4373);
    border: none;
    color: white;
    transition: all 0.3s ease;
}
.btn-facebook:hover {
    background: linear-gradient(135deg, #2d4373, #1e2e4f);
    color: white;
    transform: translateY(-2px);
    box-shadow: 0 4px 15px rgba(59, 89, 152, 0.3);
}

.btn-twitter {
    background: linear-gradient(135deg, #1da1f2, #0d8bd9);
    border: none;
    color: white;
    transition: all 0.3s ease;
}
.btn-twitter:hover {
    background: linear-gradient(135deg, #0d8bd9, #0a6bb3);
    color: white;
    transform: translateY(-2px);
    box-shadow: 0 4px 15px rgba(29, 161, 242, 0.3);
}

.btn-linkedin {
    background: linear-gradient(135deg, #0077b5, #005885);
    border: none;
    color: white;
    transition: all 0.3s ease;
}
.btn-linkedin:hover {
    background: linear-gradient(135deg, #005885, #004066);
    color: white;
    transform: translateY(-2px);
    box-shadow: 0 4px 15px rgba(0, 119, 181, 0.3);
}

/* News Stats Cards */
.edu-stat-card {
    border-radius: 1rem;
    padding: 1.5rem 1rem;
    transition: all 0.3s ease;
    position: relative;
    overflow: hidden;
}
.edu-stat-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 30px rgba(0,0,0,0.2);
}
.edu-stat-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: linear-gradient(45deg, rgba(255,255,255,0.1), transparent);
    pointer-events: none;
}

.edu-stat-icon {
    font-size: 1.5rem;
    margin-bottom: 0.5rem;
}
.edu-stat-number {
    font-size: 1rem;
    font-weight: 600;
    display: block;
}
.edu-stat-label {
    font-size: 0.85rem;
    display: block;
}

/* Article Content Styling */
.article-content {
    text-align: justify;
}
.article-content img {
    max-width: 100%;
    height: auto;
    border-radius: 0.75rem;
    margin: 1rem 0;
}
.article-content p {
    margin-bottom: 1.5rem;
}
.article-content h1, .article-content h2, .article-content h3 {
    color: var(--edu-dark);
    margin-top: 2rem;
    margin-bottom: 1rem;
}

/* Comment Styling */
.comment-item {
    transition: all 0.3s ease;
}
.comment-item:hover {
    transform: translateX(5px);
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
}

/* Hero Section Enhancements */
.hero-news-card {
    transition: all 0.3s ease;
}
.hero-news-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 25px 50px rgba(0, 0, 0, 0.15) !important;
}

.news-category-badge {
    transition: all 0.3s ease;
}
.news-category-badge:hover {
    transform: scale(1.05);
}

.hero-title {
    transition: all 0.3s ease;
}

.meta-item {
    transition: all 0.3s ease;
}
.meta-item:hover {
    transform: translateY(-2px);
}

.meta-icon {
    transition: all 0.3s ease;
}
.meta-item:hover .meta-icon {
    transform: scale(1.1);
}

/* Floating Elements Animation */
@keyframes float {
    0%, 100% {
        transform: translateY(0px) rotate(0deg);
    }
    25% {
        transform: translateY(-10px) rotate(5deg);
    }
    50% {
        transform: translateY(-20px) rotate(0deg);
    }
    75% {
        transform: translateY(-10px) rotate(-5deg);
    }
}

.floating-icon {
    transition: all 0.3s ease;
}
.floating-icon:hover {
    transform: scale(1.2) !important;
    animation-play-state: paused;
}

/* Action Buttons */
.hero-actions .btn {
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    position: relative;
    overflow: hidden;
}

.hero-actions .btn::before {
    content: '';
    position: absolute;
    top: 50%;
    left: 50%;
    width: 0;
    height: 0;
    background: rgba(255, 255, 255, 0.2);
    border-radius: 50%;
    transform: translate(-50%, -50%);
    transition: width 0.6s, height 0.6s;
}

.hero-actions .btn:hover::before {
    width: 300px;
    height: 300px;
}

/* Background Pattern Animation */
@keyframes patternMove {
    0% {
        background-position: 0 0, 0 0, 0 0;
    }
    100% {
        background-position: 50px 50px, 60px 60px, 40px 40px;
    }
}

.edu-hero-section [style*="background-image"] {
    animation: patternMove 20s linear infinite;
}

/* Responsive Design */
@media (max-width: 768px) {
    .edu-stat-card {
        margin-bottom: 1rem;
    }
    .btn-facebook, .btn-twitter, .btn-linkedin {
        font-size: 0.85rem;
        padding: 0.5rem 1rem;
    }
    .article-content {
        font-size: 1rem;
    }
    
    /* Hero Section Mobile */
    .hero-news-card {
        padding: 2rem 1.5rem !important;
        margin: 0 1rem;
    }
    
    .hero-title {
        font-size: 1.5rem !important;
    }
    
    .hero-meta-info {
        flex-direction: column;
        gap: 1rem !important;
        align-items: center;
    }
    
    .meta-item {
        justify-content: center;
    }
    
    .hero-actions {
        flex-direction: column;
        align-items: center;
    }
    
    .hero-actions .btn {
        width: 100%;
        max-width: 250px;
    }
    
    .floating-icon {
        display: none;
    }
    
    .news-category-badge {
        font-size: 0.8rem !important;
        padding: 0.4rem 1.2rem !important;
    }
}

@media (max-width: 576px) {
    .hero-news-card {
        padding: 1.5rem 1rem !important;
        border-radius: 1.5rem !important;
    }
    
    .hero-title {
        font-size: 1.3rem !important;
        margin-bottom: 1rem !important;
    }
    
    .meta-icon {
        width: 2rem !important;
        height: 2rem !important;
    }
    
    .meta-icon i {
        font-size: 1rem !important;
    }
}
</style>

<script type="application/ld+json">
  {
    "@context": "https://schema.org",
    "@type": "NewsArticle",
    "headline": "{{ $news->title }}",
    "image": "{{ asset('storage/images/' . $news->image) }}",
    "datePublished": "{{ $news->created_at->toIso8601String() }}",
    "author": {
      "@type": "Person",
      "name": "{{ $author->name ?? 'Unknown' }}"
    },
    "publisher": {
      "@type": "Organization",
      "name": "{{ config('settings.site_name') }}",
      "logo": {
        "@type": "ImageObject",
        "url": "{{ asset('storage/' . config('settings.site_logo')) }}"
      }
    },
    "description": "{{ $news->meta_description }}"
  }
</script>

<script type="application/ld+json">
  {
    "@context": "https://schema.org",
    "@type": "BreadcrumbList",
    "itemListElement": [{
        "@type": "ListItem",
        "position": 1,
        "name": "Home",
        "item": "{{ url('/') }}"
      },
      {
        "@type": "ListItem",
        "position": 2,
        "name": "News",
        "item": "{{ route('content.frontend.news.index',['database' => $database ?? session('database', 'default_database')]) }}"
      },
      {
        "@type": "ListItem",
        "position": 3,
        "name": "{{ $news->title }}",
        "item": "{{ request()->url() }}"
      }
    ]
  }
</script>

@endsection
