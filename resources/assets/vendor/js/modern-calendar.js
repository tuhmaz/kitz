/* Modern Interactive Calendar JavaScript */

class ModernCalendar {
  constructor(containerId, options = {}) {
    this.container = document.getElementById(containerId);
    this.currentDate = new Date();
    this.selectedDate = null;
    this.events = [];
    this.options = {
      locale: 'ar',
      showEventDetails: true,
      maxEventsPerDay: 3,
      ...options
    };

    this.monthNames = [
      'يناير', 'فبراير', 'مارس', 'أبريل', 'مايو', 'يونيو',
      'يوليو', 'أغسطس', 'سبتمبر', 'أكتوبر', 'نوفمبر', 'ديسمبر'
    ];

    this.dayNames = [
      'الأحد', 'الاثنين', 'الثلاثاء', 'الأربعاء', 'الخميس', 'الجمعة', 'السبت'
    ];

    this.init();
  }

  init() {
    if (!this.container) return;

    this.render();
    this.loadEvents();
    this.attachEventListeners();
  }

  render() {
    this.container.innerHTML = `
      <div class="edu-calendar-card">
        <div class="edu-calendar-header">
          <div class="d-flex align-items-center">
            <i class="ti ti-calendar-event me-2"></i>
            <h5>التقويم التفاعلي</h5>
          </div>
        </div>
        <div class="edu-calendar-body">
          <div class="edu-calendar-nav">
            <button class="edu-nav-btn" id="prevMonth" aria-label="الشهر السابق">
              <i class="ti ti-chevron-right"></i>
            </button>
            <h6 class="edu-current-month" id="currentMonth"></h6>
            <button class="edu-nav-btn" id="nextMonth" aria-label="الشهر التالي">
              <i class="ti ti-chevron-left"></i>
            </button>
          </div>
          <div class="edu-calendar-grid">
            <div class="edu-calendar-days-header" id="daysHeader"></div>
            <div class="edu-calendar-days" id="calendarDays"></div>
          </div>
        </div>
      </div>
      <div class="edu-event-details" id="eventDetails" style="display: none;">
        <div class="edu-event-header">
          <h6 id="selectedDateTitle">أحداث اليوم</h6>
          <button class="edu-close-btn" id="closeDetails" aria-label="إغلاق">
            <i class="ti ti-x"></i>
          </button>
        </div>
        <div class="edu-event-list" id="eventList"></div>
      </div>
    `;

    this.updateCalendar();
  }

  attachEventListeners() {
    this.container.querySelector('#prevMonth').addEventListener('click', () => {
      this.currentDate.setMonth(this.currentDate.getMonth() - 1);
      this.updateCalendar();
    });

    this.container.querySelector('#nextMonth').addEventListener('click', () => {
      this.currentDate.setMonth(this.currentDate.getMonth() + 1);
      this.updateCalendar();
    });

    this.container.querySelector('#closeDetails').addEventListener('click', () => {
      this.hideEventDetails();
    });

    document.addEventListener('keydown', (e) => {
      if (e.key === 'Escape') this.hideEventDetails();
    });
  }

  updateCalendar() {
    this.renderDaysHeader();
    this.renderCalendarDays();
    this.updateCurrentMonthDisplay();
  }

  renderDaysHeader() {
    const daysHeader = this.container.querySelector('#daysHeader');
    daysHeader.innerHTML = this.dayNames.map(day => `<div class="edu-day-header">${day}</div>`).join('');
  }

  renderCalendarDays() {
    const calendarDays = this.container.querySelector('#calendarDays');
    const year = this.currentDate.getFullYear();
    const month = this.currentDate.getMonth();
    const firstDay = new Date(year, month, 1);
    const startDate = new Date(firstDay);
    startDate.setDate(startDate.getDate() - firstDay.getDay());
    const days = [];
    const currentDate = new Date(startDate);
    for (let i = 0; i < 42; i++) {
      days.push(new Date(currentDate));
      currentDate.setDate(currentDate.getDate() + 1);
    }
    calendarDays.innerHTML = days.map(date => this.renderDay(date, month)).join('');
    days.forEach((date, index) => {
      const dayEvents = this.getEventsForDate(date);
      if (dayEvents.length > 0) {
        const dayElement = calendarDays.children[index];
        dayElement.addEventListener('click', () => {
          this.showEventDetails(date, dayEvents);
        });
        dayElement.addEventListener('keydown', (e) => {
          if (e.key === 'Enter' || e.key === ' ') {
            e.preventDefault();
            this.showEventDetails(date, dayEvents);
          }
        });
      }
    });
  }

  renderDay(date, currentMonth) {
    const today = new Date();
    const isToday = ModernCalendar.isSameDate(date, today);
    const isCurrentMonth = date.getMonth() === currentMonth;
    const dayEvents = this.getEventsForDate(date);
    const hasEvents = dayEvents.length > 0;
    const classes = [
      'edu-calendar-day',
      isToday ? 'today' : '',
      !isCurrentMonth ? 'other-month' : '',
      hasEvents ? 'has-events' : ''
    ].filter(Boolean).join(' ');
    const eventIndicators = this.renderEventIndicators(dayEvents);
    return `
      <div class="${classes}"
        ${hasEvents ? 'tabindex="0" role="button"' : ''}
        ${hasEvents ? `aria-label="يوم ${date.getDate()} - ${dayEvents.length} حدث"` : ''}>
        <span class="edu-day-number">${date.getDate()}</span>
        ${eventIndicators}
      </div>
    `;
  }

  renderEventIndicators(events) {
    if (events.length === 0) return '';
    const maxDots = Math.min(events.length, this.options.maxEventsPerDay);
    const dots = Array.from({ length: maxDots }).map(() => '<div class="edu-event-dot"></div>').join('');
    const moreCount = events.length - maxDots;
    const moreIndicator = moreCount > 0 ? `<div class="edu-event-more">+${moreCount}</div>` : '';
    return `<div class="edu-event-indicators">${dots}${moreIndicator}</div>`;
  }

  updateCurrentMonthDisplay() {
    const currentMonth = this.container.querySelector('#currentMonth');
    currentMonth.textContent = `${this.monthNames[this.currentDate.getMonth()]} ${this.currentDate.getFullYear()}`;
  }

  showEventDetails(date, events) {
    this.selectedDate = date;
    const eventDetails = this.container.querySelector('#eventDetails');
    const selectedDateTitle = this.container.querySelector('#selectedDateTitle');
    const eventList = this.container.querySelector('#eventList');
    selectedDateTitle.textContent = `أحداث يوم ${ModernCalendar.formatDate(date)}`;
    eventList.innerHTML = events.map(event => this.renderEventItem(event)).join('');
    eventDetails.style.display = 'block';
    setTimeout(() => {
      eventDetails.style.opacity = '1';
      eventDetails.style.transform = 'translateY(0)';
    }, 10);
  }

  hideEventDetails() {
    const eventDetails = this.container.querySelector('#eventDetails');
    eventDetails.style.opacity = '0';
    eventDetails.style.transform = 'translateY(20px)';
    setTimeout(() => {
      eventDetails.style.display = 'none';
    }, 300);
  }

  renderEventItem(event) {
    const time = event.start_time ? ModernCalendar.formatTime(event.start_time) : '';
    const timeHtml = time ? `<div class="edu-event-time"><i class="ti ti-clock"></i>${time}</div>` : '';
    return `
      <div class="edu-event-item">
        <div class="edu-event-title">${event.title}</div>
        ${event.description ? `<div class="edu-event-description">${event.description}</div>` : ''}
        ${timeHtml}
      </div>
    `;
  }

  async loadEvents() {
    // إظهار حالة التحميل
    this.showLoadingState();
    
    try {
      const currentDatabase = ModernCalendar.getCurrentDatabase();
      const year = this.currentDate.getFullYear();
      const month = this.currentDate.getMonth() + 1;
      
      // إنشاء URL مع معاملات محسنة
      const url = new URL('/dashboard/calendar-events', window.location.origin);
      url.searchParams.set('year', year);
      url.searchParams.set('month', month);
      url.searchParams.set('database', currentDatabase);
      url.searchParams.set('_t', Date.now()); // منع التخزين المؤقت
      
      // إعداد الطلب مع timeout
      const controller = new AbortController();
      const timeoutId = setTimeout(() => controller.abort(), 10000); // 10 ثوان timeout
      
      const response = await fetch(url.toString(), {
        method: 'GET',
        headers: {
          'Accept': 'application/json',
          'Content-Type': 'application/json',
          'X-Requested-With': 'XMLHttpRequest'
        },
        signal: controller.signal
      });
      
      clearTimeout(timeoutId);
      
      if (!response.ok) {
        throw new Error(`HTTP ${response.status}: ${response.statusText}`);
      }
      
      const result = await response.json();
      
      // التحقق من بنية الاستجابة
      if (!result || typeof result !== 'object') {
        throw new Error('Invalid response format');
      }
      
      // معالجة البيانات حسب تنسيق الاستجابة
      let eventsData;
      if (result.status === 'success' && result.data) {
        eventsData = Array.isArray(result.data) ? result.data : [];
      } else if (Array.isArray(result)) {
        eventsData = result;
      } else {
        console.warn('تنسيق البيانات المستلمة غير متوقع:', result);
        eventsData = [];
      }
      
      // التحقق من صحة البيانات
      if (!Array.isArray(eventsData)) {
        console.warn('البيانات المستلمة ليست مصفوفة:', eventsData);
        this.events = [];
      } else {
        // تحويل وتنظيف البيانات
        this.events = eventsData
          .filter(event => event && event.id && event.title && event.start) // تصفية البيانات غير الصحيحة
          .map(event => ({
            id: event.id,
            title: String(event.title).trim(),
            description: this.extractDescription(event),
            start: event.start, // هذا يأتي من event_date وليس created_at
            start_time: this.extractStartTime(event),
            end_time: this.extractEndTime(event)
          }));
      }
      
      // تسجيل نجاح العملية
      console.log(`تم تحميل ${this.events.length} حدث لشهر ${month}/${year} من قاعدة البيانات ${currentDatabase}`);
      
      // تحديث التقويم
      this.updateCalendar();
      
      // إخفاء حالة التحميل
      this.hideLoadingState();
      
    } catch (error) {
      console.error('خطأ في تحميل الأحداث:', error);
      
      // معالجة أنواع الأخطاء المختلفة
      let errorMessage = 'حدث خطأ أثناء تحميل الأحداث';
      
      if (error.name === 'AbortError') {
        errorMessage = 'انتهت مهلة تحميل الأحداث';
      } else if (error.message.includes('HTTP')) {
        errorMessage = 'خطأ في الاتصال بالخادم';
      } else if (error.message.includes('Failed to fetch')) {
        errorMessage = 'تعذر الاتصال بالخادم';
      }
      
      // إظهار رسالة خطأ للمستخدم
      this.showErrorMessage(errorMessage);
      
      // تعيين قائمة فارغة من الأحداث
      this.events = [];
      this.updateCalendar();
      
      // إخفاء حالة التحميل
      this.hideLoadingState();
    }
  }

  static getCurrentDatabase() {
    const selector = document.getElementById('database-selector');
    return selector ? selector.value : 'jo';
  }

  // دوال مساعدة لاستخراج البيانات من الأحداث
  extractDescription(event) {
    if (event.extendedProps && event.extendedProps.description) {
      return event.extendedProps.description;
    }
    if (event.description) {
      return event.description;
    }
    return '';
  }

  extractStartTime(event) {
    if (event.extendedProps && event.extendedProps.start_time) {
      return event.extendedProps.start_time;
    }
    if (event.start_time) {
      return event.start_time;
    }
    return null;
  }

  extractEndTime(event) {
    if (event.extendedProps && event.extendedProps.end_time) {
      return event.extendedProps.end_time;
    }
    if (event.end_time) {
      return event.end_time;
    }
    return null;
  }

  // دوال إدارة حالة التحميل
  showLoadingState() {
    const calendar = this.container.querySelector('.edu-calendar-grid');
    if (calendar) {
      calendar.classList.add('loading');
      
      // إضافة مؤشر تحميل إذا لم يكن موجوداً
      let loader = this.container.querySelector('.edu-loading-spinner');
      if (!loader) {
        loader = document.createElement('div');
        loader.className = 'edu-loading-spinner';
        loader.innerHTML = `
          <div class="spinner-border text-primary" role="status">
            <span class="visually-hidden">جاري التحميل...</span>
          </div>
          <div class="mt-2 text-muted">جاري تحميل الأحداث...</div>
        `;
        calendar.appendChild(loader);
      }
      loader.style.display = 'flex';
    }
  }

  hideLoadingState() {
    const calendar = this.container.querySelector('.edu-calendar-grid');
    if (calendar) {
      calendar.classList.remove('loading');
      
      const loader = this.container.querySelector('.edu-loading-spinner');
      if (loader) {
        loader.style.display = 'none';
      }
    }
  }

  showErrorMessage(message) {
    // إزالة رسائل الخطأ السابقة
    const existingError = this.container.querySelector('.edu-error-message');
    if (existingError) {
      existingError.remove();
    }

    // إنشاء رسالة خطأ جديدة
    const errorDiv = document.createElement('div');
    errorDiv.className = 'edu-error-message alert alert-warning d-flex align-items-center';
    errorDiv.innerHTML = `
      <i class="ti ti-alert-triangle me-2"></i>
      <div>
        <strong>تنبيه:</strong> ${message}
        <button type="button" class="btn-close ms-auto" aria-label="إغلاق"></button>
      </div>
    `;

    // إدراج الرسالة في أعلى التقويم
    const calendarHeader = this.container.querySelector('.edu-calendar-header');
    if (calendarHeader) {
      calendarHeader.insertAdjacentElement('afterend', errorDiv);
    } else {
      this.container.insertBefore(errorDiv, this.container.firstChild);
    }

    // إضافة مستمع لإغلاق الرسالة
    const closeBtn = errorDiv.querySelector('.btn-close');
    if (closeBtn) {
      closeBtn.addEventListener('click', () => {
        errorDiv.remove();
      });
    }

    // إزالة الرسالة تلقائياً بعد 5 ثوان
    setTimeout(() => {
      if (errorDiv.parentNode) {
        errorDiv.remove();
      }
    }, 5000);
  }

  getEventsForDate(date) {
    const dateString = ModernCalendar.formatDateForComparison(date);
    return this.events.filter(event => {
      const eventDate = new Date(event.start);
      return ModernCalendar.formatDateForComparison(eventDate) === dateString;
    });
  }

  static formatDateForComparison(date) {
    return `${date.getFullYear()}-${String(date.getMonth() + 1).padStart(2, '0')}-${String(date.getDate()).padStart(2, '0')}`;
  }

  static formatDate(date) {
    const monthNames = [
      'يناير', 'فبراير', 'مارس', 'أبريل', 'مايو', 'يونيو',
      'يوليو', 'أغسطس', 'سبتمبر', 'أكتوبر', 'نوفمبر', 'ديسمبر'
    ];
    return `${date.getDate()} ${monthNames[date.getMonth()]} ${date.getFullYear()}`;
  }

  static formatTime(timeString) {
    try {
      const time = new Date(`2000-01-01T${timeString}`);
      return time.toLocaleTimeString('ar', { hour: '2-digit', minute: '2-digit', hour12: true });
    } catch {
      return timeString;
    }
  }

  static isSameDate(date1, date2) {
    return (
      date1.getFullYear() === date2.getFullYear() &&
      date1.getMonth() === date2.getMonth() &&
      date1.getDate() === date2.getDate()
    );
  }

  refresh() {
    this.clearCache();
    this.loadEvents();
  }

  goToDate(date) {
    this.currentDate = new Date(date);
    this.updateCalendar();
    this.loadEvents();
  }

  goToToday() {
    this.currentDate = new Date();
    this.updateCalendar();
    this.loadEvents();
  }
  
  // إدارة التخزين المؤقت
  getCacheKey() {
    const database = ModernCalendar.getCurrentDatabase();
    const year = this.currentDate.getFullYear();
    const month = this.currentDate.getMonth() + 1;
    return `calendar_${database}_${year}_${month}`;
  }
  
  getCachedEvents() {
    try {
      const cacheKey = this.getCacheKey();
      const cached = localStorage.getItem(cacheKey);
      if (cached) {
        const data = JSON.parse(cached);
        // التحقق من عدم انتهاء صلاحية التخزين (5 دقائق)
        if (Date.now() - data.timestamp < 300000) {
          return data.events;
        }
      }
    } catch (error) {
      console.warn('خطأ في قراءة التخزين المؤقت:', error);
    }
    return null;
  }
  
  setCachedEvents(events) {
    try {
      const cacheKey = this.getCacheKey();
      const data = {
        events: events,
        timestamp: Date.now()
      };
      localStorage.setItem(cacheKey, JSON.stringify(data));
    } catch (error) {
      console.warn('خطأ في حفظ التخزين المؤقت:', error);
    }
  }
  
  clearCache() {
    try {
      const keys = Object.keys(localStorage);
      keys.forEach(key => {
        if (key.startsWith('calendar_')) {
          localStorage.removeItem(key);
        }
      });
    } catch (error) {
      console.warn('خطأ في مسح التخزين المؤقت:', error);
    }
  }
  
  // تحسين التحديث التلقائي
  setupAutoRefresh() {
    // إيقاف التحديث السابق إن وجد
    if (this.refreshInterval) {
      clearInterval(this.refreshInterval);
    }
    
    // تعيين تحديث تلقائي كل 5 دقائق
    this.refreshInterval = setInterval(() => {
      // التحديث فقط إذا كانت الصفحة مرئية
      if (!document.hidden) {
        this.loadEvents();
      }
    }, 300000); // 5 دقائق
  }
  
  // تنظيف الموارد
  destroy() {
    if (this.refreshInterval) {
      clearInterval(this.refreshInterval);
    }
    
    // إزالة مستمعي الأحداث
    document.removeEventListener('keydown', this.handleKeydown);
    document.removeEventListener('visibilitychange', this.handleVisibilityChange);
  }
}

document.addEventListener('DOMContentLoaded', () => {
  const container = document.getElementById('modern-calendar');
  if (container) {
    window.modernCalendar = new ModernCalendar('modern-calendar');
    const dbSelector = document.getElementById('database-selector');
    if (dbSelector) {
      dbSelector.addEventListener('change', () => {
        window.modernCalendar.refresh();
      });
    }
    setInterval(() => {
      window.modernCalendar.refresh();
    }, 300000);
  }
});

if (typeof module !== 'undefined' && module.exports) {
  module.exports = ModernCalendar;
}
