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
        Schema::table('main_equipments', function (Blueprint $table) {
            // اضافه کردن ستون‌های JSON (اگه وجود ندارن)
            if (!Schema::hasColumn('main_equipments', 'equipments_data')) {
                $table->json('equipments_data')->nullable();
            }
            if (!Schema::hasColumn('main_equipments', 'activities_data')) {
                $table->json('activities_data')->nullable();
            }
            if (!Schema::hasColumn('main_equipments', 'consumables_data')) {
                $table->json('consumables_data')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('main_equipments', function (Blueprint $table) {
            // حذف ستون‌ها (اگه وجود دارن)
            $columns = ['equipments_data', 'activities_data', 'consumables_data'];
            foreach ($columns as $column) {
                if (Schema::hasColumn('main_equipments', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};