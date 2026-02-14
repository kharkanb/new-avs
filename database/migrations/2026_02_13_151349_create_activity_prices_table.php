<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('activity_prices', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->string('title');
            $table->string('unit', 50)->nullable();
            $table->bigInteger('unit_price'); // قیمت به ریال یا تومان
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('activity_prices');
    }
};