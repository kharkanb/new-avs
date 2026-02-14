<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cell_equipment_types', function (Blueprint $table) {
            $table->id();
            $table->string('name', 150)->unique(); // دژنکتور، رله، کنترل‌پنل، مودم و ...
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cell_equipment_types');
    }
};