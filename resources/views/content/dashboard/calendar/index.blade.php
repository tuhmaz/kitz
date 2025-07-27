@extends('layouts.contentNavbarLayout')

@section('title', 'إدارة التقويم')

@section('vendor-style')
  @vite([

    'resources/assets/vendor/libs/flatpickr/flatpickr.scss',
    'resources/assets/vendor/libs/sweetalert2/sweetalert2.scss',
    'resources/assets/vendor/libs/select2/select2.scss'
  ])
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/boxicons@2.1.4/css/boxicons.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/animate.css@4.1.1/animate.min.css">
@endsection

@section('page-style')
  @vite([

    'resources/assets/vendor/scss/dashboard-calendar.scss'
  ])
@endsection

@section('vendor-script')
  @vite([
    'resources/assets/vendor/libs/fullcalendar/fullcalendar.js',
    'resources/assets/vendor/libs/flatpickr/flatpickr.js',
    'resources/assets/vendor/libs/moment/moment.js',
    'resources/assets/vendor/libs/sweetalert2/sweetalert2.js',
    'resources/assets/vendor/libs/select2/select2.js'
  ])
@endsection

@section('page-script')
  @vite([
    'resources/assets/js/app-calendar.js',
    
  ])
@endsection

@section('content')
<div class="container-fluid">
  <!-- Page Header -->
  <div class="row mb-4">
    <div class="col-12">
      <div class="card calendar-header animate__animated animate__fadeInDown">
        <div class="card-body text-center">
          <h4 class="mb-2">
            <i class="bx bx-calendar me-2"></i>
            إدارة التقويم الأكاديمي
          </h4>
          <p class="mb-0">إدارة وتنظيم الأحداث والمواعيد الأكاديمية</p>
        </div>
      </div>
    </div>
  </div>

  <!-- Statistics Cards -->
  <div class="row mb-4">
    <div class="col-md-3 col-sm-6 mb-3">
      <div class="card stats-card animate__animated animate__fadeInUp">
        <div class="card-body">
          <div class="d-flex align-items-center">
            <div class="avatar me-3">
              <span class="avatar-initial rounded bg-label-primary">
                <i class="bx bx-calendar-plus"></i>
              </span>
            </div>
            <div>
              <small class="text">الأحداث هذا الشهر</small>
              <h5 class="mb-0" id="monthly-events-count">-</h5>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="col-md-3 col-sm-6 mb-3">
      <div class="card stats-card success animate__animated animate__fadeInUp" style="animation-delay: 0.1s;">
        <div class="card-body">
          <div class="d-flex align-items-center">
            <div class="avatar me-3">
              <span class="avatar-initial rounded bg-label-success">
                <i class="bx bx-calendar-check"></i>
              </span>
            </div>
            <div>
              <small class="text">أحداث اليوم</small>
              <h5 class="mb-0" id="today-events-count">-</h5>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="col-md-3 col-sm-6 mb-3">
      <div class="card stats-card warning animate__animated animate__fadeInUp" style="animation-delay: 0.2s;">
        <div class="card-body">
          <div class="d-flex align-items-center">
            <div class="avatar me-3">
              <span class="avatar-initial rounded bg-label-warning">
                <i class="bx bx-time"></i>
              </span>
            </div>
            <div>
              <small class="text">أحداث قادمة</small>
              <h5 class="mb-0" id="upcoming-events-count">-</h5>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="col-md-3 col-sm-6 mb-3">
      <div class="card stats-card info animate__animated animate__fadeInUp" style="animation-delay: 0.3s;">
        <div class="card-body">
          <div class="d-flex align-items-center">
            <div class="avatar me-3">
              <span class="avatar-initial rounded bg-label-info">
                <i class="bx bx-data"></i>
              </span>
            </div>
            <div>
              <small class="text">قاعدة البيانات</small>
              <h6 class="mb-0" id="current-database-name">{{ $currentDatabase }}</h6>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Main Calendar Section -->
  <div class="row">
    <!-- Calendar Sidebar -->
    <div class="col-xl-3 col-lg-4 col-md-5 mb-4">
      <div class="card calendar-sidebar animate__animated animate__fadeInRight">
        <div class="card-header border-0 pb-0">
          <h5 class="card-title mb-3">
            <i class="bx bx-cog me-2 text-primary"></i>
            إعدادات التقويم
          </h5>
        </div>
        <div class="card-body">
          <!-- Add Event Button -->
          <div class="d-grid mb-4">
            <button type="button" class="btn quick-action-btn w-100 text-start"
                    data-bs-toggle="offcanvas" data-bs-target="#addEventSidebar"
                    data-bs-toggle="tooltip" data-bs-placement="right"
                    title="إضافة حدث جديد إلى التقويم">
              <i class="bx bx-plus me-2"></i>
              إضافة حدث جديد
            </button>

            <button type="button" class="btn quick-action-btn w-100 text-start"
                    data-bs-toggle="tooltip" data-bs-placement="right"
                    title="عرض جميع أحداث اليوم">
              <i class="bx bx-calendar-event me-2"></i>
              عرض الأحداث اليوم
            </button>

            <button type="button" class="btn quick-action-btn w-100 text-start"
                    data-bs-toggle="tooltip" data-bs-placement="right"
                    title="بحث وتصفية الأحداث">
              <i class="bx bx-search me-2"></i>
              بحث في الأحداث
            </button>
          </div>

          <!-- Database Selection -->
          <div class="mb-4">
            <label class="form-label fw-semibold mb-2">
              <i class="bx bx-data me-1 text-info"></i>
              اختيار قاعدة البيانات
            </label>
            <select class="form-select database-selector" id="mainDatabaseSelector">
              @foreach($databases as $db)
                <option value="{{ $db }}" {{ $currentDatabase === $db ? 'selected' : '' }}>
                  @switch($db)
                    @case('jo')
                      🇯🇴 الأردن
                      @break
                    @case('sa')
                      🇸🇦 السعودية
                      @break
                    @case('ae')
                      🇦🇪 الإمارات
                      @break
                    @case('bh')
                      🇧🇭 البحرين
                      @break
                    @case('kw')
                      🇰🇼 الكويت
                      @break
                    @case('om')
                      🇴🇲 عمان
                      @break
                    @case('qa')
                      🇶🇦 قطر
                      @break
                    @case('eg')
                      🇪🇬 مصر
                      @break
                    @case('ps')
                      🇵🇸 فلسطين
                      @break
                    @default
                      {{ $db }}
                  @endswitch
                </option>
              @endforeach
            </select>
          </div>

          <!-- Calendar Filters -->
          <div class="mb-4">
            <h6 class="fw-semibold mb-3">
              <i class="bx bx-filter me-1 text-secondary"></i>
              تصفية الأحداث
            </h6>
            <div class="form-check mb-2">
              <input class="form-check-input" type="checkbox" id="showAllEvents" checked>
              <label class="form-check-label" for="showAllEvents">
                عرض جميع الأحداث
              </label>
            </div>
            <div class="form-check mb-2">
              <input class="form-check-input" type="checkbox" id="showTodayEvents" checked>
              <label class="form-check-label" for="showTodayEvents">
                أحداث اليوم فقط
              </label>
            </div>
            <div class="form-check">
              <input class="form-check-input" type="checkbox" id="showUpcomingEvents" checked>
              <label class="form-check-label" for="showUpcomingEvents">
                الأحداث القادمة
              </label>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Calendar Main Content -->
    <div class="col-xl-9 col-lg-8 col-md-7">
      <div class="card calendar-card animate__animated animate__fadeInLeft">
        <div class="card-body p-0">
          <!-- Calendar Toolbar -->
          <div class="calendar-toolbar">
            <div class="d-flex justify-content-between align-items-center flex-wrap">
              <div class="d-flex align-items-center">
                <button class="btn btn-sm btn-modern me-2" id="prevMonth">
                  <i class="bx bx-chevron-left"></i>
                </button>
                <button class="btn btn-sm btn-modern me-2" id="nextMonth">
                  <i class="bx bx-chevron-right"></i>
                </button>
                <button class="btn btn-sm btn-modern" id="todayBtn">
                  اليوم
                </button>
              </div>
              <div class="d-flex align-items-center">
                <div class="btn-group me-2" role="group">
                  <button type="button" class="btn btn-sm btn-modern" id="monthView">شهر</button>
                  <button type="button" class="btn btn-sm btn-modern" id="weekView">أسبوع</button>
                  <button type="button" class="btn btn-sm btn-modern" id="dayView">يوم</button>
                </div>
                <button class="btn btn-sm btn-modern" data-bs-toggle="offcanvas" data-bs-target="#addEventSidebar">
                  <i class="bx bx-plus me-1"></i>
                  إضافة حدث
                </button>
              </div>
            </div>
          </div>

          <!-- FullCalendar -->
          <div id="calendar" class="p-3"></div>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Modern Event Form Offcanvas -->
<div class="offcanvas offcanvas-end offcanvas-modern" tabindex="-1" id="addEventSidebar"
     aria-labelledby="addEventSidebarLabel" style="width: 420px;">
  <div class="offcanvas-header border-0 pb-0">
    <div class="d-flex align-items-center">
      <div class="avatar me-3">
        <span class="avatar-initial rounded bg-primary">
          <i class="bx bx-calendar-plus text-white"></i>
        </span>
      </div>
      <div>
        <h5 class="offcanvas-title mb-0" id="addEventSidebarLabel">إضافة حدث جديد</h5>
        <small class="text">أضف حدثاً جديداً إلى تقويمك</small>
      </div>
    </div>
    <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
  </div>

  <div class="offcanvas-body pt-0">
    <form class="event-form" id="eventForm" onsubmit="return false">
      <!-- Event Title -->
      <div class="mb-4">
        <label class="form-label fw-semibold mb-2" for="eventTitle">
          <i class="bx bx-text me-1 text-primary"></i>
          عنوان الحدث
          <span class="text-danger">*</span>
        </label>
        <input type="text" class="form-control form-control-modern" id="eventTitle" name="eventTitle"
               placeholder="أدخل عنوان الحدث..." required />
        <div class="form-text">اختر عنواناً واضحاً ومعبراً</div>
      </div>

      <!-- Event Description -->
      <div class="mb-4">
        <label class="form-label fw-semibold mb-2" for="eventDescription">
          <i class="bx bx-detail me-1 text-success"></i>
          وصف الحدث
        </label>
        <textarea class="form-control form-control-modern" name="eventDescription" id="eventDescription"
                  rows="4" placeholder="أضف وصفاً مفصلاً للحدث..."></textarea>
        <div class="form-text">وصف اختياري للحدث</div>
      </div>

      <!-- Event Date -->
      <div class="mb-4">
        <label class="form-label fw-semibold mb-2" for="eventStartDate">
          <i class="bx bx-calendar me-1 text-warning"></i>
          تاريخ الحدث
          <span class="text-danger">*</span>
        </label>
        <input type="date" class="form-control form-control-modern" id="eventStartDate" name="eventStartDate" required />
        <div class="form-text">اختر التاريخ المناسب للحدث</div>
      </div>

      <!-- Database Selection -->
      <div class="mb-4">
        <label class="form-label fw-semibold mb-2" for="eventDatabase">
          <i class="bx bx-data me-1 text-info"></i>
          قاعدة البيانات
          <span class="text-danger">*</span>
        </label>
        <select id="eventDatabase" name="eventDatabase" class="form-select database-selector" required>
          @foreach($databases as $db)
            <option value="{{ $db }}" {{ $currentDatabase === $db ? 'selected' : '' }}>
              @switch($db)
                @case('jo')
                  🇯🇴 الأردن
                  @break
                @case('sa')
                  🇸🇦 السعودية
                  @break
                @case('eg')
                  🇪🇬 مصر
                  @break
                @case('ps')
                  🇵🇸 فلسطين
                  @break
                @default
                  {{ $db }}
              @endswitch
            </option>
          @endforeach
        </select>
        <div class="form-text">اختر قاعدة البيانات المناسبة</div>
      </div>

      <!-- Action Buttons -->
      <div class="d-flex flex-column gap-2 mt-4">
        <button type="submit" class="btn btn-primary btn-modern btn-add-event"
                data-bs-toggle="tooltip" data-bs-placement="top"
                title="حفظ الحدث (Ctrl+S)">
          <i class="bx bx-check me-2"></i>
          إضافة الحدث
        </button>
        <button type="reset" class="btn btn-outline-secondary btn-modern btn-cancel"
                data-bs-dismiss="offcanvas"
                data-bs-toggle="tooltip" data-bs-placement="top"
                title="إلغاء وإغلاق النموذج (Esc)">
          <i class="bx bx-x me-2"></i>
          إلغاء
        </button>
        <button class="btn btn-outline-danger btn-modern btn-delete-event d-none"
                data-bs-toggle="tooltip" data-bs-placement="top"
                title="حذف الحدث نهائياً">
          <i class="bx bx-trash me-2"></i>
          حذف الحدث
        </button>
      </div>

      <!-- Event Preview Card -->
      <div class="card mt-4 d-none" id="eventPreview">
        <div class="card-header bg-light">
          <h6 class="card-title mb-0">
            <i class="bx bx-show me-1"></i>
            معاينة الحدث
          </h6>
        </div>
        <div class="card-body">
          <div class="d-flex align-items-start">
            <div class="avatar me-3">
              <span class="avatar-initial rounded bg-label-primary">
                <i class="bx bx-calendar-event"></i>
              </span>
            </div>
            <div class="flex-grow-1">
              <h6 class="mb-1" id="previewTitle">عنوان الحدث</h6>
              <p class="mb-2 text" id="previewDescription">وصف الحدث</p>
              <div class="d-flex align-items-center text">
                <i class="bx bx-time me-1"></i>
                <small id="previewDate">تاريخ الحدث</small>
              </div>
            </div>
          </div>
        </div>
      </div>
    </form>
  </div>
</div>

<!-- Pass databases data to JavaScript -->
<script>
  window.appCalendar = {
    databases: @json($databases),
    currentDatabase: @json($currentDatabase)
  };
</script>
@endsection
