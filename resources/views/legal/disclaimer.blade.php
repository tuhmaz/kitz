@php
$configData = Helper::appClasses();
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Detection\MobileDetect;

$detect = new MobileDetect;
@endphp

@extends('layouts/layoutFront')

@section('title', __('إخلاء المسؤولية'))

@section('vendor-style')
@vite(['resources/assets/vendor/scss/home.scss'])
@endsection

@section('content')
<!-- Hero Section -->
<section class="hero-section" style="background: linear-gradient(135deg, #e74c3c 0%, #c0392b 50%, #a93226 100%); min-height: 60vh; position: relative; overflow: hidden;">
  <!-- Floating Elements -->
  <div class="floating-elements">
    <div class="floating-icon" style="top: 15%; left: 10%; color: #fff; animation-delay: 0s;">
      <i class="ti ti-alert-triangle"></i>
    </div>
    <div class="floating-icon" style="top: 25%; right: 15%; color: #fff; animation-delay: 1s;">
      <i class="ti ti-info-circle"></i>
    </div>
    <div class="floating-icon" style="bottom: 30%; left: 20%; color: #fff; animation-delay: 2s;">
      <i class="ti ti-shield-x"></i>
    </div>
    <div class="floating-icon" style="bottom: 20%; right: 25%; color: #fff; animation-delay: 3s;">
      <i class="ti ti-exclamation-mark"></i>
    </div>
  </div>

  <div class="container">
    <div class="row align-items-center justify-content-center" style="min-height: 60vh;">
      <div class="col-lg-8 text-center">
        <div class="hero-content" style="background: rgba(255, 255, 255, 0.1); backdrop-filter: blur(20px); border-radius: 30px; padding: 3rem; border: 1px solid rgba(255, 255, 255, 0.2);">
          <div class="hero-icon mb-4">
            <i class="ti ti-alert-triangle" style="font-size: 4rem; color: #fff; text-shadow: 0 4px 20px rgba(0,0,0,0.3);"></i>
          </div>
          <h1 class="display-4 fw-bold text-white mb-3" style="text-shadow: 0 4px 20px rgba(0,0,0,0.3);">
            {{ __('إخلاء المسؤولية') }}
          </h1>
          <p class="lead text-white mb-0" style="text-shadow: 0 2px 10px rgba(0,0,0,0.3);">
            {{ __('معلومات مهمة حول حدود المسؤولية واستخدام موقع علم للتعليم') }}
          </p>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- Breadcrumb -->
<nav aria-label="breadcrumb" style="background: transparent; padding: 1rem 0;">
  <div class="container">
    <ol class="breadcrumb mb-0" style="background: transparent; padding: 0;">
      <li class="breadcrumb-item">
        <a href="{{ route('home') }}" style="color: #e74c3c; text-decoration: none; transition: all 0.3s ease;">
          <i class="ti ti-home me-1"></i>{{ __('الرئيسية') }}
        </a>
      </li>
      <li class="breadcrumb-item active" style="color: #6c757d;">{{ __('إخلاء المسؤولية') }}</li>
    </ol>
  </div>
</nav>

<!-- Content Section -->
<section class="py-5">
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-lg-10">
        <div class="card" style="border: none; border-radius: 20px; box-shadow: 0 10px 40px rgba(0,0,0,0.1); overflow: hidden;">
          <!-- Card Header -->
          <div class="card-header" style="background: linear-gradient(135deg, #e74c3c 0%, #c0392b 100%); border: none; padding: 2rem;">
            <div class="row align-items-center">
              <div class="col-md-2 text-center">
                <div style="width: 60px; height: 60px; background: rgba(255,255,255,0.2); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto;">
                  <i class="ti ti-alert-triangle" style="font-size: 1.8rem; color: #fff;"></i>
                </div>
              </div>
              <div class="col-md-8">
                <h2 class="h3 text-white mb-1 fw-bold">{{ __('إخلاء المسؤولية') }}</h2>
                <p class="text-white mb-0 opacity-90">{{ __('معلومات مهمة حول حدود المسؤولية والاستخدام الآمن للموقع') }}</p>
              </div>
              <div class="col-md-2 text-center">
                <span class="badge" style="background: rgba(255,255,255,0.2); color: #fff; padding: 0.5rem 1rem; border-radius: 20px; font-size: 0.9rem;">
                  <i class="ti ti-alert-circle me-1"></i>{{ __('مهم') }}
                </span>
              </div>
            </div>
          </div>

          <!-- Card Body -->
          <div class="card-body disclaimer-content" style="padding: 3rem; line-height: 1.8; color: #2d3748;">

<div class="container my-5"> <h1 class="mb-4">{{ __('Disclaimer') }}</h1> <div class="card"> <div class="card-body">

    <p>آخر تحديث: 18 يناير 2025</p>

    <h2>1. الغرض من الموقع</h2>
    <p>موقع <strong>{{ config('settings.site_name') }}</strong> (<a href="{{ $settings['canonical_url'] ?? 'https://alemedu.com' }}" target="_blank" class="text-decoration-none">{{ $settings['canonical_url'] ?? 'https://alemedu.com' }}</a>) هو منصة تعليمية تهدف إلى تقديم محتوى تعليمي محدث ومصمم لدعم العملية التعليمية وفقًا للمنهاج الأردني. جميع المعلومات والمحتويات المقدمة على هذا الموقع هي لأغراض تعليمية وإرشادية فقط.</p>

    <h2>2. دقة المعلومات</h2>
    <p>نحن نسعى لضمان دقة وصحة جميع المعلومات المقدمة على الموقع. ومع ذلك، لا نضمن أن تكون جميع المواد والمحتويات خالية تمامًا من الأخطاء أو محدثة بشكل كامل. يتحمل المستخدم مسؤولية التحقق من المعلومات قبل الاعتماد عليها.</p>

    <h2>3. حدود المسؤولية</h2>
    <p>موقع {{ config('settings.site_name') }} غير مسؤول عن:</p>
    <ul>
        <li>أي أضرار مباشرة أو غير مباشرة قد تنجم عن استخدامك للموقع أو الاعتماد على محتوياته.</li>
        <li>أي خسائر أو أضرار تتعلق بتنزيل المرفقات أو المستندات التعليمية من الموقع.</li>
        <li>أي انقطاع في الخدمة بسبب مشكلات تقنية أو خارجية.</li>
    </ul>

            <h2>5. الاستخدام الشخصي وغير التجاري</h2>
            <p>جميع المحتويات والمواد التعليمية المقدمة على الموقع مصممة للاستخدام الشخصي وغير التجاري. يُحظر نسخ أو إعادة توزيع أي محتوى دون إذن كتابي مسبق.</p>

            <h2>6. تحديث إخلاء المسؤولية</h2>
            <p>يحتفظ موقع {{ config('settings.site_name') }} بالحق في تعديل هذا إخلاء المسؤولية في أي وقت دون إشعار مسبق. ينصح بمراجعة هذه الصفحة بشكل دوري للاطلاع على أي تغييرات.</p>

            <h2>7. التواصل معنا</h2>
            <p>إذا كانت لديك أي أسئلة أو اقتراحات، يسعدنا أن نتواصل معك عبر:</p>
            <div class="d-flex flex-column gap-2">
                <div class="d-flex align-items-center">
                    <i class="ti ti-mail fs-4 text-primary me-2"></i>
                    <a href="mailto:{{ $settings['contact_email'] ?? 'info@alemedu.com' }}" class="d-flex align-items-center">
                        {{ $settings['contact_email'] ?? 'info@alemedu.com' }}
                    </a>
                </div>
                <div class="d-flex align-items-center">
                    <i class="ti ti-world fs-4 text-primary me-2"></i>
                    <a href="{{ $settings['canonical_url'] ?? 'https://alemedu.com' }}" target="_blank" class="text-decoration-none">{{ $settings['canonical_url'] ?? 'https://alemedu.com' }}</a>
                </div>
            </div>
          </div>

          <!-- Card Footer -->
          <div class="card-footer" style="background: #ffebee; border: none; padding: 2rem; text-align: center;">
            <div class="row align-items-center">
              <div class="col-md-6">
                <p class="mb-0 text-muted">
                  <i class="ti ti-info-circle me-1"></i>
                  آخر تحديث: {{ date('Y-m-d') }}
                </p>
              </div>
              <div class="col-md-6 text-md-end">
                <a href="{{ route('home') }}" class="btn btn-danger" style="border-radius: 15px; padding: 0.75rem 2rem;">
                  <i class="ti ti-arrow-right me-1"></i>
                  العودة للرئيسية
                </a>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<style>
/* Disclaimer Content Styling */
.disclaimer-content {
  font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
}

.disclaimer-content h1, .disclaimer-content h2, .disclaimer-content h3, .disclaimer-content h4, .disclaimer-content h5, .disclaimer-content h6 {
  color: #e74c3c;
  margin-top: 2rem;
  margin-bottom: 1rem;
  font-weight: 600;
}

.disclaimer-content h1 {
  font-size: 2rem;
  border-bottom: 3px solid #e74c3c;
  padding-bottom: 0.5rem;
}

.disclaimer-content h2 {
  font-size: 1.5rem;
  color: #2d3748;
}

.disclaimer-content p {
  margin-bottom: 1.2rem;
  text-align: justify;
}

.disclaimer-content ul, .disclaimer-content ol {
  margin-bottom: 1.5rem;
  padding-left: 2rem;
}

.disclaimer-content li {
  margin-bottom: 0.5rem;
}

.disclaimer-content blockquote {
  border-left: 4px solid #e74c3c;
  background: rgba(231, 76, 60, 0.05);
  padding: 1rem 1.5rem;
  margin: 1.5rem 0;
  border-radius: 0 10px 10px 0;
}

.disclaimer-content strong {
  color: #e74c3c;
  font-weight: 600;
}

.disclaimer-content a {
  color: #e74c3c;
  text-decoration: none;
  border-bottom: 1px solid transparent;
  transition: all 0.3s ease;
}

.disclaimer-content a:hover {
  border-bottom-color: #e74c3c;
}

/* Floating Animation */
@keyframes float {
  0%, 100% { transform: translateY(0px) rotate(0deg); }
  50% { transform: translateY(-20px) rotate(5deg); }
}

.floating-icon {
  position: absolute;
  width: 60px;
  height: 60px;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  background: rgba(255, 255, 255, 0.1);
  backdrop-filter: blur(10px);
  animation: float 6s ease-in-out infinite;
  font-size: 1.5rem;
}

/* Responsive */
@media (max-width: 768px) {
  .floating-elements {
    display: none;
  }
  
  .hero-content {
    padding: 1.5rem !important;
  }
  
  .disclaimer-content {
    padding: 2rem !important;
  }
  
  .card-header .row {
    text-align: center;
  }
  
  .card-header .col-md-2,
  .card-header .col-md-8,
  .card-header .col-md-2 {
    margin-bottom: 1rem;
  }
}
</style>
@endsection
