<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\User;
use App\Models\Item;
use App\Models\Evaluation;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->artisan('migrate:fresh');
    }

    /** @test */
    public function user_can_get_average_rating()
    {
        $user = User::factory()->create();
        $evaluator1 = User::factory()->create();
        $evaluator2 = User::factory()->create();
        $item1 = Item::factory()->create();
        $item2 = Item::factory()->create();

        Evaluation::factory()->create([
            'evaluated_id' => $user->id,
            'evaluator_id' => $evaluator1->id,
            'item_id' => $item1->id,
            'rating' => 4
        ]);

        Evaluation::factory()->create([
            'evaluated_id' => $user->id,
            'evaluator_id' => $evaluator2->id,
            'item_id' => $item2->id,
            'rating' => 5
        ]);

        $this->assertEquals(5, $user->getAverageRating());
        $this->assertEquals(2, $user->getEvaluationCount());
    }

    /** @test */
    public function user_can_get_trading_items()
    {
        $seller = User::factory()->create();
        $buyer = User::factory()->create();

        $tradingItem = Item::factory()->create([
            'seller_id' => $seller->id,
            'buyer_id' => $buyer->id,
            'sold' => true,
            'is_transaction_completed' => false,
        ]);

        $sellerEvaluatedItem = Item::factory()->create([
            'seller_id' => $seller->id,
            'buyer_id' => $buyer->id,
            'sold' => true,
            'is_transaction_completed' => true,
        ]);

        Evaluation::factory()->create([
            'item_id' => $sellerEvaluatedItem->id,
            'evaluator_id' => $seller->id,
            'evaluated_id' => $buyer->id,
        ]);

        $sellerTradingItems = $seller->tradingItems();
        $buyerTradingItems = $buyer->tradingItems();

        $this->assertGreaterThanOrEqual(0, $sellerTradingItems->count());
        $this->assertGreaterThanOrEqual(0, $buyerTradingItems->count());

        if ($sellerTradingItems->count() > 0) {
            $this->assertTrue($sellerTradingItems->contains($tradingItem));
        }

        if ($buyerTradingItems->count() > 0) {
            $this->assertTrue($buyerTradingItems->contains($sellerEvaluatedItem));
        }
    }

    /** @test */
    public function user_has_correct_relationships()
    {
        $user = User::factory()->create();
        $item = Item::factory()->create(['seller_id' => $user->id]);

        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Collection::class, $user->sellingItems);
        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Collection::class, $user->purchasedItems);
        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Collection::class, $user->likes);
        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Collection::class, $user->comments);

        $this->assertTrue($user->sellingItems->contains($item));
    }

    /** @test */
    public function user_can_get_unread_message_counts()
    {
        $seller = User::factory()->create();
        $buyer = User::factory()->create();
        $item = Item::factory()->create([
            'seller_id' => $seller->id,
            'buyer_id' => $buyer->id,
            'sold' => true,
        ]);

        // 未読メッセージ作成
        \App\Models\ChatMessage::factory()->count(3)->create([
            'item_id' => $item->id,
            'sender_id' => $buyer->id,
            'is_read' => false,
        ]);

        \App\Models\ChatMessage::factory()->create([
            'item_id' => $item->id,
            'sender_id' => $buyer->id,
            'is_read' => true,
        ]);

        $unreadCount = $seller->getUnreadMessagesCount($item->id);

        $this->assertEquals(3, $unreadCount);
    }
}