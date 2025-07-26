# التحكم في رسائل Console للـ CSRF Manager

## 📊 **حول رسائل Console**

رسائل console التي تراها مثل:
- `CSRF Manager started`
- `CSRF token refreshed successfully`
- `Fetch finished loading: GET "http://127.0.0.1:8000/csrf-refresh"`

هي **آمنة تماماً** ومفيدة للمراقبة والتشخيص.

## 🔧 **طرق التحكم في الرسائل**

### 1. **التحكم عبر ملف .env**

أضف هذه الإعدادات إلى ملف `.env`:

```env
# إلغاء رسائل debug في الإنتاج
CSRF_DEBUG=false

# التحكم في أنواع الرسائل
CSRF_LOGGING=false
CSRF_LOG_INFO=false
CSRF_LOG_WARNING=true
CSRF_LOG_ERROR=true
```

### 2. **التحكم التلقائي حسب البيئة**

النظام يتحكم تلقائياً:
- **localhost/127.0.0.1**: يظهر جميع الرسائل
- **الخادم المباشر**: يخفي رسائل debug تلقائياً

### 3. **التحكم اليدوي عبر JavaScript**

```javascript
// إلغاء جميع رسائل debug
window.CSRFManager.config.debug = false;

// إلغاء console logging
window.CSRFManager.config.logging.enabled = false;
```

## ⚙️ **الإعدادات المتاحة**

### في ملف `config/csrf-manager.php`:

```php
'debug' => env('CSRF_DEBUG', config('app.debug')),
'logging' => [
    'enabled' => env('CSRF_LOGGING', config('app.debug')),
    'levels' => [
        'info' => env('CSRF_LOG_INFO', true),
        'warning' => env('CSRF_LOG_WARNING', true),
        'error' => env('CSRF_LOG_ERROR', true),
    ],
],
```

### في ملف `.env`:

```env
# التحكم الأساسي
CSRF_DEBUG=false              # إلغاء debug messages
CSRF_LOGGING=false            # إلغاء جميع console logs

# التحكم المفصل
CSRF_LOG_INFO=false           # إلغاء رسائل المعلومات
CSRF_LOG_WARNING=true         # إبقاء رسائل التحذير
CSRF_LOG_ERROR=true           # إبقاء رسائل الأخطاء
```

## 🎯 **التوصيات**

### للتطوير (Development):
```env
CSRF_DEBUG=true
CSRF_LOGGING=true
CSRF_LOG_INFO=true
CSRF_LOG_WARNING=true
CSRF_LOG_ERROR=true
```

### للإنتاج (Production):
```env
CSRF_DEBUG=false
CSRF_LOGGING=false
CSRF_LOG_INFO=false
CSRF_LOG_WARNING=true
CSRF_LOG_ERROR=true
```

### للاختبار (Testing):
```env
CSRF_DEBUG=false
CSRF_LOGGING=true
CSRF_LOG_INFO=false
CSRF_LOG_WARNING=true
CSRF_LOG_ERROR=true
```

## 🔍 **أنواع الرسائل**

### ✅ **رسائل المعلومات (Info)**
- `CSRF Manager started`
- `CSRF token refreshed successfully`
- معلومات عن حالة النظام

### ⚠️ **رسائل التحذير (Warning)**
- تحذيرات انتهاء الجلسة
- مشاكل في الاتصال
- تحذيرات الأمان

### ❌ **رسائل الأخطاء (Error)**
- فشل تحديث CSRF token
- أخطاء الشبكة
- مشاكل في الجلسة

## 🚀 **تطبيق التغييرات**

بعد تعديل إعدادات `.env`:

```bash
# تنظيف الكاش
php artisan config:clear
php artisan cache:clear

# إعادة تحميل الصفحة
```

## 💡 **نصائح**

1. **لا تقلق من الرسائل**: هي مفيدة للمراقبة
2. **في الإنتاج**: استخدم `CSRF_DEBUG=false`
3. **للتشخيص**: فعل `CSRF_LOG_ERROR=true` دائماً
4. **للأداء**: قلل الرسائل في الإنتاج

## 🔒 **الأمان**

جميع رسائل console آمنة ولا تكشف:
- كلمات مرور
- معلومات حساسة
- بيانات المستخدمين
- مفاتيح API

تظهر فقط معلومات عامة عن حالة النظام.
