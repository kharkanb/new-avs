import React from 'react';
import { Link } from '@inertiajs/react';

export default function DashboardLayout({ children, user }) {
    const menuItems = [
        { name: 'داشبورد', icon: '📊', href: '/dashboard', permission: '*' },
        { name: 'بازدیدها', icon: '🔍', href: '/dashboard/inspections', permission: '*' },
        { name: 'گزارش‌گیری', icon: '📈', href: '/dashboard/reports', permission: '*' },
        { name: 'کاربران', icon: '👥', href: '/dashboard/users', permission: 'admin' },
        { name: 'تنظیمات', icon: '⚙️', href: '/dashboard/settings', permission: 'admin' },
    ];

    return (
        <div className="min-h-screen bg-gray-100" dir="rtl">
            {/* نوار بالایی */}
            <nav className="bg-white shadow-lg">
                <div className="max-w-7xl mx-auto px-4">
                    <div className="flex justify-between h-16">
                        <div className="flex items-center">
                            <h1 className="text-xl font-bold text-gray-800">
                                سیستم مدیریت بازدید
                            </h1>
                        </div>
                        <div className="flex items-center space-x-4 space-x-reverse">
                            <span className="text-gray-600">
                                {user.name} 
                                <span className="mr-2 text-sm bg-blue-100 text-blue-800 px-2 py-1 rounded">
                                    {user.role === 'admin' ? 'مدیر' : 'کاربر'}
                                </span>
                            </span>
                            <Link
                                href="/logout"
                                method="post"
                                as="button"
                                className="text-red-600 hover:text-red-800"
                            >
                                خروج
                            </Link>
                        </div>
                    </div>
                </div>
            </nav>

            <div className="flex">
                {/* منوی کناری */}
                <aside className="w-64 bg-white shadow-lg min-h-screen">
                    <div className="py-4">
                        {menuItems.map((item) => {
                            if (item.permission !== '*' && user.role !== 'admin') return null;
                            return (
                                <Link
                                    key={item.href}
                                    href={item.href}
                                    className="flex items-center px-6 py-3 text-gray-700 hover:bg-blue-50 hover:text-blue-700 transition-colors"
                                >
                                    <span className="ml-3">{item.icon}</span>
                                    <span>{item.name}</span>
                                </Link>
                            );
                        })}
                    </div>
                </aside>

                {/* محتوای اصلی */}
                <main className="flex-1 p-6">
                    {children}
                </main>
            </div>
        </div>
    );
}