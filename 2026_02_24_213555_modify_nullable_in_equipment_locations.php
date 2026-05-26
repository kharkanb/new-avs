<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('equipment_locations', function (Blueprint $table) {
            // تغییر فیلدها به nullable
            $table->decimal('cabinet_initial_height', 5, 2)->nullable()->change();
            $table->decimal('cabinet_final_height', 5, 2)->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('equipment_locations', function (Blueprint $table) {
            // برگرداندن به حالت قبلی (not nullable)
            $table->decimal('cabinet_initial_height', 5, 2)->nullable(false)->change();
            $table->decimal('cabinet_final_height', 5, 2)->nullable(false)->change();
        });
    }
};