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
        </div>
      </div>
    </div>
    <div class="col-md-9">
      <div class="card">
        <div class="card-header">プロフィール</div>
        <div class="card-body">
          <div class="row">
            <form action="{{ route('profile.show') }}" method="GET" class="col-md-8">
              <div class="col-md-4 text-center">
                @if(Auth::user()->profile_image)
                  <img src="{{ asset('images/items/' . basename(Auth::user()->profile_image)) }}" class="img-fluid rounded-circle mb-3" style="max-width: 150px;" alt="{{ Auth::user()->name }}">
                @else
                  <div class="bg-light rounded-circle mx-auto mb-3" style="width: 150px; height: 150px; display: flex; align-items: center; justify-content: center;">
                    <span class="h1 text-muted">{{ strtoupper(substr(Auth::user()->name, 0, 1)) }}</span>
                  </div>
                @endif
              </div>
              <div class="mt-3">
                <button type="submit" class="btn btn-primary">プロフィールを編集</button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
    <div class="card mb-4">
      <div class="list-group list-group-flush">
        <a href="{{ route('mypage.buy') }}" class="list-group-item list-group-item-action {{ request('page') === 'buy' ? 'active' : '' }}">
          購入した商品
        </a>
        <a href="{{ route('mypage.sell') }}" class="list-group-item list-group-item-action {{ request('page') === 'sell' ? 'active' : '' }}">
          出品した商品
        </a>
      </div>
    </div>
  </div>
</div>
@endsection