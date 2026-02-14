<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Inspection;
use App\Models\MainEquipment;
use App\Models\User;
use Laravel\Sanctum\Sanctum;
use Illuminate\Foundation\Testing\RefreshDatabase;

class InspectionTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        
        // ایجاد یه کاربر و لاگین کردن قبل از هر تست
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => bcrypt('password')
        ]);
        
        Sanctum::actingAs($user);
    }

    public function test_can_list_inspections()
    {
        // ایجاد چند بازرسی نمونه
        Inspection::factory()->count(3)->create();

        // درخواست GET به آدرس inspections
        $response = $this->getJson('/api/inspections');

        // بررسی وضعیت 200
        $response->assertStatus(200)
                 ->assertJsonCount(3, 'data.data');
    }

    public function test_can_create_inspection()
    {
        $inspectionData = [
            'inspection_date' => '2026-02-15',
            'daily_start_time' => '08:00',
            'daily_end_time' => '16:00',
            'contractor' => 'پیمانکار نمونه',
            'contract_coefficient' => 1.5,
            'contract_number' => 'CON-12345',
            'whatsapp_number' => '09123456789',
            'status' => 'draft'
        ];

        $response = $this->postJson('/api/inspections', $inspectionData);

        $response->assertStatus(201)
                 ->assertJson([
                     'success' => true,
                     'message' => 'بازرسی با موفقیت ثبت شد'
                 ]);

        $this->assertDatabaseHas('inspections', [
            'contract_number' => 'CON-12345',
            'status' => 'draft'
        ]);
    }

    public function test_can_show_inspection()
    {
        $inspection = Inspection::factory()->create();

        $response = $this->getJson('/api/inspections/' . $inspection->id);

        $response->assertStatus(200)
                 ->assertJson([
                     'success' => true,
                     'data' => [
                         'id' => $inspection->id
                     ]
                 ]);
    }

    public function test_can_update_inspection()
    {
        $inspection = Inspection::factory()->create();

        $updateData = [
            'contractor' => 'پیمانکار جدید',
            'status' => 'completed'
        ];

        $response = $this->putJson('/api/inspections/' . $inspection->id, $updateData);

        $response->assertStatus(200)
                 ->assertJson([
                     'success' => true,
                     'message' => 'بازرسی با موفقیت بروزرسانی شد'
                 ]);

        $this->assertDatabaseHas('inspections', [
            'id' => $inspection->id,
            'contractor' => 'پیمانکار جدید',
            'status' => 'completed'
        ]);
    }

    public function test_can_delete_inspection()
    {
        $inspection = Inspection::factory()->create();

        $response = $this->deleteJson('/api/inspections/' . $inspection->id);

        $response->assertStatus(200)
                 ->assertJson([
                     'success' => true,
                     'message' => 'بازرسی با موفقیت حذف شد'
                 ]);

        $this->assertDatabaseMissing('inspections', [
            'id' => $inspection->id
        ]);
    }

    public function test_can_get_inspection_equipments()
    {
        $inspection = Inspection::factory()->create();
        
        // ایجاد چند تجهیز برای این بازرسی
        MainEquipment::factory()->count(2)->create([
            'inspection_id' => $inspection->id
        ]);

        $response = $this->getJson('/api/inspections/' . $inspection->id . '/equipments');

        $response->assertStatus(200)
                 ->assertJsonCount(2, 'data');
    }

    public function test_validation_works()
    {
        // ارسال داده ناقص
        $response = $this->postJson('/api/inspections', []);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['inspection_date']);
    }

    public function test_returns_404_for_non_existent_inspection()
    {
        $response = $this->getJson('/api/inspections/99999');

        $response->assertStatus(404);
    }
}