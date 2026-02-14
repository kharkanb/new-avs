<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_login_with_valid_credentials()
    {
        // یه کاربر بساز
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => bcrypt('123456')
        ]);

        // درخواست لاگین بفرست
        $response = $this->postJson('/api/login', [
            'email' => 'test@example.com',
            'password' => '123456'
        ]);

        // بررسی کن که پاسخ موفق بوده
        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'success',
                     'token',
                     'user'
                 ]);
    }

    public function test_user_cannot_login_with_invalid_password()
    {
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => bcrypt('123456')
        ]);

        $response = $this->postJson('/api/login', [
            'email' => 'test@example.com',
            'password' => 'wrong-password'
        ]);

        $response->assertStatus(422);
    }
}