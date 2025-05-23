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

          <!-- 支払い方法 -->
          <div class="mb-4">
            <h5>支払い方法</h5>
            <div class="form-check mb-2">
              <form action="{{ route('items.selectPayment', $item) }}" method="POST">
                @csrf
                <div class="form-check mb-2">
                  <select id="payment_method" class="form-select @error('payment_method') is-invalid @enderror" name="payment_method" required onChange="this.form.submit()">
                    <option value="" disabled {{ !session('selected_payment') ? 'selected' : '' }}>
                      選択してください
                    </option>
                    <option value="コンビニ払い" {{ session('selected_payment') == 'コンビニ払い' ? 'selected' : '' }}>
                      コンビニ払い
                    </option>
                    <option value="カード払い" {{ session('selected_payment') == 'カード払い' ? 'selected' : '' }}>
                      カード払い
                    </option>
                  </select>
                </div>
              </form>
            </div>
          </div>

          <hr>

          <!-- 配送先情報 -->
          <div class="mb-4">
            <div class="d-flex justify-content-between align-items-center mb-2">
              <h5>配送先</h5>
              <a href="{{ route('items.changeAddress', $item) }}" class="btn btn-sm btn-outline-primary">変更する</a>
            </div>
            <div class="card">
              <div class="card-body">
                <p class="mb-1">〒{{ Auth::user()->postal_code }}</p>
                <p class="mb-0">{{ Auth::user()->address }}{{ Auth::user()->building }}</p>
              </div>
            </div>
          </div>

          <div class="card">
            <div class="price">
              商品代金 ¥{{ number_format($item->price) }}
            </div>
            <div class="card-body">
              @if(session('selected_payment'))
                支払い方法 {{ session('selected_payment') }}
              @endif
            </div>
          </div>
          <!-- 購入ボタン -->
          <div class="d-grid">
            <form method="POST" action="{{ route('items.completePurchase', $item) }}">
              @csrf
              <button type="submit" class="btn btn-success btn-lg" {{ (!Auth::user()->postal_code || !Auth::user()->address || !Auth::user()->building) ? 'disabled' : '' }}>
                購入する
              </button>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection