FROM php:8.2-apache

# تثبيت MySQL PDO
RUN docker-php-ext-install pdo pdo_mysql

# نسخ ملفات المشروع إلى داخل السيرفر
COPY . /var/www/html/

# إعطاء الصلاحيات
RUN chown -R www-data:www-data /var/www/html
