@php
    $cookieConsentConfig = config('cookie-consent');
    $cookieName = $cookieConsentConfig['cookie_name'] ?? 'laravel_cookie_consent';
    $cookieLifetime = $cookieConsentConfig['cookie_lifetime'] ?? 365;
    $sessionSecure = config('session.secure') ? 'true' : 'false';
    $sessionSameSite = config('session.same_site') ?? 'lax';
    $sessionDomain = config('session.domain') ?? request()->getHost();
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
            const COOKIE_DOMAIN = '{{ $sessionDomain }}';
            const COOKIE_LIFETIME = {{ $cookieLifetime }};
            const SESSION_SECURE = {{ $sessionSecure }};
            const SESSION_SAME_SITE = '{{ $sessionSameSite }}';

            function consentWithCookies() {
                setCookie(COOKIE_NAME, COOKIE_VALUE, COOKIE_LIFETIME);
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
                let cookieString = name + '=' + value + ';expires=' + date.toUTCString() + ';path=/';
                
                if (COOKIE_DOMAIN && COOKIE_DOMAIN !== 'localhost' && COOKIE_DOMAIN !== '127.0.0.1') {
                    cookieString += ';domain=' + COOKIE_DOMAIN;
                }
                
                if (SESSION_SECURE) {
                    cookieString += ';secure';
                }
                
                if (SESSION_SAME_SITE) {
                    cookieString += ';samesite=' + SESSION_SAME_SITE;
                }
                
                document.cookie = cookieString;
                console.log('Cookie set:', cookieString);
                console.log('Cookie check after set:', getCookie(name));
            }

            function checkConsent() {
                if (cookieExists(COOKIE_NAME)) {
                    hideCookieDialog();
                } else {
                    showCookieDialog();
                }
            }

            if (document.readyState === 'loading') {
                document.addEventListener('DOMContentLoaded', checkConsent);
            } else {
                checkConsent();
            }

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
