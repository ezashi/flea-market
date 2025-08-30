@extends('layouts.app')
@section('content')
<style>
  .tab-navigation {
    background-color: #fff;
    border-bottom: 2px solid #ddd;
    margin: 0;
    padding: 0;
    width: 100%;
}

  .tab-list {
      list-style: none;
      display: flex;
      padding: 50px 0px 0px 150px;
      margin: 0;
  }

  .tab-item {
      margin-right: 20px;
  }

  .tab-link {
      display: block;
      padding: 0;
      text-decoration: none;
      color: #666;
      font-size: 16px;
      border-bottom: 3px solid transparent;
      transition: all 0.2s;
  }

  .tab-link.active {
      color: #ff0000;
      font-weight: bold;
  }

  .products-container {
    padding: 40px 20px;
    max-width: 1600px;
    margin: 0 auto;
  }

  .products-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 20px;
  }

  /* PC (1400-1540px) - 4列固定 */
  @media (min-width: 1400px) and (max-width: 1540px) {
    .products-container {
      padding: 40px 60px;
    }
    .products-grid {
      grid-template-columns: repeat(4, 1fr);
      gap: 25px;
    }
  }

  /* タブレット (768-850px) - 4列維持 */
  @media (min-width: 768px) and (max-width: 850px) {
    .products-container {
      padding: 40px 15px;
    }
    .products-grid {
      grid-template-columns: repeat(4, 1fr);
      gap: 12px;
    }
  }

  .product-card {
    background-color: white;
    border-radius: 8px;
    overflow: hidden;
    transition: transform 0.2s;
    text-decoration: none;
    color: inherit;
    position: relative;
    min-width: 0;
  }

  .product-card:hover {
    transform: translateY(-2px);
  }

  .product-image-placeholder {
    width: 100%;
    height: 200px;
    padding-bottom: 75%;
    background-color: #fff;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #000;
    font-size: 18px;
    position: relative;
    overflow: hidden;
  }

  .product-image-placeholder img {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    object-fit: contain;
    background-color: #fff;
  }

  /* 画像がない場合のテキスト表示 */
  .product-image-placeholder:not(:has(img))::before {
    content: "商品画像";
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    z-index: 1;
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
    text-align: left;
  }

  .product-title {
    font-size: 14px;
    font-weight: normal;
    color: #000;
    margin: 0;
    text-decoration: none;
  }

  /* aタグ全体の下線も消す */
  .product-card a {
    text-decoration: none;
    color: inherit;
    display: block;
  }

  .no-items-message {
    text-align: center;
    color: #000;
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
          <div class="product-card">
            <a href="{{ route('items.show', $item->id) }}">
              <div class="product-image-placeholder">
                @if($item->image)
                  <img src="{{ asset($item->image) }}" alt="{{ $item->name }}">
                @endif
                @if($item->sold)
                  <div class="sold-overlay">Sold</div>
                @endif
              </div>
              <div class="product-details">
                <h5 class="product-title">{{ $item->name }}</h5>
              </div>
            </a>
          </div>
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