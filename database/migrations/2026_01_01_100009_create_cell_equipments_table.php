<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cell_equipments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cell_id')->constrained('cell_specifications')->cascadeOnDelete();
            $table->foreignId('cell_equipment_type_id')->constrained('cell_equipment_types')->cascadeOnDelete();
            $table->foreignId('brand_id')->nullable()->constrained()->nullOnDelete();
            
            $table->string('other_brand', 100)->nullable(); // اگر برند در لیست نبود
            $table->string('model', 100)->nullable();
            $table->string('serial_number', 100)->nullable();
            $table->year('manufacture_year')->nullable();
            $table->string('manual_name', 200)->nullable(); // نام دفترچه راهنما
            $table->text('description')->nullable();
            $table->timestamps();
            
            $table->index('cell_id');
            $table->index('cell_equipment_type_id');
            $table->unique(['cell_id', 'cell_equipment_type_id'], 'cell_equipment_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cell_equipments');
    }
};