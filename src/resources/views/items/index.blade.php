@extends('layouts.app')
@section('content')
<div class="container">
  <div class="row">
    <div class="col-12">
      <ul class="nav nav-tabs">
        <li class="nav-item">
          <a class="nav-link" href="{{ route('index') }}">おすすめ</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="{{ route('index', ['tab' => 'mylist', 'search' => $search]) }}">マイリスト</a>
        </li>
      </ul>
    </div>
  </div>

  <div class="row mt-4">
    @if(count($items) > 0)
      @foreach($items as $item)
        <div class="col-md-4 mb-4">
          <a href="{{ route('items.show', $item->id) }}" class="text-decoration-none text-dark">
            <div class="card h-100">
              <div class="position-relative">
                @if($item->image)
                  <img src="{{ asset($item->image) }}" class="card-img-top" alt="{{ $item->name }}" style="height: 200px; object-fit: cover;">
                @endif
                <!-- 購入時み -->
                @if($item->sold)
                  <div class="sold">Sold</div>
                @endif
              </div>
              <div class="card-body">
                <h5 class="card-title">{{ $item->name }}</h5>
              </div>
            </div>
          </a>
        </div>
      @endforeach
    @else
      <div class="col-12">
        <div class="alert alert-info">
          @if(request('tab') === 'mylist')
            いいねした商品がありません。
          @else
            商品がありません。
          @endif
        </div>
      </div>
    @endif
  </div>
</div>
@endsection