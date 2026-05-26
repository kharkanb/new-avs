<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('equipment_communications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('main_equipment_id')
                  ->constrained()
                  ->onDelete('cascade')
                  ->unique()
                  ->comment('شناسه تجهیز اصلی (یک به یک)');
            
            $table->string('simcard_type', 50)
                  ->nullable()
                  ->comment('نوع سیم‌کارت (ایرانسل، همراه اول، ...)');
            
            $table->string('simcard_number', 20)
                  ->nullable()
                  ->comment('شماره سیم‌کارت');
            
            $table->string('simcard_ip', 45)
                  ->nullable()
                  ->comment('آی‌پی سیم‌کارت');
            
            $table->string('antenna_status', 50)
                  ->nullable()
                  ->comment('وضعیت نصب آنتن');
            
            $table->string('signal_status', 20)
                  ->nullable()
                  ->comment('وضعیت سیگنال (خوب/ضعیف)');
            
            $table->string('modem_power', 20)
                  ->nullable()
                  ->comment('تغذیه مودم (پنل/باتری)');
            
            $table->boolean('reset_possible')
                  ->default(false)
                  ->comment('قابلیت ریست');
            
            $table->timestamps();
            
            // ایندکس‌ها
            $table->index('main_equipment_id');
            $table->index('simcard_number');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('equipment_communications');
    }
};