@extends('layouts.app')
@section('content')
<div>
  <!-- 商品画像 -->
  @if($item->image)
    <img src="{{ asset($item->image) }}" alt="{{ $item->name }}" style="height: 200px;">
  @endif

  @if($item->sold)
    <div>Sold</div>
  @endif

  <!-- 商品情報 -->
  <div>
    <h2>{{ $item->name }}</h2>
    @if($item->brand)
      <p>{{ $item->brand }}</p>
    @endif
    <h3>¥{{ number_format($item->price) }}</h3>

    <form action="{{ route('items.like', $item) }}" method="POST">
      @csrf
      <button type="submit">いいね {{ $item->likes()->count() }}</button>
    </form>
    <label>コメント ({{ $item->comments->count() }})</label>
    <form action="{{ route('items.purchase', $item) }}" method="GET">
      @csrf
      @if($item->seller_id !== Auth::id() && !$item->sold)
        <button type="submit">購入手続きへ</button>
      @endif
    </form>
  </div>

  <!-- 商品説明 -->
  <div>
    <h4>商品説明</h4>
    {!! nl2br(e($item->description)) !!}

    <h4>商品の情報</h4>
    <p>カテゴリー {{ $item->categories->pluck('name')->implode(' ') }}</p>
    <p>商品の状態 {{ $item->condition }}</p>
  </div>

  <!-- コメント欄 -->
  <div>
    <h4>コメント ({{ $item->comments->count() }})</h4>
    <form action="{{ route('items.comment', $item) }}" method="POST">
      @csrf
      @foreach($item->comments as $comment)
        <span>
          <img src="{{ asset($comment->user->profile_image) }}" alt="{{ $comment->user->name }}" style="width: 40px; height: 40px;"> {{ $comment->user->name }}
        </span>
        {!! nl2br(e($comment->content)) !!}
      @endforeach
      <div>
        ここにコメントが入ります
      </div>
      <p for="content">商品へのコメント</p>
      <textarea name="content" rows="3"></textarea>
      @error('content')
        <div class="error-message">{{ $message }}</div>
      @enderror
      <button type="submit">コメントを送信する</button>
    </form>
  </div>
</div>
@endsection