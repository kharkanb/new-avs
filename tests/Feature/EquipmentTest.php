<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\MainEquipment;
use App\Models\MainEquipmentType;
use App\Models\Post;
use App\Models\Inspection;
use App\Models\User;
use Laravel\Sanctum\Sanctum;
use Illuminate\Foundation\Testing\RefreshDatabase;

class EquipmentTest extends TestCase
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

    public function test_can_list_equipments()
    {
        // ایجاد چند تجهیز نمونه
        MainEquipment::factory()->count(3)->create();

        $response = $this->getJson('/api/main-equipments');

        $response->assertStatus(200);
    }

    public function test_can_create_equipment()
    {
        // ایجاد داده‌های مورد نیاز
        $inspection = Inspection::factory()->create();
        $type = MainEquipmentType::factory()->create();
        $post = Post::factory()->create();

        $equipmentData = [
            'inspection_id' => $inspection->id,
            'main_equipment_type_id' => $type->id,
            'post_id' => $post->id,
            'scada_code' => '1234',
            'latitude' => 31.5,
            'longitude' => 54.3,
            'height' => 5,
            'installation_type' => 'هوایی'
        ];

        $response = $this->postJson('/api/main-equipments', $equipmentData);

        $response->assertStatus(201);
        
        $this->assertDatabaseHas('main_equipments', [
            'scada_code' => '1234'
        ]);
    }

    public function test_can_show_equipment()
    {
        $equipment = MainEquipment::factory()->create();

        $response = $this->getJson('/api/main-equipments/' . $equipment->id);

        $response->assertStatus(200)
                 ->assertJson([
                     'success' => true,
                     'data' => [
                         'id' => $equipment->id
                     ]
                 ]);
    }

    public function test_can_update_equipment()
    {
        $equipment = MainEquipment::factory()->create();

        $updateData = [
            'height' => 10,
            'installation_type' => 'زمینی'
        ];

        $response = $this->putJson('/api/main-equipments/' . $equipment->id, $updateData);

        $response->assertStatus(200);
        
        $this->assertDatabaseHas('main_equipments', [
            'id' => $equipment->id,
            'height' => 10,
            'installation_type' => 'زمینی'
        ]);
    }

    public function test_can_delete_equipment()
    {
        $equipment = MainEquipment::factory()->create();

        $response = $this->deleteJson('/api/main-equipments/' . $equipment->id);

        $response->assertStatus(200);
        
        $this->assertDatabaseMissing('main_equipments', [
            'id' => $equipment->id
        ]);
    }

    public function test_can_get_equipment_cells()
    {
        $equipment = MainEquipment::factory()->create();

        $response = $this->getJson('/api/main-equipments/' . $equipment->id . '/cells');

        $response->assertStatus(200);
    }

    public function test_can_get_equipment_activities()
    {
        $equipment = MainEquipment::factory()->create();

        $response = $this->getJson('/api/main-equipments/' . $equipment->id . '/activities');

        $response->assertStatus(200);
    }

    public function test_can_get_equipment_consumables()
    {
        $equipment = MainEquipment::factory()->create();

        $response = $this->getJson('/api/main-equipments/' . $equipment->id . '/consumables');

        $response->assertStatus(200);
    }

    public function test_validation_works()
    {
        $response = $this->postJson('/api/main-equipments', []);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['inspection_id', 'main_equipment_type_id', 'post_id']);
    }

    public function test_returns_404_for_non_existent_equipment()
    {
        $response = $this->getJson('/api/main-equipments/99999');

        $response->assertStatus(404);
    }
}