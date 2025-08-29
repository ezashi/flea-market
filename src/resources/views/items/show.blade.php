@extends('layouts.app')
@section('content')
<style>
  .show-page {
    background-color: #f5f5f5;
    min-height: 100vh;
  }

  .product-details-container {
    background-color: white;
    max-width: 1000px;
    margin: 40px auto;
    padding: 0;
    border-radius: 8px;
    box-shadow: none;
    display: flex;
    gap: 40px;
  }

  .product-image-section {
    flex: 1;
    padding: 40px;
  }

  .product-main-image {
    width: 100%;
    height: 400px;
    background-color: #ccc;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #666;
    font-size: 18px;
    border-radius: 8px;
    position: relative;
    overflow: hidden;
  }

  .product-main-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
  }

  .sold-badge {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background-color: rgba(0, 0, 0, 0.7);
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 24px;
    font-weight: bold;
  }

  .product-info-section {
    flex: 1;
    padding: 40px 40px 40px 0;
  }

  .product-title {
    font-size: 28px;
    font-weight: bold;
    color: #333;
    margin-bottom: 8px;
  }

  .product-brand {
    font-size: 16px;
    color: #666;
    margin-bottom: 20px;
  }

  .product-price {
    font-size: 24px;
    font-weight: bold;
    color: #333;
    margin-bottom: 30px;
  }

  .product-actions {
    display: flex;
    align-items: center;
    gap: 20px;
    margin-bottom: 40px;
  }

  .like-button {
    background: none;
    border: none;
    cursor: pointer;
    display: flex;
    align-items: center;
    gap: 5px;
    font-size: 16px;
    color: #666;
  }

  .like-icon {
    width: 24px;
    height: 24px;
    fill: none;
    stroke: currentColor;
    stroke-width: 2;
  }

  .comment-icon {
    width: 24px;
    height: 24px;
    fill: none;
    stroke: currentColor;
    stroke-width: 2;
  }

  .comment-count {
    color: #666;
    font-size: 16px;
  }

  .purchase-button {
    width: 100%;
    padding: 15px;
    background-color: #ff6b6b;
    color: white;
    border: none;
    border-radius: 5px;
    font-size: 16px;
    font-weight: 600;
    cursor: pointer;
    transition: background-color 0.2s;
    text-decoration: none;
    display: block;
    text-align: center;
  }

  .purchase-button:hover {
    background-color: #e55555;
  }

  .product-description-section {
    background-color: white;
    max-width: 1000px;
    margin: 20px auto;
    padding: 40px;
    border-radius: 8px;
  }

  .section-header {
    font-size: 18px;
    font-weight: bold;
    color: #333;
    margin-bottom: 20px;
    border-bottom: 1px solid #ddd;
    padding-bottom: 10px;
  }

  .description-text {
    line-height: 1.6;
    color: #333;
    margin-bottom: 30px;
  }

  .product-info-table {
    margin-bottom: 30px;
  }

  .info-row {
    display: flex;
    align-items: center;
    padding: 10px 0;
    border-bottom: 1px solid #f0f0f0;
  }

  .info-label {
    width: 120px;
    font-weight: 500;
    color: #333;
  }

  .info-value {
    flex: 1;
    color: #333;
  }

  .category-badges {
    display: flex;
    gap: 10px;
    flex-wrap: wrap;
  }

  .category-badge {
    background-color: #f0f0f0;
    color: #666;
    padding: 4px 12px;
    border-radius: 15px;
    font-size: 14px;
  }

  .comments-section {
    background-color: white;
    max-width: 1000px;
    margin: 20px auto;
    padding: 40px;
    border-radius: 8px;
  }

  .comments-header {
    font-size: 18px;
    font-weight: bold;
    color: #333;
    margin-bottom: 20px;
  }

  .comments-list {
    margin-bottom: 30px;
  }

  .comment-item {
    display: flex;
    gap: 15px;
    margin-bottom: 20px;
    padding-bottom: 20px;
    border-bottom: 1px solid #f0f0f0;
  }

  .comment-avatar {
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
    flex-shrink: 0;
  }

  .comment-avatar img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    border-radius: 50%;
  }

  .comment-content {
    flex: 1;
  }

  .comment-author {
    font-weight: bold;
    color: #333;
    margin-bottom: 5px;
  }

  .comment-text {
    background-color: #f8f8f8;
    padding: 15px;
    border-radius: 8px;
    line-height: 1.5;
    color: #333;
  }

  .comment-form {
    margin-top: 30px;
  }

  .comment-form-label {
    display: block;
    margin-bottom: 15px;
    font-size: 16px;
    color: #333;
    font-weight: 500;
  }

  .comment-textarea {
    width: 100%;
    padding: 15px;
    border: 2px solid #ddd;
    border-radius: 5px;
    font-size: 16px;
    outline: none;
    transition: border-color 0.2s;
    resize: vertical;
    min-height: 120px;
    margin-bottom: 20px;
  }

  .comment-textarea:focus {
    border-color: #ff6b6b;
  }

  .comment-submit-button {
    width: 100%;
    padding: 15px;
    background-color: #ff6b6b;
    color: white;
    border: none;
    border-radius: 5px;
    font-size: 16px;
    font-weight: 600;
    cursor: pointer;
    transition: background-color 0.2s;
  }

  .comment-submit-button:hover {
    background-color: #e55555;
  }
</style>

<div class="show-page">
  <div class="product-details-container">
    <div class="product-image-section">
      <div class="product-main-image">
        @if($item->image)
          <img src="{{ asset($item->image) }}" alt="{{ $item->name }}">
        @else
          商品画像
        @endif
        @if($item->sold)
          <div class="sold-badge">Sold</div>
        @endif
      </div>
    </div>

    <div class="product-info-section">
      <h1 class="product-title">{{ $item->name }}</h1>
      @if($item->brand)
        <p class="product-brand">{{ $item->brand }}</p>
      @endif
      <div class="product-price">¥{{ number_format($item->price) }}</div>

      <div class="product-actions">
        <form action="{{ route('items.like', $item) }}" method="POST" style="display: inline;">
          @csrf
          <button type="submit" class="like-button">
            <svg class="like-icon" viewBox="0 0 24 24">
              <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path>
            </svg>
            {{ $item->likes()->count() }}
          </button>
        </form>

        <div class="comment-count">
          <svg class="comment-icon" viewBox="0 0 24 24">
            <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path>
          </svg>
          {{ $item->comments->count() }}
        </div>
      </div>

      @if($item->seller_id !== Auth::id() && !$item->sold)
        <a href="{{ route('items.purchase', $item) }}" class="purchase-button">購入手続きへ</a>
      @endif
    </div>
  </div>

  <div class="product-description-section">
    <div class="section-header">商品説明</div>
    <div class="description-text">
      {!! nl2br(e($item->description)) !!}
    </div>

    <div class="section-header">商品の情報</div>
    <div class="product-info-table">
      <div class="info-row">
        <div class="info-label">カテゴリー</div>
        <div class="info-value">
          <div class="category-badges">
            <p class="category-badge">
              {{ $item->categories->pluck('name')->implode(' ') }}
            </p>
          </div>
        </div>
      </div>
      <div class="info-row">
        <div class="info-label">商品の状態</div>
        <div class="info-value">{{ $item->condition }}</div>
      </div>
    </div>
  </div>

  <div class="comments-section">
    <div class="comments-header">コメント ({{ $item->comments->count() }})</div>

    <div class="comments-list">
      @foreach($item->comments as $comment)
        <div class="comment-item">
          <div class="comment-avatar">
            @if($comment->user->profile_image)
              <img src="{{ asset('storage/' . $comment->user->profile_image) }}" alt="{{ $comment->user->name }}">
            @else
              {{ strtoupper(substr($comment->user->name, 0, 1)) }}
            @endif
          </div>
          <div class="comment-content">
            <div class="comment-author">{{ $comment->user->name }}</div>
            <div class="comment-text">{!! nl2br(e($comment->content)) !!}</div>
          </div>
        </div>
      @endforeach

      @if($item->comments->isEmpty())
        <div style="text-align: center; color: #666; padding: 20px;">
          こちらにコメントが入ります。
        </div>
      @endif
    </div>

    <form action="{{ route('items.comment', $item) }}" method="POST" class="comment-form">
      @csrf
      <label for="content" class="comment-form-label">商品へのコメント</label>
      <textarea name="content" id="content" class="comment-textarea" required></textarea>
      @error('content')
        <div class="error-message">{{ $message }}</div>
      @enderror
      <button type="submit" class="comment-submit-button">コメントを送信する</button>
    </form>
  </div>
</div>
@endsection