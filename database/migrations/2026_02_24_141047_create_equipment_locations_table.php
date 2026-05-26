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
        Schema::create('equipment_locations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('main_equipment_id')
                  ->constrained()
                  ->onDelete('cascade')
                  ->unique()
                  ->comment('شناسه تجهیز اصلی (یک به یک)');
            
            $table->decimal('latitude', 10, 7)
                  ->nullable()
                  ->comment('عرض جغرافیایی');
            
            $table->decimal('longitude', 10, 7)
                  ->nullable()
                  ->comment('طول جغرافیایی');
            
            $table->text('address')
                  ->nullable()
                  ->comment('آدرس نصب');
            
            $table->decimal('cabinet_initial_height', 5, 2)
                  ->nullable()
                  ->comment('ارتفاع اولیه تابلو (متر)');
            
            $table->decimal('cabinet_final_height', 5, 2)
                  ->nullable()
                  ->comment('ارتفاع نهایی تابلو (متر)');
            
            $table->timestamps();
            
            // ایندکس‌ها
            $table->index('main_equipment_id');
            $table->index(['latitude', 'longitude']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('equipment_locations');
    }
};