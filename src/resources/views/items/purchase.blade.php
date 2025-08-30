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
    max-width: 1000px;
    margin: 0 auto;
    padding: 60px;
    min-height: 100vh;
    display: flex;
    gap: 40px;
    align-items: flex-start;
  }

  .product-summary {
    flex-shrink: 0;
    width: 200px;
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
    margin-bottom: 15px;
    overflow: hidden;
  }

  .product-image-small img {
    width: 100%;
    height: 100%;
    object-fit: contain;
    background-color: #fff;
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

  .purchase-form-section {
    flex: 1;
    display: flex;
    flex-direction: column;
  }

  .left-form-section {
    flex: 1;
    margin-right: 40px;
  }

  .payment-section {
    margin-bottom: 40px;
    padding-bottom: 30px;
    border-bottom: 1px solid #ddd;
  }

  .section-label {
    font-size: 16px;
    font-weight: bold;
    color: #333;
    margin-bottom: 15px;
  }

  .payment-select {
    width: 100%;
    padding: 12px 16px;
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

  .delivery-section {
    margin-bottom: 40px;
    padding-bottom: 30px;
    border-bottom: 1px solid #ddd;
  }

  .delivery-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 15px;
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
  }

  .address-line {
    margin-bottom: 5px;
  }

  .purchase-summary-section {
    flex-shrink: 0;
    width: 350px;
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
</style>

<div class="purchase-page">
  <form method="POST" action="{{ route('items.completePurchase', $item->id) }}" id="purchase-form" novalidate>
    @csrf

    <div class="purchase-container">
      <div class="product-summary">
        <div class="product-image-small">
          @if($item->image)
            <img src="{{ asset($item->image) }}" alt="{{ $item->name }}">
          @else
            商品画像
          @endif
        </div>
        <div class="product-name-small">{{ $item->name }}</div>
        <div class="product-price-small">¥{{ number_format($item->price) }}</div>
      </div>

      <div class="purchase-form-section">
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