@php
$configData = Helper::appClasses();
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Detection\MobileDetect;

$detect = new MobileDetect;
@endphp

@extends('layouts/layoutFront')

@section('title', __('سياسة ملفات تعريف الارتباط'))

@section('vendor-style')
@vite(['resources/assets/vendor/scss/home.scss'])
@endsection

@section('page-style')
<style>
/* Cookie Policy Specific Styles */
.cookie-hero {
  background: linear-gradient(135deg, #9b59b6 0%, #8e44ad 50%, #7d3c98 100%);
  position: relative;
  overflow: hidden;
  min-height: 400px;
  padding-top: 100px;
}

.cookie-hero::before {
  content: '';
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grain" width="100" height="100" patternUnits="userSpaceOnUse"><circle cx="20" cy="20" r="1" fill="%23ffffff" opacity="0.1"/><circle cx="80" cy="40" r="1" fill="%23ffffff" opacity="0.1"/><circle cx="40" cy="80" r="1" fill="%23ffffff" opacity="0.1"/></pattern></defs><rect width="100" height="100" fill="url(%23grain)"/></svg>') repeat;
  opacity: 0.3;
}

.cookie-floating-icons {
  position: absolute;
  width: 100%;
  height: 100%;
  overflow: hidden;
  pointer-events: none;
}

.cookie-floating-icon {
  position: absolute;
  font-size: 2rem;
  opacity: 0.6;
  animation: cookieFloat 6s ease-in-out infinite;
}

.cookie-floating-icon:nth-child(1) {
  top: 20%;
  left: 10%;
  color: #ff6b6b;
  animation-delay: 0s;
}

.cookie-floating-icon:nth-child(2) {
  top: 60%;
  right: 15%;
  color: #4ecdc4;
  animation-delay: 2s;
}

.cookie-floating-icon:nth-child(3) {
  bottom: 30%;
  left: 20%;
  color: #45b7d1;
  animation-delay: 4s;
}

.cookie-floating-icon:nth-child(4) {
  top: 40%;
  right: 30%;
  color: #f9ca24;
  animation-delay: 1s;
}

@keyframes cookieFloat {
  0%, 100% {
    transform: translateY(0px) rotate(0deg);
  }
  33% {
    transform: translateY(-20px) rotate(120deg);
  }
  66% {
    transform: translateY(10px) rotate(240deg);
  }
}

.cookie-hero-content {
  position: relative;
  z-index: 2;
}

.cookie-hero-icon {
  width: 120px;
  height: 120px;
  background: rgba(255, 255, 255, 0.1);
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  margin: 0 auto 2rem;
  backdrop-filter: blur(10px);
  border: 2px solid rgba(255, 255, 255, 0.2);
  transition: all 0.3s ease;
}

.cookie-hero-icon:hover {
  transform: scale(1.1) rotate(10deg);
  background: rgba(255, 255, 255, 0.2);
}

.cookie-hero-title {
  font-size: 3rem;
  font-weight: 700;
  color: #fff;
  text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
  margin-bottom: 1rem;
}

.cookie-hero-subtitle {
  font-size: 1.2rem;
  color: rgba(255, 255, 255, 0.9);
  text-shadow: 1px 1px 2px rgba(0,0,0,0.2);
  margin-bottom: 2rem;
}

.cookie-stats {
  background: rgba(255, 255, 255, 0.1);
  backdrop-filter: blur(15px);
  border-radius: 20px;
  padding: 2rem;
  border: 1px solid rgba(255, 255, 255, 0.2);
}

.cookie-stat-item {
  text-align: center;
  color: #fff;
}

.cookie-stat-icon {
  width: 50px;
  height: 50px;
  background: rgba(255, 255, 255, 0.2);
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  margin: 0 auto 1rem;
  font-size: 1.5rem;
  transition: all 0.3s ease;
}

.cookie-stat-item:hover .cookie-stat-icon {
  transform: scale(1.1);
  background: rgba(255, 255, 255, 0.3);
}

.cookie-content {
  font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
}

.cookie-content h2 {
  border-bottom: 3px solid #9b59b6;
  padding-bottom: 0.5rem;
  margin-bottom: 1.5rem;
}

.cookie-content .card {
  border: none;
  border-radius: 15px;
  box-shadow: 0 5px 15px rgba(0,0,0,0.08);
  transition: all 0.3s ease;
  margin-bottom: 1.5rem;
}

.cookie-content .card:hover {
  transform: translateY(-5px);
  box-shadow: 0 10px 25px rgba(0,0,0,0.15);
}

.cookie-content .card-header {
  border: none;
  border-radius: 15px 15px 0 0 !important;
  font-weight: 600;
}

.cookie-footer {
  background: linear-gradient(135deg, #9b59b6 0%, #8e44ad 100%);
  color: #fff;
}

.cookie-footer .btn {
  background: linear-gradient(135deg, #fff 0%, #f8f9fa 100%);
  color: #9b59b6;
  border: none;
  border-radius: 25px;
  padding: 0.75rem 2rem;
  font-weight: 600;
  transition: all 0.3s ease;
}

.cookie-footer .btn:hover {
  transform: translateY(-2px);
  box-shadow: 0 5px 15px rgba(0,0,0,0.2);
  color: #8e44ad;
}

/* Responsive Design */
@media (max-width: 768px) {
  .cookie-hero {
    padding-top: 80px;
  }
  
  .cookie-hero-title {
    font-size: 2rem;
  }

  .cookie-hero-subtitle {
    font-size: 1rem;
  }

  .cookie-floating-icon {
    display: none;
  }

  .cookie-hero-icon {
    width: 80px;
    height: 80px;
  }

  .cookie-stats {
    padding: 1rem;
  }
}
</style>
@endsection

@section('content')
<!-- Hero Section -->
<section class="cookie-hero">
  <!-- Floating Icons -->
  <div class="cookie-floating-icons">
    <i class="ti ti-cookie cookie-floating-icon"></i>
    <i class="ti ti-shield-check cookie-floating-icon"></i>
    <i class="ti ti-settings cookie-floating-icon"></i>
    <i class="ti ti-lock cookie-floating-icon"></i>
  </div>

  <div class="container cookie-hero-content">

    <div class="row align-items-center min-vh-60">
      <div class="col-lg-8 mx-auto text-center">
        <!-- Hero Icon -->
        <div class="cookie-hero-icon">
          <i class="ti ti-cookie" style="font-size: 3rem; color: #fff;"></i>
        </div>

        <!-- Hero Title -->
        <h1 class="cookie-hero-title">
          {{ __('سياسة ملفات تعريف الارتباط') }}
        </h1>

        <!-- Hero Subtitle -->
        <p class="cookie-hero-subtitle">
          {{ __('معلومات شاملة حول استخدام وإدارة ملفات تعريف الارتباط على موقعنا') }}
        </p>

        <!-- Stats Card -->
        <div class="cookie-stats">
          <div class="row">
            <div class="col-md-4">
              <div class="cookie-stat-item">
                <div class="cookie-stat-icon">
                  <i class="ti ti-cookie"></i>
                </div>
                <h4 class="mb-1">{{ __('ملفات آمنة') }}</h4>
                <small class="opacity-75">{{ __('حماية كاملة') }}</small>
              </div>
            </div>
            <div class="col-md-4">
              <div class="cookie-stat-item">
                <div class="cookie-stat-icon">
                  <i class="ti ti-shield-check"></i>
                </div>
                <h4 class="mb-1">{{ __('شفافية كاملة') }}</h4>
                <small class="opacity-75">{{ __('معلومات واضحة') }}</small>
              </div>
            </div>
            <div class="col-md-4">
              <div class="cookie-stat-item">
                <div class="cookie-stat-icon">
                  <i class="ti ti-settings"></i>
                </div>
                <h4 class="mb-1">{{ __('تحكم كامل') }}</h4>
                <small class="opacity-75">{{ __('إدارة سهلة') }}</small>
              </div>
            </div>
          </div>
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
        <a href="{{ route('home') }}" style="color: #9b59b6; text-decoration: none; transition: all 0.3s ease;">
          <i class="ti ti-home me-1"></i>{{ __('الرئيسية') }}
        </a>
      </li>
      <li class="breadcrumb-item active" style="color: #6c757d;">{{ __('سياسة ملفات تعريف الارتباط') }}</li>
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
          <div class="card-header" style="background: linear-gradient(135deg, #9b59b6 0%, #8e44ad 100%); border: none; padding: 2rem;">
            <div class="row align-items-center">
              <div class="col-md-2 text-center">
                <div style="width: 60px; height: 60px; background: rgba(255,255,255,0.2); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto;">
                  <i class="ti ti-cookie" style="font-size: 1.8rem; color: #fff;"></i>
                </div>
              </div>
              <div class="col-md-8">
                <h2 class="h3 text-white mb-1 fw-bold">{{ __('سياسة ملفات تعريف الارتباط') }}</h2>
                <p class="text-white mb-0 opacity-90">{{ __('معلومات مهمة حول استخدام وإدارة ملفات تعريف الارتباط') }}</p>
              </div>
              <div class="col-md-2 text-center">
                <span class="badge" style="background: rgba(255,255,255,0.2); color: #fff; padding: 0.5rem 1rem; border-radius: 20px; font-size: 0.9rem;">
                  <i class="ti ti-settings me-1"></i>{{ __('إعدادات') }}
                </span>
              </div>
            </div>
          </div>

          <!-- Card Body -->
          <div class="card-body cookie-content" style="padding: 3rem; line-height: 1.8; color: #2d3748;">
            <p class="text-muted mb-4">آخر تحديث: {{ date('d F Y') }}</p>

            <div class="alert alert-info" role="alert" style="border-radius: 15px; border: none; background: linear-gradient(135deg, #d1ecf1, #bee5eb);">
              <i class="ti ti-info-circle me-2"></i>
              <strong>ملاحظة مهمة:</strong> تساعدنا ملفات تعريف الارتباط في تقديم تجربة أفضل لك على موقع <strong>{{ config('settings.site_name', 'علم للتعليم') }}</strong>.
            </div>

            <h2 class="h4 text-primary mb-3">
              <i class="ti ti-help-circle me-2"></i>1. ما هي ملفات تعريف الارتباط؟
            </h2>
            <div class="card" style="border-radius: 15px; border-left: 5px solid #17a2b8;">
              <div class="card-body">
                <div class="row align-items-center">
                  <div class="col-md-2 text-center mb-3 mb-md-0">
                    <i class="ti ti-cookie" style="font-size: 3rem; color: #17a2b8;"></i>
                  </div>
                  <div class="col-md-10">
                    <h6 class="card-title text-info mb-2">
                      <i class="ti ti-info-circle me-2"></i>تعريف ملفات الكوكيز
                    </h6>
                    <p class="card-text mb-0">
                      ملفات تعريف الارتباط (Cookies) هي ملفات نصية صغيرة يتم تخزينها على جهازك عند زيارة موقعنا. تساعد هذه الملفات في تحسين تجربة التصفح وتذكر تفضيلاتك الشخصية.
                    </p>
                  </div>
                </div>
              </div>
            </div>
            <h2 class="h4 text-primary mb-3 mt-4">
              <i class="ti ti-settings me-2"></i>2. كيفية استخدامنا لملفات تعريف الارتباط
            </h2>
            <p class="mb-4">نستخدم ملفات تعريف الارتباط لتحسين تجربتك على موقعنا:</p>
            <div class="row mb-4">
              <div class="col-lg-6 mb-3">
                <div class="card h-100" style="border-radius: 15px; border-left: 5px solid #28a745;">
                  <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                      <i class="ti ti-shield-check text-success me-3" style="font-size: 2rem;"></i>
                      <h6 class="card-title mb-0 text-success">ملفات ضرورية</h6>
                    </div>
                    <p class="card-text mb-0">تُستخدم لتمكين الميزات الأساسية مثل التنقل في الموقع والوصول الآمن.</p>
                  </div>
                </div>
              </div>
              <div class="col-lg-6 mb-3">
                <div class="card h-100" style="border-radius: 15px; border-left: 5px solid #007bff;">
                  <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                      <i class="ti ti-chart-line text-primary me-3" style="font-size: 2rem;"></i>
                      <h6 class="card-title mb-0 text-primary">ملفات تحليلية</h6>
                    </div>
                    <p class="card-text mb-0">تساعدنا في فهم كيفية تفاعل الزوار مع الموقع لتحسين الأداء والخدمات.</p>
                  </div>
                </div>
              </div>
              <div class="col-lg-6 mb-3">
                <div class="card h-100" style="border-radius: 15px; border-left: 5px solid #6f42c1;">
                  <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                      <i class="ti ti-adjustments text-purple me-3" style="font-size: 2rem;"></i>
                      <h6 class="card-title mb-0" style="color: #6f42c1;">ملفات وظيفية</h6>
                    </div>
                    <p class="card-text mb-0">تُستخدم لتذكر تفضيلاتك مثل اللغة والإعدادات الشخصية.</p>
                  </div>
                </div>
              </div>
              <div class="col-lg-6 mb-3">
                <div class="card h-100" style="border-radius: 15px; border-left: 5px solid #fd7e14;">
                  <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                      <i class="ti ti-ad text-warning me-3" style="font-size: 2rem;"></i>
                      <h6 class="card-title mb-0 text-warning">ملفات إعلانية</h6>
                    </div>
                    <p class="card-text mb-0">تُستخدم لعرض الإعلانات ذات الصلة بناءً على اهتماماتك وتفضيلاتك.</p>
                  </div>
                </div>
              </div>
            </div>
            <h2 class="h4 text-primary mb-3">
              <i class="ti ti-category me-2"></i>3. أنواع ملفات تعريف الارتباط
            </h2>
            <div class="row mb-4">
              <div class="col-lg-6 mb-3">
                <div class="card h-100" style="border-radius: 15px; border-left: 5px solid #dc3545;">
                  <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                      <i class="ti ti-clock text-danger me-3" style="font-size: 2rem;"></i>
                      <h6 class="card-title mb-0 text-danger">ملفات دائمة</h6>
                    </div>
                    <p class="card-text mb-0">تظل مخزنة على جهازك حتى تنتهي صلاحيتها أو تقوم بحذفها يدوياً.</p>
                  </div>
                </div>
              </div>
              <div class="col-lg-6 mb-3">
                <div class="card h-100" style="border-radius: 15px; border-left: 5px solid #20c997;">
                  <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                      <i class="ti ti-refresh text-teal me-3" style="font-size: 2rem;"></i>
                      <h6 class="card-title mb-0" style="color: #20c997;">ملفات مؤقتة</h6>
                    </div>
                    <p class="card-text mb-0">تُحذف تلقائياً عند إغلاق متصفحك وتُستخدم فقط أثناء جلسة التصفح.</p>
                  </div>
                </div>
              </div>
            </div>
            <h2 class="h4 text-primary mb-3">
              <i class="ti ti-settings-cog me-2"></i>4. التحكم في ملفات تعريف الارتباط
            </h2>
            <div class="alert alert-warning" style="border-radius: 15px; border-left: 5px solid #ffc107;">
              <i class="ti ti-alert-triangle me-2"></i>
              <strong>تنبيه:</strong> يمكنك التحكم في ملفات تعريف الارتباط من خلال إعدادات متصفحك، لكن قد يؤثر ذلك على بعض ميزات الموقع.
            </div>
            <div class="card" style="border-radius: 15px; border-left: 5px solid #007bff;">
              <div class="card-body">
                <h6 class="card-title text-primary mb-3">
                  <i class="ti ti-browser me-2"></i>إرشادات المتصفحات
                </h6>
                <div class="row">
                  <div class="col-md-4 mb-2">
                    <div class="d-flex align-items-center">
                      <i class="ti ti-brand-chrome text-success me-2"></i>
                      <a href="https://support.google.com/chrome/answer/95647?hl=ar" target="_blank" class="text-decoration-none">Google Chrome</a>
                    </div>
                  </div>
                  <div class="col-md-4 mb-2">
                    <div class="d-flex align-items-center">
                      <i class="ti ti-brand-firefox text-warning me-2"></i>
                      <a href="https://support.mozilla.org/ar/kb/حظر-ملفات-تعريف-الارتباط" target="_blank" class="text-decoration-none">Mozilla Firefox</a>
                    </div>
                  </div>
                  <div class="col-md-4 mb-2">
                    <div class="d-flex align-items-center">
                      <i class="ti ti-brand-safari text-info me-2"></i>
                      <a href="https://support.apple.com/ar-sa/guide/safari/sfri11471/mac" target="_blank" class="text-decoration-none">Safari</a>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <h2 class="h4 text-primary mb-3 mt-4">
              <i class="ti ti-external-link me-2"></i>5. ملفات تعريف الارتباط الخارجية
            </h2>
            <div class="card" style="border-radius: 15px; border-left: 5px solid #6c757d;">
              <div class="card-body">
                <div class="d-flex align-items-center mb-3">
                  <i class="ti ti-chart-dots text-secondary me-3" style="font-size: 2rem;"></i>
                  <h6 class="card-title mb-0 text-secondary">خدمات التحليل الخارجية</h6>
                </div>
                <p class="card-text mb-0">
                  قد نستخدم خدمات خارجية مثل
                  <span class="badge bg-secondary-subtle text-secondary">Google Analytics</span>
                  لتحليل أداء الموقع. هذه الخدمات قد تضع ملفات تعريف ارتباط خاصة بها لجمع البيانات حول استخدامك للموقع. نحن لا نتحكم في ملفات تعريف الارتباط التي يتم وضعها بواسطة الجهات الخارجية.
                </p>
              </div>
            </div>
            <h2 class="h4 text-primary mb-3 mt-4">
              <i class="ti ti-refresh me-2"></i>6. تحديث سياسة ملفات تعريف الارتباط
            </h2>
            <div class="card" style="border-radius: 15px; border-left: 5px solid #17a2b8;">
              <div class="card-body">
                <div class="d-flex align-items-center mb-3">
                  <i class="ti ti-calendar-event text-info me-3" style="font-size: 2rem;"></i>
                  <h6 class="card-title mb-0 text-info">تحديثات دورية</h6>
                </div>
                <p class="card-text mb-0">
                  قد يتم تحديث سياسة ملفات تعريف الارتباط من وقت لآخر لتلبية المتطلبات القانونية أو التكنولوجية. يُنصح بمراجعة هذه الصفحة بانتظام للحصول على أحدث المعلومات.
                </p>
              </div>
            </div>
            <h2 class="h4 text-primary mb-3 mt-4">
              <i class="ti ti-mail me-2"></i>7. التواصل معنا
            </h2>
            <div class="row mb-4">
              <div class="col-lg-6 mb-3">
                <div class="card h-100" style="border-radius: 15px; border-left: 5px solid #28a745;">
                  <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                      <i class="ti ti-mail text-success me-3" style="font-size: 2rem;"></i>
                      <h6 class="card-title mb-0 text-success">البريد الإلكتروني</h6>
                    </div>
                    <p class="card-text mb-0">
                      <a href="mailto:{{ $settings['contact_email'] ?? 'info@alemedu.com' }}" class="text-decoration-none">
                        {{ $settings['contact_email'] ?? 'info@alemedu.com' }}
                      </a>
                    </p>
                  </div>
                </div>
              </div>
              <div class="col-lg-6 mb-3">
                <div class="card h-100" style="border-radius: 15px; border-left: 5px solid #007bff;">
                  <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                      <i class="ti ti-world text-primary me-3" style="font-size: 2rem;"></i>
                      <h6 class="card-title mb-0 text-primary">الموقع الإلكتروني</h6>
                    </div>
                    <p class="card-text mb-0">
                      <a href="{{ $settings['canonical_url'] ?? url('/') }}" class="text-decoration-none">
                        {{ $settings['canonical_url'] ?? url('/') }}
                      </a>
                    </p>
                  </div>
                </div>
              </div>
            </div>
            <div class="alert alert-success" style="border-radius: 15px; border-left: 5px solid #28a745;">
              <div class="d-flex align-items-center">
                <i class="ti ti-check-circle text-success me-3" style="font-size: 2rem;"></i>
                <div>
                  <h6 class="alert-heading mb-2 text-success">شكراً لقراءتك لسياسة ملفات تعريف الارتباط</h6>
                  <p class="mb-0">
                    باستمرارك في استخدام موقع
                    <span class="badge bg-primary-subtle text-primary">{{ config('settings.site_name') }}</span>
                    فإنك توافق على استخدام ملفات تعريف الارتباط وفقاً للسياسة المذكورة أعلاه.
                  </p>
                </div>
              </div>
            </div>
          </div>
          <!-- Card Footer -->
          <div class="card-footer cookie-footer" style="border: none; padding: 2rem; text-align: center;">
            <div class="row align-items-center">
              <div class="col-md-6">
                <p class="mb-0">
                  <i class="ti ti-info-circle me-1"></i>
                  آخر تحديث: {{ date('Y-m-d') }}
                </p>
              </div>
              <div class="col-md-6 text-md-end">
                <a href="{{ route('home') }}" class="btn">
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
@endsection
