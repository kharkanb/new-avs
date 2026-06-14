// =====================================================
// سیستم مدیریت بازدید تجهیزات اتوماسیون
// فایل جاوااسکریپت اصلی - نسخه نهایی و تصحیح شده
// =====================================================

// Global variables
let equipmentCount = 0;
let currentEquipmentIndex = -1;
let equipments = [];
let autoSaveEnabled = true;
let autoSaveTimer = null;

// Equipment types with installation type rules
const equipmentTypes = [
    'ریکلوزر',
    'سکسیونر', 
    'سکشنالایزر',
    'فالت دتکتور',
    'پست دو سو تغذیه (مشترک حساس)',
    'پست دو سو تغذیه (بیمارستانی)',
    'مشترک ولتاژ اولیه'
];

let equipmentWithoutHeight = [
    'پست دو سو تغذیه (مشترک حساس)',
    'پست دو سو تغذیه (بیمارستانی)',
    'مشترک ولتاژ اولیه'
];

let equipmentWithBrands = [
    'ریکلوزر',
    'سکسیونر', 
    'سکشنالایزر',
    'فالت دتکتور'
];

let equipmentWithoutBrands = [
    'پست دو سو تغذیه (مشترک حساس)',
    'پست دو سو تغذیه (بیمارستانی)',
    'مشترک ولتاژ اولیه'
];

let switchBrands = [
    'ABB', 'Schneider', 'Siemens', 'NOJA', 'Tavrida', 'Eaton', 'Ormazabal', 'Hyundai', 'LS', 'ENTEC', 'سایر'
];

let modemBrands = [
    'Fortak', 'FSK', 'Quadric', 'جهان ویستا', 'سایر'
];

let rtuBrands = [
    'PNC', 'حافظ', 'اشنایدر', 'PPEP', 'Fanox', 'سایر'
];

// Price list for activities
let priceList = [
    { code: '911377-1', title: 'نصب یا تعویض مودم در داخل تابلو تجهیزات اتوماسیونی به همراه سیم‌کشی‌های لازم', unit: 'مورد', price: 1800000 },
    { code: '911378-1', title: 'نصب یا تعویض پنل کنترل (RTU) در داخل تابلو تجهیزات اتوماسیونی به همراه سیم‌کشی‌های لازم', unit: 'مورد', price: 1800000 },
    { code: '911379-1', title: 'نصب یا تعویض باطری در داخل تجهیزات اتوماسیونی به همراه سیم‌کشی‌های لازم', unit: 'مجموعه', price: 1500000 },
    { code: '911380-1', title: 'نصب یا تعویض پاور تغذیه در داخل تابلو تجهیزات اتوماسیونی به همراه سیم‌کشی‌های لازم', unit: 'دستگاه', price: 1800000 },
    { code: '911381-1', title: 'نصب یا تعویض فیوز مینیاتوری در داخل تابلو تجهیزات اتوماسیونی به همراه سیم‌کشی‌های لازم', unit: 'مورد', price: 700000 },
    { code: '911382-1', title: 'نصب یا تعویض فیوز فشنگی در داخل تابلو تجهیزات اتوماسیونی به همراه سیم‌کشی‌های لازم', unit: 'مورد', price: 500000 },
    { code: '911383-1', title: 'نصب، اصلاح و یا تعویض سیم‌کشی و اتصالات در داخل تابلو تجهیزات اتوماسیونی بدون نصب تجهیز', unit: 'مورد', price: 1200000 },
    { code: '911384-1', title: 'نصب یا تعویض سیم کارت مودم در داخل تابلو تجهیزات اتوماسیونی به همراه تست ارتباط', unit: 'مورد', price: 1200000 },
    { code: '911385-1', title: 'انجام تنظیمات، پیکربندی و بروزرسانی مودم تجهیزات قطع کننده اتوماسیونی', unit: 'مورد', price: 1500000 },
    { code: '911386-1', title: 'راه‌اندازی و انجام تنظیمات، پیکربندی و بروزرسانی پنل کنترل (RTU) و مودم تجهیزات قطع کننده اتوماسیونی', unit: 'مورد', price: 1500000 },
    { code: '911387-1', title: 'انجام تنظیمات، پیکربندی و بروزرسانی پنل کنترل (RTU) و مودم تجهیزات قطع کننده اتوماسیونی جدید به همراه تست ارتباطی', unit: 'مورد', price: 3500000 },
    { code: '911388-1', title: 'به‌روزرسانی مودم براساس ورژن جدید شرکت سازنده', unit: 'مورد', price: 1500000 },
    { code: '911389-1', title: 'به‌روزرسانی پنل کنترل (RTU) براساس ورژن جدید شرکت سازنده', unit: 'مورد', price: 1500000 },
    { code: '911390-1', title: 'کالیبراسیون و تنظیمات و اصلاح مقادیر ولتاژ و جریان اندازه‌گیری شده توسط پنل RTU', unit: 'مورد', price: 1500000 },
    { code: '911391-1', title: 'انجام نظافت داخل تابلو فرمان به همراه چک کردن و فرم دهی کلیه کابل‌های ارتباطی', unit: 'مورد', price: 1500000 },
    { code: '911392-1', title: 'تکمیل چک‌لیست بازدید دوره‌ای تخصصی ریکلوزر (F-20342-01)', unit: 'مورد', price: 2000000 },
    { code: '911393-1', title: 'تکمیل چک‌لیست بازدید دوره‌ای تخصصی سکشنالایزر (F20345)', unit: 'مورد', price: 2000000 },
    { code: '911394-1', title: 'تکمیل فرم بازدید دوره‌ای کلیدهای قدرت با قابلیت اتوماسیون', unit: 'مورد', price: 1500000 },
    { code: '911395-1', title: 'شناسایی کلیدهای جدید با قابلیت اتوماسیون و تکمیل فرم گزارش', unit: 'مورد', price: 1500000 },
    { code: '911396-1', title: 'تکمیل فرم گزارش سرویس و عیوب باقیمانده تجهیزات اتوماسیون شده', unit: 'مورد', price: 1500000 },
    { code: '911397-1', title: 'انجام تنظیمات و تغییر شماره پیام فالت دیتکتورهای اتوماسیون شده شبکه', unit: 'مورد', price: 1500000 },
    { code: '911398-1', title: 'تنظیم فالت دتکتور جهت مشترکین دی‌ماندی', unit: 'مورد', price: 5000000 },
    { code: '911399-1', title: 'عیب‌یابی و تعمیرات مدارات اتوماسیون مدیریت بار (RCMU)', unit: 'مورد', price: 5000000 },
    { code: '911400-1', title: 'تعریف شماتیک و تگ‌ها در اسکادا برای تجهیزات جدید', unit: 'مورد', price: 1300000 },
    { code: '911401-1', title: 'ارسال گزارش دوره‌ای ماهیانه از وضعیت اجزای معیوب کلیدها و تجهیزات اتوماسیون شده', unit: 'مورد', price: 1500000 },
    { code: '911402-1', title: 'ایاب و ذهاب با خودروی شخصی جهت بازدید و عیب‌یابی تجهیزات اتوماسیونی', unit: 'کیلومتر', price: 50000 }
];

let consumablesList = [
    { name: 'مودم', unit: 'عدد' },
    { name: 'RTU', unit: 'عدد' },
    { name: 'آنتن', unit: 'عدد' },
    { name: 'باتری', unit: 'عدد' },
    { name: 'فیوز مینیاتوری', unit: 'عدد' },
    { name: 'فیوز فشنگی', unit: 'عدد' },
    { name: 'کابل ارتباطی', unit: 'متر' },
    { name: 'کابل تغذیه', unit: 'متر' },
    { name: 'کانکتور', unit: 'عدد' },
    { name: 'سیم کارت', unit: 'عدد' },
    { name: 'پاور', unit: 'عدد' },
    { name: 'رله', unit: 'عدد' },
    { name: 'سکسیونر', unit: 'عدد' },
    { name: 'دژنکتور', unit: 'عدد' },
    { name: 'کابل آنتن', unit: 'متر' },
    { name: 'سایر اقلام', unit: 'عدد' }
];

const equipmentTypesForCells = [
    'دژنکتور',
    'سکسیونر',
    'رله/RTU',
    'کنترل‌پنل',
    'پاور',
    'باتری',
    'فیوز',
    'کنترل‌ر حفاظتی',
    'ترانس جریان',
    'ترانس ولتاژ',
    'مبدل آنالوگ',
    'سوئیچ شبکه',
    'مودم',
    'اینورتر',
    'کنترل‌ر دما'
];

const equipmentBrands = {
    'دژنکتور': ['ABB', 'Schneider', 'Siemens', 'GE', 'Hyundai', 'Eaton', 'Mitsubishi', 'Toshiba', 'LS', 'Entec', 'سایر'],
    'سکسیونر': ['ABB', 'Schneider', 'Siemens', 'GE', 'Hyundai', 'Eaton', 'Mitsubishi', 'Toshiba', 'LS', 'Entec', 'سایر'],
    'رله/RTU': ['ABB', 'Schneider', 'Siemens', 'GE', 'Hyundai', 'Eaton', 'Mitsubishi', 'Toshiba', 'LS', 'Entec', 'سایر'],
    'کنترل‌پنل': ['ABB', 'Schneider', 'Siemens', 'GE', 'Hyundai', 'Eaton', 'Mitsubishi', 'Toshiba', 'LS', 'Entec', 'سایر'],
    'پاور': ['ABB', 'Schneider', 'Siemens', 'GE', 'Hyundai', 'Eaton', 'Mitsubishi', 'Toshiba', 'LS', 'Entec', 'سایر'],
    'باتری': ['Yuasa', 'CSB', 'Vision', 'Rocket', 'Panasonic', 'Samsung', 'LG', 'Exide', 'Varta', 'Tudor', 'سایر'],
    'فیوز': ['ABB', 'Schneider', 'Siemens', 'GE', 'Hyundai', 'Eaton', 'Mitsubishi', 'Toshiba', 'LS', 'Entec', 'سایر'],
    'کنترل‌ر حفاظتی': ['ABB', 'Schneider', 'Siemens', 'GE', 'Hyundai', 'Eaton', 'Mitsubishi', 'Toshiba', 'LS', 'Entec', 'سایر'],
    'ترانس جریان': ['ABB', 'Schneider', 'Siemens', 'GE', 'Hyundai', 'Eaton', 'Mitsubishi', 'Toshiba', 'LS', 'Entec', 'سایر'],
    'ترانس ولتاژ': ['ABB', 'Schneider', 'Siemens', 'GE', 'Hyundai', 'Eaton', 'Mitsubishi', 'Toshiba', 'LS', 'Entec', 'سایر'],
    'مبدل آنالوگ': ['ABB', 'Schneider', 'Siemens', 'GE', 'Hyundai', 'Eaton', 'Mitsubishi', 'Toshiba', 'LS', 'Entec', 'سایر'],
    'سوئیچ شبکه': ['Cisco', 'HP', 'D-Link', 'TP-Link', 'Netgear', 'Juniper', 'Huawei', 'Zyxel', 'MikroTik', 'Ubiquiti', 'سایر'],
    'مودم': ['Fortak', 'FSK', 'Quadric', 'جهان ویستا', 'Sierra', 'Huawei', 'ZTE', 'Teltonika', 'Cradlepoint', 'Digi', 'سایر'],
    'اینورتر': ['ABB', 'Schneider', 'Siemens', 'GE', 'Hyundai', 'Eaton', 'Mitsubishi', 'Toshiba', 'LS', 'Entec', 'سایر'],
    'کنترل‌ر دما': ['ABB', 'Schneider', 'Siemens', 'GE', 'Hyundai', 'Eaton', 'Mitsubishi', 'Toshiba', 'LS', 'Entec', 'سایر'],
    'سایر': ['استاندارد', 'محلی', 'نامشخص', 'سایر']
};

let cityDepartments = [
    'ستاد',
    'امور یک',
    'امور دو', 
    'امور سه',
    'زارچ',
    'اشکذر',
    'میبد',
    'اردکان',
    'تفت',
    'مهریز',
    'نیر',
    'هرات',
    'مروست',
    'ابرکوه',
    'بافق',
    'بهاباد'
];

let equipmentChecklists = {
    'ریکلوزر': [
        'ارتفاع نصب تابلو کنترل',
        'وضعیت برق ورودی تابلو و اتصال PT',
        'وضعیت فیوزهای تابلو کنترل',
        'وضعیت اتصال کابل دیتای ارتباطی تجهیز (تانک) به تابلو کنترل',
        'وضعیت باتری تابلو کنترل',
        'وضعیت سیم‌بندی داخل تابلو کنترل',
        'وضعیت نصب مودم در داخل تابلو کنترل',
        'وضعیت اتصال کابل تغذیه مودم (پنل یا باتری)',
        'بررسی سطح مناسب ولتاژ خروجی DC پنل کنترل',
        'وضعیت اتصال کابل آنتن مودم',
        'وضعیت نصب آنتن مخابراتی',
        'وضعیت سیگنال ارتباطی',
        'وضعیت اتصال کابل ارتباطی مودم به پنل کنترل داخل تابلو',
        'وضعیت تنظیمات پورت پنل کنترل (Port Setting)',
        'صحت تنظیم پارامترهای ارتباطی (Protocol & Communication)',
        'وضعیت ارت تابلو کنترل',
        'وضعیت نظافت و تمیزی تابلو کنترل',
        'وضعیت ارسال وضعیت تجهیز به SCADA',
        'ثبت لاگ و رویدادهای تجهیز'
    ],
    'سکسیونر': [
        'ارتفاع نصب تابلو کنترل',
        'وضعیت برق ورودی تابلو و اتصال PT',
        'وضعیت فیوزهای تابلو کنترل',
        'وضعیت اتصال کابل دیتای ارتباطی تجهیز (تانک) به تابلو کنترل',
        'وضعیت باتری تابلو کنترل',
        'وضعیت سیم‌بندی داخل تابلو کنترل',
        'وضعیت نصب مودم در داخل تابلو کنترل',
        'وضعیت اتصال کابل تغذیه مودم (پنل یا باتری)',
        'بررسی سطح مناسب ولتاژ خروجی DC پنل کنترل',
        'وضعیت اتصال کابل آنتن مودم',
        'وضعیت نصب آنتن مخابراتی',
        'وضعیت سیگنال ارتباطی',
        'وضعیت اتصال کابل ارتباطی مودم به پنل کنترل داخل تابلو',
        'وضعیت تنظیمات پورت پنل کنترل (Port Setting)',
        'صحت تنظیم پارامترهای ارتباطی (Protocol & Communication)',
        'وضعیت ارت تابلو کنترل',
        'وضعیت نظافت و تمیزی تابلو کنترل',
        'وضعیت ارسال وضعیت تجهیز به SCADA',
        'ثبت لاگ و رویدادهای تجهیز'
    ],
    'سکشنالایزر': [
        'ارتفاع نصب تابلو کنترل',
        'وضعیت برق ورودی تابلو و اتصال PT',
        'وضعیت فیوزهای تابلو کنترل',
        'وضعیت اتصال کابل دیتای ارتباطی تجهیز (تانک) به تابلو کنترل',
        'وضعیت باتری تابلو کنترل',
        'وضعیت سیم‌بندی داخل تابلو کنترل',
        'وضعیت نصب مودم در داخل تابلو کنترل',
        'وضعیت اتصال کابل تغذیه مودم (پنل یا باتری)',
        'بررسی سطح مناسب ولتاژ خروجی DC پنل کنترل',
        'وضعیت اتصال کابل آنتن مودم',
        'وضعیت نصب آنتن مخابراتی',
        'وضعیت سیگنال ارتباطی',
        'وضعیت اتصال کابل ارتباطی مودم به پنل کنترل داخل تابلو',
        'وضعیت تنظیمات پورت پنل کنترل (Port Setting)',
        'صحت تنظیم پارامترهای ارتباطی (Protocol & Communication)',
        'وضعیت ارت تابلو کنترل',
        'وضعیت نظافت و تمیزی تابلو کنترل',
        'وضعیت ارسال وضعیت تجهیز به SCADA',
        'ثبت لاگ و رویدادهای تجهیز'
    ],
    'فالت دتکتور': [
        'وضعیت نصب فیزیکی فالت‌دیتکتور روی فیدر',
        'سلامت بدنه و عدم نفوذ رطوبت',
        'وضعیت منبع تغذیه / باتری داخلی',
        'وضعیت نشانگرهای LED',
        'صحت تشخیص خطا (Fault Indication)',
        'وضعیت ارسال آلارم Fault به SCADA',
        'وضعیت ارتباط مخابراتی دستگاه',
        'وضعیت آنتن یا ماژول ارتباطی',
        'عدم وجود آلارم خطای داخلی',
        'ثبت رویداد Fault در حافظه دستگاه',
        'وضعیت کابل‌کشی و اتصالات',
        'نظافت و تمیزی تجهیز'
    ],
    'پست دو سو تغذیه (بیمارستانی)': [
        'وضعیت تابلو اتوماسیون پست',
        'وضعیت RTU / FRTU (عدم ریست یا هنگ)',
        'وضعیت دریافت سیگنال فیدر اول در SCADA',
        'وضعیت دریافت سیگنال فیدر دوم در SCADA',
        'صحت تشخیص فیدر فعال',
        'وضعیت لاجیک اینترلاک (فقط بررسی)',
        'ثبت صحیح رویداد تغییر منبع تغذیه',
        'وضعیت ارسال آلارم‌های بحرانی',
        'وضعیت ولتاژ DC تابلو اتوماسیون',
        'وضعیت باتری و شارژر',
        'وضعیت لینک اصلی ارتباط مخابراتی',
        'وضعیت لینک پشتیبان ارتباطی (در صورت وجود)',
        'وضعیت کابل‌کشی داخل تابلو',
        'وضعیت ارت تجهیزات اتوماسیون',
        'عدم تأخیر غیرمجاز در ارسال دیتا',
        'ثبت صحیح لاگ‌ها و زمان رویداد',
        'نظافت تابلو و تجهیزات'
    ],
    'پست دو سو تغذیه (مشترک حساس)': [
        'پایداری عملکرد تجهیزات اتوماسیون',
        'وضعیت تشخیص منبع تغذیه فعال',
        'ثبت صحیح سوییچ بین فیدرها',
        'وضعیت ارسال آلارم‌های فوری',
        'وضعیت باتری بک‌آپ تجهیزات',
        'عملکرد صحیح شارژر DC',
        'وضعیت ارتباط مخابراتی اصلی',
        'وضعیت ارتباط مخابراتی پشتیبان',
        'عدم ریست ناخواسته RTU',
        'امنیت فیزیکی تابلو اتوماسیون',
        'ثبت صحیح زمان و ترتیب رویدادها',
        'تطابق تگ‌های ارسالی با SCADA',
        'نظافت تجهیزات و تابلو'
    ],
    'مشترک ولتاژ اولیه': [
        'وضعیت نصب تابلو اتوماسیون مشترک',
        'وضعیت عملکرد RTU مشترک',
        'وضعیت ارسال داده تجهیزات اندازه‌گیری (VT / CT)',
        'صحت نمایش ولتاژ و جریان در SCADA',
        'وضعیت آلارم‌های اضافه‌بار و قطع',
        'وضعیت ارتباط مخابراتی با مرکز',
        'وضعیت منبع تغذیه پشتیبان (باتری / UPS)',
        'وضعیت کابل‌کشی و اتصالات داخلی',
        'وضعیت ارت تجهیزات اتوماسیون',
        'عدم دستکاری غیرمجاز به تجهیزات',
        'ثبت صحیح رویدادها و لاگ‌ها',
        'نظافت تابلو و تجهیزات'
    ]
};

let postsAndFeedersData = [
    { post: 'امام شهر', feeders: ['401 امامشهر', '403 امامشهر', '404 امامشهر', '405 امامشهر', '406 امامشهر', '409 امامشهر', '410 امامشهر', '411 امامشهر', '412 امامشهر', '413 امامشهر'] },
    { post: 'آزادگان', feeders: ['401 آزادگان', '402 آزادگان', '403 آزادگان', '404 آزادگان', '405 آزادگان', '406 آزادگان', '407 آزادگان', '408 آزادگان', '410 آزادگان', '411 آزادگان', '412 آزادگان', '413 آزادگان'] },
    { post: 'پاکنژاد', feeders: ['401 پاکنژاد', '402 پاکنژاد', '403 پاکنژاد', '404 پاکنژاد', '405 پاکنژاد', '406 پاکنژاد', '407 پاکنژاد'] },
    { post: 'شرق', feeders: ['401 شرق', '403 شرق', '404 شرق', '405 شرق', '406 شرق', '407 شرق', '408 شرق', '409 شرق', '410 شرق', '411 شرق', '412 شرق', '414 شرق', '415 شرق', '417شرق'] },
    { post: 'شمال', feeders: ['401 شمال', '402 شمال', '403 شمال', '404 شمال', '406 شمال', '407 شمال', '408 شمال', '411 شمال', '413 شمال', '415 شمال', '417شمال'] },
    { post: 'شهرک صنعتي', feeders: ['401 شهرک', '403 شهرک', '404 شهرک', '405 شهرک', '406 شهرک', '407 شهرک', '409 شهرک', '410 شهرک', '411 شهرک', '412 شهرک', '413 شهرک', '414 شهرک', '415 شهرک', '416 شهرک', '418 شهرک', '419 شهرک', '420 شهرك', '421 شهرک', '422 شهرک', '423 شهرک'] },
    { post: 'غرب', feeders: ['401 غرب', '402 غرب', '403 غرب', '405 غرب', '406 غرب', '407 غرب', '408 غرب'] },
    { post: 'منتظر قائم', feeders: ['401 منتظرقائم', '402 منتظرقائم', '403 منتظرقائم', '404 منتظر قائم', '405 منتظرقائم', '406 منتظر قائم', '407 منتظرقائم', '408 منتظر قائم', '410 منتظر قائم', '411 منتظر قائم'] },
    { post: 'مدرس', feeders: ['401 مدرس', '402 مدرس', '403 مدرس', '404 مدرس', '405 مدرس', '406 مدرس', '407 مدرس', '408 مدرس', '410 مدرس', '411 مدرس', '412 مدرس', '413 مدرس'] },
    { post: 'جنوب', feeders: ['401 جنوب', '403 جنوب', '404 جنوب', '405 جنوب', '406 جنوب', '409 جنوب', '410 جنوب', '411 جنوب', '412 جنوب', '413 جنوب'] },
    { post: 'دانشگاه', feeders: ['401 دانشگاه', '403 دانشگاه', '404 دانشگاه', '405 دانشگاه', '406 دانشگاه', '407 دانشگاه', '408 دانشگاه', '409 دانشگاه', '410 دانشگاه', '413 دانشگاه'] },
    { post: 'فهرج', feeders: ['401 فهرج', '403 فهرج', '405 فهرج', '407 فهرج', '407فهرج', '411 فهرج', '413 فهرج'] },
    { post: 'دروازه قرآن', feeders: ['401 دروازه قرآن', '403 دروازه قرآن', '404 دروازه قرآن', '405 دروازه قرآن', '406 دروازه قرآن', '409 دروازه قرآن', '410 دروازه قرآن', '411 دروازه قرآن', '412 دروازه قرآن', '413 دروازه قرآن', '414 دروازه قرآن'] },
    { post: 'چرخاب', feeders: ['401 چرخاب', '402 چرخاب', '403 چرخاب', '404 چرخاب', '404چرخاب', '405 چرخاب', '406 چرخاب', '407 چرخاب', '408 چرخاب', '410 چرخاب', '411 چرخاب', '412 چرخاب', '413 چرخاب'] },
    { post: 'زارچ', feeders: ['402 زارچ', '404 زارچ'] },
    { post: 'سیار بردیا(سیار زارچ)', feeders: ['401 سیار بردیا(سیار زارچ)', '402 سیار بردیا(سیار زارچ)', '403 سیار بردیا(سیار زارچ)', '404 سیار بردیا(سیار زارچ)'] },
    { post: 'سرو', feeders: ['401 سرو', '402 سرو'] },
    { post: 'اردکان', feeders: ['402 اردکان', '403 اردکان', '404 اردکان', '405 اردکان', '406 اردکان', '408 اردکان', '410 اردكان', '411 اردكان', '413 اردكان', '415 اردكان'] },
    { post: 'ترک آباد', feeders: ['401 ترك آباد', '402 ترك آباد', '403 ترک آباد', '404 ترك آباد', '405 ترك آباد', '406 ترک آباد', '407 ترک آباد', '408 ترك آباد', '410 ترك آباد', '411 ترك آباد'] },
    { post: 'چادرملو', feeders: ['414 چادرملو'] },
    { post: 'امامزاده', feeders: ['404 امام زاده', '405 امامزاده', '406 امامزاده', '407 امامزاده', '408 امامزاده', '410 امام زاده', '411 امامزاده', '413 امامزاده'] },
    { post: 'ساغند', feeders: ['401 ساغند', '403 ساغند', '405 ساغند'] },
    { post: 'خورشید', feeders: ['407 خورشید', '411 خورشید'] },
    { post: 'جهان آباد', feeders: ['401 جهان آباد', '402 جهان آباد', '403 جهان آباد', '404 جهان آباد', '405 جهان آباد', '406 جهان آباد', '407 جهان آباد', '408 جهان آباد', '410 جهان آباد', '411 جهان آباد', '412 جهان آباد', '413 جهان اباد', '414 جهان آباد', '417 جهان آباد'] },
    { post: 'ميبد', feeders: ['401 ميبد', '402 ميبد', '403 ميبد', '404 میبد', '405 ميبد', '406 ميبد', '407 ميبد', '408 ميبد', '411 ميبد', '410 میبد', '412 میبد', '413 ميبد'] },
    { post: 'مزرعه کلانتر', feeders: ['401 مزرعه کلانتر', '402 مزرعه کلانتر', '403 مزرعه کلانتر', '405 مزرعه کلانتر', '407 مزرعه کلانتر', '408 مزرعه کلانتر', '410 مزرعه کلانتر', '412 مزرعه کلانتر'] },
    { post: 'صدوق', feeders: ['401 صدوق', '402صدوق', '403 صدوق', '404 صدوق', '405 صدوق', '406 صدوق', '407 صدوق', '408 صدوق', '410 صدوق', '411 صدوق', '412 صدوق', '413 صدوق', '414صدوق', '415 صدوق', '420 صدوق'] },
    { post: 'رستاق', feeders: ['401 رستاق', '402 رستاق', '403 رستاق', '404 رستاق', '405 رستاق', '406 رستاق', '407 رستاق', '408 رستاق', '410 رستاق', '411 رستاق', '412 رستاق', '413 رستاق'] },
    { post: 'خضر آباد', feeders: ['401 خضر آباد', '402خضرآباد', '403خضر آباد', '404خضر آباد', '405خضر آباد', '406خضر آباد', '407خضر آباد', '408خضر آباد', '410خضر آباد', '411خضر آباد', '412 خضرآباد', '413 خضر آباد', '417 خضر آباد'] },
    { post: 'ابرکوه', feeders: ['401 ابرکوه', '402 ابرکوه', '403 ابرکوه', '404 ابرکوه', '406 ابرکوه', '407 ابرکوه', '408 ابرکوه', '410 ابركوه', '411 ابرکوه'] },
    { post: 'موبایل بهمن( کوثر)', feeders: ['401 موبایل بهمن (کوثر)', '402 موبايل بهمن(کوثر)', '403 موبایل بهمن (کوثر)'] },
    { post: 'باستان', feeders: ['401 باستان', '402 باستان', '403 باستان'] },
    { post: 'پست 230 ابرکوه', feeders: ['401 ابرکوه 230', '402 ابرکوه 230'] },
    { post: 'تفت', feeders: ['402 تفت', '403 تفت', '404 تفت', '405 تفت', '406 تفت', '407 تفت', '408 تفت', '410 تفت', '411 تفت', '412 تفت', '413 تفت', '415 تفت'] },
    { post: 'فيض آباد', feeders: ['402 فيض آباد', '403 فيض آباد', '404 فيض آباد', '405 فيض آباد', '406 فيض آباد', '407 فيض آباد'] },
    { post: 'نیر', feeders: ['401 نیر', '402 نیر', '403 نير', '406 نیر', '407 نیر', '408 نیر'] },
    { post: 'مهريز', feeders: ['402 مهريز', '403 مهريز', '404 مهريز', '405 مهريز', '406 مهريز', '407 مهريz', '408 مهريز', '410 مهريz', '411 مهريz', '413 مهريz', '417 مهريz'] },
    { post: 'یزدمهر', feeders: ['401 یزدمهر', '402 یزدمهر', '404 یزد مهر', '405 یزدمهر', '406 یزد مهر', '407 یزدمهر', '410 یزدمهر', '411 یزد مهر', '412 یزدمهر'] },
    { post: 'سریزد', feeders: ['401 پست دائم سریزد', '402 پست دائم سریزد', '403 پست دائم سریزد', '404 پست دائم سریزد', '405 پست دائم سریزد', '406 پست دائم سریزد', '407 پست دائم سریزد', '408 پست دائم سریزد', '410 پست دائم سریزد', '411 پست دائم سریزد'] },
    { post: 'بافق', feeders: ['401 بافق', '402 بافق', '403 بافق', '404 بافق', '405 بافق', '406 بافق', '410 بافق'] },
    { post: 'کوشک', feeders: ['401 کوشک', '403 کوشك', '404 کوشک', '406 کوشک', '409 کوشک', '411 کوشک', '412 کوشک'] },
    { post: 'بهاباد', feeders: ['401 بهاباد', '403 بهاباد', '405 بهاباد', '407 بهاباد'] },
    { post: 'سیار بهاباد(فراز)', feeders: ['401 فراز', '403 فراز', '405 فراز', '407 فراز'] },
    { post: 'چاهك', feeders: ['401 چاهك', '403 چاهك', '405 چاهك', '407 چاهك', '409 چاهك', '411 چاهک'] },
    { post: 'هرات', feeders: ['401 هرات', '403 هرات', '404 هرات', '406 هرات', '407 هرات', '408 هرات', '410 هرات'] },
    { post: 'مروست', feeders: ['401 مروست', '402 مروست', '403 مروست', '404 مروست', '405 مروست', '406 مروست', '407 مروست', '408 مروست'] },
    { post: 'خورشید', feeders: ['401 خورشید', '402 خورشید', '403 خورشید', '404 خورشید', '405 خورشید', '406 خورشید', '407 خورشید'] }
];

let postsList = postsAndFeedersData.map(item => item.post);

// =====================================================
// Utility Functions
// =====================================================

function formatNumber(num) {
    if (!num && num !== 0) return '۰';
    return new Intl.NumberFormat('fa-IR').format(num);
}

function checkLibraries() {
    if (typeof XLSX === 'undefined') {
        console.error('XLSX library not loaded!');
        return false;
    }
    return true;
}

// الگوریتم تبدیل میلادی به شمسی
function gregorianToJalali(gy, gm, gd) {
    var g_d_m = [0, 31, 59, 90, 120, 151, 181, 212, 243, 273, 304, 334];
    var jy = (gy <= 1600) ? 0 : 979;
    gy -= (gy <= 1600) ? 621 : 1600;
    var gy2 = (gm > 2) ? (gy + 1) : gy;
    var days = (365 * gy) + parseInt((gy2 + 3) / 4) - parseInt((gy2 + 99) / 100) + parseInt((gy2 + 399) / 400) - 80 + gd + g_d_m[gm - 1];
    jy += 33 * parseInt(days / 12053);
    days %= 12053;
    jy += 4 * parseInt(days / 1461);
    days %= 1461;
    jy += parseInt((days - 1) / 365);
    if (days > 365) days = (days - 1) % 365;
    var jm = (days < 186) ? 1 + parseInt(days / 31) : 7 + parseInt((days - 186) / 30);
    var jd = 1 + ((days < 186) ? (days % 31) : ((days - 186) % 30));
    return [jy, jm, jd];
}

function formatJalaliDate(dateArray) {
    if (!dateArray || dateArray.length < 3) return '';
    const toPersian = (num) => {
        return num.toString().replace(/\d/g, d => '۰۱۲۳۴۵۶۷۸۹'[d]);
    };
    const year = toPersian(dateArray[0]);
    const month = toPersian(('0' + dateArray[1]).slice(-2));
    const day = toPersian(('0' + dateArray[2]).slice(-2));
    return year + '/' + month + '/' + day;
}

function getCurrentJalaliDate() {
    const now = new Date();
    return gregorianToJalali(now.getFullYear(), now.getMonth() + 1, now.getDate());
}

function saveAsFile(blob, filename) {
    const link = document.createElement('a');
    const url = URL.createObjectURL(blob);
    link.href = url;
    link.download = filename;
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
    setTimeout(() => URL.revokeObjectURL(url), 150);
    
    if (window.navigator && window.navigator.msSaveBlob) {
        window.navigator.msSaveBlob(blob, filename);
    }
}

// تابع اصلاح مقادیر timepicker در صورت وجود خطا
function fixTimeValues() {
    var startTime = document.getElementById('daily-start-time');
    var endTime = document.getElementById('daily-end-time');
    
    if (startTime) {
        startTime.value = '08:00';
    }
    if (endTime) {
        endTime.value = '14:00';
    }
}

// =====================================================
// Step Navigation Functions
// =====================================================

function goToStep(step) {
    document.querySelectorAll('.form-step').forEach(el => {
        el.classList.remove('active');
    });
    
    const stepElement = document.getElementById(`step-${step}`);
    if (stepElement) {
        stepElement.classList.add('active');
    }
    
    updateStepIndicator(step);
    
    if (step === 2 && equipmentCount === 0) {
        addNewEquipment();
    }
    
    if (step === 3) {
        updateTechnicalInfoSections();
    }
    
    if (step === 4) {
        updateSummary();
    }
    triggerAutoSave();
}

function updateStepIndicator(step) {
    document.querySelectorAll('.step').forEach((stepEl, index) => {
        stepEl.classList.remove('active', 'completed');
        if (index + 1 === step) {
            stepEl.classList.add('active');
        } else if (index + 1 < step) {
            stepEl.classList.add('completed');
        }
    });
}

// =====================================================
// Equipment Management Functions
// =====================================================

function addNewEquipment() {
    equipmentCount++;
    const equipmentId = `eq-${Date.now()}-${equipmentCount}`;
    
    const equipmentData = {
        id: equipmentId,
        index: equipmentCount,
        equipmentType: '',
        scadaCode: '',
        installationType: '',
        switchBrand: '',
        modemBrand: '',
        rtuBrand: '',
        otherSwitchBrand: '',
        otherModemBrand: '',
        otherRTUBrand: '',
        startTime: '',
        endTime: '',
        tabsValidated: {},
        activitiesData: [],
        consumablesData: [],
        feeders: [],
        departmentData: {
            department: '',
            city: ''
        }
    };
    
    equipments.push(equipmentData);
    
    const equipmentCard = document.createElement('div');
    equipmentCard.className = 'equipment-card';
    equipmentCard.id = equipmentId;
    
    equipmentCard.innerHTML = `
        <div class="equipment-header">
            <div>
                <i class="bi bi-hdd"></i>
                <span class="ms-2">تجهیز ${equipmentCount}</span>
            </div>
            <div>
                <button class="btn btn-sm btn-outline-light me-2" onclick="editEquipment('${equipmentId}')">
                    <i class="bi bi-pencil"></i> ویرایش
                </button>
                <button class="btn btn-sm btn-outline-danger" onclick="removeEquipment('${equipmentId}')">
                    <i class="bi bi-trash"></i> حذف
                </button>
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-4">
                    <p><i class="bi bi-gear"></i> <strong>نوع تجهیز:</strong> <span id="${equipmentId}-type">---</span></p>
                </div>
                <div class="col-md-4">
                    <p><i class="bi bi-code-slash"></i> <strong>کد اسکادا:</strong> <span id="${equipmentId}-scada">---</span></p>
                </div>
                <div class="col-md-4">
                    <p><i class="bi bi-tag"></i> <strong>برند کلید:</strong> <span id="${equipmentId}-switch-brand">---</span></p>
                </div>
                <div class="col-md-4">
                    <p><i class="bi bi-clipboard-check"></i> <strong>وضعیت اطلاعات فنی:</strong> <span id="${equipmentId}-status" class="badge bg-danger">فاقد اطلاعات</span></p>
                </div>
                <div class="col-md-8">
                    <p><i class="bi bi-lightning-charge"></i> <strong>فیدرها:</strong> <span id="${equipmentId}-feeders">---</span></p>
                </div>
            </div>
        </div>
    `;
    
    const container = document.getElementById('equipment-container');
    if (container) {
        container.appendChild(equipmentCard);
    }
    editEquipment(equipmentId);
    triggerAutoSave();
}

function saveEquipmentDataFromModal() {
    const currentEquipment = getCurrentEquipment();
    const equipmentIndex = currentEquipment.index;
    
    const installationType = $('#modal-installation-type').val();
    const brandId = $('#modal-brand-id').val();
    
    if (window.equipmentsData && window.equipmentsData[equipmentIndex]) {
        window.equipmentsData[equipmentIndex].installationType = installationType;
        window.equipmentsData[equipmentIndex].brand_id = brandId;
    }
    
    $(`#equipment-${equipmentIndex}`).data('installation-type', installationType);
    $(`#equipment-${equipmentIndex}`).data('brand-id', brandId);
}

function editEquipment(equipmentId) {
    const equipment = equipments.find(e => e.id === equipmentId);
    if (!equipment) return;
    
    currentEquipmentIndex = equipment.index;
    
    // ========== بررسی اینکه آیا این تجهیز باید برند داشته باشد یا نه ==========
    const isWithoutBrand = equipmentWithoutBrands.includes(equipment.equipmentType);
    
    // ساخت گزینه‌های برند با ID از دیتابیس (فقط برای تجهیزات دارای برند)
    let brandOptions = '';
    
    if (!isWithoutBrand) {
        brandOptions = '<option value="">انتخاب کنید...</option>';
        if (window.brandsList && window.brandsList.length) {
            window.brandsList.forEach(b => {
                brandOptions += `<option value="${b.id}">${b.name}</option>`;
            });
            console.log('✅ گزینه‌های برند ساخته شد، تعداد:', window.brandsList.length);
        } else {
            console.warn('⚠️ window.brandsList خالی است، استفاده از fallback');
            brandOptions += `
                <option value="1">ABB</option>
                <option value="2">Schneider</option>
                <option value="3">Siemens</option>
                <option value="4">NOJA</option>
                <option value="5">Tavrida</option>
                <option value="6">Eaton</option>
                <option value="7">سایر</option>
            `;
        }
    }
    
    const modalContent = `
        <form id="equipment-form-${equipmentId}" onsubmit="saveEquipment('${equipmentId}'); return false;">
            <div class="row mb-3">
                <div class="col-md-6">
                    <label class="form-label required"><i class="bi bi-gear"></i> نوع تجهیز</label>
                    <select class="form-select equipment-type-select" required 
                            onchange="updateEquipmentFields('${equipmentId}')">
                        <option value="">انتخاب کنید</option>
                        ${equipmentTypes.map(type => 
                            `<option value="${type}" ${equipment.equipmentType === type ? 'selected' : ''}>${type}</option>`
                        ).join('')}
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label required"><i class="bi bi-code-slash"></i> کد اسکادا (4 رقم)</label>
                    <input type="text" class="form-control scada-code" required 
                           maxlength="4" pattern="[0-9]{4}" 
                           title="کد اسکادا باید 4 رقم باشد"
                           value="${equipment.scadaCode || ''}">
                    <small class="text-muted"><i class="bi bi-info-circle"></i> کد 4 رقمی اسکادا را وارد کنید</small>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    ${!isWithoutBrand ? `
                    <label class="form-label">برند:</label>
                    <select class="form-control" id="modal-brand-id-${equipmentId}">
                        ${brandOptions}
                    </select>
                    ` : '<div style="display: none;"></div>'}
                </div>
            </div>
            
            <div class="department-container">
                <h6><i class="bi bi-building"></i> اطلاعات امور و شهرستان</h6>
                <div class="row">
                    <div class="col-md-6">
                        <label class="form-label required">امور شهرستان</label>
                        <select class="form-select department-select" required>
                            <option value="">انتخاب کنید</option>
                            ${cityDepartments.map(dept => 
                                `<option value="${dept}" ${equipment.departmentData?.department === dept ? 'selected' : ''}>${dept}</option>`
                            ).join('')}
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label required">GIS Code</label>
                        <input type="text" class="form-control city" required 
                               value="${equipment.departmentData?.city || ''}" 
                               placeholder="OID">
                    </div>
                </div>
            </div>
            
            <div id="brand-section-${equipmentId}">
                <!-- Brand fields will be dynamically added here -->
            </div>
            
            <div id="installation-type-section-${equipmentId}">
                <!-- Installation type section will be dynamically added -->
            </div>
            
            <div id="feeder-section-${equipmentId}">
                <!-- Feeder fields will be dynamically added here -->
            </div>
            
            <div id="cell-specs-${equipmentId}" style="display: none;">
                <!-- Cell specifications for posts and primary voltage customers -->
            </div>
            
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><i class="bi bi-x"></i> انصراف</button>
                <button type="submit" class="btn btn-primary"><i class="bi bi-save"></i> ذخیره تجهیز</button>
            </div>
        </form>
    `;
    
    const modalBody = document.querySelector('#equipmentModal .modal-body');
    if (modalBody) {
        modalBody.innerHTML = modalContent;
    }
    
    setTimeout(() => {
        const form = document.getElementById(`equipment-form-${equipmentId}`);
        if (form) {
            const typeSelect = form.querySelector('.equipment-type-select');
            if (typeSelect && typeof $ !== 'undefined') {
                $(typeSelect).select2({
                    placeholder: 'انتخاب کنید',
                    allowClear: true,
                    width: '100%',
                    dropdownParent: $('#equipmentModal'),
                    dir: 'rtl'
                });
            }
            
            const deptSelect = form.querySelector('.department-select');
            if (deptSelect && typeof $ !== 'undefined') {
                $(deptSelect).select2({
                    placeholder: 'انتخاب کنید',
                    allowClear: true,
                    width: '100%',
                    dropdownParent: $('#equipmentModal'),
                    dir: 'rtl'
                });
            }
            
            // ========== لود مقدار برند ذخیره شده (فقط برای تجهیزات دارای برند) ==========
            if (!isWithoutBrand) {
                const brandSelect = document.getElementById(`modal-brand-id-${equipmentId}`);
                if (brandSelect) {
                    console.log('سلیکت برند پیدا شد، تعداد گزینه‌ها:', brandSelect.options.length);
                    if (equipment.brand_id) {
                        brandSelect.value = equipment.brand_id;
                        console.log('✅ مقدار برند تنظیم شد:', equipment.brand_id);
                        if (typeof $ !== 'undefined' && $(brandSelect).hasClass('select2-hidden-accessible')) {
                            $(brandSelect).trigger('change');
                        }
                    } else {
                        console.log('⚠️ equipment.brand_id ندارد، مقدار پیش‌فرض انتخاب نشد');
                    }
                } else {
                    console.error('❌ سلیکت برند پیدا نشد!');
                }
            }
            // ============================================
        }
        
        if (equipment.equipmentType) {
            const typeSelect = document.querySelector(`#equipment-form-${equipmentId} .equipment-type-select`);
            if (typeSelect && typeof $ !== 'undefined') {
                $(typeSelect).val(equipment.equipmentType).trigger('change');
            }
        }
    }, 200);
    
    const modalElement = document.getElementById('equipmentModal');
    if (modalElement && typeof bootstrap !== 'undefined') {
        const modal = new bootstrap.Modal(modalElement);
        modal.show();
    }
}

function updateEquipmentFields(equipmentId) {

    const form = document.getElementById(`equipment-form-${equipmentId}`);
    if (!form) return;
    
    const typeSelect = form.querySelector('.equipment-type-select');
    const equipmentType = typeSelect ? (typeof $ !== 'undefined' ? $(typeSelect).val() : typeSelect.value) : '';

    const isWithoutBrand = equipmentWithoutBrands.includes(equipmentType);
    
    // بررسی برای نمایش مشخصات سلول‌ها
    const shouldShowCellSpecs = [
        'پست دو سو تغذیه (مشترک حساس)',
        'پست دو سو تغذیه (بیمارستانی)',
        'مشترک ولتاژ اولیه'
    ].includes(equipmentType);
    
    const brandSection = document.getElementById(`brand-section-${equipmentId}`);
    const installationSection = document.getElementById(`installation-type-section-${equipmentId}`);
    const feederSection = document.getElementById(`feeder-section-${equipmentId}`);
    const cellSpecsDiv = document.getElementById(`cell-specs-${equipmentId}`);
    
    if (brandSection) brandSection.innerHTML = '';
    if (installationSection) installationSection.innerHTML = '';
    if (feederSection) feederSection.innerHTML = '';
    
    const equipment = equipments.find(e => e.id === equipmentId);
    
    // ========== مدیریت برند سلکت (مخفی یا نمایش) ==========
    const brandSelect = document.getElementById(`modal-brand-id-${equipmentId}`);
    
    if (isWithoutBrand) {
        // اگر تجهیز بدون برند است، برند سلکت را مخفی کن
        if (brandSelect) {
            const parentCol = brandSelect.closest('.col-md-6');
            if (parentCol) parentCol.style.display = 'none';
        }
    } else {
        // اگر تجهیز دارای برند است، برند سلکت را نشان بده و آپدیت کن
        if (brandSelect && window.brandsList) {
            const parentCol = brandSelect.closest('.col-md-6');
            if (parentCol) parentCol.style.display = 'block';
            
            let options = '<option value="">انتخاب کنید...</option>';
            window.brandsList.forEach(b => {
                options += `<option value="${b.id}">${b.name}</option>`;
            });
            brandSelect.innerHTML = options;
            
            if (equipment && equipment.brand_id) {
                brandSelect.value = equipment.brand_id;
            }
            
            if (typeof $ !== 'undefined' && $(brandSelect).hasClass('select2-hidden-accessible')) {
                $(brandSelect).trigger('change');
            }
        }
    }
    
    // ========== نمایش بخش‌های مختلف بر اساس نوع تجهیز ==========
    if (equipmentWithBrands.includes(equipmentType)) {
        // تجهیزات دارای برند (ریکلوزر، سکسیونر، سکشنالایزر، فالت دتکتور)
        if (brandSection && equipment) {
            brandSection.innerHTML = `
                <div class="row mb-3">
                    <div class="col-md-4">
                        <label class="form-label required"><i class="bi bi-wifi"></i> برند مودم</label>
                        <select class="form-select modem-brand-select" required onchange="toggleBrandOther(this, '${equipmentId}', 'modem')">
                            <option value="">انتخاب کنید</option>
                            ${modemBrands.map(brand => 
                                `<option value="${brand}" ${equipment.modemBrand === brand ? 'selected' : ''}>${brand}</option>`
                            ).join('')}
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label required"><i class="bi bi-cpu"></i> برند RTU</label>
                        <select class="form-select rtu-brand-select" required onchange="toggleBrandOther(this, '${equipmentId}', 'rtu')">
                            <option value="">انتخاب کنید</option>
                            ${rtuBrands.map(brand => 
                                `<option value="${brand}" ${equipment.rtuBrand === brand ? 'selected' : ''}>${brand}</option>`
                            ).join('')}
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label required"><i class="bi bi-clock"></i> زمان شروع فعالیت</label>
                        <input type="time" class="form-control start-time" required value="${equipment.startTime || ''}">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label required"><i class="bi bi-clock-fill"></i> زمان پایان فعالیت</label>
                        <input type="time" class="form-control end-time" required value="${equipment.endTime || ''}">
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-4" id="modem-other-container-${equipmentId}"></div>
                    <div class="col-md-4" id="rtu-other-container-${equipmentId}"></div>
                </div>
            `;
            
            setTimeout(() => {
                if (typeof $ !== 'undefined') {
                    $(`#equipment-form-${equipmentId} .modem-brand-select`).select2({
                        placeholder: 'انتخاب کنید',
                        allowClear: true,
                        width: '100%',
                        dropdownParent: $('#equipmentModal'),
                        dir: 'rtl'
                    });
                    $(`#equipment-form-${equipmentId} .rtu-brand-select`).select2({
                        placeholder: 'انتخاب کنید',
                        allowClear: true,
                        width: '100%',
                        dropdownParent: $('#equipmentModal'),
                        dir: 'rtl'
                    });
                }
            }, 100);
        }
        
        // مخفی کردن سلول‌ها برای تجهیزات دارای برند
        if (cellSpecsDiv) {
            cellSpecsDiv.style.display = 'none';
            cellSpecsDiv.innerHTML = '';
        }
    } 
    else if (isWithoutBrand) {
        // تجهیزات بدون برند (پست دو سو تغذیه و مشترک ولتاژ اولیه)
        if (brandSection && equipment) {
            brandSection.innerHTML = `
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label required"><i class="bi bi-clock"></i> زمان شروع فعالیت</label>
                        <input type="time" class="form-control start-time" required value="${equipment.startTime || ''}">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label required"><i class="bi bi-clock-fill"></i> زمان پایان فعالیت</label>
                        <input type="time" class="form-control end-time" required value="${equipment.endTime || ''}">
                    </div>
                </div>
            `;
        }
        
        // نمایش مشخصات سلول‌ها فقط برای این سه نوع تجهیز
        if (shouldShowCellSpecs) {
            if (cellSpecsDiv) {
                cellSpecsDiv.style.display = 'block';
                cellSpecsDiv.innerHTML = getCellSpecificationsHTML(equipmentId, equipmentType);
                
                if (equipment && equipment.cellSpecs) {
                    setTimeout(() => {
                        loadCellSpecifications(equipmentId, equipment.cellSpecs);
                    }, 300);
                }
            }
        } else {
            if (cellSpecsDiv) {
                cellSpecsDiv.style.display = 'none';
                cellSpecsDiv.innerHTML = '';
            }
        }
    }
    
    // ========== منطق نوع نصب (فقط برای سکسیونر و سکشنالایزر) ==========
    if (equipmentType === 'سکسیونر' || equipmentType === 'سکشنالایزر') {
        if (installationSection && equipment) {
            installationSection.innerHTML = `
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label required"><i class="bi bi-lightning"></i> نوع نصب</label>
                        <select class="form-select installation-type-select" required onchange="updateFeederFields('${equipmentId}')">
                            <option value="">انتخاب کنید</option>
                            <option value="بین‌فیدری" ${equipment.installationType === 'بین‌فیدری' ? 'selected' : ''}>بین‌فیدری</option>
                            <option value="مانوری" ${equipment.installationType === 'مانوری' ? 'selected' : ''}>مانوری</option>
                        </select>
                    </div>
                </div>
            `;
            
            setTimeout(() => {
                if (typeof $ !== 'undefined') {
                    $(installationSection).find('.installation-type-select').select2({
                        placeholder: 'انتخاب کنید',
                        allowClear: true,
                        width: '100%',
                        dropdownParent: $('#equipmentModal'),
                        dir: 'rtl'
                    });
                }
                if (equipment.installationType) {
                    updateFeederFields(equipmentId);
                }
            }, 100);
        }
    } else {
        setTimeout(() => {
            updateFeederFields(equipmentId);
        }, 100);
    }
}

function toggleBrandOther(select, equipmentId, brandType) {
    const container = document.getElementById(`${brandType}-other-container-${equipmentId}`);
    if (container) {
        if (select.value === 'سایر') {
            container.innerHTML = `
                <label class="form-label required">نام برند دیگر</label>
                <input type="text" class="form-control" id="${brandType}-other-input-${equipmentId}" required>
            `;
        } else {
            container.innerHTML = '';
        }
    }
}

function updateFeederFields(equipmentId) {
    const form = document.getElementById(`equipment-form-${equipmentId}`);
    if (!form) return;
    
    const typeSelect = form.querySelector('.equipment-type-select');
    const equipmentType = typeSelect ? (typeof $ !== 'undefined' ? $(typeSelect).val() : typeSelect.value) : '';
    
    const installSelect = form.querySelector('.installation-type-select');
    const installationType = installSelect ? (typeof $ !== 'undefined' ? $(installSelect).val() : installSelect.value) : '';
    
    const feederSection = document.getElementById(`feeder-section-${equipmentId}`);
    if (!feederSection) return;
    
    let feederCount = 1;
    
    if (equipmentType === 'سکسیونر' || equipmentType === 'سکشنالایزر') {
        feederCount = installationType === 'مانوری' ? 2 : 1;
    } else if (['پست دو سو تغذیه (مشترک حساس)','پست دو سو تغذیه (بیمارستانی)'].includes(equipmentType)) {
        feederCount = 2;
    }
    
    let feederHTML = '<div class="row mb-3"><div class="col-md-12"><h6><i class="bi bi-lightning-charge"></i> انتخاب فیدرها</h6></div></div>';
    
    for (let i = 1; i <= feederCount; i++) {
        feederHTML += `
            <div class="row mb-3">
                <div class="col-md-6">
                    <label class="form-label required">پست فیدر ${i}</label>
                    <select class="form-select feeder-post-select" onchange="updateFeederOptions('${equipmentId}', this, ${i})">
                        <option value="">انتخاب کنید</option>
                        ${getPostsOptions()}
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label required">فیدر ${i}</label>
                    <select class="form-select feeder-select" id="feeder-select-${equipmentId}-${i}">
                        <option value="">ابتدا پست را انتخاب کنید</option>
                    </select>
                </div>
            </div>
        `;
    }
    
    feederSection.innerHTML = feederHTML;
    
    setTimeout(() => {
        if (typeof $ !== 'undefined') {
            $(feederSection).find('.feeder-post-select').each(function() {
                $(this).select2({
                    placeholder: 'انتخاب کنید',
                    allowClear: true,
                    width: '100%',
                    dropdownParent: $('#equipmentModal'),
                    dir: 'rtl'
                });
            });
            $(feederSection).find('.feeder-select').each(function() {
                $(this).select2({
                    placeholder: 'انتخاب کنید',
                    allowClear: true,
                    width: '100%',
                    dropdownParent: $('#equipmentModal'),
                    dir: 'rtl'
                });
            });
        }
        
        setTimeout(() => {
            const equipment = equipments.find(e => e.id === equipmentId);
            if (equipment && equipment.feeders && equipment.feeders.length > 0) {
                const postSelects = feederSection.querySelectorAll('.feeder-post-select');
                postSelects.forEach((postSelect, index) => {
                    if (equipment.feeders[index]) {
                        if (typeof $ !== 'undefined') {
                            $(postSelect).val(equipment.feeders[index].post).trigger('change');
                        } else {
                            postSelect.value = equipment.feeders[index].post;
                        }
                        
                        setTimeout(() => {
                            updateFeederOptions(equipmentId, postSelect, index + 1);
                            const feederSelect = document.getElementById(`feeder-select-${equipmentId}-${index + 1}`);
                            if (feederSelect && equipment.feeders[index].feeder) {
                                if (typeof $ !== 'undefined') {
                                    $(feederSelect).val(equipment.feeders[index].feeder).trigger('change');
                                } else {
                                    feederSelect.value = equipment.feeders[index].feeder;
                                }
                            }
                        }, 300);
                    }
                });
            }
        }, 300);
    }, 200);
}

function updateFeederOptions(equipmentId, postSelect, feederIndex) {
    const post = postSelect.value;
    const feederSelect = document.getElementById(`feeder-select-${equipmentId}-${feederIndex}`);
    
    if (!post) {
        if (feederSelect) {
            feederSelect.innerHTML = '<option value="">ابتدا پست را انتخاب کنید</option>';
            if (feederSelect.select2 && typeof $ !== 'undefined') {
                $(feederSelect).trigger('change.select2');
            }
        }
        return;
    }
    
    const selectedPost = postsAndFeedersData.find(p => p.post === post);
    let options = '<option value="">انتخاب کنید</option>';
    
    if (selectedPost && selectedPost.feeders) {
        selectedPost.feeders.forEach(feeder => {
            options += `<option value="${feeder}">${feeder}</option>`;
        });
    }
    
    if (feederSelect) {
        feederSelect.innerHTML = options;
        setTimeout(() => {
            if (feederSelect.select2 && typeof $ !== 'undefined') {
                $(feederSelect).trigger('change.select2');
            }
        }, 50);
    }
}

function getPostsOptions() {
    return postsList.map(post => `<option value="${post}">${post}</option>`).join('');
}

function getCellSpecificationsHTML(equipmentId, equipmentType) {
    const equipment = equipments.find(e => e.id === equipmentId);
    const cellSpecs = equipment?.cellSpecs || {};
    
    return `
        <h6 class="border-bottom pb-2 mt-3 mb-3"><i class="bi bi-grid-3x3-gap"></i> مشخصات سلول‌ها</h6>
        
        <div class="row mb-4">
            <div class="col-md-12">
                <h6><i class="bi bi-arrow-right-circle"></i> سلول‌های ورودی</h6>
                <div class="row mb-2">
                    <div class="col-md-3">
                        <label class="form-label">تعداد سلول‌های ورودی</label>
                        <input type="number" class="form-control input-cells-count" min="0" 
                               value="${cellSpecs.inputCellsCount || 0}" 
                               onchange="updateCellSections('${equipmentId}', 'input')">
                    </div>
                </div>
                <div id="input-cells-container-${equipmentId}"></div>
            </div>
        </div>
        
        <div class="row mb-4">
            <div class="col-md-12">
                <h6><i class="bi bi-arrow-left-circle"></i> سلول‌های خروجی</h6>
                <div class="row mb-2">
                    <div class="col-md-3">
                        <label class="form-label">تعداد سلول‌های خروجی</label>
                        <input type="number" class="form-control output-cells-count" min="0" 
                               value="${cellSpecs.outputCellsCount || 0}" 
                               onchange="updateCellSections('${equipmentId}', 'output')">
                    </div>
                </div>
                <div id="output-cells-container-${equipmentId}"></div>
            </div>
        </div>
        
        <div class="row mb-4">
            <div class="col-md-12">
                <h6><i class="bi bi-speedometer2"></i> سلول‌های اندازه‌گیری</h6>
                <div class="row mb-2">
                    <div class="col-md-3">
                        <label class="form-label">تعداد سلول‌های اندازه‌گیری</label>
                        <input type="number" class="form-control measurement-cells-count" min="0" 
                               value="${cellSpecs.measurementCellsCount || 0}" 
                               onchange="updateCellSections('${equipmentId}', 'measurement')">
                    </div>
                </div>
                <div id="measurement-cells-container-${equipmentId}"></div>
            </div>
        </div>
        
        <div class="row mb-3">
            <div class="col-md-12">
                <label class="form-label"><i class="bi bi-chat-left-text"></i> توضیحات سایر تجهیزات</label>
                <textarea class="form-control other-equipments-notes" rows="2" 
                          placeholder="توضیحات سایر تجهیزات">${cellSpecs.otherEquipmentsNotes || ''}</textarea>
            </div>
        </div>
    `;
}

function loadCellSpecifications(equipmentId, cellSpecs) {
    const form = document.getElementById(`equipment-form-${equipmentId}`);
    if (!form || !cellSpecs) return;
    
    const inputCellsCount = form.querySelector('.input-cells-count');
    const outputCellsCount = form.querySelector('.output-cells-count');
    const measurementCellsCount = form.querySelector('.measurement-cells-count');
    
    if (cellSpecs.inputCellsCount > 0 && inputCellsCount) {
        inputCellsCount.value = cellSpecs.inputCellsCount;
        updateCellSections(equipmentId, 'input');
        loadCellEquipments(equipmentId, 'input', cellSpecs.inputCells);
    }
    
    if (cellSpecs.outputCellsCount > 0 && outputCellsCount) {
        outputCellsCount.value = cellSpecs.outputCellsCount;
        updateCellSections(equipmentId, 'output');
        loadCellEquipments(equipmentId, 'output', cellSpecs.outputCells);
    }
    
    if (cellSpecs.measurementCellsCount > 0 && measurementCellsCount) {
        measurementCellsCount.value = cellSpecs.measurementCellsCount;
        updateCellSections(equipmentId, 'measurement');
        loadCellEquipments(equipmentId, 'measurement', cellSpecs.measurementCells);
    }
}

function loadCellEquipments(equipmentId, cellType, cells) {
    if (!cells || cells.length === 0) return;
    
    cells.forEach(cell => {
        const cellNumber = cell.cellNumber;
        const container = document.getElementById(`${cellType}-cell-${cellNumber}-equipments-${equipmentId}`);
        const notesField = document.getElementById(`${cellType}-cell-${cellNumber}-notes-${equipmentId}`);
        
        if (notesField && cell.notes) {
            notesField.value = cell.notes;
        }
        
        if (cell.equipments && cell.equipments.length > 0 && container) {
            cell.equipments.forEach((equipment, eqIndex) => {
                addEquipmentToCell(equipmentId, cellType, cellNumber);
                
                setTimeout(() => {
                    const equipmentContainers = container.querySelectorAll('.equipment-type-container');
                    const lastContainer = equipmentContainers[equipmentContainers.length - 1];
                    
                    if (lastContainer) {
                        const typeSelect = lastContainer.querySelector('.equipment-type-select');
                        const brandSelect = lastContainer.querySelector('.equipment-brand-select');
                        const otherBrandInput = lastContainer.querySelector('.other-brand-input');
                        const manualInput = lastContainer.querySelector('.manual-equipment-name');
                        
                        if (typeSelect && typeof $ !== 'undefined') {
                            $(typeSelect).val(equipment.type).trigger('change');
                            
                            setTimeout(() => {
                                if (brandSelect && equipment.brand) {
                                    $(brandSelect).val(equipment.brand).trigger('change');
                                    
                                    if (equipment.brand === 'سایر' && otherBrandInput) {
                                        const otherContainer = lastContainer.querySelector('.brand-other-container');
                                        if (otherContainer) {
                                            otherContainer.style.display = 'block';
                                            otherBrandInput.value = equipment.otherBrand || '';
                                        }
                                    }
                                }
                                
                                if (equipment.type === 'سایر' && manualInput) {
                                    const manualSection = lastContainer.querySelector('.manual-equipment-section');
                                    if (manualSection) {
                                        manualSection.style.display = 'block';
                                        manualInput.value = equipment.name || '';
                                    }
                                }
                            }, 100);
                        }
                    }
                }, 200);
            });
        }
    });
}

function updateCellSections(equipmentId, cellType) {
    const input = document.querySelector(`#equipment-form-${equipmentId} .${cellType}-cells-count`);
    const count = input ? parseInt(input.value) || 0 : 0;
    const container = document.getElementById(`${cellType}-cells-container-${equipmentId}`);
    
    if (!container) return;
    
    let html = '';
    
    for (let i = 1; i <= count; i++) {
        html += getCellHTML(equipmentId, cellType, i);
    }
    
    container.innerHTML = html;
    
    setTimeout(() => {
        if (typeof $ !== 'undefined') {
            container.querySelectorAll('.equipment-type-select').forEach(select => {
                $(select).select2({
                    placeholder: 'انتخاب کنید',
                    allowClear: true,
                    width: '100%',
                    dropdownParent: $('#equipmentModal'),
                    dir: 'rtl'
                });
            });
        }
    }, 100);
}

function getCellHTML(equipmentId, cellType, cellNumber) {
    const cellTypePersian = {
        'input': 'ورودی',
        'output': 'خروجی',
        'measurement': 'اندازه‌گیری'
    }[cellType];
    
    return `
        <div class="cell-spec-row">
            <div class="row mb-2">
                <div class="col-md-12">
                    <h6 class="mb-0">سلول ${cellTypePersian} ${cellNumber}</h6>
                    <small class="text-muted">تجهیزات این سلول را مشخص کنید</small>
                </div>
            </div>
            
            <div id="${cellType}-cell-${cellNumber}-equipments-${equipmentId}"></div>
            
            <div class="row mt-2">
                <div class="col-md-12">
                    <button type="button" class="btn btn-sm btn-primary add-equipment-btn" 
                            onclick="addEquipmentToCell('${equipmentId}', '${cellType}', ${cellNumber})">
                        <i class="bi bi-plus-circle"></i> افزودن تجهیز به این سلول
                    </button>
                </div>
            </div>
            
            <div class="row mt-2">
                <div class="col-md-12">
                    <label class="form-label"><i class="bi bi-chat-left-text"></i> توضیحات سلول ${cellTypePersian} ${cellNumber}</label>
                    <textarea class="form-control cell-notes" rows="2" 
                              id="${cellType}-cell-${cellNumber}-notes-${equipmentId}"
                              placeholder="توضیحات مربوط به این سلول"></textarea>
                </div>
            </div>
        </div>
    `;
}

function addEquipmentToCell(equipmentId, cellType, cellNumber) {
    const container = document.getElementById(`${cellType}-cell-${cellNumber}-equipments-${equipmentId}`);
    if (!container) return;
    
    const equipmentCount = container.querySelectorAll('.equipment-type-container').length + 1;
    
    const row = document.createElement('div');
    row.className = 'equipment-type-container';
    row.innerHTML = `
        <div class="row align-items-center">
            <div class="col-md-4">
                <label class="form-label">نوع تجهیز</label>
                <select class="form-select equipment-type-select" 
                        onchange="handleEquipmentTypeChange(this, '${equipmentId}', '${cellType}', ${cellNumber}, ${equipmentCount})">
                    <option value="">انتخاب کنید</option>
                    ${equipmentTypesForCells.map(type => 
                        `<option value="${type}">${type}</option>`
                    ).join('')}
                    <option value="سایر">سایر</option>
                </select>
            </div>
            <div class="col-md-5">
                <label class="form-label">برند</label>
                <div class="brand-selection-container" id="${cellType}-cell-${cellNumber}-equipment-${equipmentCount}-brand-container-${equipmentId}">
                    <select class="form-select equipment-brand-select" 
                            id="${cellType}-cell-${cellNumber}-equipment-${equipmentCount}-brand-${equipmentId}">
                        <option value="">ابتدا نوع تجهیز را انتخاب کنید</option>
                    </select>
                    <div class="brand-other-container" id="${cellType}-cell-${cellNumber}-equipment-${equipmentCount}-other-brand-container-${equipmentId}" style="display: none;">
                        <input type="text" class="form-control mt-1 other-brand-input" 
                               placeholder="نام برند دیگر">
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <label class="form-label">عملیات</label>
                <button type="button" class="btn btn-sm btn-outline-danger w-100" 
                        onclick="removeEquipmentFromCell(this, '${equipmentId}', '${cellType}', ${cellNumber})">
                    <i class="bi bi-trash"></i> حذف
                </button>
            </div>
        </div>
        <div class="row mt-2 manual-equipment-section" style="display: none;" id="${cellType}-cell-${cellNumber}-equipment-${equipmentCount}-manual-${equipmentId}">
            <div class="col-md-12">
                <label class="form-label">نام تجهیز (سایر)</label>
                <input type="text" class="form-control manual-equipment-name" 
                       placeholder="نام تجهیز را وارد کنید">
            </div>
        </div>
    `;
    
    container.appendChild(row);
    
    setTimeout(() => {
        const typeSelect = row.querySelector('.equipment-type-select');
        if (typeSelect && typeof $ !== 'undefined') {
            $(typeSelect).select2({
                placeholder: 'انتخاب کنید',
                allowClear: true,
                width: '100%',
                dropdownParent: $('#equipmentModal'),
                dir: 'rtl'
            }).on('change', function() {
                handleEquipmentTypeChange(this, equipmentId, cellType, cellNumber, equipmentCount);
            });
        }
    }, 100);
}

function handleEquipmentTypeChange(select, equipmentId, cellType, cellNumber, equipmentIndex) {
    const equipmentType = select.value;
    
    const brandContainer = document.getElementById(`${cellType}-cell-${cellNumber}-equipment-${equipmentIndex}-brand-container-${equipmentId}`);
    const manualSection = document.getElementById(`${cellType}-cell-${cellNumber}-equipment-${equipmentIndex}-manual-${equipmentId}`);
    
    if (!brandContainer) return;
    
    if (manualSection) {
        manualSection.style.display = equipmentType === 'سایر' ? 'block' : 'none';
    }
    
    const brandSelect = brandContainer.querySelector('.equipment-brand-select');
    const otherContainer = brandContainer.querySelector('.brand-other-container');
    
    if (otherContainer) {
        otherContainer.style.display = 'none';
    }
    
    let brandsHTML = '<option value="">انتخاب کنید</option>';
    
    if (equipmentBrands[equipmentType]) {
        brandsHTML += equipmentBrands[equipmentType].map(brand => `<option value="${brand}">${brand}</option>`).join('');
    }
    
    if (brandSelect) {
        brandSelect.innerHTML = brandsHTML;
        
        setTimeout(() => {
            if (typeof $ !== 'undefined') {
                $(brandSelect).select2({
                    placeholder: 'انتخاب کنید',
                    allowClear: true,
                    width: '100%',
                    dropdownParent: $('#equipmentModal'),
                    dir: 'rtl'
                }).on('change', function() {
                    if (otherContainer) {
                        otherContainer.style.display = this.value === 'سایر' ? 'block' : 'none';
                    }
                });
            }
        }, 100);
    }
}

function removeEquipmentFromCell(button, equipmentId, cellType, cellNumber) {
    const container = button.closest('.equipment-type-container');
    if (container) {
        container.remove();
    }
}

function saveEquipment(equipmentId) {
    const form = document.getElementById(`equipment-form-${equipmentId}`);
    if (!form) return;
    
    const equipment = equipments.find(e => e.id === equipmentId);
    if (!equipment) return;
    
    // دریافت نوع تجهیز
    const typeSelect = form.querySelector('.equipment-type-select');
    equipment.equipmentType = typeSelect ? (typeof $ !== 'undefined' ? $(typeSelect).val() : typeSelect.value) || '' : '';
    
    // دریافت کد اسکادا
    const scadaInput = form.querySelector('.scada-code');
    equipment.scadaCode = scadaInput ? scadaInput.value || '' : '';
    
    // دریافت زمان شروع و پایان
    const startTimeInput = form.querySelector('.start-time');
    const endTimeInput = form.querySelector('.end-time');
    equipment.startTime = startTimeInput ? startTimeInput.value || '' : '';
    equipment.endTime = endTimeInput ? endTimeInput.value || '' : '';
    
    // دریافت اطلاعات امور و شهرستان
    const deptSelect = form.querySelector('.department-select');
    const cityInput = form.querySelector('.city');
    
    equipment.departmentData = {
        department: deptSelect ? (typeof $ !== 'undefined' ? $(deptSelect).val() : deptSelect.value) || '' : '',
        city: cityInput ? cityInput.value || '' : ''
    };
    
    // =====================================================
    // ========== دریافت ID برند از دیتابیس ==========
    // =====================================================
const noBrandEquipmentTypes = [
'پست دو سو تغذیه (مشترک حساس)',
'پست دو سو تغذیه (بیمارستانی)',
'مشترک ولتاژ اولیه'
];

// این تجهیزات برند ندارند
if (noBrandEquipmentTypes.includes(equipment.equipmentType)) {

    equipment.brand_id = null;

    equipment.modemBrand = '';
    equipment.rtuBrand = '';

    equipment.otherModemBrand = '';
    equipment.otherRTUBrand = '';

} else {

    const brandDbSelect = document.getElementById(
        `modal-brand-id-${equipmentId}`
    );

    if (brandDbSelect?.value) {

        const brandId = parseInt(brandDbSelect.value, 10);

        equipment.brand_id =
            !isNaN(brandId) && brandId > 0
                ? brandId
                : null;

    } else {

        equipment.brand_id = null;
    }
}    
    // =====================================================
    
    // دریافت دپارتمان از مدال (در صورت وجود)
    const departmentDbSelect = document.getElementById('modal-department-id');
    if (departmentDbSelect && departmentDbSelect.value) {
        equipment.departmentData = {
            ...equipment.departmentData,
            department: departmentDbSelect.value,
            department_id: departmentDbSelect.value
        };
    }
    
    // اعتبارسنجی دپارتمان
    if (!equipment.departmentData.department || equipment.departmentData.department === '') {
        Swal.fire({
            icon: 'error',
            title: 'خطا',
            text: 'لطفا امور/شهرستان را انتخاب کنید',
            confirmButtonText: 'باشه'
        });
        return;
    }
    
    // ذخیره برندهای کلید، مودم، RTU برای تجهیزات دارای برند
    if (equipmentWithBrands.includes(equipment.equipmentType)) {
        const modemSelect = form.querySelector('.modem-brand-select');
        const rtuSelect = form.querySelector('.rtu-brand-select');
        
        equipment.modemBrand = modemSelect ? (typeof $ !== 'undefined' ? $(modemSelect).val() : modemSelect.value) || '' : '';
        equipment.rtuBrand = rtuSelect ? (typeof $ !== 'undefined' ? $(rtuSelect).val() : rtuSelect.value) || '' : '';
        
        const modemOtherInput = document.getElementById(`modem-other-input-${equipmentId}`);
        const rtuOtherInput = document.getElementById(`rtu-other-input-${equipmentId}`);
        
        equipment.otherModemBrand = modemOtherInput ? modemOtherInput.value || '' : '';
        equipment.otherRTUBrand = rtuOtherInput ? rtuOtherInput.value || '' : '';
    } else {
        equipment.modemBrand = '';
        equipment.rtuBrand = '';

        equipment.otherModemBrand = '';
        equipment.otherRTUBrand = '';
    }
    
    // ذخیره نوع نصب
    const installSelect = form.querySelector('.installation-type-select');
    if (installSelect) {
        equipment.installationType = typeof $ !== 'undefined' ? $(installSelect).val() : installSelect.value || '';
    }
    
    // ذخیره فیدرها
    equipment.feeders = [];
    const feederPosts = form.querySelectorAll('.feeder-post-select');
    
    feederPosts.forEach((post, index) => {
        const postValue = typeof $ !== 'undefined' ? $(post).val() : post.value;
        const feederSelect = document.getElementById(`feeder-select-${equipmentId}-${index + 1}`);
        const feederValue = feederSelect ? (typeof $ !== 'undefined' ? $(feederSelect).val() : feederSelect.value) : '';
        
        if (postValue && feederValue) {
            equipment.feeders.push({
                post: postValue,
                feeder: feederValue
            });
        }
    });
    
    // ذخیره مشخصات سلول‌ها (برای پست‌ها و مشترکین ولتاژ اولیه)
    const inputCellsCount = form.querySelector('.input-cells-count');
    if (inputCellsCount) {
        equipment.cellSpecs = {
            inputCellsCount: inputCellsCount.value || 0,
            outputCellsCount: form.querySelector('.output-cells-count')?.value || 0,
            measurementCellsCount: form.querySelector('.measurement-cells-count')?.value || 0,
            otherEquipmentsNotes: form.querySelector('.other-equipments-notes')?.value || '',
            inputCells: [],
            outputCells: [],
            measurementCells: []
        };
        
        ['input', 'output', 'measurement'].forEach(cellType => {
            const count = parseInt(equipment.cellSpecs[`${cellType}CellsCount`]) || 0;
            
            for (let i = 1; i <= count; i++) {
                const cellNotes = document.getElementById(`${cellType}-cell-${i}-notes-${equipmentId}`)?.value || '';
                const cellContainer = document.getElementById(`${cellType}-cell-${i}-equipments-${equipmentId}`);
                
                const cell = {
                    cellNumber: i,
                    notes: cellNotes,
                    equipments: []
                };
                
                if (cellContainer) {
                    const equipmentContainers = cellContainer.querySelectorAll('.equipment-type-container');
                    equipmentContainers.forEach(container => {
                        const typeSelect = container.querySelector('.equipment-type-select');
                        const brandSelect = container.querySelector('.equipment-brand-select');
                        const otherBrandInput = container.querySelector('.other-brand-input');
                        const manualNameInput = container.querySelector('.manual-equipment-name');
                        
                        const equipmentType = typeSelect ? (typeof $ !== 'undefined' ? $(typeSelect).val() : typeSelect.value) : '';
                        const brand = brandSelect ? (typeof $ !== 'undefined' ? $(brandSelect).val() : brandSelect.value) : '';
                        const otherBrand = otherBrandInput ? otherBrandInput.value : '';
                        const manualName = manualNameInput ? manualNameInput.value : '';
                        
                        if (equipmentType) {
                            const eqData = {
                                type: equipmentType,
                                brand: brand,
                                otherBrand: otherBrand
                            };
                            
                            if (equipmentType === 'سایر' && manualName) {
                                eqData.name = manualName;
                            }
                            
                            cell.equipments.push(eqData);
                        }
                    });
                }
                
                equipment.cellSpecs[`${cellType}Cells`].push(cell);
            }
        });
    }
    
    // به روز رسانی کارت تجهیز
    updateEquipmentCard(equipmentId);
    
    // بستن مودال
    const modal = bootstrap.Modal.getInstance(document.getElementById('equipmentModal'));
    if (modal) {
        modal.hide();
    }
    
    // به روز رسانی اطلاعات فنی
    updateTechnicalInfoSections();
    
    // ذخیره خودکار
    triggerAutoSave();
    
    // نمایش پیام موفقیت
    Swal.fire({
        icon: 'success',
        title: 'موفق',
        text: 'اطلاعات تجهیز با موفقیت ذخیره شد.',
        timer: 1500,
        showConfirmButton: false
    });
}



function updateEquipmentCard(equipmentId) {
    const equipment = equipments.find(e => e.id === equipmentId);
    if (!equipment) return;
    
    const card = document.getElementById(equipmentId);
    if (!card) return;
    
    const typeSpan = card.querySelector(`#${equipmentId}-type`);
    const scadaSpan = card.querySelector(`#${equipmentId}-scada`);
    const brandSpan = card.querySelector(`#${equipmentId}-switch-brand`);
    const feedersSpan = card.querySelector(`#${equipmentId}-feeders`);
    const statusBadge = card.querySelector(`#${equipmentId}-status`);
    
    if (typeSpan) typeSpan.textContent = equipment.equipmentType || '---';
    if (scadaSpan) scadaSpan.textContent = equipment.scadaCode || '---';
    
if (equipment.brand_id) {

    const brand = window.brandsList?.find(
        b => b.id == equipment.brand_id
    );

    if (brandSpan) {
        brandSpan.textContent = brand ? brand.name : '---';
    }

} else {

    if (brandSpan) {
        brandSpan.textContent = 'بدون برند';
    }

}
    
    const feedersText = equipment.feeders && equipment.feeders.length > 0 
        ? equipment.feeders.map(f => `${f.post} (${f.feeder})`).join('، ')
        : '---';
    if (feedersSpan) feedersSpan.textContent = feedersText;
    
    const validatedCount = equipment.tabsValidated ? Object.keys(equipment.tabsValidated).length : 0;
    
    let statusText = 'فاقد اطلاعات';
    let statusClass = 'bg-danger';
    
    if (validatedCount >= 5) {
        statusText = 'اطلاعات کامل';
        statusClass = 'bg-success';
    } else if (validatedCount >= 3) {
        statusText = 'اطلاعات متوسط';
        statusClass = 'bg-warning';
    } else if (validatedCount > 0) {
        statusText = 'اطلاعات جزئی';
        statusClass = 'bg-info';
    }
    
    if (statusBadge) {
        statusBadge.className = `badge ${statusClass}`;
        statusBadge.textContent = statusText;
    }
}

function removeEquipment(equipmentId) {
    Swal.fire({
        title: 'آیا از حذف این تجهیز اطمینان دارید؟',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'بله، حذف شود',
        cancelButtonText: 'انصراف'
    }).then((result) => {
        if (result.isConfirmed) {
            const index = equipments.findIndex(e => e.id === equipmentId);
            if (index !== -1) {
                equipments.splice(index, 1);
                
                const card = document.getElementById(equipmentId);
                if (card) {
                    card.remove();
                }
                
                updateTechnicalInfoSections();
                triggerAutoSave();
                
                Swal.fire({
                    icon: 'success',
                    title: 'حذف شد',
                    text: 'تجهیز با موفقیت حذف شد.',
                    timer: 1500,
                    showConfirmButton: false
                });
            }
        }
    });
}

// =====================================================
// Technical Tab Functions
// =====================================================

function updateTechnicalInfoSections() {
    const container = document.getElementById('tech-info-container');
    if (!container) return;
    
    container.innerHTML = '';
    
    if (equipments.length === 0) {
        container.innerHTML = `
            <div class="alert alert-warning">
                <i class="bi bi-exclamation-triangle"></i>
                هنوز هیچ تجهیزی اضافه نشده است. لطفا در مرحله قبل تجهیزات را اضافه کنید.
            </div>
        `;
        return;
    }
    
    equipments.forEach((equipment, index) => {
        const section = document.createElement('div');
        section.className = 'equipment-tech-section';
        section.id = `tech-section-${equipment.id}`;
        
        section.innerHTML = `
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5><i class="bi bi-gear"></i> اطلاعات فنی تجهیز ${index + 1}: ${equipment.equipmentType}</h5>
            </div>
            
            <ul class="nav nav-tabs" id="techInfoTabs-${equipment.id}">
                <li class="nav-item">
                    <button class="nav-link ${equipment.tabsValidated?.location ? 'tab-validated' : ''} ${index === 0 ? 'active' : ''}" 
                            data-bs-toggle="tab" data-bs-target="#location-tab-${equipment.id}" 
                            id="tab-location-${equipment.id}">
                        <i class="bi bi-geo-alt tab-icon"></i> موقعیت جغرافیایی
                    </button>
                </li>
                <li class="nav-item">
                    <button class="nav-link ${equipment.tabsValidated?.communication ? 'tab-validated' : ''}" 
                            data-bs-toggle="tab" data-bs-target="#communication-tab-${equipment.id}" 
                            id="tab-communication-${equipment.id}">
                        <i class="bi bi-wifi tab-icon"></i> ارتباطات
                    </button>
                </li>
                <li class="nav-item">
                    <button class="nav-link ${equipment.tabsValidated?.checklist ? 'tab-validated' : ''}" 
                            data-bs-toggle="tab" data-bs-target="#checklist-tab-${equipment.id}" 
                            id="tab-checklist-${equipment.id}">
                        <i class="bi bi-clipboard-check tab-icon"></i> چک‌لیست بازدید
                    </button>
                </li>
                <li class="nav-item">
                    <button class="nav-link ${equipment.tabsValidated?.activities ? 'tab-validated' : ''}" 
                            data-bs-toggle="tab" data-bs-target="#activities-tab-${equipment.id}" 
                            id="tab-activities-${equipment.id}">
                        <i class="bi bi-list-check tab-icon"></i> فعالیت‌ها و مصارف
                    </button>
                </li>
                <li class="nav-item">
                    <button class="nav-link ${equipment.tabsValidated?.photos ? 'tab-validated' : ''}" 
                            data-bs-toggle="tab" data-bs-target="#photos-tab-${equipment.id}" 
                            id="tab-photos-${equipment.id}">
                        <i class="bi bi-camera tab-icon"></i> مستندات تصویری
                    </button>
                </li>
            </ul>

            <div class="tab-content" id="techInfoTabsContent-${equipment.id}">
                <div class="tab-pane fade ${index === 0 ? 'show active' : ''}" id="location-tab-${equipment.id}">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label required"><i class="bi bi-globe"></i> عرض جغرافیایی (Latitude)</label>
                            <input type="text" class="form-control" id="latitude-${equipment.id}" 
                                   placeholder="مثال: 31.8974" 
                                   value="${equipment.locationData?.latitude || ''}">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label required"><i class="bi bi-globe-americas"></i> طول جغرافیایی (Longitude)</label>
                            <input type="text" class="form-control" id="longitude-${equipment.id}" 
                                   placeholder="مثال: 54.3569"
                                   value="${equipment.locationData?.longitude || ''}">
                        </div>
                        <div class="col-md-12 mb-3">
                            <label class="form-label required"><i class="bi bi-geo-alt"></i> آدرس نصب</label>
                            <textarea class="form-control" id="installation-address-${equipment.id}" rows="2">${equipment.locationData?.address || ''}</textarea>
                        </div>
                        ${!equipmentWithoutHeight.includes(equipment.equipmentType) ? `
                        <div class="col-md-6 mb-3">
                            <label class="form-label required"><i class="bi bi-rulers"></i> ارتفاع اولیه تابلو (متر)</label>
                            <input type="number" class="form-control cabinet-height-field" 
                                   step="0.1" min="0" value="${equipment.locationData?.cabinetInitialHeight || ''}">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label"><i class="bi bi-rulers"></i> ارتفاع نهایی زیر تابلو تا زمین (متر)</label>
                            <input type="number" class="form-control cabinet-height-field" 
                                   step="0.1" min="0" value="${equipment.locationData?.cabinetFinalHeight || ''}">
                        </div>
                        ` : ''}
                    </div>
                    <div class="text-end">
                        <button class="btn btn-primary btn-icon" onclick="saveTechnicalTabData('${equipment.id}', 'location')">
                            <i class="bi bi-save"></i> ذخیره اطلاعات موقعیت
                        </button>
                    </div>
                </div>
                <div class="tab-pane fade" id="communication-tab-${equipment.id}">
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label class="form-label required"><i class="bi bi-sim"></i> نوع سیم‌کارت</label>
                            <select class="form-select tech-tab-select" id="simcard-type-${equipment.id}">
                                <option value="">انتخاب کنید</option>
                                <option value="ایرانسل" ${equipment.communicationData?.simcardType === 'ایرانسل' ? 'selected' : ''}>ایرانسل</option>
                                <option value="همراه اول" ${equipment.communicationData?.simcardType === 'همراه اول' ? 'selected' : ''}>همراه اول</option>
                                <option value="رایتل" ${equipment.communicationData?.simcardType === 'رایتل' ? 'selected' : ''}>رایتل</option>
                                <option value="شاتل" ${equipment.communicationData?.simcardType === 'شاتل' ? 'selected' : ''}>شاتل</option>
                                <option value="سایر" ${equipment.communicationData?.simcardType === 'سایر' ? 'selected' : ''}>سایر</option>
                            </select>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label required"><i class="bi bi-phone"></i> شماره سیم‌کارت</label>
                            <input type="text" class="form-control" id="simcard-number-${equipment.id}" 
                                   placeholder="مثال: 09106545840"
                                   value="${equipment.communicationData?.simcardNumber || ''}">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label required"><i class="bi bi-hdd-network"></i> IP سیم‌کارت</label>
                            <input type="text" class="form-control" id="simcard-ip-${equipment.id}" 
                                   placeholder="مثال: 10.213.77.5"
                                   value="${equipment.communicationData?.simcardIp || ''}">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label required"><i class="bi bi-wifi"></i> وضعیت نصب آنتن</label>
                            <select class="form-select tech-tab-select" id="antenna-status-${equipment.id}">
                                <option value="">انتخاب کنید</option>
                                <option value="ندارد" ${equipment.communicationData?.antennaStatus === 'ندارد' ? 'selected' : ''}>ندارد</option>
                                <option value="داخل تابلو" ${equipment.communicationData?.antennaStatus === 'داخل تابلو' ? 'selected' : ''}>داخل تابلو</option>
                                <option value="خارج تابلو" ${equipment.communicationData?.antennaStatus === 'خارج تابلو' ? 'selected' : ''}>خارج تابلو</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label required"><i class="bi bi-bar-chart"></i> وضعیت سیگنال ارتباطی</label>
                            <select class="form-select tech-tab-select" id="signal-status-${equipment.id}">
                                <option value="">انتخاب کنید</option>
                                <option value="خوب" ${equipment.communicationData?.signalStatus === 'خوب' ? 'selected' : ''}>خوب</option>
                                <option value="ضعیف" ${equipment.communicationData?.signalStatus === 'ضعیف' ? 'selected' : ''}>ضعیف</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label required"><i class="bi bi-battery-charging"></i> تغذیه مودم</label>
                            <select class="form-select tech-tab-select" id="modem-power-${equipment.id}">
                                <option value="">انتخاب کنید</option>
                                <option value="پنل" ${equipment.communicationData?.modemPower === 'پنل' ? 'selected' : ''}>پنل</option>
                                <option value="باتری" ${equipment.communicationData?.modemPower === 'باتری' ? 'selected' : ''}>باتری</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="reset-possible-${equipment.id}" ${equipment.communicationData?.resetPossible ? 'checked' : ''}>
                                <label class="form-check-label" for="reset-possible-${equipment.id}">
                                    <i class="bi bi-arrow-clockwise"></i> تجهیز قابلیت ریست دارد
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="text-end">
                        <button class="btn btn-primary btn-icon" onclick="saveTechnicalTabData('${equipment.id}', 'communication')">
                            <i class="bi bi-save"></i> ذخیره اطلاعات ارتباطی
                        </button>
                    </div>
                </div>
                <div class="tab-pane fade" id="checklist-tab-${equipment.id}">
                    <div class="alert alert-info">
                        <i class="bi bi-info-circle"></i>
                        <strong>توجه:</strong> برای هر آیتم وضعیت "OK" یا "Not OK" را انتخاب کنید. در صورت Not OK توضیحات الزامی است.
                    </div>
                    
                    <div id="checklist-items-${equipment.id}">
                        ${getChecklistItemsHTML(equipment)}
                    </div>
                    <div class="mt-3 text-end">
                        <button class="btn btn-primary btn-icon" onclick="saveTechnicalTabData('${equipment.id}', 'checklist')">
                            <i class="bi bi-save"></i> ذخیره چک‌لیست
                        </button>
                    </div>
                </div>
                <div class="tab-pane fade" id="activities-tab-${equipment.id}">
                    <div class="row">
                        <div class="col-md-12 mb-4">
                            <h5 class="border-bottom pb-2"><i class="bi bi-list-check"></i> فعالیت‌های انجام شده (فهرست بها)</h5>
                            <div class="table-responsive">
                                <table class="table table-bordered table-sm" id="activities-table-${equipment.id}">
                                    <thead class="table-light">
                                        <tr>
                                            <th><i class="bi bi-hash"></i> ردیف</th>
                                            <th><i class="bi bi-code-slash"></i> کد فهرست بها</th>
                                            <th><i class="bi bi-card-text"></i> عنوان فعالیت</th>
                                            <th><i class="bi bi-rulers"></i> واحد</th>
                                            <th><i class="bi bi-currency-exchange"></i> فی واحد (ریال)</th>
                                            <th><i class="bi bi-123"></i> تعداد</th>
                                            <th><i class="bi bi-cash-stack"></i> مبلغ (بدون ضریب)</th>
                                            <th><i class="bi bi-trash"></i> حذف</th>
                                        </tr>
                                    </thead>
                                    <tbody id="activities-tbody-${equipment.id}"></tbody>
                                    <tfoot>
                                        <tr>
                                            <td colspan="6" class="text-end"><strong><i class="bi bi-calculator"></i> جمع کل:</strong></td>
                                            <td id="activities-total-${equipment.id}" class="persian-numbers">۰</td>
                                            <td></td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                            <button class="btn btn-sm btn-outline-primary btn-icon" onclick="addActivityRow('${equipment.id}')">
                                <i class="bi bi-plus-circle"></i> افزودن فعالیت
                            </button>
                        </div>

                        <div class="col-md-12">
                            <h5 class="border-bottom pb-2"><i class="bi bi-box-seam"></i> تجهیزات مصرفی</h5>
                            <div class="table-responsive">
                                <table class="table table-bordered table-sm" id="consumables-table-${equipment.id}">
                                    <thead class="table-light">
                                        <tr>
                                            <th><i class="bi bi-hash"></i> ردیف</th>
                                            <th><i class="bi bi-box"></i> نام قلم مصرفی</th>
                                            <th><i class="bi bi-123"></i> تعداد</th>
                                            <th><i class="bi bi-rulers"></i> واحد</th>
                                            <th><i class="bi bi-chat-left-text"></i> توضیحات</th>
                                            <th><i class="bi bi-trash"></i> حذف</th>
                                        </tr>
                                    </thead>
                                    <tbody id="consumables-tbody-${equipment.id}"></tbody>
                                    <tfoot>
                                        <td>
                                            <td colspan="2" class="text-end"><strong><i class="bi bi-calculator"></i> جمع اقلام مصرفی:</strong></td>
                                            <td id="consumables-total-${equipment.id}" class="persian-numbers">۰</td>
                                            <td colspan="3"></td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                            <button class="btn btn-sm btn-outline-success btn-icon" onclick="addConsumableRow('${equipment.id}')">
                                <i class="bi bi-plus-circle"></i> افزودن قلم مصرفی
                            </button>
                        </div>
                    </div>
                    <div class="text-end mt-3">
                        <button class="btn btn-primary btn-icon" onclick="saveActivitiesTab('${equipment.id}')">
                            <i class="bi bi-save"></i> ذخیره فعالیت‌ها و مصارف
                        </button>
                    </div>
                </div>
                <div class="tab-pane fade" id="photos-tab-${equipment.id}">
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <label class="form-label"><i class="bi bi-cloud-arrow-up"></i> آپلود تصاویر</label>
                            <input type="file" class="form-control" id="photo-upload-${equipment.id}" 
                                   accept="image/*" multiple onchange="handlePhotoUpload('${equipment.id}', this)">
                            <small class="text-muted"><i class="bi bi-info-circle"></i> می‌توانید چندین عکس را انتخاب کنید (حداکثر 10 مگابایت برای هر عکس)</small>
                        </div>
                    </div>
                    
                    <div class="row" id="photos-preview-${equipment.id}">
                        ${getPhotosPreviewHTML(equipment)}
                    </div>
                    <div class="text-end mt-3">
                        <button class="btn btn-primary btn-icon" onclick="savePhotosTab('${equipment.id}')">
                            <i class="bi bi-save"></i> ذخیره تصاویر
                        </button>
                    </div>
                </div>
            </div>
        `;
        
        container.appendChild(section);
    });
    
    setTimeout(() => {
        equipments.forEach(equip => {
            if (document.getElementById(`simcard-type-${equip.id}`) && typeof $ !== 'undefined') {
                try {
                    $(`#simcard-type-${equip.id}, #antenna-status-${equip.id}, #signal-status-${equip.id}, #modem-power-${equip.id}`).select2({
                        placeholder: 'انتخاب کنید',
                        allowClear: true,
                        width: '100%',
                        dir: 'rtl'
                    });
                } catch(e) {
                    console.log('Select2 error:', e);
                }
            }
            loadExistingActivitiesAndConsumables(equip.id);
        });
    }, 500);
}

function loadExistingActivitiesAndConsumables(equipmentId) {
    const equipment = equipments.find(e => e.id === equipmentId);
    if (!equipment) return;
    
    const tbody = document.getElementById(`activities-tbody-${equipmentId}`);
    if (tbody) {
        tbody.innerHTML = '';
        
        if (equipment.activitiesData && equipment.activitiesData.length > 0) {
            equipment.activitiesData.forEach((activity, index) => {
                addActivityRow(equipmentId);
                
                setTimeout(() => {
                    const rows = tbody.querySelectorAll('tr');
                    const row = rows[rows.length - 1];
                    
                    if (row) {
                        const codeSelect = row.querySelector('.activity-code');
                        if (codeSelect && typeof $ !== 'undefined') {
                            $(codeSelect).val(activity.code).trigger('change');
                        }
                        const quantityInput = row.querySelector('.activity-quantity');
                        if (quantityInput) quantityInput.value = activity.quantity || 1;
                        updateActivityTotal(equipmentId);
                    }
                }, 50);
            });
        } else {
            addActivityRow(equipmentId);
        }
    }
    
    const consumablesTbody = document.getElementById(`consumables-tbody-${equipmentId}`);
    if (consumablesTbody) {
        consumablesTbody.innerHTML = '';
        
        if (equipment.consumablesData && equipment.consumablesData.length > 0) {
            equipment.consumablesData.forEach((consumable, index) => {
                addConsumableRow(equipmentId);
                
                setTimeout(() => {
                    const rows = consumablesTbody.querySelectorAll('tr');
                    const row = rows[rows.length - 1];
                    
                    if (row) {
                        const nameSelect = row.querySelector('.consumable-name');
                        if (nameSelect && typeof $ !== 'undefined') {
                            $(nameSelect).val(consumable.name).trigger('change');
                        }
                        const quantityInput = row.querySelector('.consumable-quantity');
                        if (quantityInput) quantityInput.value = consumable.quantity || 1;
                        const unitSelect = row.querySelector('.consumable-unit');
                        if (unitSelect && typeof $ !== 'undefined') {
                            $(unitSelect).val(consumable.unit || 'عدد');
                        }
                        const descInput = row.querySelector('.consumable-description');
                        if (descInput) descInput.value = consumable.description || '';
                        
                        if (consumable.name === 'سایر اقلام') {
                            const otherInput = row.querySelector('.other-consumable');
                            if (otherInput) {
                                otherInput.style.display = 'block';
                                otherInput.value = consumable.otherName || '';
                            }
                        }
                        
                        updateConsumablesTotal(equipmentId);
                    }
                }, 50);
            });
        } else {
            addConsumableRow(equipmentId);
        }
    }
}

function getChecklistItemsHTML(equipment) {
    const checklist = equipmentChecklists[equipment.equipmentType] || [];
    let checklistHTML = '';
    
    checklist.forEach((item, index) => {
        const existingItem = equipment.checklistData && equipment.checklistData[index];
        const itemId = `checklist-${equipment.id}-${index}`;
        const isOK = existingItem?.status === 'OK';
        const isNotOK = existingItem?.status === 'Not OK';
        
        checklistHTML += `
            <div class="checklist-item" id="${itemId}">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <p class="mb-0">${index + 1}. ${item}</p>
                    </div>
                    <div class="col-md-4">
                        <div class="btn-group w-100" role="group">
                            <button type="button" 
                                    class="btn ${isOK ? 'ok-btn' : 'btn-outline-success'} btn-icon" 
                                    onclick="setChecklistStatus('${equipment.id}', ${index}, 'OK')">
                                <i class="bi bi-check-circle"></i> OK
                            </button>
                            <button type="button" 
                                    class="btn ${isNotOK ? 'not-ok-btn' : 'btn-outline-danger'} btn-icon" 
                                    onclick="setChecklistStatus('${equipment.id}', ${index}, 'Not OK')">
                                <i class="bi bi-x-circle"></i> Not OK
                            </button>
                        </div>
                    </div>
                </div>
                <div class="row mt-2" id="${itemId}-description" style="${isNotOK ? 'display: block;' : 'display: none;'}">
                    <div class="col-md-12">
                        <label class="form-label"><i class="bi bi-chat-left-text"></i> توضیحات و اقدامات اصلاحی</label>
                        <textarea class="form-control" rows="2" id="${itemId}-description-text">${existingItem?.description || ''}</textarea>
                    </div>
                </div>
            </div>
        `;
    });
    
    return checklistHTML;
}

function getPhotosPreviewHTML(equipment) {
    let html = '';
    const photos = equipment.photosData || [];
    
    if (photos.length === 0) {
        html = '<div class="col-12 text-center"><p class="text-muted"><i class="bi bi-images"></i> هنوز تصویری آپلود نشده است</p></div>';
    } else {
        photos.forEach((photo, index) => {
            html += `
                <div class="col-md-4 mb-3">
                    <div class="photo-preview">
                        <button type="button" class="btn btn-sm btn-danger photo-remove" onclick="removePhoto(this, '${equipment.id}')">
                            <i class="bi bi-x"></i>
                        </button>
                        <img src="${photo.dataUrl}" class="img-fluid rounded" alt="تصویر ${index + 1}">
                        <div class="mt-2">
                            <input type="text" class="form-control form-control-sm mb-1 scan-code" 
                                   placeholder="کد عکس" value="${photo.scanCode || ''}">
                            <textarea class="form-control form-control-sm photo-description" rows="2" 
                                      placeholder="توضیحات">${photo.description || ''}</textarea>
                        </div>
                    </div>
                </div>
            `;
        });
    }
    
    return html;
}

function setChecklistStatus(equipmentId, index, status) {
    const item = document.getElementById(`checklist-${equipmentId}-${index}`);
    const descriptionDiv = document.getElementById(`checklist-${equipmentId}-${index}-description`);
    
    if (!item) return;
    
    const okBtn = item.querySelector('.btn:first-child');
    const notOkBtn = item.querySelector('.btn:last-child');
    
    if (status === 'OK') {
        if (okBtn) {
            okBtn.className = 'btn ok-btn btn-icon';
        }
        if (notOkBtn) {
            notOkBtn.className = 'btn btn-outline-danger btn-icon';
        }
        if (descriptionDiv) descriptionDiv.style.display = 'none';
    } else {
        if (okBtn) {
            okBtn.className = 'btn btn-outline-success btn-icon';
        }
        if (notOkBtn) {
            notOkBtn.className = 'btn not-ok-btn btn-icon';
        }
        if (descriptionDiv) descriptionDiv.style.display = 'block';
    }
}

function saveTechnicalTabData(equipmentId, tabName) {
    const equipment = equipments.find(e => e.id === equipmentId);
    if (!equipment) return;
    
    if (tabName === 'location') {
        const shouldHideHeight = equipmentWithoutHeight.includes(equipment.equipmentType);
        
        equipment.locationData = {
            latitude: document.getElementById(`latitude-${equipmentId}`)?.value || '',
            longitude: document.getElementById(`longitude-${equipmentId}`)?.value || '',
            address: document.getElementById(`installation-address-${equipmentId}`)?.value || '',
            cabinetInitialHeight: '',
            cabinetFinalHeight: ''
        };
        
        if (!shouldHideHeight) {
            const heightFields = document.querySelectorAll(`#location-tab-${equipmentId} .cabinet-height-field`);
            if (heightFields.length >= 2) {
                equipment.locationData.cabinetInitialHeight = heightFields[0]?.value || '';
                equipment.locationData.cabinetFinalHeight = heightFields[1]?.value || '';
            }
        }
        
    } else if (tabName === 'communication') {
        equipment.communicationData = {
            simcardType: document.getElementById(`simcard-type-${equipmentId}`)?.value || '',
            simcardNumber: document.getElementById(`simcard-number-${equipmentId}`)?.value || '',
            simcardIp: document.getElementById(`simcard-ip-${equipmentId}`)?.value || '',
            antennaStatus: document.getElementById(`antenna-status-${equipmentId}`)?.value || '',
            signalStatus: document.getElementById(`signal-status-${equipmentId}`)?.value || '',
            modemPower: document.getElementById(`modem-power-${equipmentId}`)?.value || '',
            resetPossible: document.getElementById(`reset-possible-${equipmentId}`)?.checked || false
        };
    } else if (tabName === 'checklist') {
        const checklist = [];
        const items = document.querySelectorAll(`#checklist-items-${equipmentId} .checklist-item`);
        
        items.forEach((item, index) => {
            const okBtn = item.querySelector('.btn:first-child');
            const notOkBtn = item.querySelector('.btn:last-child');
            const descriptionText = item.querySelector('textarea')?.value || '';
            
            let status = '';
            if (okBtn && okBtn.classList.contains('ok-btn')) {
                status = 'OK';
            } else if (notOkBtn && notOkBtn.classList.contains('not-ok-btn')) {
                status = 'Not OK';
            }
            
            if (status) {
                checklist.push({
                    item: item.querySelector('p')?.textContent.replace(`${index + 1}. `, '') || '',
                    status: status,
                    description: descriptionText
                });
            }
        });
        
        equipment.checklistData = checklist;
    }
    
    markTabAsValidated(equipmentId, tabName);
    updateEquipmentCard(equipmentId);
    triggerAutoSave();
    
    Swal.fire({
        icon: 'success',
        title: 'موفق',
        text: `اطلاعات ${tabName} برای تجهیز ${equipment.index} با موفقیت ذخیره شد.`,
        timer: 1500,
        showConfirmButton: false
    });
}

function markTabAsValidated(equipmentId, tabId) {
    const equipment = equipments.find(e => e.id === equipmentId);
    if (equipment) {
        if (!equipment.tabsValidated) {
            equipment.tabsValidated = {};
        }
        equipment.tabsValidated[tabId] = true;
        
        const tabElement = document.getElementById(`tab-${tabId}-${equipmentId}`);
        if (tabElement && !tabElement.classList.contains('tab-validated')) {
            tabElement.classList.add('tab-validated');
        }
    }
}

// =====================================================
// Activities and Consumables Functions
// =====================================================

function addActivityRow(equipmentId) {
    const tbody = document.getElementById(`activities-tbody-${equipmentId}`);
    if (!tbody) return;
    
    const rowCount = tbody.children.length + 1;
    
    const row = document.createElement('tr');
    row.innerHTML = `
        <td class="persian-numbers">${rowCount}</td>
        <td>
            <select class="form-select form-select-sm activity-code" onchange="updateActivityRow('${equipmentId}', this)">
                <option value="">انتخاب کنید</option>
                ${priceList.map(item => 
                    `<option value="${item.code}" data-title="${item.title}" data-unit="${item.unit}" data-price="${item.price}">
                        ${item.code}
                    </option>`
                ).join('')}
            </select>
        </td>
        <td class="activity-title">---</td>
        <td class="activity-unit">---</td>
        <td class="activity-price text-end persian-numbers">---</td>
        <td>
            <input type="number" class="form-control form-control-sm activity-quantity persian-numbers" 
                   min="1" value="1" onchange="updateActivityTotal('${equipmentId}')">
        </td>
        <td class="activity-total text-end persian-numbers">۰</td>
        <td>
            <button class="btn btn-sm btn-outline-danger" onclick="removeActivityRow('${equipmentId}', this)">
                <i class="bi bi-trash"></i>
            </button>
        </td>
    `;
    
    tbody.appendChild(row);
    
    setTimeout(() => {
        const codeSelect = row.querySelector('.activity-code');
        if (codeSelect && typeof $ !== 'undefined') {
            $(codeSelect).select2({
                placeholder: 'انتخاب کنید',
                allowClear: true,
                width: '100%',
                dir: 'rtl'
            });
        }
    }, 100);
    
    updateActivityTotal(equipmentId);
}

function updateActivityRow(equipmentId, select) {
    const row = select.closest('tr');
    const selectedOption = select.options[select.selectedIndex];
    
    const titleCell = row.querySelector('.activity-title');
    const unitCell = row.querySelector('.activity-unit');
    const priceCell = row.querySelector('.activity-price');
    
    if (titleCell) titleCell.textContent = selectedOption?.dataset?.title || '---';
    if (unitCell) unitCell.textContent = selectedOption?.dataset?.unit || '---';
    if (priceCell) priceCell.textContent = selectedOption?.dataset?.price ? formatNumber(selectedOption.dataset.price) + ' ریال' : '---';
    
    updateActivityTotal(equipmentId);
}

function updateActivityTotal(equipmentId) {
    let total = 0;
    const rows = document.querySelectorAll(`#activities-tbody-${equipmentId} tr`);
    
    rows.forEach((row, index) => {
        const quantity = parseInt(row.querySelector('.activity-quantity')?.value) || 0;
        const select = row.querySelector('.activity-code');
        const price = select && select.selectedOptions[0] ? parseInt(select.selectedOptions[0].dataset.price) || 0 : 0;
        const rowTotal = quantity * price;
        
        const totalCell = row.querySelector('.activity-total');
        if (totalCell) totalCell.textContent = formatNumber(rowTotal);
        
        const rowNumCell = row.querySelector('td:first-child');
        if (rowNumCell) rowNumCell.textContent = index + 1;
        
        total += rowTotal;
    });
    
    const totalElement = document.getElementById(`activities-total-${equipmentId}`);
    if (totalElement) totalElement.textContent = formatNumber(total);
}

function addConsumableRow(equipmentId) {
    const tbody = document.getElementById(`consumables-tbody-${equipmentId}`);
    if (!tbody) return;
    
    const rowCount = tbody.children.length + 1;
    
    const row = document.createElement('tr');
    row.innerHTML = `
        <td class="persian-numbers">${rowCount}</td>
        <td>
            <select class="form-select form-select-sm consumable-name" onchange="toggleOtherConsumable('${equipmentId}', this)">
                <option value="">انتخاب کنید</option>
                ${consumablesList.map(item => `<option value="${item.name}">${item.name}</option>`).join('')}
            </select>
            <input type="text" class="form-control form-control-sm mt-1 other-consumable" 
                   placeholder="نام قلم مصرفی" style="display: none;">
        </td>
        <td>
            <input type="number" class="form-control form-control-sm consumable-quantity persian-numbers" min="1" value="1">
        </td>
        <td>
            <select class="form-select form-select-sm consumable-unit">
                <option value="عدد">عدد</option>
                <option value="متر">متر</option>
                <option value="کیلوگرم">کیلوگرم</option>
                <option value="لیتر">لیتر</option>
                <option value="بسته">بسته</option>
                <option value="رول">رول</option>
            </select>
        </td>
        <td>
            <input type="text" class="form-control form-control-sm consumable-description" 
                   placeholder="توضیحات (اختیاری)">
        </td>
        <td>
            <button class="btn btn-sm btn-outline-danger" onclick="removeConsumableRow('${equipmentId}', this)">
                <i class="bi bi-trash"></i>
            </button>
        </td>
    `;
    
    tbody.appendChild(row);
    
    setTimeout(() => {
        if (typeof $ !== 'undefined') {
            $(row.querySelector('.consumable-name')).select2({
                placeholder: 'انتخاب کنید',
                allowClear: true,
                width: '100%',
                dir: 'rtl'
            });
            $(row.querySelector('.consumable-unit')).select2({
                placeholder: 'انتخاب کنید',
                allowClear: true,
                width: '100%',
                dir: 'rtl'
            });
        }
    }, 50);
    
    updateConsumablesTotal(equipmentId);
}

function toggleOtherConsumable(equipmentId, select) {
    const row = select.closest('tr');
    const otherInput = row.querySelector('.other-consumable');
    if (otherInput) {
        otherInput.style.display = select.value === 'سایر اقلام' ? 'block' : 'none';
    }
}

function updateConsumablesTotal(equipmentId) {
    let total = 0;
    const rows = document.querySelectorAll(`#consumables-tbody-${equipmentId} tr`);
    
    rows.forEach((row, index) => {
        const quantity = parseInt(row.querySelector('.consumable-quantity')?.value) || 0;
        total += quantity;
        const rowNumCell = row.querySelector('td:first-child');
        if (rowNumCell) rowNumCell.textContent = index + 1;
    });
    
    const totalElement = document.getElementById(`consumables-total-${equipmentId}`);
    if (totalElement) totalElement.textContent = total;
}

function removeActivityRow(equipmentId, button) {
    const row = button.closest('tr');
    if (row) {
        row.remove();
        updateActivityTotal(equipmentId);
    }
}

function removeConsumableRow(equipmentId, button) {
    const row = button.closest('tr');
    if (row) {
        row.remove();
        updateConsumablesTotal(equipmentId);
    }
}

function saveActivitiesTab(equipmentId) {
    const equipment = equipments.find(e => e.id === equipmentId);
    
    if (equipment) {
        const activities = [];
        const rows = document.querySelectorAll(`#activities-tbody-${equipmentId} tr`);
        
        rows.forEach(row => {
            const codeSelect = row.querySelector('.activity-code');
            const code = codeSelect ? codeSelect.value : '';
            const selectedOption = codeSelect ? codeSelect.selectedOptions[0] : null;
            
            if (code) {
                activities.push({
                    code: code,
                    title: selectedOption?.dataset?.title || '',
                    unit: selectedOption?.dataset?.unit || '',
                    unitPrice: parseInt(selectedOption?.dataset?.price) || 0,
                    quantity: parseInt(row.querySelector('.activity-quantity')?.value) || 0,
                    total: (parseInt(row.querySelector('.activity-quantity')?.value) || 0) * (parseInt(selectedOption?.dataset?.price) || 0)
                });
            }
        });
        
        const consumables = [];
        const consumableRows = document.querySelectorAll(`#consumables-tbody-${equipmentId} tr`);
        
        consumableRows.forEach(row => {
            const nameSelect = row.querySelector('.consumable-name');
            const name = nameSelect ? nameSelect.value : '';
            const otherName = row.querySelector('.other-consumable')?.value || '';
            const finalName = name === 'سایر اقلام' ? otherName : name;
            const unitSelect = row.querySelector('.consumable-unit');
            const unit = unitSelect ? unitSelect.value : 'عدد';
            
            if (finalName) {
                consumables.push({
                    name: finalName,
                    quantity: parseInt(row.querySelector('.consumable-quantity')?.value) || 0,
                    unit: unit || 'عدد',
                    description: row.querySelector('.consumable-description')?.value || '',
                    otherName: name === 'سایر اقلام' ? otherName : ''
                });
            }
        });
        
        equipment.activitiesData = activities;
        equipment.consumablesData = consumables;
        markTabAsValidated(equipmentId, 'activities');
        triggerAutoSave();
        
        Swal.fire({
            icon: 'success',
            title: 'موفق',
            text: 'فعالیت‌ها و مصارف با موفقیت ذخیره شد.',
            timer: 1500,
            showConfirmButton: false
        });
    }
}

// =====================================================
// Photos Functions
// =====================================================

function handlePhotoUpload(equipmentId, input) {
    const files = input.files;
    const previewContainer = document.getElementById(`photos-preview-${equipmentId}`);
    if (!previewContainer) return;
    
    Array.from(files).forEach((file, index) => {
        if (file.type.startsWith('image/')) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const col = document.createElement('div');
                col.className = 'col-md-4 mb-3';
                col.innerHTML = `
                    <div class="photo-preview">
                        <button type="button" class="btn btn-sm btn-danger photo-remove" onclick="removePhoto(this, '${equipmentId}')">
                            <i class="bi bi-x"></i>
                        </button>
                        <img src="${e.target.result}" class="img-fluid rounded" alt="تصویر ${index + 1}">
                        <div class="mt-2">
                            <input type="text" class="form-control form-control-sm mb-1 scan-code" 
                                   placeholder="کد عکس">
                            <textarea class="form-control form-control-sm photo-description" rows="2" 
                                      placeholder="توضیحات"></textarea>
                        </div>
                    </div>
                `;
                previewContainer.appendChild(col);
            };
            reader.readAsDataURL(file);
        }
    });
}

function removePhoto(button, equipmentId) {
    const preview = button.closest('.photo-preview');
    if (preview) {
        preview.remove();
    }
}

function savePhotosTab(equipmentId) {
    const equipment = equipments.find(e => e.id === equipmentId);
    const photosData = getPhotosData(equipmentId);
    
    if (equipment) {
        equipment.photosData = photosData;
        markTabAsValidated(equipmentId, 'photos');
        triggerAutoSave();
        
        Swal.fire({
            icon: 'success',
            title: 'موفق',
            text: 'تصاویر با موفقیت ذخیره شد.',
            timer: 1500,
            showConfirmButton: false
        });
    }
}

function getPhotosData(equipmentId) {
    const photos = [];
    const previewContainer = document.getElementById(`photos-preview-${equipmentId}`);
    if (!previewContainer) return photos;
    
    const photoElements = previewContainer.querySelectorAll('.photo-preview');
    
    photoElements.forEach(photoEl => {
        const scanCode = photoEl.querySelector('.scan-code')?.value || '';
        const description = photoEl.querySelector('.photo-description')?.value || '';
        const imgSrc = photoEl.querySelector('img')?.src || '';
        
        photos.push({
            scanCode: scanCode,
            description: description,
            dataUrl: imgSrc
        });
    });
    
    return photos;
}

// =====================================================
// Summary and Final Report Functions
// =====================================================

function updateSummary() {
    const equipmentCountEl = document.getElementById('summary-equipment-count');
    const coefficientEl = document.getElementById('summary-coefficient');
    const activityCountEl = document.getElementById('summary-activity-count');
    const totalCostEl = document.getElementById('summary-total-cost');
    const finalCostEl = document.getElementById('summary-final-cost');
    
    if (equipmentCountEl) equipmentCountEl.textContent = formatNumber(equipments.length);
    if (coefficientEl) coefficientEl.textContent = document.getElementById('contract-coefficient')?.value || '2.35';
    
    let totalActivityCount = 0;
    let totalCost = 0;
    let activitiesSummary = {};
    let consumablesSummary = {};
    
    equipments.forEach(equipment => {
        if (equipment.activitiesData) {
            equipment.activitiesData.forEach(activity => {
                totalActivityCount += activity.quantity;
                totalCost += activity.total;
                
                if (!activitiesSummary[activity.code]) {
                    activitiesSummary[activity.code] = {
                        title: activity.title,
                        unit: activity.unit,
                        unitPrice: activity.unitPrice,
                        totalQuantity: 0,
                        totalAmount: 0
                    };
                }
                activitiesSummary[activity.code].totalQuantity += activity.quantity;
                activitiesSummary[activity.code].totalAmount += activity.total;
            });
        }
        
        if (equipment.consumablesData) {
            equipment.consumablesData.forEach(consumable => {
                if (!consumablesSummary[consumable.name]) {
                    consumablesSummary[consumable.name] = {
                        totalQuantity: 0,
                        unit: consumable.unit || 'عدد',
                        descriptions: []
                    };
                }
                consumablesSummary[consumable.name].totalQuantity += consumable.quantity;
                if (consumable.description) {
                    consumablesSummary[consumable.name].descriptions.push(consumable.description);
                }
            });
        }
    });

    equipments.forEach(equipment => {
        if (equipment.locationData) {
            if (equipment.locationData.cabinetInitialHeight) {
                let val = parseFloat(equipment.locationData.cabinetInitialHeight);
                if (isNaN(val) || val > 10) val = 0;
                if (val < 0) val = 0;
                equipment.locationData.cabinetInitialHeight = val;
            }
            
            if (equipment.locationData.cabinetFinalHeight) {
                let val = parseFloat(equipment.locationData.cabinetFinalHeight);
                if (isNaN(val) || val > 10) val = 0;
                if (val < 0) val = 0;
                equipment.locationData.cabinetFinalHeight = val;
            }
        }
    });
    
    if (activityCountEl) activityCountEl.textContent = formatNumber(totalActivityCount);
    if (totalCostEl) totalCostEl.textContent = formatNumber(totalCost) + ' ریال';
    
    const coefficient = parseFloat(document.getElementById('contract-coefficient')?.value) || 2.35;
    const finalCost = totalCost * coefficient;
    if (finalCostEl) finalCostEl.textContent = formatNumber(finalCost) + ' ریال';
    
    updateActivitiesSummaryTable(activitiesSummary, coefficient);
    updateConsumablesSummaryTable(consumablesSummary);
    updateEquipmentDetailsSummary();
}

function updateActivitiesSummaryTable(activitiesSummary, coefficient) {
    const tbody = document.getElementById('activities-summary-body');
    if (!tbody) return;
    
    tbody.innerHTML = '';
    
    let totalAmount = 0;
    
    Object.entries(activitiesSummary).forEach(([code, data]) => {
        const row = document.createElement('tr');
        row.innerHTML = `
            <td>${code}</td>
            <td>${data.title}</td>
            <td class="persian-numbers">${formatNumber(data.totalQuantity)}</td>
            <td class="persian-numbers">${formatNumber(data.unitPrice)} ریال</td>
            <td class="persian-numbers">${formatNumber(data.totalAmount)} ریال</td>
            <td class="persian-numbers">${formatNumber(data.totalAmount * coefficient)} ریال</td>
        `;
        tbody.appendChild(row);
        totalAmount += data.totalAmount;
    });
    
    // اگر هیچ فعالیتی وجود نداشت، یک ردیف خالی نشان بده
    if (Object.keys(activitiesSummary).length === 0) {
        const emptyRow = document.createElement('tr');
        emptyRow.innerHTML = `<td colspan="6" class="text-center py-4 text-muted">هیچ فعالیتی ثبت نشده است</td>`;
        tbody.appendChild(emptyRow);
    }
    
    const totalElement = document.getElementById('final-activities-total');
    const totalCoefficientElement = document.getElementById('final-activities-total-coefficient');
    
    if (totalElement) totalElement.textContent = formatNumber(totalAmount) + ' ریال';
    if (totalCoefficientElement) totalCoefficientElement.textContent = formatNumber(totalAmount * coefficient) + ' ریال';
}

function updateConsumablesSummaryTable(consumablesSummary) {
    const tbody = document.getElementById('consumables-summary-body');
    if (!tbody) return;
    
    tbody.innerHTML = '';
    
    let totalQuantity = 0;
    
    Object.entries(consumablesSummary).forEach(([name, data]) => {
        const row = document.createElement('tr');
        const description = data.descriptions.join('، ');
        row.innerHTML = `
            <td>${name}</td>
            <td class="persian-numbers">${formatNumber(data.totalQuantity)}</td>
            <td>${data.unit}</td>
            <td>${description || '-'}</td>
        `;
        tbody.appendChild(row);
        totalQuantity += data.totalQuantity;
    });
    
    // اگر هیچ قلم مصرفی وجود نداشت
    if (Object.keys(consumablesSummary).length === 0) {
        const emptyRow = document.createElement('tr');
        emptyRow.innerHTML = `<td colspan="4" class="text-center py-4 text-muted">هیچ قلم مصرفی ثبت نشده است</td>`;
        tbody.appendChild(emptyRow);
    }
    
    const totalElement = document.getElementById('final-consumables-total');
    if (totalElement) totalElement.textContent = formatNumber(totalQuantity);
}

function updateEquipmentDetailsSummary() {
    const container = document.getElementById('equipment-details-summary');
    if (!container) return;
    
    if (equipments.length === 0) {
        container.innerHTML = '<div class="alert alert-warning text-center">هیچ تجهیزی اضافه نشده است</div>';
        return;
    }
    
    let html = '<div class="row">';
    
    equipments.forEach((equipment, index) => {
        // پیدا کردن برند از لیست برندها
        let brandName = 'ثبت نشده';
        if (equipment.brand_id && window.brandsList) {
            const brandObj = window.brandsList.find(b => b.id == equipment.brand_id);
            if (brandObj) brandName = brandObj.name;
        }
        
        const hasBrands = equipmentWithBrands.includes(equipment.equipmentType);
        
        // برند مودم
        let modemBrandDisplay = 'ثبت نشده';
        if (hasBrands) {
            if (equipment.modemBrand === 'سایر') {
                modemBrandDisplay = equipment.otherModemBrand || 'سایر';
            } else if (equipment.modemBrand) {
                modemBrandDisplay = equipment.modemBrand;
            }
        }
        
        // برند RTU
        let rtuBrandDisplay = 'ثبت نشده';
        if (hasBrands) {
            if (equipment.rtuBrand === 'سایر') {
                rtuBrandDisplay = equipment.otherRTUBrand || 'سایر';
            } else if (equipment.rtuBrand) {
                rtuBrandDisplay = equipment.rtuBrand;
            }
        }
        
        const feedersText = equipment.feeders && equipment.feeders.length > 0 
            ? equipment.feeders.map(f => `${f.post} (${f.feeder})`).join('، ')
            : 'ثبت نشده';
        
        // جمع‌آوری فعالیت‌های این تجهیز
        let equipmentActivities = [];
        if (equipment.activitiesData && equipment.activitiesData.length > 0) {
            equipmentActivities = equipment.activitiesData;
        }
        
        // جمع‌آوری اقلام مصرفی این تجهیز
        let equipmentConsumables = [];
        if (equipment.consumablesData && equipment.consumablesData.length > 0) {
            equipmentConsumables = equipment.consumablesData;
        }
        
        // جمع‌آوری آیتم‌های Not OK چک‌لیست
        let notOkItems = [];
        if (equipment.checklistData && equipment.checklistData.length > 0) {
            notOkItems = equipment.checklistData.filter(item => item.status === 'Not OK');
        }
        
        html += `
            <div class="col-md-6 mb-4">
                <div class="card equipment-summary-card h-100 shadow-sm">
                    <div class="card-header bg-primary text-white">
                        <h6 class="card-title mb-0">
                            <i class="bi bi-hdd-stack"></i> تجهیز ${index + 1}: ${equipment.equipmentType || 'ثبت نشده'}
                            <span class="badge bg-light text-dark ms-2">کد: ${equipment.scadaCode || '---'}</span>
                        </h6>
                    </div>
                    <div class="card-body">
                        <!-- اطلاعات پایه -->
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <p class="mb-1"><i class="bi bi-code-slash text-primary"></i> <strong>کد اسکادا:</strong> ${equipment.scadaCode || 'ثبت نشده'}</p>
                                <p class="mb-1"><i class="bi bi-building text-primary"></i> <strong>امور شهرستان:</strong> ${equipment.departmentData?.department || 'ثبت نشده'}</p>
                                <p class="mb-1"><i class="bi bi-geo-alt text-primary"></i> <strong>GIS Code:</strong> ${equipment.departmentData?.city || 'ثبت نشده'}</p>
                                <p class="mb-1"><i class="bi bi-clock text-primary"></i> <strong>زمان فعالیت:</strong> ${equipment.startTime || 'ثبت نشده'} - ${equipment.endTime || 'ثبت نشده'}</p>
                            </div>
                            <div class="col-md-6">
                                <p class="mb-1"><i class="bi bi-tag text-primary"></i> <strong>برند تجهیزات:</strong> ${brandName}</p>
                                <p class="mb-1"><i class="bi bi-wifi text-primary"></i> <strong>برند مودم:</strong> ${modemBrandDisplay}</p>
                                <p class="mb-1"><i class="bi bi-cpu text-primary"></i> <strong>برند RTU:</strong> ${rtuBrandDisplay}</p>
                                <p class="mb-1"><i class="bi bi-lightning-charge text-primary"></i> <strong>نوع نصب:</strong> ${equipment.installationType || 'ثبت نشده'}</p>
                            </div>
                        </div>
                        
                        <!-- موقعیت جغرافیایی -->
                        <div class="border-top pt-2 mb-2">
                            <p class="mb-1"><i class="bi bi-geo-alt-fill text-success"></i> <strong>موقعیت جغرافیایی:</strong> 
                                عرض: ${equipment.locationData?.latitude || 'ثبت نشده'} - 
                                طول: ${equipment.locationData?.longitude || 'ثبت نشده'}
                            </p>
                            <p class="mb-1"><i class="bi bi-pin-map-fill text-success"></i> <strong>آدرس:</strong> ${equipment.locationData?.address || 'ثبت نشده'}</p>
                            <p class="mb-1"><i class="bi bi-rulers text-success"></i> <strong>ارتفاع تابلو:</strong> 
                                اولیه: ${equipment.locationData?.cabinetInitialHeight || 'ثبت نشده'} متر - 
                                نهایی: ${equipment.locationData?.cabinetFinalHeight || 'ثبت نشده'} متر
                            </p>
                        </div>
                        
                        <!-- اطلاعات ارتباطی -->
                        <div class="border-top pt-2 mb-2">
                            <p class="mb-1"><i class="bi bi-sim-fill text-info"></i> <strong>سیم‌کارت:</strong> ${equipment.communicationData?.simcardType || 'ثبت نشده'} - 
                                شماره: ${equipment.communicationData?.simcardNumber || 'ثبت نشده'} - 
                                IP: ${equipment.communicationData?.simcardIp || 'ثبت نشده'}
                            </p>
                            <p class="mb-1"><i class="bi bi-antenna text-info"></i> <strong>وضعیت آنتن:</strong> ${equipment.communicationData?.antennaStatus || 'ثبت نشده'} - 
                                وضعیت سیگنال: ${equipment.communicationData?.signalStatus || 'ثبت نشده'} - 
                                تغذیه مودم: ${equipment.communicationData?.modemPower || 'ثبت نشده'}
                            </p>
                            <p class="mb-1"><i class="bi bi-arrow-repeat text-info"></i> <strong>قابلیت ریست:</strong> ${equipment.communicationData?.resetPossible ? 'دارد' : 'ندارد'}</p>
                        </div>
                        
                        <!-- فیدرها -->
                        <div class="border-top pt-2 mb-2">
                            <p class="mb-1"><i class="bi bi-diagram-3-fill text-warning"></i> <strong>فیدرها:</strong> ${feedersText}</p>
                        </div>
                        
                        <!-- فعالیت‌ها -->
                        ${equipmentActivities.length > 0 ? `
                        <div class="border-top pt-2 mb-2">
                            <p class="mb-2"><i class="bi bi-list-check text-success"></i> <strong>فعالیت‌های انجام شده (${equipmentActivities.length} قلم):</strong></p>
                            <div class="table-responsive">
                                <table class="table table-sm table-bordered">
                                    <thead class="table-light">
                                        <tr><th>کد</th><th>عنوان فعالیت</th><th>تعداد</th><th>مبلغ (ریال)</th></tr>
                                    </thead>
                                    <tbody>
                                        ${equipmentActivities.map(act => `
                                            <tr>
                                                <td>${act.code || '-'}</td>
                                                <td>${act.title || '-'}</td>
                                                <td class="persian-numbers">${(act.quantity || 0).toLocaleString()}</td>
                                                <td class="persian-numbers">${(act.total || 0).toLocaleString()}</td>
                                            </tr>
                                        `).join('')}
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        ` : ''}
                        
                        <!-- اقلام مصرفی -->
                        ${equipmentConsumables.length > 0 ? `
                        <div class="border-top pt-2 mb-2">
                            <p class="mb-2"><i class="bi bi-box-seam text-info"></i> <strong>اقلام مصرفی (${equipmentConsumables.length} قلم):</strong></p>
                            <div class="table-responsive">
                                <table class="table table-sm table-bordered">
                                    <thead class="table-light">
                                        <tr><th>نام قلم</th><th>تعداد</th><th>واحد</th><th>توضیحات</th></tr>
                                    </thead>
                                    <tbody>
                                        ${equipmentConsumables.map(con => `
                                            <tr>
                                                <td>${con.name || '-'}</td>
                                                <td class="persian-numbers">${(con.quantity || 0).toLocaleString()}</td>
                                                <td>${con.unit || 'عدد'}</td>
                                                <td>${con.description || '-'}</td>
                                            </tr>
                                        `).join('')}
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        ` : ''}
                        
                        <!-- آیتم‌های Not OK چک‌لیست -->
                        ${notOkItems.length > 0 ? `
                        <div class="border-top pt-2">
                            <p class="mb-2"><i class="bi bi-exclamation-triangle-fill text-danger"></i> <strong>آیتم‌های خراب (Not OK) (${notOkItems.length} قلم):</strong></p>
                            <div class="table-responsive">
                                <table class="table table-sm table-bordered">
                                    <thead class="table-light">
                                        <tr><th>آیتم</th><th>توضیحات</th></tr>
                                    </thead>
                                    <tbody>
                                        ${notOkItems.map(item => `
                                            <tr>
                                                <td>${item.item || '-'}</td>
                                                <td>${item.description || '-'}</td>
                                            </tr>
                                        `).join('')}
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        ` : ''}
                    </div>
                </div>
            </div>
        `;
    });
    
    html += '</div>';
    container.innerHTML = html;
}
// =====================================================
// Report Generation Functions
// =====================================================

function generateExcelReport() {
    if (!checkLibraries()) {
        Swal.fire({
            icon: 'error',
            title: 'خطا',
            text: 'کتابخانه‌های مورد نیاز بارگذاری نشده‌اند. لطفا صفحه را رفرش کنید.'
        });
        return;
    }

    try {
        const wb = XLSX.utils.book_new();
        const inspectionDate = document.getElementById('inspection-date')?.value || '';
        const contractor = document.getElementById('contractor')?.value || '';
        const coefficient = parseFloat(document.getElementById('contract-coefficient')?.value) || 2.35;
        const contractNumber = document.getElementById('contract-number')?.value || '';
        const dailyStartTime = document.getElementById('daily-start-time')?.value || '';
        const dailyEndTime = document.getElementById('daily-end-time')?.value || '';
        const cityDepartment = equipments.length > 0 ? equipments[0].departmentData?.department || 'ثبت نشده' : 'ثبت نشده';
        
        // =============================================
        // محاسبه آمار
        // =============================================
        let totalActivitiesCount = 0;
        let totalCost = 0;
        let allActivities = [];
        let allConsumables = [];
        
        equipments.forEach(equipment => {
            if (equipment.activitiesData) {
                equipment.activitiesData.forEach(activity => {
                    totalActivitiesCount += activity.quantity || 0;
                    totalCost += activity.total || 0;
                    allActivities.push({
                        equipmentName: equipment.equipmentType,
                        equipmentScada: equipment.scadaCode,
                        code: activity.code,
                        title: activity.title,
                        unit: activity.unit,
                        unitPrice: activity.unitPrice,
                        quantity: activity.quantity || 0,
                        total: activity.total || 0
                    });
                });
            }
            
            if (equipment.consumablesData) {
                equipment.consumablesData.forEach(consumable => {
                    allConsumables.push({
                        equipmentName: equipment.equipmentType,
                        equipmentScada: equipment.scadaCode,
                        name: consumable.name,
                        quantity: consumable.quantity || 0,
                        unit: consumable.unit || 'عدد',
                        description: consumable.description || ''
                    });
                });
            }
        });
        
        const finalCost = totalCost * coefficient;
        
        // گروه‌بندی فعالیت‌ها برای خلاصه
        const activitiesSummary = {};
        allActivities.forEach(activity => {
            if (!activitiesSummary[activity.code]) {
                activitiesSummary[activity.code] = {
                    title: activity.title,
                    unitPrice: activity.unitPrice,
                    totalQuantity: 0,
                    totalAmount: 0
                };
            }
            activitiesSummary[activity.code].totalQuantity += activity.quantity;
            activitiesSummary[activity.code].totalAmount += activity.total;
        });
        
        // گروه‌بندی اقلام مصرفی برای خلاصه
        const consumablesSummary = {};
        allConsumables.forEach(consumable => {
            if (!consumablesSummary[consumable.name]) {
                consumablesSummary[consumable.name] = {
                    unit: consumable.unit,
                    totalQuantity: 0,
                    descriptions: []
                };
            }
            consumablesSummary[consumable.name].totalQuantity += consumable.quantity;
            if (consumable.description && !consumablesSummary[consumable.name].descriptions.includes(consumable.description)) {
                consumablesSummary[consumable.name].descriptions.push(consumable.description);
            }
        });
        
        // =============================================
        // شیت 1: خلاصه روزانه
        // =============================================
        const dailyData = [
            ['شرکت توزیع نیروی برق استان یزد', '', '', '', '', '', ''],
            ['سیستم مدیریت بازدید تجهیزات اتوماسیون', '', '', '', '', '', ''],
            ['فرم شماره: F-20324-01', '', '', '', '', '', ''],
            ['', '', '', '', '', '', ''],
            ['خلاصه روزانه', '', '', '', '', '', ''],
            ['تاریخ بازدید', inspectionDate, '', '', '', '', ''],
            ['امور شهرستان', cityDepartment, '', '', '', '', ''],
            ['پیمانکار', contractor, '', '', '', '', ''],
            ['شماره قرارداد', contractNumber, '', '', '', '', ''],
            ['ساعت فعالیت', `${dailyStartTime} - ${dailyEndTime}`, '', '', '', '', ''],
            ['ضریب قرارداد', coefficient, '', '', '', '', ''],
            ['', '', '', '', '', '', ''],
            ['آمار کلی', '', '', '', '', '', ''],
            ['تعداد تجهیزات', equipments.length, '', '', '', '', ''],
            ['کل فعالیت‌ها', totalActivitiesCount.toLocaleString(), '', '', '', '', ''],
            ['هزینه بدون ضریب', totalCost.toLocaleString() + ' ریال', '', '', '', '', ''],
            ['هزینه نهایی', finalCost.toLocaleString() + ' ریال', '', '', '', '', '']
        ];
        
        const ws1 = XLSX.utils.aoa_to_sheet(dailyData);
        ws1['!cols'] = [{wch:25}, {wch:20}, {wch:10}, {wch:10}, {wch:10}, {wch:10}, {wch:10}];
        XLSX.utils.book_append_sheet(wb, ws1, "خلاصه روزانه");
        
        // =============================================
        // شیت 2: صورت وضعیت مالی
        // =============================================
        const financialData = [
            ['صورت وضعیت کلی روز', '', '', '', '', ''],
            ['تاریخ', inspectionDate, '', '', '', ''],
            ['امور شهرستان', cityDepartment, '', '', '', ''],
            ['پیمانکار', contractor, '', '', '', ''],
            ['شماره قرارداد', contractNumber, '', '', '', ''],
            ['ضریب قرارداد', coefficient, '', '', '', ''],
            ['', '', '', '', '', ''],
            ['خلاصه مالی روز', '', '', '', '', ''],
            ['تعداد تجهیزات', equipments.length, '', '', '', ''],
            ['کل فعالیت‌ها', totalActivitiesCount.toLocaleString(), '', '', '', ''],
            ['هزینه بدون ضریب', totalCost.toLocaleString() + ' ریال', '', '', '', ''],
            ['هزینه نهایی', finalCost.toLocaleString() + ' ریال', '', '', '', ''],
            ['', '', '', '', '', ''],
            ['جمع‌بندی فعالیت‌های فهرست بها', '', '', '', '', ''],
            ['کد فهرست بها', 'عنوان فعالیت', 'تعداد کل', 'فی واحد (ریال)', 'مبلغ کل (بدون ضریب)', 'مبلغ با ضریب']
        ];
        
        let totalDailyAmount = 0;
        Object.entries(activitiesSummary).forEach(([code, data]) => {
            financialData.push([
                code,
                data.title || '',
                data.totalQuantity.toLocaleString(),
                data.unitPrice.toLocaleString(),
                data.totalAmount.toLocaleString() + ' ریال',
                (data.totalAmount * coefficient).toLocaleString() + ' ریال'
            ]);
            totalDailyAmount += data.totalAmount;
        });
        
        financialData.push(['', '', '', '', '', '']);
        financialData.push(['جمع کل فعالیت‌ها', '', '', '', 
            totalDailyAmount.toLocaleString() + ' ریال', 
            (totalDailyAmount * coefficient).toLocaleString() + ' ریال']);
        
        const wsFinancial = XLSX.utils.aoa_to_sheet(financialData);
        wsFinancial['!cols'] = [{wch:20}, {wch:40}, {wch:15}, {wch:20}, {wch:25}, {wch:25}];
        XLSX.utils.book_append_sheet(wb, wsFinancial, "صورت وضعیت مالی");
        
        // =============================================
        // شیت 3: خلاصه اقلام مصرفی
        // =============================================
        const consumableSummaryData = [
            ['خلاصه اقلام مصرفی', '', '', ''],
            ['نام قلم مصرفی', 'تعداد کل', 'واحد', 'توضیحات']
        ];
        
        Object.entries(consumablesSummary).forEach(([name, data]) => {
            consumableSummaryData.push([
                name,
                data.totalQuantity.toLocaleString(),
                data.unit,
                data.descriptions.join('، ') || '-'
            ]);
        });
        
        if (Object.keys(consumablesSummary).length === 0) {
            consumableSummaryData.push(['هیچ قلم مصرفی ثبت نشده است', '', '', '']);
        }
        
        const wsConsumableSummary = XLSX.utils.aoa_to_sheet(consumableSummaryData);
        wsConsumableSummary['!cols'] = [{wch:35}, {wch:15}, {wch:15}, {wch:40}];
        XLSX.utils.book_append_sheet(wb, wsConsumableSummary, "خلاصه اقلام مصرفی");
        
        // =============================================
        // شیت 4: جزئیات کامل تجهیزات
        // =============================================
        const equipmentDetailsData = [
            ['جزئیات کامل تجهیزات بازدید شده', '', '', '', '', '', '', '', ''],
            ['ردیف', 'نوع تجهیز', 'کد اسکادا', 'برند کلید', 'مودم', 'RTU', 'امور شهرستان', 'GIS Code', 'فیدرها', 'موقعیت جغرافیایی', 'آدرس', 'زمان فعالیت', 'نوع نصب', 'وضعیت سیگنال']
        ];
        
        equipments.forEach((equipment, index) => {
            const hasBrands = equipmentWithBrands.includes(equipment.equipmentType);
            const switchBrandDisplay = hasBrands ? 
                (equipment.switchBrand === 'سایر' ? equipment.otherSwitchBrand : equipment.switchBrand) : 
                'بدون برند';
            const modemBrandDisplay = hasBrands ? 
                (equipment.modemBrand === 'سایر' ? equipment.otherModemBrand : equipment.modemBrand || '---') : 
                '---';
            const rtuBrandDisplay = hasBrands ? 
                (equipment.rtuBrand === 'سایر' ? equipment.otherRTUBrand : equipment.rtuBrand || '---') : 
                '---';
            
            const feedersText = equipment.feeders && equipment.feeders.length > 0 
                ? equipment.feeders.map(f => `${f.post} (${f.feeder})`).join('، ')
                : 'ثبت نشده';
            
            equipmentDetailsData.push([
                index + 1,
                equipment.equipmentType || '---',
                equipment.scadaCode || '---',
                switchBrandDisplay,
                modemBrandDisplay,
                rtuBrandDisplay,
                equipment.departmentData?.department || 'ثبت نشده',
                equipment.departmentData?.city || 'ثبت نشده',
                feedersText,
                `عرض: ${equipment.locationData?.latitude || '---'} - طول: ${equipment.locationData?.longitude || '---'}`,
                equipment.locationData?.address || 'ثبت نشده',
                `${equipment.startTime || '---'} - ${equipment.endTime || '---'}`,
                equipment.installationType || '---',
                equipment.communicationData?.signalStatus || '---'
            ]);
        });
        
        const wsEquipment = XLSX.utils.aoa_to_sheet(equipmentDetailsData);
        wsEquipment['!cols'] = [{wch:6}, {wch:20}, {wch:15}, {wch:15}, {wch:15}, {wch:15}, {wch:20}, {wch:15}, {wch:30}, {wch:35}, {wch:40}, {wch:20}, {wch:15}, {wch:15}];
        XLSX.utils.book_append_sheet(wb, wsEquipment, "جزئیات تجهیزات");
        
        // =============================================
        // شیت 5: جزئیات فعالیت‌ها
        // =============================================
        const activitiesDetailsData = [
            ['جزئیات کامل فعالیت‌های انجام شده', '', '', '', '', '', ''],
            ['ردیف', 'تجهیز', 'کد اسکادا', 'کد فهرست بها', 'عنوان فعالیت', 'واحد', 'تعداد', 'قیمت واحد (ریال)', 'مبلغ کل (ریال)']
        ];
        
        let activityRowIndex = 1;
        allActivities.forEach(activity => {
            activitiesDetailsData.push([
                activityRowIndex++,
                activity.equipmentName,
                activity.equipmentScada,
                activity.code,
                activity.title,
                activity.unit,
                activity.quantity.toLocaleString(),
                activity.unitPrice.toLocaleString(),
                activity.total.toLocaleString()
            ]);
        });
        
        if (allActivities.length === 0) {
            activitiesDetailsData.push(['', 'هیچ فعالیتی ثبت نشده است', '', '', '', '', '', '', '']);
        }
        
        const wsActivities = XLSX.utils.aoa_to_sheet(activitiesDetailsData);
        wsActivities['!cols'] = [{wch:6}, {wch:20}, {wch:15}, {wch:15}, {wch:45}, {wch:10}, {wch:10}, {wch:20}, {wch:20}];
        XLSX.utils.book_append_sheet(wb, wsActivities, "جزئیات فعالیت‌ها");
        
        // =============================================
        // شیت 6: جزئیات اقلام مصرفی
        // =============================================
        const consumablesDetailsData = [
            ['جزئیات کامل اقلام مصرفی', '', '', '', ''],
            ['ردیف', 'تجهیز', 'کد اسکادا', 'نام قلم مصرفی', 'تعداد', 'واحد', 'توضیحات']
        ];
        
        let consumableRowIndex = 1;
        allConsumables.forEach(consumable => {
            consumablesDetailsData.push([
                consumableRowIndex++,
                consumable.equipmentName,
                consumable.equipmentScada,
                consumable.name,
                consumable.quantity.toLocaleString(),
                consumable.unit,
                consumable.description || '-'
            ]);
        });
        
        if (allConsumables.length === 0) {
            consumablesDetailsData.push(['', 'هیچ قلم مصرفی ثبت نشده است', '', '', '', '', '']);
        }
        
        const wsConsumables = XLSX.utils.aoa_to_sheet(consumablesDetailsData);
        wsConsumables['!cols'] = [{wch:6}, {wch:20}, {wch:15}, {wch:30}, {wch:10}, {wch:10}, {wch:40}];
        XLSX.utils.book_append_sheet(wb, wsConsumables, "جزئیات اقلام مصرفی");
        
        // =============================================
        // شیت 7: چک‌لیست‌های Not OK
        // =============================================
        const notOkChecklistData = [
            ['گزارش آیتم‌های Not OK در چک‌لیست‌ها', '', '', '', ''],
            ['ردیف', 'تجهیز', 'کد اسکادا', 'آیتم چک‌لیست', 'وضعیت', 'توضیحات']
        ];
        
        let notOkRowIndex = 1;
        equipments.forEach(equipment => {
            if (equipment.checklistData && equipment.checklistData.length > 0) {
                equipment.checklistData.forEach(item => {
                    if (item.status === 'Not OK') {
                        notOkChecklistData.push([
                            notOkRowIndex++,
                            equipment.equipmentType,
                            equipment.scadaCode || '---',
                            item.item,
                            'Not OK',
                            item.description || '-'
                        ]);
                    }
                });
            }
        });
        
        if (notOkChecklistData.length === 2) {
            notOkChecklistData.push(['', 'هیچ آیتم Not OK ثبت نشده است', '', '', '', '']);
        }
        
        const wsNotOk = XLSX.utils.aoa_to_sheet(notOkChecklistData);
        wsNotOk['!cols'] = [{wch:6}, {wch:20}, {wch:15}, {wch:50}, {wch:10}, {wch:40}];
        XLSX.utils.book_append_sheet(wb, wsNotOk, "آیتم‌های Not OK");
        
        // ذخیره فایل
        const wbout = XLSX.write(wb, { bookType: 'xlsx', type: 'array' });
        const blob = new Blob([wbout], { type: 'application/octet-stream' });
        const filename = `بازدید_اتوماسیون_${inspectionDate}_${Date.now()}.xlsx`;
        saveAsFile(blob, filename);
        
        Swal.fire({
            icon: 'success',
            title: 'موفق',
            text: 'فایل Excel با موفقیت ایجاد شد.',
            timer: 2000,
            showConfirmButton: false
        });
        
    } catch (error) {
        console.error('Error generating Excel:', error);
        Swal.fire({
            icon: 'error',
            title: 'خطا',
            text: 'خطا در ایجاد فایل Excel: ' + error.message
        });
    }
}

function generatePDFReport() {
    try {
        const printWindow = window.open('', '_blank');
        
        const inspectionDate = document.getElementById('inspection-date')?.value || '';
        const contractor = document.getElementById('contractor')?.value || '';
        const coefficient = parseFloat(document.getElementById('contract-coefficient')?.value) || 2.35;
        const contractNumber = document.getElementById('contract-number')?.value || '';
        const dailyStartTime = document.getElementById('daily-start-time')?.value || '';
        const dailyEndTime = document.getElementById('daily-end-time')?.value || '';
        const cityDepartment = equipments.length > 0 ? equipments[0].departmentData?.department || 'ثبت نشده' : 'ثبت نشده';
        
        let totalActivities = 0;
        let totalCost = 0;
        
        // جمع‌آوری فعالیت‌ها برای جدول
        let allActivities = [];
        
        equipments.forEach(equipment => {
            if (equipment.activitiesData && equipment.activitiesData.length > 0) {
                equipment.activitiesData.forEach(activity => {
                    totalActivities += activity.quantity || 0;
                    totalCost += activity.total || 0;
                    allActivities.push({
                        code: activity.code,
                        title: activity.title,
                        unit: activity.unit,
                        unitPrice: activity.unitPrice,
                        quantity: activity.quantity || 0,
                        total: activity.total || 0,
                        equipmentName: equipment.equipmentType,
                        equipmentScada: equipment.scadaCode
                    });
                });
            }
        });
        
        // جمع‌آوری اقلام مصرفی برای جدول
        let allConsumables = [];
        
        equipments.forEach(equipment => {
            if (equipment.consumablesData && equipment.consumablesData.length > 0) {
                equipment.consumablesData.forEach(consumable => {
                    allConsumables.push({
                        name: consumable.name,
                        quantity: consumable.quantity || 0,
                        unit: consumable.unit || 'عدد',
                        description: consumable.description || '',
                        equipmentName: equipment.equipmentType,
                        equipmentScada: equipment.scadaCode
                    });
                });
            }
        });
        
        const finalCost = totalCost * coefficient;
        
        // گروه‌بندی فعالیت‌ها بر اساس کد
        const groupedActivities = {};
        allActivities.forEach(activity => {
            if (!groupedActivities[activity.code]) {
                groupedActivities[activity.code] = {
                    code: activity.code,
                    title: activity.title,
                    unit: activity.unit,
                    unitPrice: activity.unitPrice,
                    totalQuantity: 0,
                    totalAmount: 0
                };
            }
            groupedActivities[activity.code].totalQuantity += activity.quantity;
            groupedActivities[activity.code].totalAmount += activity.total;
        });
        
        // گروه‌بندی اقلام مصرفی بر اساس نام
        const groupedConsumables = {};
        allConsumables.forEach(consumable => {
            if (!groupedConsumables[consumable.name]) {
                groupedConsumables[consumable.name] = {
                    name: consumable.name,
                    unit: consumable.unit,
                    totalQuantity: 0,
                    descriptions: []
                };
            }
            groupedConsumables[consumable.name].totalQuantity += consumable.quantity;
            if (consumable.description && !groupedConsumables[consumable.name].descriptions.includes(consumable.description)) {
                groupedConsumables[consumable.name].descriptions.push(consumable.description);
            }
        });
        
        let equipmentHTML = '';
        
        equipments.forEach((equipment, index) => {
            const hasBrands = equipmentWithBrands.includes(equipment.equipmentType);
            const switchBrandDisplay = hasBrands ? 
                (equipment.switchBrand === 'سایر' ? equipment.otherSwitchBrand : equipment.switchBrand) : 
                'بدون برند';
                
            const feedersText = equipment.feeders && equipment.feeders.length > 0 
                ? equipment.feeders.map(f => `${f.post} (${f.feeder})`).join('، ')
                : 'ثبت نشده';
            
            // فعالیت‌های این تجهیز
            let equipmentActivitiesHTML = '';
            const eqActivities = allActivities.filter(a => a.equipmentScada === equipment.scadaCode);
            if (eqActivities.length > 0) {
                equipmentActivitiesHTML = `
                    <h4 style="margin-top: 15px; margin-bottom: 10px; color: #3498db;">فعالیت‌های انجام شده</h4>
                    <table style="width: 100%; border-collapse: collapse; margin-bottom: 15px;">
                        <thead>
                            <tr>
                                <th style="padding: 8px; border: 1px solid #ddd; background-color: #f2f2f2;">کد</th>
                                <th style="padding: 8px; border: 1px solid #ddd; background-color: #f2f2f2;">عنوان فعالیت</th>
                                <th style="padding: 8px; border: 1px solid #ddd; background-color: #f2f2f2;">واحد</th>
                                <th style="padding: 8px; border: 1px solid #ddd; background-color: #f2f2f2;">تعداد</th>
                                <th style="padding: 8px; border: 1px solid #ddd; background-color: #f2f2f2;">مبلغ (ریال)</th>
                            </tr>
                        </thead>
                        <tbody>
                            ${eqActivities.map(act => `
                                <tr>
                                    <td style="padding: 8px; border: 1px solid #ddd;">${act.code}</td>
                                    <td style="padding: 8px; border: 1px solid #ddd;">${act.title}</td>
                                    <td style="padding: 8px; border: 1px solid #ddd;">${act.unit}</td>
                                    <td style="padding: 8px; border: 1px solid #ddd;" class="persian-numbers">${act.quantity.toLocaleString()}</td>
                                    <td style="padding: 8px; border: 1px solid #ddd;" class="persian-numbers">${act.total.toLocaleString()}</td>
                                </tr>
                            `).join('')}
                        </tbody>
                    </table>
                `;
            }
            
            // اقلام مصرفی این تجهیز
            let equipmentConsumablesHTML = '';
            const eqConsumables = allConsumables.filter(c => c.equipmentScada === equipment.scadaCode);
            if (eqConsumables.length > 0) {
                equipmentConsumablesHTML = `
                    <h4 style="margin-top: 15px; margin-bottom: 10px; color: #27ae60;">اقلام مصرفی</h4>
                    <table style="width: 100%; border-collapse: collapse; margin-bottom: 15px;">
                        <thead>
                            <tr>
                                <th style="padding: 8px; border: 1px solid #ddd; background-color: #f2f2f2;">نام قلم مصرفی</th>
                                <th style="padding: 8px; border: 1px solid #ddd; background-color: #f2f2f2;">تعداد</th>
                                <th style="padding: 8px; border: 1px solid #ddd; background-color: #f2f2f2;">واحد</th>
                                <th style="padding: 8px; border: 1px solid #ddd; background-color: #f2f2f2;">توضیحات</th>
                            </tr>
                        </thead>
                        <tbody>
                            ${eqConsumables.map(con => `
                                <tr>
                                    <td style="padding: 8px; border: 1px solid #ddd;">${con.name}</td>
                                    <td style="padding: 8px; border: 1px solid #ddd;" class="persian-numbers">${con.quantity.toLocaleString()}</td>
                                    <td style="padding: 8px; border: 1px solid #ddd;">${con.unit}</td>
                                    <td style="padding: 8px; border: 1px solid #ddd;">${con.description || '-'}</td>
                                </tr>
                            `).join('')}
                        </tbody>
                    </table>
                `;
            }
            
            equipmentHTML += `
                <div style="margin-bottom: 30px; padding: 20px; border: 2px solid #ddd; border-radius: 10px; background-color: #fff; page-break-inside: avoid;">
                    <h3 style="color: #2c3e50; border-bottom: 2px solid #3498db; padding-bottom: 10px; margin-top: 0;">
                        تجهیز ${index + 1}: ${equipment.equipmentType || 'ثبت نشده'}
                        <span style="font-size: 14px; color: #666;">(کد اسکادا: ${equipment.scadaCode || 'ثبت نشده'})</span>
                    </h3>
                    
                    <table style="width: 100%; border-collapse: collapse; margin-bottom: 15px;">
                        <tr>
                            <th style="padding: 10px; border: 1px solid #ddd; background-color: #f2f2f2; width: 25%;">زمان فعالیت:</th>
                            <td style="padding: 10px; border: 1px solid #ddd; width: 25%;">${equipment.startTime || '---'} - ${equipment.endTime || '---'}</td>
                            <th style="padding: 10px; border: 1px solid #ddd; background-color: #f2f2f2; width: 25%;">نوع نصب:</th>
                            <td style="padding: 10px; border: 1px solid #ddd; width: 25%;">${equipment.installationType || '---'}</td>
                        </tr>
                        ${hasBrands ? `
                        <tr>
                            <th style="padding: 10px; border: 1px solid #ddd; background-color: #f2f2f2;">برند کلید:</th>
                            <td style="padding: 10px; border: 1px solid #ddd;">${switchBrandDisplay}</td>
                            <th style="padding: 10px; border: 1px solid #ddd; background-color: #f2f2f2;">برند مودم:</th>
                            <td style="padding: 10px; border: 1px solid #ddd;">${equipment.modemBrand === 'سایر' ? equipment.otherModemBrand : equipment.modemBrand || '---'}</td>
                        </tr>
                        <tr>
                            <th style="padding: 10px; border: 1px solid #ddd; background-color: #f2f2f2;">برند RTU:</th>
                            <td style="padding: 10px; border: 1px solid #ddd;">${equipment.rtuBrand === 'سایر' ? equipment.otherRTUBrand : equipment.rtuBrand || '---'}</td>
                            <th style="padding: 10px; border: 1px solid #ddd; background-color: #f2f2f2;">وضعیت سیگنال:</th>
                            <td style="padding: 10px; border: 1px solid #ddd;">${equipment.communicationData?.signalStatus || '---'}</td>
                        </tr>
                        ` : ''}
                        <tr>
                            <th style="padding: 10px; border: 1px solid #ddd; background-color: #f2f2f2;">امور شهرستان:</th>
                            <td style="padding: 10px; border: 1px solid #ddd;">${equipment.departmentData?.department || 'ثبت نشده'}</td>
                            <th style="padding: 10px; border: 1px solid #ddd; background-color: #f2f2f2;">GIS Code:</th>
                            <td style="padding: 10px; border: 1px solid #ddd;">${equipment.departmentData?.city || 'ثبت نشده'}</td>
                        </tr>
                        <tr>
                            <th style="padding: 10px; border: 1px solid #ddd; background-color: #f2f2f2;">فیدرها:</th>
                            <td style="padding: 10px; border: 1px solid #ddd;" colspan="3">${feedersText}</td>
                        </tr>
                        <tr>
                            <th style="padding: 10px; border: 1px solid #ddd; background-color: #f2f2f2;">موقعیت جغرافیایی:</th>
                            <td style="padding: 10px; border: 1px solid #ddd;" colspan="3">
                                عرض: ${equipment.locationData?.latitude || 'ثبت نشده'} - طول: ${equipment.locationData?.longitude || 'ثبت نشده'}
                            </td>
                        </tr>
                        <tr>
                            <th style="padding: 10px; border: 1px solid #ddd; background-color: #f2f2f2;">آدرس:</th>
                            <td style="padding: 10px; border: 1px solid #ddd;" colspan="3">${equipment.locationData?.address || 'ثبت نشده'}</td>
                        </tr>
                        <tr>
                            <th style="padding: 10px; border: 1px solid #ddd; background-color: #f2f2f2;">اطلاعات ارتباطی:</th>
                            <td style="padding: 10px; border: 1px solid #ddd;" colspan="3">
                                سیم‌کارت: ${equipment.communicationData?.simcardType || 'ثبت نشده'} - 
                                شماره: ${equipment.communicationData?.simcardNumber || 'ثبت نشده'} - 
                                IP: ${equipment.communicationData?.simcardIp || 'ثبت نشده'}
                            </td>
                        </tr>
                        <tr>
                            <th style="padding: 10px; border: 1px solid #ddd; background-color: #f2f2f2;">وضعیت آنتن:</th>
                            <td style="padding: 10px; border: 1px solid #ddd;">${equipment.communicationData?.antennaStatus || 'ثبت نشده'}</td>
                            <th style="padding: 10px; border: 1px solid #ddd; background-color: #f2f2f2;">تغذیه مودم:</th>
                            <td style="padding: 10px; border: 1px solid #ddd;">${equipment.communicationData?.modemPower || 'ثبت نشده'}</td>
                        </tr>
                    </table>
                    
                    ${equipmentActivitiesHTML}
                    ${equipmentConsumablesHTML}
                </div>
            `;
        });
        
        // ساخت جدول خلاصه فعالیت‌ها
        let activitiesSummaryHTML = '';
        if (Object.keys(groupedActivities).length > 0) {
            activitiesSummaryHTML = `
                <div class="section-title">خلاصه فعالیت‌های فهرست بها</div>
                <table style="width: 100%; border-collapse: collapse; margin-bottom: 20px;">
                    <thead>
                        <tr>
                            <th style="padding: 10px; border: 1px solid #ddd; background-color: #f2f2f2;">کد</th>
                            <th style="padding: 10px; border: 1px solid #ddd; background-color: #f2f2f2;">عنوان فعالیت</th>
                            <th style="padding: 10px; border: 1px solid #ddd; background-color: #f2f2f2;">تعداد کل</th>
                            <th style="padding: 10px; border: 1px solid #ddd; background-color: #f2f2f2;">فی واحد (ریال)</th>
                            <th style="padding: 10px; border: 1px solid #ddd; background-color: #f2f2f2;">مبلغ کل (ریال)</th>
                        </tr>
                    </thead>
                    <tbody>
                        ${Object.values(groupedActivities).map(act => `
                            <tr>
                                <td style="padding: 8px; border: 1px solid #ddd;">${act.code}</td>
                                <td style="padding: 8px; border: 1px solid #ddd;">${act.title}</td>
                                <td style="padding: 8px; border: 1px solid #ddd;" class="persian-numbers">${act.totalQuantity.toLocaleString()}</td>
                                <td style="padding: 8px; border: 1px solid #ddd;" class="persian-numbers">${act.unitPrice.toLocaleString()}</td>
                                <td style="padding: 8px; border: 1px solid #ddd;" class="persian-numbers">${act.totalAmount.toLocaleString()}</td>
                            </tr>
                        `).join('')}
                    </tbody>
                    <tfoot>
                        <tr style="background-color: #f2f2f2; font-weight: bold;">
                            <td colspan="4" class="text-end">جمع کل:</td>
                            <td class="persian-numbers">${totalCost.toLocaleString()} ریال</td>
                        </tr>
                    </tfoot>
                </table>
            `;
        }
        
        // ساخت جدول خلاصه اقلام مصرفی
        let consumablesSummaryHTML = '';
        if (Object.keys(groupedConsumables).length > 0) {
            consumablesSummaryHTML = `
                <div class="section-title">خلاصه اقلام مصرفی</div>
                <table style="width: 100%; border-collapse: collapse; margin-bottom: 20px;">
                    <thead>
                        <tr>
                            <th style="padding: 10px; border: 1px solid #ddd; background-color: #f2f2f2;">نام قلم مصرفی</th>
                            <th style="padding: 10px; border: 1px solid #ddd; background-color: #f2f2f2;">تعداد کل</th>
                            <th style="padding: 10px; border: 1px solid #ddd; background-color: #f2f2f2;">واحد</th>
                            <th style="padding: 10px; border: 1px solid #ddd; background-color: #f2f2f2;">توضیحات</th>
                        </tr>
                    </thead>
                    <tbody>
                        ${Object.values(groupedConsumables).map(con => `
                            <tr>
                                <td style="padding: 8px; border: 1px solid #ddd;">${con.name}</td>
                                <td style="padding: 8px; border: 1px solid #ddd;" class="persian-numbers">${con.totalQuantity.toLocaleString()}</td>
                                <td style="padding: 8px; border: 1px solid #ddd;">${con.unit}</td>
                                <td style="padding: 8px; border: 1px solid #ddd;">${con.descriptions.join('، ') || '-'}</td>
                            </tr>
                        `).join('')}
                    </tbody>
                </table>
            `;
        }
        
        const htmlContent = `
            <!DOCTYPE html>
            <html dir="rtl" lang="fa">
            <head>
                <meta charset="UTF-8">
                <title>گزارش بازدید تجهیزات اتوماسیون</title>
                <style>
                    body { font-family: 'Vazirmatn', Tahoma, Arial, sans-serif; direction: rtl; margin: 2cm; line-height: 1.6; }
                    .header { text-align: center; margin-bottom: 30px; border-bottom: 3px solid #2c3e50; padding-bottom: 20px; }
                    .header h1 { color: #2c3e50; margin: 0; font-size: 24px; }
                    .header h2 { color: #34495e; margin: 10px 0; font-size: 18px; }
                    .section-title { background-color: #2c3e50; color: white; padding: 10px 15px; border-radius: 5px; margin: 20px 0 15px 0; }
                    table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
                    th, td { padding: 10px; border: 1px solid #ddd; text-align: right; }
                    th { background-color: #f2f2f2; }
                    .footer { margin-top: 50px; border-top: 1px solid #ddd; padding-top: 20px; text-align: center; font-size: 12px; color: #666; }
                    .persian-numbers { font-family: 'Vazirmatn', monospace; }
                    .text-end { text-align: left; }
                    @media print { body { margin: 1cm; } }
                </style>
            </head>
            <body>
                <div class="header">
                    <h1>شرکت توزیع نیروی برق استان یزد</h1>
                    <h2>گزارش بازدید تجهیزات اتوماسیون</h2>
                    <p>فرم شماره: F-20324-01 | تاریخ بازدید: ${inspectionDate}</p>
                    <p>پیمانکار: ${contractor} | شماره قرارداد: ${contractNumber}</p>
                    <p>امور شهرستان: ${cityDepartment} | ساعت فعالیت: ${dailyStartTime} - ${dailyEndTime}</p>
                </div>
                
                <div class="section-title">خلاصه مالی</div>
                <table style="width: 100%; border-collapse: collapse; margin-bottom: 20px;">
                    <tr>
                        <th style="padding: 10px; border: 1px solid #ddd; background-color: #f2f2f2; width: 25%;">تعداد تجهیزات:</th>
                        <td style="padding: 10px; border: 1px solid #ddd; width: 25%;" class="persian-numbers">${equipments.length}</td>
                        <th style="padding: 10px; border: 1px solid #ddd; background-color: #f2f2f2; width: 25%;">کل فعالیت‌ها:</th>
                        <td style="padding: 10px; border: 1px solid #ddd; width: 25%;" class="persian-numbers">${totalActivities.toLocaleString()}</td>
                    </tr>
                    <tr>
                        <th style="padding: 10px; border: 1px solid #ddd; background-color: #f2f2f2;">هزینه بدون ضریب:</th>
                        <td style="padding: 10px; border: 1px solid #ddd;" class="persian-numbers">${totalCost.toLocaleString()} ریال</td>
                        <th style="padding: 10px; border: 1px solid #ddd; background-color: #f2f2f2;">ضریب قرارداد:</th>
                        <td style="padding: 10px; border: 1px solid #ddd;" class="persian-numbers">${coefficient}</td>
                    </tr>
                    <tr style="background-color: #d4edda;">
                        <th style="padding: 10px; border: 1px solid #ddd; background-color: #28a745; color: white;">هزینه نهایی:</th>
                        <td colspan="3" style="padding: 10px; border: 1px solid #ddd; font-size: 18px; font-weight: bold;" class="persian-numbers">${finalCost.toLocaleString()} ریال</td>
                    </tr>
                </table>
                
                ${activitiesSummaryHTML}
                ${consumablesSummaryHTML}
                
                <div class="section-title">جزئیات تجهیزات بازدید شده</div>
                ${equipmentHTML}
                
                <div class="footer">
                    <p>سیستم مدیریت بازدید تجهیزات اتوماسیون - شرکت توزیع نیروی برق استان یزد</p>
                    <p>تاریخ چاپ: ${new Date().toLocaleDateString('fa-IR')}</p>
                </div>
            </body>
            </html>
        `;
        
        if (printWindow) {
            printWindow.document.write(htmlContent);
            printWindow.document.close();
            printWindow.focus();
            printWindow.print();
        }
        
    } catch (error) {
        console.error('Error generating PDF report:', error);
        Swal.fire({
            icon: 'error',
            title: 'خطا',
            text: 'خطا در ایجاد گزارش: ' + error.message
        });
    }
}




// =====================================================
// Word Report Functions
// =====================================================

function generateWordReport() {
    try {
        const inspectionDate = document.getElementById('inspection-date')?.value || '';
        const contractor = document.getElementById('contractor')?.value || '';
        const coefficient = parseFloat(document.getElementById('contract-coefficient')?.value) || 2.35;
        const contractNumber = document.getElementById('contract-number')?.value || '';
        const dailyStartTime = document.getElementById('daily-start-time')?.value || '';
        const dailyEndTime = document.getElementById('daily-end-time')?.value || '';
        const cityDepartment = equipments.length > 0 ? equipments[0].departmentData?.department || 'ثبت نشده' : 'ثبت نشده';
        
        let totalActivities = 0;
        let totalCost = 0;
        
        // جمع‌آوری فعالیت‌ها
        let allActivities = [];
        equipments.forEach(equipment => {
            if (equipment.activitiesData && equipment.activitiesData.length > 0) {
                equipment.activitiesData.forEach(activity => {
                    totalActivities += activity.quantity || 0;
                    totalCost += activity.total || 0;
                    allActivities.push({
                        code: activity.code,
                        title: activity.title,
                        unit: activity.unit,
                        unitPrice: activity.unitPrice,
                        quantity: activity.quantity || 0,
                        total: activity.total || 0,
                        equipmentName: equipment.equipmentType,
                        equipmentScada: equipment.scadaCode
                    });
                });
            }
        });
        
        // جمع‌آوری اقلام مصرفی
        let allConsumables = [];
        equipments.forEach(equipment => {
            if (equipment.consumablesData && equipment.consumablesData.length > 0) {
                equipment.consumablesData.forEach(consumable => {
                    allConsumables.push({
                        name: consumable.name,
                        quantity: consumable.quantity || 0,
                        unit: consumable.unit || 'عدد',
                        description: consumable.description || '',
                        equipmentName: equipment.equipmentType,
                        equipmentScada: equipment.scadaCode
                    });
                });
            }
        });
        
        const finalCost = totalCost * coefficient;
        
        // گروه‌بندی فعالیت‌ها
        const groupedActivities = {};
        allActivities.forEach(activity => {
            if (!groupedActivities[activity.code]) {
                groupedActivities[activity.code] = {
                    code: activity.code,
                    title: activity.title,
                    unit: activity.unit,
                    unitPrice: activity.unitPrice,
                    totalQuantity: 0,
                    totalAmount: 0
                };
            }
            groupedActivities[activity.code].totalQuantity += activity.quantity;
            groupedActivities[activity.code].totalAmount += activity.total;
        });
        
        // گروه‌بندی اقلام مصرفی
        const groupedConsumables = {};
        allConsumables.forEach(consumable => {
            if (!groupedConsumables[consumable.name]) {
                groupedConsumables[consumable.name] = {
                    name: consumable.name,
                    unit: consumable.unit,
                    totalQuantity: 0,
                    descriptions: []
                };
            }
            groupedConsumables[consumable.name].totalQuantity += consumable.quantity;
            if (consumable.description && !groupedConsumables[consumable.name].descriptions.includes(consumable.description)) {
                groupedConsumables[consumable.name].descriptions.push(consumable.description);
            }
        });
        
        // ساخت HTML تجهیزات
        let equipmentHTML = '';
        equipments.forEach((equipment, index) => {
            const hasBrands = equipmentWithBrands.includes(equipment.equipmentType);
            const switchBrandDisplay = hasBrands ? 
                (equipment.switchBrand === 'سایر' ? equipment.otherSwitchBrand : equipment.switchBrand) : 
                'بدون برند';
            
            const feedersText = equipment.feeders && equipment.feeders.length > 0 
                ? equipment.feeders.map(f => `${f.post} (${f.feeder})`).join('، ')
                : 'ثبت نشده';
            
            // فعالیت‌های این تجهیز
            let equipmentActivitiesHTML = '';
            const eqActivities = allActivities.filter(a => a.equipmentScada === equipment.scadaCode);
            if (eqActivities.length > 0) {
                equipmentActivitiesHTML = `
                    <h4 style="margin-top: 15px; margin-bottom: 10px; color: #3498db;">فعالیت‌های انجام شده</h4>
                    <table style="width: 100%; border-collapse: collapse; margin-bottom: 15px;">
                        <thead>
                            <tr>
                                <th style="padding: 8px; border: 1px solid #ddd; background-color: #f2f2f2;">کد</th>
                                <th style="padding: 8px; border: 1px solid #ddd; background-color: #f2f2f2;">عنوان فعالیت</th>
                                <th style="padding: 8px; border: 1px solid #ddd; background-color: #f2f2f2;">تعداد</th>
                                <th style="padding: 8px; border: 1px solid #ddd; background-color: #f2f2f2;">مبلغ (ریال)</th>
                            </tr>
                        </thead>
                        <tbody>
                            ${eqActivities.map(act => `
                                <tr>
                                    <td style="padding: 8px; border: 1px solid #ddd;">${act.code}</td>
                                    <td style="padding: 8px; border: 1px solid #ddd;">${act.title}</td>
                                    <td style="padding: 8px; border: 1px solid #ddd;">${act.quantity.toLocaleString()}</td>
                                    <td style="padding: 8px; border: 1px solid #ddd;">${act.total.toLocaleString()}</td>
                                </tr>
                            `).join('')}
                        </tbody>
                    </table>
                `;
            }
            
            // اقلام مصرفی این تجهیز
            let equipmentConsumablesHTML = '';
            const eqConsumables = allConsumables.filter(c => c.equipmentScada === equipment.scadaCode);
            if (eqConsumables.length > 0) {
                equipmentConsumablesHTML = `
                    <h4 style="margin-top: 15px; margin-bottom: 10px; color: #27ae60;">اقلام مصرفی</h4>
                    <table style="width: 100%; border-collapse: collapse; margin-bottom: 15px;">
                        <thead>
                            <tr>
                                <th style="padding: 8px; border: 1px solid #ddd; background-color: #f2f2f2;">نام قلم مصرفی</th>
                                <th style="padding: 8px; border: 1px solid #ddd; background-color: #f2f2f2;">تعداد</th>
                                <th style="padding: 8px; border: 1px solid #ddd; background-color: #f2f2f2;">واحد</th>
                                <th style="padding: 8px; border: 1px solid #ddd; background-color: #f2f2f2;">توضیحات</th>
                            </tr>
                        </thead>
                        <tbody>
                            ${eqConsumables.map(con => `
                                <tr>
                                    <td style="padding: 8px; border: 1px solid #ddd;">${con.name}</td>
                                    <td style="padding: 8px; border: 1px solid #ddd;">${con.quantity.toLocaleString()}</td>
                                    <td style="padding: 8px; border: 1px solid #ddd;">${con.unit}</td>
                                    <td style="padding: 8px; border: 1px solid #ddd;">${con.description || '-'}</td>
                                </tr>
                            `).join('')}
                        </tbody>
                    </table>
                `;
            }
            
            equipmentHTML += `
                <div style="margin-bottom: 30px; padding: 20px; border: 2px solid #ddd; border-radius: 10px; page-break-inside: avoid;">
                    <h3 style="color: #2c3e50; border-bottom: 2px solid #3498db; padding-bottom: 10px;">
                        تجهیز ${index + 1}: ${equipment.equipmentType || 'ثبت نشده'}
                        <span style="font-size: 14px; color: #666;">(کد: ${equipment.scadaCode || 'ثبت نشده'})</span>
                    </h3>
                    
                    <table style="width: 100%; border-collapse: collapse; margin-bottom: 15px;">
                        <tr>
                            <th style="padding: 8px; border: 1px solid #ddd; background-color: #f2f2f2; width: 25%;">زمان فعالیت:</th>
                            <td style="padding: 8px; border: 1px solid #ddd; width: 25%;">${equipment.startTime || '---'} - ${equipment.endTime || '---'}</td>
                            <th style="padding: 8px; border: 1px solid #ddd; background-color: #f2f2f2; width: 25%;">نوع نصب:</th>
                            <td style="padding: 8px; border: 1px solid #ddd; width: 25%;">${equipment.installationType || '---'}</td>
                        </tr>
                        ${hasBrands ? `
                        <tr>
                            <th style="padding: 8px; border: 1px solid #ddd; background-color: #f2f2f2;">برند کلید:</th>
                            <td style="padding: 8px; border: 1px solid #ddd;">${switchBrandDisplay}</td>
                            <th style="padding: 8px; border: 1px solid #ddd; background-color: #f2f2f2;">وضعیت سیگنال:</th>
                            <td style="padding: 8px; border: 1px solid #ddd;">${equipment.communicationData?.signalStatus || '---'}</td>
                        </tr>
                        ` : ''}
                        <tr>
                            <th style="padding: 8px; border: 1px solid #ddd; background-color: #f2f2f2;">امور شهرستان:</th>
                            <td style="padding: 8px; border: 1px solid #ddd;">${equipment.departmentData?.department || 'ثبت نشده'}</td>
                            <th style="padding: 8px; border: 1px solid #ddd; background-color: #f2f2f2;">فیدرها:</th>
                            <td style="padding: 8px; border: 1px solid #ddd;">${feedersText}</td>
                        </tr>
                        <tr>
                            <th style="padding: 8px; border: 1px solid #ddd; background-color: #f2f2f2;">موقعیت جغرافیایی:</th>
                            <td colspan="3" style="padding: 8px; border: 1px solid #ddd;">
                                عرض: ${equipment.locationData?.latitude || 'ثبت نشده'} - طول: ${equipment.locationData?.longitude || 'ثبت نشده'}
                            </td>
                        </tr>
                        <tr>
                            <th style="padding: 8px; border: 1px solid #ddd; background-color: #f2f2f2;">آدرس:</th>
                            <td colspan="3" style="padding: 8px; border: 1px solid #ddd;">${equipment.locationData?.address || 'ثبت نشده'}</td>
                        </tr>
                    </table>
                    
                    ${equipmentActivitiesHTML}
                    ${equipmentConsumablesHTML}
                </div>
            `;
        });
        
        // ساخت جدول خلاصه فعالیت‌ها
        let activitiesSummaryHTML = '';
        if (Object.keys(groupedActivities).length > 0) {
            activitiesSummaryHTML = `
                <div class="section-title">📋 خلاصه فعالیت‌های فهرست بها</div>
                <table style="width: 100%; border-collapse: collapse; margin-bottom: 20px;">
                    <thead>
                        <tr>
                            <th style="padding: 8px; border: 1px solid #ddd; background-color: #f2f2f2;">کد</th>
                            <th style="padding: 8px; border: 1px solid #ddd; background-color: #f2f2f2;">عنوان فعالیت</th>
                            <th style="padding: 8px; border: 1px solid #ddd; background-color: #f2f2f2;">تعداد کل</th>
                            <th style="padding: 8px; border: 1px solid #ddd; background-color: #f2f2f2;">فی واحد (ریال)</th>
                            <th style="padding: 8px; border: 1px solid #ddd; background-color: #f2f2f2;">مبلغ کل (ریال)</th>
                        </tr>
                    </thead>
                    <tbody>
                        ${Object.values(groupedActivities).map(act => `
                            <tr>
                                <td style="padding: 8px; border: 1px solid #ddd;">${act.code}</td>
                                <td style="padding: 8px; border: 1px solid #ddd;">${act.title}</td>
                                <td style="padding: 8px; border: 1px solid #ddd;">${act.totalQuantity.toLocaleString()}</td>
                                <td style="padding: 8px; border: 1px solid #ddd;">${act.unitPrice.toLocaleString()}</td>
                                <td style="padding: 8px; border: 1px solid #ddd;">${act.totalAmount.toLocaleString()}</td>
                            </tr>
                        `).join('')}
                    </tbody>
                    <tfoot>
                        <tr style="background-color: #f2f2f2; font-weight: bold;">
                            <td colspan="4" style="text-align: left;">جمع کل:</td>
                            <td>${totalCost.toLocaleString()} ریال</td>
                        </tr>
                    </tfoot>
                </table>
            `;
        }
        
        // ساخت جدول خلاصه اقلام مصرفی
        let consumablesSummaryHTML = '';
        if (Object.keys(groupedConsumables).length > 0) {
            consumablesSummaryHTML = `
                <div class="section-title">📦 خلاصه اقلام مصرفی</div>
                <table style="width: 100%; border-collapse: collapse; margin-bottom: 20px;">
                    <thead>
                        <tr>
                            <th style="padding: 8px; border: 1px solid #ddd; background-color: #f2f2f2;">نام قلم مصرفی</th>
                            <th style="padding: 8px; border: 1px solid #ddd; background-color: #f2f2f2;">تعداد کل</th>
                            <th style="padding: 8px; border: 1px solid #ddd; background-color: #f2f2f2;">واحد</th>
                            <th style="padding: 8px; border: 1px solid #ddd; background-color: #f2f2f2;">توضیحات</th>
                        </tr>
                    </thead>
                    <tbody>
                        ${Object.values(groupedConsumables).map(con => `
                            <tr>
                                <td style="padding: 8px; border: 1px solid #ddd;">${con.name}</td>
                                <td style="padding: 8px; border: 1px solid #ddd;">${con.totalQuantity.toLocaleString()}</td>
                                <td style="padding: 8px; border: 1px solid #ddd;">${con.unit}</td>
                                <td style="padding: 8px; border: 1px solid #ddd;">${con.descriptions.join('، ') || '-'}</td>
                            </tr>
                        `).join('')}
                    </tbody>
                </table>
            `;
        }
        
        const wordContent = `
            <!DOCTYPE html>
            <html dir="rtl" lang="fa">
            <head>
                <meta charset="UTF-8">
                <title>گزارش بازدید تجهیزات اتوماسیون</title>
                <style>
                    body { font-family: 'B Nazanin', 'Vazirmatn', Tahoma, sans-serif; direction: rtl; margin: 2cm; line-height: 1.5; }
                    .header { text-align: center; margin-bottom: 30px; border-bottom: 3px solid #2c3e50; padding-bottom: 20px; }
                    .header h1 { color: #2c3e50; margin: 0; font-size: 24px; }
                    .header h2 { color: #34495e; margin: 10px 0; font-size: 18px; }
                    .section-title { background-color: #2c3e50; color: white; padding: 10px 15px; border-radius: 5px; margin: 20px 0 15px 0; font-size: 16px; }
                    table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
                    th, td { padding: 8px; border: 1px solid #ddd; text-align: right; vertical-align: top; }
                    th { background-color: #f2f2f2; }
                    .footer { margin-top: 50px; border-top: 1px solid #ddd; padding-top: 20px; text-align: center; font-size: 12px; color: #666; }
                    @media print { body { margin: 1cm; } }
                </style>
            </head>
            <body>
                <div class="header">
                    <h1>شرکت توزیع نیروی برق استان یزد</h1>
                    <h2>گزارش بازدید تجهیزات اتوماسیون</h2>
                    <p>فرم شماره: F-20324-01 | تاریخ بازدید: ${inspectionDate}</p>
                    <p>پیمانکار: ${contractor} | شماره قرارداد: ${contractNumber}</p>
                    <p>امور شهرستان: ${cityDepartment} | ساعت فعالیت: ${dailyStartTime} - ${dailyEndTime}</p>
                </div>
                
                <div class="section-title">💰 خلاصه مالی</div>
                <table style="width: 100%; border-collapse: collapse; margin-bottom: 20px;">
                    <tr>
                        <th style="padding: 10px; width: 25%;">تعداد تجهیزات:</th>
                        <td style="padding: 10px; width: 25%;">${equipments.length}</td>
                        <th style="padding: 10px; width: 25%;">کل فعالیت‌ها:</th>
                        <td style="padding: 10px; width: 25%;">${totalActivities.toLocaleString()}</td>
                    </tr>
                    <tr>
                        <th style="padding: 10px;">هزینه بدون ضریب:</th>
                        <td style="padding: 10px;">${totalCost.toLocaleString()} ریال</td>
                        <th style="padding: 10px;">ضریب قرارداد:</th>
                        <td style="padding: 10px;">${coefficient}</td>
                    </tr>
                    <tr style="background-color: #d4edda;">
                        <th style="padding: 10px; background-color: #28a745; color: white;">هزینه نهایی:</th>
                        <td colspan="3" style="padding: 10px; font-size: 18px; font-weight: bold;">${finalCost.toLocaleString()} ریال</td>
                    </tr>
                </table>
                
                ${activitiesSummaryHTML}
                ${consumablesSummaryHTML}
                
                <div class="section-title">🔧 جزئیات تجهیزات بازدید شده</div>
                ${equipmentHTML}
                
                <div class="footer">
                    <p>سیستم مدیریت بازدید تجهیزات اتوماسیون - شرکت توزیع نیروی برق استان یزد</p>
                    <p>تاریخ چاپ: ${new Date().toLocaleDateString('fa-IR')}</p>
                </div>
            </body>
            </html>
        `;
        
        const blob = new Blob([wordContent], { type: 'application/msword' });
        const filename = `گزارش_بازدید_اتوماسیون_${inspectionDate}_${Date.now()}.doc`;
        saveAsFile(blob, filename);
        
        Swal.fire({
            icon: 'success',
            title: 'موفق',
            text: 'گزارش Word با موفقیت ایجاد شد.',
            timer: 1500,
            showConfirmButton: false
        });
        
    } catch (error) {
        console.error('Error generating Word report:', error);
        Swal.fire({
            icon: 'error',
            title: 'خطا',
            text: 'خطا در ایجاد گزارش Word: ' + error.message
        });
    }
}

// =====================================================
// WhatsApp Functions
// =====================================================

function sendToWhatsApp() {
    const whatsappNumber = document.getElementById('whatsapp-number')?.value;
    
    if (!whatsappNumber) {
        Swal.fire({
            icon: 'warning',
            title: 'خطا',
            text: 'لطفا شماره واتساپ را وارد کنید.'
        });
        return;
    }
    
    const reportMessage = generateWhatsAppReport();
    const encodedMessage = encodeURIComponent(reportMessage);
    const whatsappUrl = `https://wa.me/${whatsappNumber}?text=${encodedMessage}`;
    
    window.open(whatsappUrl, '_blank');
}

function generateWhatsAppReport() {
    const inspectionDate = document.getElementById('inspection-date')?.value || '';
    const contractor = document.getElementById('contractor')?.value || '';
    const coefficient = document.getElementById('contract-coefficient')?.value || '2.35';
    const contractNumber = document.getElementById('contract-number')?.value || '';
    const dailyStartTime = document.getElementById('daily-start-time')?.value || '';
    const dailyEndTime = document.getElementById('daily-end-time')?.value || '';
    const equipmentCount = equipments.length;
    const activityCount = document.getElementById('summary-activity-count')?.textContent || '۰';
    const totalCost = document.getElementById('summary-total-cost')?.textContent || '۰';
    const finalCost = document.getElementById('summary-final-cost')?.textContent || '۰';
    
    // جمع‌آوری خلاصه فعالیت‌ها برای واتساپ
    let activitiesSummary = '';
    let totalActivitiesCount = 0;
    let allActivitiesList = [];
    
    equipments.forEach(equipment => {
        if (equipment.activitiesData && equipment.activitiesData.length > 0) {
            equipment.activitiesData.forEach(activity => {
                totalActivitiesCount += activity.quantity || 0;
                allActivitiesList.push({
                    title: activity.title,
                    quantity: activity.quantity || 0,
                    total: activity.total || 0
                });
            });
        }
    });
    
    // گرفتن 5 فعالیت اول برای خلاصه
    const topActivities = allActivitiesList.slice(0, 5);
    if (topActivities.length > 0) {
        activitiesSummary = `\n📊 *خلاصه فعالیت‌ها:*\n`;
        topActivities.forEach(act => {
            activitiesSummary += `▫️ ${act.title.substring(0, 40)}: ${act.quantity.toLocaleString()} عدد - ${act.total.toLocaleString()} ریال\n`;
        });
        if (allActivitiesList.length > 5) {
            activitiesSummary += `\n... و ${allActivitiesList.length - 5} فعالیت دیگر\n`;
        }
    }
    
    // جمع‌آوری خلاصه تجهیزات
    let equipmentsSummary = '';
    equipments.forEach((equipment, idx) => {
        equipmentsSummary += `\n${idx + 1}. ${equipment.equipmentType} (${equipment.scadaCode || 'بدون کد'})`;
        if (equipment.feeders && equipment.feeders.length > 0) {
            equipmentsSummary += ` - فیدر: ${equipment.feeders[0].feeder || ''}`;
        }
    });
    
    let message = `📋 *گزارش بازدید تجهیزات اتوماسیون*\n`;
    message += `━━━━━━━━━━━━━━━━━━━━━\n`;
    message += `📅 *تاریخ:* ${inspectionDate}\n`;
    message += `👷 *پیمانکار:* ${contractor}\n`;
    message += `📄 *شماره قرارداد:* ${contractNumber}\n`;
    message += `⏰ *ساعت فعالیت:* ${dailyStartTime} - ${dailyEndTime}\n`;
    message += `━━━━━━━━━━━━━━━━━━━━━\n`;
    message += `💰 *ضریب قرارداد:* ${coefficient}\n`;
    message += `⚙️ *تعداد تجهیزات:* ${equipmentCount}\n`;
    message += `📊 *کل فعالیت‌ها:* ${totalActivitiesCount.toLocaleString()}\n`;
    message += `💵 *هزینه بدون ضریب:* ${totalCost}\n`;
    message += `🏆 *هزینه نهایی:* ${finalCost}\n`;
    message += `━━━━━━━━━━━━━━━━━━━━━\n`;
    
    if (activitiesSummary) {
        message += activitiesSummary;
        message += `━━━━━━━━━━━━━━━━━━━━━\n`;
    }
    
    message += `🔧 *تجهیزات بازدید شده:*${equipmentsSummary}\n`;
    message += `━━━━━━━━━━━━━━━━━━━━━\n`;
    message += `🏢 شرکت توزیع نیروی برق استان یزد\n`;
    message += `📱 *این گزارش به صورت خودکار ارسال شده است*`;
    
    return message;
}
// =====================================================
// Auto-save and Draft Functions
// =====================================================

function triggerAutoSave() {
    if (!autoSaveEnabled) return;
    
    if (autoSaveTimer) {
        clearTimeout(autoSaveTimer);
    }
    
    autoSaveTimer = setTimeout(() => {
        saveDraftSilently();
    }, 5000);
}

function saveDraftSilently() {
    try {
        const draftData = {
            inspectionDate: document.getElementById('inspection-date')?.value || '',
            contractor: document.getElementById('contractor')?.value || '',
            coefficient: document.getElementById('contract-coefficient')?.value || '',
            contractNumber: document.getElementById('contract-number')?.value || '',
            whatsappNumber: document.getElementById('whatsapp-number')?.value || '',
            dailyStartTime: document.getElementById('daily-start-time')?.value || '',
            dailyEndTime: document.getElementById('daily-end-time')?.value || '',
            equipments: equipments
        };
        
        localStorage.setItem('automationInspectionDraft', JSON.stringify(draftData));
        console.log('پیش‌نویس به صورت خودکار ذخیره شد.');
    } catch (error) {
        console.error('Error in auto-save:', error);
    }
}

function setupAutoSaveToggle() {
    const toggleBtn = document.getElementById('auto-save-toggle');
    if (toggleBtn) {
        updateAutoSaveButton();
        
        toggleBtn.addEventListener('click', function() {
            autoSaveEnabled = !autoSaveEnabled;
            updateAutoSaveButton();
            
            Swal.fire({
                icon: 'info',
                title: autoSaveEnabled ? 'ذخیره خودکار فعال شد' : 'ذخیره خودکار غیرفعال شد',
                timer: 1500,
                showConfirmButton: false
            });
            
            if (autoSaveEnabled) {
                saveDraftSilently();
            }
        });
    }
}

function updateAutoSaveButton() {
    const toggleBtn = document.getElementById('auto-save-toggle');
    if (toggleBtn) {
        if (autoSaveEnabled) {
            toggleBtn.innerHTML = '<i class="bi bi-check-circle"></i> ذخیره خودکار';
            toggleBtn.className = 'btn btn-outline-success';
        } else {
            toggleBtn.innerHTML = '<i class="bi bi-x-circle"></i> ذخیره خودکار';
            toggleBtn.className = 'btn btn-outline-danger';
        }
    }
}

function saveDraft() {
    try {
        const draftData = {
            inspectionDate: document.getElementById('inspection-date')?.value || '',
            contractor: document.getElementById('contractor')?.value || '',
            coefficient: document.getElementById('contract-coefficient')?.value || '',
            contractNumber: document.getElementById('contract-number')?.value || '',
            whatsappNumber: document.getElementById('whatsapp-number')?.value || '',
            dailyStartTime: document.getElementById('daily-start-time')?.value || '',
            dailyEndTime: document.getElementById('daily-end-time')?.value || '',
            equipments: equipments
        };
        
        localStorage.setItem('automationInspectionDraft', JSON.stringify(draftData));
        Swal.fire({
            icon: 'success',
            title: 'موفق',
            text: 'پیش‌نویس با موفقیت ذخیره شد.',
            timer: 1500,
            showConfirmButton: false
        });
    } catch (error) {
        console.error('Error saving draft:', error);
        Swal.fire({
            icon: 'error',
            title: 'خطا',
            text: 'خطا در ذخیره پیش‌نویس: ' + error.message
        });
    }
}

function loadDraft() {
    Swal.fire({
        title: 'آیا مایل به بارگذاری پیش‌نویس ذخیره‌شده هستید؟',
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'بله',
        cancelButtonText: 'خیر'
    }).then((result) => {
        if (result.isConfirmed) {
            try {
                const draftData = JSON.parse(localStorage.getItem('automationInspectionDraft'));
                if (!draftData) {
                    Swal.fire({
                        icon: 'info',
                        title: 'اطلاعات',
                        text: 'پیش‌نویس ذخیره‌شده‌ای یافت نشد.'
                    });
                    return;
                }
                
                const inspectionDateInput = document.getElementById('inspection-date');
                if (inspectionDateInput) inspectionDateInput.value = draftData.inspectionDate || '';
                
                const contractorSelect = document.getElementById('contractor');
                if (contractorSelect) contractorSelect.value = draftData.contractor || (window.contractorsData && window.contractorsData[0]?.id) || '';
                if (typeof updateContractorDetails === 'function') {
                    updateContractorDetails(contractorSelect);
                }
                
                const coefficientInput = document.getElementById('contract-coefficient');
                if (coefficientInput) coefficientInput.value = draftData.coefficient || '2.35';
                
                const contractNumberInput = document.getElementById('contract-number');
                if (contractNumberInput) contractNumberInput.value = draftData.contractNumber || '.../.../.../...';
                
                const whatsappInput = document.getElementById('whatsapp-number');
                if (whatsappInput) whatsappInput.value = draftData.whatsappNumber || '';
                
                const startTimeInput = document.getElementById('daily-start-time');
                if (startTimeInput) startTimeInput.value = draftData.dailyStartTime || '';
                
                const endTimeInput = document.getElementById('daily-end-time');
                if (endTimeInput) endTimeInput.value = draftData.dailyEndTime || '';
                
                if (draftData.equipments && draftData.equipments.length > 0) {
                    equipments = draftData.equipments;
                    equipmentCount = equipments.length;
                    
                    const container = document.getElementById('equipment-container');
                    if (container) container.innerHTML = '';
                    
                    equipments.forEach(equipment => {
                        const equipmentCard = document.createElement('div');
                        equipmentCard.className = 'equipment-card';
                        equipmentCard.id = equipment.id;
                        
                        equipmentCard.innerHTML = `
                            <div class="equipment-header">
                                <div><i class="bi bi-hdd"></i><span class="ms-2">تجهیز ${equipment.index}</span></div>
                                <div>
                                    <button class="btn btn-sm btn-outline-light me-2" onclick="editEquipment('${equipment.id}')">
                                        <i class="bi bi-pencil"></i> ویرایش
                                    </button>
                                    <button class="btn btn-sm btn-outline-danger" onclick="removeEquipment('${equipment.id}')">
                                        <i class="bi bi-trash"></i> حذف
                                    </button>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-4"><p><strong>نوع تجهیز:</strong> <span id="${equipment.id}-type">${equipment.equipmentType || '---'}</span></p></div>
                                    <div class="col-md-4"><p><strong>کد اسکادا:</strong> <span id="${equipment.id}-scada">${equipment.scadaCode || '---'}</span></p></div>
                                    <div class="col-md-12"><p><strong>فیدرها:</strong> <span id="${equipment.id}-feeders">${equipment.feeders && equipment.feeders.length > 0 ? equipment.feeders.map(f => `${f.post} (${f.feeder})`).join('، ') : '---'}</span></p></div>
                                </div>
                            </div>
                        `;
                        
                        if (container) container.appendChild(equipmentCard);
                    });
                }
                
                Swal.fire({
                    icon: 'success',
                    title: 'موفق',
                    text: 'پیش‌نویس با موفقیت بارگذاری شد.',
                    timer: 1500,
                    showConfirmButton: false
                });
                
            } catch (error) {
                console.error('Error loading draft:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'خطا',
                    text: 'خطا در بارگذاری پیش‌نویس: ' + error.message
                });
            }
        }
    });
}

function clearForm() {
    Swal.fire({
        title: 'آیا از پاک کردن فرم اطمینان دارید؟',
        text: 'تمام اطلاعات حذف خواهند شد.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'بله، پاک شود',
        cancelButtonText: 'انصراف'
    }).then((result) => {
        if (result.isConfirmed) {
            equipments = [];
            equipmentCount = 0;
            
            const container = document.getElementById('equipment-container');
            if (container) container.innerHTML = '';
            
            const techContainer = document.getElementById('tech-info-container');
            if (techContainer) techContainer.innerHTML = '';
            
            const inspectionDateInput = document.getElementById('inspection-date');
            if (inspectionDateInput) inspectionDateInput.value = formatJalaliDate(getCurrentJalaliDate());
            
            const startTimeInput = document.getElementById('daily-start-time');
            if (startTimeInput) startTimeInput.value = '';
            
            const endTimeInput = document.getElementById('daily-end-time');
            if (endTimeInput) endTimeInput.value = '';
            
            const contractorSelect = document.getElementById('contractor');
            if (contractorSelect && window.contractorsData && window.contractorsData.length > 0) {
                contractorSelect.value = window.contractorsData[0].id;
                if (typeof updateContractorDetails === 'function') {
                    updateContractorDetails(contractorSelect);
                }
            } else if (contractorSelect) {
                contractorSelect.value = '1';
            }
            
            const coefficientInput = document.getElementById('contract-coefficient');
            if (coefficientInput) coefficientInput.value = '2.35';
            
            const contractNumberInput = document.getElementById('contract-number');
            if (contractNumberInput) contractNumberInput.value = '.../.../.../...';
            
            const whatsappInput = document.getElementById('whatsapp-number');
            if (whatsappInput) whatsappInput.value = '';
            
            updateStepIndicator(1);
            goToStep(1);
            
            Swal.fire({
                icon: 'success',
                title: 'پاک شد',
                text: 'فرم با موفقیت پاک شد.',
                timer: 1500,
                showConfirmButton: false
            });
        }
    });
}

function printForm() {
    window.print();
}

// =====================================================
// Final Submission Function
// =====================================================

async function submitFinalInspection() {
    try {
        const token = localStorage.getItem('auth_token');
        
        if (!token) {
            Swal.fire({
                icon: 'error',
                title: 'خطا',
                text: 'لطفا ابتدا وارد سیستم شوید'
            });
            return;
        }

        if (equipments.length === 0) {
            Swal.fire({
                icon: 'warning',
                title: 'خطا',
                text: 'حداقل یک تجهیز باید اضافه کنید'
            });
            return;
        }

        // دیباگ: چاپ مقادیر brand_id قبل از ارسال
        console.log('=== بررسی برندهای ارسالی قبل از ثبت ===');
        equipments.forEach((eq, idx) => {
            console.log(`تجهیز ${idx + 1}: brand_id = ${eq.brand_id}, نوع = ${typeof eq.brand_id}, نام برند = ${eq.switchBrand}`);
        });

        Swal.fire({
            title: 'در حال ثبت اطلاعات...',
            text: 'لطفا صبر کنید',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });

        const jalaliDate = document.getElementById('inspection-date')?.value || '';
        
        function convertJalaliToGregorian(jalaliDateStr) {
            try {
                let cleanDate = jalaliDateStr.replace(/[^0-9/]/g, '');
                const persianNumbers = { '۰': '0', '۱': '1', '۲': '2', '۳': '3', '۴': '4', '۵': '5', '۶': '6', '۷': '7', '۸': '8', '۹': '9' };
                for (let [persian, english] of Object.entries(persianNumbers)) {
                    cleanDate = cleanDate.replace(new RegExp(persian, 'g'), english);
                }
                const parts = cleanDate.split('/');
                if (parts.length !== 3) return new Date().toISOString().split('T')[0];
                
                const year = parseInt(parts[0]);
                const month = parseInt(parts[1]);
                const day = parseInt(parts[2]);
                if (isNaN(year) || isNaN(month) || isNaN(day)) return new Date().toISOString().split('T')[0];
                
                const jalaliMonthDays = [31, 31, 31, 31, 31, 31, 30, 30, 30, 30, 30, 29];
                let daysPassed = 0;
                for (let i = 0; i < month - 1; i++) daysPassed += jalaliMonthDays[i];
                daysPassed += day - 1;
                
                const baseGregorianDate = new Date(2021, 2, 21);
                const yearDiff = year - 1400;
                const resultDate = new Date(baseGregorianDate);
                resultDate.setFullYear(baseGregorianDate.getFullYear() + yearDiff);
                resultDate.setDate(baseGregorianDate.getDate() + daysPassed);
                return resultDate.toISOString().split('T')[0];
            } catch (e) {
                console.error('Date conversion error:', e);
                return new Date().toISOString().split('T')[0];
            }
        }

        const gregorianDate = convertJalaliToGregorian(jalaliDate);

        // ========== اصلاح مهم: ساخت processedEquipments با brand_id صحیح ==========
const processedEquipments = equipments.map(equipment => {

    let finalBrandId = null;

    // این سه تجهیز نباید برند داشته باشند
    if (
        !['پست دو سو تغذیه (مشترک حساس)',
'پست دو سو تغذیه (بیمارستانی)',
'مشترک ولتاژ اولیه']
            .includes(equipment.equipmentType)
    ) {

        if (
            equipment.brand_id &&
            !isNaN(parseInt(equipment.brand_id))
        ) {
            finalBrandId = parseInt(equipment.brand_id);
        }

    }

    return {
        equipmentType: equipment.equipmentType || '',
        scadaCode: equipment.scadaCode || '',
        installationType: equipment.installationType || '',

        brand_id: finalBrandId,

        modemBrand: equipment.modemBrand || '',
        rtuBrand: equipment.rtuBrand || '',

        otherModemBrand: equipment.otherModemBrand || '',
        otherRTUBrand: equipment.otherRTUBrand || '',

        startTime: equipment.startTime || '',
        endTime: equipment.endTime || '',

        feeders: equipment.feeders || [],
        departmentData: equipment.departmentData || {},
        locationData: equipment.locationData || {},
        communicationData: equipment.communicationData || {},
        checklistData: equipment.checklistData || [],
        activitiesData: equipment.activitiesData || [],
        consumablesData: equipment.consumablesData || [],
        photosData: equipment.photosData || [],
        cellSpecs: equipment.cellSpecs || {},
        tabsValidated: equipment.tabsValidated || {},

        department_id:
            equipment.departmentData?.department_id || null,

        department_name:
            equipment.departmentData?.department || null
    };

});
        console.log('=== processedEquipments ساخته شد ===');
        processedEquipments.forEach((eq, idx) => {
            console.log(`تجهیز ${idx + 1}: brand_id = ${eq.brand_id}, type = ${typeof eq.brand_id}`);
        });
        // ============================================

        let totalCost = 0;
        equipments.forEach(equipment => {
            if (equipment.activitiesData) {
                equipment.activitiesData.forEach(activity => {
                    totalCost += activity.total || 0;
                });
            }
        });

        const coefficient = parseFloat(document.getElementById('contract-coefficient')?.value) || 2.35;
        
        let userId = null;
        try {
            const userData = localStorage.getItem('user');
            if (userData) {
                const user = JSON.parse(userData);
                userId = user.id;
            }
        } catch(e) {
            console.error('Error parsing user data:', e);
        }

        const contractorSelect = document.getElementById('contractor');
        let contractorId = contractorSelect ? contractorSelect.value : null;
        let contractorName = contractorSelect ? contractorSelect.options[contractorSelect.selectedIndex]?.text : null;

        if (!contractorId) {
            Swal.fire({
                icon: 'error',
                title: 'خطا',
                text: 'لطفا پیمانکار را انتخاب کنید'
            });
            return;
        }

        if (!userId) {
            Swal.fire({
                icon: 'warning',
                title: 'خطا',
                text: 'لطفا وارد سیستم شوید'
            });
            return;
        }

        const inspectionData = {
            user_id: parseInt(userId),
            department_id: null,
            contractor_id: parseInt(contractorId),
            contractor_name: contractorName,
            inspection_date: gregorianDate,
            daily_start_time: document.getElementById('daily-start-time')?.value || '',
            daily_end_time: document.getElementById('daily-end-time')?.value || '',
            contractor: contractorName,
            contract_coefficient: coefficient,
            contract_number: document.getElementById('contract-number')?.value || '',
            whatsapp_number: document.getElementById('whatsapp-number')?.value || '',
            status: 'completed',
            final_status: 'approved',
            equipments: processedEquipments
        };

console.log('=== INSPECTION DATA ===');
console.log(JSON.stringify(inspectionData, null, 2));
inspectionData.equipments.forEach(eq => {
    console.log(
        'TYPE:',
        eq.equipmentType,
        'BRAND:',
        eq.brand_id
    );
});

console.log('=== processedEquipments ===');
processedEquipments.forEach(eq => {
    console.log({
        type: eq.equipmentType,
        brand_id: eq.brand_id
    });
});

        const response = await fetch('/api/inspections', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Authorization': `Bearer ${token}`,
                'Accept': 'application/json'
            },
            body: JSON.stringify(inspectionData)
        });

        const responseText = await response.text();
        let result;
        try {
            result = JSON.parse(responseText);
        } catch (e) {
            console.error('JSON parse error:', e);
            throw new Error('پاسخ سرور نامعتبر است');
        }

        if (!response.ok) {
            if (response.status === 422 && result.errors) {
                const errorMessages = Object.values(result.errors).flat();
                throw new Error(errorMessages.join('\n'));
            }
            throw new Error(result.message || result.error || 'خطا در ثبت اطلاعات');
        }

        const trackingCode = result.data?.id || result.id || 'ثبت شده';
        
        Swal.fire({
            icon: 'success',
            title: '✅ ثبت با موفقیت انجام شد',
            html: `
                <div style="text-align: right;">
                    <p>بازدید با موفقیت در سیستم ثبت شد.</p>
                    <p><strong>کد پیگیری:</strong> ${trackingCode}</p>
                    <p><strong>تاریخ:</strong> ${jalaliDate}</p>
                    <p><strong>تعداد تجهیزات:</strong> ${equipments.length}</p>
                    <p><strong>هزینه نهایی:</strong> ${formatNumber(totalCost * coefficient)} ریال</p>
                </div>
            `,
            confirmButtonText: 'باشه'
        }).then(() => {
            localStorage.removeItem('automationInspectionDraft');
            if (confirm('آیا می‌خواهید یک بازدید جدید شروع کنید؟')) {
                clearForm();
            }
        });

    } catch (error) {
        console.error('Error submitting inspection:', error);
        Swal.fire({
            icon: 'error',
            title: '❌ خطا در ثبت اطلاعات',
            text: error.message || 'خطایی رخ داده است',
            confirmButtonText: 'باشه'
        });
    }
}


// =====================================================
// Datepicker Functions
// =====================================================

function createPersianDatepicker() {
    const input = $('#inspection-date');
    const currentDate = getCurrentJalaliDate();
    const formattedDate = formatJalaliDate(currentDate);
    
    if (input.length && typeof $.fn.persianDatepicker !== 'undefined') {
        input.val(formattedDate);
        input.persianDatepicker({
            format: 'YYYY/MM/DD',
            autoClose: true,
            initialValue: true,
            observer: true,
            calendar: {
                persian: {
                    locale: 'fa'
                }
            }
        });
    } else {
        if (input.length) input.val(formattedDate);
    }
}

// =====================================================
// Initialize Functions
// =====================================================

function initializeSelect2() {
    if (typeof $ !== 'undefined' && $.fn.select2) {
        $('.form-select').each(function() {
            if (!$(this).hasClass('select2-hidden-accessible')) {
                $(this).select2({
                    placeholder: 'انتخاب کنید',
                    allowClear: true,
                    width: '100%',
                    dir: 'rtl'
                });
            }
        });
    }
}

function initializeTimepicker() {
    var startTime = document.getElementById('daily-start-time');
    var endTime = document.getElementById('daily-end-time');
    
    if (startTime) {
        startTime.value = '08:00';
        startTime.addEventListener('change', function() {
            if (!this.value.match(/^([0-1][0-9]|2[0-3]):[0-5][0-9]$/)) {
                this.value = '08:00';
            }
        });
    }
    
    if (endTime) {
        endTime.value = '14:00';
        endTime.addEventListener('change', function() {
            if (!this.value.match(/^([0-1][0-9]|2[0-3]):[0-5][0-9]$/)) {
                this.value = '14:00';
            }
        });
    }
    
    console.log('✅ Time inputs initialized');
}

// =====================================================
// DOM Ready Event
// =====================================================

document.addEventListener('DOMContentLoaded', function() {
    console.log('✅ صفحه بارگذاری شد');
    
    fixTimeValues();
    
    createPersianDatepicker();
    initializeSelect2();
    initializeTimepicker();
    setupAutoSaveToggle();
    updateStepIndicator(1);
    
    const currentDateSpan = document.getElementById('current-date');
    if (currentDateSpan) {
        const todayJalali = getCurrentJalaliDate();
        currentDateSpan.textContent = formatJalaliDate(todayJalali);
    }
    
    const draftData = localStorage.getItem('automationInspectionDraft');
    if (draftData) {
        Swal.fire({
            title: 'پیش‌نویس ذخیره‌شده یافت شد',
            text: 'آیا مایل به بارگذاری آن هستید؟',
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'بله',
            cancelButtonText: 'خیر'
        }).then((result) => {
            if (result.isConfirmed) {
                loadDraft();
            }
        });
    }
    
    const token = localStorage.getItem('auth_token');
    if (token) {
        loadReferenceData();
        
        try {
            const userData = localStorage.getItem('user');
            if (userData) {
                const user = JSON.parse(userData);
                const userNameSpan = document.getElementById('user-name');
                if (userNameSpan) userNameSpan.textContent = user.name || user.email || 'کاربر';
            }
        } catch(e) {}
    } else {
        const userNameSpan = document.getElementById('user-name');
        if (userNameSpan) {
            userNameSpan.innerHTML = '<i class="bi bi-box-arrow-in-right"></i> ورود';
            userNameSpan.style.cursor = 'pointer';
            userNameSpan.onclick = () => showLoginModal();
        }
    }
    
    setTimeout(function() {
        var startTime = document.getElementById('daily-start-time');
        var endTime = document.getElementById('daily-end-time');
        
        if (startTime && (startTime.value === '0808:mm' || startTime.value === '08:08' || startTime.value === '08:8')) {
            startTime.value = '08:00';
        }
        if (endTime && (endTime.value === '1414:mm' || endTime.value === '14:14' || endTime.value === '14:14')) {
            endTime.value = '14:00';
        }
    }, 100);
});

// Login Modal Functions
function showLoginModal() {
    const modalElement = document.getElementById('loginModal');
    if (modalElement && typeof bootstrap !== 'undefined') {
        const modal = new bootstrap.Modal(modalElement);
        modal.show();
    }
}

function handleLogin(event) {
    event.preventDefault();
    
    const email = document.getElementById('email')?.value;
    const password = document.getElementById('password')?.value;
    
    if (!email || !password) {
        Swal.fire({
            icon: 'warning',
            title: 'خطا',
            text: 'ایمیل و رمز عبور را وارد کنید'
        });
        return;
    }
    
    loginUser(email, password);
}

async function loginUser(email, password) {
    try {
        Swal.fire({
            title: 'در حال ورود...',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });
        
        const response = await fetch('/api/login', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify({ email: email, password: password })
        });

        const responseText = await response.text();
        if (!responseText.trim()) throw new Error('پاسخ خالی از سرور دریافت شد');

        let result;
        try {
            result = JSON.parse(responseText);
        } catch (jsonError) {
            throw new Error('فرمت پاسخ سرور نامعتبر است');
        }

        if (!response.ok) throw new Error(result.message || result.error || 'خطا در ورود');
        if (!result.token) throw new Error('توکن دریافتی از سرور نامعتبر است');

        localStorage.setItem('auth_token', result.token);
        if (result.user) {
            localStorage.setItem('user', JSON.stringify(result.user));
        }
        
        Swal.close();
        
        const modal = bootstrap.Modal.getInstance(document.getElementById('loginModal'));
        if (modal) modal.hide();
        
        const userNameSpan = document.getElementById('user-name');
        if (userNameSpan) userNameSpan.textContent = email.split('@')[0];
        
        Swal.fire({
            icon: 'success',
            title: 'ورود موفق',
            text: 'به سیستم خوش آمدید',
            timer: 2000,
            showConfirmButton: false
        });
        
        loadReferenceData();
        
    } catch (error) {
        console.error('Login error:', error);
        Swal.fire({
            icon: 'error',
            title: 'خطا در ورود',
            text: error.message || 'خطایی رخ داده است. لطفا دوباره تلاش کنید.'
        });
    }
}

// =====================================================
// Load Reference Data from Database
// =====================================================

async function loadReferenceData() {
    try {
        const token = localStorage.getItem('auth_token');
        if (!token) {
            console.warn('⚠️ توکن یافت نشد. لطفا وارد سیستم شوید.');
            return;
        }
        
        console.log('🔄 در حال بارگذاری دیتا از دیتابیس...');
        
        const response = await fetch('/api/reference/all', {
            headers: { 
                'Authorization': `Bearer ${token}`,
                'Accept': 'application/json'
            }
        });
        
        if (!response.ok) throw new Error(`HTTP ${response.status}`);
        
        const result = await response.json();
        if (!result.success) throw new Error(result.message || 'خطا در دریافت دیتا');
        
        const data = result;
        
        if (data.equipment_types && data.equipment_types.length) {
            window.equipmentTypes = data.equipment_types.map(et => et.name);
            window.equipmentWithBrands = data.equipment_types.filter(et => et.has_brand == 1).map(et => et.name);
            window.equipmentWithoutBrands = data.equipment_types.filter(et => et.has_brand == 0).map(et => et.name);
            window.equipmentWithoutHeight = data.equipment_types.filter(et => et.has_height == 0).map(et => et.name);
            console.log('✅ انواع تجهیزات:', window.equipmentTypes.length);
        }
        
if (data.brands && data.brands.length) {
    // ذخیره لیست کامل برندها با ID و name
    window.brandsList = data.brands.map(b => ({
        id: b.id,
        name: b.name,
        equipment_type: b.equipment_type
    }));
    
    // برای سازگاری با کدهای قبلی (فقط نام‌ها)
    window.switchBrands = window.brandsList.filter(b => 
        ['ریکلوزر', 'سکسیونر', 'سکشنالایزر'].includes(b.equipment_type)
    ).map(b => b.name);
    
    console.log('✅ برندها بارگذاری شد', window.brandsList.length);
    console.log('نمونه برند اول:', window.brandsList[0]); // برای دیباگ
}    

    
        if (data.departments && data.departments.length) {
            window.cityDepartments = data.departments.map(d => d.name);
            console.log('✅ دپارتمان‌ها:', window.cityDepartments.length);
        }
        
        if (data.activity_prices && data.activity_prices.length) {
            window.priceList = data.activity_prices.map(p => ({
                code: p.code,
                title: p.title,
                unit: p.unit || 'مورد',
                price: p.unit_price
            }));
            console.log('✅ فهرست بها:', window.priceList.length);
        }
        
        if (data.consumable_items && data.consumable_items.length) {
            window.consumablesList = data.consumable_items.map(c => ({
                name: c.name,
                unit: c.unit || 'عدد'
            }));
            console.log('✅ اقلام مصرفی:', window.consumablesList.length);
        }
        
        if (data.checklist_templates && data.checklist_templates.length) {
            window.equipmentChecklists = {};
            for (const template of data.checklist_templates) {
                const equipmentName = template.main_equipment_type?.name;
                if (equipmentName && template.items && template.items.length) {
                    window.equipmentChecklists[equipmentName] = template.items.map(i => i.item_text);
                }
            }
            console.log('✅ چک‌لیست‌ها:', Object.keys(window.equipmentChecklists).length);
        }
        
        if (data.posts && data.feeders) {
            window.postsAndFeedersData = data.posts.map(post => ({
                post: post.name,
                feeders: data.feeders.filter(f => f.post_id === post.id).map(f => f.name)
            }));
            window.postsList = data.posts.map(p => p.name);
            console.log('✅ پست‌ها و فیدرها:', window.postsAndFeedersData.length);
        }
        
if (data.contractors && data.contractors.length) {
    const contractorEl = document.getElementById('contractor');
    if (contractorEl) {
        window.contractorsData = data.contractors;
        // دیباگ: ببینیم contractors چه مقادیری دارند
        console.log('📋 لیست پیمانکاران از دیتابیس:', data.contractors.map(c => ({
            id: c.id,
            name: c.name,
            coefficient: c.coefficient,
            contract_number: c.contract_number
        })));
        
        const select = document.createElement('select');
        select.id = 'contractor';
        select.className = contractorEl.className;
        select.innerHTML = '<option value="">انتخاب کنید</option>' + 
            data.contractors.map(c => {
                // ذخیره coefficient و contract_number در data attributes
                const coeff = c.coefficient || 2.35;
                const contractNum = c.contract_number || '';
                return `<option value="${c.id}" data-coefficient="${coeff}" data-contract-number="${contractNum}">${c.name}</option>`;
            }).join('');
        
        select.addEventListener('change', function() {
            updateContractorDetails(this);
        });
        
        // انتخاب اولین پیمانکار به صورت پیش‌فرض
        if (data.contractors.length > 0) {
            select.value = data.contractors[0].id;
            updateContractorDetails(select);
        }
        
        contractorEl.parentNode.replaceChild(select, contractorEl);
    }
    console.log('✅ پیمانکاران:', data.contractors.length);
}      

  
        console.log('🎉 همه دیتاها با موفقیت از دیتابیس بارگذاری شدند!');
        
    } catch (error) {
        console.error('❌ خطا:', error);
        console.log('⚠️ از دیتای ثابت داخل فرم استفاده می‌شود.');
    }
}

function updateContractorDetails(selectElement) {
    if (!selectElement) return;
    
    const selectedOption = selectElement.options[selectElement.selectedIndex];
    if (!selectedOption || !selectedOption.value) return;
    
    // روش اول: خواندن از data attributes
    let coefficient = selectedOption.getAttribute('data-coefficient');
    let contractNumber = selectedOption.getAttribute('data-contract-number');
    
    // روش دوم: اگر از طریق data attributes نیامد، از selectedOption مستقیماً بخوان
    if (!coefficient && selectedOption.dataset) {
        coefficient = selectedOption.dataset.coefficient;
        contractNumber = selectedOption.dataset.contractNumber;
    }
    
    // روش سوم: اگر باز هم نداشت، از array اصلی پیدا کن
    if ((!coefficient || coefficient === 'null') && window.contractorsData) {
        const contractorId = parseInt(selectedOption.value);
        const foundContractor = window.contractorsData.find(c => c.id === contractorId);
        if (foundContractor) {
            coefficient = foundContractor.coefficient || 2.35;
            contractNumber = foundContractor.contract_number || '';
            console.log('✅ از array پیدا شد:', { coefficient, contractNumber });
        }
    }
    
    // مقدار پیش‌فرض
    coefficient = coefficient && coefficient !== 'null' ? coefficient : 2.35;
    contractNumber = contractNumber || '';
    
    const coefficientInput = document.getElementById('contract-coefficient');
    const contractNumberInput = document.getElementById('contract-number');
    
    if (coefficientInput) {
        coefficientInput.value = coefficient;
        console.log('✅ ضریب تنظیم شد:', coefficient);
    }
    if (contractNumberInput) {
        contractNumberInput.value = contractNumber;
        console.log('✅ شماره قرارداد تنظیم شد:', contractNumber);
    }
}

console.log('✅ inspection-form.js loaded successfully'); 