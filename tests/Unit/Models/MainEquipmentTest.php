<?php

namespace Tests\Unit\Models;

use Tests\TestCase;
use App\Models\MainEquipment;
use App\Models\MainEquipmentType;
use App\Models\Post;
use App\Models\Inspection;
use Illuminate\Foundation\Testing\RefreshDatabase;

class MainEquipmentTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_create_equipment_with_all_fields()
    {
        // ساختن همه مدل‌های مورد نیاز
        $inspection = Inspection::factory()->create();
        $type = MainEquipmentType::factory()->create();
        $post = Post::factory()->create();
        
        // ساختن تجهیز با مقادیر دستی
        $equipment = MainEquipment::create([
            'inspection_id' => $inspection->id,
            'main_equipment_type_id' => $type->id,
            'post_id' => $post->id,
            'scada_code' => '1234',
            'latitude' => 31.5,
            'longitude' => 54.3,
            'height' => 5,
            'installation_type' => 'هوایی'
        ]);
        
        // چک کن که توی دیتابیس هست
        $this->assertDatabaseHas('main_equipments', [
            'id' => $equipment->id,
            'inspection_id' => $inspection->id,
            'main_equipment_type_id' => $type->id,
            'post_id' => $post->id,
            'scada_code' => '1234'
        ]);
    }

    public function test_factory_works_correctly()
    {
        $equipment = MainEquipment::factory()->create();
        
        $this->assertDatabaseHas('main_equipments', [
            'id' => $equipment->id
        ]);
        
        // رفرش کن تا روابط لود بشن
        $equipment->refresh();
        
        $this->assertNotNull($equipment->inspection, 'inspection نباید null باشد');
        $this->assertNotNull($equipment->type, 'type نباید null باشد');
        $this->assertNotNull($equipment->post, 'post نباید null باشد');
    }

    public function test_main_equipment_belongs_to_inspection()
    {
        $inspection = Inspection::factory()->create();
        $equipment = MainEquipment::factory()->create([
            'inspection_id' => $inspection->id
        ]);

        $equipment->refresh(); // رفرش کن
        
        $this->assertInstanceOf(Inspection::class, $equipment->inspection);
        $this->assertEquals($inspection->id, $equipment->inspection->id);
    }

    public function test_main_equipment_belongs_to_type()
    {
        $type = MainEquipmentType::factory()->create();
        $equipment = MainEquipment::factory()->create([
            'main_equipment_type_id' => $type->id
        ]);

        $equipment->refresh(); // رفرش کن
        
        $this->assertInstanceOf(MainEquipmentType::class, $equipment->type);
        $this->assertEquals($type->id, $equipment->type->id);
    }

    public function test_main_equipment_belongs_to_post()
    {
        $post = Post::factory()->create();
        $equipment = MainEquipment::factory()->create([
            'post_id' => $post->id
        ]);

        $equipment->refresh(); // رفرش کن
        
        $this->assertInstanceOf(Post::class, $equipment->post);
        $this->assertEquals($post->id, $equipment->post->id);
    }

public function test_main_equipment_has_required_fields()
{
    $equipment = MainEquipment::factory()->make();
    
    $this->assertNotNull($equipment->inspection_id);
    $this->assertNotNull($equipment->main_equipment_type_id);
    $this->assertNotNull($equipment->post_id);
}

}