<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('main_equipment_types', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique(); // ریکلوزر، سکسیونر، سکشنالایزر، فالت دتکتور، پست دو سوتغذیه
            $table->enum('feeder_mode', ['single', 'dual'])->default('single'); // برای پست‌های دو سوتغذیه 'dual' است
            $table->boolean('has_cells')->default(false); // آیا سلول دارد؟ (پست‌ها دارند)
            $table->boolean('has_brand')->default(false); // برند ندارد
            $table->boolean('has_height')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('main_equipment_types');
    }
};