<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('inspections', function (Blueprint $table) {
            $table->id();
            $table->date('inspection_date');
            $table->time('daily_start_time')->nullable();
            $table->time('daily_end_time')->nullable();
            $table->string('contractor')->nullable();
            $table->decimal('contract_coefficient', 5, 2)->nullable();
            $table->string('contract_number')->nullable();
            $table->string('whatsapp_number')->nullable();
            $table->enum('status', ['draft', 'completed', 'archived'])->default('draft');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('inspections');
    }
};