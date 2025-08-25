@extends('layouts.app')
@section('content')
<div>
  <div>
    <div>
      @if($item->image)
        <img src="{{ asset($item->image) }}" alt="{{ $item->name }}" style="height: 200px; object-fit: cover;">
      @endif
    </div>
    <h4>{{ $item->name }}</h4>
    <h5>¥ {{ number_format($item->price) }}</h5>
  </div>

  <hr>
  <div>
    <form method="POST" action="{{ route('items.completePurchase', $item->id) }}">
      @csrf
      <div>
        <h5>支払い方法</h5>
        <select id="payment_method" name="payment_method">
          <option value="" disabled selected>
            選択してください
          </option>
          <option value="コンビニ払い">
            コンビニ払い
          </option>
          <option value="カード払い">
            カード払い
          </option>
        </select>
        @error('payment_method')
          <div class="error-message">{{ $message }}</div>
        @enderror
      </div>

      <hr>
      <div>
        <h5>配送先</h5>
        <a href="{{ route('items.changeAddress', $item->id) }}">変更する</a>
        <p>〒{{ Auth::user()->postal_code }}</p>
        <p>{{ Auth::user()->address }}{{ Auth::user()->building }}</p>
        @error('delivery_address')
          <div class="error-message">{{ $message }}</div>
        @enderror
      </div>

      <button type="submit">購入する</button>
    </form>
  </div>
</div>
@endsection