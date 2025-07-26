@php
    $cookieConsentConfig = config('cookie-consent');
    $cookieName = $cookieConsentConfig['cookie_name'] ?? 'laravel_cookie_consent';
    // لا نعتمد على PHP للفحص، سنتركه للـ JavaScript
@endphp

@if($cookieConsentConfig['enabled'] ?? true)
    <div class="cookie-consent js-cookie-consent" style="display: none;">
        <p class="cookie-consent__message" style="color: white;">
            {{ __('cookie-consent.message') }}
        </p>

        <button class="cookie-consent__agree js-cookie-consent-agree">
            {{ __('agree') }}
        </button>
    </div>

    <script>
        window.laravelCookieConsent = (function () {
            const COOKIE_NAME = '{{ $cookieName }}';
            const COOKIE_VALUE = '1';
            const COOKIE_DOMAIN = '{{ config('session.domain') ?? request()->getHost() }}';
            const COOKIE_LIFETIME = {{ $cookieConsentConfig['cookie_lifetime'] ?? 365 }};

            function consentWithCookies() {
                setCookie(COOKIE_NAME, COOKIE_VALUE, COOKIE_LIFETIME);
                // حفظ إضافي في localStorage كـ backup
                try {
                    localStorage.setItem('cookie_consent_given', 'true');
                } catch (e) {
                    console.log('localStorage not available');
                }
                hideCookieDialog();
            }

            function getCookie(name) {
                const nameEQ = name + "=";
                const ca = document.cookie.split(';');
                for (let i = 0; i < ca.length; i++) {
                    let c = ca[i];
                    while (c.charAt(0) === ' ') c = c.substring(1, c.length);
                    if (c.indexOf(nameEQ) === 0) return c.substring(nameEQ.length, c.length);
                }
                return null;
            }

            function cookieExists(name) {
                const cookieValue = getCookie(name);
                // فحص إضافي في localStorage
                let localStorageConsent = false;
                try {
                    localStorageConsent = localStorage.getItem('cookie_consent_given') === 'true';
                } catch (e) {
                    localStorageConsent = false;
                }
                return cookieValue === COOKIE_VALUE || localStorageConsent;
            }

            function hideCookieDialog() {
                const dialogs = document.getElementsByClassName('js-cookie-consent');
                for (let i = 0; i < dialogs.length; ++i) {
                    dialogs[i].style.display = 'none';
                }
            }

            function showCookieDialog() {
                const dialogs = document.getElementsByClassName('js-cookie-consent');
                for (let i = 0; i < dialogs.length; ++i) {
                    dialogs[i].style.display = 'flex';
                }
            }

            function setCookie(name, value, expirationInDays) {
                const date = new Date();
                date.setTime(date.getTime() + (expirationInDays * 24 * 60 * 60 * 1000));
                let cookieString = name + '=' + value
                    + ';expires=' + date.toUTCString()
                    + ';path=/';
                
                // إضافة domain فقط إذا لم يكن localhost
                if (COOKIE_DOMAIN && COOKIE_DOMAIN !== 'localhost' && COOKIE_DOMAIN !== '127.0.0.1') {
                    cookieString += ';domain=' + COOKIE_DOMAIN;
                }
                
                // إضافة secure و samesite إذا كانت مُعرَّفة
                @if(config('session.secure'))
                cookieString += ';secure';
                @endif
                @if(config('session.same_site'))
                cookieString += ';samesite={{ config('session.same_site') }}';
                @endif
                
                document.cookie = cookieString;
                
                // تأكيد أن الـ cookie تم حفظه
                console.log('Cookie set:', cookieString);
                console.log('Cookie check after set:', getCookie(name));
            }

            // فحص الموافقة عند تحميل الصفحة
            function checkConsent() {
                if (cookieExists(COOKIE_NAME)) {
                    hideCookieDialog();
                } else {
                    showCookieDialog();
                }
            }

            // تشغيل الفحص عند تحميل الصفحة
            if (document.readyState === 'loading') {
                document.addEventListener('DOMContentLoaded', checkConsent);
            } else {
                checkConsent();
            }

            // ربط أزرار الموافقة
            const buttons = document.getElementsByClassName('js-cookie-consent-agree');
            for (let i = 0; i < buttons.length; ++i) {
                buttons[i].addEventListener('click', consentWithCookies);
            }

            return {
                consentWithCookies: consentWithCookies,
                hideCookieDialog: hideCookieDialog,
                showCookieDialog: showCookieDialog,
                checkConsent: checkConsent
            };
        })();
    </script>
@endif
