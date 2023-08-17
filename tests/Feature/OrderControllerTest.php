<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Tests\TestCase;
use App\Models\User;
use App\Models\Order;
use App\Models\Product;

class OrderControllerTest extends TestCase
{
    use RefreshDatabase, WithoutMiddleware;

    public function testUserCanRetrieveTheirOrders()
    {
        $user = User::factory()->create();
        Order::factory()->count(3)->create(['user_id' => $user->id]);

        $response = $this->actingAs($user)->getJson('/api/order');

        $response->assertStatus(200);
        $response->assertJsonCount(3, 'data');
    }

    public function testCreateOrderWithValidData()
    {
        $user = User::factory()->create();
        $products = Product::factory()->count(3)->create();

        $orderData = [
            'total' => 1000,
            'address' => 'Fake Street 123',
            'phone' => '1234567890',
            'products' => [
                ['id' => $products[0]->id, 'amount' => 2],
                ['id' => $products[1]->id, 'amount' => 1],
                ['id' => $products[2]->id, 'amount' => 3],
            ]
        ];

        $response = $this->actingAs($user)->postJson('/api/order', $orderData);

        $response->assertStatus(200);
        $response->assertJson(['message' => 'Order created successfully']);
    }

    public function testCreateOrderWithInvalidData()
    {
        $user = User::factory()->create();

        $orderData = [
            'total' => 'invalid',
            'address' => '',
            'phone' => '',
            'products' => 'invalid'
        ];

        $response = $this->actingAs($user)->postJson('/api/order', $orderData);

        $response->assertStatus(400);
    }
}
