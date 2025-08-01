<!DOCTYPE html>
@php
$menuFixed = ($configData['layout'] === 'vertical') ? ($menuFixed ?? '') : (($configData['layout'] === 'front') ? '' : $configData['headerType']);
$navbarType = ($configData['layout'] === 'vertical') ? ($configData['navbarType'] ?? '') : (($configData['layout'] === 'front') ? 'layout-navbar-fixed': '');
$isFront = ($isFront ?? '') == true ? 'Front' : '';
$contentLayout = (isset($container) ? (($container === 'container-xxl') ? "layout-compact" : "layout-wide") : "");
@endphp

<html lang="{{ session()->get('locale') ?? app()->getLocale() }}" class="{{ $configData['style'] }}-style {{($contentLayout ?? '')}} {{ ($navbarType ?? '') }} {{ ($menuFixed ?? '') }} {{ $menuCollapsed ?? '' }} {{ $menuFlipped ?? '' }} {{ $menuOffcanvas ?? '' }} {{ $footerFixed ?? '' }} {{ $customizerHidden ?? '' }}" dir="{{ $configData['textDirection'] }}" data-theme="{{ $configData['theme'] }}" data-assets-path="{{ asset('/assets') . '/' }}" data-base-url="{{url('/')}}" data-framework="laravel" data-template="{{ $configData['layout'] . '-menu-' . $configData['themeOpt'] . '-' . $configData['styleOpt'] }}" data-style="{{$configData['styleOptVal']}}">
<title>@yield('title') |
    {{ config('settings.site_name') ? config('settings.site_name') : 'site_name' }}
     
  </title>
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, maximum-scale=1.0">
  <meta http-equiv="Cache-Control" content="public, max-age=31536000">
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <!-- Favicon -->
  @if(config('settings.site_favicon'))
  <link rel="icon" type="image/png" href="{{ asset('storage/' . config('settings.site_favicon')) }}">
  <link rel="shortcut icon" type="image/png" href="{{ asset('storage/' . config('settings.site_favicon')) }}">
  <link rel="apple-touch-icon" href="{{ asset('storage/' . config('settings.site_favicon')) }}">
  @endif

  @if(isset($noindex) && $noindex)
  <meta name="robots" content="noindex, nofollow">
  <meta name="googlebot" content="noindex, nofollow">
  @else
  <meta name="robots" content="index, follow">
  <meta name="googlebot" content="index, follow">
  @endif

  @if(isset($canonical))
  <link rel="canonical" href="{{ $canonical }}" />
  @else
  <link rel="canonical" href="{{ url()->current() }}" />
  @endif

  @hasSection('meta')
  @yield('meta')
  @else
  @if(request()->is('/'))
  <!-- Primary Meta Tags -->
  <meta name="title" content="{{ config('settings.meta_title', config('settings.site_name', 'Site Name')) }}">
  <meta name="description" content="{{ config('settings.meta_description', '') }}" />
  <meta name="keywords" content="{{ config('settings.meta_keywords', '') }}">

  @endif
  @endif
   <!-- Open Graph / Facebook -->
   <meta property="og:type" content="website">
  <meta property="og:url" content="{{ url()->current() }}">
  <meta property="og:title" content="{{ config('settings.meta_title', config('settings.site_name', 'Site Name')) }}">
  <meta property="og:description" content="{{ config('settings.meta_description', '') }}">
  <meta property="og:image" content="{{ asset(config('settings.site_logo', 'assets/img/logo/logo.png')) }}">

  @if(config('settings.facebook_pixel_id'))
  <!-- Facebook Pixel Code -->
  <script>
    !function(f,b,e,v,n,t,s)
    {if(f.fbq)return;n=f.fbq=function(){n.callMethod?
    n.callMethod.apply(n,arguments):n.queue.push(arguments)};
    if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
    n.queue=[];t=b.createElement(e);t.async=!0;
    t.src=v;s=b.getElementsByTagName(e)[0];
    s.parentNode.insertBefore(t,s)}(window, document,'script',
    'https://connect.facebook.net/en_US/fbevents.js');
    fbq('init', '{{ config('settings.facebook_pixel_id') }}');
    fbq('track', 'PageView');
  </script>
  <noscript>
    <img height="1" width="1" style="display:none"
         src="https://www.facebook.com/tr?id={{ config('settings.facebook_pixel_id') }}&ev=PageView&noscript=1"/>
  </noscript>
  @endif

  @if(config('settings.google_analytics_id'))
  <!-- Google Analytics -->
  <script async src="https://www.googletagmanager.com/gtag/js?id={{ config('settings.google_analytics_id') }}"></script>
  <script>
    window.dataLayer = window.dataLayer || [];
    function gtag(){dataLayer.push(arguments);}
    gtag('js', new Date());
    gtag('config', '{{ config('settings.google_analytics_id') }}');
  </script>
  @endif

  <!-- Canonical SEO -->
  @if(config('settings.canonical_url'))
    <link rel="canonical" href="{{ config('settings.canonical_url') }}">
  @else
    <link rel="canonical" href="{{ url()->current() }}">
  @endif

  @include('layouts/sections/styles' . $isFront)
  @include('layouts/sections/scriptsIncludes' . $isFront)
  @vite(['resources/css/cookie-consent.css', 'resources/css/footer-front.css'])
</head>

<body>
  @yield('layoutContent')
  @include('layouts/sections/scripts' . $isFront)
  @yield('page-script')
  @include('components.cookie-consent')
  
  <!-- CSRF Token Refresh Script -->
  @vite(['resources/assets/js/csrf-refresh.js'])
  <script>
    // تهيئة CSRF token في axios و jQuery
    window.addEventListener('DOMContentLoaded', function() {
      // إعداد axios
      if (window.axios) {
        window.axios.defaults.headers.common['X-CSRF-TOKEN'] = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
      }
      
      // إعداد jQuery
      if (window.$ && $.ajaxSetup) {
        $.ajaxSetup({
          headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          }
        });
      }
      
      // إعداد CSRF Manager من Laravel config
      if (window.CSRFManager && window.CSRFManager.config) {
        window.CSRFManager.config.debug = {{ config('csrf-manager.debug') ? 'true' : 'false' }};
        window.CSRFManager.config.refreshInterval = {{ config('csrf-manager.refresh_interval') }};
        window.CSRFManager.config.warningTime = {{ config('csrf-manager.warning_time') }};
        window.CSRFManager.config.maxInactivity = {{ config('csrf-manager.max_inactivity') }};
        window.CSRFManager.config.logging = {
          enabled: {{ config('csrf-manager.logging.enabled') ? 'true' : 'false' }},
          info: {{ config('csrf-manager.logging.levels.info') ? 'true' : 'false' }},
          warning: {{ config('csrf-manager.logging.levels.warning') ? 'true' : 'false' }},
          error: {{ config('csrf-manager.logging.levels.error') ? 'true' : 'false' }}
        };
      }
    });
  </script>

  
</body>
</html>
