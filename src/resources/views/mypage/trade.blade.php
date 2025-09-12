@extends('layouts.app')
@section('content')
<style>
  body {
    overflow: hidden;
    height: 100vh;
  }

  html {
    overflow: hidden;
    height: 100vh;
  }

  main {
    padding-top: 0;
    height: calc(100vh - 62px);
    overflow: hidden;
  }

  .trade-chat-container {
    display: flex;
    height: 100%;
    background-color: #fff;
    overflow: hidden;
  }

  .chat-sidebar {
    width: 200px;
    background-color: #666;
    color: white;
    padding: 20px 15px;
    position: fixed;
    height: calc(100vh - 62px);
    left: 0;
    top: 62px;
    overflow-y: auto;
    z-index: 100;
    flex-shrink: 0;
  }

  .sidebar-title {
    font-size: 20px;
    font-weight: bold;
    margin-bottom: 20px;
    color: white;
  }

  .other-trades {
    margin-bottom: 30px;
  }

  .other-trade-item {
    display: block;
    color: white;
    text-decoration: none;
    margin-bottom: 10px;
    padding: 10px;
    border-radius: 5px;
    transition: background-color 0.2s;
    word-wrap: break-word;
    line-height: 1.4;
  }

  .other-trade-item:hover {
    background-color: rgba(255, 255, 255, 0.1);
  }

  .chat-main-content {
    flex: 1;
    margin-left: 200px;
    display: flex;
    flex-direction: column;
    height: 100%;
    position: relative;
    min-width: 0;
  }

  .chat-header {
    background-color: white;
    padding: 20px 30px;
    border-bottom: 1px solid #000;
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 15px;
    flex-shrink: 0;
  }

  .chat-user-info {
    display: flex;
    align-items: center;
    gap: 15px;
    min-width: 0;
    flex: 1;
  }

  .chat-user-avatar {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    background-color: #ccc;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #666;
    font-size: 20px;
    font-weight: bold;
    overflow: hidden;
    flex-shrink: 0;
  }

  .chat-user-avatar img {
    width: 100%;
    height: 100%;
    object-fit: cover;
  }

  .chat-title {
    font-size: 20px;
    font-weight: bold;
    color: #333;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    min-width: 0;
  }

  .complete-transaction-btn {
    background-color: #ff6b6b;
    color: white;
    border: none;
    padding: 12px 24px;
    border-radius: 25px;
    font-size: 16px;
    font-weight: 600;
    cursor: pointer;
    transition: background-color 0.2s;
    white-space: nowrap;
    flex-shrink: 0;
  }

  .complete-transaction-btn:hover {
    background-color: #e55555;
  }

  .product-info-section {
    background-color: white;
    padding: 30px;
    border-bottom: 3px solid #ddd;
    display: flex;
    align-items: center;
    gap: 30px;
    flex-shrink: 0;
  }

  .product-image {
    width: 150px;
    height: 150px;
    background-color: #ccc;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #666;
    font-size: 16px;
    border-radius: 8px;
    overflow: hidden;
    flex-shrink: 0;
  }

  .product-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
  }

  .product-details {
    flex: 1;
    min-width: 0;
  }

  .product-name {
    font-size: 24px;
    font-weight: bold;
    color: #333;
    margin-bottom: 10px;
    word-wrap: break-word;
  }

  .product-price {
    font-size: 20px;
    font-weight: bold;
    color: #333;
  }

  .chat-messages {
    flex: 1;
    padding: 30px;
    background-color: #fff;
    overflow-y: auto;
    min-height: 0;
  }

  .message-item {
    display: flex;
    margin-bottom: 20px;
    align-items: flex-start;
    gap: 15px;
  }

  .message-item.own {
    flex-direction: row-reverse;
  }

  .message-avatar {
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
    overflow: hidden;
    flex-shrink: 0;
  }

  .message-avatar img {
    width: 100%;
    height: 100%;
    object-fit: cover;
  }

  .message-content {
    max-width: 60%;
    position: relative;
    min-width: 0;
  }

  .message-sender {
    font-size: 14px;
    color: #666;
    margin-bottom: 5px;
    text-align: center;
    word-wrap: break-word;
  }

  .message-bubble {
    background-color: #e9e9e9;
    padding: 15px 20px;
    border-radius: 20px;
    font-size: 16px;
    line-height: 1.4;
    color: #333;
    position: relative;
    word-wrap: break-word;
  }

  .message-item.own .message-bubble {
    background-color: #007bff;
    color: white;
  }

  .message-actions {
    display: flex;
    gap: 10px;
    margin-top: 8px;
    font-size: 12px;
  }

  .message-actions a,
  .message-actions button {
    color: #666;
    text-decoration: none;
    background: none;
    border: none;
    cursor: pointer;
    padding: 2px 5px;
  }

  .message-actions a:hover,
  .message-actions button:hover {
    color: #333;
  }

  .deleted-message {
    background-color: #f0f0f0 !important;
    color: #999 !important;
    font-style: italic;
  }

  .message-image {
    max-width: 200px;
    border-radius: 10px;
    margin-top: 10px;
  }

  .chat-input-section {
    background-color: white;
    padding: 20px 30px;
    flex-shrink: 0;
  }

  .chat-input-form {
    display: flex;
    align-items: flex-end;
    gap: 15px;
    flex-wrap: wrap;
  }

  .chat-input-main {
    flex: 1;
    position: relative;
    min-width: 200px;
  }

  .chat-textarea {
    width: 100%;
    min-height: 50px;
    max-height: 120px;
    padding: 15px 20px;
    border: 2px solid #ddd;
    border-radius: 25px;
    font-size: 16px;
    outline: none;
    resize: none;
    transition: border-color 0.2s;
  }

  .chat-textarea:focus {
    border-color: #ff6b6b;
  }

  .draft-status {
    position: absolute;
    right: 25px;
    bottom: -25px;
    font-size: 12px;
    color: #999;
    opacity: 0;
    transition: opacity 0.3s;
  }

  .draft-status.saving {
    opacity: 1;
    color: #007bff;
  }

  .draft-status.saved {
    opacity: 1;
    color: #28a745;
  }

  .chat-input-actions {
    display: flex;
    align-items: center;
    gap: 10px;
    flex-shrink: 0;
  }

  .image-upload-btn {
    background-color: white;
    color: #ff6b6b;
    border: 2px solid #ff6b6b;
    padding: 12px 20px;
    border-radius: 25px;
    font-size: 14px;
    cursor: pointer;
    transition: all 0.2s;
    text-decoration: none;
    display: inline-block;
    white-space: nowrap;
  }

  .image-upload-btn:hover {
    background-color: #ff6b6b;
    color: white;
  }

  .send-btn {
    background-color: #007bff;
    color: white;
    border: none;
    width: 50px;
    height: 50px;
    border-radius: 50%;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: background-color 0.2s;
    flex-shrink: 0;
  }

  .send-btn:hover {
    background-color: #0056b3;
  }

  .send-icon {
    width: 20px;
    height: 20px;
    fill: white;
  }

  .file-input {
    display: none;
  }

  .error-message {
    color: #dc3545;
    font-size: 12px;
    margin-top: 5px;
  }

  /* 評価モーダル */
  .evaluation-modal {
    position: fixed;
    top: 0;
    left: 0;
    width: 100vw;
    height: 100vh;
    display: none;
    align-items: center;
    justify-content: center;
    z-index: 9999;
  }

  .evaluation-modal.show {
    display: flex;
  }

  .modal-content {
    background: #fcf9deff;
    padding: 18px;
    border-radius: 15px;
    max-width: 500px;
    width: 90%;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
    text-align: center;
  }

  .modal-title {
    font-size: 24px;
    font-weight: bold;
    color: #000;
    margin: 0 -20px 20px;
    padding: 0 20px;
    text-align: left;
    border-bottom: 1px solid #ddd;
    display: block;
  }

  .modal-subtitle {
    font-size: 16px;
    color: #666;
    margin-bottom: 10px;
    text-align: left;
  }

  .evaluation-form {
    text-align: right;
  }

  .rating-section {
    margin: 0 -20px;
    border-bottom: 1px solid #000;
  }

  .interactive-rating {
    display: flex;
    justify-content: center;
    gap: 8px;
    margin: 20px 0;
    flex-wrap: wrap;
  }

  .rating-star-interactive {
    font-size: 48px;
    color: #ddd;
    cursor: pointer;
    transition: all 0.2s ease;
    user-select: none;
  }

  .rating-star-interactive:hover {
    transform: scale(1.1);
  }

  .rating-star-interactive.active {
    color: #ffd700;
  }

  .rating-star-interactive.hover {
    color: #ffd700;
  }

  .rating-input-hidden {
    display: none;
  }

  .modal-submit-btn {
    background-color: #ff6b6b;
    color: white;
    border: none;
    padding: 10px 15px;
    font-size: 16px;
    font-weight: 600;
    cursor: pointer;
    transition: background-color 0.2s;
    margin-top: 20px;
    opacity: 0.5;
    pointer-events: none;
  }

  .modal-submit-btn.enabled {
    opacity: 1;
    pointer-events: auto;
  }

  .modal-submit-btn:hover.enabled {
    background-color: #e55555;
  }

  .evaluation-status {
    background-color: #f8f9fa;
    padding: 15px;
    border-radius: 8px;
    margin-bottom: 20px;
    font-size: 14px;
    color: #666;
  }

  .evaluation-complete {
    color: #28a745;
    font-weight: bold;
  }

  /* PC大画面対応 (1400px以上) */
  @media (min-width: 1400px) {
    .chat-sidebar {
      width: 250px;
      padding: 25px 20px;
    }

    .sidebar-title {
      font-size: 20px;
      margin-bottom: 25px;
    }

    .other-trade-item {
      padding: 12px;
      font-size: 18px;
      margin-bottom: 12px;
    }

    .chat-main-content {
      margin-left: 250px;
    }

    .chat-header {
      padding: 25px 40px;
      gap: 20px;
    }

    .chat-user-avatar {
      width: 55px;
      height: 55px;
      font-size: 22px;
    }

    .chat-title {
      font-size: 22px;
    }

    .complete-transaction-btn {
      padding: 14px 28px;
      font-size: 17px;
    }

    .product-info-section {
      padding: 35px 40px;
      gap: 35px;
    }

    .product-image {
      width: 170px;
      height: 170px;
    }

    .product-name {
      font-size: 26px;
      margin-bottom: 12px;
    }

    .product-price {
      font-size: 22px;
    }

    .chat-messages {
      padding: 35px 40px;
    }

    .message-item {
      gap: 18px;
      margin-bottom: 25px;
    }

    .message-avatar {
      width: 45px;
      height: 45px;
      font-size: 18px;
    }

    .message-bubble {
      padding: 18px 24px;
      font-size: 17px;
    }

    .chat-input-section {
      padding: 25px 40px;
    }

    .chat-textarea {
      padding: 18px 24px;
      font-size: 17px;
    }

    .image-upload-btn {
      padding: 14px 24px;
      font-size: 15px;
    }

    .send-btn {
      width: 55px;
      height: 55px;
    }

    .send-icon {
      width: 22px;
      height: 22px;
    }
  }

  /* PC標準サイズ対応 (1200px-1399px) */
  @media (min-width: 1200px) and (max-width: 1399px) {
    .chat-sidebar {
      width: 220px;
      padding: 20px 18px;
    }

    .sidebar-title {
      font-size: 20px;
      margin-bottom: 22px;
    }

    .other-trade-item {
      padding: 11px;
      font-size: 18px;
      margin-bottom: 11px;
    }

    .chat-main-content {
      margin-left: 220px;
    }

    .chat-header {
      padding: 22px 35px;
    }

    .product-info-section {
      padding: 32px 35px;
      gap: 32px;
    }

    .product-image {
      width: 160px;
      height: 160px;
    }

    .chat-messages {
      padding: 32px 35px;
    }

    .chat-input-section {
      padding: 22px 35px;
    }
  }

  /* タブレット大画面対応 (851px-1199px) */
  @media (min-width: 851px) and (max-width: 1199px) {
    .chat-sidebar {
      width: 200px;
      padding: 15px 10px;
    }

    .chat-main-content {
      margin-left: 200px;
    }

    .chat-header {
      padding: 15px 20px;
    }

    .product-info-section {
      padding: 25px 20px;
      gap: 25px;
    }

    .product-image {
      width: 130px;
      height: 130px;
    }

    .product-name {
      font-size: 22px;
    }

    .product-price {
      font-size: 19px;
    }

    .chat-messages {
      padding: 25px 20px;
    }

    .chat-input-section {
      padding: 18px 20px;
    }
  }

  /* タブレット標準サイズ対応 (765px-850px) */
  @media (min-width: 765px) and (max-width: 850px) {
    .chat-sidebar {
      width: 180px;
      padding: 15px 10px;
      height: calc(100vh - 51px);
      top: 51px;
      left: 0;
      position: fixed;
      box-sizing: border-box;
    }

    .sidebar-title {
      font-size: 20px;
      margin-bottom: 18px;
    }

    .other-trade-item {
      padding: 8px;
      font-size: 18px;
      margin-bottom: 8px;
    }

    .chat-main-content {
      margin-left: 180px;
      height: calc(100vh - 62px);
    }

    .chat-header {
      padding: 15px 18px;
      gap: 12px;
    }

    .chat-user-avatar {
      width: 45px;
      height: 45px;
      font-size: 18px;
    }

    .chat-title {
      font-size: 18px;
    }

    .complete-transaction-btn {
      padding: 10px 18px;
      font-size: 14px;
    }

    .product-info-section {
      padding: 20px 18px;
      gap: 20px;
    }

    .product-image {
      width: 120px;
      height: 120px;
    }

    .product-name {
      font-size: 20px;
      margin-bottom: 8px;
    }

    .product-price {
      font-size: 18px;
    }

    .chat-messages {
      padding: 20px 18px;
    }

    .message-item {
      gap: 12px;
      margin-bottom: 18px;
    }

    .message-avatar {
      width: 36px;
      height: 36px;
      font-size: 15px;
    }

    .message-content {
      max-width: 65%;
    }

    .message-bubble {
      padding: 12px 18px;
      font-size: 15px;
    }

    .chat-input-section {
      padding: 15px 18px;
    }

    .chat-input-form {
      gap: 12px;
    }

    .chat-input-main {
      min-width: 180px;
    }

    .chat-textarea {
      padding: 12px 18px;
      font-size: 15px;
    }

    .image-upload-btn {
      padding: 10px 16px;
      font-size: 13px;
    }

    .send-btn {
      width: 45px;
      height: 45px;
    }

    .send-icon {
      width: 18px;
      height: 18px;
    }

    /* モーダルの調整 */
    .modal-content {
      width: 85%;
      padding: 25px 15px;
    }

    .modal-title {
      font-size: 20px;
      margin-bottom: 18px;
    }

    .modal-subtitle {
      font-size: 14px;
    }

    .rating-star-interactive {
      font-size: 42px;
    }

    .modal-submit-btn {
      padding: 12px 20px;
      font-size: 15px;
    }
  }

  /* モバイル (764px以下) */
  @media (max-width: 764px) {
    main {
      height: calc(100vh - 140px);
    }

    .chat-sidebar {
      width: 100%;
      height: auto;
      position: relative;
      top: 0;
      padding: 15px;
      order: -1;
    }

    .trade-chat-container {
      flex-direction: column;
    }

    .chat-main-content {
      margin-left: 0;
      flex: 1;
      display: flex;
      flex-direction: column;
    }

    .other-trades {
      display: none;
    }

    .chat-header {
      padding: 10px 15px;
      flex-shrink: 0;
      flex-direction: column;
      align-items: flex-start;
      gap: 10px;
    }

    .chat-user-info {
      gap: 10px;
      width: 100%;
    }

    .chat-user-avatar {
      width: 40px;
      height: 40px;
      font-size: 16px;
    }

    .chat-title {
      font-size: 16px;
      white-space: normal;
    }

    .complete-transaction-btn {
      padding: 8px 16px;
      font-size: 14px;
      border-radius: 20px;
      width: 100%;
    }

    .product-info-section {
      padding: 15px;
      gap: 15px;
      flex-direction: column;
      align-items: flex-start;
      flex-shrink: 0;
    }

    .product-image {
      width: 100px;
      height: 100px;
      align-self: center;
    }

    .product-details {
      text-align: center;
      width: 100%;
    }

    .product-name {
      font-size: 18px;
      margin-bottom: 5px;
    }

    .product-price {
      font-size: 16px;
    }

    .chat-messages {
      padding: 15px;
      flex: 1;
      min-height: 0;
    }

    .message-item {
      margin-bottom: 15px;
      gap: 10px;
    }

    .message-avatar {
      width: 32px;
      height: 32px;
      font-size: 14px;
    }

    .message-content {
      max-width: 75%;
    }

    .message-bubble {
      padding: 10px 15px;
      font-size: 14px;
      border-radius: 15px;
    }

    .chat-input-section {
      padding: 10px 15px;
      flex-shrink: 0;
    }

    .chat-input-form {
      gap: 10px;
      flex-wrap: wrap;
    }

    .chat-input-main {
      min-width: auto;
      width: 100%;
      order: 1;
    }

    .chat-input-actions {
      order: 2;
      justify-content: space-between;
      width: 100%;
    }

    .chat-textarea {
      padding: 10px 15px;
      border-radius: 20px;
      font-size: 14px;
    }

    .image-upload-btn {
      padding: 8px 12px;
      font-size: 12px;
      border-radius: 20px;
    }

    .send-btn {
      width: 40px;
      height: 40px;
    }

    .send-icon {
      width: 16px;
      height: 16px;
    }

    /* モーダルのレスポンシブ調整 */
    .modal-content {
      padding: 30px 20px;
      margin: 20px;
      width: calc(100% - 40px);
    }

    .modal-title {
      font-size: 20px;
    }

    .modal-subtitle {
      font-size: 14px;
    }

    .rating-star-interactive {
      font-size: 36px;
    }

    .modal-submit-btn {
      padding: 12px 24px;
      font-size: 14px;
    }
  }

  /* 非常に小さい画面 (480px以下) */
  @media (max-width: 480px) {
    .chat-header {
      padding: 8px 10px;
    }

    .chat-user-info {
      flex-direction: column;
      align-items: flex-start;
    }

    .complete-transaction-btn {
      margin-top: 10px;
    }

    .product-info-section {
      padding: 10px;
    }

    .product-image {
      width: 80px;
      height: 80px;
    }

    .product-name {
      font-size: 16px;
    }

    .product-price {
      font-size: 14px;
    }

    .chat-input-actions {
      flex-direction: column;
      gap: 8px;
    }

    .image-upload-btn,
    .send-btn {
      width: 100%;
    }

    .send-btn {
      height: 40px;
      border-radius: 20px;
    }

    .modal-content {
      padding: 20px 15px;
      margin: 15px;
    }

    .rating-star-interactive {
      font-size: 32px;
    }

    .interactive-rating {
      gap: 5px;
    }
  }
</style>

<div class="trade-chat-container">
  <div class="chat-sidebar">
    <p class="sidebar-title">その他の取引</p>
    <div class="other-trades">
      @if($tradingItems->isEmpty())
        <p style="color: #ccc; font-size: 14px;">取引中の商品はありません</p>
      @else
        @foreach($tradingItems as $tradingItem)
          <a href="{{ route('chat.show', $tradingItem->id) }}" class="other-trade-item">
            {{ $tradingItem->name }}
            @if(isset($unreadCounts[$tradingItem->id]) && $unreadCounts[$tradingItem->id] > 0)
              <span style="background: #ff6b6b; color: white; border-radius: 10px; padding: 2px 6px; font-size: 12px; margin-left: 5px;">
                {{ $unreadCounts[$tradingItem->id] > 99 ? '99+' : $unreadCounts[$tradingItem->id] }}
              </span>
            @endif
          </a>
        @endforeach
      @endif
    </div>
  </div>

  <div class="chat-main-content">
    <div class="chat-header">
      <div class="chat-user-info">
        <div class="chat-user-avatar">
          @if($chatPartner->profile_image)
            <img src="{{ asset($chatPartner->profile_image) }}" alt="{{ $chatPartner->name }}">
          @else
            {{ strtoupper(substr($chatPartner->name, 0, 1)) }}
          @endif
        </div>
        <h1 class="chat-title">「{{ $chatPartner->name }}」さんとの取引画面</h1>
      </div>
      @if(Auth::id() === $item->buyer_id && !$item->is_transaction_completed)
        <form action="{{ route('items.completeTransaction', $item->id) }}" method="POST">
          @csrf
          <button type="submit" class="complete-transaction-btn">取引を完了する</button>
        </form>
      @endif
    </div>

    <div class="product-info-section">
      <div class="product-image">
        @if($item->image)
          <img src="{{ asset($item->image) }}" alt="{{ $item->name }}">
        @else
          商品画像
        @endif
      </div>
      <div class="product-details">
        <h3 class="product-name">{{ $item->name }}</h3>
        <div class="product-price">¥{{ number_format($item->price) }}</div>
      </div>
    </div>

    <div class="chat-messages">
      @if($messages->isNotEmpty())
        @foreach($messages as $message)
          <div class="message-item {{ $message->sender_id === Auth::id() ? 'own' : '' }}">
            @if($message->sender_id !== Auth::id())
              <div class="message-avatar">
                @if($message->sender->profile_image)
                  <img src="{{ asset($message->sender->profile_image) }}" alt="{{ $message->sender->name }}">
                @else
                  {{ strtoupper(substr($message->sender->name, 0, 1)) }}
                @endif
              </div>
            @endif
            @if($message->sender_id === Auth::id())
              <div class="message-avatar">
                @if(Auth::user()->profile_image)
                  <img src="{{ asset(Auth::user()->profile_image) }}" alt="{{ Auth::user()->name }}">
                @else
                  {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                @endif
              </div>
            @endif

            <div class="message-content">
              @if($message->sender_id !== Auth::id())
                <div class="message-sender">{{ $message->sender->name }}</div>
              @else
                <div class="message-sender">{{ Auth::user()->name }}</div>
              @endif

              <div class="message-bubble {{ $message->isDeleted() ? 'deleted-message' : '' }}">
                @if($message->isDeleted())
                  このメッセージは削除されました
                @else
                  @if($message->message)
                    {{ $message->message }}
                  @endif
                  @if($message->image_path)
                    <img src="{{ asset($message->image_path) }}" alt="送信画像" class="message-image">
                  @endif
                @endif
              </div>

              @if($message->sender_id === Auth::id() && !$message->isDeleted())
                <div class="message-actions">
                  <a href="{{ route('chat.edit', $message->id) }}">編集</a>
                  <form action="{{ route('chat.delete', $message->id) }}" method="POST" style="display: inline;">
                    @csrf
                    <button type="submit">削除</button>
                  </form>
                </div>
              @endif
            </div>
          </div>
        @endforeach
      @endif
    </div>

    <div class="chat-input-section">
      <form method="POST" action="{{ route('chat.send', $item->id) }}" enctype="multipart/form-data" class="chat-input-form" novalidate>
        @csrf
        <div class="chat-input-main">
          <textarea
            name="message"
            id="chat-message-input"
            class="chat-textarea"
            placeholder="取引メッセージを記入してください"
            rows="1"
          >{{ $draftMessage }}</textarea>
          <div class="draft-status" id="draft-status"></div>
          @error('message')
            <div class="error-message">{{ $message }}</div>
          @enderror
        </div>

        <div class="chat-input-actions">
          <input type="file" name="image" id="image-input" class="file-input" accept="image/*">
          <label for="image-input" class="image-upload-btn">画像を追加</label>
          @error('image')
            <div class="error-message">{{ $message }}</div>
          @enderror

          <button type="submit" class="send-btn">
            <svg class="send-icon" viewBox="0 0 24 24">
              <path d="M2 21l21-9L2 3v7l15 2-15 2v7z"/>
            </svg>
          </button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- 評価モーダル -->
@if($item->is_transaction_completed && $canEvaluate && $showEvaluationModal)
  <div id="evaluation-modal" class="evaluation-modal show">
    <div class="modal-content">
      <h3 class="modal-title">取引が完了しました。</h3>
      <p class="modal-subtitle">今回の取引相手はどうでしたか？</p>

      <form method="POST" action="{{ route('evaluation.store', $item->id) }}" class="evaluation-form" id="evaluation-form">
        @csrf
        <div class="rating-section">
          <div class="interactive-rating" id="star-rating">
            @for($i = 1; $i <= 5; $i++)
              <span class="rating-star-interactive" data-rating="{{ $i }}">★</span>
            @endfor
          </div>
          <input type="hidden" name="rating" id="rating-value" required>
        </div>

        <button type="submit" class="modal-submit-btn" id="submit-btn">送信する</button>
      </form>
    </div>
  </div>
@endif

<script>
document.addEventListener('DOMContentLoaded', function() {
  const csrfToken = document.querySelector('meta[name="csrf-token"]');
  const token = csrfToken ? csrfToken.getAttribute('content') : '';

  const messageInput = document.getElementById('chat-message-input');
  const draftStatus = document.getElementById('draft-status');
  const itemId = {{ $item->id }};

  let draftTimer = null;
  let lastSavedContent = messageInput.value;

  function saveDraft() {
    const currentContent = messageInput.value;

    if (currentContent === lastSavedContent) {
      return;
    }

    draftStatus.textContent = '保存中...';
    draftStatus.className = 'draft-status saving';

    fetch(`/chat/${itemId}/save-draft`, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': token,
        'Accept': 'application/json',
      },
      body: JSON.stringify({
        message: currentContent
      })
    })
    .then(response => response.json())
    .then(data => {
      if (data.status === 'success') {
        lastSavedContent = currentContent;
        draftStatus.textContent = '下書き保存済み';
        draftStatus.className = 'draft-status saved';

        setTimeout(() => {
          draftStatus.className = 'draft-status';
        }, 3000);
      }
    })
    .catch(error => {
      console.error('下書き保存エラー:', error);
      draftStatus.textContent = '保存に失敗しました';
      draftStatus.className = 'draft-status';
    });
  }

  function clearDraft() {
    fetch(`/chat/${itemId}/clear-draft`, {
      method: 'POST',
      headers: {
        'X-CSRF-TOKEN': token,
        'Accept': 'application/json',
      }
    })
    .then(response => response.json())
    .catch(error => {
      console.error('下書き削除エラー:', error);
    });
  }

  messageInput.addEventListener('input', function() {
    if (draftTimer) {
      clearTimeout(draftTimer);
    }

    draftTimer = setTimeout(() => {
      saveDraft();
    }, 2000);
  });

  document.querySelector('.chat-input-form').addEventListener('submit', function(e) {
    if (draftTimer) {
      clearTimeout(draftTimer);
    }
    clearDraft();
  });

  window.addEventListener('beforeunload', function() {
    const currentContent = messageInput.value.trim();
    if (currentContent !== lastSavedContent.trim() && currentContent !== '') {
      const formData = new FormData();
      formData.append('message', currentContent);
      navigator.sendBeacon && navigator.sendBeacon(`/chat/${itemId}/save-draft`, formData);
    }
  });

  document.addEventListener('visibilitychange', function() {
    const currentContent = messageInput.value.trim();
    if (document.hidden && currentContent !== lastSavedContent.trim() && currentContent !== '') {
      saveDraft();
    }
  });

  const stars = document.querySelectorAll('.rating-star-interactive');
  const ratingInput = document.getElementById('rating-value');
  const submitBtn = document.getElementById('submit-btn');
  let selectedRating = 0;

  if (stars.length > 0) {
    stars.forEach((star, index) => {
      star.addEventListener('mouseenter', function() {
        const hoverRating = index + 1;
        updateStarDisplay(hoverRating, false);
      });

      star.addEventListener('click', function() {
        selectedRating = index + 1;
        ratingInput.value = selectedRating;
        updateStarDisplay(selectedRating, true);
        enableSubmitButton();
      });
    });

    document.getElementById('star-rating').addEventListener('mouseleave', function() {
      updateStarDisplay(selectedRating, true);
    });

    function updateStarDisplay(rating, isSelected) {
      stars.forEach((star, index) => {
        star.classList.remove('active', 'hover');
        if (index < rating) {
          star.classList.add(isSelected ? 'active' : 'hover');
        }
      });
    }

    function enableSubmitButton() {
      if (submitBtn) {
        submitBtn.classList.add('enabled');
      }
    }
  }
});
</script>
@endsection