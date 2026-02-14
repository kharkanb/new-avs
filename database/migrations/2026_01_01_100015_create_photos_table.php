<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('photos', function (Blueprint $table) {
            $table->id();
            $table->morphs('photoble'); // این خط خودش ایندکس را ایجاد می‌کند
            $table->string('path');
            $table->string('caption')->nullable();
            $table->integer('sort_order')->default(0);
            $table->timestamps();
            
            // این خط را حذف کنید
            // $table->index(['photoble_type', 'photoble_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('photos');
    }
};