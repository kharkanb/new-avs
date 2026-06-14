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

    public function up(): void
    {
        $columns = array_values(array_filter(
            ['equipments_data', 'activities_data', 'consumables_data'],
            fn (string $column): bool => Schema::hasColumn('inspections', $column)
        ));

        if ($columns !== []) {
            Schema::table('inspections', function (Blueprint $table) use ($columns) {
                $table->dropColumn($columns);
            });
        }
    }


    public function down(): void
    {
        Schema::table('inspections', function (Blueprint $table) {
            if (!Schema::hasColumn('inspections', 'equipments_data')) {
                $table->json('equipments_data')->nullable();
            }
            if (!Schema::hasColumn('inspections', 'activities_data')) {
                $table->json('activities_data')->nullable();
            }
            if (!Schema::hasColumn('inspections', 'consumables_data')) {
                $table->json('consumables_data')->nullable();
            }
        });
    }
};