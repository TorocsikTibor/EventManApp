<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class SearchTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function testSearchCanSearch_Success(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->postJson('/event/search?=test', );
        $response->assertStatus(200);
    }
}
