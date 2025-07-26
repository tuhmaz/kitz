# دليل النشر - منصة جبل التعليمية

## متطلبات السيرفر

### البرامج المطلوبة:
- **PHP 8.1+** مع الإضافات التالية:
  - php-fpm
  - php-mysql
  - php-mbstring
  - php-xml
  - php-curl
  - php-zip
  - php-gd
  - php-intl
  - php-bcmath
  - php-json
  - php-tokenizer

- **Apache 2.4+** أو **Nginx 1.18+**
- **MySQL 8.0+** أو **MariaDB 10.4+**
- **Composer 2.0+**
- **Node.js 16+** و **NPM**

## خطوات النشر

### 1. تحميل الكود من GitHub
```bash
cd /var/www
git clone https://github.com/tuhmaz/kitz.git jabal.news
cd jabal.news
```

### 2. تثبيت المتطلبات
```bash
# تثبيت متطلبات PHP
composer install --optimize-autoloader --no-dev

# تثبيت متطلبات JavaScript
npm install
npm run build
```

### 3. إعداد البيئة
```bash
# نسخ ملف البيئة
cp .env.example .env

# توليد مفتاح التطبيق
php artisan key:generate

# تحرير ملف .env
nano .env
```

### 4. إعداد قاعدة البيانات
```bash
# إنشاء قاعدة البيانات
mysql -u root -p -e "CREATE DATABASE jabal_news CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"

# تشغيل الهجرات
php artisan migrate --force

# تشغيل البذور (اختياري)
php artisan db:seed --force
```

### 5. إعداد الصلاحيات
```bash
# تغيير مالك الملفات
sudo chown -R www-data:www-data /var/www/jabal.news

# إعداد صلاحيات المجلدات
sudo chmod -R 755 /var/www/jabal.news
sudo chmod -R 775 /var/www/jabal.news/storage
sudo chmod -R 775 /var/www/jabal.news/bootstrap/cache
```

### 6. إعداد Apache Virtual Host

إنشاء ملف `/etc/apache2/sites-available/jabal.news.conf`:

```apache
<VirtualHost *:80>
    ServerName jabal.news
    ServerAlias www.jabal.news
    DocumentRoot /var/www/jabal.news/public
    
    <Directory /var/www/jabal.news/public>
        AllowOverride All
        Require all granted
        Options -Indexes -ExecCGI -FollowSymLinks
        
        # تفعيل PHP
        <FilesMatch \.php$>
            SetHandler "proxy:unix:/var/run/php/php8.1-fpm.sock|fcgi://localhost/"
        </FilesMatch>
    </Directory>
    
    # السجلات
    ErrorLog ${APACHE_LOG_DIR}/jabal.news_error.log
    CustomLog ${APACHE_LOG_DIR}/jabal.news_access.log combined
    
    # إعدادات الأمان
    ServerTokens Prod
    ServerSignature Off
    
    # منع الوصول للمجلدات الحساسة
    <DirectoryMatch "^/var/www/jabal.news/(storage|vendor|node_modules|bootstrap/cache)">
        Require all denied
    </DirectoryMatch>
</VirtualHost>

# إعداد HTTPS (مطلوب للإنتاج)
<VirtualHost *:443>
    ServerName jabal.news
    ServerAlias www.jabal.news
    DocumentRoot /var/www/jabal.news/public
    
    SSLEngine on
    SSLCertificateFile /path/to/certificate.crt
    SSLCertificateKeyFile /path/to/private.key
    
    # إعادة توجيه جميع HTTP إلى HTTPS
    Header always set Strict-Transport-Security "max-age=31536000; includeSubDomains; preload"
    
    <Directory /var/www/jabal.news/public>
        AllowOverride All
        Require all granted
        Options -Indexes -ExecCGI -FollowSymLinks
        
        <FilesMatch \.php$>
            SetHandler "proxy:unix:/var/run/php/php8.1-fpm.sock|fcgi://localhost/"
        </FilesMatch>
    </Directory>
    
    ErrorLog ${APACHE_LOG_DIR}/jabal.news_ssl_error.log
    CustomLog ${APACHE_LOG_DIR}/jabal.news_ssl_access.log combined
</VirtualHost>
```

### 7. تفعيل الموقع
```bash
# تفعيل الموديولات المطلوبة
sudo a2enmod rewrite
sudo a2enmod ssl
sudo a2enmod headers
sudo a2enmod deflate
sudo a2enmod expires

# تفعيل الموقع
sudo a2ensite jabal.news.conf
sudo systemctl reload apache2
```

## إعدادات ملف .env

```env
APP_NAME="منصة جبل التعليمية"
APP_ENV=production
APP_KEY=base64:YOUR_GENERATED_KEY
APP_DEBUG=false
APP_URL=https://jabal.news

LOG_CHANNEL=stack
LOG_DEPRECATIONS_CHANNEL=null
LOG_LEVEL=error

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=jabal_news
DB_USERNAME=your_db_user
DB_PASSWORD=your_db_password

BROADCAST_DRIVER=log
CACHE_DRIVER=file
FILESYSTEM_DISK=local
QUEUE_CONNECTION=sync
SESSION_DRIVER=file
SESSION_LIFETIME=120

MEMCACHED_HOST=127.0.0.1

REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379

MAIL_MAILER=smtp
MAIL_HOST=mailpit
MAIL_PORT=1025
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=null
MAIL_FROM_ADDRESS="hello@example.com"
MAIL_FROM_NAME="${APP_NAME}"

AWS_ACCESS_KEY_ID=
AWS_SECRET_ACCESS_KEY=
AWS_DEFAULT_REGION=us-east-1
AWS_BUCKET=
AWS_USE_PATH_STYLE_ENDPOINT=false

PUSHER_APP_ID=
PUSHER_APP_KEY=
PUSHER_APP_SECRET=
PUSHER_HOST=
PUSHER_PORT=443
PUSHER_SCHEME=https
PUSHER_APP_CLUSTER=mt1

VITE_PUSHER_APP_KEY="${PUSHER_APP_KEY}"
VITE_PUSHER_HOST="${PUSHER_HOST}"
VITE_PUSHER_PORT="${PUSHER_PORT}"
VITE_PUSHER_SCHEME="${PUSHER_SCHEME}"
VITE_PUSHER_APP_CLUSTER="${PUSHER_APP_CLUSTER}"
```

## استكشاف الأخطاء

### 1. إذا ظهر كود PHP بدلاً من الصفحة:
```bash
# تأكد من تثبيت PHP-FPM
sudo apt install php8.1-fpm

# تأكد من تشغيل PHP-FPM
sudo systemctl status php8.1-fpm
sudo systemctl start php8.1-fpm

# تأكد من إعدادات Apache
sudo a2enmod proxy_fcgi setenvif
sudo a2enconf php8.1-fpm
sudo systemctl restart apache2
```

### 2. خطأ 500:
```bash
# تحقق من سجلات الأخطاء
tail -f /var/log/apache2/jabal.news_error.log
tail -f /var/www/jabal.news/storage/logs/laravel.log

# تأكد من الصلاحيات
sudo chmod -R 775 storage bootstrap/cache
```

### 3. خطأ في قاعدة البيانات:
```bash
# تحقق من الاتصال
php artisan tinker
>>> DB::connection()->getPdo();
```

## الأمان والصيانة

### 1. تحديثات دورية:
```bash
# تحديث الكود
git pull origin master
composer install --optimize-autoloader --no-dev
npm run build
php artisan migrate --force
php artisan cache:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### 2. النسخ الاحتياطي:
```bash
# نسخ احتياطي لقاعدة البيانات
mysqldump -u username -p jabal_news > backup_$(date +%Y%m%d).sql

# نسخ احتياطي للملفات
tar -czf files_backup_$(date +%Y%m%d).tar.gz /var/www/jabal.news
```

### 3. مراقبة الأداء:
- استخدم `htop` لمراقبة استخدام الموارد
- راقب سجلات Apache و PHP-FPM
- استخدم أدوات مراقبة مثل New Relic أو DataDog

## الدعم

للحصول على الدعم، يرجى:
1. التحقق من سجلات الأخطاء أولاً
2. مراجعة هذا الدليل
3. التواصل مع فريق التطوير

---
**ملاحظة**: تأكد من تفعيل HTTPS في الإنتاج وتحديث إعدادات الأمان بانتظام.
