<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\MainEquipment;
use App\Models\MainEquipmentType;
use App\Models\Post;
use App\Models\Inspection;
use Illuminate\Foundation\Testing\RefreshDatabase;

class EquipmentTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_list_equipments()
    {
        $response = $this->getJson('/api/main-equipments');
        
        $response->assertStatus(200);
    }

    public function test_can_create_equipment()
    {
        $inspection = Inspection::factory()->create();
        $type = MainEquipmentType::factory()->create();
        $post = Post::factory()->create();

        $response = $this->postJson('/api/main-equipments', [
            'inspection_id' => $inspection->id,
            'main_equipment_type_id' => $type->id,
            'post_id' => $post->id,
            'scada_code' => '1234',
            'latitude' => 31.5,
            'longitude' => 54.3,
            'height' => 5,
            'installation_type' => 'هوایی'
        ]);

        $response->assertStatus(201);
    }
}