<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Evaluation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\EvaluationRequest;

class EvaluationController extends Controller
{
  public function store(EvaluationRequest $request, $item_id)
  {
    $item = Item::findOrFail($item_id);
    $currentUserId = Auth::id();

    if (!$item->is_transaction_completed) {
      return redirect()->back();
    }

    if ($currentUserId !== $item->seller_id && $currentUserId !== $item->buyer_id) {
      return redirect()->back();
    }

    $evaluatedId = ($currentUserId === $item->buyer_id) ? $item->seller_id : $item->buyer_id;

    // 既に評価済みかチェック
    $existingEvaluation = Evaluation::where('item_id', $item_id)
    ->where('evaluator_id', $currentUserId)
    ->first();

    if ($existingEvaluation) {
      return redirect()->back();
    }

    Evaluation::create([
      'item_id' => $item_id,
      'evaluator_id' => $currentUserId,
      'evaluated_id' => $evaluatedId,
      'rating' => $request->rating,
    ]);

    return redirect()->back();
  }

  public function canEvaluate($item_id)
  {
    $item = Item::findOrFail($item_id);
    $currentUserId = Auth::id();

    // 取引完了済みでない場合は評価不可
    if (!$item->is_transaction_completed) {
      return false;
    }

    if ($currentUserId !== $item->seller_id && $currentUserId !== $item->buyer_id) {
      return false;
    }

    // 既に評価済みの場合は評価不可
    $existingEvaluation = Evaluation::where('item_id', $item_id)
    ->where('evaluator_id', $currentUserId)
    ->first();

    return !$existingEvaluation;
  }

  /**
   * 評価状況を取得
   */
  public function getEvaluationStatus($item_id)
  {
    $item = Item::findOrFail($item_id);

    if (!$item->is_transaction_completed) {
      return [
        'buyer_evaluated' => false,
        'seller_evaluated' => false,
        'both_evaluated' => false
      ];
    }

    $buyerEvaluation = Evaluation::where('item_id', $item_id)
    ->where('evaluator_id', $item->buyer_id)
    ->exists();

    $sellerEvaluation = Evaluation::where('item_id', $item_id)
    ->where('evaluator_id', $item->seller_id)
    ->exists();

    return [
      'buyer_evaluated' => $buyerEvaluation,
      'seller_evaluated' => $sellerEvaluation,
      'both_evaluated' => $buyerEvaluation && $sellerEvaluation
    ];
  }
}