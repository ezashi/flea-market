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

    return view('mypage.trade', compact('item', 'messages', 'chatPartner'));
  }

  public function send(ChatMessageRequest $request, $item_id)
  {
    $item = Item::findOrFail($item_id);
    $currentUserId = Auth::id();

    if ($request->hasFile('image')) {
      $filename = Str::random(20) . '.' . $request->file('image')->getClientOriginalExtension();
      $request->file('image')->storeAs('images/chat', $filename, 'public');
      $imagePath = 'storage/images/chat/' . $filename;

      ChatMessage::create([
        'item_id' => $item_id,
        'sender_id' => Auth::id(),
        'image_path' => $imagePath,
        'message_type' => 'image',
      ]);
    } else{
      ChatMessage::create([
            'item_id' => $item_id,
            'sender_id' => Auth::id(),
            'message' => $request->message,
            'message_type' => 'text',
        ]);
    }

    return redirect()->route('chat.show', $item_id);
  }
}