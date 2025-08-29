@extends('layouts.app')
@section('content')
<style>
  .mypage-content {
    background-color: #f5f5f5;
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
    margin-bottom: 15px;
  }

  .user-rating {
    display: flex;
    align-items: center;
    gap: 10px;
  }

  .rating-stars {
    display: flex;
    gap: 2px;
  }

  .rating-star {
    color: #ffd700;
    font-size: 20px;
  }

  .rating-star.unfilled {
    color: #ddd;
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

  .mypage-navigation {
    background-color: white;
    border-bottom: 1px solid #ddd;
  }

  .navigation-tabs {
    list-style: none;
    display: flex;
    padding: 0;
    margin: 0;
    max-width: 1200px;
    margin: 0 auto;
  }

  .navigation-tab {
    flex: 1;
  }

  .navigation-link {
    display: block;
    padding: 15px 20px;
    text-align: center;
    text-decoration: none;
    color: #666;
    font-size: 16px;
    border-bottom: 3px solid transparent;
    transition: all 0.2s;
    position: relative;
  }

  .navigation-link.current {
    color: #ff6b6b;
    border-bottom-color: #ff6b6b;
  }

  .unread-count {
    position: absolute;
    top: 8px;
    right: 15px;
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
    max-width: 1200px;
    margin: 0 auto;
  }

  .items-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
    gap: 30px;
  }

  .item-card {
    background-color: white;
    border-radius: 8px;
    overflow: hidden;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    transition: transform 0.2s;
    text-decoration: none;
    color: inherit;
    position: relative;
  }

  .item-card:hover {
    transform: translateY(-2px);
  }

  .item-image-container {
    width: 100%;
    height: 200px;
    background-color: #ccc;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #666;
    font-size: 18px;
    position: relative;
    overflow: hidden;
  }

  .item-image-container img {
    width: 100%;
    height: 100%;
    object-fit: cover;
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
  }

  .item-title {
    font-size: 16px;
    font-weight: 500;
    color: #333;
    margin: 0;
  }

  .empty-state {
    text-align: center;
    color: #666;
    font-size: 16px;
    padding: 60px 20px;
  }
</style>

<div class="mypage-content">
  <div class="user-profile-section">
    <div class="user-info-group">
      <div class="user-avatar">
        @if(Auth::user()->profile_image)
          <img src="{{ asset('storage/' . Auth::user()->profile_image) }}" alt="{{ Auth::user()->name }}">
        @else
          {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
        @endif
      </div>
      <div class="user-details">
        @if($user->getEvaluationCount() > 0)
          <div class="user-rating">
            <div class="rating-stars">
              @for($i = 1; $i <= 5; $i++)
                <span class="rating-star {{ $i <= $user->getAverageRating() ? '' : 'unfilled' }}">
                  {{ $i <= $user->getAverageRating() ? '★' : '☆' }}
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

  <nav class="mypage-navigation">
    <ul class="navigation-tabs">
      <li class="navigation-tab">
        <a href="{{ route('mypage', ['tab' => 'sell']) }}" class="navigation-link {{ request('tab') === 'sell' || !request('tab') ? 'current' : '' }}">
          出品した商品
        </a>
      </li>
      <li class="navigation-tab">
        <a href="{{ route('mypage', ['tab' => 'buy']) }}" class="navigation-link {{ request('tab') === 'buy' ? 'current' : '' }}">
          購入した商品
        </a>
      </li>
      <li class="navigation-tab">
        <a href="{{ route('mypage', ['tab' => 'trade']) }}" class="navigation-link {{ request('tab') === 'trade' ? 'current' : '' }}">
          取引中の商品
          @if(isset($unreadCounts) && array_sum($unreadCounts) > 0)
            <span class="unread-count">2</span>
          @endif
        </a>
      </li>
    </ul>
  </nav>

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