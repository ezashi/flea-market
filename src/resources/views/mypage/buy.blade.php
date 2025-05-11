@extends('layouts.app')
@section('content')
<div class="container">
  <div class="row justify-content-center">
    <div class="col-md-3">
      <!-- サイドバー -->
      <div class="card mb-4">
        <div class="card-header">マイページメニュー</div>
        <div class="list-group list-group-flush">
          <a href="{{ route('mypage') }}" class="list-group-item list-group-item-action {{ request('page') === null ? 'active' : '' }}">
            プロフィール
          </a>
          <a href="{{ route('mypage.buy') }}" class="list-group-item list-group-item-action {{ request('page') === 'buy' ? 'active' : '' }}">
            購入した商品
          </a>
          <a href="{{ route('mypage.sell') }}" class="list-group-item list-group-item-action {{ request('page') === 'sell' ? 'active' : '' }}">
            出品した商品
          </a>
          <a href="{{ route('profile.show') }}" class="list-group-item list-group-item-action">
            設定
          </a>
        </div>
      </div>
    </div>
    <div class="col-md-9">
      <div class="card">
        <div class="card-header">購入した商品一覧</div>
        <div class="card-body">
          @if($items->isEmpty())
            <div class="alert alert-info">
              購入した商品はありません。
            </div>
          @else
            <div class="row">
              @foreach($items as $item)
                <div class="col-md-4 mb-4">
                  <div class="card h-100">
                    <div class="position-relative">
                      @if($item->image)
                        <img src="{{ asset('images/items/' . basename($item->image)) }}" class="card-img-top" alt="{{ $item->name }}">
                      @else
                        <div class="card-img-top bg-light text-center py-5">No Image</div>
                      @endif
                    </div>
                    <div class="card-body">
                      <h5 class="card-title">{{ $item->name }}</h5>
                      <p class="card-text">¥{{ number_format($item->price) }}</p>
                      <p class="card-text">
                        <small class="text-muted">購入日：{{ $item->updated_at->format('Y/m/d') }}</small>
                      </p>
                      <a href="{{ route('items.show', $item) }}" class="btn btn-primary">詳細を見る</a>
                    </div>
                  </div>
                </div>
              @endforeach
            </div>

            <!-- ページネーション -->
            <div class="d-flex justify-content-center mt-4">
              {{ $items->links() }}
            </div>
          @endif
        </div>
      </div>
    </div>
  </div>
</div>
@endsection