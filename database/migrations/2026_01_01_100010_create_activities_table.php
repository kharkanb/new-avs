<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('activities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('main_equipment_id')->constrained()->cascadeOnDelete(); // ✅ این خط رو اضافه کن
            $table->morphs('activitable'); // این خط هم activitable_type و هم activitable_id را ایجاد می‌کند و ایندکس هم می‌گذارد
            $table->string('activity_code', 20);
            $table->text('description')->nullable();
            $table->string('unit', 50)->nullable();
            $table->decimal('unit_price', 12, 2)->nullable();
            $table->integer('quantity')->default(1);
            $table->decimal('total_price', 12, 2)->nullable();
            $table->timestamps();
            
            $table->index('activity_code');
            // خط زیر را حذف کنید چون morphs قبلاً ایندکس را ایجاد کرده
            // $table->index(['activitable_type', 'activitable_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('activities');
    }
};