import React, { useState } from 'react';
import { useForm, Link } from '@inertiajs/inertia-react';

export default function Login({ status, canResetPassword, errors }) {
    const { data, setData, post, processing } = useForm({
        email: '',
        password: '',
        remember: false,
    });

    const submit = (e) => {
        e.preventDefault();
        post(route('login'));
    };

    return (
        <div className="login-wrapper">
            {/* RTU و کنترلرها */}
            <div className="bg-emoji-1">🎛️</div>
            <div className="bg-emoji-2">🕹️</div>
            <div className="bg-emoji-3">📟</div>
            <div className="bg-emoji-4">💻</div>
            <div className="bg-emoji-5">🖥️</div>
            
            {/* مودم و ارتباطات بیسیم */}
            <div className="bg-emoji-6">📡</div>
            <div className="bg-emoji-7">📶</div>
            <div className="bg-emoji-8">📱</div>
            <div className="bg-emoji-9">🌐</div>
            <div className="bg-emoji-10">🔄</div>
            <div className="bg-emoji-11">📞</div>
            
            {/* تابلو برق و تجهیزات */}
            <div className="bg-emoji-16">⚡</div>
            <div className="bg-emoji-17">🔌</div>
            <div className="bg-emoji-18">🔋</div>
            <div className="bg-emoji-19">💡</div>
            <div className="bg-emoji-20">🔧</div>
            <div className="bg-emoji-21">⚙️</div>
            
            {/* مانیتورینگ و نمایشگر */}
            <div className="bg-emoji-22">📺</div>
            <div className="bg-emoji-23">📊</div>
            <div className="bg-emoji-24">📈</div>
            <div className="bg-emoji-25">🎯</div>
            
            {/* امنیت */}
            <div className="bg-emoji-26">🔒</div>
            <div className="bg-emoji-27">🛡️</div>
            
            {/* سیم کارت */}
            <div className="bg-emoji-29">📇</div>
            <div className="bg-emoji-30">📲</div>
            
            {/* تنظیمات */}
            <div className="bg-emoji-31">🎚️</div>
            <div className="bg-emoji-32">⏱️</div>
            <div className="bg-emoji-33">🗝️</div>
            <div className="bg-emoji-34">📳</div>
            
            <div className="login-card">
                <div className="login-left">
                    <div className="login-header">
                        <div className="logo-icon">
                            <i className="bi bi-grid-3x3-gap-fill"></i>
                        </div>
                        <h2>سیستم بازدید تجهیزات</h2>
                        <p>شرکت توزیع نیروی برق استان یزد</p>
                    </div>
                    
                    {status && (
                        <div className="alert alert-success">{status}</div>
                    )}
                    
                    {Object.keys(errors).length > 0 && (
                        <div className="alert alert-danger">
                            اطلاعات وارد شده صحیح نمی‌باشد.
                        </div>
                    )}
                    
                    <form onSubmit={submit}>
                        <div className="form-group">
                            <label>ایمیل</label>
                            <input
                                type="email"
                                value={data.email}
                                onChange={e => setData('email', e.target.value)}
                                className="form-control"
                                placeholder="admin@example.com"
                                autoFocus
                            />
                        </div>
                        
                        <div className="form-group">
                            <label>رمز عبور</label>
                            <input
                                type="password"
                                value={data.password}
                                onChange={e => setData('password', e.target.value)}
                                className="form-control"
                                placeholder="********"
                            />
                        </div>
                        
                        <div className="form-group checkbox-group">
                            <label className="checkbox">
                                <span>مرا به خاطر بسپار</span>
                                <input
                                    type="checkbox"
                                    checked={data.remember}
                                    onChange={e => setData('remember', e.target.checked)}
                                />
                            </label>
                        </div>
                        
                        <button type="submit" className="btn-login" disabled={processing}>
                            {processing ? 'در حال ورود...' : 'ورود به سیستم'}
                        </button>
                        
                        {canResetPassword && (
                            <div className="form-footer">
                                <Link href={route('password.request')}>رمز عبور را فراموش کرده‌اید؟</Link>
                            </div>
                        )}
                    </form>
                </div>
                
                <div className="login-right">
                    <div className="equipment-showcase">
                        <h3 className="animated-title">
                            <span>سامانه مدیریت بازدید</span>
                            <span>تجهیزات اتوماسیون</span>
                        </h3>
                    </div>
                </div>
            </div>
            
            <div className="company-footer">
                شرکت توزیع نیروی برق استان یزد | نسخه 2.0
            </div>
        </div>
    );
}