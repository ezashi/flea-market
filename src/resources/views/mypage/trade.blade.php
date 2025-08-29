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
                  {{ $unreadCounts[$tradingItem->id] > 99 ? '99+' : $unreadCounts[$tradingItem->id] }}
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
@if($showEvaluationModal && $canEvaluate)
  <div id="evaluation-modal" style="display: block;">
    <h3>取引が完了しました</h3>
    <form method="POST" action="{{ route('evaluation.store', $item->id) }}">
      @csrf
      <p>今回の取引相手はどうでしたか？</p>
      <div>
        @for($i = 1; $i <= 5; $i++)
          <input type="radio" name="rating" value="{{ $i }}" id="rating{{ $i }}" required>
          <label for="rating{{ $i }}" style="cursor: pointer; margin-left: 10px;">
            @for($j = 1; $j <= 5; $j++)
              @if($j <= $i)
                <span style="color: #ffd700;">★</span>
              @else
                <span style="color: #ddd;">☆</span>
              @endif
            @endfor
            ({{ $i }}点)
          </label>
        @endfor
      </div>
      <button type="submit">送信する</button>
    </form>
  </div>
@endif

<script>
// 軽量な下書き保存機能（リアルタイム保存は避けてページ離脱時のみ）
function saveDraft(textarea) {
  const message = textarea.value;
  const itemId = {{ $item->id }};

  // ページ離脱時に保存
  window.addEventListener('beforeunload', function() {
    if (message.trim() !== '') {
      fetch(`/chat/${itemId}/draft`, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({ message: message }),
        keepalive: true // ページ離脱時でも送信を完了させる
      });
    }
  });
}

// 評価モーダルのラジオボタンハイライト
document.addEventListener('DOMContentLoaded', function() {
  const radioButtons = document.querySelectorAll('input[name="rating"]');

  radioButtons.forEach(radio => {
    radio.addEventListener('change', function() {
      // 選択時の視覚的フィードバック
      const selectedValue = this.value;
      const label = this.nextElementSibling;
      if (label) {
        label.style.fontWeight = 'bold';
        label.style.color = '#007bff';
      }

      // 他の選択肢をリセット
      radioButtons.forEach(otherRadio => {
        if (otherRadio !== this) {
          const otherLabel = otherRadio.nextElementSibling;
          if (otherLabel) {
            otherLabel.style.fontWeight = 'normal';
            otherLabel.style.color = 'black';
          }
        }
      });
    });
  });
});
</script>
@endsection