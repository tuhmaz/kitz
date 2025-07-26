@php
$customizerHidden = 'customizer-hide';
$configData = Helper::appClasses();
@endphp

@extends('layouts/blankLayout')

@section('title', 'Forgot Password')

@section('vendor-style')
@vite([
  'resources/assets/vendor/libs/@form-validation/form-validation.scss'
])
@endsection

@section('page-style')
@vite(['resources/assets/vendor/scss/pages/page-auth.scss'])
<style>
.edu-forgot-wrapper {
  min-height: 100vh;
  background: linear-gradient(135deg, #1f36ad 0%, #286aad 50%, #3b82f6 100%);
  position: relative;
  overflow: hidden;
}

.edu-floating-elements {
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  pointer-events: none;
  z-index: 1;
}

.edu-floating-icon {
  position: absolute;
  background: rgba(255, 255, 255, 0.1);
  border-radius: 50%;
  padding: 1rem;
  animation: float 6s ease-in-out infinite;
}

.edu-floating-icon:nth-child(1) {
  top: 15%;
  left: 15%;
  animation-delay: 0s;
}

.edu-floating-icon:nth-child(2) {
  top: 25%;
  right: 20%;
  animation-delay: 2s;
}

.edu-floating-icon:nth-child(3) {
  bottom: 35%;
  left: 25%;
  animation-delay: 4s;
}

.edu-floating-icon:nth-child(4) {
  bottom: 25%;
  right: 15%;
  animation-delay: 1s;
}

@keyframes float {
  0%, 100% { transform: translateY(0px) rotate(0deg); }
  50% { transform: translateY(-20px) rotate(180deg); }
}

.edu-forgot-card {
  background: rgba(255, 255, 255, 0.95);
  backdrop-filter: blur(10px);
  border-radius: 20px;
  box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
  position: relative;
  z-index: 2;
}

.edu-brand-section {
  text-align: center;
  margin-bottom: 2rem;
}

.edu-brand-logo {
  width: 80px;
  height: 80px;
  background: linear-gradient(135deg, #1f36ad, #3b82f6);
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  margin: 0 auto 1rem;
  box-shadow: 0 10px 20px rgba(31, 54, 173, 0.3);
}

.edu-welcome-text {
  color: #1f36ad;
  font-weight: 700;
  margin-bottom: 0.5rem;
}

.edu-subtitle {
  color: #64748b;
  font-size: 0.95rem;
  line-height: 1.5;
}

.edu-form-group {
  margin-bottom: 1.5rem;
}

.edu-form-label {
  display: block;
  margin-bottom: 0.5rem;
  font-weight: 600;
  color: #374151;
}

.form-control {
  border: 2px solid #e2e8f0;
  border-radius: 12px;
  padding: 0.75rem 1rem;
  font-size: 0.95rem;
  transition: all 0.3s ease;
}

.form-control:focus {
  border-color: #1f36ad;
  box-shadow: 0 0 0 3px rgba(31, 54, 173, 0.1);
}

.btn-primary {
  background: linear-gradient(135deg, #1f36ad, #3b82f6);
  border: none;
  border-radius: 12px;
  padding: 0.75rem 1.5rem;
  font-weight: 600;
  transition: all 0.3s ease;
}

.btn-primary:hover {
  transform: translateY(-2px);
  box-shadow: 0 10px 20px rgba(31, 54, 173, 0.3);
}

.edu-back-link {
  display: inline-flex;
  align-items: center;
  color: #64748b;
  text-decoration: none;
  font-weight: 500;
  transition: all 0.3s ease;
  padding: 0.5rem 1rem;
  border-radius: 8px;
}

.edu-back-link:hover {
  color: #1f36ad;
  background: rgba(31, 54, 173, 0.1);
  transform: translateX(-3px);
}

@keyframes spin {
  0% { transform: rotate(0deg); }
  100% { transform: rotate(360deg); }
}

@media (max-width: 768px) {
  .edu-floating-elements {
    display: none;
  }

  .edu-forgot-card {
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
<div class="edu-forgot-wrapper d-flex align-items-center justify-content-center">
  <!-- Floating Elements -->
  <div class="edu-floating-elements">
    <div class="edu-floating-icon">
      <i class="ti ti-lock text-white" style="font-size: 2rem;"></i>
    </div>
    <div class="edu-floating-icon">
      <i class="ti ti-mail text-white" style="font-size: 1.5rem;"></i>
    </div>
    <div class="edu-floating-icon">
      <i class="ti ti-key text-white" style="font-size: 1.75rem;"></i>
    </div>
    <div class="edu-floating-icon">
      <i class="ti ti-shield-check text-white" style="font-size: 2.25rem;"></i>
    </div>
  </div>

  <!-- Forgot Password Card -->
  <div class="edu-forgot-card" style="width: 100%; max-width: 480px; margin: 2rem; padding: 3rem;">
    <!-- Brand Section -->
    <div class="edu-brand-section">
      <div class="edu-brand-logo">
        <img src="{{ asset('storage/' . config('settings.site_logo')) }}"
             alt="{{ config('settings.site_name') }} Logo"
             width="60"
             height="60"
             loading="lazy" />
      </div>
      <h2 class="edu-welcome-text">نسيت كلمة المرور؟ 🔒</h2>
      <p class="edu-subtitle">أدخل بريدك الإلكتروني وسنرسل لك تعليمات إعادة تعيين كلمة المرور</p>
    </div>

    <!-- Success Message -->
    @if(session('status'))
    <div class="alert alert-success" style="border-radius: 12px; border: none; background: linear-gradient(135deg, #dcfce7, #bbf7d0); color: #16a34a; margin-bottom: 1.5rem;">
        <i class="ti ti-check-circle me-2"></i>
        {{ session('status') }}
    </div>
    @endif

    <!-- Forgot Password Form -->
    <form id="formAuthentication" class="mb-6" action="{{ route('password.email') }}" method="POST">

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

      <!-- Submit Button -->
      <div class="edu-form-group">
        <button class="btn btn-primary w-100" type="submit" id="resetBtn">
          <i class="ti ti-send me-2"></i>
          إرسال رابط إعادة التعيين
        </button>
      </div>
    </form>

    <!-- Back to Login -->
    <div class="text-center">
      <a href="{{ route('login') }}" class="edu-back-link">
        <i class="ti ti-arrow-left me-2"></i>
        العودة لتسجيل الدخول
      </a>
    </div>

    <!-- Help Section -->
    <div class="text-center mt-4" style="padding: 1rem; background: rgba(31, 54, 173, 0.05); border-radius: 12px;">
      <p style="color: #64748b; margin-bottom: 0.5rem; font-size: 0.875rem;">
        <i class="ti ti-info-circle me-1" style="color: #1f36ad;"></i>
        هل تحتاج مساعدة؟
      </p>
      <a href="{{ url('/contact-us') }}"
         style="color: #1f36ad; text-decoration: none; font-weight: 500; font-size: 0.875rem;">
        <i class="ti ti-headset me-1"></i>
        تواصل مع الدعم الفني
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
    const resetBtn = document.getElementById('resetBtn');

    if (form && resetBtn) {
        // تأكيد عمل الزر
        resetBtn.addEventListener('click', function(e) {
            console.log('تم الضغط على زر إعادة تعيين كلمة المرور');

            // فحص صحة البريد الإلكتروني
            const email = document.getElementById('email');

            if (!email.value.trim()) {
                alert('يرجى إدخال البريد الإلكتروني');
                email.focus();
                e.preventDefault();
                return false;
            }

            // فحص صيغة البريد الإلكتروني
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRegex.test(email.value)) {
                alert('يرجى إدخال بريد إلكتروني صحيح');
                email.focus();
                e.preventDefault();
                return false;
            }

            // إظهار حالة التحميل
            this.innerHTML = '<i class="ti ti-loader-2 me-2" style="animation: spin 1s linear infinite;"></i> جاري الإرسال...';
            this.disabled = true;
        });

        // تأكيد عمل النموذج عند الضغط على Enter
        form.addEventListener('submit', function(e) {
            console.log('تم إرسال نموذج إعادة تعيين كلمة المرور');
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
    const buttons = document.querySelectorAll('.btn-primary');
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
