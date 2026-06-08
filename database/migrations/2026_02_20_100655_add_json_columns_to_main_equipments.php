<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

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
};