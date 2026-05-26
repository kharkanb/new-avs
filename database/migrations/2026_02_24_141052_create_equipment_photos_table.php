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
        Schema::create('equipment_photos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('main_equipment_id')
                  ->constrained()
                  ->onDelete('cascade')
                  ->comment('شناسه تجهیز اصلی');
            
            $table->string('scan_code', 100)
                  ->nullable()
                  ->comment('کد عکس');
            
            $table->text('description')
                  ->nullable()
                  ->comment('توضیحات عکس');
            
            $table->string('path', 500)
                  ->comment('مسیر فایل عکس');
            
            $table->integer('sort_order')
                  ->default(0)
                  ->comment('ترتیب نمایش');
            
            $table->timestamps();
            
            // ایندکس‌ها
            $table->index('main_equipment_id');
            $table->index('scan_code');
            $table->index('sort_order');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('equipment_photos');
    }
};