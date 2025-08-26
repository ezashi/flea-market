@extends('layouts.app')
@section('content')
<div>
  <div>
    <h3>取引中の商品</h3>
    @if($tradingItems->isEmpty())
      <p>取引中の商品はありません</p>
    @else
      @foreach($tradingItems as $tradingItem)
        <div>
          <a href="{{ route('chat.show', $tradingItem->id) }}">
            <div>
              <img src="{{ asset($tradingItem->image) }}" alt="{{ $tradingItem->name }}">
              <h5>{{ $tradingItem->name }}</h5>
            </div>
          </a>
        </div>
      @endforeach
    @endif
  </div>
  <div>
    <form action="{{ route('') }}" method="POST">
      @if($chatPartner->profile_image)
        <img src="{{ asset($chatPartner->profile_image) }}" style="max-width: 100px;" alt="{{ $chatPartner->name }}">
      @else
        <div>
          <span>{{ strtoupper(substr($chatPartner->name, 0, 1)) }}</span>
        </div>
      @endif
      「 {{ $chatPartner->name }} 」さんとの取引画面
      <button type="submit">取引を完了する</button>
    </form>
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
          @if($message->sender->profile_image)
            <img src="{{ asset($message->sender->profile_image) }}" alt="{{ $message->sender->name }}">
          @else
            {{ strtoupper(substr($message->sender->name, 0, 1)) }}
          @endif
          <span>{{ $message->sender->name }}</span>
        </div>
        <div>
          @if($message->isImage())
            <img src="{{ $message->getImageUrl() }}" alt="送信された画像" onclick="openImageModal(this.src)">
          @else
            {!! nl2br(e($message->message)) !!}
          @endif
        </div>
      @endforeach
    @endif
  </div>
  <div>
    <form method="POST" action="{{ route('chat.send', $item->id) }}">
      @csrf
      <div>
        <textarea name="message" placeholder="取引メッセージを記入してください">
          {{ old('message') }}
        </textarea>
        @error('message')
          <div class="error-message">{{ $message }}</div>
        @enderror
        <label for="image" style="cursor: pointer;">
          画像を追加
        </label>
        <button type="submit">
          <img src="{{ asset('image/送信ボタン.png') }}" alt="送信ボタン"/>
        </button>
      </div>
    </form>
  </div>
</div>
@endsection