<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Item;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PurchaseTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->artisan('migrate:fresh');
        $this->seed();
    }

    /** @test */
    public function user_can_purchase_item()
    {
        $seller = User::factory()->create();
        $buyer = User::factory()->create([
            'postal_code' => '100-0001',
            'address' => '東京都千代田区',
            'building' => 'テストビル101',
        ]);

        $item = Item::factory()->create([
            'seller_id' => $seller->id,
            'sold' => false,
        ]);

        $response = $this->actingAs($buyer)->post("/purchase/{$item->id}", [
            'payment_method' => 'カード払い',
            'delivery_address' => $buyer->postal_code . ' ' . $buyer->address . $buyer->building,
        ]);

        $response->assertRedirect('/mypage?tab=buy');
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('items', [
            'id' => $item->id,
            'buyer_id' => $buyer->id,
            'sold' => true,
            'payment_method' => 'カード払い',
        ]);
    }

    /** @test */
    public function user_cannot_purchase_own_item()
    {
        $user = User::factory()->create();
        $item = Item::factory()->create([
            'seller_id' => $user->id,
            'sold' => false,
        ]);

        $response = $this->actingAs($user)->post("/purchase/{$item->id}", [
            'payment_method' => 'カード払い',
            'delivery_address' => '東京都千代田区',
        ]);

        $response->assertRedirect('/');
        $response->assertSessionHas('error');

        $this->assertDatabaseHas('items', [
            'id' => $item->id,
            'sold' => false,
            'buyer_id' => null,
        ]);
    }

    /** @test */
    public function user_cannot_purchase_already_sold_item()
    {
        $seller = User::factory()->create();
        $firstBuyer = User::factory()->create();
        $secondBuyer = User::factory()->create();

        $item = Item::factory()->create([
            'seller_id' => $seller->id,
            'buyer_id' => $firstBuyer->id,
            'sold' => true, // 既に売却済み
        ]);

        $response = $this->actingAs($secondBuyer)->post("/purchase/{$item->id}", [
            'payment_method' => 'コンビニ払い',
            'delivery_address' => '東京都千代田区',
        ]);

        $response->assertRedirect('/');
        $response->assertSessionHas('error');

        $this->assertDatabaseHas('items', [
            'id' => $item->id,
            'buyer_id' => $firstBuyer->id,
        ]);
    }

    /** @test */
    public function purchase_requires_payment_method_and_delivery_address()
    {
        $seller = User::factory()->create();
        $buyer = User::factory()->create();
        $item = Item::factory()->create([
            'seller_id' => $seller->id,
            'sold' => false,
        ]);

        $response = $this->actingAs($buyer)->post("/purchase/{$item->id}", [
            'delivery_address' => '東京都千代田区',
        ]);
        $response->assertSessionHasErrors('payment_method');

        $response = $this->actingAs($buyer)->post("/purchase/{$item->id}", [
            'payment_method' => 'カード払い',
        ]);
        $response->assertSessionHasErrors('delivery_address');
    }

    /** @test */
    public function user_can_change_delivery_address()
    {
        $seller = User::factory()->create();
        $buyer = User::factory()->create([
            'postal_code' => '100-0001',
            'address' => '東京都千代田区',
            'building' => 'テストビル101',
        ]);

        $item = Item::factory()->create([
            'seller_id' => $seller->id,
        ]);

        $response = $this->actingAs($buyer)->post("/purchase/address/{$item->id}", [
            'postal_code' => '150-0001',
            'address' => '東京都渋谷区',
            'building' => '新ビル201',
        ]);

        $response->assertRedirect("/purchase/{$item->id}");

        $this->assertDatabaseHas('users', [
            'id' => $buyer->id,
            'postal_code' => '150-0001',
            'address' => '東京都渋谷区',
            'building' => '新ビル201',
        ]);
    }

    /** @test */
    public function buyer_can_complete_transaction()
    {
        $seller = User::factory()->create();
        $buyer = User::factory()->create();

        $item = Item::factory()->create([
            'seller_id' => $seller->id,
            'buyer_id' => $buyer->id,
            'sold' => true,
            'is_transaction_completed' => false,
        ]);

        $response = $this->actingAs($buyer)->post("/items/{$item->id}/complete");

        $response->assertRedirect("/chat/{$item->id}");
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('items', [
            'id' => $item->id,
            'is_transaction_completed' => true,
        ]);
    }

    /** @test */
    public function only_buyer_can_complete_transaction()
    {
        $seller = User::factory()->create();
        $buyer = User::factory()->create();
        $outsider = User::factory()->create();

        $item = Item::factory()->create([
            'seller_id' => $seller->id,
            'buyer_id' => $buyer->id,
            'sold' => true,
            'is_transaction_completed' => false,
        ]);

        $response = $this->actingAs($seller)->post("/items/{$item->id}/complete");
        $response->assertRedirect();
        $response->assertSessionHas('error');

        $response = $this->actingAs($outsider)->post("/items/{$item->id}/complete");
        $response->assertRedirect();
        $response->assertSessionHas('error');

        $this->assertDatabaseHas('items', [
            'id' => $item->id,
            'is_transaction_completed' => false,
        ]);
    }

    /** @test */
    public function guest_cannot_access_purchase_pages()
    {
        $item = Item::factory()->create();

        $response = $this->get("/purchase/{$item->id}");
        $response->assertRedirect('/login');

        $response = $this->get("/purchase/address/{$item->id}");
        $response->assertRedirect('/login');

        $response = $this->post("/purchase/{$item->id}");
        $response->assertRedirect('/login');
    }
}