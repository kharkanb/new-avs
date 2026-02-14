<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('main_equipments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('inspection_id')->constrained('inspections')->onDelete('cascade');
            $table->foreignId('main_equipment_type_id')->constrained('main_equipment_types')->cascadeOnDelete();
            $table->string('scada_code', 4)->nullable()->unique();
            $table->foreignId('post_id')->constrained()->cascadeOnDelete();
            
            // موقعیت جغرافیایی (یک موقعیت)
                        $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable(); // اینجا اصلاح شد: 7')->nullable() به 7)->nullable()
            
            $table->integer('height')->nullable();
            $table->string('installation_type', 50)->nullable(); // هوایی، زمینی
            $table->timestamps();
            
            $table->index('inspection_id');
            $table->index('main_equipment_type_id');
            $table->index('post_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('main_equipments');
    }
};