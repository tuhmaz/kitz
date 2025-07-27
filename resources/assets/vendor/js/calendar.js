/* global FullCalendar, Swal */

function initCalendar() {
  const calendarEl = document.getElementById('calendar')
  if (!calendarEl) return

  // Get CSRF token from meta tag
  const metaTag = document.querySelector('meta[name="csrf-token"]')
  const csrfToken = metaTag ? metaTag.getAttribute('content') : null

  // تهيئة التقويم
  const calendar = new FullCalendar.Calendar(calendarEl, {
    initialView: 'dayGridMonth',
    direction: 'rtl',
    locale: 'ar',
    height: 800,
    headerToolbar: {
      start: 'prev,next today',
      center: 'title',
      end: 'dayGridMonth,timeGridWeek,timeGridDay,listMonth'
    },
    firstDay: 6, // السبت
    buttonText: {
      today: 'اليوم',
      month: 'شهر',
      week: 'أسبوع',
      day: 'يوم',
      list: 'قائمة'
    },
    events: function fetchEvents(info, successCallback, failureCallback) {
      const databaseSelect = document.getElementById('select-database')
      const database = databaseSelect && databaseSelect.value ? databaseSelect.value : 'mysql'

      // تحديد ما إذا كان المستخدم في الواجهة الأمامية أو لوحة التحكم
      const isFrontend = window.location.pathname === '/' || window.location.pathname.startsWith('/frontend')
      const endpoint = isFrontend ? `/frontend/calendar-events` : `/dashboard/calendar-events`

      fetch(`${endpoint}?database=${database}&_=${new Date().getTime()}`, {
        method: 'GET',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': csrfToken
        }
      })
        .then(response => response.json())
        .then(result => {
          if (result.status === 'success') {
            // تحويل البيانات إلى تنسيق FullCalendar
            const events = []
            if (isFrontend) {
              // تنسيق الواجهة الأمامية (قائمة مسطحة)
              if (Array.isArray(result.data)) {
                result.data.forEach(event => {
                  const eventData = {
                    id: event.id,
                    title: event.title || 'حدث بدون عنوان',
                    start: event.start || event.event_date,
                    allDay: true,
                    extendedProps: {
                      description: event.description || 'لا يوجد وصف',
                      database: event.database || null
                    }
                  }

                  // دعم للتنسيق القديم مع extendedProps
                  if (event.extendedProps) {
                    if (event.extendedProps.description) {
                      eventData.extendedProps.description = event.extendedProps.description
                    }
                    if (event.extendedProps.database) {
                      eventData.extendedProps.database = event.extendedProps.database
                    }
                  }

                  events.push(eventData)
                })
              } else {
                // دعم للتنسيق القديم (مصنف حسب التاريخ) للتوافق مع الإصدارات السابقة
                const dates = Object.keys(result.data)
                dates.forEach(date => {
                  if (Array.isArray(result.data[date])) {
                    events.push(
                      ...result.data[date].map(event => ({
                        id: event.id,
                        title: event.title,
                        start: event.date || event.start,
                        allDay: true,
                        extendedProps: {
                          description: event.description || 'لا يوجد وصف',
                          database: event.database
                        }
                      }))
                    )
                  }
                })
              }
            } else {
              // تنسيق لوحة التحكم (قائمة مسطحة)
              events.push(...result.data)
            }
            successCallback(events)
          } else {
            failureCallback(new Error(result.message))
          }
        })
        .catch(error => {
          failureCallback(error)
        })
    },
    eventClick: function handleEventClick(info) {
      // تنسيق التاريخ بالعربية
      const eventDate = new Date(info.event.start)
      const dateOptions = {
        weekday: 'long',
        year: 'numeric',
        month: 'long',
        day: 'numeric'
      }
      const formattedDate = new Intl.DateTimeFormat('ar-SA', dateOptions).format(eventDate)

      // تعديل طريقة استرجاع الوصف
      let description = info.event.extendedProps.description || 'لا يوجد وصف'

      // وإضافة تحقق من القيمة الفارغة
      if (description.trim() === '') {
        description = 'لا يوجد وصف'
      }

      // إنشاء محتوى النافذة المنبثقة
      const modalContent = `
        <div class="modal-event-details">
          <div class="event-date mb-3">
            <i class="ti ti-calendar me-1"></i>
            <strong>${formattedDate}</strong>
          </div>
          <div class="event-description">
            <i class="ti ti-note me-1"></i>
            <p>${description}</p>
          </div>
          <div class="event-database mt-3">
            <i class="ti ti-database me-1"></i>
            <small class="text-muted">قاعدة البيانات: ${document.getElementById('select-database') && document.getElementById('select-database').value ? document.getElementById('select-database').value : 'mysql'}</small>
          </div>
        </div>
      `

      // تحديد ما إذا كان في الواجهة الأمامية لاستخدام Bootstrap Modal
      const isFrontend = window.location.pathname === '/' || window.location.pathname.startsWith('/frontend')
      if (isFrontend) {
        const modalElement = document.getElementById('eventModal')
        if (modalElement) {
          // eslint-disable-next-line no-undef
          const eventModal = new bootstrap.Modal(modalElement)

          // تحديث محتوى النافذة المنبثقة
          const modalTitle = document.getElementById('eventModalLabel')
          const modalBody = document.getElementById('eventModalBody')

          if (modalTitle) modalTitle.textContent = info.event.title
          if (modalBody) modalBody.innerHTML = modalContent

          // عرض النافذة المنبثقة (إدارة accessibility تتم في home.blade.php)
          eventModal.show()
        }
      } else {
        // استخدام SweetAlert للوحة التحكم
        Swal.fire({
          title: info.event.title,
          html: modalContent,
          icon: 'info',
          showCloseButton: true,
          showConfirmButton: false,
          customClass: {
            popup: 'event-details-modal',
            title: 'event-title',
            content: 'event-content'
          }
        })
      }
    }
  })

  // تهيئة التقويم
  calendar.render()

  // استمع لتغييرات قاعدة البيانات
  const databaseSelector = document.getElementById('select-database')
  if (databaseSelector) {
    databaseSelector.addEventListener('change', function handleDatabaseChange() {
      calendar.refetchEvents()
    })
  }

  // تحديث دوري للأحداث كل 30 ثانية
  setInterval(() => calendar.refetchEvents(), 30000)
}

document.addEventListener('DOMContentLoaded', initCalendar)
