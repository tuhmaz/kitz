@php
$customizerHidden = 'customizer-hide';
$configData = Helper::appClasses();
@endphp

@extends('layouts/blankLayout')

@section('title', 'تسجيل الدخول - ' . config('settings.site_name'))

@section('vendor-style')
@vite([
  'resources/assets/vendor/libs/@form-validation/form-validation.scss'
])
@endsection

@section('page-style')
@vite(['resources/assets/vendor/scss/pages/page-auth.scss'])
<style>
.edu-login-wrapper {
  min-height: 100vh;
  background: linear-gradient(135deg, #1f36ad 0%, #286aad 50%, #3b82f6 100%);
  position: relative;
  overflow: hidden;
}

.edu-login-wrapper::before {
  content: '';
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grain" width="100" height="100" patternUnits="userSpaceOnUse"><circle cx="50" cy="50" r="1" fill="%23ffffff" opacity="0.1"/></pattern></defs><rect width="100" height="100" fill="url(%23grain)"/></svg>');
  opacity: 0.3;
  animation: grain-move 20s linear infinite;
}

@keyframes grain-move {
  0%, 100% { transform: translate(0, 0); }
  10% { transform: translate(-5%, -5%); }
  20% { transform: translate(-10%, 5%); }
  30% { transform: translate(5%, -10%); }
  40% { transform: translate(-5%, 15%); }
  50% { transform: translate(-15%, 5%); }
  60% { transform: translate(15%, -5%); }
  70% { transform: translate(5%, 10%); }
  80% { transform: translate(-10%, -15%); }
  90% { transform: translate(10%, 15%); }
}

.edu-floating-elements {
  position: absolute;
  width: 100%;
  height: 100%;
  pointer-events: none;
  z-index: 1;
}

.edu-floating-icon {
  position: absolute;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  animation: loginFloat 6s ease-in-out infinite;
}

.edu-floating-icon:nth-child(1) {
  top: 10%;
  left: 10%;
  width: 80px;
  height: 80px;
  background: linear-gradient(135deg, #f59e0b, #d97706);
  animation-delay: 0s;
}

.edu-floating-icon:nth-child(2) {
  top: 20%;
  right: 15%;
  width: 60px;
  height: 60px;
  background: linear-gradient(135deg, #10b981, #059669);
  animation-delay: 2s;
}

.edu-floating-icon:nth-child(3) {
  bottom: 30%;
  left: 20%;
  width: 70px;
  height: 70px;
  background: linear-gradient(135deg, #8b5cf6, #7c3aed);
  animation-delay: 4s;
}

.edu-floating-icon:nth-child(4) {
  bottom: 15%;
  right: 10%;
  width: 90px;
  height: 90px;
  background: linear-gradient(135deg, #ef4444, #dc2626);
  animation-delay: 1s;
}

@keyframes loginFloat {
  0%, 100% {
    transform: translateY(0px) rotate(0deg);
  }
  50% {
    transform: translateY(-20px) rotate(180deg);
  }
}

.edu-login-card {
  background: rgba(255, 255, 255, 0.95);
  backdrop-filter: blur(20px);
  border: 1px solid rgba(255, 255, 255, 0.2);
  border-radius: 24px;
  box-shadow: 0 20px 60px rgba(0, 0, 0, 0.1);
  position: relative;
  z-index: 10;
  overflow: hidden;
}

.edu-login-card::before {
  content: '';
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  height: 4px;
  background: linear-gradient(90deg, #1f36ad, #3b82f6, #10b981, #f59e0b);
}

.edu-brand-section {
  text-align: center;
  margin-bottom: 2rem;
  padding: 1.5rem 0;
}

.edu-brand-logo {
  width: 80px;
  height: 80px;
  margin: 0 auto 1rem;
  background: linear-gradient(135deg, #1f36ad, #3b82f6);
  border-radius: 20px;
  display: flex;
  align-items: center;
  justify-content: center;
  box-shadow: 0 8px 32px rgba(31, 54, 173, 0.3);
  transition: transform 0.3s ease;
}

.edu-brand-logo:hover {
  transform: scale(1.05);
}

.edu-brand-logo img {
  width: 50px;
  height: 50px;
  filter: brightness(0) invert(1);
}

.edu-form-group {
  position: relative;
  margin-bottom: 1.5rem;
}

.edu-form-label {
  font-weight: 600;
  color: #1e293b;
  margin-bottom: 0.5rem;
  display: flex;
  align-items: center;
  gap: 0.5rem;
}

.edu-form-control {
  border: 2px solid #e2e8f0;
  border-radius: 12px;
  padding: 0.875rem 1rem;
  font-size: 1rem;
  transition: all 0.3s ease;
  background: #f8fafc;
}

.edu-form-control:focus {
  border-color: #1f36ad;
  box-shadow: 0 0 0 3px rgba(31, 54, 173, 0.1);
  background: white;
}

.edu-btn-primary {
  background: linear-gradient(135deg, #1f36ad, #3b82f6);
  border: none;
  border-radius: 12px;
  padding: 0.875rem 2rem;
  font-weight: 600;
  font-size: 1rem;
  transition: all 0.3s ease;
  position: relative;
  overflow: hidden;
}

.edu-btn-primary:hover {
  transform: translateY(-2px);
  box-shadow: 0 8px 25px rgba(31, 54, 173, 0.3);
}

.edu-btn-primary::before {
  content: '';
  position: absolute;
  top: 0;
  left: -100%;
  width: 100%;
  height: 100%;
  background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
  transition: left 0.5s;
}

.edu-btn-primary:hover::before {
  left: 100%;
}

.edu-btn-google {
  border: 2px solid #ea4335;
  color: #ea4335;
  border-radius: 12px;
  padding: 0.875rem 2rem;
  font-weight: 600;
  transition: all 0.3s ease;
  background: white;
}

.edu-btn-google:hover {
  background: #ea4335;
  color: white;
  transform: translateY(-2px);
  box-shadow: 0 8px 25px rgba(234, 67, 53, 0.3);
}

.edu-divider {
  position: relative;
  text-align: center;
  margin: 2rem 0;
}

.edu-divider::before {
  content: '';
  position: absolute;
  top: 50%;
  left: 0;
  right: 0;
  height: 1px;
  background: linear-gradient(90deg, transparent, #e2e8f0, transparent);
}

.edu-divider-text {
  background: white;
  padding: 0 1rem;
  color: #64748b;
  font-weight: 500;
  position: relative;
  z-index: 1;
}

.edu-stats {
  display: flex;
  justify-content: space-around;
  margin: 2rem 0;
  padding: 1.5rem;
  background: linear-gradient(135deg, #f8fafc, #e2e8f0);
  border-radius: 16px;
  border: 1px solid #e2e8f0;
}

.edu-stat-item {
  text-align: center;
}

.edu-stat-number {
  font-size: 1.5rem;
  font-weight: 700;
  color: #1f36ad;
  display: block;
}

.edu-stat-label {
  font-size: 0.875rem;
  color: #64748b;
  margin-top: 0.25rem;
}

.edu-welcome-text {
  background: linear-gradient(135deg, #1f36ad, #3b82f6);
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
  background-clip: text;
  font-weight: 700;
  font-size: 1.75rem;
  margin-bottom: 0.5rem;
}

.edu-subtitle {
  color: #64748b;
  font-size: 1rem;
  margin-bottom: 2rem;
}

@keyframes spin {
  0% { transform: rotate(0deg); }
  100% { transform: rotate(360deg); }
}

@media (max-width: 768px) {
  .edu-floating-elements {
    display: none;
  }

  .edu-login-card {
    margin: 1rem;
    padding: 2rem;
    border-radius: 16px;
  }

  .edu-brand-logo {
    width: 60px;
    height: 60px;
  }

  .edu-brand-logo img {
    width: 35px;
    height: 35px;
  }
}
</style>
@endsection

@section('vendor-script')
@vite([
  'resources/assets/vendor/libs/@form-validation/popular.js',
  'resources/assets/vendor/libs/@form-validation/bootstrap5.js',
  'resources/assets/vendor/libs/@form-validation/auto-focus.js'
])
@endsection

@section('page-script')
@vite([
  'resources/assets/js/pages-auth.js'
])
@endsection

@section('content')
<div class="edu-login-wrapper d-flex align-items-center justify-content-center">
  <!-- Floating Elements -->
  <div class="edu-floating-elements">
    <div class="edu-floating-icon">
      <i class="ti ti-book-2 text-white" style="font-size: 2rem;"></i>
    </div>
    <div class="edu-floating-icon">
      <i class="ti ti-users text-white" style="font-size: 1.5rem;"></i>
    </div>
    <div class="edu-floating-icon">
      <i class="ti ti-certificate text-white" style="font-size: 1.75rem;"></i>
    </div>
    <div class="edu-floating-icon">
      <i class="ti ti-school text-white" style="font-size: 2.25rem;"></i>
    </div>
  </div>

  <!-- Login Card -->
  <div class="edu-login-card" style="width: 100%; max-width: 480px; margin: 2rem; padding: 3rem;">
    <!-- Brand Section -->
    <div class="edu-brand-section">
      <div class="edu-brand-logo">
        <img src="{{ asset('storage/' . config('settings.site_logo')) }}"
             alt="{{ config('settings.site_name') }} Logo"
             width="60"
             height="60"
             loading="lazy" />
      </div>
      <h2 class="edu-welcome-text">أهلاً بك في {{ config('settings.site_name') }}</h2>
      <p class="edu-subtitle">سجل دخولك للوصول إلى منصة التعلم الرقمية</p>
    </div>



    <!-- Error Messages -->
    @if(session('error'))
    <div class="alert alert-danger" style="border-radius: 12px; border: none; background: linear-gradient(135deg, #fee2e2, #fecaca); color: #dc2626; margin-bottom: 1.5rem;">
        <i class="ti ti-alert-circle me-2"></i>
        {{ session('error') }}
    </div>
    @endif

    @if(session('success'))
    <div class="alert alert-success" style="border-radius: 12px; border: none; background: linear-gradient(135deg, #dcfce7, #bbf7d0); color: #16a34a; margin-bottom: 1.5rem;">
        <i class="ti ti-check-circle me-2"></i>
        {{ session('success') }}
    </div>
    @endif

    <!-- Login Form -->
    <form id="formAuthentication" class="mb-6" action="{{ route('login') }}" method="POST">
      @csrf

      <!-- Email Field -->
      <div class="edu-form-group">
        <label for="email" class="edu-form-label">
          <i class="ti ti-mail" style="color: #1f36ad;"></i>
          البريد الإلكتروني
        </label>
        <input type="email"
               class="form-control @error('email') is-invalid @enderror"
               id="email"
               name="email"
               placeholder="أدخل بريدك الإلكتروني"
               value="{{ old('email') }}"
               required
               autofocus>
        @error('email')
        <div class="invalid-feedback" style="color: #dc2626; font-size: 0.875rem; margin-top: 0.5rem;">
            <i class="ti ti-alert-circle me-1"></i>
            {{ $message }}
        </div>
        @enderror
      </div>

      <!-- Password Field -->
      <div class="edu-form-group">
        <div class="d-flex justify-content-between align-items-center mb-2">
          <label class="edu-form-label" for="password">
            <i class="ti ti-lock" style="color: #1f36ad;"></i>
            كلمة المرور
          </label>
          <a href="{{ route('password.request') }}"
             style="color: #1f36ad; text-decoration: none; font-size: 0.875rem; font-weight: 500;">
            <i class="ti ti-help me-1"></i>
            نسيت كلمة المرور؟
          </a>
        </div>
        <div class="input-group">
          <input type="password"
                 id="password"
                 class="form-control @error('password') is-invalid @enderror"
                 name="password"
                 placeholder="أدخل كلمة المرور"
                 required
                 style="border-left: none;">
          <span class="input-group-text"
                style="background: #f8fafc; border: 2px solid #e2e8f0; border-left: none; border-radius: 0 12px 12px 0; cursor: pointer;"
                onclick="togglePassword()">
            <i class="ti ti-eye-off" id="toggleIcon"></i>
          </span>
        </div>
        @error('password')
        <div class="invalid-feedback" style="color: #dc2626; font-size: 0.875rem; margin-top: 0.5rem; display: block;">
            <i class="ti ti-alert-circle me-1"></i>
            {{ $message }}
        </div>
        @enderror
      </div>

      <!-- Remember Me -->
      <div class="edu-form-group">
        <div class="form-check" style="margin-bottom: 0;">
          <input class="form-check-input"
                 type="checkbox"
                 id="remember"
                 name="remember"
                 style="border: 2px solid #e2e8f0; border-radius: 6px;"
                 {{ old('remember') ? 'checked' : '' }}>
          <label class="form-check-label" for="remember" style="color: #64748b; font-weight: 500; margin-right: 0.5rem;">
            <i class="ti ti-check me-1" style="color: #1f36ad;"></i>
            تذكرني
          </label>
        </div>
      </div>

      <!-- Submit Button -->
      <div class="edu-form-group">
        <button class="btn btn-primary d-grid w-100" type="submit">
          <i class="ti ti-login me-2"></i>
          {{ __('Sign in') }}
        </button>
      </div>
    </form>

    <!-- Divider -->
    <div class="edu-divider">
      <div class="edu-divider-text">أو</div>
    </div>

    <!-- Google Login -->
    <div class="d-grid mb-4">
      <a href="{{ route('login.google') }}" class="btn btn-outline-danger w-100">
        <i class="ti ti-brand-google me-2"></i>
        تسجيل الدخول بـ Google
      </a>
    </div>

    <!-- Register Link -->
    <div class="text-center">
      <p style="color: #64748b; margin-bottom: 0.5rem;">جديد على منصتنا؟</p>
      <a href="{{ route('register') }}"
         style="color: #1f36ad; text-decoration: none; font-weight: 600; font-size: 1rem;">
        <i class="ti ti-user-plus me-1"></i>
        إنشاء حساب جديد
      </a>
    </div>

    <!-- Back to Home -->
    <div class="text-center mt-4">
      <a href="{{ url('/') }}"
         style="color: #64748b; text-decoration: none; font-size: 0.875rem;">
        <i class="ti ti-arrow-left me-1"></i>
        العودة للصفحة الرئيسية
      </a>
    </div>
  </div>
</div>

<script>
function togglePassword() {
    const passwordField = document.getElementById('password');
    const toggleIcon = document.getElementById('toggleIcon');

    if (passwordField.type === 'password') {
        passwordField.type = 'text';
        toggleIcon.className = 'ti ti-eye';
    } else {
        passwordField.type = 'password';
        toggleIcon.className = 'ti ti-eye-off';
    }
}

// تأثيرات إضافية للنموذج
document.addEventListener('DOMContentLoaded', function() {
    // تأكيد عمل النموذج
    const form = document.getElementById('formAuthentication');
    const loginBtn = document.getElementById('loginBtn');

    if (form && loginBtn) {
        // تأكيد عمل الزر - إزالة preventDefault للسماح بالإرسال العادي
        loginBtn.addEventListener('click', function(e) {
            console.log('تم الضغط على زر تسجيل الدخول');

            // فحص صحة الحقول
            const email = document.getElementById('email');
            const password = document.getElementById('password');

            if (!email.value.trim()) {
                alert('يرجى إدخال البريد الإلكتروني');
                email.focus();
                e.preventDefault();
                return false;
            }

            if (!password.value.trim()) {
                alert('يرجى إدخال كلمة المرور');
                password.focus();
                e.preventDefault();
                return false;
            }

            // إظهار حالة التحميل
            this.innerHTML = '<i class="ti ti-loader-2 me-2" style="animation: spin 1s linear infinite;"></i> جاري تسجيل الدخول...';
            this.disabled = true;

            // السماح بالإرسال العادي للنموذج
            // لا نحتاج preventDefault هنا
        });

        // تأكيد عمل النموذج عند الضغط على Enter
        form.addEventListener('submit', function(e) {
            console.log('تم إرسال النموذج');
        });
    }

    // تأثير focus للحقول
    const formControls = document.querySelectorAll('.form-control');
    formControls.forEach(control => {
        control.addEventListener('focus', function() {
            this.style.borderColor = '#1f36ad';
            this.style.boxShadow = '0 0 0 3px rgba(31, 54, 173, 0.1)';
        });

        control.addEventListener('blur', function() {
            this.style.borderColor = '#e2e8f0';
            this.style.boxShadow = 'none';
        });
    });

    // تأثير الأزرار
    const buttons = document.querySelectorAll('.btn-primary, .btn-outline-danger');
    buttons.forEach(button => {
        button.addEventListener('mousedown', function() {
            this.style.transform = 'scale(0.98)';
        });

        button.addEventListener('mouseup', function() {
            this.style.transform = 'scale(1)';
        });
    });
});


</script>
@endsection
