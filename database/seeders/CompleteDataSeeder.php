<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Inspection;
use App\Models\MainEquipment;
use App\Models\MainEquipmentType;
use App\Models\EquipmentActivity;
use App\Models\EquipmentChecklist;
use App\Models\EquipmentLocation;
use App\Models\EquipmentCommunication;
use App\Models\EquipmentConsumable;
use App\Models\EquipmentPhoto;
use App\Models\EquipmentFeeder;
use App\Models\Contractor;
use App\Models\Department;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Hekmatinasser\Verta\Verta;

class CompleteDataSeeder extends Seeder
{
    protected $contractors = [
        'سام سرمد کویر',
        'مانا نیرو'
    ];

    protected $equipmentTypes = [
        'ریکلوزر',
        'سکسیونر',
        'سکشنالایزر',
        'فالت دتکتور',
        'پست دو سو تغذیه (مشترک حساس)',
        'پست دو سو تغذیه (بیمارستانی)',
        'مشترک ولتاژ اولیه'
    ];

    protected $checklistItems = [
        'وضعیت نصب فیزیکی',
        'سلامت بدنه و عدم نفوذ رطوبت',
        'وضعیت منبع تغذیه',
        'وضعیت نشانگرهای LED',
        'وضعیت ارتباط مخابراتی',
        'وضعیت آنتن یا ماژول ارتباطی',
        'عدم وجود آلارم خطای داخلی',
        'وضعیت کابل‌کشی و اتصالات',
        'نظافت و تمیزی تجهیز',
        'وضعیت ارت تجهیزات',
        'وضعیت باتری',
        'صحت تنظیم پارامترهای ارتباطی'
    ];

    protected $activityCodes = [
        ['code' => '911377-1', 'title' => 'نصب یا تعویض مودم', 'price' => 1800000],
        ['code' => '911378-1', 'title' => 'نصب یا تعویض پنل کنترل (RTU)', 'price' => 1800000],
        ['code' => '911379-1', 'title' => 'نصب یا تعویض باطری', 'price' => 1500000],
        ['code' => '911380-1', 'title' => 'نصب یا تعویض پاور تغذیه', 'price' => 1800000],
        ['code' => '911381-1', 'title' => 'نصب یا تعویض فیوز مینیاتوری', 'price' => 700000],
        ['code' => '911382-1', 'title' => 'نصب یا تعویض فیوز فشنگی', 'price' => 500000],
        ['code' => '911383-1', 'title' => 'نصب، اصلاح و یا تعویض سیم‌کشی', 'price' => 1200000],
        ['code' => '911384-1', 'title' => 'نصب یا تعویض سیم کارت مودم', 'price' => 1200000],
        ['code' => '911385-1', 'title' => 'انجام تنظیمات و پیکربندی مودم', 'price' => 1500000],
        ['code' => '911386-1', 'title' => 'راه‌اندازی و تنظیمات RTU', 'price' => 1500000],
        ['code' => '911387-1', 'title' => 'تنظیمات تجهیز جدید', 'price' => 3500000],
        ['code' => '911391-1', 'title' => 'نظافت داخل تابلو فرمان', 'price' => 1500000],
        ['code' => '911402-1', 'title' => 'ایاب و ذهاب', 'price' => 50000]
    ];

    public function run(): void
    {
        $this->command->info('🚀 شروع ساخت دیتای کامل...');

        // ============================================
        // 1. ایجاد کاربران (7 کاربر)
        // ============================================
        $this->command->info('ایجاد کاربران...');
        $createdUsers = $this->createUsers();

        // ============================================
        // 2. ایجاد پیمانکاران (2 پیمانکار)
        // ============================================
        $this->command->info('ایجاد پیمانکاران...');
        $contractors = $this->createContractors();

        // ============================================
        // 3. ایجاد دپارتمان‌ها (16 دپارتمان)
        // ============================================
        $this->command->info('ایجاد دپارتمان‌ها...');
        $departments = $this->createDepartments();

        // ============================================
        // 4. ایجاد انواع تجهیزات (7 نوع)
        // ============================================
        $this->command->info('ایجاد انواع تجهیزات...');
        $equipmentTypes = $this->createEquipmentTypes();

        // ============================================
        // 5. ایجاد 150 بازدید برای یک سال گذشته
        // ============================================
        $this->command->info('ایجاد 150 بازدید برای یک سال گذشته...');
        $inspections = $this->createInspections($createdUsers, $contractors);

        // ============================================
        // 6. ایجاد تجهیزات برای هر بازدید (3-4 تجهیز)
        // ============================================
        $this->command->info('ایجاد تجهیزات برای بازدیدها (هر بازدید 3-4 تجهیز)...');
        $this->createEquipmentsWithDetails($inspections, $departments, $equipmentTypes);

        // ============================================
        // 7. آمار نهایی
        // ============================================
        $this->showFinalStats();
    }

    protected function createUsers()
    {
        $users = [
            ['name' => 'مدیر سیستم', 'email' => 'admin@yazd-electric.ir', 'role' => 'admin'],
            ['name' => 'ناظر عالی', 'email' => 'supervisor@yazd-electric.ir', 'role' => 'supervisor'],
            ['name' => 'کارشناس فنی ۱', 'email' => 'tech1@yazd-electric.ir', 'role' => 'user'],
            ['name' => 'کارشناس فنی ۲', 'email' => 'tech2@yazd-electric.ir', 'role' => 'user'],
            ['name' => 'کارشناس فنی ۳', 'email' => 'tech3@yazd-electric.ir', 'role' => 'user'],
            ['name' => 'کارشناس فنی ۴', 'email' => 'tech4@yazd-electric.ir', 'role' => 'user'],
            ['name' => 'کارشناس فنی ۵', 'email' => 'tech5@yazd-electric.ir', 'role' => 'user']
        ];

        $createdUsers = [];
        foreach ($users as $userData) {
            $user = User::firstOrCreate(
                ['email' => $userData['email']],
                [
                    'name' => $userData['name'],
                    'password' => Hash::make('123456'),
                    'role' => $userData['role']
                ]
            );
            $createdUsers[] = $user;
            $this->command->info($user->wasRecentlyCreated ? "  ✅ کاربر {$userData['name']} ایجاد شد" : "  ⏩ کاربر {$userData['name']} از قبل وجود داشت");
        }
        
        return $createdUsers;
    }

    protected function createContractors()
    {
        $contractors = [];
        foreach ($this->contractors as $contractorName) {
            $contractor = Contractor::firstOrCreate(
                ['name' => $contractorName],
                [
                    'coefficient' => 2.35,
                    'contract_number' => '1403/' . rand(100, 999) . '/' . rand(100, 999),
                    'phone' => '035-' . rand(1000000, 9999999),
                    'whatsapp' => '989' . rand(100000000, 999999999),
                    'address' => 'آدرس ' . $contractorName
                ]
            );
            $contractors[] = $contractor;
            $this->command->info($contractor->wasRecentlyCreated ? "  ✅ پیمانکار {$contractorName} ایجاد شد" : "  ⏩ پیمانکار {$contractorName} از قبل وجود داشت");
        }
        return $contractors;
    }

    protected function createDepartments()
    {
        $departmentNames = [
            'ستاد', 'امور یک', 'امور دو', 'امور سه', 'زارچ', 'اشکذر',
            'میبد', 'اردکان', 'تفت', 'مهریز', 'نیر', 'هرات',
            'مروست', 'ابرکوه', 'بافق', 'بهاباد'
        ];

        $departments = [];
        foreach ($departmentNames as $name) {
            $department = Department::firstOrCreate(
                ['name' => $name]
            );
            $departments[] = $department;
            $this->command->info($department->wasRecentlyCreated ? "  ✅ دپارتمان {$name} ایجاد شد" : "  ⏩ دپارتمان {$name} از قبل وجود داشت");
        }
        
        return $departments;
    }

    protected function createEquipmentTypes()
    {
        $types = [];
        foreach ($this->equipmentTypes as $typeName) {
            $type = MainEquipmentType::firstOrCreate(['name' => $typeName]);
            $types[] = $type;
            $this->command->info($type->wasRecentlyCreated ? "  ✅ نوع تجهیز {$typeName} ایجاد شد" : "  ⏩ نوع تجهیز {$typeName} از قبل وجود داشت");
        }
        return $types;
    }

    protected function createInspections($users, $contractors)
    {
        $inspections = [];
        $endDate = Carbon::now();
        $startDate = Carbon::now()->subYear();

        for ($i = 1; $i <= 150; $i++) {
            $randomTimestamp = rand($startDate->timestamp, $endDate->timestamp);
            $randomDate = Carbon::createFromTimestamp($randomTimestamp);
            $v = verta($randomDate);
            
            $status = $this->getRandomWeighted(['completed' => 80, 'draft' => 15, 'archived' => 5]);
            $user = $users[array_rand($users)];
            $contractor = $contractors[array_rand($contractors)];
            
            $inspection = Inspection::create([
                'inspection_date' => $randomDate->format('Y-m-d'),
                'contractor_id' => $contractor->id,
                'status' => $status,
                'user_id' => $user->id,
                'daily_start_time' => $randomDate->copy()->setTime(rand(8, 9), rand(0, 59))->format('H:i'),
                'daily_end_time' => $randomDate->copy()->setTime(rand(14, 16), rand(0, 59))->format('H:i'),
                'created_at' => $randomDate,
                'updated_at' => $randomDate,
            ]);
            
            $inspections[] = $inspection;
            
            if ($i % 10 == 0) {
                $this->command->info("  ... {$i} بازدید ایجاد شد (تاریخ: {$v->format('Y/m/d')})");
            }
        }
        
        $this->command->info("  ✅ 150 بازدید برای یک سال گذشته ایجاد شد");
        return $inspections;
    }

    protected function createEquipmentsWithDetails($inspections, $departments, $equipmentTypes)
    {
        $totalEquipments = 0;
        $totalActivities = 0;
        $totalChecklists = 0;

        foreach ($inspections as $inspection) {
            // هر بازدید بین 3 تا 4 تجهیز دارد
            $equipmentCount = rand(3, 4);
            
            for ($j = 1; $j <= $equipmentCount; $j++) {
                // انتخاب تصادفی نوع تجهیز از بین همه انواع
                $type = $equipmentTypes[array_rand($equipmentTypes)];
                
                // انتخاب یک دپارتمان تصادفی
                $department = $departments[array_rand($departments)];
                
                // ========== ۱. ایجاد تجهیز اصلی ==========
                $equipment = MainEquipment::create([
                    'inspection_id' => $inspection->id,
                    'main_equipment_type_id' => $type->id,
                    'department_id' => $department->id,
                    'scada_code' => str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT),
                    'created_at' => $inspection->created_at,
                    'updated_at' => $inspection->created_at,
                ]);
                
                $totalEquipments++;

                // ========== ۲. موقعیت جغرافیایی ==========
                EquipmentLocation::create([
                    'main_equipment_id' => $equipment->id,
                    'latitude' => 31.8974 + (rand(-100, 100) / 1000),
                    'longitude' => 54.3569 + (rand(-100, 100) / 1000),
                    'address' => 'آدرس پست شماره ' . rand(1, 100) . ' - خیابان ' . $this->getRandom(['امام', 'شهید بهشتی', 'شهید چمران', 'بلوار جمهوری']),
                    'cabinet_initial_height' => rand(5, 15) / 10,
                    'cabinet_final_height' => rand(5, 15) / 10,
                    'created_at' => $inspection->created_at,
                    'updated_at' => $inspection->created_at,
                ]);

                // ========== ۳. اطلاعات ارتباطی ==========
                EquipmentCommunication::create([
                    'main_equipment_id' => $equipment->id,
                    'simcard_type' => $this->getRandom(['ایرانسل', 'همراه اول', 'رایتل']),
                    'simcard_number' => '09' . rand(10, 99) . rand(100, 999) . rand(1000, 9999),
                    'simcard_ip' => '10.' . rand(1, 255) . '.' . rand(1, 255) . '.' . rand(1, 255),
                    'antenna_status' => $this->getRandom(['داخل تابلو', 'خارج تابلو', 'ندارد']),
                    'signal_status' => $this->getRandom(['خوب', 'ضعیف']),
                    'modem_power' => $this->getRandom(['پنل', 'باتری']),
                    'reset_possible' => (rand(1, 10) > 2),
                    'created_at' => $inspection->created_at,
                    'updated_at' => $inspection->created_at,
                ]);

                // ========== ۴. چک‌لیست (بین 5 تا 10 آیتم) ==========
                $checklistCount = rand(5, 10);
                for ($k = 1; $k <= $checklistCount; $k++) {
                    $status = $this->getRandomWeighted(['OK' => 70, 'Not OK' => 25, 'Not Checked' => 5]);
                    
                    EquipmentChecklist::create([
                        'main_equipment_id' => $equipment->id,
                        'item' => $this->checklistItems[array_rand($this->checklistItems)],
                        'status' => $status,
                        'description' => $status == 'Not OK' ? $this->getRandom([
                            'نیاز به تعمیر', 'اتصالات شل', 'قطعه خراب', 'نشت روغن'
                        ]) : null,
                        'sort_order' => $k,
                        'created_at' => $inspection->created_at,
                        'updated_at' => $inspection->created_at,
                    ]);
                    $totalChecklists++;
                }

                // ========== ۵. فعالیت‌ها (بین 1 تا 4 فعالیت) ==========
                $activityCount = rand(1, 4);
                for ($l = 1; $l <= $activityCount; $l++) {
                    $activity = $this->activityCodes[array_rand($this->activityCodes)];
                    $quantity = $activity['code'] == '911402-1' ? rand(10, 50) : rand(1, 3);
                    $total = $activity['price'] * $quantity;
                    
                    EquipmentActivity::create([
                        'main_equipment_id' => $equipment->id,
                        'code' => $activity['code'],
                        'title' => $activity['title'],
                        'unit' => $activity['code'] == '911402-1' ? 'کیلومتر' : 'مورد',
                        'unit_price' => $activity['price'],
                        'quantity' => $quantity,
                        'total' => $total,
                        'created_at' => $inspection->created_at,
                        'updated_at' => $inspection->created_at,
                    ]);
                    $totalActivities++;
                }

                // ========== ۶. مصارف (بین 0 تا 3 قلم) ==========
                $consumableCount = rand(0, 3);
                for ($m = 1; $m <= $consumableCount; $m++) {
                    EquipmentConsumable::create([
                        'main_equipment_id' => $equipment->id,
                        'name' => $this->getRandom(['کابل', 'فیوز', 'باتری', 'مودم', 'آنتن']),
                        'quantity' => rand(1, 5),
                        'unit' => $this->getRandom(['عدد', 'متر', 'کیلوگرم']),
                        'description' => 'مصرفی شماره ' . rand(1, 100),
                        'created_at' => $inspection->created_at,
                    ]);
                }

                // ========== ۷. فیدرها (بین 1 تا 3 فیدر) ==========
                $feederCount = rand(1, 3);
                for ($n = 1; $n <= $feederCount; $n++) {
                    EquipmentFeeder::create([
                        'main_equipment_id' => $equipment->id,
                        'post' => 'پست شماره ' . rand(1, 20),
                        'feeder' => 'فیدر ' . rand(1, 10),
                        'created_at' => $inspection->created_at,
                    ]);
                }
            }
        }

        $this->command->info("  ✅ {$totalEquipments} تجهیز ایجاد شد");
        $this->command->info("  ✅ {$totalActivities} فعالیت ایجاد شد");
        $this->command->info("  ✅ {$totalChecklists} آیتم چک‌لیست ایجاد شد");
    }

    protected function showFinalStats()
    {
        $this->command->info("\n📊 آمار نهایی:");
        
        $stats = [
            'کاربران' => User::count(),
            'پیمانکاران' => Contractor::count(),
            'دپارتمان‌ها' => Department::count(),
            'انواع تجهیزات' => MainEquipmentType::count(),
            'بازدیدها' => Inspection::count(),
            'تجهیزات اصلی' => MainEquipment::count(),
            'موقعیت‌های جغرافیایی' => EquipmentLocation::count(),
            'ارتباطات' => EquipmentCommunication::count(),
            'چک‌لیست‌ها' => EquipmentChecklist::count(),
            'فعالیت‌ها' => EquipmentActivity::count(),
            'مصارف' => EquipmentConsumable::count(),
            'فیدرها' => EquipmentFeeder::count(),
        ];
        
        foreach ($stats as $key => $value) {
            $this->command->info("  📌 {$key}: {$value}");
        }
        
        $this->command->info("\n✅ دیتای کامل با موفقیت ایجاد شد!");
        $this->command->info("👥 کاربران:");
        $this->command->info("   admin@yazd-electric.ir / 123456");
        $this->command->info("   supervisor@yazd-electric.ir / 123456");
        $this->command->info("   tech1@yazd-electric.ir / 123456");
    }

    protected function getRandom($array)
    {
        return $array[array_rand($array)];
    }

    protected function getRandomWeighted($weights)
    {
        $rand = rand(1, 100);
        $cumulative = 0;
        
        foreach ($weights as $value => $weight) {
            $cumulative += $weight;
            if ($rand <= $cumulative) return $value;
        }
        
        return array_key_first($weights);
    }
}