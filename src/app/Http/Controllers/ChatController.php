<?php

namespace App\Http\Controllers;

use App\Models\Item;
use Illuminate\Support\Str;
use App\Models\ChatMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\ChatMessageRequest;

class ChatController extends Controller
{
  public function show($item_id)
  {
    $item = Item::findOrFail($item_id);
    $currentUserId = Auth::id();

    $messages = ChatMessage::where('item_id', $item_id)
      ->notDeleted()
      ->with('sender')
      ->orderBy('created_at', 'asc')
      ->get();

    ChatMessage::where('item_id', $item_id)
      ->where('sender_id', '!=', Auth::id())
      ->where('is_read', false)
      ->update(['is_read' => true]);

    if ($currentUserId === $item->seller_id) {
      $chatPartner = $item->buyer; // 出品者の場合は購入者を返す
    } else {
      $chatPartner = $item->seller; // 購入者の場合は出品者を返す
    }

    $tradingItems = Auth::user()->tradingItems();

    $draftMessage = session("draft_message_{$item_id}", '');

    return view('mypage.trade', compact('item', 'messages', 'chatPartner', 'tradingItems', 'draftMessage'));
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
    } elseif ($hasMessage) {
      $messageData['message_type'] = 'text';
      $messageData['message'] = $request->message;
    } else {
      return back();
    }

    if ($hasImage) {
      $filename = Str::random(20) . '.' . $request->file('image')->getClientOriginalExtension();
      $request->file('image')->storeAs('images/chat', $filename, 'public');
      $messageData['image_path'] = 'storage/images/chat/' . $filename;
    }

    ChatMessage::create($messageData);

    session()->forget("draft_message_{$item_id}");

    return redirect()->route('chat.show', $item_id);
  }

  public function saveDraft(Request $request, $item_id)
  {
    $message = $request->input('message', '');
    session(["draft_message_{$item_id}" => $message]);

    return response()->json(['success' => true]);
  }
}