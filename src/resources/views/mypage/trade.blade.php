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
@if($showEvaluationModal && $canEvaluate)
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
              <span style="margin-left: 10px; font-weight: 500;">{{ $i }}点</span>
            </label>
          </div>
        @endfor
      </div>

      <button type="submit">送信する</button>
    </form>
  </div>
@endif
@endsection