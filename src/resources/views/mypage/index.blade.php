@extends('layouts.app')
@section('content')
<div>
  <form action="{{ route('profile.show') }}" method="GET">
    @if(Auth::user()->profile_image)
      <img src="{{ asset(Auth::user()->profile_image) }}" style="max-width: 100px;" alt="{{ Auth::user()->profile_image }}">
    @else
      <div style="width: 100px; height: 100px; display: flex; align-items: center; justify-content: center;">
        <span class="h1 text-muted">{{ strtoupper(substr(Auth::user()->name, 0, 1)) }}</span>
      </div>
    @endif
    {{ Auth::user()->name }}
    <button type="submit">プロフィールを編集</button>
  </form>
  <div>
    <ul>
      <li>
        <a href="{{ route('mypage', ['tab' => 'buy']) }}">購入した商品</a>
      </li>
      <li>
        <a href="{{ route('mypage', ['tab' => 'sell']) }}">出品した商品</a>
      </li>
    </ul>
  </div>
  <div>
    @if($items->isEmpty())
      <div>
      @if(request('tab') === 'sell')
        出品した商品はありません。
      @else
        購入した商品はありません。
      @endif
      </div>
    @else
      <div>
        @foreach($items as $item)
          <a href="{{ route('items.show', $item->id) }}">
            @if($item->image)
              <img src="{{ asset($item->image) }}" alt="{{ $item->name }}" style="height: 200px; object-fit: cover;">
            @else
              <div>No Image</div>
            @endif
            <h5>{{ $item->name }}</h5>
          </a>
        @endforeach
      </div>
    @endif
  </div>
</div>
@endsection