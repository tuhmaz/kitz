@php
$customizerHidden = 'customizer-hide';
$configData = Helper::appClasses();
@endphp

@extends('layouts/blankLayout')

@section('title', __('تأكيد البريد الإلكتروني'))

@section('page-style')
<!-- Page -->
@vite('resources/assets/vendor/scss/pages/page-auth.scss')
@endsection

@section('content')
<div class="edu-verify-container">
  <!-- Floating Animated Elements -->
  <div class="edu-floating-elements">
    <div class="edu-float-icon edu-float-1">
      <i class="ti ti-mail-check" style="color: #1f36ad; font-size: 24px;"></i>
    </div>
    <div class="edu-float-icon edu-float-2">
      <i class="ti ti-shield-check" style="color: #286aad; font-size: 20px;"></i>
    </div>
    <div class="edu-float-icon edu-float-3">
      <i class="ti ti-lock-check" style="color: #3b82f6; font-size: 22px;"></i>
    </div>
    <div class="edu-float-icon edu-float-4">
      <i class="ti ti-circle-check" style="color: #1f36ad; font-size: 26px;"></i>
    </div>
  </div>
  <!-- Main Verify Card -->
  <div class="edu-verify-card">
    <!-- Brand Section -->
    <div class="edu-brand-section">
      <div class="edu-brand-logo">
        <img src="{{ asset('storage/' . config('settings.site_logo')) }}"
             alt="{{ config('settings.site_name') }} Logo"
             loading="lazy" />
      </div>
      <h2 class="edu-brand-title">
        <i class="ti ti-mail-check me-2" style="color: #1f36ad;"></i>
        تأكيد البريح الإلكتروني 📬
      </h2>
      <p class="edu-brand-subtitle">
        تم إرسال رابط التفعيل إلى بريدك الإلكتروني
      </p>
    </div>
    <!-- Success Message -->
    @if (session('status') == 'verification-link-sent')
    <div class="alert alert-success" style="border-radius: 12px; border: none; background: linear-gradient(135deg, #d1fae5, #a7f3d0); color: #065f46; margin-bottom: 1.5rem;">
        <i class="ti ti-check-circle me-2"></i>
        {{ __('تم إرسال رابط تحقق جديد إلى عنوان بريدك الإلكتروني.') }}
    </div>
    @endif
    
    <!-- Error Messages -->
    @if ($errors->any())
    <div class="alert alert-danger" style="border-radius: 12px; border: none; background: linear-gradient(135deg, #fee2e2, #fecaca); color: #dc2626; margin-bottom: 1.5rem;">
        <i class="ti ti-alert-circle me-2"></i>
        <ul class="mb-0" style="list-style: none; padding: 0;">
            @foreach ($errors->all() as $error)
            <li><i class="ti ti-point me-1"></i>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <!-- Email Info -->
    <div class="edu-email-info">
      <div class="edu-email-display">
        <i class="ti ti-mail" style="color: #1f36ad;"></i>
        <div>
          <span class="edu-email-label">البريد الإلكتروني:</span>
          <span class="edu-email-value">{{ Auth::user()->email }}</span>
        </div>
      </div>
    </div>

    <!-- Alert Messages -->
    <div id="alert-container"></div>

    <!-- Action Buttons -->
    <div class="edu-actions">
      <!-- Resend Verification Link -->
      <form method="POST" action="{{ route('verification.send') }}" id="resend-form" class="mb-3">
        @csrf
        <button type="submit" class="btn btn-primary w-100" id="resend-button">
          <i class="ti ti-mail me-2"></i>
          إعادة إرسال رابط التفعيل
        </button>
      </form>

      <!-- Logout -->
      <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit" class="btn btn-outline-secondary w-100">
          <i class="ti ti-logout me-2"></i>
          تسجيل الخروج
        </button>
      </form>
    </div>

    <!-- Help Section -->
    <div class="edu-help-section">
      <p class="edu-help-text">
        <i class="ti ti-info-circle me-1" style="color: #1f36ad;"></i>
        لم تستلم الرسالة؟
      </p>
      <a href="{{ url('/contact-us') }}" class="edu-help-link">
        <i class="ti ti-headset me-1"></i>
        تواصل مع الدعم الفني
      </a>
    </div>
  </div>
</div>

<style>
/* Verify Email Page Styles */
.edu-verify-container {
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

/* Verify Card */
.edu-verify-card {
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

/* Email Info */
.edu-email-info {
  margin-bottom: 2rem;
}

.edu-email-display {
  background: rgba(31, 54, 173, 0.05);
  border: 2px solid rgba(31, 54, 173, 0.1);
  border-radius: 12px;
  padding: 1rem;
  display: flex;
  align-items: center;
  gap: 0.75rem;
}

.edu-email-display i {
  font-size: 1.25rem;
}

.edu-email-label {
  display: block;
  font-weight: 600;
  color: #374151;
  font-size: 0.875rem;
  margin-bottom: 0.25rem;
}

.edu-email-value {
  color: #1f36ad;
  font-weight: 500;
  font-size: 1rem;
}

/* Actions */
.edu-actions {
  margin-bottom: 2rem;
}

.edu-actions .btn {
  border-radius: 12px;
  padding: 0.875rem 2rem;
  font-weight: 600;
  font-size: 1rem;
  transition: all 0.3s ease;
  margin-bottom: 1rem;
}

.btn-primary {
  background: linear-gradient(135deg, #1f36ad, #3b82f6);
  border: none;
  box-shadow: 0 4px 15px rgba(31, 54, 173, 0.3);
}

.btn-primary:hover {
  transform: translateY(-2px);
  box-shadow: 0 8px 25px rgba(31, 54, 173, 0.4);
  background: linear-gradient(135deg, #1e3a8a, #2563eb);
}

.btn-outline-secondary {
  border: 2px solid #e2e8f0;
  color: #64748b;
  background: transparent;
}

.btn-outline-secondary:hover {
  background: #f8fafc;
  border-color: #cbd5e1;
  color: #475569;
  transform: translateY(-1px);
}

/* Help Section */
.edu-help-section {
  text-align: center;
  padding-top: 1.5rem;
  border-top: 1px solid #e2e8f0;
}

.edu-help-text {
  color: #64748b;
  font-size: 0.875rem;
  margin-bottom: 0.75rem;
}

.edu-help-link {
  color: #1f36ad;
  text-decoration: none;
  font-weight: 600;
  font-size: 0.875rem;
  transition: all 0.3s ease;
  display: inline-flex;
  align-items: center;
}

.edu-help-link:hover {
  color: #1e3a8a;
  transform: translateY(-1px);
}

/* Alert Styles */
#alert-container .alert {
  border-radius: 12px;
  border: none;
  margin-bottom: 1.5rem;
  padding: 1rem 1.25rem;
  font-weight: 500;
}

#alert-container .alert-success {
  background: linear-gradient(135deg, #d1fae5, #a7f3d0);
  color: #065f46;
}

#alert-container .alert-danger {
  background: linear-gradient(135deg, #fee2e2, #fecaca);
  color: #dc2626;
}

/* Responsive Design */
@media (max-width: 768px) {
  .edu-floating-elements {
    display: none;
  }
  
  .edu-verify-card {
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

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
  const resendButton = document.getElementById('resend-button');
  const resendForm = document.getElementById('resend-form');
  const alertContainer = document.getElementById('alert-container');
  let cooldown = false;
  let cooldownTime = 60; // 60 seconds cooldown

  function showAlert(message, type = 'success') {
    const alertHtml = `
      <div class="alert alert-${type} fade show" role="alert">
        <i class="ti ti-${type === 'success' ? 'check-circle' : 'alert-circle'} me-2"></i>
        ${message}
      </div>
    `;
    alertContainer.innerHTML = alertHtml;

    // Auto hide after 5 seconds
    setTimeout(() => {
      const alert = alertContainer.querySelector('.alert');
      if (alert) {
        alert.classList.remove('show');
        setTimeout(() => alertContainer.innerHTML = '', 300);
      }
    }, 5000);
  }

  if (resendForm && resendButton) {
    resendForm.addEventListener('submit', function (e) {
      e.preventDefault();

      if (cooldown) {
        return;
      }

      resendButton.disabled = true;
      const originalText = resendButton.innerHTML;
      resendButton.innerHTML = '<i class="ti ti-loader-2 me-2" style="animation: spin 1s linear infinite;"></i>جاري الإرسال...';

      // Send AJAX request
      fetch(resendForm.action, {
        method: 'POST',
        headers: {
          'Accept': 'application/json',
          'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
        },
        body: new FormData(resendForm)
      })
      .then(response => response.json())
      .then(data => {
        if (data.status) {
          showAlert(data.message || 'تم إرسال رابط التحقق بنجاح', 'success');
          
          // Start cooldown
          cooldown = true;
          let timeLeft = cooldownTime;

          const interval = setInterval(() => {
            timeLeft--;
            resendButton.innerHTML = `<i class="ti ti-clock me-2"></i>انتظر ${timeLeft} ثانية`;

            if (timeLeft <= 0) {
              clearInterval(interval);
              cooldown = false;
              resendButton.disabled = false;
              resendButton.innerHTML = originalText;
            }
          }, 1000);
        } else {
          throw new Error(data.message || 'حدث خطأ أثناء إرسال رابط التحقق');
        }
      })
      .catch(error => {
        showAlert(error.message, 'danger');
        resendButton.disabled = false;
        resendButton.innerHTML = originalText;
      });
    });
  }

  // Button hover effects
  const buttons = document.querySelectorAll('.btn');
  buttons.forEach(button => {
    button.addEventListener('mouseenter', function() {
      if (!this.disabled) {
        this.style.transform = 'translateY(-2px)';
      }
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
@endpush
@endsection