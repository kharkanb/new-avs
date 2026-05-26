<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CompleteBrandsSeeder extends Seeder
{
    public function run(): void
    {
        $this->command->info('🚀 در حال ایجاد برندها بر اساس نوع تجهیزات...');

        // ============================================
        // برندهای تجهیزات اصلی (Main Equipment)
        // ============================================
        $this->command->info('1️⃣ ایجاد برندهای تجهیزات اصلی...');

        $mainEquipmentBrands = [
            "ریکلوزر" => ["ABB", "Schneider", "Siemens", "NOJA", "Tavrida", "Eaton", "GE", "Hyundai", "LS", "Entec", "سایر"],
            "سکسیونر" => ["ABB", "Schneider", "Siemens", "NOJA", "Tavrida", "Eaton", "GE", "Hyundai", "LS", "Entec", "سایر"],
            "سکشنالایزر" => ["ABB", "Schneider", "Siemens", "NOJA", "Tavrida", "Eaton", "GE", "Hyundai", "LS", "Entec", "سایر"],
            "فالت دتکتور" => ["ABB", "Schneider", "Siemens", "NOJA", "Tavrida", "Eaton", "GE", "Hyundai", "LS", "Entec", "سایر"],
        ];

        foreach ($mainEquipmentBrands as $equipmentType => $brands) {
            foreach ($brands as $brand) {
                DB::table('brands')->updateOrInsert(
                    ['name' => $brand, 'equipment_type' => $equipmentType],
                    ['created_at' => now(), 'updated_at' => now()]
                );
                $this->command->info("   ✅ {$brand} - {$equipmentType}");
            }
        }

        // ============================================
        // برندهای تجهیزات سلولی (Cell Equipment)
        // ============================================
        $this->command->info('2️⃣ ایجاد برندهای تجهیزات سلولی...');

        $cellEquipmentBrands = [
            "دژنکتور" => ["ABB", "Schneider", "Siemens", "GE", "Hyundai", "Eaton", "Mitsubishi", "Toshiba", "LS", "Entec", "سایر"],
            "سکسیونر" => ["ABB", "Schneider", "Siemens", "GE", "Hyundai", "Eaton", "Mitsubishi", "Toshiba", "LS", "Entec", "سایر"],
            "کلید بار" => ["ABB", "Schneider", "Siemens", "GE", "Hyundai", "Eaton", "Mitsubishi", "Toshiba", "LS", "Entec", "سایر"],
            "رله/RTU" => ["ABB", "Schneider", "Siemens", "SICAM", "FARAB", "ARVAND", "PNC", "حافظ", "سایر"],
            "کنترل‌پنل" => ["ABB", "Schneider", "Siemens", "GE", "Hyundai", "Eaton", "Mitsubishi", "Toshiba", "LS", "Entec", "سایر"],
            "پاور" => ["ABB", "Schneider", "Siemens", "GE", "Hyundai", "Eaton", "Mitsubishi", "Toshiba", "LS", "Entec", "سایر"],
            "باتری" => ["Yuasa", "CSB", "Vision", "Rocket", "Panasonic", "Samsung", "LG", "Exide", "Varta", "Tudor", "سایر"],
            "فیوز" => ["ABB", "Schneider", "Siemens", "GE", "Hyundai", "Eaton", "Mitsubishi", "Toshiba", "LS", "Entec", "سایر"],
            "مودم" => ["Fortak", "FSK", "Quadric", "جهان ویستا", "TELTONIKA", "HUAWEI", "D-LINK", "Mikrotik", "Sierra", "Cradlepoint", "Digi", "سایر"],
            "سوئیچ شبکه" => ["Cisco", "HP", "D-Link", "TP-Link", "Netgear", "Juniper", "Huawei", "Zyxel", "MikroTik", "Ubiquiti", "سایر"],
            "رله حفاظتی" => ["ABB", "Schneider", "Siemens", "GE", "Hyundai", "Eaton", "Mitsubishi", "Toshiba", "LS", "Entec", "سایر"],
            "ترانس جریان" => ["ABB", "Schneider", "Siemens", "GE", "Hyundai", "Eaton", "Mitsubishi", "Toshiba", "LS", "Entec", "سایر"],
            "ترانس ولتاژ" => ["ABB", "Schneider", "Siemens", "GE", "Hyundai", "Eaton", "Mitsubishi", "Toshiba", "LS", "Entec", "سایر"],
            "برد مخابراتی" => ["Fortak", "FSK", "Quadric", "جهان ویستا", "Huawei", "ZTE", "سایر"],
            "شارژر باتری" => ["ABB", "Schneider", "Siemens", "GE", "سایر"],
        ];

        foreach ($cellEquipmentBrands as $equipmentType => $brands) {
            foreach ($brands as $brand) {
                DB::table('brands')->updateOrInsert(
                    ['name' => $brand, 'equipment_type' => $equipmentType],
                    ['created_at' => now(), 'updated_at' => now()]
                );
                $this->command->info("   ✅ {$brand} - {$equipmentType}");
            }
        }

        // ============================================
        // برندهای عمومی (بدون نوع تجهیز مشخص)
        // ============================================
        $this->command->info('3️⃣ ایجاد برندهای عمومی...');

        $generalBrands = [
            "سایمونز", "فنیکس", "وگا", "دلتا", "وین‌تک", "مکسون", "اپتوس", "راکول", "هانیول", "یوکوگاوا"
        ];

        foreach ($generalBrands as $brand) {
            DB::table('brands')->updateOrInsert(
                ['name' => $brand],
                ['equipment_type' => 'عمومی', 'created_at' => now(), 'updated_at' => now()]
            );
            $this->command->info("   ✅ {$brand} - عمومی");
        }

        $this->command->info("\n📊 نتیجه نهایی:");
        $this->command->info("   ✅ کل برندها: " . DB::table('brands')->count());
        $this->command->info("\n✅ همه برندها با موفقیت ایجاد شدند!");
    }
}