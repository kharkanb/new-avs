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
<<<<<<< HEAD
        Schema::table('inspections', function (Blueprint $table) {

            // قبل از حذف، چک کن که ستون‌ها وجود دارن
            $columns = ['equipments_data', 'activities_data', 'consumables_data'];
            
            foreach ($columns as $column) {
                if (Schema::hasColumn('inspections', $column)) {
                    $table->dropColumn($column);
                }
            }

            $table->dropColumn(['equipments_data', 'activities_data', 'consumables_data']);

        });
=======
        $columns = array_values(array_filter(
            ['equipments_data', 'activities_data', 'consumables_data'],
            fn (string $column): bool => Schema::hasColumn('inspections', $column)
        ));

        if ($columns !== []) {
            Schema::table('inspections', function (Blueprint $table) use ($columns) {
                $table->dropColumn($columns);
            });
        }
>>>>>>> e82339cac376f551a8a66da0035c095e88a5df9d
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('inspections', function (Blueprint $table) {
<<<<<<< HEAD

            // اگه خواستی برگردونی، دوباره اضافه کن
            $table->json('equipments_data')->nullable();
            $table->json('activities_data')->nullable();
            $table->json('consumables_data')->nullable();
            $table->json('equipments_data')->nullable()->after('status');
            $table->json('activities_data')->nullable()->after('equipments_data');
            $table->json('consumables_data')->nullable()->after('activities_data');

=======
            if (!Schema::hasColumn('inspections', 'equipments_data')) {
                $table->json('equipments_data')->nullable()->after('status');
            }
            if (!Schema::hasColumn('inspections', 'activities_data')) {
                $table->json('activities_data')->nullable()->after('equipments_data');
            }
            if (!Schema::hasColumn('inspections', 'consumables_data')) {
                $table->json('consumables_data')->nullable()->after('activities_data');
            }
>>>>>>> e82339cac376f551a8a66da0035c095e88a5df9d
        });
    }
};