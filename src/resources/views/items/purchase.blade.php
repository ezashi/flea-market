@extends('layouts.app')
@section('content')
<div class="container">
  <div class="row justify-content-center">
    <div class="col-md-8">
      <div class="card">
        <div class="card-header">商品購入</div>
        <div class="card-body">
          <div class="row mb-4">
            <!-- 商品情報 -->
            <div class="col-md-4">
              @if($item->image)
                <img src="{{ asset('storage/' . $item->image) }}" class="img-fluid" alt="{{ $item->name }}">
              @else
                <div class="bg-light text-center py-5">No Image</div>
              @endif
            </div>
            <div class="col-md-8">
              <h4>{{ $item->name }}</h4>
              <p class="text-muted">出品者: {{ $item->seller->name }}</p>
              <h5 class="text-danger">¥{{ number_format($item->price) }}</h5>
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
                <p class="mb-1">{{ Auth::user()->name }} 様</p>
                @if(Auth::user()->postal_code && Auth::user()->address)
                  <p class="mb-1">〒{{ Auth::user()->postal_code }}</p>
                  <p class="mb-0">{{ Auth::user()->address }}</p>
                @else
                  <p class="text-danger">住所が設定されていません。<a href="{{ route('items.changeAddress', $item) }}">住所を設定してください</a></p>
                @endif
              </div>
            </div>
          </div>

          <!-- 支払い方法 -->
          <div class="mb-4">
            <h5>支払い方法</h5>
            <div class="form-check mb-2">
              <input class="form-check-input" type="radio" name="payment_method" id="credit_card" checked>
              <label class="form-check-label" for="credit_card">
                クレジットカード
              </label>
            </div>
            <div class="form-check">
              <input class="form-check-input" type="radio" name="payment_method" id="convenience_store">
              <label class="form-check-label" for="convenience_store">
                コンビニ払い
              </label>
            </div>
          </div>

          <!-- 購入ボタン -->
          <div class="d-grid">
            <form method="POST" action="{{ route('items.completePurchase', $item) }}">
              @csrf
              <button type="submit" class="btn btn-success btn-lg" {{ (!Auth::user()->postal_code || !Auth::user()->address) ? 'disabled' : '' }}>
                購入を確定する
              </button>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection