<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Evaluation;
use Illuminate\Support\Str;
use App\Models\ChatMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\ChatMessageRequest;

class ChatController extends Controller
{
  public function show($item_id, Request $request)
  {
    $item = $request->get('item') ?? Item::findOrFail($item_id);
    $currentUserId = Auth::id();

    $messages = ChatMessage::where('item_id', $item_id)
    ->with('sender')
    ->orderBy('created_at', 'asc')
    ->get();

    ChatMessage::where('item_id', $item_id)
    ->where('sender_id', '!=', Auth::id())
    ->where('is_read', false)
    ->update(['is_read' => true]);

    if ($currentUserId === $item->seller_id) {
      $chatPartner = $item->buyer;
    } else {
      $chatPartner = $item->seller;
    }

    $tradingItems = Auth::user()->tradingItems();

    $draftMessage = session("draft_message_{$item_id}", '');

    $canEvaluate = false;
    $showEvaluationModal = false;
    $hasEvaluated = false;
    $partnerHasEvaluated = false;

    if ($item->is_transaction_completed) {
      $myEvaluation = Evaluation::where('item_id', $item_id)
      ->where('evaluator_id', $currentUserId)
      ->first();

      $partnerEvaluation = Evaluation::where('item_id', $item_id)
      ->where('evaluated_id', $currentUserId)
      ->first();

      $hasEvaluated = $myEvaluation !== null;
      $partnerHasEvaluated = $partnerEvaluation !== null;

      $canEvaluate = !$hasEvaluated && ($currentUserId === $item->seller_id || $currentUserId === $item->buyer_id);

      if (session('show_buyer_evaluation_modal') == $item_id && $currentUserId === $item->buyer_id) {
        $showEvaluationModal = true;
        session()->forget('show_buyer_evaluation_modal');
      }

      if ($currentUserId === $item->seller_id && !$hasEvaluated) {
        $showEvaluationModal = true;
      }

      if (session('show_seller_evaluation_modal') == $item_id && $currentUserId === $item->seller_id) {
        $showEvaluationModal = true;
        session()->forget('show_seller_evaluation_modal');
      }
    }

    return view('mypage.trade', compact(
      'item', 'messages', 'chatPartner', 'tradingItems', 'draftMessage',
      'canEvaluate', 'showEvaluationModal', 'hasEvaluated', 'partnerHasEvaluated'
    ));
  }

  public function send(ChatMessageRequest $request, $item_id)
  {
    $item = Item::findOrFail($item_id);
    $currentUserId = Auth::id();

    $messageData = [
      'item_id' => $item_id,
      'sender_id' => $currentUserId,
    ];

    $hasMessage = !empty($request->message);
    $hasImage = $request->hasFile('image');

    if ($hasMessage && $hasImage) {
      $messageData['message_type'] = 'both';
      $messageData['message'] = $request->message;
    } else {
      $messageData['message_type'] = 'text';
      $messageData['message'] = $request->message;
    };

    if ($hasImage) {
      $filename = Str::random(20) . '.' . $request->file('image')->getClientOriginalExtension();
      $request->file('image')->storeAs('images/chat', $filename, 'public');
      $messageData['image_path'] = 'storage/images/chat/' . $filename;
    }

    ChatMessage::create($messageData);

    $item->touch();

    session()->forget("draft_message_{$item_id}");

    return redirect()->route('chat.show', $item_id);
  }

  public function edit($message_id)
  {
    $message = ChatMessage::findOrFail($message_id);

    if ($message->sender_id !== Auth::id()) {
      return redirect()->back();
    }

    return view('mypage.edit', compact('message'));
  }

  public function update(Request $request, $message_id)
  {
    $message = ChatMessage::findOrFail($message_id);

    if ($message->sender_id !== Auth::id()) {
      return redirect()->back();
    }

    $message->update([
      'message' => $request->message,
      'is_edited' => true,
      'edited_at' => now(),
    ]);

    $message->item->touch();

    return redirect()->route('chat.show', $message->item_id);
  }

  public function delete($message_id)
  {
    $message = ChatMessage::findOrFail($message_id);

    if ($message->sender_id !== Auth::id()) {
      return redirect()->back();
    }

    $message->update([
      'is_deleted' => true,
      'deleted_at' => now(),
    ]);

    return redirect()->route('chat.show', $message->item_id);
  }

  public function unreadCounts($item_id = null)
  {
    $currentUserId = Auth::id();

    $unreadBaseQuery = function() use ($currentUserId) {
      return ChatMessage::where('sender_id', '!=', $currentUserId)
      ->where('is_read', false)
      ->where('is_deleted', false);
    };
    $tradingItemsQuery = function() use ($currentUserId) {
      return Item::where(function($query) use ($currentUserId) {
        $query->where('seller_id', $currentUserId)
        ->orWhere('buyer_id', $currentUserId);
      })
      ->where('sold', true);
    };

    if ($item_id) {
      $count = (clone $unreadBaseQuery())
      ->where('item_id', $item_id)
      ->count();

      return response()->json(['count' => $count]);
    }

    $tradingItemIds = $tradingItemsQuery()->pluck('id');

    $unreadCounts = $unreadBaseQuery()
    ->whereIn('item_id', $tradingItemIds)
    ->select('item_id', \DB::raw('COUNT(*) as unread_count'))
    ->groupBy('item_id')
    ->pluck('unread_count', 'item_id')
    ->toArray();

    return $unreadCounts;
  }
}