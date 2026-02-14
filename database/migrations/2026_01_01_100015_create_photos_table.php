<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('photos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('main_equipment_id')->constrained()->cascadeOnDelete(); // ✅ این خط رو اضافه کن
            $table->string('path');
            $table->string('caption')->nullable();
            $table->integer('sort_order')->default(0);
            $table->timestamps();
            
            $table->index('main_equipment_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('photos');
    }
};