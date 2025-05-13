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
                  <img src="{{ asset('storage/images/items/' . basename(Auth::user()->profile_image)) }}" class="img-fluid rounded-circle mb-3" style="max-width: 150px;" alt="{{ Auth::user()->profile_image }}">
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
        <form action="{{ route('mypage.buy') }}" method="GET" class="col-md-8">
          <button type="submit" class="btn btn-buy" style="background: none; border: none; cursor: pointer;">
            購入した商品
          </button>
        </form>
        <form action="{{ route('mypage.sell') }}" method="GET" class="col-md-8">
          <button type="submit" class="btn btn-sell" style="background: none; border: none; cursor: pointer;">
            出品した商品
          </button>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection