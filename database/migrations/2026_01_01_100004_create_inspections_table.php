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
            $table->string('inspection_date');
            $table->time('daily_start_time')->nullable();
            $table->time('daily_end_time')->nullable();
            $table->string('contractor');
            $table->decimal('contract_coefficient', 5, 2);
            $table->string('contract_number')->nullable();
            $table->string('whatsapp_number')->nullable();
            $table->json('equipments_data')->nullable();
            $table->json('activities_data')->nullable();
            $table->json('consumables_data')->nullable();
            $table->string('status')->default('draft');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('inspections');
    }
};