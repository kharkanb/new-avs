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
            // قبل از حذف، چک کن که ستون‌ها وجود دارن
            $columns = ['equipments_data', 'activities_data', 'consumables_data'];
            
            foreach ($columns as $column) {
                if (Schema::hasColumn('inspections', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('inspections', function (Blueprint $table) {
            // اگه خواستی برگردونی، دوباره اضافه کن
            $table->json('equipments_data')->nullable();
            $table->json('activities_data')->nullable();
            $table->json('consumables_data')->nullable();
        });
    }
};