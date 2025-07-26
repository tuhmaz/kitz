@extends('layouts.layoutFront')

@php
use Detection\MobileDetect;
$detect = new MobileDetect;
@endphp

@section('title', __('all_keywords'))

@section('page-style')
@vite([
  'resources/assets/vendor/scss/pages/front-page-help-center.scss',
  'resources/assets/vendor/scss/home.scss'
])
 
@endsection

@section('content')
<!-- Hero Section -->
<div class="edu-gradient-bg" style="padding-top: 80px;">
  <section class="edu-welcome-section">
    <div class="container">
      <div class="edu-welcome-content">
        <div class="row align-items-center justify-content-center">
          <div class="col-lg-8 text-center">
            <!-- Keywords Icon -->
            <div class="edu-icon-circle mx-auto mb-4" style="background: rgba(255,255,255,0.2); backdrop-filter: blur(15px); border: 1px solid rgba(255,255,255,0.3); width: 5rem; height: 5rem;">
              <i class="ti ti-tags text-white" style="font-size: 2rem;"></i>
            </div>

            <h1 class="display-5 fw-bold mb-4" style="color: white; line-height: 1.2;">
              {{ __('all_keywords') }}
            </h1>
            <p class="lead mb-4" style="color: rgba(255, 255, 255, 0.9); font-size: 1.1rem;">
              {{ __('explore_keywords') }}
            </p>

            <!-- Statistics -->
            <div class="row g-3 justify-content-center">
              <div class="col-md-4">
                <div class="text-center p-3" style="background: rgba(255,255,255,0.1); backdrop-filter: blur(15px); border: 1px solid rgba(255,255,255,0.2); border-radius: 1rem;">
                  <div class="edu-icon-circle mx-auto mb-2" style="background: rgba(255,255,255,0.2); backdrop-filter: blur(10px); border: 1px solid rgba(255,255,255,0.3); width: 3rem; height: 3rem;">
                    <i class="ti ti-article text-white" style="font-size: 1.2rem;"></i>
                  </div>
                  <p class="small mb-1" style="color: rgba(255,255,255,0.9); font-size: 0.75rem;">كلمات المقالات</p>
                  <p class="h5 fw-bold mb-0" style="color: white;">{{ $articleKeywords->count() }}</p>
                </div>
              </div>
              <div class="col-md-4">
                <div class="text-center p-3" style="background: rgba(255,255,255,0.1); backdrop-filter: blur(15px); border: 1px solid rgba(255,255,255,0.2); border-radius: 1rem;">
                  <div class="edu-icon-circle mx-auto mb-2" style="background: rgba(255,255,255,0.2); backdrop-filter: blur(10px); border: 1px solid rgba(255,255,255,0.3); width: 3rem; height: 3rem;">
                    <i class="ti ti-news text-white" style="font-size: 1.2rem;"></i>
                  </div>
                  <p class="small mb-1" style="color: rgba(255,255,255,0.9); font-size: 0.75rem;">كلمات الأخبار</p>
                  <p class="h5 fw-bold mb-0" style="color: white;">{{ $newsKeywords->count() }}</p>
                </div>
              </div>
              <div class="col-md-4">
                <div class="text-center p-3" style="background: rgba(255,255,255,0.1); backdrop-filter: blur(15px); border: 1px solid rgba(255,255,255,0.2); border-radius: 1rem;">
                  <div class="edu-icon-circle mx-auto mb-2" style="background: rgba(255,255,255,0.2); backdrop-filter: blur(10px); border: 1px solid rgba(255,255,255,0.3); width: 3rem; height: 3rem;">
                    <i class="ti ti-hash text-white" style="font-size: 1.2rem;"></i>
                  </div>
                  <p class="small mb-1" style="color: rgba(255,255,255,0.9); font-size: 0.75rem;">إجمالي الكلمات</p>
                  <p class="h5 fw-bold mb-0" style="color: white;">{{ $articleKeywords->count() + $newsKeywords->count() }}</p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Floating Elements -->
    <div class="floating-element" style="position: absolute; top: 15%; left: 8%; width: 60px; height: 60px; background: linear-gradient(135deg, #3498db, #2980b9); border-radius: 50%; display: flex; align-items: center; justify-content: center; animation: float 6s ease-in-out infinite;">
      <i class="ti ti-article text-white" style="font-size: 1.5rem;"></i>
    </div>
    <div class="floating-element" style="position: absolute; top: 25%; right: 12%; width: 50px; height: 50px; background: linear-gradient(135deg, #9b59b6, #8e44ad); border-radius: 50%; display: flex; align-items: center; justify-content: center; animation: float 8s ease-in-out infinite reverse;">
      <i class="ti ti-news text-white" style="font-size: 1.2rem;"></i>
    </div>
    <div class="floating-element" style="position: absolute; bottom: 25%; left: 15%; width: 45px; height: 45px; background: linear-gradient(135deg, #f39c12, #e67e22); border-radius: 50%; display: flex; align-items: center; justify-content: center; animation: float 7s ease-in-out infinite;">
      <i class="ti ti-hash text-white" style="font-size: 1rem;"></i>
    </div>
    <div class="floating-element" style="position: absolute; bottom: 15%; right: 20%; width: 55px; height: 55px; background: linear-gradient(135deg, #e74c3c, #c0392b); border-radius: 50%; display: flex; align-items: center; justify-content: center; animation: float 9s ease-in-out infinite;">
      <i class="ti ti-tags text-white" style="font-size: 1.3rem;"></i>
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
      <li class="breadcrumb-item active" aria-current="page" style="color: var(--edu-dark);">
        <i class="ti ti-tags me-1"></i>{{ __('keywords') }}
      </li>
    </ol>
  </nav>
</div>

<section class="section-py bg-body first-section-pt" style="padding-top: 10px;">
  <div class="container">
    <div class="card px-3 mt-6 shadow-sm">
      <div class="row">
        <div class="content-header text-center bg-primary py-3">
          <h2 class="text-white">{{ __('article_keywords') }}</h2>
        </div>
        <div class="content-body text-center mt-3">
          @if($articleKeywords->count())
          <div class="keywords-cloud">
            @foreach($articleKeywords as $keyword)
            <a href="{{ route('keywords.indexByKeyword', ['database' => $database ?? session('database', 'jo'), 'keywords' => $keyword->keyword]) }}" class="keyword-item btn btn-outline-secondary m-1">
              {{ $keyword->keyword }}
            </a>
            @endforeach
          </div>
          @else
          <p>{{ __('no_article_keywords') }}</p>
          @endif
        </div>
      </div>
    </div>
  </div>
</section>

<section class="section-py bg-body first-section-pt" style="padding-top: 10px;">
  <div class="container">
    <div class="card px-3 mt-6 shadow-sm">
      <div class="row">
        <div class="content-header text-center bg-primary py-3">
          <h2 class="text-white">{{ __('news_keywords') }}</h2>
        </div>
        <div class="content-body text-center mt-3">
          @if($newsKeywords->count())
          <div class="keywords-cloud">
            @foreach($newsKeywords as $keyword)
            <a href="{{ route('keywords.indexByKeyword', ['database' => $database ?? session('database', 'jo'), 'keywords' => $keyword->keyword]) }}" class="keyword-item btn btn-outline-secondary m-1">
              {{ $keyword->keyword }}
            </a>
            @endforeach
          </div>
          @else
          <p>{{ __('no_news_keywords') }}</p>
          @endif
        </div>
      </div>
    </div>
  </div>
</section>

@endsection
