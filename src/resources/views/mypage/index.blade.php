@extends('layouts.app')
@section('content')
<div class="container">
  <div class="row justify-content-center">
    <div class="col-md-9">
      <div class="card">
        <div class="card-body">
          <div class="row">
            <form action="{{ route('profile.show') }}" method="GET" class="col-md-8">
              <div class="col-md-4 text-center">
                @if(Auth::user()->profile_image)
                  <img src="{{ asset(Auth::user()->profile_image) }}" class="img-fluid rounded-circle mb-3" style="max-width: 100px;" alt="{{ Auth::user()->profile_image }}">
                @else
                  <div class="bg-light rounded-circle mx-auto mb-3" style="width: 100px; height: 100px; display: flex; align-items: center; justify-content: center;">
                    <span class="h1 text-muted">{{ strtoupper(substr(Auth::user()->name, 0, 1)) }}</span>
                  </div>
                @endif
                {{ Auth::user()->name }}
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
        <ul class="list-group">
          <li class="list-buy">
            <a class="list-buy-link" href="{{ route('mypage', ['tab' => 'buy']) }}">購入した商品</a>
          </li>
          <li class="list-sell">
            <a class="list-sell-link" href="{{ route('mypage', ['tab' => 'sell']) }}">出品した商品</a>
          </li>
        </ul>
      </div>
    </div>
    <div class="mypage-buy">
      @if($items->isEmpty())
        <div class="alert alert-info">
          @if(request('tab') === 'sell')
            出品した商品はありません。
          @else
            購入した商品はありません。
          @endif
        </div>
      @else
        <div class="row">
          @foreach($items as $item)
            <div class="col-md-4 mb-4">
              <div class="card h-100">
                <a href="{{ route('items.show', $item->id) }}" class="btn btn-primary">
                  <div class="position-relative">
                    @if($item->image)
                      <img src="{{ asset($item->image) }}" class="card-img-top" alt="{{ $item->name }}" style="height: 200px; object-fit: cover;">
                    @else
                      <div class="card-img-top bg-light text-center py-5">No Image</div>
                    @endif
                  </div>
                  <div class="card-body">
                    <h5 class="card-title">{{ $item->name }}</h5>
                  </div>
                </a>
              </div>
            </div>
          @endforeach
        </div>
      @endif
    </div>
  </div>
</div>
@endsection