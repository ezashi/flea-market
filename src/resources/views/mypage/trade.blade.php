@extends('layouts.app')
@section('content')
<style>
  .trade-chat-container {
    display: flex;
    min-height: 100vh;
    background-color: #f5f5f5;
  }

  .sidebar {
    width: 230px;
    background-color: #666;
    color: white;
    padding: 20px 15px;
    position: fixed;
    height: 100vh;
    top: 0;
    left: 0;
    overflow-y: auto;
  }

  .sidebar-title {
    font-size: 18px;
    font-weight: bold;
    margin-bottom: 20px;
    color: white;
  }

  .other-trades {
    margin-bottom: 30px;
  }

  .other-trade-item {
    display: block;
    color: white;
    text-decoration: none;
    margin-bottom: 10px;
    padding: 10px;
    border-radius: 5px;
    transition: background-color 0.2s;
  }

  .other-trade-item:hover {
    background-color: rgba(255, 255, 255, 0.1);
  }

  .main-content {
    flex: 1;
    margin-left: 230px;
    display: flex;
    flex-direction: column;
  }

  .chat-header {
    background-color: white;
    padding: 20px 30px;
    border-bottom: 1px solid #ddd;
    display: flex;
    align-items: center;
    justify-content: space-between;
  }

  .chat-user-info {
    display: flex;
    align-items: center;
    gap: 15px;
  }

  .chat-user-avatar {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    background-color: #ccc;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #666;
    font-size: 20px;
    font-weight: bold;
    overflow: hidden;
  }

  .chat-user-avatar img {
    width: 100%;
    height: 100%;
    object-fit: cover;
  }

  .chat-title {
    font-size: 20px;
    font-weight: bold;
    color: #333;
  }

  .complete-transaction-btn {
    background-color: #ff6b6b;
    color: white;
    border: none;
    padding: 12px 24px;
    border-radius: 25px;
    font-size: 16px;
    font-weight: 600;
    cursor: pointer;
    transition: background-color 0.2s;
  }

  .complete-transaction-btn:hover {
    background-color: #e55555;
  }

  .product-info-section {
    background-color: white;
    padding: 30px;
    border-bottom: 1px solid #ddd;
    display: flex;
    align-items: center;
    gap: 30px;
  }

  .product-image {
    width: 150px;
    height: 150px;
    background-color: #ccc;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #666;
    font-size: 16px;
    border-radius: 8px;
    overflow: hidden;
    flex-shrink: 0;
  }

  .product-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
  }

  .product-details {
    flex: 1;
  }

  .product-name {
    font-size: 24px;
    font-weight: bold;
    color: #333;
    margin-bottom: 10px;
  }

  .product-price {
    font-size: 20px;
    font-weight: bold;
    color: #333;
  }

  .chat-messages {
    flex: 1;
    padding: 30px;
    background-color: #f5f5f5;
    overflow-y: auto;
    min-height: 400px;
  }

  .message-item {
    display: flex;
    margin-bottom: 20px;
    align-items: flex-start;
    gap: 15px;
  }

  .message-item.own {
    flex-direction: row-reverse;
  }

  .message-avatar {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background-color: #ccc;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #666;
    font-size: 16px;
    font-weight: bold;
    overflow: hidden;
    flex-shrink: 0;
  }

  .message-avatar img {
    width: 100%;
    height: 100%;
    object-fit: cover;
  }

  .message-content {
    max-width: 60%;
    position: relative;
  }

  .message-sender {
    font-size: 14px;
    color: #666;
    margin-bottom: 5px;
  }

  .message-bubble {
    background-color: #e9e9e9;
    padding: 15px 20px;
    border-radius: 20px;
    font-size: 16px;
    line-height: 1.4;
    color: #333;
    position: relative;
  }

  .message-item.own .message-bubble {
    background-color: #007bff;
    color: white;
  }

  .message-actions {
    display: flex;
    gap: 10px;
    margin-top: 8px;
    font-size: 12px;
  }

  .message-actions a,
  .message-actions button {
    color: #666;
    text-decoration: none;
    background: none;
    border: none;
    cursor: pointer;
    padding: 2px 5px;
  }

  .message-actions a:hover,
  .message-actions button:hover {
    color: #333;
  }

  .deleted-message {
    background-color: #f0f0f0 !important;
    color: #999 !important;
    font-style: italic;
  }

  .message-image {
    max-width: 200px;
    border-radius: 10px;
    margin-top: 10px;
  }

  .chat-input-section {
    background-color: white;
    padding: 20px 30px;
    border-top: 1px solid #ddd;
  }

  .chat-input-form {
    display: flex;
    align-items: flex-end;
    gap: 15px;
  }

  .chat-input-main {
    flex: 1;
    position: relative;
  }

  .chat-textarea {
    width: 100%;
    min-height: 50px;
    max-height: 120px;
    padding: 15px 20px;
    border: 2px solid #ddd;
    border-radius: 25px;
    font-size: 16px;
    outline: none;
    resize: none;
    transition: border-color 0.2s;
  }

  .chat-textarea:focus {
    border-color: #ff6b6b;
  }

  .chat-input-actions {
    display: flex;
    align-items: center;
    gap: 10px;
  }

  .image-upload-btn {
    background-color: white;
    color: #ff6b6b;
    border: 2px solid #ff6b6b;
    padding: 12px 20px;
    border-radius: 25px;
    font-size: 14px;
    cursor: pointer;
    transition: all 0.2s;
    text-decoration: none;
    display: inline-block;
  }

  .image-upload-btn:hover {
    background-color: #ff6b6b;
    color: white;
  }

  .send-btn {
    background-color: #007bff;
    color: white;
    border: none;
    width: 50px;
    height: 50px;
    border-radius: 50%;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: background-color 0.2s;
  }

  .send-btn:hover {
    background-color: #0056b3;
  }

  .send-icon {
    width: 20px;
    height: 20px;
    fill: white;
  }

  .file-input {
    display: none;
  }

  /* Evaluation Modal */
  .evaluation-modal {
    position: fixed;
    top: 0;
    left: 0;
    width: 100vw;
    height: 100vh;
    background-color: rgba(0, 0, 0, 0.7);
    display: none;
    align-items: center;
    justify-content: center;
    z-index: 9999;
  }

  .evaluation-modal.show {
    display: flex;
  }

  .modal-content {
    background: white;
    padding: 40px;
    border-radius: 15px;
    max-width: 500px;
    width: 90%;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
    text-align: center;
  }

  .modal-title {
    font-size: 24px;
    font-weight: bold;
    color: #333;
    margin-bottom: 20px;
  }

  .modal-subtitle {
    font-size: 16px;
    color: #666;
    margin-bottom: 30px;
  }

  .rating-options {
    margin: 30px 0;
  }

  .rating-item {
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 15px 0;
    cursor: pointer;
    transition: transform 0.2s;
  }

  .rating-item:hover {
    transform: scale(1.05);
  }

  .rating-input {
    display: none;
  }

  .rating-stars {
    display: flex;
    gap: 5px;
    font-size: 30px;
  }

  .star {
    color: #ddd;
    transition: color 0.2s;
  }

  .star.filled {
    color: #ffd700;
  }

  .rating-input:checked + .rating-stars .star {
    color: #ffd700;
  }

  .modal-submit-btn {
    background-color: #ff6b6b;
    color: white;
    border: none;
    padding: 15px 30px;
    border-radius: 25px;
    font-size: 16px;
    font-weight: 600;
    cursor: pointer;
    transition: background-color 0.2s;
    margin-top: 20px;
  }

  .modal-submit-btn:hover {
    background-color: #e55555;
  }
</style>

<div class="trade-chat-container">
  <div class="sidebar">
    <h3 class="sidebar-title">その他の取引</h3>
    <div class="other-trades">
      @if($tradingItems->isEmpty())
        <p style="color: #ccc; font-size: 14px;">取引中の商品はありません</p>
      @else
        @foreach($tradingItems as $tradingItem)
          <a href="{{ route('chat.show', $tradingItem->id) }}" class="other-trade-item">
            {{ $tradingItem->name }}
            @if(isset($unreadCounts[$tradingItem->id]) && $unreadCounts[$tradingItem->id] > 0)
              <span style="background: #ff6b6b; color: white; border-radius: 10px; padding: 2px 6px; font-size: 12px; margin-left: 5px;">
                {{ $unreadCounts[$tradingItem->id] > 99 ? '99+' : $unreadCounts[$tradingItem->id] }}
              </span>
            @endif
          </a>
        @endforeach
      @endif
    </div>
  </div>

  <div class="main-content">
    <div class="chat-header">
      <div class="chat-user-info">
        <div class="chat-user-avatar">
          @if($chatPartner->profile_image)
            <img src="{{ asset($chatPartner->profile_image) }}" alt="{{ $chatPartner->name }}">
          @else
            {{ strtoupper(substr($chatPartner->name, 0, 1)) }}
          @endif
        </div>
        <h2 class="chat-title">「{{ $chatPartner->name }}」さんとの取引画面</h2>
      </div>
      @if(Auth::id() === $item->buyer_id && !$item->is_transaction_completed)
        <form action="{{ route('items.completeTransaction', $item->id) }}" method="POST">
          @csrf
          <button type="submit" class="complete-transaction-btn">取引を完了する</button>
        </form>
      @endif
    </div>

    <div class="product-info-section">
      <div class="product-image">
        @if($item->image)
          <img src="{{ asset($item->image) }}" alt="{{ $item->name }}">
        @else
          商品画像
        @endif
      </div>
      <div class="product-details">
        <h3 class="product-name">{{ $item->name }}</h3>
        <div class="product-price">¥{{ number_format($item->price) }}</div>
      </div>
    </div>

    <div class="chat-messages">
      @if($messages->isNotEmpty())
        @foreach($messages as $message)
          <div class="message-item {{ $message->sender_id === Auth::id() ? 'own' : '' }}">
            @if($message->sender_id !== Auth::id())
              <div class="message-avatar">
                @if($message->sender->profile_image)
                  <img src="{{ asset($message->sender->profile_image) }}" alt="{{ $message->sender->name }}">
                @else
                  {{ strtoupper(substr($message->sender->name, 0, 1)) }}
                @endif
              </div>
            @endif

            <div class="message-content">
              @if($message->sender_id !== Auth::id())
                <div class="message-sender">{{ $message->sender->name }}</div>
              @else
                <div class="message-sender">ユーザー名</div>
              @endif

              <div class="message-bubble {{ $message->isDeleted() ? 'deleted-message' : '' }}">
                @if($message->isDeleted())
                  このメッセージは削除されました
                @else
                  @if($message->message)
                    {{ $message->message }}
                  @endif
                  @if($message->image_path)
                    <img src="{{ $message->getImageUrl() }}" alt="送信画像" class="message-image">
                  @endif
                @endif
              </div>

              @if($message->sender_id === Auth::id() && !$message->isDeleted())
                <div class="message-actions">
                  <a href="{{ route('chat.edit', $message->id) }}">編集</a>
                  <form action="{{ route('chat.delete', $message->id) }}" method="POST" style="display: inline;">
                    @csrf
                    <button type="submit">削除</button>
                  </form>
                </div>
              @endif
            </div>

            @if($message->sender_id === Auth::id())
              <div class="message-avatar">
                @if(Auth::user()->profile_image)
                  <img src="{{ asset(Auth::user()->profile_image) }}" alt="{{ Auth::user()->name }}">
                @else
                  {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                @endif
              </div>
            @endif
          </div>
        @endforeach
      @endif
    </div>

    <div class="chat-input-section">
      <form method="POST" action="{{ route('chat.send', $item->id) }}" enctype="multipart/form-data" class="chat-input-form">
        @csrf
        <div class="chat-input-main">
          <textarea name="message" class="chat-textarea" placeholder="取引メッセージを記入してください"rows="1">
            {{ $draftMessage }}
          </textarea>
          @error('message')
            <div class="error-message">{{ $message }}</div>
          @enderror
        </div>

        <div class="chat-input-actions">
          <input type="file" name="image" id="image-input" class="file-input" accept="image/*">
          <label for="image-input" class="image-upload-btn">画像を追加</label>
          @error('image')
            <div class="error-message">{{ $message }}</div>
          @enderror

          <button type="submit" class="send-btn">
            <svg class="send-icon" viewBox="0 0 24 24">
              <path d="M2 21l21-9L2 3v7l15 2-15 2v7z"/>
            </svg>
          </button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- 評価モーダル -->
@if($item->is_transaction_completed && $canEvaluate && $showEvaluationModal)
  <div id="evaluation-modal" class="evaluation-modal show">
    <div class="modal-content">
      <h3 class="modal-title">取引が完了しました。</h3>
      <p class="modal-subtitle">今回の取引相手はどうでしたか？</p>

      <form method="POST" action="{{ route('evaluation.store', $item->id) }}">
        @csrf
        <div class="rating-options">
          @for($i = 1; $i <= 5; $i++)
            <label class="rating-item">
              <input type="radio" name="rating" value="{{ $i }}" class="rating-input" required>
              <div class="rating-stars">
                @for($j = 1; $j <= 5; $j++)
                  <span class="star {{ $j <= $i ? 'filled' : '' }}">★</span>
                @endfor
              </div>
            </label>
          @endfor
        </div>

        <button type="submit" class="modal-submit-btn">送信する</button>
      </form>
    </div>
  </div>
@endif
@endsection