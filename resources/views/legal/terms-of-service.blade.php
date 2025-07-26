@php
$configData = Helper::appClasses();
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Detection\MobileDetect;

$detect = new MobileDetect;
@endphp

@extends('layouts/layoutFront')

@section('title', __('الشروط والأحكام'))

@section('vendor-style')
@vite(['resources/assets/vendor/scss/home.scss'])
@endsection

@section('content')
<!-- Hero Section -->
<section class="hero-section" style="background: linear-gradient(135deg, #f39c12 0%, #e67e22 50%, #d35400 100%); min-height: 60vh; position: relative; overflow: hidden;">
  <!-- Floating Elements -->
  <div class="floating-elements">
    <div class="floating-icon" style="top: 15%; left: 10%; color: #fff; animation-delay: 0s;">
      <i class="ti ti-file-text"></i>
    </div>
    <div class="floating-icon" style="top: 25%; right: 15%; color: #fff; animation-delay: 1s;">
      <i class="ti ti-shield-check"></i>
    </div>
    <div class="floating-icon" style="bottom: 30%; left: 20%; color: #fff; animation-delay: 2s;">
      <i class="ti ti-scale"></i>
    </div>
    <div class="floating-icon" style="bottom: 20%; right: 25%; color: #fff; animation-delay: 3s;">
      <i class="ti ti-certificate"></i>
    </div>
  </div>

  <div class="container">
    <div class="row align-items-center justify-content-center" style="min-height: 60vh;">
      <div class="col-lg-8 text-center">
        <div class="hero-content" style="background: rgba(255, 255, 255, 0.1); backdrop-filter: blur(20px); border-radius: 30px; padding: 3rem; border: 1px solid rgba(255, 255, 255, 0.2);">
          <div class="hero-icon mb-4">
            <i class="ti ti-file-text" style="font-size: 4rem; color: #fff; text-shadow: 0 4px 20px rgba(0,0,0,0.3);"></i>
          </div>
          <h1 class="display-4 fw-bold text-black mb-3" style="text-shadow: 0 4px 20px rgba(0,0,0,0.3);">
            {{ __('الشروط والأحكام') }}
          </h1>
          <p class="lead text-black mb-0" style="text-shadow: 0 2px 10px rgba(0,0,0,0.3);">
            {{ __('تعرف على الشروط والأحكام الخاصة بموقع علم للتعليم') }}
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
        <a href="{{ route('home') }}" style="color: #f39c12; text-decoration: none; transition: all 0.3s ease;">
          <i class="ti ti-home me-1"></i>{{ __('الرئيسية') }}
        </a>
      </li>
      <li class="breadcrumb-item active" style="color: #6c757d;">{{ __('الشروط والأحكام') }}</li>
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
          <div class="card-header" style="background: linear-gradient(135deg, #f39c12 0%, #e67e22 100%); border: none; padding: 2rem;">
            <div class="row align-items-center">
              <div class="col-md-2 text-center">
                <div style="width: 60px; height: 60px; background: rgba(255,255,255,0.2); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto;">
                  <i class="ti ti-file-text" style="font-size: 1.8rem; color: #fff;"></i>
                </div>
              </div>
              <div class="col-md-8">
                <h2 class="h3 text-white mb-1 fw-bold">{{ __('الشروط والأحكام') }}</h2>
                <p class="text-white mb-0 opacity-90">{{ __('القواعد والأحكام التي تحكم استخدام موقع علم للتعليم') }}</p>
              </div>
              <div class="col-md-2 text-center">
                <span class="badge" style="background: rgba(255,255,255,0.2); color: #fff; padding: 0.5rem 1rem; border-radius: 20px; font-size: 0.9rem;">
                  <i class="ti ti-refresh me-1"></i>{{ __('محدث') }}
                </span>
              </div>
            </div>
          </div>

          <!-- Card Body -->
          <div class="card-body terms-content" style="padding: 3rem; line-height: 1.8; color: #2d3748;">
            <p class="text-muted mb-4">آخر تحديث: {{ date('d F Y') }}</p>

            <div class="alert alert-warning" role="alert" style="border-radius: 15px; border: none; background: linear-gradient(135deg, #fff3cd, #ffeaa7);">
              <i class="ti ti-info-circle me-2"></i>
              <strong>ملاحظة مهمة:</strong> يرجى قراءة هذه الشروط بعناية قبل استخدام موقع <strong>{{ config('settings.site_name', 'علم للتعليم') }}</strong>. باستخدامك لهذا الموقع، فإنك توافق على الالتزام بهذه الشروط.
            </div>
        <div class="hero-content" style="background: rgba(255, 255, 255, 0.1); backdrop-filter: blur(20px); border-radius: 30px; padding: 3rem; border: 1px solid rgba(255, 255, 255, 0.2);">
          <div class="hero-icon mb-4">
            <i class="ti ti-file-text" style="font-size: 4rem; color: #fff; text-shadow: 0 4px 20px rgba(0,0,0,0.3);"></i>
          </div>
          <h1 class="display-4 fw-bold text-black mb-3" style="text-shadow: 0 4px 20px rgba(0,0,0,0.3);">
            {{ __('الشروط والأحكام') }}
          </h1>
          <p class="lead text-black mb-0" style="text-shadow: 0 2px 10px rgba(0,0,0,0.3);">
            {{ __('تعرف على الشروط والأحكام الخاصة بموقع علم للتعليم') }}
          </p>
           <!-- Card Body -->
            <h2 class="h4 text-primary mb-3">
              <i class="ti ti-info-circle me-2"></i>1. مقدمة عن الموقع
            </h2>
            <div class="card mb-4" style="border: none; background: #f8f9fa; border-radius: 15px;">
              <div class="card-body">
                <p class="mb-0">يهدف موقع <strong>{{ config('settings.site_name', 'علم للتعليم') }}</strong> إلى توفير محتوى تعليمي متكامل ومحدث يتماشى مع المنهاج الأردني. يتم تقسيم المحتوى إلى صفوف دراسية، مواد تعليمية، وأقسام مرفقات تهدف لدعم العملية التعليمية.</p>
              </div>
            </div>

            <h2 class="h4 text-primary mb-3">
              <i class="ti ti-book me-2"></i>2. التعريفات الأساسية
            </h2>
            <div class="row mb-4">
              <div class="col-md-6 mb-3">
                <div class="card h-100" style="border: 1px solid #e9ecef; border-radius: 15px;">
                  <div class="card-body">
                    <h5 class="card-title text-warning"><i class="ti ti-world me-2"></i>الموقع</h5>
                    <p class="card-text">يشير إلى موقع {{ config('settings.site_name', 'علم للتعليم') }} وجميع صفحاته وخدماته.</p>
                  </div>
                </div>
              </div>
              <div class="col-md-6 mb-3">
                <div class="card h-100" style="border: 1px solid #e9ecef; border-radius: 15px;">
                  <div class="card-body">
                    <h5 class="card-title text-success"><i class="ti ti-settings me-2"></i>الخدمة</h5>
                    <p class="card-text">تعني جميع المحتويات، المواد التعليمية، والمرفقات المتاحة.</p>
                  </div>
                </div>
              </div>
              <div class="col-md-6 mb-3">
                <div class="card h-100" style="border: 1px solid #e9ecef; border-radius: 15px;">
                  <div class="card-body">
                    <h5 class="card-title text-info"><i class="ti ti-user me-2"></i>المستخدم</h5>
                    <p class="card-text">أي شخص يصل إلى الموقع أو يستخدمه، سواء كان مديرًا، مشرفًا، أو عضوًا.</p>
                  </div>
                </div>
              </div>
              <div class="col-md-6 mb-3">
                <div class="card h-100" style="border: 1px solid #e9ecef; border-radius: 15px;">
                  <div class="card-body">
                    <h5 class="card-title text-purple"><i class="ti ti-id me-2"></i>العضوية</h5>
                    <p class="card-text">تعني الحساب المسجل الذي يمنح المستخدم صلاحيات وميزات محددة.</p>
                  </div>
                </div>
              </div>
            </div>

            <h2 class="h4 text-primary mb-3">
              <i class="ti ti-users me-2"></i>3. الأدوار والصلاحيات
            </h2>
            <div class="row mb-4">
              <div class="col-lg-4 mb-3">
                <div class="card text-center h-100" style="border: 2px solid #dc3545; border-radius: 15px;">
                  <div class="card-body">
                    <div class="mb-3">
                      <i class="ti ti-crown" style="font-size: 3rem; color: #dc3545;"></i>
                    </div>
                    <h5 class="card-title text-danger">المدير</h5>
                    <p class="card-text">يتمتع بكامل الصلاحيات لإدارة المحتوى، المستخدمين، والإعدادات.</p>
                  </div>
                </div>
              </div>
              <div class="col-lg-4 mb-3">
                <div class="card text-center h-100" style="border: 2px solid #ffc107; border-radius: 15px;">
                  <div class="card-body">
                    <div class="mb-3">
                      <i class="ti ti-shield-check" style="font-size: 3rem; color: #ffc107;"></i>
                    </div>
                    <h5 class="card-title text-warning">المشرف</h5>
                    <p class="card-text">يقتصر دوره على إدارة المقالات (إضافة، تعديل، حذف).</p>
                  </div>
                </div>
              </div>
              <div class="col-lg-4 mb-3">
                <div class="card text-center h-100" style="border: 2px solid #28a745; border-radius: 15px;">
                  <div class="card-body">
                    <div class="mb-3">
                      <i class="ti ti-user-check" style="font-size: 3rem; color: #28a745;"></i>
                    </div>
                    <h5 class="card-title text-success">العضو</h5>
                    <p class="card-text">يمكنه التعليق على المقالات وتحميل المرفقات فقط.</p>
                  </div>
                </div>
              </div>
            </div>

            <h2 class="h4 text-primary mb-3">
              <i class="ti ti-checklist me-2"></i>4. استخدام الخدمة
            </h2>
            <div class="alert alert-info" style="border-radius: 15px; border-left: 5px solid #17a2b8;">
              <i class="ti ti-info-circle me-2"></i>
              <strong>ملاحظة مهمة:</strong> باستخدامك للموقع، فإنك توافق على الشروط التالية:
            </div>
            <div class="row mb-4">
              <div class="col-lg-6 mb-3">
                <div class="card h-100" style="border-radius: 15px; border-left: 5px solid #dc3545;">
                  <div class="card-body">
                    <h6 class="card-title text-danger">
                      <i class="ti ti-ban me-2"></i>ممنوعات
                    </h6>
                    <ul class="list-unstyled mb-0">
                      <li class="mb-2">
                        <i class="ti ti-x text-danger me-2"></i>
                        عدم استخدام الموقع لأغراض غير قانونية
                      </li>
                      <li class="mb-2">
                        <i class="ti ti-x text-danger me-2"></i>
                        عدم نشر محتوى مسيء أو مخالف
                      </li>
                      <li class="mb-0">
                        <i class="ti ti-x text-danger me-2"></i>
                        عدم محاولة اختراق الموقع
                      </li>
                    </ul>
                  </div>
                </div>
              </div>
              <div class="col-lg-6 mb-3">
                <div class="card h-100" style="border-radius: 15px; border-left: 5px solid #28a745;">
                  <div class="card-body">
                    <h6 class="card-title text-success">
                      <i class="ti ti-check me-2"></i>مطلوب
                    </h6>
                    <ul class="list-unstyled mb-0">
                      <li class="mb-2">
                        <i class="ti ti-check text-success me-2"></i>
                        احترام حقوق المستخدمين الآخرين
                      </li>
                      <li class="mb-2">
                        <i class="ti ti-check text-success me-2"></i>
                        استخدام الموقع لأغراض تعليمية
                      </li>
                      <li class="mb-0">
                        <i class="ti ti-check text-success me-2"></i>
                        التقيد بالآداب العامة
                      </li>
                    </ul>
                  </div>
                </div>
              </div>
            </div>

            <h2 class="h4 text-primary mb-3">
              <i class="ti ti-copyright me-2"></i>5. الملكية الفكرية
            </h2>
            <div class="card" style="border-radius: 15px; border-left: 5px solid #6f42c1;">
              <div class="card-body">
                <div class="row align-items-center">
                  <div class="col-md-2 text-center mb-3 mb-md-0">
                    <i class="ti ti-shield-lock" style="font-size: 3rem; color: #6f42c1;"></i>
                  </div>
                  <div class="col-md-10">
                    <h6 class="card-title text-purple mb-2">
                      <i class="ti ti-info-circle me-2"></i>حقوق الملكية الفكرية محفوظة
                    </h6>
                    <p class="card-text mb-0">
                      جميع المحتويات المنشورة على الموقع، بما في ذلك النصوص، الصور، والشعارات، هي ملك لموقع
                      <span class="badge bg-primary-subtle text-primary">{{ config('settings.site_name') }}</span>
                      وتحميها قوانين حقوق الملكية الفكرية. يُحظر نسخ أو استخدام أي جزء من الموقع دون إذن كتابي مسبق.
                    </p>
                  </div>
                </div>
              </div>
            </div>

            <h2 class="h4 text-primary mb-3">
              <i class="ti ti-files me-2"></i>6. سياسة المرفقات
            </h2>
            <p class="mb-4">يحتوي الموقع على مجموعة متنوعة من المرفقات التعليمية:</p>
            <div class="row mb-4">
              <div class="col-lg-6 mb-3">
                <div class="card h-100" style="border-radius: 15px; border-left: 5px solid #dc3545;">
                  <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                      <i class="ti ti-file-type-pdf text-danger me-3" style="font-size: 2rem;"></i>
                      <h6 class="card-title mb-0 text-danger">PDF Files</h6>
                    </div>
                    <p class="card-text mb-0">ملفات PDF للمقالات والكتب الدراسية والمراجع التعليمية.</p>
                  </div>
                </div>
              </div>
              <div class="col-lg-6 mb-3">
                <div class="card h-100" style="border-radius: 15px; border-left: 5px solid #007bff;">
                  <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                      <i class="ti ti-file-type-doc text-primary me-3" style="font-size: 2rem;"></i>
                      <h6 class="card-title mb-0 text-primary">Word Documents</h6>
                    </div>
                    <p class="card-text mb-0">ملفات Word للورق التعليمية والامتحانات والواجبات.</p>
                  </div>
                </div>
              </div>
              <div class="col-lg-6 mb-3">
                <div class="card h-100" style="border-radius: 15px; border-left: 5px solid #28a745;">
                  <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                      <i class="ti ti-file-type-xls text-success me-3" style="font-size: 2rem;"></i>
                      <h6 class="card-title mb-0 text-success">Excel Sheets</h6>
                    </div>
                    <p class="card-text mb-0">ملفات Excel للجداول والإحصائيات والدرجات.</p>
                  </div>
                </div>
              </div>
              <div class="col-lg-6 mb-3">
                <div class="card h-100" style="border-radius: 15px; border-left: 5px solid #fd7e14;">
                  <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                      <i class="ti ti-file-type-ppt text-warning me-3" style="font-size: 2rem;"></i>
                      <h6 class="card-title mb-0 text-warning">PowerPoint</h6>
                    </div>
                    <p class="card-text mb-0">ملفات PowerPoint للعروض التقديمية والدروس.</p>
                  </div>
                </div>
              </div>
            </div>
            <div class="alert alert-warning" style="border-radius: 15px; border-left: 5px solid #ffc107;">
              <i class="ti ti-alert-triangle me-2"></i>
              <strong>ملاحظة مهمة:</strong> جميع هذه المرفقات متاحة للتحميل والاستخدام الشخصي والتعليمي فقط.
            </div>

            <h2 class="h4 text-primary mb-3">
              <i class="ti ti-shield-off me-2"></i>7. حدود المسؤولية
            </h2>
            <div class="alert alert-danger" style="border-radius: 15px; border-left: 5px solid #dc3545;">
              <div class="d-flex align-items-center mb-3">
                <i class="ti ti-alert-circle text-danger me-3" style="font-size: 2rem;"></i>
                <h6 class="mb-0 text-danger">إخلاء مسؤولية</h6>
              </div>
              <p class="mb-3">لا يتحمل الموقع أي مسؤولية عن الحالات التالية:</p>
              <div class="row">
                <div class="col-md-6 mb-2">
                  <div class="d-flex align-items-center">
                    <i class="ti ti-x text-danger me-2"></i>
                    <span>أي أضرار مباشرة أو غير مباشرة</span>
                  </div>
                </div>
                <div class="col-md-6 mb-2">
                  <div class="d-flex align-items-center">
                    <i class="ti ti-x text-danger me-2"></i>
                    <span>فقدان البيانات أو انقطاع الخدمة</span>
                  </div>
                </div>
                <div class="col-md-6 mb-2">
                  <div class="d-flex align-items-center">
                    <i class="ti ti-x text-danger me-2"></i>
                    <span>محتوى المواقع الخارجية المرتبطة</span>
                  </div>
                </div>
                <div class="col-md-6 mb-2">
                  <div class="d-flex align-items-center">
                    <i class="ti ti-x text-danger me-2"></i>
                    <span>أي أخطاء في المحتوى أو المعلومات</span>
                  </div>
                </div>
              </div>
            </div>

            <h2 class="h4 text-primary mb-3">
              <i class="ti ti-edit me-2"></i>8. التعديلات
            </h2>
            <div class="card" style="border-radius: 15px; border-left: 5px solid #17a2b8;">
              <div class="card-body">
                <div class="d-flex align-items-center mb-3">
                  <i class="ti ti-refresh text-info me-3" style="font-size: 2rem;"></i>
                  <h6 class="card-title mb-0 text-info">حق التعديل</h6>
                </div>
                <p class="card-text mb-0">
                  يحتفظ موقع
                  <span class="badge bg-primary-subtle text-primary">{{ config('settings.site_name') }}</span>
                  بالحق في تعديل هذه الشروط والأحكام في أي وقت. سيتم إشعار المستخدمين بأي تغييرات من خلال تحديث هذه الصفحة. استمرارك في استخدام الموقع يعني قبولك للشروط المعدلة.
                </p>
              </div>
            </div>

            <h2 class="h4 text-primary mb-3 mt-4">
              <i class="ti ti-mail me-2"></i>9. الاتصال
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

            <h2 class="h4 text-primary mb-3 mt-4">
              <i class="ti ti-gavel me-2"></i>10. القانون الحاكم
            </h2>
            <div class="card" style="border-radius: 15px; border-left: 5px solid #6c757d;">
              <div class="card-body">
                <div class="d-flex align-items-center mb-3">
                  <i class="ti ti-scale text-secondary me-3" style="font-size: 2rem;"></i>
                  <h6 class="card-title mb-0 text-secondary">القوانين المعمول بها</h6>
                </div>
                <p class="card-text mb-0">
                  تُفسر هذه الشروط والأحكام وفقًا لقوانين
                  <span class="badge bg-secondary-subtle text-secondary">المملكة الأردنية الهاشمية</span>
                  وتخضع لاختصاص محاكمها.
                </p>
              </div>
            </div>

            <div class="alert alert-success mt-4" style="border-radius: 15px; border-left: 5px solid #28a745;">
              <div class="d-flex align-items-center">
                <i class="ti ti-check-circle text-success me-3" style="font-size: 2rem;"></i>
                <div>
                  <h6 class="alert-heading mb-2 text-success">شكراً لقراءتك للشروط والأحكام</h6>
                  <p class="mb-0">
                    باستخدامك لموقع
                    <span class="badge bg-primary-subtle text-primary">{{ config('settings.site_name') }}</span>
                    فإنك توافق على جميع الشروط والأحكام المذكورة أعلاه.
                  </p>
                </div>
              </div>
            </div>
          </div>

        </div>
 <!-- Card Footer -->
          <div class="card-footer" style="background: #fff8e1; border: none; padding: 2rem; text-align: center;">
            <div class="row align-items-center">
              <div class="col-md-6">
                <p class="mb-0 text-muted">
                  <i class="ti ti-info-circle me-1"></i>
                  آخر تحديث: {{ date('Y-m-d') }}
                </p>
              </div>
              <div class="col-md-6 text-md-end">
                <a href="{{ route('home') }}" class="btn btn-warning" style="border-radius: 15px; padding: 0.75rem 2rem;">
                  <i class="ti ti-arrow-right me-1"></i>
                  العودة للرئيسية
                </a>
              </div>
            </div>
          </div>
      </div>

    </div>
  </div>
</section>

<style>
/* Terms Content Styling */
.terms-content {
  font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
}

.terms-content h1, .terms-content h2, .terms-content h3, .terms-content h4, .terms-content h5, .terms-content h6 {
  color: #f39c12;
  margin-top: 2rem;
  margin-bottom: 1rem;
  font-weight: 600;
}

.terms-content h1 {
  font-size: 2rem;
  border-bottom: 3px solid #f39c12;
  padding-bottom: 0.5rem;
}

.terms-content h2 {
  font-size: 1.5rem;
  color: #2d3748;
}

.terms-content p {
  margin-bottom: 1.2rem;
  text-align: justify;
}

.terms-content ul, .terms-content ol {
  margin-bottom: 1.5rem;
  padding-left: 2rem;
}

.terms-content li {
  margin-bottom: 0.5rem;
}

.terms-content blockquote {
  border-left: 4px solid #f39c12;
  background: rgba(243, 156, 18, 0.05);
  padding: 1rem 1.5rem;
  margin: 1.5rem 0;
  border-radius: 0 10px 10px 0;
}

.terms-content strong {
  color: #f39c12;
  font-weight: 600;
}

.terms-content a {
  color: #f39c12;
  text-decoration: none;
  border-bottom: 1px solid transparent;
  transition: all 0.3s ease;
}

.terms-content a:hover {
  border-bottom-color: #f39c12;
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

  .terms-content {
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
