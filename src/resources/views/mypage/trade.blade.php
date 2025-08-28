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
    <form method="POST" action="{{ route('chat.send', $item->id) }}" enctype="multipart/form-data">
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
          <img src="{{ asset('image/送信ボタン.png') }}" style="width: 20px; height: 20px; object-fit: cover;" alt="send button"/>
        </button>
      </div>
    </form>
  </div>
</div>


<div id="edit-modal" style="display: none;">
  <h3>メッセージを編集</h3>
  <form id="edit-form" method="POST">
    @csrf
    <textarea id="edit-message" name="message"></textarea>
    <button type="button" onclick="closeEditModal()">キャンセル</button>
    <button type="submit">更新</button>
  </form>
</div>

@if($showEvaluationModal || $canEvaluate)
  <div id="evaluation-modal">
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
  </div>
@endif


<script>
  // 下書き保存機能
  function saveDraft() {
    const message = document.getElementById('message-input').value;
    const itemId = {{ $item->id }};

    fetch(`/chat/${itemId}/draft`, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '{{ csrf_token() }}'
      },
      body: JSON.stringify({ message: message })
    });
  }

  window.addEventListener('beforeunload', function() {
    saveDraft();
  });

  // メッセージ編集機能
  function editMessage(messageId, currentMessage) {
    document.getElementById('edit-message').value = currentMessage;
    document.getElementById('edit-form').action = `/chat/message/${messageId}/edit`;
    document.getElementById('edit-modal').style.display = 'block';
  }

  function closeEditModal() {
    document.getElementById('edit-modal').style.display = 'none';
  }

  // メッセージ削除機能
  function deleteMessage(messageId) {
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = `/chat/message/${messageId}/delete`;
    form.innerHTML = `
      <input type="hidden" name="_token" value="{{ csrf_token() }}">
      <input type="hidden" name="_method" value="DELETE">
    `;
    document.body.appendChild(form);
    form.submit();
  }

  // 画像モーダル表示機能
  function openImageModal(imageSrc) {
    const modal = document.createElement('div');
    modal.className = 'modal';
    modal.style.display = 'block';
    modal.innerHTML = `
      <div style="text-align: left; max-width: 80%;">
        <img src="${imageSrc}" style="max-width: 100%; max-height: 80vh;">
      </div>
    `;
    modal.onclick = function() {
      modal.remove();
    };
    document.body.appendChild(modal);
  }

  // 評価モーダルの背景クリック防止
  const evaluationModal = document.getElementById('evaluation-modal');
  if (evaluationModal) {
    evaluationModal.onclick = function(e) {
      if (e.target === this) {
        // 背景クリックでは閉じない
        return false;
      }
    };
  }

  // 編集モーダルの背景クリックで閉じる
  const editModal = document.getElementById('edit-modal');
  if (editModal) {
    editModal.onclick = function(e) {
      if (e.target === this) {
        closeEditModal();
      }
    };
  }

  // 星評価のホバー効果
  document.querySelectorAll('.star-option').forEach(option => {
    option.addEventListener('mouseenter', function() {
      const rating = this.querySelector('input').value;
      highlightStars(rating);
    });
  });

  document.querySelector('.star-rating')?.addEventListener('mouseleave', function() {
    const checkedRating = document.querySelector('input[name="rating"]:checked')?.value || 0;
    highlightStars(checkedRating);
  });

  function highlightStars(rating) {
    document.querySelectorAll('.star-display').forEach((star, index) => {
      if (index < rating) {
        star.style.color = '#ffd700';
      } else {
        star.style.color = '#ddd';
      }
    });
  }
</script>
@endsection