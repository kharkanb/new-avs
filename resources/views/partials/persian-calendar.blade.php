<!-- resources/views/partials/persian-calendar.blade.php -->
<style>
.persian-calendar-modal .calendar-grid {
    display: grid;
    grid-template-columns: repeat(7, 1fr);
    gap: 5px;
    margin-top: 10px;
}
.persian-calendar-modal .calendar-weekday {
    text-align: center;
    font-weight: bold;
    padding: 8px;
    background: #f8f9fa;
    border-radius: 4px;
}
.persian-calendar-modal .calendar-day {
    text-align: center;
    padding: 8px;
    border: 1px solid #dee2e6;
    border-radius: 4px;
    cursor: pointer;
    transition: all 0.2s;
}
.persian-calendar-modal .calendar-day:hover {
    background: #3498db;
    color: white;
}
.persian-calendar-modal .calendar-day.today {
    background: #27ae60;
    color: white;
    font-weight: bold;
}
.persian-calendar-modal .calendar-day.empty {
    border: none;
    cursor: default;
}
.persian-calendar-modal .calendar-day.empty:hover {
    background: transparent;
}
</style>

<!-- مودال تقویم شمسی -->
<div class="modal fade persian-calendar-modal" id="persianCalendarModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">انتخاب تاریخ</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="calendarBody">
                <div class="text-center py-3">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">در حال بارگذاری...</span>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">بستن</button>
            </div>
        </div>
    </div>
</div>

<script>
// متغیرهای سراسری برای تقویم
let activeDateInput = null;
let currentYear = null;
let currentMonth = null;

// تابع تبدیل میلادی به شمسی (دستی)
function toJalaliDate(gy, gm, gd) {
    let g_d_m = [0, 31, 59, 90, 120, 151, 181, 212, 243, 273, 304, 334];
    let jy = (gy <= 1600) ? 0 : 979;
    gy -= (gy <= 1600) ? 621 : 1600;
    let gy2 = (gm > 2) ? (gy + 1) : gy;
    let days = (365 * gy) + Math.floor((gy2 + 3) / 4) - Math.floor((gy2 + 99) / 100) + Math.floor((gy2 + 399) / 400) - 80 + gd + g_d_m[gm - 1];
    jy += 33 * Math.floor(days / 12053);
    days %= 12053;
    jy += 4 * Math.floor(days / 1461);
    days %= 1461;
    jy += Math.floor((days - 1) / 365);
    if (days > 365) days = (days - 1) % 365;
    let jm = (days < 186) ? 1 + Math.floor(days / 31) : 7 + Math.floor((days - 186) / 30);
    let jd = 1 + ((days < 186) ? (days % 31) : ((days - 186) % 30));
    return [jy, jm, jd];
}

// تابع نمایش تقویم
window.showPersianCalendar = function(inputId) {
    activeDateInput = document.getElementById(inputId);
    if (!activeDateInput) return;
    
    // دریافت تاریخ فعلی میلادی و تبدیل به شمسی
    const now = new Date();
    const [year, month, day] = toJalaliDate(now.getFullYear(), now.getMonth() + 1, now.getDate());
    
    renderCalendar(year, month);
    
    const modal = new bootstrap.Modal(document.getElementById('persianCalendarModal'));
    modal.show();
}

// تابع محاسبه تعداد روزهای ماه
function getMonthDays(year, month) {
    if (month <= 6) return 31;
    if (month <= 11) return 30;
    // اسفند
    return (year % 4 === 3) ? 30 : 29; // سال کبیسه
}

// تابع محاسبه روز اول ماه
function getFirstDayOfMonth(year, month) {
    // مبنا: 1 فروردین 1403 = جمعه (5)
    const baseYear = 1403;
    const baseMonth = 1;
    const baseDay = 5;
    
    let totalDays = 0;
    
    // محاسبه روزهای سال‌های گذشته
    for (let y = baseYear; y < year; y++) {
        totalDays += (y % 4 === 3) ? 366 : 365;
    }
    
    // محاسبه روزهای ماه‌های قبل از ماه جاری
    for (let m = 1; m < month; m++) {
        totalDays += getMonthDays(year, m);
    }
    
    return (baseDay + totalDays) % 7;
}

// تابع رندر تقویم
window.renderCalendar = function(year, month) {
    const persianMonths = [
        'فروردین', 'اردیبهشت', 'خرداد', 'تیر', 'مرداد', 'شهریور',
        'مهر', 'آبان', 'آذر', 'دی', 'بهمن', 'اسفند'
    ];
    
    const weekDays = ['ش', 'ی', 'د', 'س', 'چ', 'پ', 'ج'];
    
    try {
        const daysInMonth = getMonthDays(year, month);
        const startDayOfWeek = getFirstDayOfMonth(year, month);
        
        let html = `
            <div class="text-center mb-3">
                <div class="row g-2 align-items-center">
                    <div class="col-4">
                        <button class="btn btn-sm btn-outline-secondary w-100" onclick="changeMonth(-1)">
                            <i class="bi bi-chevron-right"></i>
                        </button>
                    </div>
                    <div class="col-4">
                        <span class="fw-bold">${persianMonths[month-1]} ${year}</span>
                    </div>
                    <div class="col-4">
                        <button class="btn btn-sm btn-outline-secondary w-100" onclick="changeMonth(1)">
                            <i class="bi bi-chevron-left"></i>
                        </button>
                    </div>
                </div>
            </div>
            <div class="calendar-grid">
        `;
        
        weekDays.forEach(day => {
            html += `<div class="calendar-weekday">${day}</div>`;
        });
        
        for (let i = 0; i < startDayOfWeek; i++) {
            html += '<div class="calendar-day empty"></div>';
        }
        
        // تاریخ امروز شمسی
        const now = new Date();
        const [todayYear, todayMonth, todayDay] = toJalaliDate(now.getFullYear(), now.getMonth() + 1, now.getDate());
        
        for (let day = 1; day <= daysInMonth; day++) {
            const isToday = (todayYear === year && todayMonth === month && day === todayDay);
            const dayClass = isToday ? 'calendar-day today' : 'calendar-day';
            html += `<div class="${dayClass}" onclick="selectDate('${year}/${month}/${day}')">${day}</div>`;
        }
        
        html += '</div>';
        
        document.getElementById('calendarBody').innerHTML = html;
        
        currentYear = year;
        currentMonth = month;
    } catch (error) {
        console.error('خطا در رندر تقویم:', error);
        document.getElementById('calendarBody').innerHTML = `
            <div class="alert alert-danger text-center">
                خطا در بارگذاری تقویم
            </div>
        `;
    }
}

window.changeMonth = function(delta) {
    let year = currentYear;
    let month = currentMonth + delta;
    
    if (month < 1) {
        month = 12;
        year--;
    } else if (month > 12) {
        month = 1;
        year++;
    }
    
    renderCalendar(year, month);
}

window.selectDate = function(date) {
    if (activeDateInput) {
        activeDateInput.value = date;
        const event = new Event('change', { bubbles: true });
        activeDateInput.dispatchEvent(event);
    }
    
    const modal = bootstrap.Modal.getInstance(document.getElementById('persianCalendarModal'));
    if (modal) {
        modal.hide();
    }
}
</script>