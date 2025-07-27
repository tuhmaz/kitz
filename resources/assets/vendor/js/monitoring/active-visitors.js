/**
 * نظام مراقبة الزوار والمستخدمين النشطين
 * Active Visitors Monitoring System
 * 
 * @author Your Name
 * @version 1.0.0
 */

// نظام مراقبة المستخدمين والزوار النشطين
class ActiveVisitorsMonitor {
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
    const refreshBtn = document.getElementById('refresh-btn');
    if (refreshBtn) refreshBtn.addEventListener('click', () => this.refreshData());
    
    const exportBtn = document.getElementById('export-btn');
    if (exportBtn) exportBtn.addEventListener('click', () => this.exportData());

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
    const totalActiveEl = document.getElementById('total-active');
    if (totalActiveEl) totalActiveEl.textContent = this.statistics.totalActive;
    
    const registeredUsersEl = document.getElementById('registered-users');
    if (registeredUsersEl) registeredUsersEl.textContent = this.statistics.registeredUsers;
    
    const guestVisitorsEl = document.getElementById('guest-visitors');
    if (guestVisitorsEl) guestVisitorsEl.textContent = this.statistics.guestVisitors;
    
    const lastUpdateTimeEl = document.getElementById('last-update-time');
    if (lastUpdateTimeEl) lastUpdateTimeEl.textContent = this.formatTime(this.lastUpdateTime);
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
        id: user.user_id,
        name: user.user_name,
        avatar: user.avatar,
        status: 'online',
        url: user.url || 'الصفحة الرئيسية',
        ip: user.ip_address,
        location: `${user.city || 'غير محدد'}, ${user.country || 'غير محدد'}`,
        browser: this.getBrowserInfo(user.user_agent),
        os: this.getOSInfo(user.user_agent),
        lastActivity: user.last_activity,
        userAgent: user.user_agent
      });
    });

    // إضافة الزوار غير المسجلين (تجنب التكرار بناءً على IP)
    const existingIPs = new Set(users.map(user => user.ip_address));
    
    visitors.forEach(visitor => {
      if (!existingIPs.has(visitor.ip)) {
        combined.push({
          type: 'guest',
          id: visitor.id || `guest-${visitor.ip}`,
          name: `زائر ${visitor.ip}`,
          avatar: null,
          status: 'guest',
          url: visitor.url || 'الصفحة الرئيسية',
          ip: visitor.ip,
          location: `${visitor.city || 'غير محدد'}, ${visitor.country_name || 'غير محدد'}`,
          browser: this.getBrowserInfo(visitor.user_agent),
          os: this.getOSInfo(visitor.user_agent),
          lastActivity: visitor.last_activity,
          userAgent: visitor.user_agent
        });
      }
    });

    // ترتيب حسب آخر نشاط
    return combined.sort((a, b) => new Date(b.lastActivity) - new Date(a.lastActivity));
  }

  renderCardsView(combinedList) {
    const container = document.getElementById('visitors-cards-container');
    if (!container) return;

    if (combinedList.length === 0) {
      container.innerHTML = this.getEmptyStateHTML();
      return;
    }

    const cardsHTML = combinedList.map((item, index) => this.createVisitorCard(item, index)).join('');
    container.innerHTML = `<div class="row">${cardsHTML}</div>`;
  }

  renderTableView(combinedList) {
    const tableBody = document.querySelector('#visitors-table tbody');
    if (!tableBody) return;

    if (combinedList.length === 0) {
      tableBody.innerHTML = `
        <tr>
          <td colspan="8" class="text-center py-5">
            ${this.getEmptyStateHTML()}
          </td>
        </tr>
      `;
      return;
    }

    const rowsHTML = combinedList.map((item, index) => this.createTableRow(item, index)).join('');
    tableBody.innerHTML = rowsHTML;
  }

  createVisitorCard(visitor, index) {
    const statusClass = visitor.type === 'user' ? 'registered-user' : 'guest-visitor';
    const statusBadge = visitor.type === 'user' ? 
      '<span class="badge bg-success">متصل</span>' : 
      '<span class="badge bg-warning">زائر</span>';

    return `
      <div class="col-lg-6 col-xl-4 mb-4">
        <div class="card visitor-card ${statusClass} h-100">
          <div class="card-body">
            <!-- Header -->
            <div class="d-flex align-items-center mb-3">
              <div class="avatar avatar-md me-3 ${visitor.type === 'user' ? 'user-status-online' : ''}">
                ${visitor.type === 'user' ? 
                  '<span class="avatar-initial rounded-circle bg-label-primary"><i class="ti ti-user"></i></span>' :
                  '<span class="avatar-initial rounded-circle bg-label-secondary"><i class="ti ti-user-question"></i></span>'
                }
              </div>
              <div class="flex-grow-1">
                <h6 class="mb-1">${visitor.name}</h6>
                <div class="d-flex align-items-center">
                  <span class="activity-indicator activity-${visitor.status === 'online' ? 'active' : (visitor.status === 'guest' ? 'idle' : 'offline')}"></span>
                  <small class="text-muted">
                    ${visitor.type === 'user' ? 
                      '<i class="ti ti-crown me-1 text-primary"></i>مستخدم مسجل' :
                      '<i class="ti ti-user-question me-1 text-secondary"></i>زائر'
                    }
                  </small>
                </div>
              </div>
            </div>

            <!-- Details -->
            <div class="visitor-details">
              <div class="detail-item">
                <i class="ti ti-link text-primary me-2"></i>
                <span class="detail-label">الصفحة:</span>
                <span class="detail-value text-truncate">${visitor.url}</span>
              </div>
              <div class="detail-item">
                <i class="ti ti-map-pin text-success me-2"></i>
                <span class="detail-label">الموقع:</span>
                <span class="detail-value">${visitor.location}</span>
              </div>
              <div class="detail-item">
                <i class="ti ti-device-desktop text-info me-2"></i>
                <span class="detail-label">IP:</span>
                <span class="detail-value">${visitor.ip}</span>
              </div>
              <div class="detail-item">
                <i class="ti ti-browser text-warning me-2"></i>
                <span class="detail-label">المتصفح:</span>
                <span class="detail-value">${visitor.browser}</span>
              </div>
              <div class="detail-item">
                <i class="ti ti-clock text-muted me-2"></i>
                <span class="detail-label">آخر نشاط:</span>
                <span class="detail-value">${this.formatTime(visitor.lastActivity)}</span>
              </div>
            </div>

            <!-- Footer -->
            <div class="d-flex justify-content-between align-items-center mt-3">
              ${statusBadge}
              <div class="dropdown">
                <button class="btn btn-sm btn-outline-secondary" type="button" data-bs-toggle="dropdown">
                  <i class="ti ti-dots-vertical"></i>
                </button>
                <ul class="dropdown-menu">
                  <li><a class="dropdown-item" href="#"><i class="ti ti-eye me-2"></i>عرض التفاصيل</a></li>
                  ${visitor.type === 'user' ? 
                    '<li><a class="dropdown-item" href="#"><i class="ti ti-message me-2"></i>إرسال رسالة</a></li>' : ''
                  }
                  <li><hr class="dropdown-divider"></li>
                  <li><a class="dropdown-item text-danger" href="#"><i class="ti ti-ban me-2"></i>حظر IP</a></li>
                </ul>
              </div>
            </div>
          </div>
        </div>
      </div>
    `;
  }

  createTableRow(visitor, index) {
    const statusBadge = visitor.type === 'user' ? 
      '<span class="badge bg-primary"><i class="ti ti-crown me-1"></i>مستخدم مسجل</span>' : 
      '<span class="badge bg-secondary"><i class="ti ti-user-question me-1"></i>زائر</span>';

    return `
      <tr>
        <td class="text-center fw-bold">${index + 1}</td>
        <td>
          <div class="d-flex align-items-center">
            <div class="avatar avatar-sm me-2 ${visitor.type === 'user' ? 'bg-label-primary' : 'bg-label-secondary'}">
              <span class="avatar-initial rounded-circle">
                ${visitor.type === 'user' ? 
                  '<i class="ti ti-user"></i>' : 
                  '<i class="ti ti-user-question"></i>'
                }
              </span>
            </div>
            <div>
              <h6 class="mb-0">${visitor.name}</h6>
              <small class="text-muted">ID: ${visitor.id}</small>
            </div>
          </div>
        </td>
        <td>${statusBadge}</td>
        <td>
          <span class="text-truncate d-inline-block" style="max-width: 200px;" title="${visitor.url}">
            ${visitor.url}
          </span>
        </td>
        <td>
          <div class="d-flex align-items-center">
            <i class="ti ti-map-pin me-2 text-muted"></i>
            <span>${visitor.location}</span>
          </div>
        </td>
        <td>
          <small class="text-muted">${this.formatTime(visitor.lastActivity)}</small>
        </td>
        <td>
          <span class="activity-indicator activity-${visitor.status === 'online' ? 'active' : (visitor.status === 'guest' ? 'idle' : 'offline')}"></span>
          ${visitor.type === 'user' ? 
            '<span class="badge bg-success">متصل</span>' : 
            '<span class="badge bg-warning">زائر</span>'
          }
        </td>
        <td>
          <div class="dropdown">
            <button class="btn btn-sm btn-outline-secondary" type="button" data-bs-toggle="dropdown">
              <i class="ti ti-dots-vertical"></i>
            </button>
            <ul class="dropdown-menu">
              <li><a class="dropdown-item" href="#"><i class="ti ti-eye me-2"></i>عرض التفاصيل</a></li>
              ${visitor.type === 'user' ? 
                '<li><a class="dropdown-item" href="#"><i class="ti ti-message me-2"></i>إرسال رسالة</a></li>' : ''
              }
              <li><hr class="dropdown-divider"></li>
              <li><a class="dropdown-item text-danger" href="#"><i class="ti ti-ban me-2"></i>حظر IP</a></li>
            </ul>
          </div>
        </td>
      </tr>
    `;
  }

  getEmptyStateHTML() {
    return `
      <div class="text-center py-5">
        <div class="avatar avatar-xl mx-auto mb-3">
          <span class="avatar-initial rounded-circle bg-label-secondary">
            <i class="ti ti-users ti-lg"></i>
          </span>
        </div>
        <h5 class="mb-2">لا يوجد مستخدمين أو زوار نشطين</h5>
        <p class="text-muted mb-0">لا يوجد أي نشاط حالياً على الموقع</p>
      </div>
    `;
  }

  switchView(viewType) {
    const cardsView = document.getElementById('visitors-cards-view');
    const tableView = document.getElementById('visitors-table-view');

    if (viewType === 'view-cards') {
      this.currentView = 'cards';
      if (cardsView) cardsView.classList.remove('d-none');
      if (tableView) tableView.classList.add('d-none');
    } else if (viewType === 'view-table') {
      this.currentView = 'table';
      if (cardsView) cardsView.classList.add('d-none');
      if (tableView) tableView.classList.remove('d-none');
    }

    console.log(`تم التبديل إلى عرض: ${this.currentView}`);
  }

  async refreshData() {
    try {
      this.showLoadingState();
      
      // تحديد URL البيانات بشكل آمن
      let dataUrl = '/dashboard/monitoring/active-visitors/data';
      
      // محاولة استخدام URL من Blade إذا كان متاحاً
      if (window.activeVisitorsDataUrl && 
          window.activeVisitorsDataUrl !== 'undefined' && 
          window.activeVisitorsDataUrl !== null && 
          window.activeVisitorsDataUrl.trim() !== '') {
        dataUrl = window.activeVisitorsDataUrl;
        console.log('استخدام URL من Blade:', dataUrl);
      } else {
        console.log('استخدام URL افتراضي:', dataUrl);
      }
      
      // جلب بيانات الزوار
      const visitorsResponse = await this.fetchWithTimeout(dataUrl, {
        method: 'GET',
        credentials: 'same-origin',
        headers: {
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
          'Accept': 'application/json',
          'Cache-Control': 'no-cache'
        }
      });

      if (!visitorsResponse.ok) {
        throw new Error(`HTTP error! status: ${visitorsResponse.status}`);
      }

      const visitorsData = await visitorsResponse.json();
      
      // جلب بيانات المستخدمين النشطين (إذا كان متاحاً)
      let usersData = { users: [] };
      try {
        if (window.activeUsersDataUrl) {
          const usersResponse = await this.fetchWithTimeout(window.activeUsersDataUrl, {
            method: 'GET',
            credentials: 'same-origin',
            headers: {
              'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
              'Accept': 'application/json',
              'Cache-Control': 'no-cache'
            }
          });
          
          if (usersResponse.ok) {
            usersData = await usersResponse.json();
          }
        }
      } catch (error) {
        console.warn('تعذر جلب بيانات المستخدمين النشطين:', error);
      }

      this.lastUpdateTime = new Date();
      this.updateStatistics(visitorsData, usersData);
      this.renderVisitorsList(visitorsData, usersData);
      this.hideLoadingState();
      this.showSuccessMessage('تم تحديث البيانات بنجاح');

    } catch (error) {
      console.error('خطأ في تحديث البيانات:', error);
      this.hideLoadingState();
      this.showErrorMessage('حدث خطأ أثناء تحديث البيانات');
      
      // عرض بيانات تجريبية في حالة الخطأ
      this.showDemoData();
    }
  }

  fetchWithTimeout(url, options, timeout = 8000) {
    return Promise.race([
      fetch(url, options),
      new Promise((_, reject) =>
        setTimeout(() => reject(new Error('انتهت مهلة الطلب')), timeout)
      )
    ]);
  }

  showDemoData() {
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

    const demoUsers = { users: [] };
    
    this.updateStatistics(demoData, demoUsers);
    this.renderVisitorsList(demoData, demoUsers);
    this.showWarningMessage('يتم عرض بيانات تجريبية');
  }

  exportData() {
    try {
      // جمع البيانات الحالية
      const data = this.getCurrentData();
      
      // تحويل إلى CSV
      const csv = this.convertToCSV(data);
      
      // تحميل الملف
      this.downloadCSV(csv, `active-visitors-${new Date().toISOString().split('T')[0]}.csv`);
      
      this.showSuccessMessage('تم تصدير البيانات بنجاح');
    } catch (error) {
      console.error('خطأ في تصدير البيانات:', error);
      this.showErrorMessage('حدث خطأ أثناء تصدير البيانات');
    }
  }

  getCurrentData() {
    const cards = document.querySelectorAll('.visitor-card');
    const data = [];

    cards.forEach((card, index) => {
      const h6El = card.querySelector('h6');
      const name = h6El ? h6El.textContent : '';
      const type = card.classList.contains('registered-user') ? 'مستخدم مسجل' : 'زائر';
      
      const urlEl = card.querySelector('.detail-value');
      const url = urlEl ? urlEl.textContent : '';
      
      const detailValues = card.querySelectorAll('.detail-value');
      const location = detailValues[1] ? detailValues[1].textContent : '';
      const ip = detailValues[2] ? detailValues[2].textContent : '';
      const browser = detailValues[3] ? detailValues[3].textContent : '';
      const lastActivity = detailValues[4] ? detailValues[4].textContent : '';

      data.push({
        '#': index + 1,
        'الاسم': name,
        'النوع': type,
        'الصفحة': url,
        'الموقع': location,
        'IP': ip,
        'المتصفح': browser,
        'آخر نشاط': lastActivity
      });
    });

    return data;
  }

  convertToCSV(data) {
    if (data.length === 0) return '';

    const headers = Object.keys(data[0]);
    const csvContent = [
      headers.join(','),
      ...data.map(row => headers.map(header => `"${row[header]}"`).join(','))
    ].join('\n');

    return csvContent;
  }

  downloadCSV(csv, filename) {
    const blob = new Blob([csv], { type: 'text/csv;charset=utf-8;' });
    const link = document.createElement('a');
    
    if (link.download !== undefined) {
      const url = URL.createObjectURL(blob);
      link.setAttribute('href', url);
      link.setAttribute('download', filename);
      link.style.visibility = 'hidden';
      document.body.appendChild(link);
      link.click();
      document.body.removeChild(link);
    }
  }

  startAutoRefresh() {
    // تحديث كل 30 ثانية
    this.refreshInterval = setInterval(() => {
      this.refreshData();
    }, 30000);

    console.log('تم تشغيل التحديث التلقائي (كل 30 ثانية)');
  }

  stopAutoRefresh() {
    if (this.refreshInterval) {
      clearInterval(this.refreshInterval);
      this.refreshInterval = null;
      console.log('تم إيقاف التحديث التلقائي');
    }
  }

  trackCurrentVisitor() {
    // تحديث نشاط الزائر كل 30 ثانية
    setInterval(() => {
      const data = { url: window.location.href };
      
      // التحقق من وجود CSRF token
      const csrfToken = document.querySelector('meta[name="csrf-token"]');
      if (!csrfToken) {
        console.error('CSRF token not found');
        return;
      }

      fetch('/update-visitor-activity', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': csrfToken.getAttribute('content')
        },
        body: JSON.stringify(data)
      })
      .then(response => {
        if (!response.ok) {
          throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.json();
      })
      .then(data => {
        if (data.success) {
          console.log('تم تحديث نشاط الزائر بنجاح');
        } else {
          console.error('فشل في تحديث نشاط الزائر:', data.message);
        }
      })
      .catch(error => {
        console.error('خطأ في تحديث نشاط الزائر:', error);
      });
    }, 30000);
  }

  showLoadingState() {
    const containers = [
      document.getElementById('visitors-cards-container'),
      document.querySelector('#visitors-table tbody')
    ];

    containers.forEach(container => {
      if (container) {
        container.innerHTML = `
          <div class="text-center py-5">
            <div class="spinner-border text-primary" role="status">
              <span class="visually-hidden">جاري التحميل...</span>
            </div>
            <p class="mt-2">جاري تحميل بيانات الزوار...</p>
          </div>
        `;
      }
    });
  }

  hideLoadingState() {
    // سيتم إخفاء حالة التحميل عند عرض البيانات الجديدة
  }

  showSuccessMessage(message) {
    this.showToast(message, 'success');
  }

  showErrorMessage(message) {
    this.showToast(message, 'error');
  }

  showWarningMessage(message) {
    this.showToast(message, 'warning');
  }

  showToast(message, type = 'info') {
    // إنشاء toast notification
    const toast = document.createElement('div');
    toast.className = `toast align-items-center text-white bg-${type === 'success' ? 'success' : type === 'error' ? 'danger' : 'warning'} border-0`;
    toast.setAttribute('role', 'alert');
    toast.innerHTML = `
      <div class="d-flex">
        <div class="toast-body">${message}</div>
        <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
      </div>
    `;

    // إضافة إلى container
    let toastContainer = document.getElementById('toast-container');
    if (!toastContainer) {
      toastContainer = document.createElement('div');
      toastContainer.id = 'toast-container';
      toastContainer.className = 'toast-container position-fixed top-0 end-0 p-3';
      document.body.appendChild(toastContainer);
    }

    toastContainer.appendChild(toast);

    // تشغيل toast
    const bsToast = new bootstrap.Toast(toast);
    bsToast.show();

    // إزالة toast بعد إخفائه
    toast.addEventListener('hidden.bs.toast', () => {
      toast.remove();
    });
  }

  getBrowserInfo(userAgent) {
    if (!userAgent) return 'غير معروف';

    if (userAgent.includes('Chrome')) return 'Chrome';
    if (userAgent.includes('Firefox')) return 'Firefox';
    if (userAgent.includes('Safari') && !userAgent.includes('Chrome')) return 'Safari';
    if (userAgent.includes('Edge')) return 'Edge';
    if (userAgent.includes('Opera')) return 'Opera';
    
    return 'غير معروف';
  }

  getOSInfo(userAgent) {
    if (!userAgent) return 'غير معروف';

    if (userAgent.includes('Windows')) return 'Windows';
    if (userAgent.includes('Mac')) return 'macOS';
    if (userAgent.includes('Linux')) return 'Linux';
    if (userAgent.includes('Android')) return 'Android';
    if (userAgent.includes('iOS')) return 'iOS';
    
    return 'غير معروف';
  }

  formatTime(timestamp) {
    if (!timestamp) return 'غير محدد';
    
    try {
      const date = new Date(timestamp);
      return date.toLocaleTimeString('ar-SA', {
        hour: '2-digit',
        minute: '2-digit',
        second: '2-digit'
      });
    } catch (error) {
      return 'غير صحيح';
    }
  }

  destroy() {
    this.stopAutoRefresh();
    console.log('تم تدمير نظام مراقبة الزوار النشطين');
  }
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

// تشغيل النظام عند تحميل الصفحة
document.addEventListener('DOMContentLoaded', function() {
  // تهيئة أزرار التبديل
  initViewToggle();
  
  // تهيئة نظام المراقبة إذا كانت الصفحة تحتوي على العناصر المطلوبة
  if (document.getElementById('visitors-cards-container') || document.getElementById('visitors-table')) {
    window.activeVisitorsMonitor = new ActiveVisitorsMonitor();
  }
});

// تصدير للاستخدام العام
window.ActiveVisitorsMonitor = ActiveVisitorsMonitor;
window.initViewToggle = initViewToggle;
