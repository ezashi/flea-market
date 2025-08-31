<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Item;
use App\Models\Evaluation;
use Illuminate\Foundation\Testing\RefreshDatabase;

class EvaluationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->artisan('migrate:fresh');
        $this->seed();

        $this->withoutMiddleware(\App\Http\Middleware\ChatMessage::class);
    }

    /** @test */
    public function user_can_submit_evaluation_after_transaction_completion()
    {
        $seller = User::factory()->create();
        $buyer = User::factory()->create();

        $item = Item::factory()->create([
            'seller_id' => $seller->id,
            'buyer_id' => $buyer->id,
            'sold' => true,
            'is_transaction_completed' => true,
        ]);

        $response = $this->actingAs($buyer)->post("/evaluation/{$item->id}", [
            'rating' => 5,
        ]);

        $response->assertRedirect("/");

        $this->assertDatabaseHas('evaluations', [
            'item_id' => $item->id,
            'evaluator_id' => $buyer->id,
            'evaluated_id' => $seller->id,
            'rating' => 5,
        ]);
    }

    /** @test */
    public function user_cannot_evaluate_twice()
    {
        $seller = User::factory()->create();
        $buyer = User::factory()->create();
        $item = Item::factory()->create([
            'seller_id' => $seller->id,
            'buyer_id' => $buyer->id,
            'sold' => true,
            'is_transaction_completed' => true,
        ]);

        Evaluation::factory()->create([
            'item_id' => $item->id,
            'evaluator_id' => $buyer->id,
            'evaluated_id' => $seller->id,
            'rating' => 4,
        ]);

        $response = $this->actingAs($buyer)->post("/evaluation/{$item->id}", [
            'rating' => 5,
        ]);

        $response->assertRedirect();

        $this->assertEquals(1, Evaluation::where('item_id', $item->id)
            ->where('evaluator_id', $buyer->id)->count());
    }

    /** @test */
    public function user_cannot_evaluate_before_transaction_completion()
    {
        $seller = User::factory()->create();
        $buyer = User::factory()->create();
        $item = Item::factory()->create([
            'seller_id' => $seller->id,
            'buyer_id' => $buyer->id,
            'sold' => true,
            'is_transaction_completed' => false,
        ]);

        $response = $this->actingAs($buyer)->post("/evaluation/{$item->id}", [
            'rating' => 5,
        ]);

        $response->assertRedirect();

        $this->assertDatabaseMissing('evaluations', [
            'item_id' => $item->id,
            'evaluator_id' => $buyer->id,
        ]);
    }

    /** @test */
    public function non_participant_cannot_evaluate()
    {
        $seller = User::factory()->create();
        $buyer = User::factory()->create();
        $outsider = User::factory()->create();

        $item = Item::factory()->create([
            'seller_id' => $seller->id,
            'buyer_id' => $buyer->id,
            'sold' => true,
            'is_transaction_completed' => true,
        ]);

        $response = $this->actingAs($outsider)->post("/evaluation/{$item->id}", [
            'rating' => 5,
        ]);

        $response->assertRedirect();

        $this->assertDatabaseMissing('evaluations', [
            'item_id' => $item->id,
            'evaluator_id' => $outsider->id,
        ]);
    }
}