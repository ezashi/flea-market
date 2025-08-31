<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Evaluation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class EvaluationController extends Controller
{
  public function store(Request $request, $item_id)
  {
    try {
      DB::beginTransaction();

      $item = Item::findOrFail($item_id);
      $currentUserId = Auth::id();

      if (!$item->is_transaction_completed) {
        return redirect()->back();
      }

      if ($currentUserId !== $item->seller_id && $currentUserId !== $item->buyer_id) {
        return redirect()->back();
      }

      $existingEvaluation = Evaluation::where('item_id', $item_id)
      ->where('evaluator_id', $currentUserId)
      ->first();

      if ($existingEvaluation) {
        return redirect()->back();
      }

      $evaluatedId = ($currentUserId === $item->buyer_id) ? $item->seller_id : $item->buyer_id;

      Evaluation::create([
        'item_id' => $item_id,
        'evaluator_id' => $currentUserId,
        'evaluated_id' => $evaluatedId,
        'rating' => $request->rating,
      ]);

      $totalEvaluations = Evaluation::where('item_id', $item_id)->count();

      DB::commit();

      if ($totalEvaluations >= 2) {
        return redirect()->route('chat.show', $item_id);
      } else {
        if ($currentUserId === $item->buyer_id) {
          $sellerEvaluation = Evaluation::where('item_id', $item_id)
          ->where('evaluator_id', $item->seller_id)
          ->first();

          if (!$sellerEvaluation) {
            session(['show_seller_evaluation_modal' => $item_id]);
          }
        }

        return redirect()->route('chat.show', $item_id);
      }

    } catch (\Exception $e) {
      DB::rollBack();
      \Log::error('Evaluation store failed', [
        'item_id' => $item_id,
        'user_id' => $currentUserId,
        'error' => $e->getMessage()
      ]);
      return redirect()->back();
    }
  }

  public function canEvaluate($item_id)
  {
    $item = Item::findOrFail($item_id);
    $currentUserId = Auth::id();

    if (!$item->is_transaction_completed) {
      return false;
    }

    if ($currentUserId !== $item->seller_id && $currentUserId !== $item->buyer_id) {
      return false;
    }

    $existingEvaluation = Evaluation::where('item_id', $item_id)
    ->where('evaluator_id', $currentUserId)
    ->first();

    return !$existingEvaluation;
  }

  public function getEvaluationStatus($item_id)
  {
    $item = Item::findOrFail($item_id);

    if (!$item->is_transaction_completed) {
      return [
        'buyer_evaluated' => false,
        'seller_evaluated' => false,
        'both_evaluated' => false,
        'transaction_fully_completed' => false
      ];
    }

    $buyerEvaluation = Evaluation::where('item_id', $item_id)
    ->where('evaluator_id', $item->buyer_id)
    ->exists();

    $sellerEvaluation = Evaluation::where('item_id', $item_id)
    ->where('evaluator_id', $item->seller_id)
    ->exists();

    $bothEvaluated = $buyerEvaluation && $sellerEvaluation;

    return [
      'buyer_evaluated' => $buyerEvaluation,
      'seller_evaluated' => $sellerEvaluation,
      'both_evaluated' => $bothEvaluated,
      'transaction_fully_completed' => $bothEvaluated
    ];
  }

  public function getUserEvaluations($userId)
  {
    $evaluations = Evaluation::where('evaluated_id', $userId)
    ->with(['evaluator', 'item'])
    ->orderBy('created_at', 'desc')
    ->get();

    return $evaluations;
  }
}