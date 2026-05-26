<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Inspection;
use App\Models\MainEquipment;
use App\Models\MainEquipmentType;
use App\Models\EquipmentActivity;
use App\Models\EquipmentChecklist;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Hekmatinasser\Verta\Verta;

class RealisticDataSeeder extends Seeder
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
        $this->command->info('🚀 شروع ساخت دیتای واقعی...');

        // ============================================
        // 1. ایجاد کاربران
        // ============================================
        $this->command->info('ایجاد کاربران...');
        $createdUsers = $this->createUsers();

        // ============================================
        // 2. ایجاد 150 بازدید
        // ============================================
        $this->command->info('ایجاد 150 بازدید برای یک سال گذشته...');
        $inspections = $this->createInspections($createdUsers);

        // ============================================
        // 3. ایجاد تجهیزات
        // ============================================
        $this->command->info('ایجاد تجهیزات برای بازدیدها...');
        $this->createEquipments($inspections);

        // ============================================
        // 4. آمار نهایی
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

    protected function createInspections($users)
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
            $contractor = $this->contractors[array_rand($this->contractors)];
            
            $inspection = Inspection::create([
                'inspection_date' => $randomDate->format('Y-m-d'),
                'contractor' => $contractor,
                'status' => $status,
                'user_id' => $user->id,
                'daily_start_time' => $randomDate->copy()->setTime(rand(8, 9), rand(0, 59))->format('H:i'),
                'daily_end_time' => $randomDate->copy()->setTime(rand(14, 16), rand(0, 59))->format('H:i'),
                'contract_coefficient' => 2.35,
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

    protected function createEquipments($inspections)
    {
        // ایجاد یا دریافت انواع تجهیزات
        $equipmentTypeModels = [];
        foreach ($this->equipmentTypes as $typeName) {
            $equipmentTypeModels[] = MainEquipmentType::firstOrCreate(['name' => $typeName]);
        }

        $totalEquipments = 0;

        foreach ($inspections as $inspection) {
            $equipmentCount = rand(2, 8);
            
            for ($j = 1; $j <= $equipmentCount; $j++) {
                $type = $equipmentTypeModels[array_rand($equipmentTypeModels)];
                
                $equipment = MainEquipment::create([
                    'inspection_id' => $inspection->id,
                    'main_equipment_type_id' => $type->id,
                    'scada_code' => str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT),
                    'latitude' => 31.8974 + (rand(-100, 100) / 1000),
                    'longitude' => 54.3569 + (rand(-100, 100) / 1000),
                    'installation_type' => $this->getRandom(['داخلی', 'خارجی', 'نیمه باز']),
                    'created_at' => $inspection->created_at,
                    'updated_at' => $inspection->created_at,
                ]);
                
                $totalEquipments++;
                
                // ایجاد چک‌لیست (بدون checked_by و checked_at)
                $this->createChecklist($equipment, $inspection);
                
                // ایجاد فعالیت (با ستون‌های صحیح)
                $this->createActivities($equipment, $inspection);
            }
        }

        $this->command->info("  ✅ {$totalEquipments} تجهیز ایجاد شد");
    }

    protected function createChecklist($equipment, $inspection)
    {
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
        }
    }

protected function createActivities($equipment, $inspection)
{
    $activityCount = rand(1, 4);
    
    for ($l = 1; $l <= $activityCount; $l++) {
        $activity = $this->activityCodes[array_rand($this->activityCodes)];
        $quantity = $activity['code'] == '911402-1' ? rand(10, 50) : rand(1, 3);
        $total = $activity['price'] * $quantity;
        
        // بر اساس ساختار جدول equipment_activities
        EquipmentActivity::create([
            'main_equipment_id' => $equipment->id,
            'code' => $activity['code'],
            'title' => $activity['title'],
            'unit' => $activity['code'] == '911402-1' ? 'کیلومتر' : 'مورد',
            'unit_price' => $activity['price'],  // مهم: اینجا unit_price هست نه price
            'quantity' => $quantity,
            'total' => $total,
            'created_at' => $inspection->created_at,
            'updated_at' => $inspection->created_at,
        ]);
    }
}
    protected function showFinalStats()
    {
        $this->command->info("\n📊 آمار نهایی:");
        
        $stats = [
            'users' => User::count(),
            'inspections' => Inspection::count(),
            'main_equipments' => MainEquipment::count(),
            'equipment_activities' => EquipmentActivity::count(),
            'equipment_checklists' => EquipmentChecklist::count(),
        ];
        
        foreach ($stats as $key => $value) {
            $this->command->info("  📌 {$key}: {$value}");
        }
        
        $this->command->info("\n✅ دیتای واقعی با موفقیت ایجاد شد!");
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