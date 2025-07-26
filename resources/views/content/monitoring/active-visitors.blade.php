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
                  <span class="badge bg-label-primary" id="users-trend">+0%</span>
                </div>
                <small class="text-muted">متصلين الآن</small>
              </div>
              <div class="card-icon">
                <div class="avatar avatar-md bg-label-success">
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
                <p class="card-text text-muted mb-1">زوار</p>
                <div class="d-flex align-items-center mb-2">
                  <h3 class="card-title mb-0 me-2" id="guest-visitors">0</h3>
                  <span class="badge bg-label-warning" id="guests-trend">+0%</span>
                </div>
                <small class="text-muted">غير مسجلين</small>
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
                <p class="card-text text-muted mb-1">متوسط الجلسة</p>
                <div class="d-flex align-items-center mb-2">
                  <h3 class="card-title mb-0 me-2" id="avg-session">0 د</h3>
                  <span class="badge bg-label-info" id="session-trend">+0%</span>
                </div>
                <small class="text-muted">وقت التصفح</small>
              </div>
              <div class="card-icon">
                <div class="avatar avatar-md bg-label-info">
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
          <h5 class="mb-1">المستخدمين والزوار النشطين</h5>
          <p class="text-muted mb-0">قائمة بجميع المتصلين حالياً</p>
        </div>
        <div class="d-flex gap-2">
          <div class="btn-group view-toggle-group" role="group">
            <div class="form-check form-check-inline">
              <input class="form-check-input" type="radio" name="view-type" id="view-cards" checked>
              <label class="form-check-label" for="view-cards">
                <i class="ti ti-layout-grid me-1"></i>بطاقات
              </label>
            </div>
            <div class="form-check form-check-inline">
              <input class="form-check-input" type="radio" name="view-type" id="view-table">
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
          $visitors = $visitors ?? collect();

          // تحويل إلى مصفوفات إذا لزم الأمر
          if (is_object($activeUsers) && method_exists($activeUsers, 'toArray')) {
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
              'avatar' => $user['avatar'] ?? null,
              'status' => 'online',
              'url' => $user['url'] ?? 'الصفحة الرئيسية',
              'ip' => $user['ip_address'] ?? 'غير معروف',
              'location' => ($user['city'] ?? 'غير محدد') . ', ' . ($user['country'] ?? 'غير محدد'),
              'last_activity' => $user['last_activity'] ?? now(),
              'user_agent' => $user['user_agent'] ?? ''
            ];
          }

          // إضافة الزوار غير المسجلين
          // تجنب التكرار بناءً على IP
          $existingIPs = collect($allActive)->pluck('ip')->toArray();
          
          foreach($visitors as $visitor) {
            if (!in_array($visitor['ip'] ?? '', $existingIPs)) {
              $allActive[] = [
                'type' => 'guest',
                'id' => 'guest-' . ($visitor['ip'] ?? 'unknown'),
                'name' => 'زائر ' . ($visitor['ip'] ?? 'غير معروف'),
                'avatar' => null,
                'status' => 'guest',
                'url' => $visitor['url'] ?? 'الصفحة الرئيسية',
                'ip' => $visitor['ip'] ?? 'غير معروف',
                'location' => ($visitor['city'] ?? 'غير محدد') . ', ' . ($visitor['country_name'] ?? 'غير محدد'),
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
                @foreach($allActive as $index => $item)
                  <div class="col-lg-6 col-xl-4 mb-4">
                    <div class="card visitor-card {{ $item['type'] === 'user' ? 'registered-user' : 'guest-visitor' }} h-100">
                      <div class="card-body">
                        <!-- Header -->
                        <div class="d-flex align-items-center mb-3">
                          <div class="avatar avatar-md me-3 {{ $item['type'] === 'user' ? 'user-status-online' : '' }}">
                            @if($item['type'] === 'user')
                              <span class="avatar-initial rounded-circle bg-label-primary"><i class="ti ti-user"></i></span>
                            @else
                              <span class="avatar-initial rounded-circle bg-label-secondary"><i class="ti ti-user-question"></i></span>
                            @endif
                          </div>
                          <div class="flex-grow-1">
                            <h6 class="mb-1">{{ $item['name'] }}</h6>
                            <div class="d-flex align-items-center">
                              <span class="activity-indicator activity-{{ $item['status'] === 'online' ? 'active' : ($item['status'] === 'guest' ? 'idle' : 'offline') }}"></span>
                              <small class="text-muted">
                                @if($item['type'] === 'user')
                                  <i class="ti ti-crown me-1 text-primary"></i>مستخدم مسجل
                                @else
                                  <i class="ti ti-user-question me-1 text-secondary"></i>زائر
                                @endif
                              </small>
                            </div>
                          </div>
                        </div>

                        <!-- Details -->
                        <div class="visitor-details">
                          <div class="detail-item">
                            <i class="ti ti-link text-primary me-2"></i>
                            <span class="detail-label">الصفحة:</span>
                            <span class="detail-value text-truncate">{{ $item['url'] }}</span>
                          </div>
                          <div class="detail-item">
                            <i class="ti ti-map-pin text-success me-2"></i>
                            <span class="detail-label">الموقع:</span>
                            <span class="detail-value">{{ $item['location'] }}</span>
                          </div>
                          <div class="detail-item">
                            <i class="ti ti-device-desktop text-info me-2"></i>
                            <span class="detail-label">IP:</span>
                            <span class="detail-value">{{ $item['ip'] }}</span>
                          </div>
                          <div class="detail-item">
                            <i class="ti ti-clock text-muted me-2"></i>
                            <span class="detail-label">آخر نشاط:</span>
                            <span class="detail-value">{{ \Carbon\Carbon::parse($item['last_activity'])->diffForHumans() }}</span>
                          </div>
                        </div>

                        <!-- Footer -->
                        <div class="d-flex justify-content-between align-items-center mt-3">
                          @if($item['type'] === 'user')
                            <span class="badge bg-success">متصل</span>
                          @else
                            <span class="badge bg-warning">زائر</span>
                          @endif
                          <div class="dropdown">
                            <button class="btn btn-sm btn-outline-secondary" type="button" data-bs-toggle="dropdown">
                              <i class="ti ti-dots-vertical"></i>
                            </button>
                            <ul class="dropdown-menu">
                              <li><a class="dropdown-item" href="#"><i class="ti ti-eye me-2"></i>عرض التفاصيل</a></li>
                              @if($item['type'] === 'user')
                                <li><a class="dropdown-item" href="#"><i class="ti ti-message me-2"></i>إرسال رسالة</a></li>
                              @endif
                              <li><hr class="dropdown-divider"></li>
                              <li><a class="dropdown-item text-danger" href="#"><i class="ti ti-ban me-2"></i>حظر IP</a></li>
                            </ul>
                          </div>
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
                  <th class="text-center">#</th>
                  <th>المستخدم</th>
                  <th>النوع</th>
                  <th>الصفحة</th>
                  <th>الموقع</th>
                  <th>آخر نشاط</th>
                  <th>الحالة</th>
                  <th class="text-center">الإجراءات</th>
                </tr>
              </thead>
              <tbody>
                @if(count($allActive) > 0)
                  @foreach($allActive as $index => $item)
                    <tr>
                      <td class="text-center fw-bold">{{ $index + 1 }}</td>
                      <td>
                        <div class="d-flex align-items-center">
                          <div class="avatar avatar-sm me-2 {{ $item['type'] === 'user' ? 'bg-label-primary' : 'bg-label-secondary' }}">
                            <span class="avatar-initial rounded-circle">
                              @if($item['type'] === 'user')
                                <i class="ti ti-user"></i>
                              @else
                                <i class="ti ti-user-question"></i>
                              @endif
                            </span>
                          </div>
                          <div>
                            <h6 class="mb-0">{{ $item['name'] }}</h6>
                            <small class="text-muted">ID: {{ $item['id'] }}</small>
                          </div>
                        </div>
                      </td>
                      <td>
                        @if($item['type'] === 'user')
                          <span class="badge bg-primary"><i class="ti ti-crown me-1"></i>مستخدم مسجل</span>
                        @else
                          <span class="badge bg-secondary"><i class="ti ti-user-question me-1"></i>زائر</span>
                        @endif
                      </td>
                      <td>
                        <span class="text-truncate d-inline-block" style="max-width: 200px;" title="{{ $item['url'] }}">
                          {{ $item['url'] }}
                        </span>
                      </td>
                      <td>
                        <div class="d-flex align-items-center">
                          <i class="ti ti-map-pin me-2 text-muted"></i>
                          <span>{{ $item['location'] }}</span>
                        </div>
                      </td>
                      <td>
                        <small class="text-muted">{{ \Carbon\Carbon::parse($item['last_activity'])->diffForHumans() }}</small>
                      </td>
                      <td>
                        <span class="activity-indicator activity-{{ $item['status'] === 'online' ? 'active' : ($item['status'] === 'guest' ? 'idle' : 'offline') }}"></span>
                        @if($item['type'] === 'user')
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
                            @if($item['type'] === 'user')
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
                      <div class="text-center">
                        <div class="avatar avatar-xl mx-auto mb-3">
                          <span class="avatar-initial rounded-circle bg-label-secondary">
                            <i class="ti ti-users ti-lg"></i>
                          </span>
                        </div>
                        <h5 class="mb-2">لا يوجد مستخدمين أو زوار نشطين</h5>
                        <p class="text-muted mb-0">لا يوجد أي نشاط حالياً على الموقع</p>
                      </div>
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
  try {
    window.activeVisitorsDataUrl = "{{ route('dashboard.monitoring.active-visitors.data', [], false) }}";
    console.log('تم تعيين URL البيانات:', window.activeVisitorsDataUrl);
  } catch (error) {
    console.error('خطأ في تعيين URL البيانات:', error);
    window.activeVisitorsDataUrl = '/dashboard/monitoring/active-visitors/data';
  }
  window.activeUsersDataUrl = null; // إذا كان متاحاً
</script>

{{-- استدعاء ملف الجافا سكريبت المنفصل --}}
@vite(['resources/assets/vendor/js/monitoring/active-visitors.js'])
@endsection
