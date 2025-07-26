/**
 * CSRF Token Refresh and Management
 * يدير تحديث CSRF tokens تلقائياً لمنع انتهاء صلاحية الجلسة
 */

'use strict';

window.CSRFManager = (function() {
    let refreshInterval = null;
    let lastActivity = Date.now();
    let isRefreshing = false;

    // إعدادات افتراضية
    const config = {
        refreshInterval: 5 * 60 * 1000, // 5 دقائق
        warningTime: 2 * 60 * 1000,     // تحذير قبل دقيقتين
        maxInactivity: 30 * 60 * 1000,  // 30 دقيقة خمول
        endpoints: {
            refresh: '/csrf-refresh',
            check: '/csrf-check'
        }
    };

    /**
     * تحديث CSRF token
     */
    function refreshCSRFToken() {
        if (isRefreshing) return Promise.resolve();
        
        isRefreshing = true;
        
        return fetch(config.endpoints.refresh, {
            method: 'GET',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            },
            credentials: 'same-origin'
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Failed to refresh CSRF token');
            }
            return response.json();
        })
        .then(data => {
            if (data.token) {
                updateCSRFTokens(data.token);
                console.log('CSRF token refreshed successfully');
                return data.token;
            } else {
                throw new Error('No token in response');
            }
        })
        .catch(error => {
            console.error('CSRF token refresh failed:', error);
            handleRefreshError();
            throw error;
        })
        .finally(() => {
            isRefreshing = false;
        });
    }

    /**
     * تحديث جميع CSRF tokens في الصفحة
     */
    function updateCSRFTokens(newToken) {
        // تحديث meta tag
        const metaToken = document.querySelector('meta[name="csrf-token"]');
        if (metaToken) {
            metaToken.setAttribute('content', newToken);
        }

        // تحديث جميع hidden inputs
        const tokenInputs = document.querySelectorAll('input[name="_token"]');
        tokenInputs.forEach(input => {
            input.value = newToken;
        });

        // تحديث headers في axios إذا كان متاحاً
        if (window.axios && window.axios.defaults.headers.common) {
            window.axios.defaults.headers.common['X-CSRF-TOKEN'] = newToken;
        }

        // تحديث jQuery CSRF إذا كان متاحاً
        if (window.$ && $.ajaxSetup) {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': newToken
                }
            });
        }
    }

    /**
     * فحص صحة CSRF token
     */
    function checkCSRFToken() {
        return fetch(config.endpoints.check, {
            method: 'POST',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': getCSRFToken(),
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            credentials: 'same-origin',
            body: JSON.stringify({})
        })
        .then(response => response.ok)
        .catch(() => false);
    }

    /**
     * الحصول على CSRF token الحالي
     */
    function getCSRFToken() {
        const metaToken = document.querySelector('meta[name="csrf-token"]');
        return metaToken ? metaToken.getAttribute('content') : null;
    }

    /**
     * تسجيل النشاط
     */
    function recordActivity() {
        lastActivity = Date.now();
    }

    /**
     * فحص الخمول
     */
    function checkInactivity() {
        const inactiveTime = Date.now() - lastActivity;
        
        if (inactiveTime > config.maxInactivity) {
            showInactivityWarning();
            return true;
        }
        
        return false;
    }

    /**
     * عرض تحذير الخمول
     */
    function showInactivityWarning() {
        if (window.Swal) {
            Swal.fire({
                title: 'تحذير انتهاء الجلسة',
                text: 'ستنتهي جلستك قريباً بسبب عدم النشاط. هل تريد المتابعة؟',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'متابعة',
                cancelButtonText: 'تسجيل خروج',
                timer: 30000,
                timerProgressBar: true
            }).then((result) => {
                if (result.isConfirmed) {
                    recordActivity();
                    refreshCSRFToken();
                } else {
                    window.location.href = '/logout';
                }
            });
        } else {
            const confirmed = confirm('ستنتهي جلستك قريباً. هل تريد المتابعة؟');
            if (confirmed) {
                recordActivity();
                refreshCSRFToken();
            } else {
                window.location.href = '/logout';
            }
        }
    }

    /**
     * معالجة خطأ تحديث الـ token
     */
    function handleRefreshError() {
        console.warn('CSRF token refresh failed. User may need to reload the page.');
        
        // إظهار رسالة للمستخدم
        if (window.Swal) {
            Swal.fire({
                title: 'مشكلة في الاتصال',
                text: 'حدثت مشكلة في تحديث الجلسة. يرجى تحديث الصفحة.',
                icon: 'error',
                confirmButtonText: 'تحديث الصفحة',
                allowOutsideClick: false
            }).then(() => {
                window.location.reload();
            });
        }
    }

    /**
     * بدء مراقبة CSRF
     */
    function start() {
        // تحديث دوري للـ token
        refreshInterval = setInterval(() => {
            if (!checkInactivity()) {
                refreshCSRFToken().catch(() => {
                    // في حالة الفشل، نحاول مرة أخرى بعد دقيقة
                    setTimeout(() => refreshCSRFToken(), 60000);
                });
            }
        }, config.refreshInterval);

        // مراقبة النشاط
        const activityEvents = ['mousedown', 'mousemove', 'keypress', 'scroll', 'touchstart', 'click'];
        activityEvents.forEach(event => {
            document.addEventListener(event, recordActivity, true);
        });

        // تحديث token عند تحميل الصفحة
        refreshCSRFToken();

        console.log('CSRF Manager started');
    }

    /**
     * إيقاف مراقبة CSRF
     */
    function stop() {
        if (refreshInterval) {
            clearInterval(refreshInterval);
            refreshInterval = null;
        }
        console.log('CSRF Manager stopped');
    }

    /**
     * إعداد AJAX للتعامل مع CSRF errors
     */
    function setupAjaxErrorHandling() {
        // jQuery AJAX error handling
        if (window.$ && $.ajaxSetup) {
            $(document).ajaxError(function(event, xhr, settings) {
                if (xhr.status === 419) {
                    console.warn('CSRF token mismatch detected, refreshing...');
                    refreshCSRFToken().then(() => {
                        // إعادة المحاولة
                        $.ajax(settings);
                    }).catch(() => {
                        handleRefreshError();
                    });
                }
            });
        }

        // Axios interceptor
        if (window.axios) {
            axios.interceptors.response.use(
                response => response,
                error => {
                    if (error.response && error.response.status === 419) {
                        console.warn('CSRF token mismatch detected, refreshing...');
                        return refreshCSRFToken().then(() => {
                            // إعادة المحاولة
                            return axios.request(error.config);
                        }).catch(() => {
                            handleRefreshError();
                            return Promise.reject(error);
                        });
                    }
                    return Promise.reject(error);
                }
            );
        }
    }

    // تهيئة عند تحميل DOM
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', () => {
            start();
            setupAjaxErrorHandling();
        });
    } else {
        start();
        setupAjaxErrorHandling();
    }

    // تنظيف عند مغادرة الصفحة
    window.addEventListener('beforeunload', stop);

    // واجهة عامة
    return {
        start,
        stop,
        refresh: refreshCSRFToken,
        check: checkCSRFToken,
        getToken: getCSRFToken,
        recordActivity,
        config
    };
})();
