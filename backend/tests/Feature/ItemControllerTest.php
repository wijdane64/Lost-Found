<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Item;

class ItemControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function user_can_create_item()
    {
        $user = User::factory()->create(['role' => 'user']);
        $token = $user->createToken('token')->plainTextToken;

        $response = $this->withHeaders([
            'Authorization' => 'Bearer '.$token,
        ])->postJson('/api/items', [
            'title' => 'Clé perdue',
            'description' => 'Clé de voiture',
            'type' => 'perdu',
            'location' => 'Université',
            'date' => now()->toDateString(),
        ]);

        $response->assertStatus(201)
                 ->assertJsonStructure(['item' => ['id','title','description','type','location','date','user_id']]);

        $this->assertDatabaseHas('items', [
            'title' => 'Clé perdue',
            'user_id' => $user->id,
        ]);
    }

    /** @test */
    public function user_can_see_their_own_items_only()
    {
        $user1 = User::factory()->create(['role' => 'user']);
        $user2 = User::factory()->create(['role' => 'user']);

        Item::factory()->create(['user_id' => $user1->id, 'title' => 'Item1']);
        Item::factory()->create(['user_id' => $user2->id, 'title' => 'Item2']);

        $token = $user1->createToken('token')->plainTextToken;

        $response = $this->withHeaders([
            'Authorization' => 'Bearer '.$token,
        ])->getJson('/api/my-items');

        $response->assertStatus(200)
                 ->assertJsonCount(1)
                 ->assertJsonFragment(['title' => 'Item1']);
    }

    /** @test */
    public function admin_can_see_all_items()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $user = User::factory()->create(['role' => 'user']);

        Item::factory()->create(['user_id' => $user->id, 'title' => 'Item1']);

        $token = $admin->createToken('token')->plainTextToken;

        $response = $this->withHeaders([
            'Authorization' => 'Bearer '.$token,
        ])->getJson('/api/items');

        $response->assertStatus(200)
                 ->assertJsonFragment(['title' => 'Item1']);
    }

    /** @test */
    public function user_cannot_delete_others_item()
    {
        $user1 = User::factory()->create(['role' => 'user']);
        $user2 = User::factory()->create(['role' => 'user']);

        $item = Item::factory()->create(['user_id' => $user2->id]);

        $token = $user1->createToken('token')->plainTextToken;

        $response = $this->withHeaders([
            'Authorization' => 'Bearer '.$token,
        ])->deleteJson('/api/items/'.$item->id);

        $response->assertStatus(403);
    }

    /** @test */
    public function user_can_delete_their_own_item()
    {
        $user = User::factory()->create(['role' => 'user']);

        $item = Item::factory()->create(['user_id' => $user->id]);

        $token = $user->createToken('token')->plainTextToken;

        $response = $this->withHeaders([
            'Authorization' => 'Bearer '.$token,
        ])->deleteJson('/api/items/'.$item->id);

        $response->assertStatus(200);
        $this->assertDatabaseMissing('items', ['id' => $item->id]);
    }

    /** @test */
    public function admin_can_delete_any_item()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $user = User::factory()->create(['role' => 'user']);

        $item = Item::factory()->create(['user_id' => $user->id]);

        $token = $admin->createToken('token')->plainTextToken;

        $response = $this->withHeaders([
            'Authorization' => 'Bearer '.$token,
        ])->deleteJson('/api/items/'.$item->id);

        $response->assertStatus(200);
        $this->assertDatabaseMissing('items', ['id' => $item->id]);
    }
}
