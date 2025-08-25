@extends('layouts.app')
@section('content')
<div>
  <div>
    <ul>
      <li>
        <a href="{{ route('index') }}">おすすめ</a>
      </li>
      <li>
        <a href="{{ route('index', ['tab' => 'mylist', 'search' => $search]) }}">マイリスト</a>
      </li>
    </ul>
  </div>

  <div>
    @if(count($items) > 0)
      @foreach($items as $item)
        <a href="{{ route('items.show', $item->id) }}">
          @if($item->image)
            <img src="{{ asset($item->image) }}" alt="{{ $item->name }}" style="width: 200px; height: 200px; object-fit: cover;">
          @endif
          <!-- 購入時み -->
          @if($item->sold)
            <div>Sold</div>
          @endif
          <h5>{{ $item->name }}</h5>
        </a>
      @endforeach
    @else
      @if(request('tab') === 'mylist')
        いいねした商品がありません。
      @endif
    @endif
  </div>
</div>
@endsection