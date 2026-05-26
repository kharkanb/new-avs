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
        Schema::create('equipment_feeders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('main_equipment_id')
                  ->constrained()
                  ->onDelete('cascade')
                  ->comment('شناسه تجهیز اصلی');
            
            $table->string('post', 191)
                  ->nullable()
                  ->comment('نام پست');
            
            $table->string('feeder', 191)
                  ->nullable()
                  ->comment('نام فیدر');
            
            $table->timestamps();
            
            // ایندکس‌ها برای جستجوی سریع
            $table->index('main_equipment_id');
            $table->index('post');
            $table->index('feeder');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('equipment_feeders');
    }
};