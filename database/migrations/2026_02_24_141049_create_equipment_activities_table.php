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
        Schema::create('equipment_activities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('main_equipment_id')
                  ->constrained()
                  ->onDelete('cascade')
                  ->comment('شناسه تجهیز اصلی');
            
            $table->string('code', 20)
                  ->comment('کد فهرست بها');
            
            $table->string('title', 500)
                  ->nullable()
                  ->comment('عنوان فعالیت');
            
            $table->string('unit', 50)
                  ->nullable()
                  ->comment('واحد');
            
            $table->decimal('unit_price', 15, 2)
                  ->nullable()
                  ->comment('فی واحد (ریال)');
            
            $table->integer('quantity')
                  ->default(1)
                  ->comment('تعداد');
            
            $table->decimal('total', 15, 2)
                  ->nullable()
                  ->comment('مبلغ کل (ریال)');
            
            $table->timestamps();
            
            // ایندکس‌ها
            $table->index('main_equipment_id');
            $table->index('code');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('equipment_activities');
    }
};