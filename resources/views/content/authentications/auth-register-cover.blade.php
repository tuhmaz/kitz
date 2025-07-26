@php
$configData = Helper::appClasses();
@endphp

@extends('layouts/blankLayout')

@section('title', 'إنشاء حساب جديد')

@section('vendor-style')
@vite([
  'resources/assets/vendor/libs/@form-validation/form-validation.scss'
])
@endsection

@section('page-style')
@vite([
  'resources/assets/vendor/scss/pages/page-auth.scss'
])
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
<div class="edu-register-container">
  <!-- Floating Animated Elements -->
  <div class="edu-floating-elements">
    <div class="edu-float-icon edu-float-1">
      <i class="ti ti-user-plus" style="color: #1f36ad; font-size: 24px;"></i>
    </div>
    <div class="edu-float-icon edu-float-2">
      <i class="ti ti-mail" style="color: #286aad; font-size: 20px;"></i>
    </div>
    <div class="edu-float-icon edu-float-3">
      <i class="ti ti-lock" style="color: #3b82f6; font-size: 22px;"></i>
    </div>
    <div class="edu-float-icon edu-float-4">
      <i class="ti ti-shield-check" style="color: #1f36ad; font-size: 26px;"></i>
    </div>
  </div>
  <!-- Main Register Card -->
  <div class="edu-register-card">
    <!-- Brand Section -->
    <div class="edu-brand-section">
      <div class="edu-brand-logo">
        <img src="{{ asset('storage/' . config('settings.site_logo')) }}"
             alt="{{ config('settings.site_name') }} Logo"
             loading="lazy" />
      </div>
      <h2 class="edu-brand-title">
        <i class="ti ti-user-plus me-2" style="color: #1f36ad;"></i>
        إنشاء حساب جديد 🚀
      </h2>
      <p class="edu-brand-subtitle">
        انضم إلى منصتنا التعليمية واستمتع بتجربة تعلم مميزة
      </p>
    </div>

    <!-- Error Messages -->
    @if(session('error'))
    <div class="alert alert-danger" style="border-radius: 12px; border: none; background: linear-gradient(135deg, #fee2e2, #fecaca); color: #dc2626; margin-bottom: 1.5rem;">
        <i class="ti ti-alert-circle me-2"></i>
        {{ session('error') }}
    </div>
    @endif

    <!-- Register Form -->
    <form id="formAuthentication" class="mb-6" action="{{ route('register.submit') }}" method="POST">
      @csrf
      
      <!-- Username Field -->
      <div class="edu-form-group">
        <label for="username" class="edu-form-label">
          <i class="ti ti-user" style="color: #1f36ad;"></i>
          اسم المستخدم
        </label>
        <input type="text"
               class="form-control @error('name') is-invalid @enderror"
               id="username"
               name="name"
               placeholder="أدخل اسم المستخدم"
               value="{{ old('name') }}"
               required
               autofocus>
        @error('name')
        <div class="invalid-feedback" style="color: #dc2626; font-size: 0.875rem; margin-top: 0.5rem;">
            <i class="ti ti-alert-circle me-1"></i>
            {{ $message }}
        </div>
        @enderror
      </div>

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
               required>
        @error('email')
        <div class="invalid-feedback" style="color: #dc2626; font-size: 0.875rem; margin-top: 0.5rem;">
            <i class="ti ti-alert-circle me-1"></i>
            {{ $message }}
        </div>
        @enderror
      </div>
      <!-- Password Field -->
      <div class="edu-form-group">
        <label for="password" class="edu-form-label">
          <i class="ti ti-lock" style="color: #1f36ad;"></i>
          كلمة المرور
        </label>
        <div class="input-group">
          <input type="password"
                 id="password"
                 class="form-control @error('password') is-invalid @enderror"
                 name="password"
                 placeholder="أدخل كلمة المرور"
                 required />
          <span class="input-group-text cursor-pointer" onclick="togglePassword('password', 'toggleIcon1')">
            <i class="ti ti-eye-off" id="toggleIcon1"></i>
          </span>
        </div>
        @error('password')
        <div class="invalid-feedback" style="color: #dc2626; font-size: 0.875rem; margin-top: 0.5rem;">
            <i class="ti ti-alert-circle me-1"></i>
            {{ $message }}
        </div>
        @enderror
      </div>

      <!-- Confirm Password Field -->
      <div class="edu-form-group">
        <label for="password_confirmation" class="edu-form-label">
          <i class="ti ti-lock-check" style="color: #1f36ad;"></i>
          تأكيد كلمة المرور
        </label>
        <div class="input-group">
          <input type="password"
                 id="password_confirmation"
                 class="form-control @error('password_confirmation') is-invalid @enderror"
                 name="password_confirmation"
                 placeholder="أعد إدخال كلمة المرور"
                 required />
          <span class="input-group-text cursor-pointer" onclick="togglePassword('password_confirmation', 'toggleIcon2')">
            <i class="ti ti-eye-off" id="toggleIcon2"></i>
          </span>
        </div>
        @error('password_confirmation')
        <div class="invalid-feedback" style="color: #dc2626; font-size: 0.875rem; margin-top: 0.5rem;">
            <i class="ti ti-alert-circle me-1"></i>
            {{ $message }}
        </div>
        @enderror
      </div>  

      <!-- Terms and Conditions -->
      <div class="edu-form-group">
        <div class="form-check">
          <input class="form-check-input" type="checkbox" id="terms-conditions" name="terms" required>
          <label class="form-check-label" for="terms-conditions" style="font-size: 0.875rem; color: #64748b;">
            <i class="ti ti-check" style="color: #1f36ad;"></i>
            أوافق على 
            <a href="javascript:void(0);" style="color: #1f36ad; text-decoration: none; font-weight: 500;">
              شروط الاستخدام وسياسة الخصوصية
            </a>
          </label>
        </div>
      </div>

      <!-- Submit Button -->
      <div class="edu-form-group">
        <button class="btn btn-primary w-100" type="submit" id="registerBtn">
          <i class="ti ti-user-plus me-2"></i>
          إنشاء الحساب
        </button>
      </div>
    </form>

    <!-- Divider -->
    <div class="text-center my-4">
      <div style="position: relative; text-align: center; margin: 2rem 0;">
        <hr style="border: none; height: 1px; background: linear-gradient(90deg, transparent, #e2e8f0, transparent);">
        <span style="position: absolute; top: -0.6rem; left: 50%; transform: translateX(-50%); background: white; padding: 0 1rem; color: #64748b; font-size: 0.875rem;">
          أو
        </span>
      </div>
    </div>

    <!-- Google Login -->
    <div class="text-center mb-4">
      <a href="{{ route('login.google') }}" class="btn btn-outline-danger w-100" style="border-radius: 12px; padding: 0.75rem 1.5rem; border-color: #dc2626; color: #dc2626;">
        <i class="tf-icons fa-brands fa-google me-2"></i>
        التسجيل باستخدام Google
      </a>
    </div>

    <!-- Login Link -->
    <div class="text-center">
      <p style="color: #64748b; font-size: 0.875rem; margin-bottom: 0.5rem;">
        هل لديك حساب بالفعل؟
      </p>
      <a href="{{ route('login') }}" class="edu-back-link">
        <i class="ti ti-login me-2"></i>
        تسجيل الدخول
      </a>
    </div>
  </div>
</div>

<style>
/* Register Page Styles */
.edu-register-container {
  min-height: 100vh;
  background: linear-gradient(135deg, #1f36ad 0%, #286aad 50%, #3b82f6 100%);
  display: flex;
  align-items: center;
  justify-content: center;
  padding: 2rem 1rem;
  position: relative;
  overflow: hidden;
}

/* Floating Elements */
.edu-floating-elements {
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  pointer-events: none;
  z-index: 1;
}

.edu-float-icon {
  position: absolute;
  background: rgba(255, 255, 255, 0.1);
  border-radius: 50%;
  padding: 1rem;
  backdrop-filter: blur(10px);
  animation: float 6s ease-in-out infinite;
}

.edu-float-1 {
  top: 10%;
  left: 10%;
  animation-delay: 0s;
}

.edu-float-2 {
  top: 20%;
  right: 15%;
  animation-delay: 2s;
}

.edu-float-3 {
  bottom: 30%;
  left: 20%;
  animation-delay: 4s;
}

.edu-float-4 {
  bottom: 15%;
  right: 10%;
  animation-delay: 1s;
}

@keyframes float {
  0%, 100% { transform: translateY(0px) rotate(0deg); }
  50% { transform: translateY(-20px) rotate(180deg); }
}

/* Register Card */
.edu-register-card {
  background: rgba(255, 255, 255, 0.95);
  backdrop-filter: blur(20px);
  border-radius: 24px;
  padding: 3rem;
  box-shadow: 0 25px 50px rgba(0, 0, 0, 0.15);
  border: 1px solid rgba(255, 255, 255, 0.2);
  max-width: 480px;
  width: 100%;
  position: relative;
  z-index: 2;
}

/* Brand Section */
.edu-brand-section {
  text-align: center;
  margin-bottom: 2rem;
}

.edu-brand-logo {
  width: 80px;
  height: 80px;
  margin: 0 auto 1.5rem;
  background: linear-gradient(135deg, #1f36ad, #3b82f6);
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  box-shadow: 0 10px 30px rgba(31, 54, 173, 0.3);
}

.edu-brand-logo img {
  width: 45px;
  height: 45px;
  border-radius: 50%;
}

.edu-brand-title {
  font-size: 1.75rem;
  font-weight: 700;
  color: #1e293b;
  margin-bottom: 0.5rem;
  line-height: 1.2;
}

.edu-brand-subtitle {
  color: #64748b;
  font-size: 1rem;
  margin-bottom: 0;
  line-height: 1.5;
}

/* Form Styles */
.edu-form-group {
  margin-bottom: 1.5rem;
}

.edu-form-label {
  display: block;
  font-weight: 600;
  color: #374151;
  margin-bottom: 0.5rem;
  font-size: 0.875rem;
}

.edu-form-group .form-control {
  border: 2px solid #e2e8f0;
  border-radius: 12px;
  padding: 0.75rem 1rem;
  font-size: 1rem;
  transition: all 0.3s ease;
  background: rgba(255, 255, 255, 0.8);
}

.edu-form-group .form-control:focus {
  border-color: #1f36ad;
  box-shadow: 0 0 0 3px rgba(31, 54, 173, 0.1);
  background: white;
}

.edu-form-group .input-group-text {
  background: rgba(31, 54, 173, 0.1);
  border: 2px solid #e2e8f0;
  border-left: none;
  border-radius: 0 12px 12px 0;
  color: #1f36ad;
}

/* Button Styles */
.btn-primary {
  background: linear-gradient(135deg, #1f36ad, #3b82f6);
  border: none;
  border-radius: 12px;
  padding: 0.875rem 2rem;
  font-weight: 600;
  font-size: 1rem;
  transition: all 0.3s ease;
  box-shadow: 0 4px 15px rgba(31, 54, 173, 0.3);
}

.btn-primary:hover {
  transform: translateY(-2px);
  box-shadow: 0 8px 25px rgba(31, 54, 173, 0.4);
  background: linear-gradient(135deg, #1e3a8a, #2563eb);
}

.btn-primary:active {
  transform: translateY(0);
}

/* Back Link */
.edu-back-link {
  color: #1f36ad;
  text-decoration: none;
  font-weight: 600;
  font-size: 0.875rem;
  transition: all 0.3s ease;
  display: inline-flex;
  align-items: center;
}

.edu-back-link:hover {
  color: #1e3a8a;
  transform: translateX(-3px);
}

/* Responsive Design */
@media (max-width: 768px) {
  .edu-floating-elements {
    display: none;
  }
  
  .edu-register-card {
    margin: 1rem;
    padding: 2rem;
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

<script>
// Toggle Password Visibility
function togglePassword(fieldId, iconId) {
    const passwordField = document.getElementById(fieldId);
    const toggleIcon = document.getElementById(iconId);
    
    if (passwordField.type === 'password') {
        passwordField.type = 'text';
        toggleIcon.className = 'ti ti-eye';
    } else {
        passwordField.type = 'password';
        toggleIcon.className = 'ti ti-eye-off';
    }
}

// Form Enhancement
document.addEventListener('DOMContentLoaded', function() {
    // Form validation and submission
    const form = document.getElementById('formAuthentication');
    const registerBtn = document.getElementById('registerBtn');
    
    if (form && registerBtn) {
        // Form submission handling
        registerBtn.addEventListener('click', function(e) {
            // Basic validation
            const username = document.getElementById('username');
            const email = document.getElementById('email');
            const password = document.getElementById('password');
            const passwordConfirmation = document.getElementById('password_confirmation');
            const terms = document.getElementById('terms-conditions');
            
            // Check required fields
            if (!username.value.trim()) {
                alert('يرجى إدخال اسم المستخدم');
                username.focus();
                e.preventDefault();
                return false;
            }
            
            if (!email.value.trim()) {
                alert('يرجى إدخال البريد الإلكتروني');
                email.focus();
                e.preventDefault();
                return false;
            }
            
            // Email validation
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRegex.test(email.value)) {
                alert('يرجى إدخال بريد إلكتروني صحيح');
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
            
            if (password.value.length < 8) {
                alert('يجب أن تكون كلمة المرور 8 أحرف على الأقل');
                password.focus();
                e.preventDefault();
                return false;
            }
            
            if (password.value !== passwordConfirmation.value) {
                alert('كلمتا المرور غير متطابقتين');
                passwordConfirmation.focus();
                e.preventDefault();
                return false;
            }
            
            if (!terms.checked) {
                alert('يجب الموافقة على شروط الاستخدام');
                terms.focus();
                e.preventDefault();
                return false;
            }
            
            // Show loading state
            this.innerHTML = '<i class="ti ti-loader-2 me-2" style="animation: spin 1s linear infinite;"></i> جاري إنشاء الحساب...';
            this.disabled = true;
        });
        
        // Form submission event
        form.addEventListener('submit', function(e) {
            console.log('تم إرسال نموذج التسجيل');
        });
    }
    
    // Focus effects for form controls
    const formControls = document.querySelectorAll('.form-control');
    formControls.forEach(control => {
        control.addEventListener('focus', function() {
            this.style.transform = 'translateY(-2px)';
            this.style.boxShadow = '0 8px 25px rgba(31, 54, 173, 0.15)';
        });
        
        control.addEventListener('blur', function() {
            this.style.transform = 'translateY(0)';
            this.style.boxShadow = 'none';
        });
    });
    
    // Button hover effects
    const buttons = document.querySelectorAll('.btn-primary');
    buttons.forEach(button => {
        button.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-2px)';
        });
        
        button.addEventListener('mouseleave', function() {
            if (!this.disabled) {
                this.style.transform = 'translateY(0)';
            }
        });
    });
});

// Spin animation for loading icon
const style = document.createElement('style');
style.textContent = `
@keyframes spin {
  from { transform: rotate(0deg); }
  to { transform: rotate(360deg); }
}
`;
document.head.appendChild(style);
</script>
@endsection
