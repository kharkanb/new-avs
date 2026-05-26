<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Inspection;
use App\Models\MainEquipment;
use App\Models\MainEquipmentType;
use App\Models\EquipmentFeeder;
use App\Models\EquipmentLocation;
use App\Models\EquipmentCommunication;
use App\Models\EquipmentChecklist;
use App\Models\EquipmentActivity;
use App\Models\EquipmentConsumable;
use App\Models\Post;
use Illuminate\Support\Facades\Hash;

class DummyDataSeeder extends Seeder
{
    public function run()
    {
        // ========== ۱. ایجاد کاربران متنوع ==========
        $users = [
            ['name' => 'مدیر سیستم', 'email' => 'admin@example.com', 'role' => 'admin'],
            ['name' => 'ناظر فنی', 'email' => 'supervisor@example.com', 'role' => 'supervisor'],
            ['name' => 'کارشناس یک', 'email' => 'tech1@example.com', 'role' => 'user'],
            ['name' => 'کارشناس دو', 'email' => 'tech2@example.com', 'role' => 'user'],
            ['name' => 'کارشناس سه', 'email' => 'tech3@example.com', 'role' => 'user'],
        ];

        foreach ($users as $user) {
            User::firstOrCreate(
                ['email' => $user['email']],
                [
                    'name' => $user['name'],
                    'password' => Hash::make('123456'),
                    'role' => $user['role']
                ]
            );
        }

        // ========== ۲. ایجاد پست‌ها (Posts) ==========
        $posts = ['شرق', 'غرب', 'شمال', 'جنوب', 'آزادگان', 'پاکنژاد', 'امام شهر', 'چرخاب'];
        foreach ($posts as $postName) {
            Post::firstOrCreate(['name' => $postName]);
        }

        // ========== ۳. ایجاد انواع تجهیزات ==========
        $equipmentTypes = [
            ['name' => 'ریکلوزر', 'feeder_mode' => 'single', 'has_brand' => true, 'has_height' => true],
            ['name' => 'سکسیونر', 'feeder_mode' => 'dual', 'has_brand' => true, 'has_height' => true],
            ['name' => 'سکشنالایزر', 'feeder_mode' => 'dual', 'has_brand' => true, 'has_height' => true],
            ['name' => 'فالت دتکتور', 'feeder_mode' => 'single', 'has_brand' => true, 'has_height' => false],
            ['name' => 'پست دو سو تغذیه (مشترک حساس)', 'feeder_mode' => 'dual', 'has_brand' => false, 'has_height' => false],
            ['name' => 'پست دو سو تغذیه (بیمارستانی)', 'feeder_mode' => 'dual', 'has_brand' => false, 'has_height' => false],
            ['name' => 'مشترک ولتاژ اولیه', 'feeder_mode' => 'single', 'has_brand' => false, 'has_height' => false],
        ];

        foreach ($equipmentTypes as $type) {
            MainEquipmentType::firstOrCreate(['name' => $type['name']], $type);
        }

        // ========== ۴. ایجاد ۱۰۰ بازدید آزمایشی ==========
        $allUsers = User::all();
        $allPosts = Post::all();
        $allTypes = MainEquipmentType::all();
        
        $statuses = ['completed', 'draft', 'archived'];
        $contractors = ['سام سرمد کویر', 'البرز', 'رادین', 'مهرگان', 'آریا', 'پایا'];
        $simcardTypes = ['ایرانسل', 'همراه اول', 'رایتل', 'شاتل'];
        $antennaStatus = ['داخل تابلو', 'خارج تابلو', 'ندارد'];
        $signalStatus = ['خوب', 'ضعیف'];
        $modemPowers = ['پنل', 'باتری'];

        for ($i = 0; $i < 100; $i++) {
            // تاریخ‌های تصادفی در ۶ ماه گذشته
            $inspectionDate = now()->subMonths(rand(0, 5))->subDays(rand(0, 30));
            
            // ایجاد بازدید
            $inspection = Inspection::create([
                'inspection_date' => $inspectionDate->format('Y-m-d'),
                'daily_start_time' => rand(8, 10) . ':' . rand(0, 59),
                'daily_end_time' => rand(11, 15) . ':' . rand(0, 59),
                'contractor' => $contractors[array_rand($contractors)],
                'contract_coefficient' => rand(180, 250) / 100,
                'contract_number' => 'CONTRACT-' . rand(1000, 9999),
                'whatsapp_number' => '9891' . rand(1111111, 9999999),
                'user_id' => $allUsers->random()->id,
                'status' => $statuses[array_rand($statuses)],
                'created_at' => $inspectionDate,
                'updated_at' => $inspectionDate,
            ]);

            // هر بازدید بین ۱ تا ۴ تجهیز داره
            $equipmentCount = rand(1, 4);
            
            for ($j = 0; $j < $equipmentCount; $j++) {
                $type = $allTypes->random();
                $post = $allPosts->random();
                
                // ایجاد تجهیز اصلی
                $equipment = MainEquipment::create([
                    'inspection_id' => $inspection->id,
                    'main_equipment_type_id' => $type->id,
                    'scada_code' => rand(1000, 9999),
                    'installation_type' => rand(0, 1) ? 'بین‌فیدری' : 'مانوری',
                    'created_at' => $inspectionDate,
                    'updated_at' => $inspectionDate,
                ]);

                // اضافه کردن فیدر
                EquipmentFeeder::create([
                    'main_equipment_id' => $equipment->id,
                    'post' => $post->name,
                    'feeder' => rand(401, 420) . ' ' . $post->name,
                    'created_at' => $inspectionDate,
                    'updated_at' => $inspectionDate,
                ]);

                // اضافه کردن موقعیت
                EquipmentLocation::create([
                    'main_equipment_id' => $equipment->id,
                    'latitude' => 31.5 + (rand(0, 100) / 100),
                    'longitude' => 54.1 + (rand(0, 100) / 100),
                    'address' => 'آدرس نمونه ' . rand(1, 100),
                    'cabinet_initial_height' => rand(1, 5),
                    'cabinet_final_height' => rand(1, 5),
                    'created_at' => $inspectionDate,
                    'updated_at' => $inspectionDate,
                ]);

                // اضافه کردن اطلاعات ارتباطی
                EquipmentCommunication::create([
                    'main_equipment_id' => $equipment->id,
                    'simcard_type' => $simcardTypes[array_rand($simcardTypes)],
                    'simcard_number' => '091' . rand(1111111, 9999999),
                    'simcard_ip' => rand(10, 200) . '.' . rand(1, 254) . '.' . rand(1, 254) . '.' . rand(1, 254),
                    'antenna_status' => $antennaStatus[array_rand($antennaStatus)],
                    'signal_status' => $signalStatus[array_rand($signalStatus)],
                    'modem_power' => $modemPowers[array_rand($modemPowers)],
                    'reset_possible' => (bool)rand(0, 1),
                    'created_at' => $inspectionDate,
                    'updated_at' => $inspectionDate,
                ]);

                // اضافه کردن چک‌لیست
                $checklistItems = [
                    'وضعیت برق ورودی',
                    'وضعیت فیوزها',
                    'وضعیت باتری',
                    'وضعیت سیم‌بندی',
                    'وضعیت نصب مودم',
                ];
                
                foreach ($checklistItems as $index => $item) {
                    EquipmentChecklist::create([
                        'main_equipment_id' => $equipment->id,
                        'item' => $item,
                        'status' => rand(0, 1) ? 'OK' : 'Not OK',
                        'description' => rand(0, 1) ? 'توضیحات نمونه' : null,
                        'sort_order' => $index,
                        'created_at' => $inspectionDate,
                        'updated_at' => $inspectionDate,
                    ]);
                }

                // اضافه کردن فعالیت
                $activityCodes = ['911377-1', '911378-1', '911379-1', '911380-1', '911381-1'];
                $activityTitles = [
                    'نصب مودم',
                    'نصب RTU',
                    'تعویض باتری',
                    'نصب پاور',
                    'تعویض فیوز',
                ];
                $prices = [1800000, 1800000, 1500000, 1800000, 700000];
                
                $activityCount = rand(1, 3);
                for ($k = 0; $k < $activityCount; $k++) {
                    $idx = array_rand($activityCodes);
                    $quantity = rand(1, 3);
                    EquipmentActivity::create([
                        'main_equipment_id' => $equipment->id,
                        'code' => $activityCodes[$idx],
                        'title' => $activityTitles[$idx],
                        'unit' => 'مورد',
                        'unit_price' => $prices[$idx],
                        'quantity' => $quantity,
                        'total' => $prices[$idx] * $quantity,
                        'created_at' => $inspectionDate,
                        'updated_at' => $inspectionDate,
                    ]);
                }

                // اضافه کردن مصارف
                $consumableNames = ['مودم', 'RTU', 'آنتن', 'باتری', 'فیوز'];
                $consumableCount = rand(0, 3);
                for ($k = 0; $k < $consumableCount; $k++) {
                    EquipmentConsumable::create([
                        'main_equipment_id' => $equipment->id,
                        'name' => $consumableNames[array_rand($consumableNames)],
                        'quantity' => rand(1, 5),
                        'unit' => 'عدد',
                        'description' => rand(0, 1) ? 'توضیحات مصرف' : null,
                        'created_at' => $inspectionDate,
                        'updated_at' => $inspectionDate,
                    ]);
                }
            }
        }

        $this->command->info('✅ ۱۰۰ بازدید آزمایشی با موفقیت ایجاد شد!');
    }
}