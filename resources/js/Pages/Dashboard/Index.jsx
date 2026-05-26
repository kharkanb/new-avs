import React from 'react';
import DashboardLayout from './Layout';

export default function DashboardIndex({ stats, user }) {
    const statCards = [
        { title: 'کل بازدیدها', value: stats.total_inspections, icon: '📋', color: 'blue' },
        { title: 'تجهیزات', value: stats.total_equipments, icon: '⚙️', color: 'green' },
    ];

    if (user.role === 'admin') {
        statCards.push(
            { title: 'کاربران', value: stats.total_users, icon: '👥', color: 'purple' },
            { title: 'بازدیدهای در انتظار', value: stats.pending_inspections, icon: '⏳', color: 'yellow' }
        );
    } else {
        statCards.push(
            { title: 'بازدیدهای من', value: stats.my_inspections, icon: '📌', color: 'orange' }
        );
    }

    return (
        <DashboardLayout user={user}>
            <div className="mb-8">
                <h2 className="text-2xl font-bold text-gray-800 mb-4">
                    خوش آمدید، {user.name}!
                </h2>
                <p className="text-gray-600">
                    {user.role === 'admin' 
                        ? 'به پنل مدیریت خوش آمدید. می‌توانید تمام بخش‌ها را مدیریت کنید.'
                        : 'به پنل کاربری خوش آمدید. می‌توانید بازدیدهای خود را ثبت و مدیریت کنید.'}
                </p>
            </div>

            {/* کارت‌های آمار */}
            <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                {statCards.map((card) => (
                    <div key={card.title} className={`bg-white rounded-lg shadow-lg p-6 border-r-4 border-${card.color}-500`}>
                        <div className="flex items-center justify-between">
                            <div>
                                <p className="text-gray-500 text-sm">{card.title}</p>
                                <p className="text-3xl font-bold text-gray-800">{card.value}</p>
                            </div>
                            <span className="text-4xl">{card.icon}</span>
                        </div>
                    </div>
                ))}
            </div>

            {/* فعالیت‌های اخیر */}
            <div className="bg-white rounded-lg shadow-lg p-6">
                <h3 className="text-lg font-semibold text-gray-800 mb-4">فعالیت‌های اخیر</h3>
                <div className="space-y-3">
                    {/* اینجا می‌تونی فعالیت‌های اخیر رو از دیتابیس بکشی و نمایش بدی */}
                    <div className="flex items-center text-gray-600">
                        <span className="w-2 h-2 bg-green-500 rounded-full ml-3"></span>
                        <span>بازدید جدید توسط کاربر test ثبت شد</span>
                        <span className="mr-auto text-sm text-gray-400">۲ ساعت پیش</span>
                    </div>
                </div>
            </div>
        </DashboardLayout>
    );
}