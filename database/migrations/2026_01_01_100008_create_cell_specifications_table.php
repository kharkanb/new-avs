<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cell_specifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('main_equipment_id')->constrained()->cascadeOnDelete();
            $table->string('cell_name', 100); // نام سلول
            $table->text('description')->nullable();
            $table->timestamps();
            
            $table->unique(['main_equipment_id', 'cell_name']);
            $table->index('main_equipment_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cell_specifications');
    }
};