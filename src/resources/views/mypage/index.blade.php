@extends('layouts.app')
@section('content')
<div>
  <div>
    <form action="{{ route('profile.show') }}" method="GET">
      @if(Auth::user()->profile_image)
        <img src="{{ asset(Auth::user()->profile_image) }}" style="max-width: 100px;" alt="{{ Auth::user()->profile_image }}">
      @else
        <div>
          <span>{{ strtoupper(substr(Auth::user()->name, 0, 1)) }}</span>
        </div>
      @endif
      {{ Auth::user()->name }}
      <button type="submit">プロフィールを編集</button>
    </form>
  </div>
  <div>
    <ul>
      <li>
        <a href="{{ route('mypage', ['tab' => 'buy']) }}">購入した商品</a>
      </li>
      <li>
        <a href="{{ route('mypage', ['tab' => 'sell']) }}">出品した商品</a>
      </li>
      <li>
        <a href="{{ route('mypage', ['tab' => 'trade']) }}">取引中の商品</a>
      </li>
    </ul>
  </div>
  <div>
    @if($items->isEmpty())
      @if(request('tab') === 'sell')
        出品した商品はありません。
      @elseif(request('tab') === 'buy')
        購入した商品はありません。
      @else
        取引中の商品はありません。
      @endif
    @else
      @foreach($items as $item)
        @if(request('tab') === 'trade')
          <a href="{{ route('chat.show', $item->id) }}">
            <img src="{{ asset($item->image) }}" alt="{{ $item->name }}" style="height: 200px; object-fit: cover;">
            <h5>{{ $item->name }}</h5>
            @if(isset($unreadCounts[$item->id]) && $unreadCounts[$item->id] > 0)
              <span>{{ $unreadCounts[$item->id] }}</span>
            @endif
          </a>
        @else
          <a href="{{ route('items.show', $item->id) }}">
            <img src="{{ asset($item->image) }}" alt="{{ $item->name }}" style="height: 200px; object-fit: cover;">
            <h5>{{ $item->name }}</h5>
          </a>
        @endif
      @endforeach
    @endif
  </div>
</div>
@endsection