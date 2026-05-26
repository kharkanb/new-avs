//! moment-jalaali - v0.9.2
//! Copyright (C) 2017-2021, Behrang Noruzi Niya
//! License: MIT

(function (global, factory) {
    typeof exports === 'object' && typeof module !== 'undefined' ? module.exports = factory(require('moment')) :
    typeof define === 'function' && define.amd ? define(['moment'], factory) :
    global.moment = factory(global.moment);
}(this, (function (moment) { 'use strict';

    moment = moment && moment.hasOwnProperty('default') ? moment['default'] : moment;

    var momentJalaali = moment;

    var jalaaliMonthDays = [31, 31, 31, 31, 31, 31, 30, 30, 30, 30, 30, 29];

    function jalaaliMonthLength(jy, jm) {
        if (jm <= 6) return 31;
        if (jm <= 11) return 30;
        if (jalaaliIsLeapYear(jy)) return 30;
        return 29;
    }

    function jalaaliIsLeapYear(jy) {
        return jalCal(jy)[0] === 0;
    }

    function jalCal(jy) {
        var bl = 0;
        var jy0 = jy - 979;
        var jy1 = jy0 - (jy0 >= 0 ? 0 : -33);
        var jy2 = jy1 - (jy1 >= 0 ? 0 : -33);
        var jy3 = jy2 - (jy2 >= 0 ? 0 : -33);
        var jy4 = jy3 - (jy3 >= 0 ? 0 : -33);
        var gy = jy4 * 4 + 1;
        var leap = 0;
        var daysSince = 0;
        var march = 0;
        var i;

        for (i = 0; i < jy4; i++) {
            leap = (i % 33 === 1 || i % 33 === 5 || i % 33 === 9 || i % 33 === 13 || i % 33 === 17 || i % 33 === 22 || i % 33 === 26 || i % 33 === 30) ? 1 : 0;
            daysSince += leap ? 366 : 365;
        }

        if (jy0 >= 0) {
            for (i = 0; i < jy0; i++) {
                leap = (i % 33 === 1 || i % 33 === 5 || i % 33 === 9 || i % 33 === 13 || i % 33 === 17 || i % 33 === 22 || i % 33 === 26 || i % 33 === 30) ? 1 : 0;
                daysSince += leap ? 366 : 365;
            }
        } else {
            for (i = 0; i > jy0; i--) {
                leap = ((i - 1) % 33 === 1 || (i - 1) % 33 === 5 || (i - 1) % 33 === 9 || (i - 1) % 33 === 13 || (i - 1) % 33 === 17 || (i - 1) % 33 === 22 || (i - 1) % 33 === 26 || (i - 1) % 33 === 30) ? 1 : 0;
                daysSince -= leap ? 366 : 365;
            }
        }

        leap = (jy0 % 33 === 1 || jy0 % 33 === 5 || jy0 % 33 === 9 || jy0 % 33 === 13 || jy0 % 33 === 17 || jy0 % 33 === 22 || jy0 % 33 === 26 || jy0 % 33 === 30) ? 1 : 0;

        var leapDays = leap ? 1 : 0;
        var marchDay = daysSince - (leap ? 366 : 365);
        var marchMonth = 3;
        var marchDate = 21;
        var n = leap ? 366 : 365;
        var days = n - (marchDay + 1);
        if (days < 0) {
            gy--;
            days += n;
        }
        var jd = gy * 365 + Math.floor((gy + 3) / 4) - Math.floor((gy + 99) / 100) + Math.floor((gy + 399) / 400) + days;
        jd += (marchDate - 1) + (marchMonth - 1) * 31;
        jd -= 1;
        var jd0 = jd;
        var jm = 0;
        var jd1 = jd0 + 1;

        for (i = 0; i < 12; i++) {
            var daysInMonth = jalaaliMonthLength(jy, i + 1);
            if (jd1 <= daysInMonth) break;
            jd1 -= daysInMonth;
            jm++;
        }

        return [leap, jd1, jm + 1, jy, 0];
    }

    function jalaaliToGregorian(jy, jm, jd) {
        var r = jalCal(jy);
        var gd = r[1] + jd - 1;
        var gm = r[2];
        var gy = r[3];
        var leap = r[0];

        if (gm === 12 && gd > 30) {
            gm = 1;
            gd -= 30;
            gy++;
        } else if (gm === 1 && gd > 31) {
            gm = 2;
            gd -= 31;
        } else if (gm === 2 && gd > 31) {
            gm = 3;
            gd -= 31;
        } else if (gm === 3 && gd > 31) {
            gm = 4;
            gd -= 31;
        } else if (gm === 4 && gd > 31) {
            gm = 5;
            gd -= 31;
        } else if (gm === 5 && gd > 31) {
            gm = 6;
            gd -= 31;
        } else if (gm === 6 && gd > 30) {
            gm = 7;
            gd -= 30;
        } else if (gm === 7 && gd > 30) {
            gm = 8;
            gd -= 30;
        } else if (gm === 8 && gd > 30) {
            gm = 9;
            gd -= 30;
        } else if (gm === 9 && gd > 30) {
            gm = 10;
            gd -= 30;
        } else if (gm === 10 && gd > 30) {
            gm = 11;
            gd -= 30;
        } else if (gm === 11 && gd > 29) {
            gm = 12;
            gd -= 29;
        }

        return [gy, gm, gd];
    }

    function gregorianToJalaali(gy, gm, gd) {
        var jd0 = 0;
        var jy = gy + 621;
        var days = 0;
        var leap = 0;
        var i;

        for (i = 0; i < 12; i++) {
            days += jalaaliMonthDays[i];
        }

        for (i = 0; i < 12; i++) {
            jd0 += jalaaliMonthDays[i];
        }

        var jd = (gy * 365 + Math.floor((gy + 3) / 4) - Math.floor((gy + 99) / 100) + Math.floor((gy + 399) / 400)) + (gd - 1) + (gm - 1) * 31 - jd0;

        var r = jalCal(jy);
        var jm = r[2];
        var jd1 = r[1];
        var jy1 = r[3];
        var gd1 = jd - jd1 + 1;

        if (jd < jd1) {
            jy1--;
            jd1 = jalCal(jy1)[1];
            gd1 = jd - jd1 + 1;
            jy = jy1;
        } else {
            jy = jy1;
        }

        if (gd1 > jalaaliMonthDays[jm - 1]) {
            gd1 -= jalaaliMonthDays[jm - 1];
            jm++;
        }

        return [jy, jm, gd1];
    }

    function loadPersian(options) {
        var usePersianDigits = options && options.usePersianDigits;
        var dialect = options && options.dialect;
        var persianDigits = ['۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹'];

        function toPersianDigits(number) {
            if (!usePersianDigits) return number;
            return String(number).replace(/\d/g, function (digit) {
                return persianDigits[parseInt(digit, 10)];
            });
        }

        moment.updateLocale('fa', {
            months: 'ژانویه_فوریه_مارس_آوریل_مه_ژوئن_ژوئیه_اوت_سپتامبر_اکتبر_نوامبر_دسامبر'.split('_'),
            monthsShort: 'ژانویه_فوریه_مارس_آوریل_مه_ژوئن_ژوئیه_اوت_سپتامبر_اکتبر_نوامبر_دسامبر'.split('_'),
            weekdays: 'یک‌شنبه_دوشنبه_سه‌شنبه_چهارشنبه_پنج‌شنبه_جمعه_شنبه'.split('_'),
            weekdaysShort: 'یک‌شنبه_دوشنبه_سه‌شنبه_چهارشنبه_پنج‌شنبه_جمعه_شنبه'.split('_'),
            weekdaysMin: 'ی_د_س_چ_پ_ج_ش'.split('_'),
            longDateFormat: {
                LT: 'HH:mm',
                LTS: 'HH:mm:ss',
                L: 'jYYYY/jMM/jDD',
                LL: 'jD jMMMM jYYYY',
                LLL: 'jD jMMMM jYYYY LT',
                LLLL: 'dddd، jD jMMMM jYYYY LT'
            },
            calendar: {
                sameDay: '[امروز ساعت] LT',
                nextDay: '[فردا ساعت] LT',
                nextWeek: 'dddd [ساعت] LT',
                lastDay: '[دیروز ساعت] LT',
                lastWeek: 'dddd [ساعت] LT',
                sameElse: 'L'
            },
            relativeTime: {
                future: 'در %s',
                past: '%s پیش',
                s: 'چند ثانیه',
                ss: '%d ثانیه',
                m: 'یک دقیقه',
                mm: '%d دقیقه',
                h: 'یک ساعت',
                hh: '%d ساعت',
                d: 'یک روز',
                dd: '%d روز',
                M: 'یک ماه',
                MM: '%d ماه',
                y: 'یک سال',
                yy: '%d سال'
            },
            ordinal: function (number) {
                return toPersianDigits(number);
            },
            meridiem: function (hour, minute, isLower) {
                if (hour < 12) {
                    return 'قبل از ظهر';
                } else {
                    return 'بعد از ظهر';
                }
            }
        });

        if (dialect) {
            moment.updateLocale('fa', dialect);
        }
    }

    moment.loadPersian = loadPersian;

    var proto = moment.fn;

    proto.jYear = function (input) {
        if (this._a) {
            return this._a[0];
        }
        return moment.isMoment(this) ? this.year() - 621 : parseInt(input, 10);
    };

    proto.jMonth = function (input) {
        if (this._a) {
            return this._a[1];
        }
        return moment.isMoment(this) ? this.month() : parseInt(input, 10);
    };

    proto.jDate = function (input) {
        if (this._a) {
            return this._a[2];
        }
        return moment.isMoment(this) ? this.date() : parseInt(input, 10);
    };

    proto.add = function (input, val) {
        if (this._a) {
            var newDate = this.toDate();
            newDate = moment(newDate).add(input, val);
            this._a = [newDate.jYear(), newDate.jMonth(), newDate.jDate()];
            return this;
        }
        return moment.fn.add.call(this, input, val);
    };

    proto.subtract = function (input, val) {
        if (this._a) {
            var newDate = this.toDate();
            newDate = moment(newDate).subtract(input, val);
            this._a = [newDate.jYear(), newDate.jMonth(), newDate.jDate()];
            return this;
        }
        return moment.fn.subtract.call(this, input, val);
    };

    proto.startOf = function (units) {
        if (this._a) {
            var newDate = moment(this.toDate()).startOf(units);
            this._a = [newDate.jYear(), newDate.jMonth(), newDate.jDate()];
            return this;
        }
        return moment.fn.startOf.call(this, units);
    };

    proto.endOf = function (units) {
        if (this._a) {
            var newDate = moment(this.toDate()).endOf(units);
            this._a = [newDate.jYear(), newDate.jMonth(), newDate.jDate()];
            return this;
        }
        return moment.fn.endOf.call(this, units);
    };

    proto.jDayOfYear = function (input) {
        var day = this.jDate();
        var month = this.jMonth();
        var year = this.jYear();
        var days = 0;
        for (var i = 0; i < month - 1; i++) {
            days += jalaaliMonthLength(year, i + 1);
        }
        days += day;
        if (input !== undefined) {
            return this.add(input - days, 'day');
        }
        return days;
    };

    proto.jWeek = function (input) {
        var weekStart = this.clone().startOf('year').jDayOfYear();
        var weekDay = this.clone().startOf('year').day();
        var week = Math.floor((weekStart - weekDay + 10) / 7);
        if (input !== undefined) {
            return this.add((input - week) * 7, 'day');
        }
        return week;
    };

    proto.jWeekYear = function (input) {
        var year = this.jYear();
        var week = this.jWeek();
        if (week < 1) {
            year--;
        } else if (week > 52) {
            year++;
        }
        if (input !== undefined) {
            return this.add((input - year) * 52, 'week');
        }
        return year;
    };

    return momentJalaali;

})));