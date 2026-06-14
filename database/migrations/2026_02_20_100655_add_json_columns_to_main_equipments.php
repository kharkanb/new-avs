<?php

<<<<<<< HEAD

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
=======
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
>>>>>>> e82339cac376f551a8a66da0035c095e88a5df9d

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('main_equipments', function (Blueprint $table) {
            foreach ($this->jsonColumns() as $column) {
                if (!Schema::hasColumn('main_equipments', $column)) {
                    $table->json($column)->nullable();
                }
            }
        });
    }

    public function down(): void
    {
        $columns = array_values(array_filter(
            $this->jsonColumns(),
            fn (string $column): bool => Schema::hasColumn('main_equipments', $column)
        ));

        if ($columns === []) {
            return;
        }

        Schema::table('main_equipments', function (Blueprint $table) use ($columns) {
            $table->dropColumn($columns);
        });
    }

    private function jsonColumns(): array
    {
        return [
            'feeders',
            'department_data',
            'location_data',
            'communication_data',
            'checklist_data',
            'activities_data',
            'consumables_data',
            'photos_data',
            'cell_specs',
            'tabs_validated',
        ];
    }
<<<<<<< HEAD
}
// ❌ اینجا نباید کلاس دیگری تعریف شود

=======
};
>>>>>>> e82339cac376f551a8a66da0035c095e88a5df9d
