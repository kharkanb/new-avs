<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('main_equipments', function (Blueprint $table) {
            $table->dropUnique(['scada_code']);
        });
    }

    public function down(): void
    {
        Schema::table('main_equipments', function (Blueprint $table) {
            $table->string('scada_code', 4)->nullable()->unique()->change();
        });
    }
};