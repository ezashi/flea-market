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
              <img src="{{ $message->getImageUrl() }}" alt="send image" onclick="openImageModal(this.src)">
            @endif
          @endif
        </div>
        <div>
          @if($message->sender_id === Auth::id() && !$message->isDeleted())
            <button onclick="openEditModal({{ $message->id }}, '{{ addslashes($message->message) }}')">編集</button>
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

@if($showEvaluationModal)
  <div id="evaluation-modal" style="display: block;">
    <h3>取引が完了しました</h3>
    <form method="POST" action="{{ route('evaluation.store', $item->id) }}">
      @csrf
      <p>今回の取引相手はどうでしたか？</p>
      <div>
        <div data-rating="1">
          <input type="radio" name="rating" value="1" id="rating1" required>
          <label for="rating1">
            <span>★</span>
            <span>☆</span>
            <span>☆</span>
            <span>☆</span>
            <span>☆</span>
          </label>
        </div>
        <div data-rating="2">
          <input type="radio" name="rating" value="2" id="rating2" required>
          <label for="rating2">
            <span>★</span>
            <span>★</span>
            <span>☆</span>
            <span>☆</span>
            <span>☆</span>
          </label>
        </div>
        <div data-rating="3">
          <input type="radio" name="rating" value="3" id="rating3" required>
          <label for="rating3">
            <span>★</span>
            <span>★</span>
            <span>★</span>
            <span>☆</span>
            <span>☆</span>
          </label>
        </div>
        <div data-rating="4">
          <input type="radio" name="rating" value="4" id="rating4" required>
          <label for="rating4">
            <span>★</span>
            <span>★</span>
            <span>★</span>
            <span>★</span>
            <span>☆</span>
          </label>
        </div>
        <div data-rating="5">
          <input type="radio" name="rating" value="5" id="rating5" required>
          <label for="rating5">
            <span>★</span>
            <span>★</span>
            <span>★</span>
            <span>★</span>
            <span>★</span>
          </label>
        </div>
      </div>
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

// メッセージ編集モーダル機能
function openEditModal(messageId, currentMessage) {
  document.getElementById('edit-message').value = currentMessage;
  document.getElementById('edit-form').action = `/chat/message/${messageId}/edit`;
  document.getElementById('edit-modal').style.display = 'block';
}

function closeEditModal() {
  document.getElementById('edit-modal').style.display = 'none';
  document.getElementById('edit-message').value = '';
}

// メッセージ削除機能
function deleteMessage(messageId) {
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = `/chat/message/${messageId}/delete`;
    form.innerHTML = `
      <input type="hidden" name="_token" value="{{ csrf_token() }}">
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
    <div style="text-align: center; max-width: 90%; margin-top: 2%;">
      <img src="${imageSrc}" style="max-width: 100%; max-height: 70vh; object-fit: contain;">
    </div>
  `;

  // 背景クリックで閉じる
  modal.onclick = function(e) {
    if (e.target === modal) {
      modal.remove();
    }
  };

  document.body.appendChild(modal);
}

// モーダルの背景クリック処理
document.addEventListener('click', function(e) {
  if (e.target.classList.contains('modal')) {
    // 評価モーダルは背景クリックで閉じない
    if (e.target.id !== 'evaluation-modal') {
      e.target.style.display = 'none';
    }
  }
});

// ESCキーでモーダルを閉じる
document.addEventListener('keydown', function(e) {
  if (e.key === 'Escape') {
    const editModal = document.getElementById('edit-modal');
    if (editModal && editModal.style.display === 'block') {
      closeEditModal();
    }
  }
});

// 星評価のホバー効果
document.querySelectorAll('.star-option').forEach(option => {
  const rating = parseInt(option.dataset.rating);

  option.addEventListener('mouseenter', function() {
    highlightStars(rating);
  });

  option.addEventListener('click', function() {
    const input = this.querySelector('input[type="radio"]');
    input.checked = true;
    highlightStars(rating);
  });
});

document.querySelector('.star-rating')?.addEventListener('mouseleave', function() {
  const checkedInput = document.querySelector('input[name="rating"]:checked');
  const checkedRating = checkedInput ? parseInt(checkedInput.value) : 0;
  highlightStars(checkedRating);
});

function highlightStars(rating) {
  document.querySelectorAll('.star-option').forEach((option, index) => {
    const stars = option.querySelectorAll('.star-display');
    const optionRating = parseInt(option.dataset.rating);

    stars.forEach((star, starIndex) => {
      if (optionRating === rating) {
        star.style.color = starIndex < rating ? '#ffd700' : '#ddd';
      } else {
        star.style.color = '#ddd';
      }
    });
  });
}

// ページ読み込み時に選択された評価をハイライト
document.addEventListener('DOMContentLoaded', function() {
  const checkedInput = document.querySelector('input[name="rating"]:checked');
  if (checkedInput) {
    highlightStars(parseInt(checkedInput.value));
  }
});
</script>
@endsection