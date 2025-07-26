@extends('layouts/layoutMaster')

@section('title', 'سياسة الخصوصية')

@section('vendor-style')
@vite('resources/assets/vendor/scss/home.scss')
@endsection

@section('content')
<!-- Hero Section -->
<div class="edu-gradient-bg">
  <div class="container">
    <div class="row justify-content-center align-items-center" style="min-height: 25vh;">
      <div class="col-lg-8 text-center text-white">
        <!-- Floating Elements -->
        <div class="floating-elements">
          <div class="floating-icon" style="top: 20%; left: 10%; animation-delay: 0s;">
            <i class="ti ti-shield-lock" style="color: #27ae60;"></i>
          </div>
          <div class="floating-icon" style="top: 30%; right: 15%; animation-delay: 1s;">
            <i class="ti ti-eye-off" style="color: #3498db;"></i>
          </div>
          <div class="floating-icon" style="top: 60%; left: 20%; animation-delay: 2s;">
            <i class="ti ti-user-shield" style="color: #e74c3c;"></i>
          </div>
          <div class="floating-icon" style="top: 70%; right: 10%; animation-delay: 3s;">
            <i class="ti ti-lock" style="color: #9b59b6;"></i>
          </div>
        </div>

        <!-- Main Content -->
        <div class="hero-content" style="backdrop-filter: blur(10px); background: rgba(255, 255, 255, 0.1); border-radius: 20px; padding: 2rem; border: 1px solid rgba(255, 255, 255, 0.2);">
          <div class="hero-icon mb-3">
            <div class="edu-icon-circle" style="width: 80px; height: 80px; background: linear-gradient(135deg, #27ae60, #2ecc71); margin: 0 auto; display: flex; align-items: center; justify-content: center; border-radius: 50%;">
              <i class="ti ti-shield-lock" style="font-size: 2.5rem; color: white;"></i>
            </div>
          </div>
          <h1 class="display-4 fw-bold mb-3">سياسة الخصوصية</h1>
          <p class="lead mb-0">تعرف على كيفية حماية واستخدام بياناتك الشخصية</p>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Breadcrumb -->
<div class="container px-4 mt-4">
  <nav aria-label="breadcrumb">
    <ol class="breadcrumb" style="background: none; padding: 0; margin: 0;">
      <li class="breadcrumb-item">
        <a href="{{ route('home') }}" class="text-decoration-none" style="color: var(--edu-primary);">
          <i class="ti ti-home me-1"></i>الرئيسية
        </a>
      </li>
      <li class="breadcrumb-item active" aria-current="page" style="color: var(--edu-dark);">
        <i class="ti ti-shield-lock me-1"></i>سياسة الخصوصية
      </li>
    </ol>
  </nav>
</div>

<!-- Content Section -->
<section class="section-py bg-body" style="padding-top: 2rem;">
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-lg-10">
        <!-- Privacy Policy Content Card -->
        <div class="card border-0 shadow-lg" style="border-radius: 20px; overflow: hidden;">
          <!-- Card Header -->
          <div class="card-header" style="background: linear-gradient(135deg, #27ae60, #2ecc71); color: white; padding: 2rem; text-align: center; border: none;">
            <div class="row align-items-center">
              <div class="col-md-2">
                <div class="edu-icon-circle" style="width: 60px; height: 60px; background: rgba(255, 255, 255, 0.2); margin: 0 auto; display: flex; align-items: center; justify-content: center; border-radius: 50%; backdrop-filter: blur(10px);">
                  <i class="ti ti-shield-lock" style="font-size: 1.8rem; color: white;"></i>
                </div>
              </div>
              <div class="col-md-8">
                <h2 class="mb-1 fw-bold">سياسة الخصوصية</h2>
                <p class="mb-0 opacity-75">نحن نهتم بحماية خصوصيتك وبياناتك الشخصية</p>
              </div>
              <div class="col-md-2">
                <div class="text-center">
                  <div class="badge bg-white text-success px-3 py-2" style="border-radius: 15px; font-size: 0.9rem;">
                    <i class="ti ti-shield-check me-1"></i>
                    آمن
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- Card Body -->
          <div class="card-body p-0">
            <div class="policy-content" style="padding: 3rem; line-height: 1.8; color: #4a5568;">
              {!! $policy !!}
            </div>
          </div>

          <!-- Card Footer -->
          <div class="card-footer" style="background: #f0f9ff; border: none; padding: 2rem; text-align: center;">
            <div class="row align-items-center">
              <div class="col-md-6">
                <p class="mb-0 text-muted">
                  <i class="ti ti-info-circle me-1"></i>
                  آخر تحديث: {{ date('Y-m-d') }}
                </p>
              </div>
              <div class="col-md-6 text-md-end">
                <a href="{{ route('home') }}" class="btn btn-success" style="border-radius: 15px; padding: 0.75rem 2rem;">
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
/* Policy Content Styling */
.policy-content {
  font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
}

.policy-content h1, .policy-content h2, .policy-content h3, .policy-content h4, .policy-content h5, .policy-content h6 {
  color: #27ae60;
  margin-top: 2rem;
  margin-bottom: 1rem;
  font-weight: 600;
}

.policy-content h1 {
  font-size: 2rem;
  border-bottom: 3px solid #27ae60;
  padding-bottom: 0.5rem;
}

.policy-content h2 {
  font-size: 1.5rem;
  color: #2d3748;
}

.policy-content p {
  margin-bottom: 1.2rem;
  text-align: justify;
}

.policy-content ul, .policy-content ol {
  margin-bottom: 1.5rem;
  padding-left: 2rem;
}

.policy-content li {
  margin-bottom: 0.5rem;
}

.policy-content blockquote {
  border-left: 4px solid #27ae60;
  background: rgba(39, 174, 96, 0.05);
  padding: 1rem 1.5rem;
  margin: 1.5rem 0;
  border-radius: 0 10px 10px 0;
}

.policy-content strong {
  color: #27ae60;
  font-weight: 600;
}

.policy-content a {
  color: #27ae60;
  text-decoration: none;
  border-bottom: 1px solid transparent;
  transition: all 0.3s ease;
}

.policy-content a:hover {
  border-bottom-color: #27ae60;
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
  
  .policy-content {
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
