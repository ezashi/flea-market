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

    // 相手が購入者で、まだ評価していない場合は相手にも評価モーダルを表示
    if ($currentUserId === $item->seller_id) {
      // 出品者が評価した場合、購入者の評価状況をチェック
      $buyerEvaluation = Evaluation::where('item_id', $item_id)
      ->where('evaluator_id', $item->buyer_id)
      ->first();

      if (!$buyerEvaluation) {
        // 購入者がまだ評価していない場合、次回アクセス時に評価モーダルを表示
        session(['buyer_should_evaluate_' . $item_id => true]);
      }
    }

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

  /**
   * 評価一覧を取得（マイページ用）
   */
  public function getUserEvaluations($userId)
  {
    $evaluations = Evaluation::where('evaluated_id', $userId)
    ->with(['evaluator', 'item'])
    ->orderBy('created_at', 'desc')
    ->get();

    return $evaluations;
  }
}