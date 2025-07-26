@extends('layouts/layoutFront')

@section('title', __('اتصل بنا'))

@section('vendor-style')
@vite('resources/assets/vendor/scss/home.scss')
@endsection

@section('content')
<!-- Hero Section -->
<section class="contact-hero position-relative overflow-hidden">
    <!-- Floating Icons -->
    <div class="contact-floating-icons">
        <i class="ti ti-phone contact-floating-icon" style="color: #e74c3c;"></i>
        <i class="ti ti-mail contact-floating-icon" style="color: #3498db;"></i>
        <i class="ti ti-message-circle contact-floating-icon" style="color: #f39c12;"></i>
        <i class="ti ti-map-pin contact-floating-icon" style="color: #27ae60;"></i>
    </div>

    <div class="container position-relative">
        <div class="row justify-content-center">
            <div class="col-12 col-lg-8 text-center">
                <div class="contact-hero-content">
                    <div class="contact-hero-icon mb-4">
                        <i class="ti ti-phone"></i>
                    </div>
                    <h1 class="text-white display-5 fw-bold mb-3">{{ __('تواصل معنا') }}</h1>
                    <p class="text-white-50 mb-4 fs-5">{{ __('نحن هنا لمساعدتك والإجابة على جميع استفساراتك') }}</p>

                    <!-- Contact Stats -->
                    <div class="contact-stats">
                        <div class="row g-3 justify-content-center">
                            <div class="col-6 col-md-3">
                                <div class="stat-item">
                                    <div class="stat-icon">
                                        <i class="ti ti-clock"></i>
                                    </div>
                                    <div class="stat-number">24/7</div>
                                    <div class="stat-label">دعم مستمر</div>
                                </div>
                            </div>
                            <div class="col-6 col-md-3">
                                <div class="stat-item">
                                    <div class="stat-icon">
                                        <i class="ti ti-message-check"></i>
                                    </div>
                                    <div class="stat-number">< 24</div>
                                    <div class="stat-label">ساعة رد</div>
                                </div>
                            </div>
                            <div class="col-6 col-md-3">
                                <div class="stat-item">
                                    <div class="stat-icon">
                                        <i class="ti ti-users"></i>
                                    </div>
                                    <div class="stat-number">1000+</div>
                                    <div class="stat-label">عميل راضي</div>
                                </div>
                            </div>
                            <div class="col-6 col-md-3">
                                <div class="stat-item">
                                    <div class="stat-icon">
                                        <i class="ti ti-star"></i>
                                    </div>
                                    <div class="stat-number">4.9</div>
                                    <div class="stat-label">تقييم الخدمة</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Main Content -->
<div class="container section-py">
    <div class="row g-4">
        <!-- Contact Information -->
        <div class="col-lg-4">
            <div class="row g-4">
                <!-- Email -->
                <div class="col-12">
                    <div class="contact-info-card email-card">
                        <div class="contact-card-header">
                            <div class="contact-card-icon">
                                <i class="ti ti-mail"></i>
                            </div>
                            <h5 class="mb-1">{{ __('البريد الإلكتروني') }}</h5>
                            <p class="text-muted mb-0">{{ __('راسلنا في أي وقت') }}</p>
                        </div>
                        <div class="contact-card-body">
                            <a href="mailto:{{ $settings['contact_email'] ?? 'info@alemedu.com' }}" class="contact-link">
                                <i class="ti ti-mail me-2"></i>
                                {{ $settings['contact_email'] ?? 'info@alemedu.com' }}
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Phone -->
                @if(isset($settings['contact_phone']) && !empty($settings['contact_phone']))
                <div class="col-12">
                    <div class="contact-info-card phone-card">
                        <div class="contact-card-header">
                            <div class="contact-card-icon">
                                <i class="ti ti-phone"></i>
                            </div>
                            <h5 class="mb-1">{{ __('الهاتف') }}</h5>
                            <p class="text-muted mb-0">{{ __('اتصل بنا مباشرة') }}</p>
                        </div>
                        <div class="contact-card-body">
                            <a href="tel:{{ $settings['contact_phone'] }}" class="contact-link">
                                <i class="ti ti-phone me-2"></i>
                                {{ $settings['contact_phone'] }}
                            </a>
                        </div>
                    </div>
                </div>
                @endif

                <!-- Social Media -->
                <div class="col-12">
                    <div class="contact-info-card social-card">
                        <div class="contact-card-header">
                            <div class="contact-card-icon">
                                <i class="ti ti-share"></i>
                            </div>
                            <h5 class="mb-1">{{ __('وسائل التواصل') }}</h5>
                            <p class="text-muted mb-0">{{ __('تابعنا على منصاتنا') }}</p>
                        </div>
                        <div class="contact-card-body">
                            <div class="social-buttons">
                                @if(isset($settings['social_facebook']) && !empty($settings['social_facebook']))
                                <a href="{{ $settings['social_facebook'] }}" class="social-btn facebook-btn" target="_blank">
                                    <i class="ti ti-brand-facebook"></i>
                                </a>
                                @endif
                                @if(isset($settings['social_twitter']) && !empty($settings['social_twitter']))
                                <a href="{{ $settings['social_twitter'] }}" class="social-btn twitter-btn" target="_blank">
                                    <i class="ti ti-brand-twitter"></i>
                                </a>
                                @endif
                                @if(isset($settings['social_linkedin']) && !empty($settings['social_linkedin']))
                                <a href="{{ $settings['social_linkedin'] }}" class="social-btn linkedin-btn" target="_blank">
                                    <i class="ti ti-brand-linkedin"></i>
                                </a>
                                @endif
                                @if(isset($settings['social_whatsapp']) && !empty($settings['social_whatsapp']))
                                <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $settings['social_whatsapp']) }}" class="social-btn whatsapp-btn" target="_blank">
                                    <i class="ti ti-brand-whatsapp"></i>
                                </a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                @if(isset($settings['contact_address']) && !empty($settings['contact_address']))
                <!-- Address -->
                <div class="col-12">
                    <div class="contact-info-card address-card">
                        <div class="contact-card-header">
                            <div class="contact-card-icon">
                                <i class="ti ti-map-pin"></i>
                            </div>
                            <h5 class="mb-1">{{ __('العنوان') }}</h5>
                            <p class="text-muted mb-0">{{ __('موقعنا') }}</p>
                        </div>
                        <div class="contact-card-body">
                            <p class="mb-0">{{ $settings['contact_address'] }}</p>
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>

        <!-- Contact Form -->
        <div class="col-lg-8">
            <div class="contact-form-card">
                <div class="form-card-header">
                    <div class="form-card-icon">
                        <i class="ti ti-message-circle"></i>
                    </div>
                    <h4 class="mb-2">{{ __('أرسل لنا رسالة') }}</h4>
                    <p class="text-muted mb-0">{{ __('نحن نتطلع إلى سماع منك والرد عليك في أقرب وقت') }}</p>
                </div>
                <div class="form-card-body">
                    @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="ti ti-check-circle me-2"></i>
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                    @endif
                    <form id="contactForm" method="POST" action="{{ route('contact.submit') }}">
                        @csrf
                        <div class="row g-4">
                            <!-- Name -->
                            <div class="col-md-6">
                                <label class="form-label" for="name">{{ __('الاسم الكامل') }}</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="ti ti-user"></i></span>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" required>
                                    @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Email -->
                            <div class="col-md-6">
                                <label class="form-label" for="email">{{ __('البريد الإلكتروني') }}</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="ti ti-mail"></i></span>
                                    <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}" required>
                                    @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Phone -->
                            <div class="col-md-6">
                                <label class="form-label" for="phone">{{ __('رقم الهاتف') }}</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="ti ti-phone"></i></span>
                                    <input type="tel" class="form-control @error('phone') is-invalid @enderror" id="phone" name="phone" value="{{ old('phone') }}">
                                    @error('phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Subject -->
                            <div class="col-md-6">
                                <label class="form-label" for="subject">{{ __('الموضوع') }}</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="ti ti-article"></i></span>
                                    <input type="text" class="form-control @error('subject') is-invalid @enderror" id="subject" name="subject" value="{{ old('subject') }}" required>
                                    @error('subject')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Message -->
                            <div class="col-12">
                                <label class="form-label" for="message">{{ __('الرسالة') }}</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="ti ti-message"></i></span>
                                    <textarea class="form-control @error('message') is-invalid @enderror" id="message" name="message" rows="4" required>{{ old('message') }}</textarea>
                                    @error('message')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Submit Button -->
                            <div class="col-12 text-end">
                                <button type="submit" class="btn btn-primary">
                                    <i class="ti ti-send me-2"></i>
                                    {{ __('إرسال الرسالة') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @if(isset($settings['contact_address']) && !empty($settings['contact_address']))
    <!-- Map Section -->
    <div class="row mt-5">
        <div class="col-12">
            <div class="map-card">
                <div class="map-card-header">
                    <div class="map-card-icon">
                        <i class="ti ti-map-pin"></i>
                    </div>
                    <h4 class="mb-1">{{ __('موقعنا') }}</h4>
                    <p class="text-muted mb-0">{{ __('زرنا في مقرنا الرئيسي') }}</p>
                </div>
                <div class="map-container">
                    <div class="ratio ratio-21x9">
                        <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d108513.06859756269!2d35.8747931!3d31.9515694!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x151ca7e4aee722d5%3A0x8693a9183825010b!2z2LnZhdmR2KfZhtiMINin2YTYo9ix2K_Zhg!5e0!3m2!1sar!2s!4v1660000000000!5m2!1sar!2s" style="border:0; border-radius: 12px;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
@endsection

<style>
/* Contact Hero Section */
.contact-hero {
    background: linear-gradient(135deg, #1f36ad 0%, #286aad 50%, #3b82f6 100%);
    padding: 100px 0;
    position: relative;
    overflow: hidden;
}

.contact-hero::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grain" width="100" height="100" patternUnits="userSpaceOnUse"><circle cx="25" cy="25" r="1" fill="%23ffffff" opacity="0.1"/><circle cx="75" cy="75" r="1" fill="%23ffffff" opacity="0.1"/><circle cx="50" cy="10" r="1" fill="%23ffffff" opacity="0.1"/><circle cx="10" cy="50" r="1" fill="%23ffffff" opacity="0.1"/><circle cx="90" cy="30" r="1" fill="%23ffffff" opacity="0.1"/></pattern></defs><rect width="100" height="100" fill="url(%23grain)"/></svg>') repeat;
    animation: grain-move 20s linear infinite;
    pointer-events: none;
}

@keyframes grain-move {
    0% { transform: translate(0, 0); }
    100% { transform: translate(-100px, -100px); }
}

/* Floating Icons */
.contact-floating-icons {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    pointer-events: none;
    z-index: 1;
}

.contact-floating-icon {
    position: absolute;
    font-size: 2rem;
    opacity: 0.3;
    animation: contactFloat 6s ease-in-out infinite;
}

.contact-floating-icon:nth-child(1) {
    top: 20%;
    left: 10%;
    animation-delay: 0s;
}

.contact-floating-icon:nth-child(2) {
    top: 60%;
    right: 15%;
    animation-delay: 1.5s;
}

.contact-floating-icon:nth-child(3) {
    bottom: 30%;
    left: 20%;
    animation-delay: 3s;
}

.contact-floating-icon:nth-child(4) {
    top: 40%;
    right: 30%;
    animation-delay: 4.5s;
}

@keyframes contactFloat {
    0%, 100% {
        transform: translateY(0px) rotate(0deg);
    }
    50% {
        transform: translateY(-20px) rotate(5deg);
    }
}

/* Hero Content */
.contact-hero-content {
    position: relative;
    z-index: 2;
}

.contact-hero-icon {
    width: 80px;
    height: 80px;
    background: rgba(255, 255, 255, 0.2);
    backdrop-filter: blur(10px);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto;
    font-size: 2rem;
    color: white;
    transition: all 0.3s ease;
}

.contact-hero-icon:hover {
    transform: scale(1.1) rotate(5deg);
    background: rgba(255, 255, 255, 0.3);
}

/* Contact Stats */
.contact-stats {
    margin-top: 2rem;
}

.stat-item {
    background: rgba(255, 255, 255, 0.1);
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.2);
    border-radius: 12px;
    padding: 1.5rem 1rem;
    text-align: center;
    transition: all 0.3s ease;
    position: relative;
    overflow: hidden;
}

.stat-item::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
    transition: left 0.5s ease;
}

.stat-item:hover::before {
    left: 100%;
}

.stat-item:hover {
    transform: translateY(-5px);
    background: rgba(255, 255, 255, 0.15);
    box-shadow: 0 10px 25px rgba(0,0,0,0.2);
}

.stat-icon {
    width: 40px;
    height: 40px;
    background: rgba(255, 255, 255, 0.2);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 0.5rem;
    font-size: 1.25rem;
    color: white;
    transition: all 0.3s ease;
}

.stat-item:hover .stat-icon {
    transform: scale(1.1);
    background: rgba(255, 255, 255, 0.3);
}

.stat-number {
    font-size: 1.5rem;
    font-weight: bold;
    color: white;
    margin-bottom: 0.25rem;
}

.stat-label {
    font-size: 0.875rem;
    color: rgba(255, 255, 255, 0.8);
    margin: 0;
}

/* Contact Info Cards */
.contact-info-card {
    background: white;
    border-radius: 15px;
    box-shadow: 0 5px 15px rgba(0,0,0,0.08);
    transition: all 0.3s ease;
    overflow: hidden;
    position: relative;
    height: 100%;
}

.contact-info-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 4px;
    transition: all 0.3s ease;
}

.email-card::before {
    background: linear-gradient(90deg, #3498db, #2980b9);
}

.phone-card::before {
    background: linear-gradient(90deg, #27ae60, #229954);
}

.social-card::before {
    background: linear-gradient(90deg, #f39c12, #e67e22);
}

.address-card::before {
    background: linear-gradient(90deg, #e74c3c, #c0392b);
}

.contact-info-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 15px 35px rgba(0,0,0,0.15);
}

.contact-card-header {
    padding: 1.5rem 1.5rem 1rem;
    text-align: center;
}

.contact-card-icon {
    width: 60px;
    height: 60px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 1rem;
    font-size: 1.5rem;
    color: white;
    transition: all 0.3s ease;
}

.email-card .contact-card-icon {
    background: linear-gradient(135deg, #3498db, #2980b9);
}

.phone-card .contact-card-icon {
    background: linear-gradient(135deg, #27ae60, #229954);
}

.social-card .contact-card-icon {
    background: linear-gradient(135deg, #f39c12, #e67e22);
}

.address-card .contact-card-icon {
    background: linear-gradient(135deg, #e74c3c, #c0392b);
}

.contact-info-card:hover .contact-card-icon {
    transform: scale(1.1) rotate(5deg);
}

.contact-card-body {
    padding: 0 1.5rem 1.5rem;
    text-align: center;
}

.contact-link {
    color: #333;
    text-decoration: none;
    font-weight: 500;
    transition: all 0.3s ease;
    display: inline-flex;
    align-items: center;
}

.contact-link:hover {
    color: #1f36ad;
    text-decoration: underline;
}

/* Social Buttons */
.social-buttons {
    display: flex;
    gap: 0.75rem;
    justify-content: center;
    flex-wrap: wrap;
}

.social-btn {
    width: 45px;
    height: 45px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    text-decoration: none;
    font-size: 1.25rem;
    transition: all 0.3s ease;
    position: relative;
    overflow: hidden;
}

.social-btn::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(255,255,255,0.2);
    border-radius: 50%;
    transform: scale(0);
    transition: transform 0.3s ease;
}

.social-btn:hover::before {
    transform: scale(1);
}

.social-btn:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 20px rgba(0,0,0,0.3);
}

.facebook-btn {
    background: linear-gradient(135deg, #3b5998, #2d4373);
}

.twitter-btn {
    background: linear-gradient(135deg, #1da1f2, #0d8bd9);
}

.linkedin-btn {
    background: linear-gradient(135deg, #0077b5, #005885);
}

.whatsapp-btn {
    background: linear-gradient(135deg, #25d366, #1ebe57);
}

/* Contact Form Card */
.contact-form-card {
    background: white;
    border-radius: 15px;
    box-shadow: 0 5px 15px rgba(0,0,0,0.08);
    overflow: hidden;
    position: relative;
}

.contact-form-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 4px;
    background: linear-gradient(90deg, #1f36ad, #286aad, #3b82f6);
}

.form-card-header {
    background: linear-gradient(135deg, #f8f9fa, #e9ecef);
    padding: 2rem;
    text-align: center;
    position: relative;
}

.form-card-icon {
    width: 70px;
    height: 70px;
    background: linear-gradient(135deg, #1f36ad, #286aad);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 1rem;
    font-size: 1.75rem;
    color: white;
    transition: all 0.3s ease;
}

.form-card-icon:hover {
    transform: scale(1.1) rotate(5deg);
}

.form-card-body {
    padding: 2rem;
}

/* Form Enhancements */
.form-control {
    border-radius: 10px;
    border: 2px solid #e9ecef;
    padding: 0.75rem 1rem;
    transition: all 0.3s ease;
}

.form-control:focus {
    border-color: #1f36ad;
    box-shadow: 0 0 0 0.2rem rgba(31, 54, 173, 0.25);
}

.input-group-text {
    color: white;
    border: 2px solid #1f36ad;
    border-radius: 10px 0 0 10px;
}

.btn-primary {
    background: linear-gradient(135deg, #1f36ad, #286aad);
    border: none;
    border-radius: 25px;
    padding: 0.75rem 2rem;
    font-weight: 600;
    transition: all 0.3s ease;
}

.btn-primary:hover {
    background: linear-gradient(135deg, #286aad, #1f36ad);
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(31, 54, 173, 0.3);
}

/* Map Card */
.map-card {
    background: white;
    border-radius: 15px;
    box-shadow: 0 5px 15px rgba(0,0,0,0.08);
    overflow: hidden;
    position: relative;
}

.map-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 4px;
    background: linear-gradient(90deg, #27ae60, #229954);
}

.map-card-header {
    padding: 1.5rem;
    text-align: center;
    background: linear-gradient(135deg, #f8f9fa, #e9ecef);
}

.map-card-icon {
    width: 60px;
    height: 60px;
    background: linear-gradient(135deg, #27ae60, #229954);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 1rem;
    font-size: 1.5rem;
    color: white;
    transition: all 0.3s ease;
}

.map-card-icon:hover {
    transform: scale(1.1) rotate(5deg);
}

.map-container {
    padding: 0;
}

/* Responsive Design */
@media (max-width: 768px) {
    .contact-hero {
        padding: 60px 0;
    }

    .contact-floating-icons {
        display: none;
    }

    .stat-item {
        margin-bottom: 1rem;
    }

    .social-buttons {
        gap: 0.5rem;
    }

    .social-btn {
        width: 40px;
        height: 40px;
        font-size: 1rem;
    }

    .form-card-header,
    .form-card-body {
        padding: 1.5rem;
    }
}

@media (max-width: 576px) {
    .contact-hero {
        padding: 40px 0;
    }

    .contact-hero-icon {
        width: 60px;
        height: 60px;
        font-size: 1.5rem;
    }

    .stat-item {
        padding: 1rem;
    }

    .stat-number {
        font-size: 1.25rem;
    }

    .form-card-header,
    .form-card-body {
        padding: 1rem;
    }
}
</style>
