@extends('layouts.contentNavbarLayout')

@section('title', 'مراقبة الزوار والمستخدمين النشطين')

@section('vendor-style')
@vite([
  'resources/assets/vendor/libs/datatables-bs5/datatables.bootstrap5.scss',
  'resources/assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.scss',
  'resources/assets/vendor/libs/sweetalert2/sweetalert2.scss',
  'resources/assets/vendor/scss/monitoring.scss'
])
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/animate.css@4.1.1/animate.min.css">
<style>
.user-status-online {
  position: relative;
}
.user-status-online::after {
  content: '';
  position: absolute;
  top: -2px;
  right: -2px;
  width: 12px;
  height: 12px;
  background: #28a745;
  border: 2px solid white;
  border-radius: 50%;
  animation: pulse 2s infinite;
}
@keyframes pulse {
  0% { transform: scale(1); opacity: 1; }
  50% { transform: scale(1.1); opacity: 0.7; }
  100% { transform: scale(1); opacity: 1; }
}
.visitor-card {
  transition: all 0.3s ease;
  border-left: 4px solid transparent;
}
.visitor-card.registered-user {
  border-left-color: #007bff;
  background: linear-gradient(135deg, #f8f9ff 0%, #ffffff 100%);
}
.visitor-card.guest-visitor {
  border-left-color: #6c757d;
  background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%);
}
.visitor-card:hover {
  transform: translateY(-2px);
  box-shadow: 0 4px 15px rgba(0,0,0,0.1);
}
.activity-indicator {
  display: inline-block;
  width: 8px;
  height: 8px;
  border-radius: 50%;
  margin-right: 8px;
}
.activity-active { background: #28a745; }
.activity-idle { background: #ffc107; }
.activity-offline { background: #dc3545; }
</style>
@endsection

@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">

<div class="row">
  <div class="col-12">
    <!-- Header Card -->
    <div class="card mb-4 animate__animated animate__fadeInDown">
      <div class="card-header d-flex justify-content-between align-items-center">
        <div>
          <h4 class="mb-1"><i class="ti ti-activity me-2 text-primary"></i>مراقبة النشاط المباشر</h4>
          <p class="text-muted mb-0">تتبع المستخدمين المسجلين والزوار النشطين في الوقت الفعلي</p>
        </div>
        <div class="d-flex gap-2 monitoring-controls">
          <button id="refresh-btn" class="btn btn-primary btn-sm">
            <i class="ti ti-refresh me-1"></i> تحديث
          </button>
          <button id="export-btn" class="btn btn-success btn-sm">
            <i class="ti ti-download me-1"></i> تصدير
          </button>
        </div>
      </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4 monitoring-stats">
      <!-- إجمالي النشطين -->
      <div class="col-lg-3 col-md-6 mb-3">
        <div class="card h-100 animate__animated animate__fadeInUp">
          <div class="card-body">
            <div class="d-flex justify-content-between align-items-start">
              <div class="card-info">
                <p class="card-text text-muted mb-1">إجمالي النشطين</p>
                <div class="d-flex align-items-center mb-2">
                  <h3 class="card-title mb-0 me-2" id="total-active">0</h3>
                  <span class="badge bg-label-success" id="total-trend">+0%</span>
                </div>
                <small class="text-muted">آخر تحديث: <span id="last-update-time">{{ now()->format('H:i:s') }}</span></small>
              </div>
              <div class="card-icon">
                <div class="avatar avatar-md bg-label-primary">
                  <span class="avatar-initial rounded"><i class="ti ti-users ti-md"></i></span>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- المستخدمين المسجلين -->
      <div class="col-lg-3 col-md-6 mb-3">
        <div class="card h-100 animate__animated animate__fadeInUp" style="animation-delay: 0.1s">
          <div class="card-body">
            <div class="d-flex justify-content-between align-items-start">
              <div class="card-info">
                <p class="card-text text-muted mb-1">مستخدمين مسجلين</p>
                <div class="d-flex align-items-center mb-2">
                  <h3 class="card-title mb-0 me-2" id="registered-users">0</h3>
                  <span class="badge bg-label-info" id="users-trend">+0%</span>
                </div>
                <small class="text-success"><i class="ti ti-user-check me-1"></i>نشطين الآن</small>
              </div>
              <div class="card-icon">
                <div class="avatar avatar-md bg-label-info">
                  <span class="avatar-initial rounded"><i class="ti ti-user-check ti-md"></i></span>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- الزوار -->
      <div class="col-lg-3 col-md-6 mb-3">
        <div class="card h-100 animate__animated animate__fadeInUp" style="animation-delay: 0.2s">
          <div class="card-body">
            <div class="d-flex justify-content-between align-items-start">
              <div class="card-info">
                <p class="card-text text-muted mb-1">زوار غير مسجلين</p>
                <div class="d-flex align-items-center mb-2">
                  <h3 class="card-title mb-0 me-2" id="guest-visitors">0</h3>
                  <span class="badge bg-label-warning" id="guests-trend">+0%</span>
                </div>
                <small class="text-warning"><i class="ti ti-user-question me-1"></i>زوار مجهولين</small>
              </div>
              <div class="card-icon">
                <div class="avatar avatar-md bg-label-warning">
                  <span class="avatar-initial rounded"><i class="ti ti-user-question ti-md"></i></span>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- متوسط وقت التصفح -->
      <div class="col-lg-3 col-md-6 mb-3">
        <div class="card h-100 animate__animated animate__fadeInUp" style="animation-delay: 0.3s">
          <div class="card-body">
            <div class="d-flex justify-content-between align-items-start">
              <div class="card-info">
                <p class="card-text text-muted mb-1">متوسط وقت التصفح</p>
                <div class="d-flex align-items-center mb-2">
                  <h3 class="card-title mb-0 me-2" id="avg-session-time">0:00</h3>
                  <span class="badge bg-label-secondary" id="time-trend">دقيقة</span>
                </div>
                <small class="text-secondary"><i class="ti ti-clock me-1"></i>لكل جلسة</small>
              </div>
              <div class="card-icon">
                <div class="avatar avatar-md bg-label-secondary">
                  <span class="avatar-initial rounded"><i class="ti ti-clock ti-md"></i></span>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- قائمة المستخدمين والزوار النشطين -->
    <div class="card animate__animated animate__fadeInUp" style="animation-delay: 0.4s">
      <div class="card-header d-flex justify-content-between align-items-center">
        <div>
          <h5 class="mb-1"><i class="ti ti-users me-2"></i>المستخدمين والزوار النشطين</h5>
          <p class="text-muted mb-0">قائمة بجميع المتصفحين النشطين حالياً</p>
        </div>
        <div class="d-flex gap-2">
          <div class="btn-group view-toggle" role="group">
            <div class="form-check">
              <input type="radio" class="form-check-input" name="view-type" id="view-cards" checked>
              <label class="form-check-label" for="view-cards">
                <i class="ti ti-layout-grid me-1"></i>بطاقات
              </label>
            </div>
            <div class="form-check">
              <input type="radio" class="form-check-input" name="view-type" id="view-table">
              <label class="form-check-label" for="view-table">
                <i class="ti ti-table me-1"></i>جدول
              </label>
            </div>
          </div>
        </div>
      </div>
      <div class="card-body">
        @php
          // استخدام البيانات الممررة من الكنترولر
          // $activeUsers و $visitors ممررين من الكنترولر
          $activeUsers = $activeUsers ?? collect();
          $visitors = $visitors ?? [];
          
          // تحويل activeUsers إلى مصفوفة إذا كان collection
          if ($activeUsers instanceof \Illuminate\Support\Collection) {
            $activeUsers = $activeUsers->toArray();
          }

          // دمج المستخدمين والزوار
          $allActive = [];

          // إضافة المستخدمين المسجلين
          foreach($activeUsers as $user) {
            $allActive[] = [
              'type' => 'user',
              'id' => $user['user_id'] ?? 'unknown',
              'name' => $user['user_name'] ?? 'مستخدم غير معروف',
              'avatar' => null,
              'status' => $user['status'] ?? 'online',
              'url' => $user['url'] ?? 'الصفحة الرئيسية',
              'ip' => $user['ip_address'] ?? '127.0.0.1',
              'location' => ($user['city'] ?? 'عمان') . ', ' . ($user['country'] ?? 'الأردن'),
              'browser' => $user['browser'] ?? 'غير محدد',
              'os' => $user['os'] ?? 'غير محدد',
              'last_activity' => $user['last_activity'] ?? now(),
              'user_agent' => $user['user_agent'] ?? ''
            ];
          }

          // إضافة الزوار غير المسجلين
          foreach($visitors as $visitor) {
            // تجنب تكرار المستخدمين المسجلين
            $isRegisteredUser = false;
            foreach($activeUsers as $user) {
              if(($user->ip_address ?? '') === ($visitor['ip'] ?? '')) {
                $isRegisteredUser = true;
                break;
              }
            }

            if(!$isRegisteredUser) {
              $geoData = $visitor['geo_data'] ?? ['country_name' => 'الأردن', 'city' => 'عمان'];
              $allActive[] = [
                'type' => 'visitor',
                'id' => $visitor['id'] ?? 'unknown',
                'name' => 'زائر مجهول',
                'avatar' => null,
                'status' => 'guest',
                'url' => $visitor['url'] ?? 'الصفحة الرئيسية',
                'ip' => $visitor['ip'] ?? '127.0.0.1',
                'location' => ($geoData['city'] ?? 'عمان') . ', ' . ($geoData['country_name'] ?? 'الأردن'),
                'browser' => '',
                'os' => '',
                'last_activity' => $visitor['last_activity'] ?? now(),
                'user_agent' => $visitor['user_agent'] ?? ''
              ];
            }
          }

          // ترتيب حسب آخر نشاط
          usort($allActive, function($a, $b) {
            return strtotime($b['last_activity']) - strtotime($a['last_activity']);
          });
        @endphp

        <!-- عرض البطاقات -->
        <div id="visitors-cards-view">
          <div id="visitors-cards-container">
            @if(count($allActive) > 0)
              <div class="row">
              @foreach($allActive as $index => $active)
                <div class="col-lg-6 col-xl-4 mb-4">
                  <div class="card visitor-card {{ $active['type'] === 'user' ? 'registered-user' : 'guest-visitor' }} h-100">
                    <div class="card-body">
                      <!-- Header -->
                      <div class="d-flex align-items-center mb-3">
                        <div class="avatar avatar-md me-3 {{ $active['type'] === 'user' ? 'user-status-online' : '' }}">
                          @if($active['type'] === 'user')
                            <span class="avatar-initial rounded-circle bg-label-primary">
                              <i class="ti ti-user"></i>
                            </span>
                          @else
                            <span class="avatar-initial rounded-circle bg-label-secondary">
                              <i class="ti ti-user-question"></i>
                            </span>
                          @endif
                        </div>
                        <div class="flex-grow-1">
                          <h6 class="mb-1">{{ $active['name'] }}</h6>
                          <div class="d-flex align-items-center">
                            <span class="activity-indicator activity-{{ $active['status'] === 'online' ? 'active' : ($active['status'] === 'guest' ? 'idle' : 'offline') }}"></span>
                            <small class="text-muted">
                              @if($active['type'] === 'user')
                                <i class="ti ti-crown me-1 text-primary"></i>مستخدم مسجل
                              @else
                                <i class="ti ti-user-question me-1 text-secondary"></i>زائر
                              @endif
                            </small>
                          </div>
                        </div>
                        <div class="dropdown">
                          <button class="btn btn-sm btn-outline-secondary" type="button" data-bs-toggle="dropdown">
                            <i class="ti ti-dots-vertical"></i>
                          </button>
                          <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="#"><i class="ti ti-eye me-2"></i>عرض التفاصيل</a></li>
                            @if($active['type'] === 'user')
                              <li><a class="dropdown-item" href="#"><i class="ti ti-message me-2"></i>إرسال رسالة</a></li>
                            @endif
                            <li><a class="dropdown-item text-danger" href="#"><i class="ti ti-ban me-2"></i>حظر IP</a></li>
                          </ul>
                        </div>
                      </div>

                      <!-- معلومات النشاط -->
                      <div class="mb-3">
                        <div class="d-flex align-items-center mb-2">
                          <i class="ti ti-world me-2 text-muted"></i>
                          <span class="text-truncate" style="max-width: 200px;" title="{{ $active['url'] }}">
                            {{ $active['url'] }}
                          </span>
                        </div>
                        <div class="d-flex align-items-center mb-2">
                          <i class="ti ti-map-pin me-2 text-muted"></i>
                          <span>{{ $active['location'] }}</span>
                        </div>
                        <div class="d-flex align-items-center">
                          <i class="ti ti-device-desktop me-2 text-muted"></i>
                          <span>{{ $active['ip'] }}</span>
                        </div>
                      </div>

                      <!-- معلومات المتصفح والنظام -->
                      @if($active['type'] === 'user' && ($active['browser'] || $active['os']))
                        <div class="mb-3">
                          @if($active['browser'])
                            <span class="badge bg-label-info me-1 mb-1">
                              <i class="ti ti-browser me-1"></i>{{ $active['browser'] }}
                            </span>
                          @endif
                          @if($active['os'])
                            <span class="badge bg-label-secondary me-1 mb-1">
                              <i class="ti ti-device-desktop me-1"></i>{{ $active['os'] }}
                            </span>
                          @endif
                        </div>
                      @endif

                      <!-- آخر نشاط -->
                      <div class="d-flex justify-content-between align-items-center">
                        <small class="text-muted">
                          <i class="ti ti-clock me-1"></i>
                          آخر نشاط:
                          @php
                            $lastActivity = \Carbon\Carbon::parse($active['last_activity']);
                            $diffMinutes = $lastActivity->diffInMinutes(now());
                          @endphp
                          @if($diffMinutes < 1)
                            الآن
                          @elseif($diffMinutes < 60)
                            منذ {{ $diffMinutes }} دقيقة
                          @else
                            {{ $lastActivity->format('H:i') }}
                          @endif
                        </small>
                        @if($active['type'] === 'user')
                          <span class="badge bg-success">متصل</span>
                        @else
                          <span class="badge bg-warning">زائر</span>
                        @endif
                      </div>
                    </div>
                  </div>
                </div>
              @endforeach
            </div>
          @else
            <div class="text-center py-5">
              <div class="avatar avatar-xl mx-auto mb-3">
                <span class="avatar-initial rounded-circle bg-label-secondary">
                  <i class="ti ti-users ti-lg"></i>
                </span>
              </div>
              <h5 class="mb-2">لا يوجد مستخدمين أو زوار نشطين</h5>
              <p class="text-muted mb-0">لا يوجد أي نشاط حالياً على الموقع</p>
            </div>
          @endif
          </div>
        </div>

        <!-- عرض الجدول -->
        <div id="visitors-table-view" class="d-none">
          <div class="table-responsive">
            <table id="visitors-table" class="table table-hover">
              <thead>
                <tr>
                  <th>#</th>
                  <th>المستخدم/الزائر</th>
                  <th>النوع</th>
                  <th>الصفحة</th>
                  <th>الموقع</th>
                  <th>آخر نشاط</th>
                  <th>الحالة</th>
                  <th>الإجراءات</th>
                </tr>
              </thead>
              <tbody>
                @if(count($allActive) > 0)
                  @foreach($allActive as $index => $active)
                  <tr>
                    <td class="text-center fw-bold">{{ $index + 1 }}</td>
                    <td>
                      <div class="d-flex align-items-center">
                        <div class="avatar avatar-sm me-2 {{ $active['type'] === 'user' ? 'bg-label-primary' : 'bg-label-secondary' }}">
                          <span class="avatar-initial rounded-circle">
                            @if($active['type'] === 'user')
                              <i class="ti ti-user"></i>
                            @else
                              <i class="ti ti-user-question"></i>
                            @endif
                          </span>
                        </div>
                        <div>
                          <h6 class="mb-0">{{ $active['name'] }}</h6>
                          <small class="text-muted">ID: {{ $active['id'] }}</small>
                        </div>
                      </div>
                    </td>
                    <td>
                      @if($active['type'] === 'user')
                        <span class="badge bg-primary">
                          <i class="ti ti-crown me-1"></i>مستخدم مسجل
                        </span>
                      @else
                        <span class="badge bg-secondary">
                          <i class="ti ti-user-question me-1"></i>زائر
                        </span>
                      @endif
                    </td>
                    <td>
                      <span class="text-truncate d-inline-block" style="max-width: 200px;" title="{{ $active['url'] }}">
                        {{ $active['url'] }}
                      </span>
                    </td>
                    <td>
                      <div class="d-flex align-items-center">
                        <i class="ti ti-map-pin me-2 text-muted"></i>
                        <span>{{ $active['location'] }}</span>
                      </div>
                      <small class="text-muted">{{ $active['ip'] }}</small>
                    </td>
                    <td>
                      @php
                        $lastActivity = \Carbon\Carbon::parse($active['last_activity']);
                        $diffMinutes = $lastActivity->diffInMinutes(now());
                      @endphp
                      <div class="d-flex flex-column">
                        @if($diffMinutes < 1)
                          <span class="badge bg-success">الآن</span>
                        @elseif($diffMinutes < 5)
                          <span class="badge bg-warning">منذ {{ $diffMinutes }} دقيقة</span>
                        @else
                          <span class="badge bg-secondary">منذ {{ $diffMinutes }} دقيقة</span>
                        @endif
                        <small class="text-muted">{{ $lastActivity->format('H:i:s') }}</small>
                      </div>
                    </td>
                    <td>
                      <span class="activity-indicator activity-{{ $active['status'] === 'online' ? 'active' : ($active['status'] === 'guest' ? 'idle' : 'offline') }}"></span>
                      @if($active['type'] === 'user')
                        <span class="badge bg-success">متصل</span>
                      @else
                        <span class="badge bg-warning">زائر</span>
                      @endif
                    </td>
                    <td>
                      <div class="dropdown">
                        <button class="btn btn-sm btn-outline-secondary" type="button" data-bs-toggle="dropdown">
                          <i class="ti ti-dots-vertical"></i>
                        </button>
                        <ul class="dropdown-menu">
                          <li><a class="dropdown-item" href="#"><i class="ti ti-eye me-2"></i>عرض التفاصيل</a></li>
                          @if($active['type'] === 'user')
                            <li><a class="dropdown-item" href="#"><i class="ti ti-message me-2"></i>إرسال رسالة</a></li>
                          @endif
                          <li><hr class="dropdown-divider"></li>
                          <li><a class="dropdown-item text-danger" href="#"><i class="ti ti-ban me-2"></i>حظر IP</a></li>
                        </ul>
                      </div>
                    </td>
                  </tr>
                  @endforeach
                @else
                <tr>
                  <td colspan="8" class="text-center py-5">
                    <i class="ti ti-mood-empty fs-1 text-muted d-block mb-3"></i>
                    <p class="mb-0 text-muted">لا يوجد زوار نشطين حاليًا</p>
                  </td>
                </tr>
              @endif
            </tbody>
          </table>
        </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection

@section('page-script')
{{-- تم نقل الجافا سكريبت إلى ملف منفصل --}}
<script>
  // تعيين متغيرات عامة للاستخدام في ملف الجافا سكريبت
  window.activeVisitorsDataUrl = "{{ route('dashboard.monitoring.active-visitors.data', [], false) }}";
  window.activeUsersDataUrl = null; // إذا كان متاحاً
</script>

{{-- استدعاء ملف الجافا سكريبت المنفصل --}}
@vite(['resources/assets/vendor/js/monitoring/active-visitors.js'])


  constructor() {
    this.refreshInterval = null;
    this.currentView = 'cards';
    this.lastUpdateTime = null;
    this.statistics = {
      totalActive: 0,
      registeredUsers: 0,
      guestVisitors: 0,
      avgSessionTime: 0
    };

    this.init();
  }

  init() {
    console.log('🚀 تم تهيئة نظام مراقبة الزوار النشطين');
    this.bindEvents();
    this.loadInitialData();
    this.startAutoRefresh();
  }

  bindEvents() {
    // أزرار التحكم
    document.getElementById('refresh-btn')?.addEventListener('click', () => this.refreshData());
    document.getElementById('export-btn')?.addEventListener('click', () => this.exportData());

    // تبديل العرض
    document.querySelectorAll('input[name="view-type"]').forEach(radio => {
      radio.addEventListener('change', (e) => this.switchView(e.target.id));
    });

    // تتبع الزائر الحالي
    this.trackCurrentVisitor();
  }

  async loadInitialData() {
    await this.refreshData();
  }

  updateStatistics(visitorsData, usersData) {
    const visitors = visitorsData.visitors || [];
    const users = usersData.users || [];

    this.statistics.guestVisitors = visitors.length;
    this.statistics.registeredUsers = users.length;
    this.statistics.totalActive = visitors.length + users.length;

    // تحديث بطاقات الإحصائيات
    this.updateStatisticsCards();
  }

  updateStatisticsCards() {
    document.getElementById('total-active')?.textContent = this.statistics.totalActive;
    document.getElementById('registered-users')?.textContent = this.statistics.registeredUsers;
    document.getElementById('guest-visitors')?.textContent = this.statistics.guestVisitors;
    document.getElementById('last-update-time')?.textContent = this.formatTime(this.lastUpdateTime);
  }

  renderVisitorsList(visitorsData, usersData) {
    const visitors = visitorsData.visitors || [];
    const users = usersData.users || [];

    // دمج المستخدمين والزوار
    const combinedList = this.combineUsersAndVisitors(users, visitors);

    if (this.currentView === 'cards') {
      this.renderCardsView(combinedList);
    } else {
      this.renderTableView(combinedList);
    }
  }

  combineUsersAndVisitors(users, visitors) {
    const combined = [];

    // إضافة المستخدمين المسجلين
    users.forEach(user => {
      combined.push({
        type: 'user',
        id: user.id,
        name: user.name,
        email: user.email,
        avatar: user.avatar,
        ip: user.ip_address,
        location: user.location || 'غير محدد',
        browser: this.getBrowserInfo(user.user_agent),
        os: this.getOSInfo(user.user_agent),
        lastActivity: user.last_activity,
        status: 'online'
      });
    });

    // إضافة الزوار (تجنب التكرار بناءً على IP)
    const userIPs = users.map(u => u.ip_address);
    visitors.forEach(visitor => {
      if (!userIPs.includes(visitor.ip)) {
        combined.push({
          type: 'visitor',
          id: visitor.id,
          name: 'زائر',
          ip: visitor.ip,
          location: visitor.geo_data ? `${visitor.geo_data.city}, ${visitor.geo_data.country_name}` : 'غير محدد',
          browser: this.getBrowserInfo(visitor.user_agent),
          os: this.getOSInfo(visitor.user_agent),
          lastActivity: visitor.last_activity,
          status: 'guest'
        });
      }
    });

    // ترتيب حسب آخر نشاط
    return combined.sort((a, b) => new Date(b.lastActivity) - new Date(a.lastActivity));
  }

  getBrowserInfo(userAgent) {
    if (!userAgent) return 'غير معروف';

    if (userAgent.includes('Chrome')) return 'Chrome';
    if (userAgent.includes('Firefox')) return 'Firefox';
    if (userAgent.includes('Safari') && !userAgent.includes('Chrome')) return 'Safari';
    if (userAgent.includes('Edge')) return 'Edge';
    if (userAgent.includes('MSIE') || userAgent.includes('Trident/')) return 'Internet Explorer';

    return 'آخر';
  }

  getOSInfo(userAgent) {
    if (!userAgent) return 'غير معروف';

    if (userAgent.includes('Windows NT 10.0')) return 'Windows 10';
    if (userAgent.includes('Windows NT 6.3')) return 'Windows 8.1';
    if (userAgent.includes('Windows NT 6.1')) return 'Windows 7';
    if (userAgent.includes('Windows')) return 'Windows';
    if (userAgent.includes('Mac OS X')) return 'macOS';
    if (userAgent.includes('Linux')) return 'Linux';
    if (userAgent.includes('Android')) return 'Android';
    if (userAgent.includes('iPhone') || userAgent.includes('iPad')) return 'iOS';

    return 'آخر';
  }

  renderCardsView(combinedList) {
    const container = document.getElementById('visitors-cards-container');
    if (!container) return;

    if (combinedList.length === 0) {
      container.innerHTML = this.getEmptyStateHTML();
      return;
    }

    const cardsHTML = combinedList.map(item => this.createVisitorCard(item)).join('');
    container.innerHTML = cardsHTML;
  }

  renderTableView(combinedList) {
    const tbody = document.querySelector('#visitors-table tbody');
    if (!tbody) return;

    if (combinedList.length === 0) {
      tbody.innerHTML = '<tr><td colspan="8" class="text-center py-4">' + this.getEmptyStateHTML() + '</td></tr>';
      return;
    }

    const rowsHTML = combinedList.map(item => this.createVisitorRow(item)).join('');
    tbody.innerHTML = rowsHTML;
  }

  createVisitorCard(item) {
    const statusBadge = item.type === 'user' ?
      '<span class="badge bg-success"><i class="ti ti-user me-1"></i>مسجل</span>' :
      '<span class="badge bg-info"><i class="ti ti-eye me-1"></i>زائر</span>';

    const avatar = item.avatar ?
      `<img src="${item.avatar}" alt="${item.name}" class="rounded-circle" width="40" height="40">` :
      `<div class="avatar-placeholder rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px; background: #f8f9fa; color: #6c757d;"><i class="ti ti-user"></i></div>`;

    return `
      <div class="col-md-6 col-lg-4 mb-3">
        <div class="card visitor-card h-100">
          <div class="card-body">
            <div class="d-flex align-items-center mb-3">
              ${avatar}
              <div class="ms-3 flex-grow-1">
                <h6 class="mb-1">${item.name}</h6>
                ${statusBadge}
              </div>
            </div>
            <div class="visitor-details">
              <div class="detail-item mb-2">
                <i class="ti ti-map-pin text-muted me-2"></i>
                <span class="text-muted">${item.location}</span>
              </div>
              <div class="detail-item mb-2">
                <i class="ti ti-world text-muted me-2"></i>
                <span class="text-muted">${item.ip}</span>
              </div>
              <div class="detail-item mb-2">
                <i class="ti ti-browser text-muted me-2"></i>
                <span class="text-muted">${item.browser} - ${item.os}</span>
              </div>
              <div class="detail-item">
                <i class="ti ti-clock text-muted me-2"></i>
                <span class="text-muted">${this.formatTime(item.lastActivity)}</span>
              </div>
            </div>
          </div>
        </div>
      </div>
    `;
  }

  createVisitorRow(item) {
    const statusBadge = item.type === 'user' ?
      '<span class="badge bg-success"><i class="ti ti-user me-1"></i>مسجل</span>' :
      '<span class="badge bg-info"><i class="ti ti-eye me-1"></i>زائر</span>';

    const avatar = item.avatar ?
      `<img src="${item.avatar}" alt="${item.name}" class="rounded-circle me-2" width="32" height="32">` :
      `<div class="avatar-placeholder rounded-circle d-flex align-items-center justify-content-center me-2" style="width: 32px; height: 32px; background: #f8f9fa; color: #6c757d;"><i class="ti ti-user"></i></div>`;

    return `
      <tr>
        <td>
          <div class="d-flex align-items-center">
            ${avatar}
            <div>
              <div class="fw-semibold">${item.name}</div>
              ${item.email ? `<small class="text-muted">${item.email}</small>` : ''}
            </div>
          </div>
        </td>
        <td>${statusBadge}</td>
        <td>${item.location}</td>
        <td>${item.ip}</td>
        <td>${item.browser}</td>
        <td>${item.os}</td>
        <td>${this.formatTime(item.lastActivity)}</td>
        <td>
          <div class="dropdown">
            <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
              <i class="ti ti-dots-vertical"></i>
            </button>
            <ul class="dropdown-menu">
              <li><a class="dropdown-item" href="#"><i class="ti ti-eye me-2"></i>عرض التفاصيل</a></li>
              ${item.type === 'user' ? '<li><a class="dropdown-item" href="#"><i class="ti ti-message me-2"></i>إرسال رسالة</a></li>' : ''}
            </ul>
          </div>
        </td>
      </tr>
    `;
  }

  getEmptyStateHTML() {
    return `
      <div class="text-center py-5">
        <i class="ti ti-users-off display-4 text-muted mb-3"></i>
        <h5 class="text-muted">لا يوجد زوار نشطون حالياً</h5>
        <p class="text-muted">سيتم عرض الزوار هنا عند زيارتهم للموقع</p>
      </div>
    `;
  }

  formatTime(timestamp) {
    if (!timestamp) return 'غير محدد';

    const date = new Date(timestamp);
    const now = new Date();
    const diffInMinutes = Math.floor((now - date) / (1000 * 60));

    if (diffInMinutes < 1) return 'الآن';
    if (diffInMinutes < 60) return `منذ ${diffInMinutes} دقيقة`;

    const diffInHours = Math.floor(diffInMinutes / 60);
    if (diffInHours < 24) return `منذ ${diffInHours} ساعة`;

    return date.toLocaleDateString('ar-SA');
  }

  switchView(viewType) {
    this.currentView = viewType === 'view-cards' ? 'cards' : 'table';

    // إخفاء/إظهار العروض
    const cardsContainer = document.getElementById('visitors-cards-view');
    const tableContainer = document.getElementById('visitors-table-view');

    if (this.currentView === 'cards') {
      cardsContainer?.classList.remove('d-none');
      tableContainer?.classList.add('d-none');
    } else {
      cardsContainer?.classList.add('d-none');
      tableContainer?.classList.remove('d-none');
    }

    // إعادة عرض البيانات
    this.refreshData();
  }

  startAutoRefresh() {
    // تحديث تلقائي كل 30 ثانية
    this.refreshInterval = setInterval(() => {
      console.log('🔄 تحديث تلقائي للبيانات...');
      this.refreshData();
    }, 30000);
  }

  stopAutoRefresh() {
    if (this.refreshInterval) {
      clearInterval(this.refreshInterval);
      this.refreshInterval = null;
    }
  }

  async trackCurrentVisitor() {
    try {
      const response = await fetch('/track-visitor', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
          url: window.location.href,
          referrer: document.referrer
        })
      });

      if (response.ok) {
        const data = await response.json();
        console.log('✅ تم تسجيل الزائر بنجاح:', data);
      }
    } catch (error) {
      console.error('❌ خطأ في تسجيل الزائر:', error);
    }
  }

  async exportData() {
    try {
      console.log('📤 جاري تصدير البيانات...');

      const [visitorsResponse, usersResponse] = await Promise.all([
        this.fetchActiveVisitors(),
        this.fetchActiveUsers()
      ]);

      if (visitorsResponse.success && usersResponse.success) {
        const combinedData = this.combineUsersAndVisitors(
          usersResponse.data.users || [],
          visitorsResponse.data.visitors || []
        );

        this.downloadCSV(combinedData);
        this.showSuccessMessage('تم تصدير البيانات بنجاح');
      }
    } catch (error) {
      console.error('❌ خطأ في تصدير البيانات:', error);
      this.showErrorMessage('فشل في تصدير البيانات');
    }
  }

  downloadCSV(data) {
    const headers = ['الاسم', 'النوع', 'الموقع', 'عنوان IP', 'المتصفح', 'نظام التشغيل', 'آخر نشاط'];
    const csvContent = [
      headers.join(','),
      ...data.map(item => [
        item.name,
        item.type === 'user' ? 'مسجل' : 'زائر',
        item.location,
        item.ip,
        item.browser,
        item.os,
        this.formatTime(item.lastActivity)
      ].join(','))
    ].join('\n');

    const blob = new Blob([csvContent], { type: 'text/csv;charset=utf-8;' });
    const link = document.createElement('a');
    const url = URL.createObjectURL(blob);

    link.setAttribute('href', url);
    link.setAttribute('download', `active-visitors-${new Date().toISOString().split('T')[0]}.csv`);
    link.style.visibility = 'hidden';

    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
  }

  showLoadingState() {
    const loadingElements = document.querySelectorAll('.loading-indicator');
    loadingElements.forEach(el => el.classList.remove('d-none'));
  }

  hideLoadingState() {
    const loadingElements = document.querySelectorAll('.loading-indicator');
    loadingElements.forEach(el => el.classList.add('d-none'));
  }

  showSuccessMessage(message) {
    this.showToast(message, 'success');
  }

  showErrorMessage(message) {
    this.showToast(message, 'error');
  }

  showToast(message, type = 'info') {
    const toast = document.createElement('div');
    toast.className = `toast align-items-center text-white bg-${type === 'success' ? 'success' : 'danger'} border-0`;
    toast.setAttribute('role', 'alert');
    toast.innerHTML = `
      <div class="d-flex">
        <div class="toast-body">${message}</div>
        <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
      </div>
    `;

    const container = document.querySelector('.toast-container') || document.body;
    container.appendChild(toast);

    const bsToast = new bootstrap.Toast(toast);
    bsToast.show();

    toast.addEventListener('hidden.bs.toast', () => {
      toast.remove();
    });
  }
}

// تهيئة النظام عند تحميل الصفحة
document.addEventListener('DOMContentLoaded', function() {
  window.activeVisitorsMonitor = new ActiveVisitorsMonitor();
});

// دوال مساعدة للتوافق مع الكود القديم
function refreshData() {
  if (window.activeVisitorsMonitor) {
    window.activeVisitorsMonitor.refreshData();
  }
}

function exportData() {
  if (window.activeVisitorsMonitor) {
    window.activeVisitorsMonitor.exportData();
  }
}

function testConnection() {
  console.log('🔍 اختبار الاتصال...');

  const statusDiv = document.getElementById('connection-status');
  if (statusDiv) {
    statusDiv.innerHTML = '<div class="spinner-border spinner-border-sm text-primary me-2" role="status"></div> جاري الاختبار...';

    // اختبار الاتصال بـ API الخارجي
    fetch('https://www.geoplugin.net/json.gp')
      .then(response => {
        if (response.ok) {
          statusDiv.innerHTML = '<div class="alert alert-success mt-2">geoplugin.net: نجاح ✅</div>';
        } else {
          throw new Error('فشل الاتصال');
        }
      })
      .catch(error => {
        statusDiv.innerHTML = '<div class="alert alert-danger mt-2">geoplugin.net: فشل ❌</div>';
      });
  }
}

// الكود القديم (محفوظ للتوافق)
function updateVisitorsTable() {
    console.log('جاري جلب بيانات الزوار...');

    // استخدام النظام الجديد إذا كان متاحاً
    if (window.activeVisitorsMonitor) {
      window.activeVisitorsMonitor.refreshData();
      return;
    }

    // عرض مؤشر التحميل
    document.querySelector('#visitors-table tbody').innerHTML = `
      <tr>
        <td colspan="8" class="text-center py-5">
          <div class="spinner-border text-primary" role="status">
            <span class="visually-hidden">جاري التحميل...</span>
          </div>
          <p class="mt-2">جاري تحميل بيانات الزوار...</p>
        </td>
      </tr>
    `;

    // تعريف متغير لتتبع ما إذا كانت الاستجابة قد نجحت
    let responseReceived = false;

    // مؤقت للعرض التجريبي بعد 8 ثواني إذا لم يتم الحصول على استجابة
    let timeoutId = setTimeout(() => {
      // إذا لم يتم استلام استجابة بعد، عرض بيانات تجريبية
      if (!responseReceived) {
        console.log('انتهى وقت الانتظار، عرض بيانات تجريبية');
        const demoData = {
          success: true,
          count: 1,
          visitors: [{
            id: 'demo-visitor',
            url: 'الصفحة الرئيسية',
            referrer: 'مباشر',
            ip: '127.0.0.1',
            user_agent: navigator.userAgent,
            first_seen: new Date().toISOString(),
            last_activity: new Date().toISOString(),
            geo_data: {
              country_code: 'JO',
              country_name: 'الأردن',
              city: 'عمان'
            }
          }]
        };
        updateVisitorsTableWithData(demoData);
        document.getElementById('last-update').innerHTML = `<span class="badge bg-warning">بيانات تجريبية</span> آخر تحديث: ${new Date().toLocaleTimeString()}`;
      }
    }, 8000); // زيادة الوقت إلى 8 ثواني لإعطاء فرصة للاستجابة الحقيقية

    // Debug fetch URL
    console.log('Fetching visitors URL:', "{{ route('dashboard.monitoring.active-visitors.data', [], false) }}");

    // إضافة معلمة عشوائية لمنع التخزين المؤقت
    const cacheBuster = new Date().getTime();
    const url = `{{ route('dashboard.monitoring.active-visitors.data', [], false) }}?_=${cacheBuster}`;

    fetch(url, {
      method: 'GET',
      credentials: 'same-origin',
      headers: {
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
        'Accept': 'application/json',
        'Cache-Control': 'no-cache, no-store, must-revalidate'
      }
    })
      .then(response => {
        console.log('حالة الاستجابة:', response.status);
        responseReceived = true; // تعيين المتغير للإشارة إلى أننا تلقينا استجابة
        clearTimeout(timeoutId); // إلغاء المؤقت لأننا حصلنا على استجابة
        if (!response.ok) {
          throw new Error(`خطأ في الاتصال! الحالة: ${response.status}`);
        }
        return response.json();
      })
      .then(data => {
        console.log('البيانات المستلمة:', data);
        if (!data.success) {
          throw new Error(data.message || 'حدث خطأ غير معروف');
        }

        // تحديث الجدول بالبيانات الحقيقية
        if (data.visitors && data.visitors.length > 0) {
          console.log('تم العثور على', data.visitors.length, 'زائر نشط');
          console.log('الزائر الأول:', data.visitors[0]);

          // تحديث العنوان بالبيانات الحقيقية
          document.getElementById('last-update').innerHTML = `<span class="badge bg-success">بيانات حقيقية</span> آخر تحديث: ${new Date().toLocaleTimeString()}`;

          // معالجة بيانات الموقع الجغرافي لكل زائر
          data.visitors.forEach(visitor => {
            if (visitor.geo_data && typeof visitor.geo_data === 'string') {
              try {
                visitor.geo_data = JSON.parse(visitor.geo_data);
                console.log('تم تحليل بيانات الموقع الجغرافي من سلسلة JSON');
              } catch (e) {
                console.error('خطأ في تحليل بيانات الموقع الجغرافي:', e);
                visitor.geo_data = {
                  country_code: 'JO',
                  country_name: 'الأردن',
                  city: 'عمان'
                };
              }
            }
          });

          // تحديث الجدول بالبيانات
          updateVisitorsTableWithData(data);
        } else {
          console.log('لم يتم العثور على زوار نشطين');
          throw new Error('لم يتم العثور على زوار نشطين');
        }
      })
      .catch(error => {
        console.error('خطأ في تحديث جدول الزوار:', error);

        // عرض بيانات تجريبية في حالة الخطأ
        console.log('عرض بيانات تجريبية بسبب الخطأ');
        const demoData = {
          success: true,
          count: 1,
          visitors: [{
            id: 'demo-visitor',
            url: 'الصفحة الرئيسية',
            referrer: 'مباشر',
            ip: '127.0.0.1',
            user_agent: navigator.userAgent,
            first_seen: new Date().toISOString(),
            last_activity: new Date().toISOString()
          }]
        };
        updateVisitorsTableWithData(demoData);

        // عرض رسالة تنبيه صغيرة أن البيانات تجريبية
        document.getElementById('last-update').innerHTML = `<span class="badge bg-warning">بيانات تجريبية</span> آخر تحديث: ${new Date().toLocaleTimeString()}`;

        // لا تعرض رسالة الخطأ لأننا عرضنا بيانات تجريبية بدلاً منها
      });
  }

  // وظيفة منفصلة لتحديث الجدول بالبيانات (مع إضافات جديدة)
  function updateVisitorsTableWithData(data) {
    console.log('تحديث الجدول بالبيانات:', data);
    console.log('عدد الزوار:', data.visitors ? data.visitors.length : 0);
    console.log('الزائر الأول:', data.visitors && data.visitors.length > 0 ? data.visitors[0] : 'لا يوجد');

    // تحديث عدد الزوار
    document.getElementById('visitors-count').textContent = data.visitors.length;
    document.getElementById('last-update').textContent = 'آخر تحديث: ' + new Date().toLocaleTimeString();

    // تحديث الإحصائيات
    updateVisitorStats(data.visitors);

    // الحصول على جسم الجدول
    const tbody = document.querySelector('#visitors-table tbody');
    console.log('تم العثور على جسم الجدول:', tbody ? true : false);

    // التحقق من وجود زوار
    if (!data.visitors || data.visitors.length === 0) {
      console.log('لا يوجد زوار لعرضهم');
      tbody.innerHTML = `
        <tr>
          <td colspan="8" class="text-center py-5">
            <i class="ti ti-mood-empty fs-1 text-muted d-block mb-3"></i>
            <p class="mb-0 text-muted">لا يوجد زوار نشطين حاليًا</p>
          </td>
        </tr>
      `;
      return;
    }

    console.log('سيتم عرض', data.visitors.length, 'زائر في الجدول');

    // تفريغ الجدول
    tbody.innerHTML = '';

    // إضافة صفوف لكل زائر
    data.visitors.forEach((visitor, index) => {
      console.log('معالجة الزائر #', index + 1, visitor);
      const row = document.createElement('tr');

      // تحويل الطوابع الزمنية إلى كائنات Date
      let firstSeen, lastActivity;
      try {
        firstSeen = visitor.first_seen ? new Date(visitor.first_seen) : new Date();
        lastActivity = visitor.last_activity ? new Date(visitor.last_activity) : new Date();
        console.log('تم تحويل التواريخ بنجاح:', firstSeen, lastActivity);
      } catch (e) {
        console.warn('خطأ في تحليل التواريخ:', e);
        firstSeen = new Date();
        lastActivity = new Date();
      }

      // استخراج معلومات المتصفح والجهاز
      const browser = getBrowserInfo(visitor.user_agent);
      const deviceType = getDeviceType(visitor.user_agent);

      // معالجة بيانات الموقع الجغرافي
      let location = 'غير معروف';
      if (visitor.geo_data) {
        if (typeof visitor.geo_data === 'string') {
          try {
            const geoDataObj = JSON.parse(visitor.geo_data);
            location = geoDataObj.country_name || geoDataObj.country_code || 'غير معروف';
            console.log('تم تحليل بيانات الموقع من سلسلة JSON:', geoDataObj);
          } catch (e) {
            console.error('خطأ في تحليل بيانات الموقع الجغرافي:', e);
          }
        } else {
          location = visitor.geo_data.country_name || visitor.geo_data.country_code || 'غير معروف';
          console.log('بيانات الموقع الجغرافي ككائن:', visitor.geo_data);
        }
      }

      console.log('الموقع الجغرافي:', location);
      console.log('المتصفح:', browser);
      console.log('نوع الجهاز:', deviceType);

      // إنشاء محتوى الصف
      const rowHtml = `
        <td class="text-center fw-bold">${index + 1}</td>
        <td>
          <div class="d-flex align-items-center">
            <div class="avatar avatar-sm me-2 bg-label-primary">
              <span class="avatar-initial rounded-circle"><i class="ti ti-link"></i></span>
            </div>
            <span class="text-truncate" style="max-width: 200px;" title="${visitor.url}">
              ${visitor.url || 'الصفحة الرئيسية'}
            </span>
          </div>
        </td>
        <td>
          <span class="badge bg-label-secondary">
            <i class="ti ti-arrow-back-up me-1"></i>${visitor.referrer || 'مباشر'}
          </span>
        </td>
        <td>
          <div class="d-flex flex-column">
            <span class="badge bg-label-primary mb-1">
              <i class="ti ti-device-desktop me-1"></i>${visitor.ip || '127.0.0.1'}
            </span>
            <span class="badge bg-label-warning">
              <i class="ti ti-map-pin me-1"></i>${location}
            </span>
          </div>
        </td>
        <td>
          <div class="d-flex flex-column">
            <span class="badge bg-label-info mb-1">
              <i class="ti ti-browser me-1"></i>${browser}
            </span>
            <span class="badge bg-label-${deviceType === 'محمول' ? 'success' : 'secondary'}">
              <i class="ti ti-${deviceType === 'محمول' ? 'device-mobile' : 'device-desktop'} me-1"></i>${deviceType}
            </span>
          </div>
        </td>
        <td>
          <div class="d-flex flex-column">
            <span class="text-muted small">${firstSeen.toLocaleDateString()}</span>
            <div class="d-flex align-items-center">
              <span class="badge bg-label-secondary me-2">
                <i class="ti ti-clock"></i>
              </span>
              ${firstSeen.toLocaleTimeString()}
            </div>
          </div>
        </td>
        <td>
          <div class="d-flex flex-column">
            <span class="text-muted small">${getTimeDifference(lastActivity, new Date())}</span>
            <div class="d-flex align-items-center">
              <span class="badge bg-label-success me-2">
                <i class="ti ti-activity"></i>
              </span>
              ${lastActivity.toLocaleTimeString()}
            </div>
          </div>
        </td>
        <td>
          <div class="dropdown">
            <button class="btn btn-sm btn-icon" data-bs-toggle="dropdown" aria-expanded="false">
              <i class="ti ti-dots-vertical"></i>
            </button>
            <ul class="dropdown-menu dropdown-menu-end">
              <li><a class="dropdown-item" href="javascript:void(0);"><i class="ti ti-info-circle me-1"></i>التفاصيل</a></li>
              <li><a class="dropdown-item" href="javascript:void(0);"><i class="ti ti-map me-1"></i>عرض الموقع</a></li>
            </ul>
          </div>
        </td>
      `;

      // تعيين HTML للصف
      row.innerHTML = rowHtml;
      console.log('تم إنشاء صف الجدول بنجاح');

      // إضافة الصف إلى جسم الجدول
      tbody.appendChild(row);
      console.log('تمت إضافة الصف إلى الجدول');
    });
  }

  // وظيفة لتحديث إحصائيات الزوار
  function updateVisitorStats(visitors) {
    if (!visitors || !visitors.length) {
      document.getElementById('total-visitors-count').textContent = '0';
      document.getElementById('desktop-count').textContent = '0';
      document.getElementById('mobile-count').textContent = '0';
      document.getElementById('avg-time').textContent = '0:00';
      return;
    }

    // إجمالي الزوار
    document.getElementById('total-visitors-count').textContent = visitors.length;

    // عدد الأجهزة المكتبية والمحمولة
    let desktopCount = 0;
    let mobileCount = 0;

    visitors.forEach(visitor => {
      if (isMobileDevice(visitor.user_agent)) {
        mobileCount++;
      } else {
        desktopCount++;
      }
    });

    document.getElementById('desktop-count').textContent = desktopCount;
    document.getElementById('mobile-count').textContent = mobileCount;

    // حساب متوسط وقت التصفح
    let totalTime = 0;
    visitors.forEach(visitor => {
      try {
        const firstSeen = visitor.first_seen ? new Date(visitor.first_seen) : new Date();
        const lastActivity = visitor.last_activity ? new Date(visitor.last_activity) : new Date();
        const sessionTime = (lastActivity - firstSeen) / 1000 / 60; // بالدقائق
        totalTime += sessionTime;
      } catch (e) {
        console.warn('خطأ في حساب وقت الجلسة:', e);
      }
    });

    const avgTime = totalTime / visitors.length;
    const minutes = Math.floor(avgTime);
    const seconds = Math.floor((avgTime - minutes) * 60);

    document.getElementById('avg-time').textContent = `${minutes}:${seconds < 10 ? '0' + seconds : seconds}`;
  }

  // وظيفة للتحقق من نوع الجهاز
  function isMobileDevice(userAgent) {
    if (!userAgent) return false;
    return /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(userAgent);
  }

  // وظيفة للحصول على نوع الجهاز
  function getDeviceType(userAgent) {
    if (!userAgent) return 'غير معروف';

    if (/Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(userAgent)) {
      return 'محمول';
    }

    return 'سطح المكتب';
  }

  // وظيفة لحساب الفرق بين وقتين
  function getTimeDifference(date1, date2) {
    if (!date1 || !date2) return 'لتو';

    const diffMs = Math.abs(date2 - date1);
    const diffSec = Math.floor(diffMs / 1000);
    const diffMin = Math.floor(diffSec / 60);
    const diffHours = Math.floor(diffMin / 60);

    if (diffSec < 60) {
      return `منذ ${diffSec} ثانية`;
    } else if (diffMin < 60) {
      return `منذ ${diffMin} دقيقة`;
    } else if (diffHours < 24) {
      return `منذ ${diffHours} ساعة`;
    } else {
      return `منذ ${Math.floor(diffHours / 24)} يوم`;
    }
  }

  // تحديث البيانات كل 10 ثوانٍ
  console.log('تم تعيين تحديث تلقائي كل 10 ثوانٍ');
  setInterval(updateVisitorsTable, 10000);

  // تتبع الزائر الحالي
  function trackCurrentVisitor() {
    const data = {
      url: window.location.href,
      referrer: document.referrer
    };

    fetch('/track-visitor', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
      },
      body: JSON.stringify(data)
    })
    .then(response => response.json())
    .then(data => console.log('تم تسجيل الزائر:', data))
    .catch(error => console.error('خطأ في تسجيل الزائر:', error));
  }

  // تتبع الزائر عند تحميل الصفحة
  trackCurrentVisitor();

  // تحديث نشاط الزائر كل 30 ثانية
  setInterval(function() {
    const data = {
      url: window.location.href
    };

    fetch('/update-visitor-activity', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
      },
      body: JSON.stringify(data)
    })
    .catch(error => console.error('خطأ في تحديث نشاط الزائر:', error));

    // تحديث عداد الزوار النشطين
    updateVisitorCounter();
  }, 30000);

  // وظيفة لتحديث عداد الزوار النشطين
  function updateVisitorCounter() {
    // إضافة معلمة عشوائية لمنع التخزين المؤقت
    const cacheBuster = new Date().getTime();
    const url = `{{ route('dashboard.monitoring.active-visitors.data', [], false) }}?_=${cacheBuster}`;

    fetch(url, {
      method: 'GET',
      credentials: 'same-origin',
      headers: {
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
        'Accept': 'application/json',
        'Cache-Control': 'no-cache, no-store, must-revalidate'
      }
    })
    .then(response => response.json())
    .then(data => {
      if (data.success) {
        // تحديث عداد الزوار
        document.getElementById('visitor-count').textContent = data.count;
        // تحديث وقت آخر تحديث
        document.getElementById('last-update-time').textContent = new Date().toLocaleTimeString();
      }
    })
    .catch(error => {
      console.error('خطأ في تحديث عداد الزوار:', error);
    });
  }

  // وظائف أزرار التبديل
  function initViewToggle() {
    const cardsRadio = document.getElementById('view-cards');
    const tableRadio = document.getElementById('view-table');
    const cardsView = document.getElementById('visitors-cards-view');
    const tableView = document.getElementById('visitors-table-view');

    if (cardsRadio && tableRadio && cardsView && tableView) {
      cardsRadio.addEventListener('change', function() {
        if (this.checked) {
          cardsView.classList.remove('d-none');
          tableView.classList.add('d-none');
          console.log('تم التبديل إلى عرض البطاقات');
        }
      });

      tableRadio.addEventListener('change', function() {
        if (this.checked) {
          cardsView.classList.add('d-none');
          tableView.classList.remove('d-none');
          console.log('تم التبديل إلى عرض الجدول');
        }
      });

      // تعيين العرض الافتراضي (بطاقات)
      cardsView.classList.remove('d-none');
      tableView.classList.add('d-none');
    } else {
      console.error('لم يتم العثور على عناصر أزرار التبديل');
    }
  }

  // تشغيل أزرار التبديل عند تحميل الصفحة
  document.addEventListener('DOMContentLoaded', function() {
    initViewToggle();
  });
</script>
@endsection
