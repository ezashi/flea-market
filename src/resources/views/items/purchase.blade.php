@extends('layouts.app')
@section('content')
<div class="container">
  <div class="row justify-content-center">
    <div class="col-md-8">
      <div class="card">
        <div class="card-body">
          <div class="row mb-4">
            <!-- 商品情報 -->
            <div class="col-md-4">
              @if($item->image)
                <img src="{{ asset($item->image) }}" class="img-fluid" alt="{{ $item->name }}" style="height: 200px; object-fit: cover;">
              @endif
            </div>
            <div class="col-md-8">
              <h4>{{ $item->name }}</h4>
              <h5 class="text-danger">¥ {{ number_format($item->price) }}</h5>
            </div>
          </div>

          <hr>

          <form method="POST" action="{{ route('items.completePurchase', $item->id) }}">
            @csrf
            <!-- 支払い方法 -->
            <div class="mb-4">
              <h5>支払い方法</h5>
              <div class="form-check mb-2">
                <div class="form-check mb-2">
                  <select id="payment_method" class="form-select" name="payment_method">
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
              </div>
            </div>

            <hr>

            <!-- 配送先情報 -->
            <div class="mb-4">
              <div class="d-flex justify-content-between align-items-center mb-2">
                <h5>配送先</h5>
                <a href="{{ route('items.changeAddress', $item->id) }}" class="btn btn-sm btn-outline-primary">変更する</a>
              </div>
              <div class="card">
                <div class="card-body">
                  <p class="mb-1">〒{{ Auth::user()->postal_code }}</p>
                  <p class="mb-0">{{ Auth::user()->address }}{{ Auth::user()->building }}</p>
                  <input type="hidden" name="delivery_address" value="{{ Auth::user()->postal_code }} {{ Auth::user()->address }}{{ Auth::user()->building }}">
                </div>
              </div>
              @error('delivery_address')
                <div class="error-message">{{ $message }}</div>
              @enderror
            </div>

            <!-- 購入ボタン -->
            <div class="d-grid">
              <button type="submit" class="btn btn-success btn-lg">
                購入する
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection