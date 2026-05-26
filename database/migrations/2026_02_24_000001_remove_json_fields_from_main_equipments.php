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
            // بررسی وجود هر کدوم از فیلدها قبل از حذف
            $columns = [
                'feeders_data',
                'location_data',
                'communication_data',
                'checklist_data',
                'activities_data',
                'consumables_data',
                'photos_data',
                'cell_specs',
                'tabs_validated'
            ];
            
            foreach ($columns as $column) {
                if (Schema::hasColumn('main_equipments', $column)) {
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
        Schema::table('main_equipments', function (Blueprint $table) {
            // برگردوندن فیلدها در صورت رول‌بک
            $table->json('feeders_data')->nullable()->comment('داده‌های فیدرها به صورت JSON');
            $table->json('location_data')->nullable()->comment('داده‌های موقعیت جغرافیایی به صورت JSON');
            $table->json('communication_data')->nullable()->comment('داده‌های ارتباطی به صورت JSON');
            $table->json('checklist_data')->nullable()->comment('داده‌های چک‌لیست به صورت JSON');
            $table->json('activities_data')->nullable()->comment('داده‌های فعالیت‌ها به صورت JSON');
            $table->json('consumables_data')->nullable()->comment('داده‌های مصارف به صورت JSON');
            $table->json('photos_data')->nullable()->comment('داده‌های عکس‌ها به صورت JSON');
            $table->json('cell_specs')->nullable()->comment('مشخصات سلول‌ها به صورت JSON');
            $table->json('tabs_validated')->nullable()->comment('وضعیت تب‌های تایید شده به صورت JSON');
        });
    }
};