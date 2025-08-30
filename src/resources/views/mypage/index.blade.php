@extends('layouts.app')
@section('content')
<style>
  .mypage-content {
    background-color: #fff;
    min-height: 100vh;
  }

  .user-profile-section {
    background-color: white;
    padding: 40px 20px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    max-width: 1200px;
    margin: 0 auto;
  }

  .user-info-group {
    display: flex;
    align-items: center;
    gap: 20px;
  }

  .user-avatar {
    width: 120px;
    height: 120px;
    border-radius: 50%;
    background-color: #ccc;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #666;
    font-size: 48px;
    font-weight: bold;
    overflow: hidden;
  }

  .user-avatar img {
    width: 100%;
    height: 100%;
    object-fit: cover;
  }

  .user-details h2 {
    font-size: 24px;
    font-weight: bold;
    color: #333;
    margin-bottom: 10px;
  }

  .user-rating {
    display: flex;
    align-items: center;
    gap: 8px;
    margin-bottom: 5px;
  }

  .rating-stars {
    display: flex;
    gap: 2px;
  }

  .rating-star {
    color: #ffd700;
    font-size: 18px;
  }

  .rating-star.empty {
    color: #ddd;
  }

  .rating-text {
    color: #666;
    font-size: 14px;
  }

  .profile-edit-button {
    background-color: white;
    color: #ff6b6b;
    border: 2px solid #ff6b6b;
    padding: 10px 20px;
    border-radius: 5px;
    font-size: 16px;
    cursor: pointer;
    transition: all 0.2s;
    text-decoration: none;
    display: inline-block;
  }

  .profile-edit-button:hover {
    background-color: #ff6b6b;
    color: white;
  }

  .mypage-tab-navigation {
    background-color: #fff;
    border-bottom: 2px solid #ddd;
    margin: 0;
    padding: 0;
    width: 100%;
  }

  .mypage-tab-list {
    list-style: none;
    display: flex;
    padding: 0 0 0 150px;
    margin: 0;
  }

  .mypage-tab-item {
    margin-right: 20px;
    position: relative;
  }

  .mypage-tab-link {
    display: block;
    padding: 0;
    text-decoration: none;
    color: #666;
    font-size: 16px;
    border-bottom: 3px solid transparent;
    transition: all 0.2s;
    position: relative;
  }

  .mypage-tab-link.active {
    color: #ff0000;
    font-weight: bold;
  }

  .unread-count {
    position: absolute;
    top: -8px;
    right: -8px;
    background-color: #ff6b6b;
    color: white;
    border-radius: 50%;
    width: 20px;
    height: 20px;
    font-size: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: bold;
  }

  .items-container {
    padding: 40px 20px;
    max-width: 1600px;
    margin: 0 auto;
  }

  .items-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 20px;
  }

  /* PC (1400-1540px) - 4列固定 */
  @media (min-width: 1400px) and (max-width: 1540px) {
    .items-container {
      padding: 40px 60px;
    }
    .items-grid {
      grid-template-columns: repeat(4, 1fr);
      gap: 25px;
    }
  }

  /* タブレット (768-850px) - 4列維持 */
  @media (min-width: 768px) and (max-width: 850px) {
    .items-container {
      padding: 40px 15px;
    }
    .items-grid {
      grid-template-columns: repeat(4, 1fr);
      gap: 12px;
    }
  }

  .item-card {
    background-color: white;
    border-radius: 8px;
    overflow: hidden;
    transition: transform 0.2s;
    text-decoration: none;
    color: inherit;
    position: relative;
    min-width: 0;
  }

  .item-card:hover {
    transform: translateY(-2px);
  }

  .item-image-container {
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

  .item-image-container img {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    object-fit: contain;
    background-color: #fff;
  }

  /* 画像がない場合のテキスト表示 */
  .item-image-container:not(:has(img))::before {
    content: "商品画像";
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    z-index: 1;
  }

  .message-notification {
    position: absolute;
    top: 10px;
    right: 10px;
    background-color: #ff6b6b;
    color: white;
    border-radius: 50%;
    width: 24px;
    height: 24px;
    font-size: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: bold;
  }

  .item-details {
    padding: 15px;
    text-align: left;
  }

  .item-title {
    font-size: 14px;
    font-weight: normal;
    color: #000;
    margin: 0;
    text-decoration: none;
  }

  .item-card a {
    text-decoration: none;
    color: inherit;
    display: block;
  }

  .empty-state {
    text-align: center;
    color: #000;
    font-size: 16px;
    padding: 60px 20px;
  }
</style>

<div class="mypage-content">
  <div class="user-profile-section">
    <div class="user-info-group">
      <div class="user-avatar">
        @if(Auth::user()->profile_image)
          <img src="{{ asset(Auth::user()->profile_image) }}" alt="{{ Auth::user()->name }}">
        @else
          {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
        @endif
      </div>
      <div class="user-details">
        <h2>{{ Auth::user()->name }}</h2>
        @if($user->getEvaluationCount() > 0)
          <div class="user-rating">
            <div class="rating-stars">
              @for($i = 1; $i <= 5; $i++)
                <span class="rating-star {{ $i <= $user->getAverageRating() ? '' : 'empty' }}">
                  ★
                </span>
              @endfor
            </div>
          </div>
        @endif
      </div>
    </div>
    <form action="{{ route('profile.show') }}" method="GET">
      <button type="submit" class="profile-edit-button">プロフィールを編集</button>
    </form>
  </div>

  <div class="mypage-tab-navigation">
    <ul class="mypage-tab-list">
      <li class="mypage-tab-item">
        <a href="{{ route('mypage', ['tab' => 'sell']) }}" class="mypage-tab-link {{ request('tab') === 'sell' || !request('tab') ? 'active' : '' }}">
          出品した商品
        </a>
      </li>
      <li class="mypage-tab-item">
        <a href="{{ route('mypage', ['tab' => 'buy']) }}" class="mypage-tab-link {{ request('tab') === 'buy' ? 'active' : '' }}">
          購入した商品
        </a>
      </li>
      <li class="mypage-tab-item">
        <a href="{{ route('mypage', ['tab' => 'trade']) }}" class="mypage-tab-link {{ request('tab') === 'trade' ? 'active' : '' }}">
          取引中の商品
          @if(isset($unreadCounts) && array_sum($unreadCounts) > 0)
            <span class="unread-count">{{ array_sum($unreadCounts) }}</span>
          @endif
        </a>
      </li>
    </ul>
  </div>

  <div class="items-container">
    @if($items->isEmpty())
      <div class="empty-state">
        @if(request('tab') === 'sell' || !request('tab'))
          出品した商品はありません。
        @elseif(request('tab') === 'buy')
          購入した商品はありません。
        @else
          取引中の商品はありません。
        @endif
      </div>
    @else
      <div class="items-grid">
        @foreach($items as $item)
          @if(request('tab') === 'trade')
            <a href="{{ route('chat.show', $item->id) }}" class="item-card">
              <div class="item-image-container">
                @if($item->image)
                  <img src="{{ asset($item->image) }}" alt="{{ $item->name }}">
                @else
                  商品画像
                @endif
                @if(isset($unreadCounts) && isset($unreadCounts[$item->id]) && $unreadCounts[$item->id] > 0)
                  <div class="message-notification">{{ $unreadCounts[$item->id] }}</div>
                @endif
              </div>
              <div class="item-details">
                <h5 class="item-title">{{ $item->name }}</h5>
              </div>
            </a>
          @else
            <a href="{{ route('items.show', $item->id) }}" class="item-card">
              <div class="item-image-container">
                @if($item->image)
                  <img src="{{ asset($item->image) }}" alt="{{ $item->name }}">
                @else
                  商品画像
                @endif
              </div>
              <div class="item-details">
                <h5 class="item-title">{{ $item->name }}</h5>
              </div>
            </a>
          @endif
        @endforeach
      </div>
    @endif
  </div>
</div>
@endsection