@extends('layouts.app')
@section('content')
<style>
  .purchase-page {
    background-color: #fff;
    min-height: 100vh;
    padding: 0;
  }

  .purchase-container {
    background-color: white;
    max-width: 1200px;
    margin: 0 auto;
    padding: 40px 20px;
    min-height: 100vh;
    display: flex;
    gap: 40px;
    align-items: flex-start;
    justify-content: space-between;
  }

  .left-column {
    display: flex;
    flex-direction: column;
    gap: 40px;
    flex: 1;
    min-width: 0;
  }

  .product-summary {
    flex-shrink: 0;
    display: flex;
    flex-direction: column;
    align-items: flex-start;
    gap: 20px;
  }

  .product-info {
    padding: 0 0 30px 0;
    display: flex;
    flex-direction: row;
    align-items: center;
    gap: 30px;
    border-bottom: 1px solid #000;
    width: 100%;
  }

  .product-image-small {
    width: 120px;
    height: 120px;
    background-color: #fff;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #fff;
    font-size: 14px;
    border-radius: 8px;
    overflow: hidden;
    flex-shrink: 0;
  }

  .product-image-small img {
    width: 100%;
    height: 100%;
    object-fit: contain;
    background-color: #fff;
  }

  .product-info-inline {
    display: flex;
    flex-direction: column;
    justify-content: center;
    flex: 1;
  }

  .product-name-small {
    font-size: 16px;
    font-weight: bold;
    color: #000;
    margin-bottom: 8px;
  }

  .product-price-small {
    font-size: 16px;
    color: #000;
    font-weight: bold;
  }

  .payment-section,
  .delivery-section {
    padding: 0 0 30px 0;
    border-bottom: 1px solid #000;
    width: 100%;
  }

  .section-label {
    font-size: 16px;
    font-weight: bold;
    color: #333;
    margin-bottom: 15px;
  }

  .payment-section,
  .delivery-section {
    margin-top: 0.5rem;
  }

  .payment-select {
    width: 100%;
    max-width: 400px;
    padding: 12px 16px;
    margin: 10px 0;
    border: 2px solid #ddd;
    border-radius: 5px;
    font-size: 16px;
    outline: none;
    background-color: white;
    cursor: pointer;
    transition: border-color 0.2s;
  }

  .payment-select:focus {
    border-color: #ff6b6b;
  }

  .delivery-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 15px;
    flex-wrap: wrap;
    gap: 10px;
  }

  .change-address-link {
    color: #007bff;
    text-decoration: none;
    font-size: 14px;
  }

  .change-address-link:hover {
    text-decoration: underline;
  }

  .address-info {
    color: #333;
    line-height: 1.5;
    margin: 20px 0;
    font-weight: bold;
  }

  .address-line {
    margin-bottom: 5px;
  }

  .purchase-summary-section {
    flex-shrink: 0;
    flex: 0 0 320px;
    min-width: 280px;
  }

  .purchase-summary {
    background-color: white;
    border: 1px solid #ddd;
    border-radius: 8px;
    padding: 30px;
  }

  .summary-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 15px 0;
    border-bottom: 1px solid #f0f0f0;
  }

  .summary-row:last-child {
    border-bottom: none;
  }

  .summary-label {
    font-size: 16px;
    color: #333;
  }

  .summary-value {
    font-size: 16px;
    font-weight: bold;
    color: #333;
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
    margin-top: 30px;
  }

  .purchase-button:hover {
    background-color: #e55555;
  }

  .error-message {
    color: #dc3545;
    font-size: 12px;
    margin-top: 5px;
  }

  /* PC大画面対応 (1400px以上) */
  @media (min-width: 1400px) {
    .purchase-container {
      max-width: 1400px;
      padding: 60px 40px;
    }

    .purchase-summary-section {
      flex: 0 0 360px;
    }

    .product-info {
      gap: 50px;
    }
  }

  /* PC標準サイズ対応 (1200px-1399px) */
  @media (min-width: 1200px) and (max-width: 1399px) {
    .purchase-container {
      padding: 50px 30px;
    }
  }

  /* タブレット大画面対応 (851px-1199px) */
  @media (min-width: 851px) and (max-width: 1199px) {
    .purchase-container {
      padding: 40px 20px;
      gap: 30px;
    }

    .purchase-summary-section {
      flex: 0 0 280px;
    }

    .purchase-summary {
      padding: 25px;
    }
  }

  /* タブレット標準サイズ対応 (765px-850px) */
  @media (min-width: 765px) and (max-width: 850px) {
    .purchase-container {
      padding: 30px 15px;
      gap: 25px;
    }

    .left-column {
      gap: 30px;
    }

    .product-info {
      gap: 20px;
      padding-bottom: 20px;
    }

    .product-image-small {
      width: 100px;
      height: 100px;
    }

    .product-name-small,
    .product-price-small {
      font-size: 14px;
    }

    .purchase-summary-section {
      flex: 0 0 250px;
      min-width: 250px;
    }

    .purchase-summary {
      padding: 20px;
    }

    .section-label {
      font-size: 15px;
      margin-bottom: 12px;
    }

    .payment-select {
      padding: 10px 14px;
      font-size: 14px;
    }

    .summary-label,
    .summary-value {
      font-size: 14px;
    }

    .purchase-button {
      padding: 12px;
      font-size: 14px;
    }
  }

  /* モバイル対応 (764px以下) */
  @media (max-width: 764px) {
    .purchase-container {
      flex-direction: column;
      padding: 20px 15px;
      gap: 30px;
    }

    .left-column {
      flex: none;
      width: 100%;
    }

    .purchase-summary-section {
      flex: none;
      width: 100%;
    }

    .product-info {
      flex-direction: column;
      align-items: center;
      text-align: center;
      gap: 15px;
    }

    .product-image-small {
      width: 80px;
      height: 80px;
    }

    .delivery-header {
      flex-direction: column;
      align-items: flex-start;
      gap: 5px;
    }

    .change-address-link {
      align-self: flex-end;
    }

    .address-info {
      margin: 15px 0;
    }

    .purchase-summary {
      padding: 20px;
    }
  }

  /* 極小画面対応 (480px以下) */
  @media (max-width: 480px) {
    .purchase-container {
      padding: 15px 10px;
    }

    .product-info {
      padding-bottom: 15px;
    }

    .payment-section,
    .delivery-section {
      padding-bottom: 20px;
    }

    .purchase-summary {
      padding: 15px;
    }

    .summary-row {
      padding: 10px 0;
    }
  }
</style>

<div class="purchase-page">
  <form method="POST" action="{{ route('items.completePurchase', $item->id) }}" id="purchase-form" novalidate>
    @csrf

    <div class="purchase-container">
      <div class="left-column">
        <div class="product-summary">
          <div class="product-info">
            <div class="product-image-small">
              @if($item->image)
                <img src="{{ asset($item->image) }}" alt="{{ $item->name }}">
              @else
                商品画像
              @endif
            </div>
            <div class="product-info-inline">
              <div class="product-name-small">{{ $item->name }}</div>
              <div class="product-price-small">¥{{ number_format($item->price) }}</div>
            </div>
          </div>

          <div class="payment-section">
            <div class="section-label">支払い方法</div>
            <select id="payment_method" name="payment_method" class="payment-select" required>
              <option value="">選択してください</option>
              <option value="コンビニ払い" {{ old('payment_method') == 'コンビニ払い' ? 'selected' : '' }}>
                コンビニ払い
              </option>
              <option value="カード払い" {{ old('payment_method') == 'カード払い' ? 'selected' : '' }}>
                カード払い
              </option>
            </select>
            @error('payment_method')
              <div class="error-message">{{ $message }}</div>
            @enderror
          </div>

          <div class="delivery-section">
            <div class="delivery-header">
              <div class="section-label">配送先</div>
              <a href="{{ route('items.changeAddress', $item->id) }}" class="change-address-link">変更する</a>
            </div>
            <div class="address-info">
              <div class="address-line">〒{{ Auth::user()->postal_code ?? '未設定' }}</div>
              <div class="address-line">{{ Auth::user()->address ?? '未設定' }}{{ Auth::user()->building ?? '' }}</div>
            </div>
            <input type="hidden" name="delivery_address" value="{{ Auth::user()->postal_code }} {{ Auth::user()->address }}{{ Auth::user()->building }}">
            @error('delivery_address')
              <div class="error-message">{{ $message }}</div>
            @enderror
          </div>
        </div>
      </div>

      <div class="purchase-summary-section">
        <div class="purchase-summary">
          <div class="summary-row">
            <div class="summary-label">商品代金</div>
            <div class="summary-value">¥{{ number_format($item->price) }}</div>
          </div>
          <div class="summary-row">
            <div class="summary-label">支払い方法</div>
            <div class="summary-value" id="selected-payment">コンビニ払い</div>
          </div>

          <button type="submit" class="purchase-button">購入する</button>
        </div>
      </div>
    </div>
  </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
  const form = document.getElementById('purchase-form');
  const paymentSelect = document.getElementById('payment_method');
  const selectedPayment = document.getElementById('selected-payment');

  // 支払い方法変更時の表示更新
  paymentSelect.addEventListener('change', function() {
    selectedPayment.textContent = this.value || 'コンビニ払い';
  });

  // 初期値設定
  if (paymentSelect.value) {
    selectedPayment.textContent = paymentSelect.value;
  }
});
</script>
@endsection