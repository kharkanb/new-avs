<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('consumables', function (Blueprint $table) {
            $table->id();
            $table->foreignId('main_equipment_id')->constrained()->cascadeOnDelete(); // ✅ این خط رو اضافه کن

            $table->morphs('consumableable'); // این خط خودش ایندکس را ایجاد می‌کند
            $table->string('name', 200);
            $table->string('other_name', 200)->nullable();
            $table->integer('quantity')->default(1);
            $table->string('unit', 50)->nullable();
            $table->text('description')->nullable();
            $table->decimal('price', 10, 2);
            $table->timestamps();
            
            // این خط را حذف کنید (ایندکس تکراری)
            // $table->index(['consumableable_type', 'consumableable_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('consumables');
    }
};