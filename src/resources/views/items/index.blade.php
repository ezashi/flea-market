@extends('layouts.app')
@section('content')
<style>
  .main-content {
    background-color: #f5f5f5;
    min-height: 100vh;
  }

  .tab-navigation {
    background-color: white;
    border-bottom: 1px solid #ddd;
    padding: 0;
    margin: 0;
  }

  .tab-list {
    list-style: none;
    display: flex;
    padding: 0;
    margin: 0;
  }

  .tab-item {
    flex: 1;
  }

  .tab-link {
    display: block;
    padding: 15px 20px;
    text-align: center;
    text-decoration: none;
    color: #666;
    font-size: 16px;
    border-bottom: 3px solid transparent;
    transition: all 0.2s;
  }

  .tab-link.active {
    color: #ff6b6b;
    border-bottom-color: #ff6b6b;
  }

  .products-container {
    padding: 40px 20px;
    max-width: 1200px;
    margin: 0 auto;
  }

  .products-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
    gap: 30px;
  }

  .product-card {
    background-color: white;
    border-radius: 8px;
    overflow: hidden;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    transition: transform 0.2s;
    text-decoration: none;
    color: inherit;
    position: relative;
  }

  .product-card:hover {
    transform: translateY(-2px);
  }

  .product-image-placeholder {
    width: 100%;
    height: 200px;
    background-color: #ccc;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #666;
    font-size: 18px;
    position: relative;
  }

  .product-image-placeholder img {
    width: 100%;
    height: 100%;
    object-fit: cover;
  }

  .sold-overlay {
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
    font-size: 20px;
    font-weight: bold;
  }

  .product-details {
    padding: 15px;
  }

  .product-title {
    font-size: 16px;
    font-weight: 500;
    color: #333;
    margin: 0;
  }

  .no-items-message {
    text-align: center;
    color: #666;
    font-size: 16px;
    padding: 60px 20px;
  }
</style>

<div class="main-content">
  <div class="tab-navigation">
    <ul class="tab-list">
      <li class="tab-item">
        <a href="{{ route('index') }}" class="tab-link {{ !request('tab') || request('tab') !== 'mylist' ? 'active' : '' }}">
          おすすめ
        </a>
      </li>
      <li class="tab-item">
        <a href="{{ route('index', ['tab' => 'mylist', 'search' => request('search')]) }}" class="tab-link {{ request('tab') === 'mylist' ? 'active' : '' }}">
          マイリスト
        </a>
      </li>
    </ul>
  </div>

  <div class="products-container">
    @if(count($items) > 0)
      <div class="products-grid">
        @foreach($items as $item)
          <a href="{{ route('items.show', $item->id) }}" class="product-card">
            <div class="product-image-placeholder">
              @if($item->image)
                <img src="{{ asset($item->image) }}" alt="{{ $item->name }}">
              @else
                商品画像
              @endif
              @if($item->sold)
                <div class="sold-overlay">Sold</div>
              @endif
            </div>
            <div class="product-details">
              <h5 class="product-title">{{ $item->name }}</h5>
            </div>
          </a>
        @endforeach
      </div>
    @else
      <div class="no-items-message">
        @if(request('tab') === 'mylist')
          いいねした商品がありません。
        @else
          商品がありません。
        @endif
      </div>
    @endif
  </div>
</div>
@endsection