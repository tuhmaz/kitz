@php
$customizerHidden = 'customizer-hide';
$configData = Helper::appClasses();
@endphp

@extends('layouts/blankLayout')

@section('title', 'التحقق بخطوتين')

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
  'resources/assets/vendor/libs/cleavejs/cleave.js',
  'resources/assets/vendor/libs/@form-validation/popular.js',
  'resources/assets/vendor/libs/@form-validation/bootstrap5.js',
  'resources/assets/vendor/libs/@form-validation/auto-focus.js'
])
@endsection

@section('page-script')
@vite([
  'resources/assets/js/pages-auth.js',
  'resources/assets/js/pages-auth-two-steps.js'
])
@endsection

@section('content')
<div class="edu-two-steps-container">
  <!-- Floating Animated Elements -->
  <div class="edu-floating-elements">
    <div class="edu-float-icon edu-float-1">
      <i class="ti ti-shield-lock" style="color: #1f36ad; font-size: 24px;"></i>
    </div>
    <div class="edu-float-icon edu-float-2">
      <i class="ti ti-device-mobile" style="color: #286aad; font-size: 20px;"></i>
    </div>
    <div class="edu-float-icon edu-float-3">
      <i class="ti ti-key" style="color: #3b82f6; font-size: 22px;"></i>
    </div>
    <div class="edu-float-icon edu-float-4">
      <i class="ti ti-lock-check" style="color: #1f36ad; font-size: 26px;"></i>
    </div>
  </div>
  <!-- Main Two Steps Card -->
  <div class="edu-two-steps-card">
    <!-- Brand Section -->
    <div class="edu-brand-section">
      <div class="edu-brand-logo">
        <img src="{{ asset('storage/' . config('settings.site_logo')) }}"
             alt="{{ config('settings.site_name') }} Logo"
             loading="lazy" />
      </div>
      <h2 class="edu-brand-title">
        <i class="ti ti-shield-lock me-2" style="color: #1f36ad;"></i>
        التحقق بخطوتين 🔒
      </h2>
      <p class="edu-brand-subtitle">
        تم إرسال رمز التحقق إلى هاتفك المحمول
      </p>
    </div>

    <!-- Phone Info -->
    <div class="edu-phone-info">
      <div class="edu-phone-display">
        <i class="ti ti-device-mobile" style="color: #1f36ad;"></i>
        <div>
          <span class="edu-phone-label">رقم الهاتف:</span>
          <span class="edu-phone-value">******1234</span>
        </div>
      </div>
    </div>

    <!-- Instructions -->
    <div class="edu-instructions">
      <p>
        <i class="ti ti-info-circle me-2" style="color: #1f36ad;"></i>
        أدخل الرمز المكون من 6 أرقام
      </p>
    </div>
    <!-- OTP Form -->
    <form id="twoStepsForm" action="{{url('/')}}" method="GET">
      <div class="edu-otp-section">
        <div class="edu-otp-inputs">
          <input type="tel" class="edu-otp-input" maxlength="1" autofocus data-index="0">
          <input type="tel" class="edu-otp-input" maxlength="1" data-index="1">
          <input type="tel" class="edu-otp-input" maxlength="1" data-index="2">
          <input type="tel" class="edu-otp-input" maxlength="1" data-index="3">
          <input type="tel" class="edu-otp-input" maxlength="1" data-index="4">
          <input type="tel" class="edu-otp-input" maxlength="1" data-index="5">
        </div>
        <input type="hidden" name="otp" id="otpValue" />
      </div>

      <!-- Submit Button -->
      <div class="edu-form-group">
        <button class="btn btn-primary w-100" type="submit" id="verifyBtn">
          <i class="ti ti-shield-check me-2"></i>
          تأكيد الحساب
        </button>
      </div>
    </form>

    <!-- Resend Section -->
    <div class="edu-resend-section">
      <p class="edu-resend-text">
        لم تستلم الرمز؟
      </p>
      <button class="edu-resend-btn" id="resendBtn">
        <i class="ti ti-refresh me-1"></i>
        إعادة الإرسال
      </button>
    </div>

    <!-- Back Link -->
    <div class="text-center">
      <a href="{{ route('login') }}" class="edu-back-link">
        <i class="ti ti-arrow-left me-2"></i>
        العودة لتسجيل الدخول
      </a>
    </div>
  </div>
</div>

<style>
/* Two Steps Verification Page Styles */
.edu-two-steps-container {
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

/* Two Steps Card */
.edu-two-steps-card {
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

/* Phone Info */
.edu-phone-info {
  margin-bottom: 1.5rem;
}

.edu-phone-display {
  background: rgba(31, 54, 173, 0.05);
  border: 2px solid rgba(31, 54, 173, 0.1);
  border-radius: 12px;
  padding: 1rem;
  display: flex;
  align-items: center;
  gap: 0.75rem;
}

.edu-phone-display i {
  font-size: 1.25rem;
}

.edu-phone-label {
  display: block;
  font-weight: 600;
  color: #374151;
  font-size: 0.875rem;
  margin-bottom: 0.25rem;
}

.edu-phone-value {
  color: #1f36ad;
  font-weight: 500;
  font-size: 1rem;
  font-family: monospace;
}

/* Instructions */
.edu-instructions {
  text-align: center;
  margin-bottom: 2rem;
}

.edu-instructions p {
  color: #64748b;
  font-size: 0.875rem;
  margin: 0;
  display: flex;
  align-items: center;
  justify-content: center;
}

/* OTP Section */
.edu-otp-section {
  margin-bottom: 2rem;
}

.edu-otp-inputs {
  display: flex;
  gap: 0.75rem;
  justify-content: center;
  margin-bottom: 1.5rem;
}

.edu-otp-input {
  width: 50px;
  height: 50px;
  border: 2px solid #e2e8f0;
  border-radius: 12px;
  text-align: center;
  font-size: 1.25rem;
  font-weight: 600;
  color: #1e293b;
  background: rgba(255, 255, 255, 0.8);
  transition: all 0.3s ease;
}

.edu-otp-input:focus {
  outline: none;
  border-color: #1f36ad;
  box-shadow: 0 0 0 3px rgba(31, 54, 173, 0.1);
  background: white;
  transform: scale(1.05);
}

.edu-otp-input.filled {
  border-color: #10b981;
  background: rgba(16, 185, 129, 0.1);
  color: #065f46;
}

.edu-otp-input.error {
  border-color: #ef4444;
  background: rgba(239, 68, 68, 0.1);
  animation: shake 0.5s ease-in-out;
}

@keyframes shake {
  0%, 100% { transform: translateX(0); }
  25% { transform: translateX(-5px); }
  75% { transform: translateX(5px); }
}

/* Form Group */
.edu-form-group {
  margin-bottom: 1.5rem;
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

.btn-primary:disabled {
  opacity: 0.6;
  cursor: not-allowed;
  transform: none;
}

/* Resend Section */
.edu-resend-section {
  text-align: center;
  margin-bottom: 2rem;
}

.edu-resend-text {
  color: #64748b;
  font-size: 0.875rem;
  margin-bottom: 0.75rem;
}

.edu-resend-btn {
  background: none;
  border: none;
  color: #1f36ad;
  font-weight: 600;
  font-size: 0.875rem;
  cursor: pointer;
  transition: all 0.3s ease;
  display: inline-flex;
  align-items: center;
}

.edu-resend-btn:hover {
  color: #1e3a8a;
  transform: translateY(-1px);
}

.edu-resend-btn:disabled {
  opacity: 0.5;
  cursor: not-allowed;
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
  
  .edu-two-steps-card {
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
  
  .edu-otp-inputs {
    gap: 0.5rem;
  }
  
  .edu-otp-input {
    width: 45px;
    height: 45px;
    font-size: 1.1rem;
  }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const otpInputs = document.querySelectorAll('.edu-otp-input');
    const otpValue = document.getElementById('otpValue');
    const verifyBtn = document.getElementById('verifyBtn');
    const resendBtn = document.getElementById('resendBtn');
    const form = document.getElementById('twoStepsForm');
    
    let resendCooldown = false;
    let cooldownTime = 60;
    
    // OTP Input Handling
    otpInputs.forEach((input, index) => {
        input.addEventListener('input', function(e) {
            const value = e.target.value;
            
            // Only allow numbers
            if (!/^[0-9]$/.test(value) && value !== '') {
                e.target.value = '';
                return;
            }
            
            // Update visual state
            if (value) {
                e.target.classList.add('filled');
                e.target.classList.remove('error');
                
                // Move to next input
                if (index < otpInputs.length - 1) {
                    otpInputs[index + 1].focus();
                }
            } else {
                e.target.classList.remove('filled');
            }
            
            updateOtpValue();
        });
        
        input.addEventListener('keydown', function(e) {
            // Handle backspace
            if (e.key === 'Backspace' && !e.target.value && index > 0) {
                otpInputs[index - 1].focus();
                otpInputs[index - 1].value = '';
                otpInputs[index - 1].classList.remove('filled');
                updateOtpValue();
            }
            
            // Handle paste
            if (e.key === 'v' && (e.ctrlKey || e.metaKey)) {
                e.preventDefault();
                navigator.clipboard.readText().then(text => {
                    const numbers = text.replace(/\D/g, '').slice(0, 6);
                    numbers.split('').forEach((digit, i) => {
                        if (otpInputs[i]) {
                            otpInputs[i].value = digit;
                            otpInputs[i].classList.add('filled');
                        }
                    });
                    updateOtpValue();
                });
            }
        });
        
        // Focus effects
        input.addEventListener('focus', function() {
            this.style.transform = 'scale(1.05)';
        });
        
        input.addEventListener('blur', function() {
            this.style.transform = 'scale(1)';
        });
    });
    
    function updateOtpValue() {
        const otp = Array.from(otpInputs).map(input => input.value).join('');
        otpValue.value = otp;
        
        // Enable/disable verify button
        if (otp.length === 6) {
            verifyBtn.disabled = false;
        } else {
            verifyBtn.disabled = true;
        }
    }
    
    // Form submission
    if (form && verifyBtn) {
        form.addEventListener('submit', function(e) {
            const otp = otpValue.value;
            
            if (otp.length !== 6) {
                e.preventDefault();
                otpInputs.forEach(input => {
                    input.classList.add('error');
                });
                alert('يرجى إدخال الرمز كاملاً');
                return false;
            }
            
            // Show loading state
            verifyBtn.innerHTML = '<i class="ti ti-loader-2 me-2" style="animation: spin 1s linear infinite;"></i> جاري التحقق...';
            verifyBtn.disabled = true;
        });
    }
    
    // Resend functionality
    if (resendBtn) {
        resendBtn.addEventListener('click', function() {
            if (resendCooldown) return;
            
            // Clear inputs
            otpInputs.forEach(input => {
                input.value = '';
                input.classList.remove('filled', 'error');
            });
            otpInputs[0].focus();
            updateOtpValue();
            
            // Start cooldown
            resendCooldown = true;
            let timeLeft = cooldownTime;
            
            const interval = setInterval(() => {
                timeLeft--;
                resendBtn.innerHTML = `<i class="ti ti-clock me-1"></i> انتظر ${timeLeft} ثانية`;
                resendBtn.disabled = true;
                
                if (timeLeft <= 0) {
                    clearInterval(interval);
                    resendCooldown = false;
                    resendBtn.innerHTML = '<i class="ti ti-refresh me-1"></i> إعادة الإرسال';
                    resendBtn.disabled = false;
                }
            }, 1000);
            
            // Simulate resend (replace with actual API call)
            console.log('تم إعادة إرسال رمز التحقق');
        });
    }
    
    // Button hover effects
    const buttons = document.querySelectorAll('.btn, .edu-resend-btn');
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
    
    // Initialize
    updateOtpValue();
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
