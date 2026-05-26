<template>
    <div class="login-wrapper">
        <div class="login-card">
            <div class="login-header">
                <i class="bi bi-grid-3x3-gap-fill"></i>
                <h2>سیستم بازدید تجهیزات</h2>
                <p>شرکت توزیع نیروی برق استان یزد</p>
            </div>
            
            <div class="login-body">
                <div v-if="status" class="alert alert-success">
                    {{ status }}
                </div>
                
                <div v-if="Object.keys(errors).length > 0" class="alert alert-danger">
                    اطلاعات وارد شده صحیح نمی‌باشد.
                </div>
                
                <form @submit.prevent="submit">
                    <div class="form-group">
                        <label>ایمیل</label>
                        <input type="email" v-model="form.email" class="form-control" placeholder="admin@example.com" autofocus>
                        <div v-if="form.errors.email" class="error-text">{{ form.errors.email }}</div>
                    </div>
                    
                    <div class="form-group">
                        <label>رمز عبور</label>
                        <input type="password" v-model="form.password" class="form-control" placeholder="********">
                        <div v-if="form.errors.password" class="error-text">{{ form.errors.password }}</div>
                    </div>
                    
                    <div class="form-group">
                        <label class="checkbox">
                            <input type="checkbox" v-model="form.remember">
                            <span>مرا به خاطر بسپار</span>
                        </label>
                    </div>
                    
                    <button type="submit" class="btn-login" :disabled="form.processing">
                        {{ form.processing ? 'در حال ورود...' : 'ورود به سیستم' }}
                    </button>
                    
                    <div class="form-footer" v-if="canResetPassword">
                        <a :href="route('password.request')">رمز عبور را فراموش کرده‌اید؟</a>
                    </div>
                </form>
            </div>
        </div>
        
        <div class="company-info">
            شرکت توزیع نیروی برق استان یزد | نسخه 2.0
        </div>
    </div>
</template>

<script>
import { useForm } from '@inertiajs/inertia-vue3';

export default {
    props: {
        canResetPassword: Boolean,
        status: String,
        errors: Object
    },
    setup() {
        const form = useForm({
            email: '',
            password: '',
            remember: false,
        });

        function submit() {
            form.post(route('login'), {
                onFinish: () => form.reset('password'),
            });
        }

        return { form, submit };
    }
};
</script>

<style scoped>
* {
    font-family: 'Vazirmatn', sans-serif;
}

.login-wrapper {
    min-height: 100vh;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    display: flex;
    align-items: center;
    justify-content: center;
    flex-direction: column;
    padding: 20px;
}

.login-card {
    background: white;
    border-radius: 24px;
    box-shadow: 0 20px 40px rgba(0,0,0,0.2);
    width: 100%;
    max-width: 420px;
    overflow: hidden;
}

.login-header {
    background: linear-gradient(135deg, #2c3e50, #1e2b37);
    padding: 40px 30px;
    text-align: center;
    color: white;
}

.login-header i {
    font-size: 50px;
    color: #3498db;
    margin-bottom: 15px;
}

.login-header h2 {
    font-size: 1.5rem;
    margin-bottom: 5px;
}

.login-header p {
    font-size: 0.8rem;
    opacity: 0.8;
}

.login-body {
    padding: 35px 30px;
}

.form-group {
    margin-bottom: 20px;
}

.form-group label {
    display: block;
    font-weight: 600;
    color: #333;
    margin-bottom: 8px;
}

.form-control {
    width: 100%;
    padding: 12px 15px;
    border: 2px solid #e9ecef;
    border-radius: 12px;
    font-size: 0.95rem;
    transition: all 0.3s;
}

.form-control:focus {
    border-color: #3498db;
    outline: none;
}

.checkbox {
    display: flex;
    align-items: center;
    gap: 8px;
    cursor: pointer;
    font-weight: normal;
}

.btn-login {
    width: 100%;
    padding: 12px;
    background: linear-gradient(135deg, #3498db, #2980b9);
    border: none;
    border-radius: 12px;
    color: white;
    font-size: 1rem;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s;
}

.btn-login:hover:not(:disabled) {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(52,152,219,0.4);
}

.btn-login:disabled {
    opacity: 0.7;
    cursor: not-allowed;
}

.form-footer {
    text-align: center;
    margin-top: 20px;
    padding-top: 15px;
    border-top: 1px solid #e9ecef;
}

.form-footer a {
    color: #3498db;
    text-decoration: none;
    font-size: 0.85rem;
}

.alert {
    padding: 12px;
    border-radius: 12px;
    margin-bottom: 20px;
    text-align: center;
}

.alert-success {
    background: #d4edda;
    color: #28a745;
}

.alert-danger {
    background: #fee2e2;
    color: #dc3545;
}

.error-text {
    color: #dc3545;
    font-size: 0.75rem;
    margin-top: 5px;
}

.company-info {
    text-align: center;
    margin-top: 20px;
    color: rgba(255,255,255,0.7);
    font-size: 0.75rem;
}
</style>