# سیستم مدیریت بازدید تجهیزات اتوماسیون

## 📋 توضیحات پروژه
سیستم مدیریت بازدید تجهیزات اتوماسیون شرکت توزیع نیروی برق استان یزد، یک نرم‌افزار تحت وب برای ثبت و مدیریت بازدیدهای دوره‌ای تجهیزات اتوماسیون است.

## ✨ قابلیت‌ها
- فرم بازدید با ۴ مرحله (اطلاعات روزانه، انتخاب تجهیز، اطلاعات فنی، گزارش نهایی)
- ثبت اطلاعات در دیتابیس با استفاده از لاراول
- مدیریت کاربران و احراز هویت با Sanctum
- خروجی Excel با کتابخانه XLSX
- خروجی Word با تولید فایل HTML
- خروجی PDF با قابلیت پرینت
- ذخیره و بارگذاری پیش‌نویس
- پشتیبانی کامل از زبان فارسی

## 🛠 تکنولوژی‌ها
- **Backend:** Laravel 11
- **Frontend:** HTML, CSS, JavaScript, Bootstrap 5
- **Database:** MySQL
- **Authentication:** Laravel Sanctum
- **Libraries:** 
  - XLSX (Excel)
  - FileSaver.js
  - SweetAlert2
  - Moment.js (Jalali)
  - jsPDF (PDF)
  - html2pdf (PDF)
  - Select2
  - jQuery

## 📦 نصب و راه‌اندازی

### پیش‌نیازها
- PHP 8.2 یا بالاتر
- Composer
- MySQL
- Node.js (اختیاری)

### مراحل نصب
```bash
# کلون کردن پروژه
git clone https://github.com/your-username/automation-inspection-system.git
cd automation-inspection-system

# نصب وابستگی‌های PHP
composer install

# کپی کردن فایل محیط
cp .env.example .env

# تنظیم دیتابیس در فایل .env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=automation_db
DB_USERNAME=root
DB_PASSWORD=

# ایجاد کلید
php artisan key:generate

# اجرای migration
php artisan migrate

# اجرای seeders (اختیاری)
php artisan db:seed

# نصب وابستگی‌های Node (اختیاری)
npm install
npm run build

# اجرای سرور
php artisan serve