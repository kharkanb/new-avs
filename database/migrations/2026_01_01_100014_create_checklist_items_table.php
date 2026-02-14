<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('checklist_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('main_equipment_id')->constrained()->cascadeOnDelete();
            $table->foreignId('checklist_template_item_id')->constrained('checklist_template_items')->cascadeOnDelete();
            $table->enum('status', ['OK', 'Not OK', 'Not Checked'])->default('Not Checked');
            $table->text('description')->nullable();
            $table->timestamps();
            
            $table->unique(['main_equipment_id', 'checklist_template_item_id'], 'equipment_checklist_unique');
            $table->index('main_equipment_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('checklist_items');
    }
};