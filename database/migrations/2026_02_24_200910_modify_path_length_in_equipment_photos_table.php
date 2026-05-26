<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

public function up(): void
{
    Schema::table('equipment_photos', function (Blueprint $table) {
        $table->string('path', 5000)->change();
    });
} 

    public function down(): void
    {
        Schema::table('equipment_photos', function (Blueprint $table) {
            // برگردوندن به حالت قبلی (در صورتی که رول‌بک نیاز باشه)
            $table->string('path', 500)->change();
        });
    }
};