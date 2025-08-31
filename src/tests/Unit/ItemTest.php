<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\User;
use App\Models\Item;
use App\Models\Evaluation;
use App\Models\Like;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ItemTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->artisan('migrate:fresh');
    }

    /** @test */
    public function item_can_check_if_liked_by_user()
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();
        $item = Item::factory()->create();

        Like::factory()->create([
            'user_id' => $user->id,
            'item_id' => $item->id,
        ]);

        $this->assertTrue($item->isLikedBy($user->id));
        $this->assertFalse($item->isLikedBy($otherUser->id));
    }

    /** @test */
    public function item_can_check_if_evaluated_by_user()
    {
        $seller = User::factory()->create();
        $buyer = User::factory()->create();
        $item = Item::factory()->create([
            'seller_id' => $seller->id,
            'buyer_id' => $buyer->id,
        ]);

        Evaluation::factory()->create([
            'item_id' => $item->id,
            'evaluator_id' => $buyer->id,
            'evaluated_id' => $seller->id,
        ]);

        $this->assertTrue($item->isEvaluatedBy($buyer->id));
        $this->assertFalse($item->isEvaluatedBy($seller->id));
    }

    /** @test */
    public function item_can_check_if_both_evaluated()
    {
        $seller = User::factory()->create();
        $buyer = User::factory()->create();
        $item = Item::factory()->create([
            'seller_id' => $seller->id,
            'buyer_id' => $buyer->id,
        ]);

        Evaluation::factory()->create([
            'item_id' => $item->id,
            'evaluator_id' => $buyer->id,
            'evaluated_id' => $seller->id,
        ]);

        $this->assertFalse($item->isBothEvaluated());

        Evaluation::factory()->create([
            'item_id' => $item->id,
            'evaluator_id' => $seller->id,
            'evaluated_id' => $buyer->id,
        ]);

        $this->assertTrue($item->isBothEvaluated());
    }

    /** @test */
    public function item_trading_status_for_user_before_transaction_completion()
    {
        $seller = User::factory()->create();
        $buyer = User::factory()->create();
        $item = Item::factory()->create([
            'seller_id' => $seller->id,
            'buyer_id' => $buyer->id,
            'sold' => true,
            'is_transaction_completed' => false,
        ]);

        $this->assertTrue($item->isTradingFor($seller->id));
        $this->assertTrue($item->isTradingFor($buyer->id));
    }

    /** @test */
    public function item_trading_status_after_individual_evaluation()
    {
        $seller = User::factory()->create();
        $buyer = User::factory()->create();
        $item = Item::factory()->create([
            'seller_id' => $seller->id,
            'buyer_id' => $buyer->id,
            'sold' => true,
            'is_transaction_completed' => true,
        ]);

        $this->assertTrue($item->isTradingFor($seller->id));
        $this->assertTrue($item->isTradingFor($buyer->id));

        Evaluation::factory()->create([
            'item_id' => $item->id,
            'evaluator_id' => $buyer->id,
            'evaluated_id' => $seller->id,
        ]);

        $this->assertTrue($item->isTradingFor($seller->id));
        $this->assertFalse($item->isTradingFor($buyer->id));

        // 売り手も評価
        Evaluation::factory()->create([
            'item_id' => $item->id,
            'evaluator_id' => $seller->id,
            'evaluated_id' => $buyer->id,
        ]);

        $this->assertFalse($item->isTradingFor($seller->id));
        $this->assertFalse($item->isTradingFor($buyer->id));
    }

    /** @test */
    public function item_is_fully_completed()
    {
        $seller = User::factory()->create();
        $buyer = User::factory()->create();
        $item = Item::factory()->create([
            'seller_id' => $seller->id,
            'buyer_id' => $buyer->id,
            'sold' => true,
            'is_transaction_completed' => true,
        ]);

        $this->assertFalse($item->isFullyCompleted());

        Evaluation::factory()->create([
            'item_id' => $item->id,
            'evaluator_id' => $buyer->id,
            'evaluated_id' => $seller->id,
        ]);
        Evaluation::factory()->create([
            'item_id' => $item->id,
            'evaluator_id' => $seller->id,
            'evaluated_id' => $buyer->id,
        ]);

        $this->assertTrue($item->isFullyCompleted());
    }

    /** @test */
    public function item_pending_evaluation_status()
    {
        $seller = User::factory()->create();
        $buyer = User::factory()->create();
        $item = Item::factory()->create([
            'seller_id' => $seller->id,
            'buyer_id' => $buyer->id,
            'sold' => true,
            'is_transaction_completed' => true,
        ]);

        $this->assertTrue($item->isPendingEvaluation($seller->id));
        $this->assertTrue($item->isPendingEvaluation($buyer->id));

        Evaluation::factory()->create([
            'item_id' => $item->id,
            'evaluator_id' => $buyer->id,
            'evaluated_id' => $seller->id,
        ]);

        $this->assertTrue($item->isPendingEvaluation($seller->id));
        $this->assertFalse($item->isPendingEvaluation($buyer->id));
    }

    /** @test */
    public function non_participant_is_not_trading()
    {
        $seller = User::factory()->create();
        $buyer = User::factory()->create();
        $outsider = User::factory()->create();

        $item = Item::factory()->create([
            'seller_id' => $seller->id,
            'buyer_id' => $buyer->id,
            'sold' => true,
        ]);

        $this->assertFalse($item->isTradingFor($outsider->id));
    }
}