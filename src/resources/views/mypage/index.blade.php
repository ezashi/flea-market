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
        <div class="card-header">プロフィール</div>
        <div class="card-body">
          <div class="row">
            <div class="col-md-4 text-center">
              @if(Auth::user()->profile_image)
                <img src="{{ asset('storage/' . Auth::user()->profile_image) }}" class="img-fluid rounded-circle mb-3" style="max-width: 150px;" alt="{{ Auth::user()->name }}">
              @else
                <div class="bg-light rounded-circle mx-auto mb-3" style="width: 150px; height: 150px; display: flex; align-items: center; justify-content: center;">
                  <span class="h1 text-muted">{{ strtoupper(substr(Auth::user()->name, 0, 1)) }}</span>
                </div>
              @endif
            </div>
            <div class="col-md-8">
              <h3>{{ Auth::user()->name }}</h3>
              <p class="text-muted">{{ Auth::user()->email }}</p>
              @if(Auth::user()->postal_code && Auth::user()->address)
                <p><strong>住所:</strong> 〒{{ Auth::user()->postal_code }} {{ Auth::user()->address }}</p>
              @else
                <p class="text-danger">住所が設定されていません。<a href="{{ route('profile.show') }}">設定する</a></p>
              @endif
              <div class="mt-3">
                <a href="{{ route('profile.show') }}" class="btn btn-outline-primary">プロフィール編集</a>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection