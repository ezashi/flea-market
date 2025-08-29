@extends('layouts.app')
@section('content')
<div>
  <div>
    <h3>その他の取引中の商品</h3>
    @if($tradingItems->isEmpty())
      <p>取引中の商品はありません</p>
    @else
      @foreach($tradingItems as $tradingItem)
        <div>
          <a href="{{ route('chat.show', $tradingItem->id) }}">
            <div>
              <img src="{{ asset($tradingItem->image) }}" style="width: 200px; height: 200px; object-fit: cover;" alt="{{ $tradingItem->name }}">
              <h5>{{ $tradingItem->name }}</h5>
              <div>
                @if(isset($unreadCounts[$tradingItem->id]) && $unreadCounts[$tradingItem->id] > 0)
                  <span>
                    {{ $unreadCounts[$tradingItem->id] > 99 ? '99+' : $unreadCounts[$tradingItem->id] }}
                  </span>
                @endif
              </div>
            </div>
          </a>
        </div>
      @endforeach
    @endif
  </div>

  <div>
    @if($chatPartner->profile_image)
      <img src="{{ asset($chatPartner->profile_image) }}" style="width: 200px; height: 200px; object-fit: cover;" alt="{{ $chatPartner->name }}">
    @else
      <div>
        <span>{{ strtoupper(substr($chatPartner->name, 0, 1)) }}</span>
      </div>
    @endif
    <h2>「 {{ $chatPartner->name }} 」さんとの取引画面</h2>
    @if(Auth::id() === $item->buyer_id && !$item->is_transaction_completed)
      <form action="{{ route('items.completeTransaction', $item->id) }}" method="POST">
        @csrf
        <button type="submit">取引を完了する</button>
      </form>
    @endif
  </div>

  <div>
    <img src="{{ asset($item->image) }}" alt="{{ $item->name }}" style="width: 200px; height: 200px; object-fit: cover;">
    <h5>{{ $item->name }}</h5>
    <h3>¥{{ number_format($item->price) }}</h3>
  </div>

  <div>
    @if($messages->isNotEmpty())
      @foreach($messages as $message)
        <div>
        @if($message->sender_id !== Auth::id())
          @if($message->sender->profile_image)
            <img src="{{ asset($message->sender->profile_image) }}" style="width: 40px; height: 40px;" alt="{{ $message->sender->name }}">
          @else
            {{ strtoupper(substr($message->sender->name, 0, 1)) }}
          @endif
          <span>{{ $message->sender->name }}</span>
        @endif
        </div>
        <div>
          @if($message->isDeleted())
            <p>このメッセージは削除されました</p>
          @else
            @if($message->message)
              <p>{{ $message->message }}</p>
            @endif
            @if($message->image_path)
              <img src="{{ $message->getImageUrl() }}" alt="send image" style="max-width: 200px;">
            @endif
          @endif
        </div>
        <div>
          @if($message->sender_id === Auth::id() && !$message->isDeleted())
            <a href="{{ route('chat.edit', $message->id) }}">編集</a>

            <form action="{{ route('chat.delete', $message->id) }}" method="POST">
              @csrf
              <button type="submit">削除</button>
            </form>
          @endif
        </div>
      @endforeach
    @endif
  </div>

  <div>
    <form method="POST" action="{{ route('chat.send', $item->id) }}" enctype="multipart/form-data">
      @csrf
      <div>
        <textarea name="message" id="message-input" placeholder="取引メッセージを記入してください">{{ $draftMessage }}</textarea>
        @error('message')
          <div class="error-message">{{ $message }}</div>
        @enderror
        <input type="file" name="image" id="image-input">
        <label for="image-input">
          画像を追加
        </label>
        @error('image')
          <div class="error-message">{{ $message }}</div>
        @enderror
        <button type="submit">
          <img src="{{ asset('image/送信ボタン.png') }}" style="width: 20px; height: 20px; object-fit: cover;" alt="send button"/>
        </button>
      </div>
    </form>
  </div>
</div>

<!-- 評価モーダル -->
@if($item->is_transaction_completed && $canEvaluate && $showEvaluationModal)
  <div id="evaluation-modal" style="display: block;">
    <h3>取引が完了しました</h3>
    <form method="POST" action="{{ route('evaluation.store', $item->id) }}">
      @csrf
      <div>
        @for($i = 1; $i <= 5; $i++)
          <div>
            <label>
              <input type="radio" name="rating" value="{{ $i }}" id="rating{{ $i }}" required>
              @for($j = 1; $j <= 5; $j++)
                <span style="font-size: 20px; color: {{ $j <= $i ? '#ffd700' : '#ddd' }}; margin-right: 2px;">★</span>
              @endfor
            </label>
          </div>
        @endfor
      </div>

      <button type="submit">送信する</button>
    </form>
  </div>
@endif



<style>
#evaluation-modal {
    position: fixed !important;
    top: 0 !important;
    left: 0 !important;
    width: 100vw !important;
    height: 100vh !important;
    z-index: 9999 !important;
    display: block !important;
}

.modal-overlay {
    position: absolute !important;
    top: 0 !important;
    left: 0 !important;
    width: 100% !important;
    height: 100% !important;
    background-color: rgba(0, 0, 0, 0.7) !important;
    display: flex !important;
    justify-content: center !important;
    align-items: center !important;
}

.modal-content {
    background: white !important;
    padding: 30px !important;
    border-radius: 10px !important;
    max-width: 500px !important;
    width: 90% !important;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3) !important;
    position: relative !important;
}

.modal-content h3 {
    text-align: center !important;
    margin-bottom: 20px !important;
    color: #333 !important;
}

.rating-options {
    margin: 20px 0 !important;
}

.rating-item {
    margin: 15px 0 !important;
    padding: 10px !important;
    border: 1px solid #ddd !important;
    border-radius: 5px !important;
    cursor: pointer !important;
}

.rating-item:hover {
    background-color: #f5f5f5 !important;
}

.rating-item label {
    display: flex !important;
    align-items: center !important;
    cursor: pointer !important;
    width: 100% !important;
}

.rating-item input[type="radio"] {
    margin-right: 15px !important;
}

.stars {
    margin-right: 15px !important;
}

.star {
    font-size: 20px !important;
    color: #ddd !important;
    margin-right: 2px !important;
}

.star.filled {
    color: #ffd700 !important;
}

.rating-text {
    font-weight: 500 !important;
    color: #333 !important;
}

.modal-buttons {
    text-align: center !important;
    margin-top: 25px !important;
}

.submit-btn {
    background-color: #007bff !important;
    color: white !important;
    padding: 12px 25px !important;
    border: none !important;
    border-radius: 5px !important;
    cursor: pointer !important;
    margin-right: 10px !important;
    font-size: 16px !important;
}

.submit-btn:hover {
    background-color: #0056b3 !important;
}

.close-btn {
    background-color: #6c757d !important;
    color: white !important;
    padding: 12px 25px !important;
    border: none !important;
    border-radius: 5px !important;
    cursor: pointer !important;
    font-size: 16px !important;
}

.close-btn:hover {
    background-color: #545b62 !important;
}
</style>

<script>
function closeModal() {
    document.getElementById('evaluation-modal').style.display = 'none';
}

// モーダル外クリックで閉じる
document.addEventListener('DOMContentLoaded', function() {
    const modal = document.getElementById('evaluation-modal');
    const overlay = document.querySelector('.modal-overlay');
    
    if (overlay) {
        overlay.addEventListener('click', function(e) {
            if (e.target === overlay) {
                closeModal();
            }
        });
    }
});
</script>
@endsection