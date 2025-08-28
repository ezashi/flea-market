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
              <div>
                @if(isset($unreadCounts[$tradingItem->id]) && $unreadCounts[$tradingItem->id] > 0)
                  {{ $unreadCounts[$tradingItem->id] > 99 ? '99+' : $unreadCounts[$tradingItem->id] }}
                @endif
              </div>
              <img src="{{ asset($tradingItem->image) }}" alt="{{ $tradingItem->name }}">
              <h5>{{ $tradingItem->name }}</h5>
            </div>
          </a>
        </div>
      @endforeach
    @endif
  </div>

  <div>
    @if($chatPartner->profile_image)
      <img src="{{ asset($chatPartner->profile_image) }}" style="max-width: 100px;" alt="{{ $chatPartner->name }}">
    @else
      <div>
        <span>{{ strtoupper(substr($chatPartner->name, 0, 1)) }}</span>
      </div>
    @endif
    <h2>「 {{ $chatPartner->name }} 」さんとの取引画面</h2>
    @if(Auth::id() === $item->buyer_id && !$item->sold)
      <form action="{{ route('items.completeTransaction', $item->id) }}" method="POST">
        @csrf
        <button type="submit">取引を完了する</button>
      </form>
    @endif
  </div>

  <div>
    <img src="{{ asset($item->image) }}" alt="{{ $item->name }}" style="height: 200px;">
    <h5>{{ $item->name }}</h5>
    <h3>¥{{ number_format($item->price) }}</h3>
  </div>

  <div>
    @if($messages->isNotEmpty())
      @foreach($messages as $message)
        <div>
        @if($message->sender_id !== Auth::id())
          @if($message->sender->profile_image)
            <img src="{{ asset($message->sender->profile_image) }}" alt="{{ $message->sender->name }}">
          @else
            {{ strtoupper(substr($message->sender->name, 0, 1)) }}
          @endif
          <span>{{ $message->sender->name }}</span>
        @endif
        </div>
        <div>
          @if($message->is_deleted())
            <p>このメッセージは削除されました</p>
          @else
            @if($message->message)
              <p>{{ $message->message }}</p>
            @endif
            @if($message->image_path)
              <img src="{{ $message->getImageUrl() }}" alt="send image" onclick="openImageModal(this.src)">
            @endif
          @endif
        </div>
        <div>
          @if($message->sender_id === Auth::id() && !$message->isDeleted())
            <button onclick="editMessage({{ $message->id }}, '{{ addslashes($message->message) }}')">編集</button>
            <button onclick="deleteMessage({{ $message->id }})">削除</button>
          @endif
        </div>
      @endforeach
    @endif
  </div>

  <div>
    <form method="POST" action="{{ route('chat.send', $item->id) }}">
      @csrf
      <div>
        <textarea name="message" id="message-input" placeholder="取引メッセージを記入してください" onkeyup="saveDraft()">
          {{ $draftMessage }}
        </textarea>
        @error('message')
          <div class="error-message">{{ $message }}</div>
        @enderror
        <input type="file" name="image" id="image-input" accept="image/*">
        <label for="image-input">
          画像を追加
        </label>
        @error('image')
          <div class="error-message">{{ $message }}</div>
        @enderror
        <button type="submit">
          <img src="{{ asset('image/送信ボタン.png') }}" alt="send button"/>
        </button>
      </div>
    </form>
  </div>
</div>


<div id="edit-modal">
  <h3>メッセージを編集</h3>
  <form id="edit-form" method="POST">
    @csrf
    <textarea id="edit-message" name="message"></textarea>
    <button type="button" onclick="closeEditModal()">キャンセル</button>
    <button type="submit">更新</button>
  </form>
</div>

<div id="evaluation-modal">
  @if($showEvaluationModal)
    <h3>取引が完了しました。</h3>
    <form method="POST" action="{{ route('evaluation.store', $item->id) }}">
      @csrf
      <p>今回の取引相手はどうでしたか？</p>
      <input type="radio" name="rating" value="1" id="rating1" required>
      <label for="rating1">★☆☆☆☆</label>
      <input type="radio" name="rating" value="2" id="rating2" required>
      <label for="rating2">★★☆☆☆</label>
      <input type="radio" name="rating" value="3" id="rating3" required>
      <label for="rating3">★★★☆☆</label>
      <input type="radio" name="rating" value="4" id="rating4" required>
      <label for="rating4">★★★★☆</label>
      <input type="radio" name="rating" value="5" id="rating5" required>
      <label for="rating5">★★★★★</label>
      <button type="submit">送信する</button>
    </form>
  @endif
</div>


<script>
  // 下書き保存機能
  window.addEventListener('beforeunload', function() {
    const message = document.getElementById('message-input').value;
    const itemId = {{ $item->id }};

    const url = `/chat/${itemId}/draft`;
    const data = JSON.stringify({ message: message });

    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '{{ csrf_token() }}';

    const blob = new Blob([data], { type: 'application/json' });
    navigator.sendBeacon(url, blob);
  });

  // メッセージ編集機能
  function editMessage(messageId, currentMessage) {
    document.getElementById('edit-message').value = currentMessage;
    document.getElementById('edit-form').action = `{{ url('/chat/message') }}/${messageId}/edit`;
    document.getElementById('edit-modal').style.display = 'block';
  }

  function closeEditModal() {
    document.getElementById('edit-modal').style.display = 'none';
  }

  // メッセージ削除機能
  function deleteMessage(messageId) {
    if (confirm('このメッセージを削除しますか？')) {
      const form = document.createElement('form');
      form.method = 'POST';
      form.action = `{{ url('/chat/message') }}/${messageId}/delete`;
      form.innerHTML = `
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <input type="hidden" name="_method" value="DELETE">
      `;
      document.body.appendChild(form);
      form.submit();
    }
  }
</script>
@endsection