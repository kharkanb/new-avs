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
            $table->dropColumn(['equipments_data', 'activities_data', 'consumables_data']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('inspections', function (Blueprint $table) {
            $table->json('equipments_data')->nullable()->after('status');
            $table->json('activities_data')->nullable()->after('equipments_data');
            $table->json('consumables_data')->nullable()->after('activities_data');
        });
    }
};