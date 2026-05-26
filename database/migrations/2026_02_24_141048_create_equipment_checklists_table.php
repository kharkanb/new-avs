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
        Schema::create('equipment_checklists', function (Blueprint $table) {
            $table->id();
            $table->foreignId('main_equipment_id')
                  ->constrained()
                  ->onDelete('cascade')
                  ->comment('شناسه تجهیز اصلی');
            
            $table->string('item', 500)
                  ->comment('عنوان آیتم چک‌لیست');
            
            $table->enum('status', ['OK', 'Not OK', 'Not Checked'])
                  ->default('Not Checked')
                  ->comment('وضعیت آیتم');
            
            $table->text('description')
                  ->nullable()
                  ->comment('توضیحات و اقدامات اصلاحی');
            
            $table->integer('sort_order')
                  ->default(0)
                  ->comment('ترتیب نمایش');
            
            $table->timestamps();
            
            // ایندکس‌ها
            $table->index('main_equipment_id');
            $table->index('status');
            $table->index('sort_order');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('equipment_checklists');
    }
};