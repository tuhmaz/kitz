@extends('layouts/layoutFront')

@section('title', __('من نحن'))

@section('vendor-style')
@vite('resources/assets/vendor/scss/home.scss')
@endsection

@section('page-style')
<style>
/* About Us Specific Styles */
.about-hero {
  background: linear-gradient(135deg, #1f36ad 0%, #286aad 50%, #3b82f6 100%);
  position: relative;
  overflow: hidden;
  min-height: 50vh;
  padding-top: 100px;
}

.about-hero::before {
  content: '';
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="about-grain" width="100" height="100" patternUnits="userSpaceOnUse"><circle cx="20" cy="20" r="1" fill="%23ffffff" opacity="0.1"/><circle cx="80" cy="40" r="1" fill="%23ffffff" opacity="0.1"/><circle cx="40" cy="80" r="1" fill="%23ffffff" opacity="0.1"/></pattern></defs><rect width="100" height="100" fill="url(%23about-grain)"/></svg>') repeat;
  opacity: 0.3;
}

.about-floating-icons {
  position: absolute;
  width: 100%;
  height: 100%;
  overflow: hidden;
  pointer-events: none;
}

.about-floating-icon {
  position: absolute;
  font-size: 2rem;
  opacity: 0.6;
  animation: aboutFloat 6s ease-in-out infinite;
}

.about-floating-icon:nth-child(1) {
  top: 20%;
  left: 10%;
  color: #ff6b6b;
  animation-delay: 0s;
}

.about-floating-icon:nth-child(2) {
  top: 60%;
  right: 15%;
  color: #4ecdc4;
  animation-delay: 2s;
}

.about-floating-icon:nth-child(3) {
  bottom: 30%;
  left: 20%;
  color: #45b7d1;
  animation-delay: 4s;
}

.about-floating-icon:nth-child(4) {
  top: 40%;
  right: 30%;
  color: #f9ca24;
  animation-delay: 1s;
}

@keyframes aboutFloat {
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

.about-hero-content {
  position: relative;
  z-index: 2;
}

.about-hero-icon {
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

.about-hero-icon:hover {
  transform: scale(1.1) rotate(10deg);
  background: rgba(255, 255, 255, 0.2);
}

.about-hero-title {
  font-size: 3rem;
  font-weight: 700;
  color: #fff;
  text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
  margin-bottom: 1rem;
}

.about-hero-subtitle {
  font-size: 1.2rem;
  color: rgba(255, 255, 255, 0.9);
  text-shadow: 1px 1px 2px rgba(0,0,0,0.2);
  margin-bottom: 2rem;
}

.about-stats {
  background: rgba(255, 255, 255, 0.1);
  backdrop-filter: blur(15px);
  border-radius: 20px;
  padding: 2rem;
  border: 1px solid rgba(255, 255, 255, 0.2);
}

.about-stat-item {
  text-align: center;
  color: #fff;
}

.about-stat-icon {
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

.about-stat-item:hover .about-stat-icon {
  transform: scale(1.1);
  background: rgba(255, 255, 255, 0.3);
}

.edu-card {
  border: none;
  border-radius: 20px;
  box-shadow: 0 10px 30px rgba(0,0,0,0.1);
  transition: all 0.3s ease;
  overflow: hidden;
  position: relative;
}

.edu-card::before {
  content: '';
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  height: 4px;
  background: linear-gradient(90deg, #1f36ad, #286aad, #3b82f6);
}

.edu-card:hover {
  transform: translateY(-10px);
  box-shadow: 0 20px 40px rgba(0,0,0,0.15);
}

.edu-card .card-body {
  padding: 2.5rem;
}

.edu-card-header {
  display: flex;
  align-items: center;
  margin-bottom: 1.5rem;
}

.edu-card-icon {
  width: 60px;
  height: 60px;
  border-radius: 15px;
  display: flex;
  align-items: center;
  justify-content: center;
  margin-right: 1rem;
  font-size: 1.8rem;
  color: #fff;
  position: relative;
  overflow: hidden;
}

.edu-card-icon::before {
  content: '';
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background: linear-gradient(135deg, var(--icon-color-1), var(--icon-color-2));
  opacity: 0.9;
}

.edu-card-icon i {
  position: relative;
  z-index: 1;
}

.vision-card {
  --icon-color-1: #27ae60;
  --icon-color-2: #2ecc71;
}

.mission-card {
  --icon-color-1: #e74c3c;
  --icon-color-2: #c0392b;
}

.services-card {
  --icon-color-1: #f39c12;
  --icon-color-2: #e67e22;
}

.values-card {
  --icon-color-1: #9b59b6;
  --icon-color-2: #8e44ad;
}

.contact-card {
  --icon-color-1: #3498db;
  --icon-color-2: #2980b9;
}

.service-item {
  background: #f8f9fa;
  border-radius: 15px;
  padding: 1.5rem;
  margin-bottom: 1rem;
  border-left: 4px solid #1f36ad;
  transition: all 0.3s ease;
}

.service-item:hover {
  background: #e3f2fd;
  transform: translateX(10px);
}

.value-item {
  text-align: center;
  padding: 1.5rem;
  border-radius: 15px;
  background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
  transition: all 0.3s ease;
}

.value-item:hover {
  transform: translateY(-5px);
  box-shadow: 0 10px 25px rgba(0,0,0,0.1);
}

.value-icon {
  width: 70px;
  height: 70px;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  margin: 0 auto 1rem;
  font-size: 2rem;
  color: #fff;
  background: linear-gradient(135deg, var(--value-color-1), var(--value-color-2));
}

.quality-value {
  --value-color-1: #f39c12;
  --value-color-2: #e67e22;
}

.collaboration-value {
  --value-color-1: #27ae60;
  --value-color-2: #2ecc71;
}

.innovation-value {
  --value-color-1: #9b59b6;
  --value-color-2: #8e44ad;
}

/* Responsive Design */
@media (max-width: 768px) {
  .about-hero {
    padding-top: 80px;
  }
  
  .about-hero-title {
    font-size: 2rem;
  }
  
  .about-hero-subtitle {
    font-size: 1rem;
  }
  
  .about-floating-icon {
    display: none;
  }
  
  .about-hero-icon {
    width: 80px;
    height: 80px;
  }
  
  .about-stats {
    padding: 1rem;
  }
  
  .edu-card .card-body {
    padding: 1.5rem;
  }
}
</style>
@endsection

@section('content')
<!-- Hero Section -->
<section class="about-hero">
  <!-- Floating Icons -->
  <div class="about-floating-icons">
    <i class="ti ti-users about-floating-icon"></i>
    <i class="ti ti-school about-floating-icon"></i>
    <i class="ti ti-heart about-floating-icon"></i>
    <i class="ti ti-bulb about-floating-icon"></i>
  </div>

  <div class="container about-hero-content">
    <div class="row align-items-center min-vh-50">
      <div class="col-lg-8 mx-auto text-center">
        <!-- Hero Icon -->
        <div class="about-hero-icon">
          <i class="ti ti-users" style="font-size: 3rem; color: #fff;"></i>
        </div>
        
        <!-- Hero Title -->
        <h1 class="about-hero-title">
          {{ __('من نحن') }}
        </h1>
        
        <!-- Hero Subtitle -->
        <p class="about-hero-subtitle">
          {{ __('مرحبًا بكم في موقع علم للتعليم، المنصة التعليمية المميزة المصممة لدعم الطلاب والمعلمين في رحلتهم التعليمية') }}
        </p>
        
        <!-- Stats Card -->
        <div class="about-stats">
          <div class="row">
            <div class="col-md-4">
              <div class="about-stat-item">
                <div class="about-stat-icon">
                  <i class="ti ti-school"></i>
                </div>
                <h4 class="mb-1">{{ __('تعليم متميز') }}</h4>
                <small class="opacity-75">{{ __('محتوى عالي الجودة') }}</small>
              </div>
            </div>
            <div class="col-md-4">
              <div class="about-stat-item">
                <div class="about-stat-icon">
                  <i class="ti ti-users"></i>
                </div>
                <h4 class="mb-1">{{ __('مجتمع تعليمي') }}</h4>
                <small class="opacity-75">{{ __('طلاب ومعلمين') }}</small>
              </div>
            </div>
            <div class="col-md-4">
              <div class="about-stat-item">
                <div class="about-stat-icon">
                  <i class="ti ti-heart"></i>
                </div>
                <h4 class="mb-1">{{ __('شغف بالتعليم') }}</h4>
                <small class="opacity-75">{{ __('تطوير مستمر') }}</small>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- Main Content -->
<div class="container py-5">
    <!-- رؤيتنا ورسالتنا -->
    <div class="row mb-5">
        <div class="col-md-6 mb-4 mb-md-0">
            <div class="edu-card vision-card h-100">
                <div class="card-body">
                    <div class="edu-card-header">
                        <div class="edu-card-icon">
                            <i class="ti ti-eye"></i>
                        </div>
                        <h2 class="h4 mb-0">{{ __('رؤيتنا') }}</h2>
                    </div>
                    <p class="mb-0 text-muted">{{ __('نسعى إلى أن نكون المصدر الأول للمحتوى التعليمي الموثوق والشامل، متماشين مع المنهاج الأردني، مع تسهيل الوصول إلى المواد التعليمية والاختبارات والمقالات الإرشادية للطلاب والمعلمين على حد سواء.') }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="edu-card mission-card h-100">
                <div class="card-body">
                    <div class="edu-card-header">
                        <div class="edu-card-icon">
                            <i class="ti ti-target"></i>
                        </div>
                        <h2 class="h4 mb-0">{{ __('رسالتنا') }}</h2>
                    </div>
                    <p class="mb-0 text-muted">{{ __('تقديم تجربة تعليمية متكاملة تعتمد على توفير موارد تعليمية عالية الجودة تساهم في تحسين أداء الطلاب والمعلمين وتطوير البيئة التعليمية بشكل عام.') }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- ماذا نقدم -->
    <div class="edu-card services-card mb-5">
        <div class="card-body">
            <div class="edu-card-header">
                <div class="edu-card-icon">
                    <i class="ti ti-gift"></i>
                </div>
                <h2 class="h4 mb-0">{{ __('ماذا نقدم؟') }}</h2>
            </div>
            <p class="text-muted mb-4">يقدم موقع {{ config('settings.site_name') }} مجموعة واسعة من الخدمات التعليمية المصممة بعناية، بما في ذلك:</p>
            <div class="row g-4">
                <div class="col-md-6">
                    <div class="service-item">
                        <div class="d-flex align-items-start">
                            <i class="ti ti-school fs-4 text-primary me-3 mt-1"></i>
                            <div>
                                <h3 class="h6 fw-bold mb-2">{{ __('صفوف دراسية') }}</h3>
                                <p class="mb-0 text-muted">{{ __('تغطي جميع الصفوف من التمهيدي حتى الصف الثاني عشر.') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="service-item">
                        <div class="d-flex align-items-start">
                            <i class="ti ti-books fs-4 text-primary me-3 mt-1"></i>
                            <div>
                                <h3 class="h6 fw-bold mb-2">{{ __('مواد دراسية') }}</h3>
                                <ul class="mb-0 ps-3 text-muted">
                                    <li>{{ __('الخطة الدراسية') }}</li>
                                    <li>{{ __('أوراق العمل والكورسات') }}</li>
                                    <li>{{ __('الاختبارات الشهرية والنهائية') }}</li>
                                    <li>{{ __('الكتب الرسمية ودليل المعلم') }}</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="service-item">
                        <div class="d-flex align-items-start">
                            <i class="ti ti-news fs-4 text-primary me-3 mt-1"></i>
                            <div>
                                <h3 class="h6 fw-bold mb-2">{{ __('أخبار تربوية') }}</h3>
                                <p class="mb-0 text-muted">{{ __('تشمل آخر أخبار وزارة التربية والتعليم، وأخبار المعلمين، والمقالات الإرشادية.') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="service-item">
                        <div class="d-flex align-items-start">
                            <i class="ti ti-filter fs-4 text-primary me-3 mt-1"></i>
                            <div>
                                <h3 class="h6 fw-bold mb-2">{{ __('تصفية المحتوى') }}</h3>
                                <p class="mb-0 text-muted">{{ __('أدوات بحث وتصنيف متقدمة تتيح للمستخدمين الوصول إلى المحتوى المناسب بسهولة.') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- قيمنا -->
    <div class="edu-card values-card mb-5">
        <div class="card-body">
            <div class="edu-card-header">
                <div class="edu-card-icon">
                    <i class="ti ti-heart"></i>
                </div>
                <h2 class="h4 mb-0">{{ __('قيمنا') }}</h2>
            </div>
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="value-item quality-value">
                        <div class="value-icon">
                            <i class="ti ti-award"></i>
                        </div>
                        <h3 class="h6 fw-bold mb-2">{{ __('الجودة') }}</h3>
                        <p class="mb-0 text-muted">{{ __('تقديم محتوى تعليمي متميز ودقيق.') }}</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="value-item collaboration-value">
                        <div class="value-icon">
                            <i class="ti ti-users"></i>
                        </div>
                        <h3 class="h6 fw-bold mb-2">{{ __('التعاون') }}</h3>
                        <p class="mb-0 text-muted">{{ __('تعزيز بيئة تعليمية تدعم الشراكة بين الطلاب والمعلمين.') }}</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="value-item innovation-value">
                        <div class="value-icon">
                            <i class="ti ti-bulb"></i>
                        </div>
                        <h3 class="h6 fw-bold mb-2">{{ __('الإبداع') }}</h3>
                        <p class="mb-0 text-muted">{{ __('استخدام أدوات وتقنيات حديثة لتحسين تجربة المستخدم.') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- التواصل معنا -->
    <div class="edu-card contact-card">
        <div class="card-body">
            <div class="edu-card-header">
                <div class="edu-card-icon">
                    <i class="ti ti-phone"></i>
                </div>
                <h2 class="h4 mb-0">{{ __('التواصل معنا') }}</h2>
            </div>
            <p class="text-muted mb-4">{{ __('إذا كانت لديك أي أسئلة أو اقتراحات، يسعدنا أن نتواصل معك عبر:') }}</p>
            <div class="row g-3">
                <div class="col-md-6">
                    <div class="contact-item email-contact">
                        <div class="contact-icon">
                            <i class="ti ti-mail"></i>
                        </div>
                        <div class="contact-info">
                            <h5 class="mb-1">البريد الإلكتروني</h5>
                            <a href="mailto:{{ $settings['contact_email'] ?? 'info@alemedu.com' }}" class="contact-link">
                                {{ $settings['contact_email'] ?? 'info@alemedu.com' }}
                            </a>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="contact-item website-contact">
                        <div class="contact-icon">
                            <i class="ti ti-world"></i>
                        </div>
                        <div class="contact-info">
                            <h5 class="mb-1">الموقع الإلكتروني</h5>
                            <a href="{{ $settings['canonical_url'] ?? 'https://alemedu.com' }}" target="_blank" class="contact-link">
                                {{ $settings['canonical_url'] ?? 'https://alemedu.com' }}
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="contact-cta mt-4">
                <div class="d-flex align-items-center justify-content-center gap-3">
                    <a href="mailto:{{ $settings['contact_email'] ?? 'info@alemedu.com' }}" class="btn btn-primary btn-lg">
                        <i class="ti ti-mail me-2"></i>
                        ارسل رسالة
                    </a>
                    <a href="{{ $settings['canonical_url'] ?? 'https://alemedu.com' }}" target="_blank" class="btn btn-outline-primary btn-lg">
                        <i class="ti ti-external-link me-2"></i>
                        زيارة الموقع
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

<style>
/* Values Card Styles */
.values-card {
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    border: 1px solid rgba(52, 144, 220, 0.1);
    position: relative;
    overflow: hidden;
}

.values-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 4px;
    background: linear-gradient(90deg, #3490dc, #6c63ff, #f093fb);
}

.value-item {
    text-align: center;
    padding: 1.5rem;
    border-radius: 12px;
    transition: all 0.3s ease;
    position: relative;
    overflow: hidden;
}

.value-item::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: linear-gradient(135deg, rgba(255,255,255,0.8), rgba(255,255,255,0.4));
    opacity: 0;
    transition: opacity 0.3s ease;
    border-radius: 12px;
}

.value-item:hover::before {
    opacity: 1;
}

.value-item:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 25px rgba(0,0,0,0.1);
}

.value-icon {
    width: 60px;
    height: 60px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 1rem;
    font-size: 1.5rem;
    color: white;
    position: relative;
    z-index: 2;
    transition: all 0.3s ease;
}

.quality-value .value-icon {
    background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
}

.collaboration-value .value-icon {
    background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
}

.innovation-value .value-icon {
    background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);
}

.value-item:hover .value-icon {
    transform: scale(1.1) rotate(5deg);
}

/* Contact Card Styles */
.contact-card {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    position: relative;
    overflow: hidden;
}

.contact-card::before {
    content: '';
    position: absolute;
    top: -50%;
    left: -50%;
    width: 200%;
    height: 200%;
    background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grain" width="100" height="100" patternUnits="userSpaceOnUse"><circle cx="25" cy="25" r="1" fill="%23ffffff" opacity="0.1"/><circle cx="75" cy="75" r="1" fill="%23ffffff" opacity="0.1"/><circle cx="50" cy="10" r="1" fill="%23ffffff" opacity="0.1"/><circle cx="10" cy="50" r="1" fill="%23ffffff" opacity="0.1"/><circle cx="90" cy="30" r="1" fill="%23ffffff" opacity="0.1"/></pattern></defs><rect width="100" height="100" fill="url(%23grain)"/></svg>') repeat;
    animation: grain-move 20s linear infinite;
    pointer-events: none;
}

@keyframes grain-move {
    0% { transform: translate(0, 0); }
    100% { transform: translate(-100px, -100px); }
}

.contact-card .edu-card-header {
    border-bottom: 1px solid rgba(255,255,255,0.2);
    margin-bottom: 1.5rem;
    padding-bottom: 1rem;
}

.contact-card .edu-card-icon {
    background: rgba(255,255,255,0.2);
    backdrop-filter: blur(10px);
    color: white;
}

.contact-item {
    background: rgba(255,255,255,0.1);
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255,255,255,0.2);
    border-radius: 12px;
    padding: 1.5rem;
    transition: all 0.3s ease;
    position: relative;
    overflow: hidden;
}

.contact-item::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
    transition: left 0.5s ease;
}

.contact-item:hover::before {
    left: 100%;
}

.contact-item:hover {
    transform: translateY(-3px);
    box-shadow: 0 10px 25px rgba(0,0,0,0.2);
    background: rgba(255,255,255,0.15);
}

.contact-icon {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    background: rgba(255,255,255,0.2);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.25rem;
    color: white;
    margin-bottom: 1rem;
    transition: all 0.3s ease;
}

.contact-item:hover .contact-icon {
    transform: scale(1.1);
    background: rgba(255,255,255,0.3);
}

.contact-info h5 {
    color: white;
    font-weight: 600;
}

.contact-link {
    color: rgba(255,255,255,0.9);
    text-decoration: none;
    transition: all 0.3s ease;
    font-weight: 500;
}

.contact-link:hover {
    color: white;
    text-decoration: underline;
}

.contact-cta {
    border-top: 1px solid rgba(255,255,255,0.2);
    padding-top: 1.5rem;
}

.contact-cta .btn {
    border-radius: 25px;
    padding: 0.75rem 2rem;
    font-weight: 600;
    transition: all 0.3s ease;
}

.contact-cta .btn-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border: 2px solid white;
    color: white;
}

.contact-cta .btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(0,0,0,0.3);
    background: linear-gradient(135deg, #764ba2 0%, #667eea 100%);
}

.contact-cta .btn-outline-primary {
    border: 2px solid rgba(255,255,255,0.8);
    color: white;
    background: transparent;
}

.contact-cta .btn-outline-primary:hover {
    background: rgba(255,255,255,0.2);
    border-color: white;
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(0,0,0,0.3);
}

/* Responsive Design */
@media (max-width: 768px) {
    .value-item {
        margin-bottom: 1rem;
    }
    
    .contact-cta .d-flex {
        flex-direction: column;
    }
    
    .contact-cta .btn {
        width: 100%;
        margin-bottom: 0.5rem;
    }
}
</style>
