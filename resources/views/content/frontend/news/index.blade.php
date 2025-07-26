@php
$database = session('database', 'jo');
$filterUrl = route('content.frontend.news.filter', ['database' => $database]);
use Illuminate\Support\Str;
@endphp

@extends('layouts/layoutFront')

@section('title', 'الأخبار - ' . config('app.name'))

@section('page-style')
@vite([
  'resources/assets/vendor/scss/home.scss'
])
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

            <!-- News Icon -->
            <div class="hero-icon mb-4" style="position: relative; z-index: 3;">
              <div class="edu-icon-circle" style="
                background: linear-gradient(135deg, #e74c3c, #c0392b);
                width: 5rem;
                height: 5rem;
                margin: 0 auto;
                border-radius: 50%;
                display: flex;
                align-items: center;
                justify-content: center;
                box-shadow: 0 10px 30px rgba(231, 76, 60, 0.3);
              ">
                <i class="ti ti-news" style="font-size: 2.5rem; color: white;"></i>
              </div>
            </div>

            <!-- News Title -->
            <h1 class="hero-title" style="
              color: white;
              font-size: clamp(2rem, 5vw, 3rem);
              font-weight: 700;
              line-height: 1.2;
              margin-bottom: 1rem;
              text-shadow: 0 4px 20px rgba(0,0,0,0.3);
              position: relative;
              z-index: 3;
            ">
              مركز الأخبار التعليمية
            </h1>

            <!-- News Subtitle -->
            <p class="hero-subtitle" style="
              color: rgba(255, 255, 255, 0.9);
              font-size: 1.2rem;
              margin-bottom: 2rem;
              position: relative;
              z-index: 3;
            ">
              آخر الأخبار والمستجدات في عالم التعليم
            </p>

            <!-- Stats -->
            <div class="hero-stats" style="
              display: flex;
              justify-content: center;
              flex-wrap: wrap;
              gap: 2rem;
              margin-bottom: 2rem;
              position: relative;
              z-index: 3;
            ">
              <div class="stat-item" style="
                display: flex;
                align-items: center;
                gap: 0.5rem;
                color: rgba(255, 255, 255, 0.9);
                font-size: 0.95rem;
              ">
                <div class="stat-icon" style="
                  width: 2.5rem;
                  height: 2.5rem;
                  background: linear-gradient(135deg, #3498db, #2980b9);
                  border-radius: 50%;
                  display: flex;
                  align-items: center;
                  justify-content: center;
                  box-shadow: 0 4px 15px rgba(52, 152, 219, 0.3);
                ">
                  <i class="ti ti-news" style="color: white; font-size: 1.1rem;"></i>
                </div>
                <div class="stat-content">
                  <div style="font-size: 0.8rem; opacity: 0.8;">إجمالي الأخبار</div>
                  <div style="font-weight: 600;">{{ number_format($news->total()) }}</div>
                </div>
              </div>

              <div class="stat-item" style="
                display: flex;
                align-items: center;
                gap: 0.5rem;
                color: rgba(255, 255, 255, 0.9);
                font-size: 0.95rem;
              ">
                <div class="stat-icon" style="
                  width: 2.5rem;
                  height: 2.5rem;
                  background: linear-gradient(135deg, #f39c12, #e67e22);
                  border-radius: 50%;
                  display: flex;
                  align-items: center;
                  justify-content: center;
                  box-shadow: 0 4px 15px rgba(243, 156, 18, 0.3);
                ">
                  <i class="ti ti-category" style="color: white; font-size: 1.1rem;"></i>
                </div>
                <div class="stat-content">
                  <div style="font-size: 0.8rem; opacity: 0.8;">التصنيفات</div>
                  <div style="font-weight: 600;">{{ isset($categories) ? $categories->count() : 0 }}</div>
                </div>
              </div>

              <div class="stat-item" style="
                display: flex;
                align-items: center;
                gap: 0.5rem;
                color: rgba(255, 255, 255, 0.9);
                font-size: 0.95rem;
              ">
                <div class="stat-icon" style="
                  width: 2.5rem;
                  height: 2.5rem;
                  background: linear-gradient(135deg, #9b59b6, #8e44ad);
                  border-radius: 50%;
                  display: flex;
                  align-items: center;
                  justify-content: center;
                  box-shadow: 0 4px 15px rgba(155, 89, 182, 0.3);
                ">
                  <i class="ti ti-clock" style="color: white; font-size: 1.1rem;"></i>
                </div>
                <div class="stat-content">
                  <div style="font-size: 0.8rem; opacity: 0.8;">تحديث يومي</div>
                  <div style="font-weight: 600;">24/7</div>
                </div>
              </div>
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
        <i class="ti ti-bell"></i>
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
        <i class="ti ti-speakerphone"></i>
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
        <i class="ti ti-rss"></i>
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
      animation: patternMove 20s linear infinite;
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
      <li class="breadcrumb-item active" aria-current="page" style="color: #6c757d;">
        <i class="ti ti-news me-1"></i>الأخبار
      </li>
    </ol>
  </nav>
</div>

<!-- News Content Section -->
<div class="container mt-5">
  <div class="row g-4">
    <!-- Categories Sidebar -->
    <div class="col-lg-3 col-md-4">
      <div class="edu-card sticky-top" style="top: 2rem;">
        <div class="card-header" style="
          background: linear-gradient(135deg, var(--edu-primary), #286aad);
          color: white;
          border-radius: 1rem 1rem 0 0;
          padding: 1.5rem;
        ">
          <h5 class="mb-0 d-flex align-items-center">
            <i class="ti ti-category me-2"></i>التصنيفات
          </h5>
        </div>
        <div class="card-body p-0">
          <div class="list-group list-group-flush">
            <a href="{{ route('content.frontend.news.index', ['database' => $database]) }}"
              class="list-group-item list-group-item-action d-flex justify-content-between align-items-center border-0 category-filter"
              style="padding: 1rem 1.5rem; transition: all 0.3s ease;"
              data-category-id=""
              onmouseover="this.style.background='linear-gradient(135deg, var(--edu-primary), #286aad)'; this.style.color='white'; this.querySelector('.badge').style.background='rgba(255,255,255,0.2)';"
              onmouseout="this.style.background=''; this.style.color=''; this.querySelector('.badge').style.background='';">
              <span><i class="ti ti-list me-2"></i>جميع الأخبار</span>
              <span class="badge" style="background: var(--edu-primary); color: white; border-radius: 1rem;">{{ number_format($news->total()) }}</span>
            </a>
            @if(isset($categories))
            @foreach($categories as $cat)
            <a href="{{ route('content.frontend.news.category', ['database' => $database, 'category' => $cat->id]) }}"
              class="list-group-item list-group-item-action d-flex justify-content-between align-items-center border-0 category-filter"
              style="padding: 1rem 1.5rem; transition: all 0.3s ease;"
              data-category-id="{{ $cat->id }}"
              onmouseover="this.style.background='linear-gradient(135deg, var(--edu-primary), #286aad)'; this.style.color='white'; this.querySelector('.badge').style.background='rgba(255,255,255,0.2)';"
              onmouseout="this.style.background=''; this.style.color=''; this.querySelector('.badge').style.background='';">
              <span><i class="ti ti-tag me-2"></i>{{ $cat->name }}</span>
              <span class="badge" style="background: var(--edu-primary); color: white; border-radius: 1rem;">{{ number_format($cat->news_count ?? 0) }}</span>
            </a>
            @endforeach
            @endif
          </div>
        </div>
      </div>

      <!-- Quick Stats Card -->
      <div class="edu-card mt-4">
        <div class="card-body text-center" style="padding: 2rem;">
          <div class="mb-3">
            <div class="edu-icon-circle mx-auto" style="
              background: linear-gradient(135deg, #f39c12, #e67e22);
              width: 4rem;
              height: 4rem;
              display: flex;
              align-items: center;
              justify-content: center;
            ">
              <i class="ti ti-chart-bar" style="font-size: 2rem; color: white;"></i>
            </div>
          </div>
          <h6 class="mb-2">إحصائيات سريعة</h6>
          <div class="row g-3 text-center">
            <div class="col-6">
              <div class="stat-number" style="font-size: 1.5rem; font-weight: 700; color: var(--edu-primary);">{{ number_format($news->total()) }}</div>
              <div class="stat-label" style="font-size: 0.8rem; color: #6c757d;">خبر</div>
            </div>
            <div class="col-6">
              <div class="stat-number" style="font-size: 1.5rem; font-weight: 700; color: var(--edu-primary);">{{ isset($categories) ? $categories->count() : 0 }}</div>
              <div class="stat-label" style="font-size: 0.8rem; color: #6c757d;">تصنيف</div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- News List -->
    <div class="col-lg-9 col-md-8">
      @if($news->count() > 0)
      <div class="row g-4" id="news-container">
        @foreach($news as $item)
        <div class="col-xl-6 col-lg-12 col-md-12">
          <article class="edu-card news-card h-100" style="
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
          " onmouseover="this.style.transform='translateY(-5px)'; this.style.boxShadow='0 15px 35px rgba(0,0,0,0.1)';" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='';">
            @if($item->image)
            <div class="news-image-container" style="position: relative; overflow: hidden; display: flex; justify-content: center; align-items: center; padding: 1rem 0;">
              <div class="edu-icon-circle" style="
                width: 120px;
                height: 120px;
                border-radius: 50%;
                overflow: hidden;
                box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
                border: 3px solid white;
                position: relative;
                background: linear-gradient(135deg, #e74c3c, #c0392b);
              ">
                <img src="{{ asset('storage/' . $item->image) }}"
                  alt="{{ $item->alt ?? $item->title }}"
                  loading="lazy"
                  style="
                    width: 100%;
                    height: 100%;
                    object-fit: cover;
                    transition: transform 0.3s ease;
                  " onmouseover="this.style.transform='scale(1.1)';" onmouseout="this.style.transform='scale(1)';"
                >
              </div>

              <!-- Category Badge -->
              @if($item->category)
              <div class="category-badge" style="
                position: absolute;
                top: 1rem;
                right: 1rem;
                background: linear-gradient(135deg, #e74c3c, #c0392b);
                color: white;
                padding: 0.3rem 0.8rem;
                border-radius: 1rem;
                font-size: 0.75rem;
                font-weight: 600;
                box-shadow: 0 4px 15px rgba(231, 76, 60, 0.3);
                z-index: 2;
              ">
                {{ $item->category->name }}
              </div>
              @endif

              <!-- Read More Badge -->
              <div class="read-more-badge" style="
                position: absolute;
                bottom: 1rem;
                left: 1rem;
                background: rgba(255, 255, 255, 0.9);
                color: var(--edu-primary);
                padding: 0.4rem 1rem;
                border-radius: 2rem;
                font-size: 0.8rem;
                font-weight: 600;
                opacity: 0;
                transform: translateY(10px);
                transition: all 0.3s ease;
                z-index: 2;
              ">
                <i class="ti ti-arrow-right me-1"></i>
                اقرأ المزيد
              </div>
            </div>
            @endif

            <div class="card-body" style="padding: 1.5rem;">
 
              <!-- News Meta -->
              <div class="news-meta d-flex align-items-center justify-content-between" style="
                font-size: 0.85rem;
                color: #6c757d;
              ">
                @if($recentNews)
  @if(isset($recentNews) && $recentNews->count() > 0)
                @foreach($recentNews as $recentItem)
                <div class="recent-article-item d-flex align-items-center mb-3 p-2 rounded"
                  style="background: rgba(255, 255, 255, 0.05); transition: all 0.3s ease;"
                  onmouseover="this.style.background='rgba(255, 255, 255, 0.1)'; this.style.transform='translateX(5px)';" onmouseout="this.style.background='rgba(255, 255, 255, 0.05)'; this.style.transform='translateX(0)';">
                  @if($recentItem->image)
                    <div class="edu-icon-circle me-3" style="width: 3rem; height: 3rem; overflow: hidden; border-radius: 50%; border: 2px solid white; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1); background: linear-gradient(135deg, var(--edu-purple), #8e44ad);">
                      <img src="{{ asset('storage/' . $recentItem->image) }}"
                           alt="{{ $recentItem->title }}"
                           style="width: 100%; height: 100%; object-fit: cover; transition: transform 0.3s ease;"
                           onmouseover="this.style.transform='scale(1.1)';"
                           onmouseout="this.style.transform='scale(1)';" />
                    </div>
                  @else
                    <div class="edu-icon-circle me-3" style="background: var(--edu-purple); width: 3rem; height: 3rem;">
                      <i class="ti ti-news text-white"></i>
                    </div>
                  @endif
                  <div>
                    <h6 class="mb-1" style="font-size: 0.9rem; font-weight: 600; color: var(--edu-dark);">{{ $recentItem->title }}</h6>
                    <small class="text-muted">{{ $recentItem->created_at->format('d M Y') }}</small>
                  </div>
                </div>
                @if($recentItem->views)
                <div class="meta-item d-flex align-items-center">
                  <div class="meta-icon" style="
                    width: 1.8rem;
                    height: 1.8rem;
                    background: linear-gradient(135deg, #9b59b6, #8e44ad);
                    border-radius: 50%;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    margin-left: 0.5rem;
                  ">
                    <i class="ti ti-eye" style="color: white; font-size: 0.8rem;"></i>
                  </div>
                  <span>{{ number_format($recentItem->views) }} مشاهدة</span>
                </div>
                @endif
                @endforeach
                @endif
                @endif
              </div>
            </div>
          </article>
        </div>
        @endforeach
      </div>

      <!-- Pagination -->
      @if($news->hasPages())
      <div class="d-flex justify-content-center mt-5">
        <div class="pagination-wrapper" style="
          background: white;
          border-radius: 2rem;
          padding: 0.5rem;
          box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        ">
          {{ $news->links() }}
        </div>
      </div>
      @endif

      @else
      <!-- Empty State -->
      <div class="text-center py-5">
        <div class="empty-state" style="max-width: 400px; margin: 0 auto;">
          <div class="mb-4">
            <div class="edu-icon-circle mx-auto" style="
              background: linear-gradient(135deg, #e74c3c, #c0392b);
              width: 5rem;
              height: 5rem;
              display: flex;
              align-items: center;
              justify-content: center;
            ">
              <i class="ti ti-news-off" style="font-size: 2.5rem; color: white;"></i>
            </div>
          </div>
          <h4 class="mb-3">لا توجد أخبار متاحة</h4>
          <p class="text-muted mb-4">لم يتم العثور على أي أخبار في هذا التصنيف حالياً.</p>
          <a href="{{ route('content.frontend.news.index', ['database' => $database]) }}" class="btn btn-primary">
            <i class="ti ti-arrow-right me-2"></i>عرض جميع الأخبار
          </a>
        </div>
      </div>
      @endif
    </div>
  </div>
</div>
@endsection

<style>
/* News Index Page Custom Styles */

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

/* Background Pattern Animation */
@keyframes patternMove {
    0% {
        background-position: 0 0, 0 0, 0 0;
    }
    100% {
        background-position: 50px 50px, 60px 60px, 40px 40px;
    }
}

/* Hero Section Enhancements */
.hero-news-card {
    transition: all 0.3s ease;
}
.hero-news-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 25px 50px rgba(0, 0, 0, 0.15) !important;
}

.stat-item {
    transition: all 0.3s ease;
}
.stat-item:hover {
    transform: translateY(-2px);
}

.stat-icon {
    transition: all 0.3s ease;
}
.stat-item:hover .stat-icon {
    transform: scale(1.1);
}

/* News Cards */
.news-card {
    border-radius: 1rem;
    border: none;
    box-shadow: 0 4px 20px rgba(0,0,0,0.08);
    overflow: hidden;
}

.news-card:hover .news-image-container img {
    transform: scale(1.05);
}

.category-badge {
    transition: all 0.3s ease;
}
.category-badge:hover {
    transform: scale(1.05);
}

.read-more-badge {
    transition: all 0.3s ease;
    opacity: 0;
    transform: translateY(10px);
}

.news-card:hover .read-more-badge {
    opacity: 1;
    transform: translateY(0);
}

.news-title a:hover {
    color: var(--edu-primary) !important;
}

.meta-item {
    transition: all 0.3s ease;
}
.meta-item:hover {
    transform: translateX(3px);
}

.meta-icon {
    transition: all 0.3s ease;
}
.meta-item:hover .meta-icon {
    transform: scale(1.1);
}

/* Categories Sidebar */
.category-filter {
    transition: all 0.3s ease;
    border-radius: 0.5rem;
    margin: 0.2rem 0.5rem;
}

.category-filter:hover {
    background: linear-gradient(135deg, var(--edu-primary), #286aad) !important;
    color: white !important;
    transform: translateX(5px);
}

.category-filter:hover .badge {
    background: rgba(255,255,255,0.2) !important;
    color: white !important;
}

/* Quick Stats Card */
.stat-number {
    transition: all 0.3s ease;
}

.edu-card:hover .stat-number {
    transform: scale(1.1);
    color: #e74c3c !important;
}

/* Pagination */
.pagination-wrapper {
    transition: all 0.3s ease;
}

.pagination-wrapper:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.15) !important;
}

/* Empty State */
.empty-state {
    transition: all 0.3s ease;
}

.empty-state:hover {
    transform: translateY(-5px);
}

.empty-state .edu-icon-circle {
    transition: all 0.3s ease;
}

.empty-state:hover .edu-icon-circle {
    transform: scale(1.1) rotate(10deg);
}

/* Responsive Design */
@media (max-width: 768px) {
    .hero-news-card {
        padding: 2rem 1.5rem !important;
        margin: 0 1rem;
    }

    .hero-title {
        font-size: 1.8rem !important;
    }

    .hero-stats {
        flex-direction: column;
        gap: 1rem !important;
        align-items: center;
    }

    .stat-item {
        justify-content: center;
    }

    .floating-icon {
        display: none;
    }

    .news-card {
        margin-bottom: 1.5rem;
    }

    .category-filter {
        padding: 0.8rem 1rem !important;
    }
}

@media (max-width: 576px) {
    .hero-news-card {
        padding: 1.5rem 1rem !important;
        border-radius: 1.5rem !important;
    }

    .hero-title {
        font-size: 1.5rem !important;
    }

    .stat-icon {
        width: 2rem !important;
        height: 2rem !important;
    }

    .stat-icon i {
        font-size: 1rem !important;
    }

    .news-image-container {
        height: 180px !important;
    }

    .meta-icon {
        width: 1.5rem !important;
        height: 1.5rem !important;
    }

    .meta-icon i {
        font-size: 0.7rem !important;
    }
}
</style>

@push('page-script')
@vite(['resources/assets/js/pages/news.js'])
<script>
  document.addEventListener('DOMContentLoaded', function() {
    const filterUrl = '{{ $filterUrl }}';

    // Category filter functionality with loading state
    document.querySelectorAll('.category-filter').forEach(link => {
      link.addEventListener('click', async (e) => {
        e.preventDefault();
        const categoryId = e.target.dataset.categoryId;
        const container = document.getElementById('news-container');

        try {
          container.style.opacity = '0.5';
          const response = await fetch(`${filterUrl}?category_id=${categoryId}`);
          const data = await response.json();

          if (data.success) {
            container.innerHTML = data.html;
            // Re-initialize animations for new content
            container.querySelectorAll('.animate__animated').forEach(el => {
              el.classList.add('animate__fadeInUp');
            });
          }
        } catch (error) {
          console.error('Error filtering news:', error);
        } finally {
          container.style.opacity = '1';
        }
      });
    });

    // Smooth scroll with offset for fixed header
    document.querySelectorAll('.scroll-btn').forEach(anchor => {
      anchor.addEventListener('click', function(e) {
        e.preventDefault();
        const target = document.querySelector(this.getAttribute('href'));
        const headerOffset = 80;
        if (target) {
          const elementPosition = target.getBoundingClientRect().top;
          const offsetPosition = elementPosition - headerOffset;

          window.scrollBy({
            top: offsetPosition,
            behavior: 'smooth'
          });
        }
      });
    });

    // Lazy loading for images
    if ('loading' in HTMLImageElement.prototype) {
      const images = document.querySelectorAll('img[loading="lazy"]');
      images.forEach(img => {
        img.src = img.src;
      });
    }
  });
</script>
@endpush
