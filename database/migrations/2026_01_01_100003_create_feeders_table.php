<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('feeders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('post_id')->constrained()->cascadeOnDelete();
            $table->string('name', 150);
            $table->string('feeder_number', 50)->nullable();
            $table->timestamps();
            
            $table->unique(['post_id', 'name']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('feeders');
    }
};