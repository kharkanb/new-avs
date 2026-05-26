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
        Schema::table('inspections', function (Blueprint $table) {
            // چک کن که ستون‌ها وجود دارن بعد حذف کن
            if (Schema::hasColumn('inspections', 'equipments_data')) {
                $table->dropColumn('equipments_data');
            }
            if (Schema::hasColumn('inspections', 'activities_data')) {
                $table->dropColumn('activities_data');
            }
            if (Schema::hasColumn('inspections', 'consumables_data')) {
                $table->dropColumn('consumables_data');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('inspections', function (Blueprint $table) {
            $table->json('equipments_data')->nullable();
            $table->json('activities_data')->nullable();
            $table->json('consumables_data')->nullable();
        });
    }
};