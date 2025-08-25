@extends('layouts.app')
@section('content')
<div>
  <div>
    <!-- <form action="{{ route('') }}" method="POST"> -->
      @if(Auth::user()->profile_image)
        <img src="{{ asset(Auth::user()->profile_image) }}" style="max-width: 100px;" alt="{{ Auth::user()->profile_image }}">
      @else
        <div style="width: 100px; height: 100px; display: flex; align-items: center; justify-content: center;">
          <span class="h1 text-muted">{{ strtoupper(substr(Auth::user()->name, 0, 1)) }}</span>
        </div>
      @endif
      「 {{ Auth::user()->name }} 」さんとの取引画面
      <button type="submit">取引を完了する</button>
    </form>
  </div>
  <div>
    <img src="{{ asset($item->image) }}" alt="{{ $item->name }}" style="height: 200px;">
    <h5>{{ $item->name }}</h5>
    <h3>¥{{ number_format($item->price) }}</h3>
  </div>
  <div>
    
  </div>
</div>
@endsection