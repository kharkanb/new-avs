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
        Schema::create('equipment_consumables', function (Blueprint $table) {
            $table->id();
            $table->foreignId('main_equipment_id')
                  ->constrained()
                  ->onDelete('cascade')
                  ->comment('شناسه تجهیز اصلی');
            
            $table->string('name', 200)
                  ->comment('نام قلم مصرفی');
            
            $table->string('other_name', 200)
                  ->nullable()
                  ->comment('نام دیگر (برای سایر اقلام)');
            
            $table->integer('quantity')
                  ->default(1)
                  ->comment('تعداد');
            
            $table->string('unit', 50)
                  ->nullable()
                  ->comment('واحد (عدد، متر، ...)');
            
            $table->text('description')
                  ->nullable()
                  ->comment('توضیحات');
            
            $table->timestamps();
            
            // ایندکس‌ها
            $table->index('main_equipment_id');
            $table->index('name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('equipment_consumables');
    }
};