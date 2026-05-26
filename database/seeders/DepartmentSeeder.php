<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Department;

class DepartmentSeeder extends Seeder
{
    public function run(): void
    {
        $departments = [
            ['name' => 'ستاد', 'city' => 'یزد', 'code' => 'HQ'],
            ['name' => 'امور یک', 'city' => 'یزد', 'code' => 'D1'],
            ['name' => 'امور دو', 'city' => 'یزد', 'code' => 'D2'],
            ['name' => 'امور سه', 'city' => 'یزد', 'code' => 'D3'],
            ['name' => 'زارچ', 'city' => 'زارچ', 'code' => 'ZAR'],
            ['name' => 'اشکذر', 'city' => 'اشکذر', 'code' => 'ASH'],
            ['name' => 'میبد', 'city' => 'میبد', 'code' => 'MEY'],
            ['name' => 'اردکان', 'city' => 'اردکان', 'code' => 'ARD'],
            ['name' => 'تفت', 'city' => 'تفت', 'code' => 'TAF'],
            ['name' => 'مهریز', 'city' => 'مهریز', 'code' => 'MEH'],
            ['name' => 'نیر', 'city' => 'نیر', 'code' => 'NIR'],
            ['name' => 'هرات', 'city' => 'هرات', 'code' => 'HAR'],
            ['name' => 'مروست', 'city' => 'مروست', 'code' => 'MAR'],
            ['name' => 'ابرکوه', 'city' => 'ابرکوه', 'code' => 'ABR'],
            ['name' => 'بافق', 'city' => 'بافق', 'code' => 'BAF'],
            ['name' => 'بهاباد', 'city' => 'بهاباد', 'code' => 'BAH'],
        ];

        foreach ($departments as $dept) {
            Department::create($dept);
        }
    }
}