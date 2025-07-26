@php
    $configData = Helper::appClasses();
    use Detection\MobileDetect;
    $detect = new MobileDetect;
    
    // تحديد نوع الملف والأيقونة
    $fileType = $file->file_type ?? 'default';
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
        'icon' => 'ti ti-file-download',
        'color' => 'linear-gradient(135deg, #6366f1, #4f46e5)',
        'bg' => 'rgba(99, 102, 241, 0.1)',
        'text' => 'ملف'
      ]
    };
@endphp

@vite([
    'resources/assets/vendor/scss/home.scss'
])

@extends('layouts.layoutFront')

@section('page-style')
@vite(['resources/assets/vendor/js/waitdon.js'])
@endsection

@section('title', $file->file_Name)

@section('content')

<!-- Hero Section -->
<section class="position-relative overflow-hidden" style="background: linear-gradient(135deg, #1f36ad 0%, #286aad 50%, #3b82f6 100%); min-height: 70vh; display: flex; align-items: center;">
  <!-- Background Effects -->
  <div class="position-absolute w-100 h-100" style="background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grain" width="100" height="100" patternUnits="userSpaceOnUse"><circle cx="50" cy="50" r="1" fill="%23ffffff" opacity="0.1"/></pattern></defs><rect width="100" height="100" fill="url(%23grain)"/></svg>'); opacity: 0.3;"></div>
  
  <!-- Floating Elements -->
  <div class="position-absolute" style="top: 15%; right: 10%; width: 120px; height: 120px; background: rgba(255,255,255,0.1); border-radius: 50%; animation: float 6s ease-in-out infinite;"></div>
  <div class="position-absolute" style="bottom: 25%; left: 15%; width: 80px; height: 80px; background: rgba(255,255,255,0.1); border-radius: 50%; animation: float 4s ease-in-out infinite reverse;"></div>
  
  <div class="container position-relative">
    <div class="row align-items-center justify-content-center">
      <div class="col-lg-8 text-center">
        <!-- File Type Badge -->
        <div class="mb-4">
          <span class="badge px-4 py-3" style="background: {{ $fileConfig['bg'] }}; color: #1e293b; font-size: 1.1rem; border-radius: 25px; border: 2px solid rgba(255,255,255,0.3);">
            <i class="{{ $fileConfig['icon'] }} me-2" style="font-size: 1.3rem;"></i>
            {{ $fileConfig['text'] }} File
          </span>
        </div>
        
        <!-- File Title -->
        <h1 class="display-5 fw-bold text-white mb-4" style="text-shadow: 0 4px 20px rgba(0,0,0,0.3); line-height: 1.2;">
          {{ $file->file_Name }}
        </h1>
        
        <!-- Download Icon -->
        <div class="mb-4">
          <div class="d-inline-flex align-items-center justify-content-center mx-auto" style="width: 120px; height: 120px; background: rgba(255,255,255,0.15); backdrop-filter: blur(20px); border: 2px solid rgba(255,255,255,0.3); border-radius: 50%; box-shadow: 0 20px 60px rgba(0,0,0,0.2);">
            <i class="ti ti-download text-white" style="font-size: 3rem;"></i>
          </div>
        </div>
        
        <!-- Welcome Message -->
        <p class="lead text-white mb-0" style="opacity: 0.9; font-size: 1.2rem; line-height: 1.6;">
          {{ __('Welcome to the waiting page for downloading files. Don\'t forget to support us by sharing articles on social media.') }}
        </p>
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
        <i class="ti ti-download me-1"></i>{{ __('Files') }}
      </li>
    </ol>
  </nav>
</div>

<!-- Main Content Section -->
<section class="py-5" style="background: #f8fafc;">
  <!-- Ads Section -->
  @if(config('settings.google_ads_desktop_download') || config('settings.google_ads_mobile_download'))
  <div class="container mb-4">
    <div class="text-center">
      @if($detect->isMobile())
      {!! config('settings.google_ads_mobile_download') !!}
      @else
      {!! config('settings.google_ads_desktop_download') !!}
      @endif
    </div>
  </div>
  @endif

  <div class="container">
    <div class="row justify-content-center">
      <div class="col-lg-8">
        <!-- Download Wait Card -->
        <div class="card border-0 shadow-sm mb-4" style="border-radius: 25px; overflow: hidden;">
          <div class="card-body p-5 text-center">
            <!-- Status Icon -->
            <div class="mb-4">
              <div class="d-inline-flex align-items-center justify-content-center mx-auto" style="width: 100px; height: 100px; background: linear-gradient(135deg, #f59e0b, #d97706); border-radius: 50%; box-shadow: 0 15px 35px rgba(245, 158, 11, 0.3);">
                <i class="ti ti-clock text-white" style="font-size: 2.5rem;"></i>
              </div>
            </div>
            
            <!-- Wait Message -->
            <h2 class="mb-3" style="color: #1f36ad; font-weight: 600;">
              {{ __('please_wait_download_link') }}
            </h2>
            
            <!-- Countdown -->
            <div class="mb-4">
              <p class="lead mb-2" style="color: #6b7280;">
                {{ __('download_available_in') }}
              </p>
              <div class="d-inline-flex align-items-center justify-content-center" style="width: 80px; height: 80px; background: linear-gradient(135deg, #ef4444, #dc2626); border-radius: 50%; box-shadow: 0 10px 25px rgba(239, 68, 68, 0.3);">
                <span id="countdown" class="text-white fw-bold" style="font-size: 1.5rem;">24</span>
              </div>
              <p class="mt-2" style="color: #6b7280;">{{ __('seconds') }}</p>
            </div>
            
            <!-- Download Link (Hidden Initially) -->
            <div id="downloadText" style="display: none;">
              <div class="mb-4">
                <div class="d-inline-flex align-items-center justify-content-center mx-auto" style="width: 100px; height: 100px; background: linear-gradient(135deg, #10b981, #059669); border-radius: 50%; box-shadow: 0 15px 35px rgba(16, 185, 129, 0.3); animation: pulse 2s infinite;">
                  <i class="ti ti-download text-white" style="font-size: 2.5rem;"></i>
                </div>
              </div>
              <a id="downloadLink" href="{{ route('download.wait', $file->id) }}" class="btn btn-lg" style="background: linear-gradient(135deg, #10b981, #059669); color: white; border-radius: 20px; padding: 1rem 3rem; font-size: 1.2rem; text-decoration: none; box-shadow: 0 10px 25px rgba(16, 185, 129, 0.3);">
                <i class="ti ti-download me-2"></i>{{ __('download_now') }}
              </a>
            </div>
            
            <!-- Progress Bar -->
            <div class="progress mx-auto my-4" style="width: 100%; max-width: 500px; height: 15px; border-radius: 10px; background: #e5e7eb;">
              <div class="progress-bar" id="progress-bar" role="progressbar" style="width: 0%; background: linear-gradient(135deg, #3b82f6, #1d4ed8); border-radius: 10px; transition: width 0.3s ease;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
            </div>
          </div>
        </div>
        
        <!-- Game Section -->
        <div class="card border-0 shadow-sm mb-4" style="border-radius: 25px; overflow: hidden;">
          <div class="card-body p-5">
            <div class="text-center mb-4">
              <h4 style="color: #1f36ad; font-weight: 600;">
                <i class="ti ti-target me-2"></i>{{ __('collect_points_to_end_countdown_faster') }}
              </h4>
              <p class="text-muted">اضغط على النقاط الحمراء لتسريع عملية التحميل</p>
            </div>
            
            <!-- Game Area -->
            <div class="d-flex justify-content-center mb-4">
              <div id="gameArea" style="position: relative; width: 350px; height: 350px; background: linear-gradient(135deg, #f8fafc, #e5e7eb); border-radius: 20px; border: 3px solid #e5e7eb; box-shadow: inset 0 4px 15px rgba(0,0,0,0.1);">
                <div id="clickableDot" style="position: absolute; width: 25px; height: 25px; background: linear-gradient(135deg, #ef4444, #dc2626); border-radius: 50%; cursor: pointer; box-shadow: 0 4px 15px rgba(239, 68, 68, 0.4); transition: all 0.2s ease;"></div>
              </div>
            </div>
            
            <!-- Score Display -->
            <div class="text-center">
              <div class="d-inline-flex align-items-center px-4 py-2" style="background: linear-gradient(135deg, #8b5cf6, #7c3aed); border-radius: 20px; box-shadow: 0 8px 25px rgba(139, 92, 246, 0.3);">
                <i class="ti ti-trophy text-white me-2" style="font-size: 1.2rem;"></i>
                <span class="text-white fw-bold">{{ __('Score') }}: </span>
                <span id="score" class="text-white fw-bold ms-2" style="font-size: 1.2rem;">0</span>
              </div>
            </div>
          </div>
        </div>
        
        <!-- Second Ads Section -->
        @if(config('settings.google_ads_desktop_download_2') || config('settings.google_ads_mobile_download_2'))
        <div class="card border-0 shadow-sm" style="border-radius: 25px; overflow: hidden;">
          <div class="card-body p-4 text-center">
            @if($detect->isMobile())
            {!! config('settings.google_ads_mobile_download_2') !!}
            @else
            {!! config('settings.google_ads_desktop_download_2') !!}
            @endif
          </div>
        </div>
        @endif
      </div>
    </div>
  </div>
</section>

@endsection
