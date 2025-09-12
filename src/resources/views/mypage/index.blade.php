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
    display: flex;
    align-items: center;
    gap: 8px;
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
    flex-shrink: 0;
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

  /* 取引中商品を新しいメッセージ順に左から表示 */
  .trade-items-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 20px;
    grid-auto-flow: row;
  }

  /* PC (1400-1540px) - 4列固定 */
  @media (min-width: 1400px) and (max-width: 1540px) {
    .items-container {
      padding: 40px 60px;
    }
    .items-grid,
    .trade-items-grid {
      grid-template-columns: repeat(4, 1fr);
      gap: 25px;
    }
  }

  /* PC大画面 (1200px-1399px) */
  @media (min-width: 1200px) and (max-width: 1399px) {
    .items-grid,
    .trade-items-grid {
      grid-template-columns: repeat(4, 1fr);
      gap: 22px;
    }
  }

  /* タブレット大 (851px-1199px) */
  @media (min-width: 851px) and (max-width: 1199px) {
    .items-grid,
    .trade-items-grid {
      grid-template-columns: repeat(4, 1fr);
      gap: 18px;
    }
  }

  /* タブレット (768px-850px) - 4列維持 */
  @media (min-width: 768px) and (max-width: 850px) {
    .items-container {
      padding: 40px 15px;
    }
    .items-grid,
    .trade-items-grid {
      grid-template-columns: repeat(4, 1fr);
      gap: 12px;
    }
  }

  /* タブレット小 (481px-764px) - 3列 */
  @media (min-width: 481px) and (max-width: 764px) {
    .items-container {
      padding: 30px 15px;
    }
    .items-grid,
    .trade-items-grid {
      grid-template-columns: repeat(3, 1fr);
      gap: 15px;
    }
  }

  /* モバイル (480px以下) - 2列 */
  @media (max-width: 480px) {
    .items-container {
      padding: 20px 10px;
    }
    .items-grid,
    .trade-items-grid {
      grid-template-columns: repeat(2, 1fr);
      gap: 10px;
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

  .trade-item-card {
    background-color: white;
    border-radius: 8px;
    overflow: hidden;
    transition: all 0.3s;
    text-decoration: none;
    color: inherit;
    position: relative;
    min-width: 0;
  }

  .trade-item-card.has-new-message {
    box-shadow: 0 4px 12px rgba(255, 107, 107, 0.2);
    border: 2px solid rgba(255, 107, 107, 0.3);
  }

  .trade-item-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 6px 16px rgba(0, 0, 0, 0.15);
  }

  .trade-item-card.has-new-message:hover {
    box-shadow: 0 6px 16px rgba(255, 107, 107, 0.3);
  }

  .item-image-container {
    width: 100%;
    height: 200px;
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
    top: 8px;
    left: 8px;
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
    z-index: 10;
    animation: pulse 2s infinite;
  }

  @keyframes pulse {
    0% {
      transform: scale(1);
      opacity: 1;
    }
    50% {
      transform: scale(1.1);
      opacity: 0.8;
    }
    100% {
      transform: scale(1);
      opacity: 1;
    }
  }

  .message-notification.high-priority {
    background-color: #dc3545;
    font-weight: bold;
    animation: pulse 1s infinite;
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
    line-height: 1.4;
    overflow: hidden;
    display: -webkit-box;
    -webkit-box-orient: vertical;
    -webkit-line-clamp: 2;
  }

  .item-card a {
    text-decoration: none;
    color: inherit;
    display: block;
  }

  .trade-item-card a {
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

  .trade-status-indicator {
    position: absolute;
    top: 8px;
    right: 8px;
    width: 12px;
    height: 12px;
    border-radius: 50%;
    z-index: 5;
  }

  .trade-status-indicator.active {
    background-color: #28a745;
  }

  .trade-status-indicator.waiting {
    background-color: #ffc107;
  }

  /* レスポンシブ対応でのユーザープロフィール部分 */
  @media (max-width: 850px) {
    .user-profile-section {
      padding: 30px 15px;
      flex-direction: column;
      gap: 20px;
      text-align: center;
    }

    .user-info-group {
      flex-direction: column;
      gap: 15px;
    }

    .user-avatar {
      width: 100px;
      height: 100px;
      font-size: 40px;
    }

    .mypage-tab-list {
      padding: 0 15px;
      justify-content: center;
      flex-wrap: wrap;
      gap: 10px;
    }

    .mypage-tab-item {
      margin-right: 0;
    }

    .mypage-tab-link {
      padding: 10px 15px;
      white-space: nowrap;
    }
  }

  @media (max-width: 480px) {
    .user-profile-section {
      padding: 20px 10px;
    }

    .user-avatar {
      width: 80px;
      height: 80px;
      font-size: 32px;
    }

    .item-image-container {
      height: 150px;
      font-size: 14px;
    }

    .item-details {
      padding: 10px;
    }

    .item-title {
      font-size: 13px;
    }

    .message-notification {
      width: 20px;
      height: 20px;
      font-size: 11px;
    }
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
        <h1>{{ Auth::user()->name }}</h1>
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
      <div class="{{ request('tab') === 'trade' ? 'trade-items-grid' : 'items-grid' }}">
        @foreach($items as $item)
          @if(request('tab') === 'trade')
            <a href="{{ route('chat.show', $item->id) }}" 
               class="trade-item-card {{ isset($unreadCounts) && isset($unreadCounts[$item->id]) && $unreadCounts[$item->id] > 0 ? 'has-new-message' : '' }}">
              <div class="item-image-container">
                @if($item->image)
                  <img src="{{ asset($item->image) }}" alt="{{ $item->name }}">
                @else
                  商品画像
                @endif
                @if(isset($unreadCounts) && isset($unreadCounts[$item->id]) && $unreadCounts[$item->id] > 0)
                  <div class="message-notification {{ $unreadCounts[$item->id] >= 5 ? 'high-priority' : '' }}">
                    {{ $unreadCounts[$item->id] > 99 ? '99+' : $unreadCounts[$item->id] }}
                  </div>
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