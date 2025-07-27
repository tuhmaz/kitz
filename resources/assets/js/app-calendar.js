/**
 * App Calendar - Unified Version
 * Combines dashboard calendar functionality with main calendar features
 */

'use strict';

import { Calendar } from '@fullcalendar/core';
import dayGridPlugin from '@fullcalendar/daygrid';
import interactionPlugin from '@fullcalendar/interaction';
import listPlugin from '@fullcalendar/list';
import timeGridPlugin from '@fullcalendar/timegrid';
import Swal from 'sweetalert2';
import moment from 'moment'; // Import moment.js

// Statistics Functions
function updateStatistics(events) {
  const today = new Date();
  const currentMonth = today.getMonth();
  const currentYear = today.getFullYear();
  const todayStr = today.toISOString().split('T')[0];
  
  // Count events for this month
  const monthlyEvents = events.filter(event => {
    const eventDate = new Date(event.start);
    return eventDate.getMonth() === currentMonth && eventDate.getFullYear() === currentYear;
  });
  
  // Count events for today
  const todayEvents = events.filter(event => {
    return event.start === todayStr;
  });
  
  // Count upcoming events (excluding today)
  const upcomingEvents = events.filter(event => {
    return new Date(event.start) > today;
  });
  
  // Update the UI
  updateStatisticsUI({
    monthly: monthlyEvents.length,
    today: todayEvents.length,
    upcoming: upcomingEvents.length
  });
}

function updateStatisticsUI(stats) {
  const monthlyCount = document.getElementById('monthly-events-count');
  const todayCount = document.getElementById('today-events-count');
  const upcomingCount = document.getElementById('upcoming-events-count');
  
  // Add visual feedback to the cards
  const monthlyCard = monthlyCount?.closest('.stats-card');
  const todayCard = todayCount?.closest('.stats-card');
  const upcomingCard = upcomingCount?.closest('.stats-card');
  
  if (monthlyCount) {
    if (monthlyCard) monthlyCard.classList.add('stats-updating');
    animateCounter(monthlyCount, stats.monthly);
    setTimeout(() => {
      if (monthlyCard) monthlyCard.classList.remove('stats-updating');
    }, 1600);
  }
  
  if (todayCount) {
    if (todayCard) todayCard.classList.add('stats-updating');
    animateCounter(todayCount, stats.today);
    setTimeout(() => {
      if (todayCard) todayCard.classList.remove('stats-updating');
    }, 1600);
  }
  
  if (upcomingCount) {
    if (upcomingCard) upcomingCard.classList.add('stats-updating');
    animateCounter(upcomingCount, stats.upcoming);
    setTimeout(() => {
      if (upcomingCard) upcomingCard.classList.remove('stats-updating');
    }, 1600);
  }
}

function animateCounter(element, targetValue) {
  const startValue = 0;
  const duration = 1500; // 1.5 seconds for smoother animation
  const startTime = Date.now();
  
  // Add updating class for visual feedback
  element.classList.add('stats-updating');
  
  function updateCounter() {
    const elapsed = Date.now() - startTime;
    const progress = Math.min(elapsed / duration, 1);
    
    // Use easing function for smoother animation
    const easeOutQuart = 1 - Math.pow(1 - progress, 4);
    const currentValue = Math.floor(startValue + (targetValue - startValue) * easeOutQuart);
    
    element.textContent = currentValue;
    
    if (progress < 1) {
      requestAnimationFrame(updateCounter);
    } else {
      // Remove updating class when animation is complete
      setTimeout(() => {
        element.classList.remove('stats-updating');
      }, 100);
    }
  }
  
  if (targetValue > 0) {
    updateCounter();
  } else {
    // If target is 0, just set it directly
    element.textContent = '0';
    setTimeout(() => {
      element.classList.remove('stats-updating');
    }, 100);
  }
}

// Utility Functions from dashboard-calendar.js
function showNotification(message, type = 'info') {
  const notification = document.createElement('div');
  notification.className = `alert alert-${type === 'error' ? 'danger' : type} alert-dismissible fade show position-fixed`;
  notification.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';

  let iconClass = 'bx-info-circle';
  if (type === 'success') {
    iconClass = 'bx-check-circle';
  } else if (type === 'error') {
    iconClass = 'bx-error-circle';
  }

  notification.innerHTML = `
    <div class="d-flex align-items-center">
      <i class="bx ${iconClass} me-2"></i>
      <span>${message}</span>
      <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
    </div>
  `;

  document.body.appendChild(notification);

  setTimeout(() => {
    if (notification.parentNode) {
      notification.remove();
    }
  }, 5000);
}

function updatePreview() {
  const eventTitle = document.getElementById('eventTitle');
  const eventDescription = document.getElementById('eventDescription');
  const eventStartDate = document.getElementById('eventStartDate');
  const eventPreview = document.getElementById('eventPreview');
  const previewTitle = document.getElementById('previewTitle');
  const previewDescription = document.getElementById('previewDescription');
  const previewDate = document.getElementById('previewDate');

  if (!eventTitle || !eventDescription || !eventStartDate || !eventPreview) {
    return;
  }

  const hasContent = eventTitle.value.trim() || eventDescription.value.trim() || eventStartDate.value;

  if (hasContent) {
    eventPreview.classList.remove('d-none');

    previewTitle.textContent = eventTitle.value.trim() || 'عنوان الحدث';
    previewDescription.textContent = eventDescription.value.trim() || 'وصف الحدث';

    if (eventStartDate.value) {
      const date = new Date(eventStartDate.value);
      const options = {
        year: 'numeric',
        month: 'long',
        day: 'numeric',
        weekday: 'long'
      };
      previewDate.textContent = date.toLocaleDateString('ar-SA', options);
    } else {
      previewDate.textContent = 'تاريخ الحدث';
    }
  } else {
    eventPreview.classList.add('d-none');
  }
}

function validateForm() {
  const eventTitle = document.getElementById('eventTitle');
  const eventStartDate = document.getElementById('eventStartDate');
  
  if (!eventTitle || !eventStartDate) {
    return false;
  }

  let isValid = true;
  const requiredFields = [eventTitle, eventStartDate];

  requiredFields.forEach(field => {
    const value = field.value.trim();
    const isFieldValid = value !== '';

    if (isFieldValid) {
      field.classList.add('is-valid');
      field.classList.remove('is-invalid');
    } else {
      field.classList.remove('is-valid');
      field.classList.add('is-invalid');
      isValid = false;
    }
  });

  return isValid;
}

function setupCharacterCounters() {
  const eventTitle = document.getElementById('eventTitle');
  const eventDescription = document.getElementById('eventDescription');

  if (eventTitle) {
    const titleCounter = document.createElement('div');
    titleCounter.className = 'form-text text-muted small mt-1';
    titleCounter.innerHTML = '<span id="titleCount">0</span>/100 حرف';
    eventTitle.parentNode.appendChild(titleCounter);

    eventTitle.addEventListener('input', function() {
      const count = this.value.length;
      const titleCountElement = document.getElementById('titleCount');
      if (titleCountElement) {
        titleCountElement.textContent = count;
      }

      if (count > 80) {
        titleCounter.classList.add('text-warning');
      } else {
        titleCounter.classList.remove('text-warning');
      }

      if (count >= 100) {
        titleCounter.classList.add('text-danger');
        this.value = this.value.substring(0, 100);
      } else {
        titleCounter.classList.remove('text-danger');
      }

      updatePreview();
    });
  }

  if (eventDescription) {
    const descCounter = document.createElement('div');
    descCounter.className = 'form-text text-muted small mt-1';
    descCounter.innerHTML = '<span id="descCount">0</span>/500 حرف';
    eventDescription.parentNode.appendChild(descCounter);

    eventDescription.addEventListener('input', function() {
      const count = this.value.length;
      const descCountElement = document.getElementById('descCount');
      if (descCountElement) {
        descCountElement.textContent = count;
      }

      if (count > 400) {
        descCounter.classList.add('text-warning');
      } else {
        descCounter.classList.remove('text-warning');
      }

      if (count >= 500) {
        descCounter.classList.add('text-danger');
        this.value = this.value.substring(0, 500);
      } else {
        descCounter.classList.remove('text-danger');
      }

      updatePreview();
    });
  }
}

function setupKeyboardShortcuts() {
  document.addEventListener('keydown', function(e) {
    const addEventSidebar = document.getElementById('addEventSidebar');
    
    if (e.ctrlKey && e.key === 's') {
      e.preventDefault();
      if (!addEventSidebar || !addEventSidebar.classList.contains('show')) return;

      const submitBtn = document.querySelector('.btn-add-event, .btn-update-event');
      if (submitBtn && !submitBtn.disabled) {
        submitBtn.click();
      }
    }

    if (e.key === 'Escape') {
      if (addEventSidebar && addEventSidebar.classList.contains('show')) {
        const closeBtn = document.querySelector('[data-bs-dismiss="offcanvas"]');
        if (closeBtn) closeBtn.click();
      }
    }
  });
}

function initializeTooltips() {
  const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
  tooltipTriggerList.map(function(tooltipTriggerEl) {
    return new bootstrap.Tooltip(tooltipTriggerEl);
  });
}

document.addEventListener('DOMContentLoaded', function () {
  (function () {
    const calendarEl = document.getElementById('calendar');
    const addEventSidebar = document.getElementById('addEventSidebar');
    const eventForm = document.getElementById('eventForm');
    const databaseSelect = document.getElementById('mainDatabaseSelector');
    
    // التحقق من وجود العناصر المطلوبة
    if (!calendarEl) {
      console.error('Calendar element not found');
      return;
    }
    
    if (!databaseSelect) {
      console.error('Database selector not found');
      return;
    }
    
    // Initialize date picker
    const eventDatePicker = flatpickr('#eventStartDate', {
      enableTime: false,
      dateFormat: 'Y-m-d',
      // تجنب مشاكل المنطقة الزمنية
      utc: false,
      allowInput: true,
      // تعيين التاريخ بتنسيق محلي
      parseDate: (datestr, format) => {
        if (!datestr) return null;
        const parts = datestr.split('-');
        if (parts.length === 3) {
          return new Date(parseInt(parts[0]), parseInt(parts[1]) - 1, parseInt(parts[2]));
        }
        return null;
      },
      formatDate: (date, format) => {
        if (!date) return '';
        const year = date.getFullYear();
        const month = String(date.getMonth() + 1).padStart(2, '0');
        const day = String(date.getDate()).padStart(2, '0');
        return `${year}-${month}-${day}`;
      }
    });

    // Function to get events for selected database
    const fetchEvents = (database) => {
      // إضافة timestamp لتجنب التخزين المؤقت
      return fetch(`/dashboard/calendar-events?database=${database}&_=${new Date().getTime()}`)
        .then(response => response.json())
        .then(result => {
          if (result.status === 'success') {
            calendar.removeAllEvents();
            calendar.addEventSource(result.data);
            
            // تحديث الإحصائيات
            updateStatistics(result.data);
          }
        })
        .catch(error => {
          console.error('Error fetching events:', error);
          Swal.fire({
            icon: 'error',
            title: 'خطأ',
            text: 'حدث خطأ أثناء جلب الأحداث'
          });
        });
    };

    // Handle database selection change
    databaseSelect.addEventListener('change', function() {
      const selectedDatabase = this.value;
      fetchEvents(selectedDatabase);
      
      // تحديث قاعدة البيانات في النموذج
      const eventDatabaseSelect = document.getElementById('eventDatabase');
      if (eventDatabaseSelect) {
        eventDatabaseSelect.value = selectedDatabase;
      }
      
      // تحديث اسم قاعدة البيانات في الإحصائيات
      const currentDatabaseName = document.getElementById('current-database-name');
      if (currentDatabaseName) {
        const databaseNames = {
          'jo': '🇯🇴 الأردن',
          'sa': '🇸🇦 السعودية',
          'ae': '🇦🇪 الإمارات',
          'bh': '🇧🇭 البحرين',
          'kw': '🇰🇼 الكويت',
          'om': '🇴🇲 عمان',
          'qa': '🇶🇦 قطر',
          'eg': '🇪🇬 مصر',
          'ps': '🇵🇸 فلسطين'
        };
        currentDatabaseName.textContent = databaseNames[selectedDatabase] || selectedDatabase;
      }
    });

    // Initialize the calendar
    const calendar = new Calendar(calendarEl, {
      plugins: [dayGridPlugin, timeGridPlugin, interactionPlugin, listPlugin],
      initialView: 'dayGridMonth',
      firstDay: 6,
      height: 800,
      selectable: true,
      editable: true,
      dayMaxEvents: 2,
      eventResizableFromStart: true,
      // إعدادات المنطقة الزمنية لتجنب مشاكل التحويل
      timeZone: 'local',
      forceEventDuration: false,
      displayEventTime: false, // عدم عرض الأوقات
      displayEventEnd: false, // عدم عرض أوقات الانتهاء
      headerToolbar: {
        start: 'prev,next today',
        center: 'title',
        end: 'dayGridMonth,timeGridWeek,timeGridDay,listMonth'
      },
      events: function(info, successCallback, failureCallback) {
        const database = databaseSelect.value;
        // إضافة timestamp لتجنب التخزين المؤقت
        fetch(`/dashboard/calendar-events?database=${database}&_=${new Date().getTime()}`)
          .then(response => response.json())
          .then(result => {
            if (result.status === 'success') {
              // تسجيل البيانات المستلمة لمراقبة التواريخ
              console.log('Raw events from server:', result.data);
              
              // معالجة التواريخ لضمان عدم تحويلها
              const processedEvents = result.data.map(event => {
                console.log('Processing event:', event.title, 'Original start:', event.start);
                return {
                  ...event,
                  // التأكد من أن التاريخ يبقى كما هو بدون تحويل
                  start: event.start, // استخدام التاريخ كما هو من الخادم
                  allDay: true // التأكيد على أنه حدث يومي كامل
                };
              });
              
              console.log('Processed events for calendar:', processedEvents);
              
              // تحديث الإحصائيات
              updateStatistics(processedEvents);
              
              successCallback(processedEvents);
            } else {
              failureCallback(new Error(result.message));
            }
          })
          .catch(error => {
            failureCallback(error);
          });
      },
      select: function(info) {
        // تحويل تاريخ الاختيار إلى تنسيق YYYY-MM-DD بدون مشاكل زمنية
        const selectedDate = new Date(info.start);
        const dateString = `${selectedDate.getFullYear()}-${String(selectedDate.getMonth() + 1).padStart(2, '0')}-${String(selectedDate.getDate()).padStart(2, '0')}`;
        
        // تعيين التاريخ في الحقل مباشرة
        document.getElementById('eventStartDate').value = dateString;
        // تحديث flatpickr
        eventDatePicker.setDate(dateString, false);
        
        const offcanvas = new bootstrap.Offcanvas(addEventSidebar);
        offcanvas.show();
      },
      eventClick: function(info) {
        const event = info.event;
        
        // تحديث قيم النموذج
        document.getElementById('eventTitle').value = event.title;
        document.getElementById('eventDescription').value = event.extendedProps.description || '';
        document.getElementById('eventDatabase').value = event.extendedProps.database;
        
        // تحديث التاريخ - استخدام التاريخ مباشرة بدون تحويل
        let eventDate;
        if (typeof event.start === 'string') {
          // إذا كان التاريخ نصاً، استخدمه مباشرة
          eventDate = event.start.split('T')[0]; // أخذ الجزء الخاص بالتاريخ فقط
        } else {
          // إذا كان كائن Date، حوله بحذر
          const date = new Date(event.start);
          eventDate = `${date.getFullYear()}-${String(date.getMonth() + 1).padStart(2, '0')}-${String(date.getDate()).padStart(2, '0')}`;
        }
        
        // تعيين التاريخ في الحقل مباشرة
        document.getElementById('eventStartDate').value = eventDate;
        // تحديث flatpickr أيضاً
        eventDatePicker.setDate(eventDate, false);

        // تحديث زر الإرسال
        const submitBtn = document.querySelector('.btn-add-event');
        submitBtn.textContent = 'تحديث';
        submitBtn.classList.remove('btn-add-event');
        submitBtn.classList.add('btn-update-event');
        submitBtn.dataset.eventId = event.id;

        // إظهار زر الحذف
        const deleteBtn = document.querySelector('.btn-delete-event');
        deleteBtn.classList.remove('d-none');
        
        // فتح النافذة الجانبية
        const offcanvas = new bootstrap.Offcanvas(addEventSidebar);
        offcanvas.show();
      }
    });

    // Form submit handler with enhanced validation
    eventForm.addEventListener('submit', function(e) {
      e.preventDefault();
      
      // Enhanced form validation
      if (!validateForm()) {
        showNotification('يرجى ملء جميع الحقول المطلوبة', 'error');
        return;
      }
      
      const submitBtn = document.querySelector('.btn-add-event, .btn-update-event');
      const isUpdate = submitBtn.classList.contains('btn-update-event');
      
      // Enhanced loading state
      const originalText = submitBtn.innerHTML;
      submitBtn.innerHTML = '<i class="bx bx-loader-alt bx-spin me-2"></i>' + (isUpdate ? 'جاري التحديث...' : 'جاري الحفظ...');
      submitBtn.disabled = true;
      
      // الحصول على التاريخ بتنسيق YYYY-MM-DD بدون تحويل زمني
      let eventDate;
      const inputValue = document.getElementById('eventStartDate').value;
      
      if (inputValue) {
        // استخدام قيمة الحقل مباشرة لتجنب مشاكل المنطقة الزمنية
        eventDate = inputValue;
      } else {
        // في حالة عدم وجود قيمة، استخدام تاريخ اليوم
        const today = new Date();
        eventDate = `${today.getFullYear()}-${String(today.getMonth() + 1).padStart(2, '0')}-${String(today.getDate()).padStart(2, '0')}`;
      }
      
      const formData = {
        title: document.getElementById('eventTitle').value,
        description: document.getElementById('eventDescription').value,
        event_date: eventDate,
        eventDatabase: document.getElementById('eventDatabase').value
      };

      const url = isUpdate 
        ? `/dashboard/calendar/${submitBtn.dataset.eventId}?database=${formData.eventDatabase}`
        : '/dashboard/calendar/store';

      fetch(url, {
        method: isUpdate ? 'PUT' : 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify(formData)
      })
      .then(response => response.json())
      .then(data => {
        if (data.status === 'success') {
          if (isUpdate) {
            const existingEvent = calendar.getEventById(data.data.id);
            if (existingEvent) {
              existingEvent.remove();
            }
          }

          calendar.addEvent({
            id: data.data.id,
            title: data.data.title,
            start: data.data.start,
            allDay: true,
            extendedProps: {
              description: data.data.extendedProps.description,
              database: data.data.extendedProps.database
            }
          });

          // Reset button state
          submitBtn.innerHTML = originalText;
          submitBtn.disabled = false;
          
          bootstrap.Offcanvas.getInstance(addEventSidebar).hide();
          eventForm.reset();
          
          // Clear validation classes
          document.querySelectorAll('.is-valid, .is-invalid').forEach(el => {
            el.classList.remove('is-valid', 'is-invalid');
          });
          
          // Hide preview
          const eventPreview = document.getElementById('eventPreview');
          if (eventPreview) {
            eventPreview.classList.add('d-none');
          }

          // Use enhanced notification instead of SweetAlert
          showNotification(isUpdate ? 'تم تحديث الحدث بنجاح!' : 'تم إضافة الحدث بنجاح!', 'success');
          
          // تحديث الإحصائيات بعد إضافة/تحديث الحدث
          const database = document.getElementById('eventDatabase').value;
          fetchEvents(database);
        } else {
          throw new Error(data.message || 'حدث خطأ غير معروف');
        }
      })
      .catch(error => {
        console.error('Error:', error);
        
        // Reset button state on error
        submitBtn.innerHTML = originalText;
        submitBtn.disabled = false;
        
        // Use enhanced notification for errors
        showNotification(error.message || 'حدث خطأ أثناء حفظ الحدث', 'error');
      });
    });

    // Delete event handler
    document.querySelector('.btn-delete-event').addEventListener('click', function(e) {
      e.preventDefault();
      e.stopPropagation();
      
      const eventId = document.querySelector('.btn-update-event').dataset.eventId;
      const database = document.getElementById('eventDatabase').value;
      
      Swal.fire({
        title: 'هل أنت متأكد؟',
        text: "لا يمكن التراجع عن هذا الإجراء!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'نعم، احذف!',
        cancelButtonText: 'إلغاء',
        showLoaderOnConfirm: true,
        allowOutsideClick: false,
        allowEscapeKey: false,
        allowEnterKey: false,
        focusConfirm: false,
        focusCancel: true,
        preConfirm: () => {
          return new Promise((resolve, reject) => {
            fetch(`/dashboard/calendar/${eventId}?database=${database}`, {
              method: 'DELETE',
              headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
              }
            })
            .then(response => response.json())
            .then(data => {
              if (data.status === 'success') {
                resolve(data);
              } else {
                reject(new Error(data.message || 'حدث خطأ أثناء الحذف'));
              }
            })
            .catch(error => {
              reject(error);
            });
          }).catch(error => {
            Swal.showValidationMessage(error.message);
            throw error;
          });
        }
      }).then((result) => {
        if (result.isConfirmed) {
          const event = calendar.getEventById(eventId);
          if (event) {
            event.remove();
          }
          
          bootstrap.Offcanvas.getInstance(addEventSidebar).hide();
          
          // تحديث الأحداث بعد الحذف
          fetchEvents(database);
          
          Swal.fire({
            icon: 'success',
            title: 'تم الحذف!',
            text: 'تم حذف الحدث بنجاح.',
            timer: 1500,
            showConfirmButton: false
          });
        }
      }).catch(() => {
        // تم معالجة الخطأ في preConfirm
      });
    });

    // Reset form when sidebar is hidden
    addEventSidebar.addEventListener('hidden.bs.offcanvas', function() {
      eventForm.reset();
      const submitBtn = document.querySelector('.btn-update-event');
      if (submitBtn) {
        submitBtn.classList.remove('btn-update-event');
        submitBtn.classList.add('btn-add-event');
        submitBtn.textContent = 'إضافة';
      }
      document.querySelector('.btn-delete-event').classList.add('d-none');

      // Set the default database
      document.getElementById('eventDatabase').value = databaseSelect.value;
    });

    // Set initial database value
    document.getElementById('eventDatabase').value = databaseSelect.value;

    // Initialize enhanced features from dashboard-calendar.js
    setupCharacterCounters();
    setupKeyboardShortcuts();
    initializeTooltips();

    // Setup additional event listeners for enhanced functionality
    const eventTitle = document.getElementById('eventTitle');
    const eventDescription = document.getElementById('eventDescription');
    const eventStartDate = document.getElementById('eventStartDate');

    if (eventTitle) {
      eventTitle.addEventListener('input', updatePreview);
    }

    if (eventDescription) {
      eventDescription.addEventListener('input', updatePreview);
    }

    if (eventStartDate) {
      eventStartDate.addEventListener('change', updatePreview);
    }

    // Enhanced offcanvas behavior
    if (addEventSidebar) {
      addEventSidebar.addEventListener('shown.bs.offcanvas', function() {
        if (eventTitle) {
          eventTitle.focus();
        }
        updatePreview();
      });
    }

    // Enhanced animations
    const observerOptions = {
      threshold: 0.1,
      rootMargin: '0px 0px -50px 0px'
    };

    const observer = new IntersectionObserver(function(entries) {
      entries.forEach(entry => {
        if (entry.isIntersecting) {
          entry.target.classList.add('animate__animated', 'animate__fadeInUp');
        }
      });
    }, observerOptions);

    document.querySelectorAll('.stats-card, .calendar-card, .calendar-sidebar').forEach(card => {
      observer.observe(card);
    });

    // Render calendar
    calendar.render();
    
    // تهيئة الإحصائيات بقيم افتراضية
    updateStatisticsUI({
      monthly: 0,
      today: 0,
      upcoming: 0
    });
    
    // تحميل الأحداث الأولية بعد عرض التقويم
    setTimeout(() => {
      const initialDatabase = databaseSelect.value;
      if (initialDatabase) {
        fetchEvents(initialDatabase);
      }
    }, 100);
  })();
});
