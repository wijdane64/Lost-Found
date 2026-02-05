<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;

class UserControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function admin_can_view_all_users()
    {
        $admin = User::factory()->create([
            'role' => 'admin',
        ]);

        $token = $admin->createToken('token')->plainTextToken;

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->getJson('/api/users');

        $response->assertStatus(200);
    }

    /** @test */
    public function normal_user_cannot_view_all_users()
    {
        $user = User::factory()->create([
            'role' => 'user',
        ]);

        $token = $user->createToken('token')->plainTextToken;

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->getJson('/api/users');

        $response->assertStatus(403);
    }

    /** @test */
    public function admin_can_delete_user()
    {
        $admin = User::factory()->create([
            'role' => 'admin',
        ]);

        $user = User::factory()->create();

        $token = $admin->createToken('token')->plainTextToken;

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->deleteJson('/api/users/' . $user->id);

        $response->assertStatus(200);

        $this->assertDatabaseMissing('users', [
            'id' => $user->id,
        ]);
    }

    /** @test */
    public function normal_user_cannot_delete_user()
    {
        $user1 = User::factory()->create([
            'role' => 'user',
        ]);

        $user2 = User::factory()->create();

        $token = $user1->createToken('token')->plainTextToken;

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->deleteJson('/api/users/' . $user2->id);

        $response->assertStatus(403);
    }
}
