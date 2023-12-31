<?php

namespace Tests\Feature;

use App\Models\User;
use Database\Seeders\tests\EventTestSeeder;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class SearchTest extends TestCase
{
    use DatabaseTransactions;

    public function setUp(): void
    {
        parent::setUp(); // TODO: Change the autogenerated stub
        $this->seed(EventTestSeeder::class);
    }

    /**
     * A basic feature test example.
     */
    public function testSearchOnNoValueFound_Success(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->getJson('/event/search?select=name&searchValue=alma');
        $response->assertStatus(200);
        $response->assertJson([ 'searchedEvents' => [], 'AuthId' => $user->id ]);
    }

    public function testSearchWithSeeder_Success()
    {
        $user = User::factory()->create();

        $expectedResult = [
           [ 'name' => 'test',
            'user_id' => 1,
            'date' => '2023-10-31 20:00:00',
            'location' => "Hungary",
            'image' => asset('images' . DIRECTORY_SEPARATOR . '20231030195408new.png'),
            'type' => 'concert',
            'description' => 'test desc',
            'is_private' => 0,]
        ];

        $response = $this->actingAs($user)->getJson('/event/search?select=name&searchValue=test');
        $response->assertStatus(200);
        $response->assertJson([ 'searchedEvents' => $expectedResult, 'AuthId' => $user->id ]);
    }

}
