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
<<<<<<< HEAD
            // قبل از حذف، چک کن که ستون‌ها وجود دارن
            $columns = ['equipments_data', 'activities_data', 'consumables_data'];
            
            foreach ($columns as $column) {
                if (Schema::hasColumn('inspections', $column)) {
                    $table->dropColumn($column);
                }
            }
=======
            $table->dropColumn(['equipments_data', 'activities_data', 'consumables_data']);
>>>>>>> 524cace2901cfcda4f022b89d64c22cc653187c1
        });
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
=======
            $table->json('equipments_data')->nullable()->after('status');
            $table->json('activities_data')->nullable()->after('equipments_data');
            $table->json('consumables_data')->nullable()->after('activities_data');
>>>>>>> 524cace2901cfcda4f022b89d64c22cc653187c1
        });
    }
};