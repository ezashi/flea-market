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

    if (!$item->sold) {
      return redirect()->route('index');
    }

    if ($currentUserId !== $item->seller_id && $currentUserId !== $item->buyer_id) {
      return redirect()->route('index');
    }

    $evaluatedId = ($currentUserId === $item->buyer_id) ? $item->seller_id : $item->buyer_id;

    // 既に評価済みかチェック
    $existingEvaluation = Evaluation::where('item_id', $item_id)
    ->where('evaluator_id', $currentUserId)
    ->first();
    if ($existingEvaluation) {
      return redirect()->route('index');
    }

    Evaluation::create([
      'item_id' => $item_id,
      'evaluator_id' => $currentUserId,
      'evaluated_id' => $evaluatedId,
      'rating' => $request->rating,
    ]);

    return redirect()->route('index');
  }

  public function canEvaluate($item_id)
  {
    $item = Item::findOrFail($item_id);
    $currentUserId = Auth::id();

    // 取引完了済みでない場合は評価不可
    if (!$item->sold) {
      return false;
    }

    // 既に評価済みの場合は評価不可
    $existingEvaluation = Evaluation::where('item_id', $item_id)
    ->where('evaluator_id', $currentUserId)
    ->first();

    return !$existingEvaluation;
  }
}