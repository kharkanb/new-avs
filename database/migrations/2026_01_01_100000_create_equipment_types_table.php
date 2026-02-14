<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('equipment_types', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique(); // ریکلوزر، سکسیونر، سکشنالایزر، فالت دتکتور، پست دو سوتغذیه و ...
            $table->enum('category', ['main', 'cell'])->default('main'); // نوع تجهیز: اصلی یا سلولی
            $table->boolean('has_brand')->default(false); // آیا برند دارد؟ (تجهیزات سلولی بله، اصلی خیر)
            $table->boolean('has_height')->default(true);
            $table->enum('feeder_mode', ['single', 'dual'])->default('single'); // برای تجهیزات اصلی
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('equipment_types');
    }
};