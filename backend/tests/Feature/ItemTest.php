<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use App\Models\Item;

class ItemTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_create_items()
    {
        
        $user = User::factory()->create();

        
        Item::factory()->count(5)->create([
            'user_id' => $user->id,
        ]);

        
        $this->assertDatabaseCount('items', 5);
    }
}
