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
            // اضافه کردن ستون‌های JSON (اگه وجود ندارن)
            if (!Schema::hasColumn('main_equipments', 'equipments_data')) {
                $table->json('equipments_data')->nullable();
            }
            if (!Schema::hasColumn('main_equipments', 'activities_data')) {
                $table->json('activities_data')->nullable();
            }
            if (!Schema::hasColumn('main_equipments', 'consumables_data')) {
                $table->json('consumables_data')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('main_equipments', function (Blueprint $table) {
            // حذف ستون‌ها (اگه وجود دارن)
            $columns = ['equipments_data', 'activities_data', 'consumables_data'];
            foreach ($columns as $column) {
                if (Schema::hasColumn('main_equipments', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MainEquipment extends Model
{
    protected $table = 'main_equipments';
    
    protected $fillable = [
        'inspection_id',
        'main_equipment_type_id',
        'scada_code',
        'post_id',
        'latitude',
        'longitude',
        'height',
        'installation_type',
        'feeders',
        'department_data',
        'location_data',
        'communication_data',
        'checklist_data',
        'activities_data',
        'consumables_data',
        'photos_data',
        'cell_specs',
        'tabs_validated'
    ];
    
    protected $casts = [
        'feeders' => 'array',
        'department_data' => 'array',
        'location_data' => 'array',
        'communication_data' => 'array',
        'checklist_data' => 'array',
        'activities_data' => 'array',
        'consumables_data' => 'array',
        'photos_data' => 'array',
        'cell_specs' => 'array',
        'tabs_validated' => 'array'
    ];

    // روابط
    public function inspection()
    {
        return $this->belongsTo(Inspection::class);
    }

    public function type()
    {
        return $this->belongsTo(MainEquipmentType::class, 'main_equipment_type_id');
    }

    public function post()
    {
        return $this->belongsTo(Post::class);
    }
}
// ❌ اینجا نباید کلاس دیگری تعریف شود

