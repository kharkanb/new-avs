<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class FixEmptyTablesSeeder extends Seeder
{
    public function run(): void
    {
        $this->command->info('🔧 در حال رفع جدول‌های خالی...');

        // ============================================
        // بررسی و پر کردن انواع تجهیزات اصلی
        // ============================================
        if (DB::table('main_equipment_types')->count() == 0) {
            $this->command->info('📌 پر کردن main_equipment_types...');
            $types = ['ریکلوزر', 'سکسیونر', 'سکشنالایزر', 'فالت دتکتور', 'پست دو سو تغذیه (مشترک حساس)', 'پست دو سو تغذیه (بیمارستانی)', 'مشترک ولتاژ اولیه'];
            foreach ($types as $type) {
                DB::table('main_equipment_types')->insert(['name' => $type, 'created_at' => now(), 'updated_at' => now()]);
            }
        }

        // ============================================
        // بررسی و پر کردن انواع تجهیزات سلولی
        // ============================================
        if (DB::table('cell_equipment_types')->count() == 0) {
            $this->command->info('📌 پر کردن cell_equipment_types...');
            $types = ['دژنکتور', 'سکسیونر', 'رله/RTU', 'کنترل پنل', 'پاور', 'باتری', 'فیوز', 'ترانس جریان', 'ترانس ولتاژ', 'مودم'];
            foreach ($types as $type) {
                DB::table('cell_equipment_types')->insert(['name' => $type, 'created_at' => now(), 'updated_at' => now()]);
            }
        }

        // ============================================
        // بررسی و پر کردن برندها
        // ============================================
        if (DB::table('brands')->count() == 0) {
            $this->command->info('📌 پر کردن brands...');
            $brands = [
                ['name' => 'ABB', 'equipment_type' => 'کلید قدرت'],
                ['name' => 'Schneider', 'equipment_type' => 'کلید قدرت'],
                ['name' => 'Siemens', 'equipment_type' => 'کلید قدرت'],
                ['name' => 'NOJA', 'equipment_type' => 'ریکلوزر'],
                ['name' => 'Tavrida', 'equipment_type' => 'ریکلوزر'],
                ['name' => 'Eaton', 'equipment_type' => 'کلید قدرت'],
                ['name' => 'Fortak', 'equipment_type' => 'مودم'],
                ['name' => 'FSK', 'equipment_type' => 'مودم'],
                ['name' => 'Quadric', 'equipment_type' => 'مودم'],
                ['name' => 'جهان ویستا', 'equipment_type' => 'مودم'],
            ];
            foreach ($brands as $brand) {
                DB::table('brands')->insert(['name' => $brand['name'], 'equipment_type' => $brand['equipment_type'], 'created_at' => now(), 'updated_at' => now()]);
            }
        }

        // ============================================
        // بررسی و پر کردن فهرست بها
        // ============================================
        if (DB::table('activity_prices')->count() == 0) {
            $this->command->info('📌 پر کردن activity_prices...');
            $prices = [
                ['code' => '911377-1', 'title' => 'نصب یا تعویض مودم', 'unit' => 'مورد', 'price' => 1800000],
                ['code' => '911378-1', 'title' => 'نصب یا تعویض پنل کنترل (RTU)', 'unit' => 'مورد', 'price' => 1800000],
                ['code' => '911379-1', 'title' => 'نصب یا تعویض باطری', 'unit' => 'مجموعه', 'price' => 1500000],
                ['code' => '911380-1', 'title' => 'نصب یا تعویض پاور تغذیه', 'unit' => 'دستگاه', 'price' => 1800000],
                ['code' => '911381-1', 'title' => 'نصب یا تعویض فیوز مینیاتوری', 'unit' => 'مورد', 'price' => 700000],
            ];
            foreach ($prices as $price) {
                DB::table('activity_prices')->insert($price + ['created_at' => now(), 'updated_at' => now()]);
            }
        }

        // ============================================
        // بررسی و پر کردن چک‌لیست‌ها
        // ============================================
        if (DB::table('checklist_items')->count() == 0) {
            $this->command->info('📌 پر کردن checklist_items...');
            $items = [
                'وضعیت نصب فیزیکی', 'سلامت بدنه و عدم نفوذ رطوبت', 'وضعیت منبع تغذیه',
                'وضعیت نشانگرهای LED', 'وضعیت ارتباط مخابراتی', 'وضعیت آنتن یا ماژول ارتباطی'
            ];
            foreach ($items as $item) {
                DB::table('checklist_items')->insert(['item' => $item, 'created_at' => now(), 'updated_at' => now()]);
            }
        }

        // ============================================
        // بررسی و پر کردن انواع سیم‌کارت
        // ============================================
        if (DB::table('simcard_types')->count() == 0) {
            $this->command->info('📌 پر کردن simcard_types...');
            $types = ['ایرانسل', 'همراه اول', 'رایتل', 'شاتل', 'سایر'];
            foreach ($types as $type) {
                DB::table('simcard_types')->insert(['name' => $type, 'is_active' => 1, 'created_at' => now(), 'updated_at' => now()]);
            }
        }

        // ============================================
        // بررسی و پر کردن اقلام مصرفی
        // ============================================
        if (DB::table('consumable_items')->count() == 0) {
            $this->command->info('📌 پر کردن consumable_items...');
            $items = [
                ['name' => 'مودم', 'unit' => 'عدد'],
                ['name' => 'RTU', 'unit' => 'عدد'],
                ['name' => 'آنتن', 'unit' => 'عدد'],
                ['name' => 'باتری', 'unit' => 'عدد'],
                ['name' => 'فیوز مینیاتوری', 'unit' => 'عدد'],
            ];
            foreach ($items as $item) {
                DB::table('consumable_items')->insert($item + ['created_at' => now(), 'updated_at' => now()]);
            }
        }

        $this->command->info("\n✅ جدول‌های خالی با موفقیت پر شدند!");
        
        // نمایش آمار نهایی
        $this->command->info("\n📊 آمار نهایی:");
        $this->command->info("   main_equipment_types: " . DB::table('main_equipment_types')->count());
        $this->command->info("   cell_equipment_types: " . DB::table('cell_equipment_types')->count());
        $this->command->info("   brands: " . DB::table('brands')->count());
        $this->command->info("   activity_prices: " . DB::table('activity_prices')->count());
        $this->command->info("   checklist_items: " . DB::table('checklist_items')->count());
        $this->command->info("   simcard_types: " . DB::table('simcard_types')->count());
        $this->command->info("   consumable_items: " . DB::table('consumable_items')->count());
    }
}