@extends('layouts.app')
@section('content')
<div class="container">
  <div class="row justify-content-center">
    <div class="col-md-10">
      <div class="card">
        <div class="card-body">
          <div class="row">
          <!-- 商品画像 -->
            <div class="col-md-6">
              <div class="position-relative">
                @if($item->image)
                  <img src="{{ asset($item->image) }}" class="img-fluid" alt="{{ $item->name }}" style="height: 200px;">
                @endif

                @if($item->sold)
                  <div class="position-absolute top-0 end-0 bg-danger text-white px-3 py-2">Sold</div>
                @endif
              </div>
            </div>

            <!-- 商品情報 -->
            <div class="col-md-6">
              <h2>{{ $item->name }}</h2>
              @if($item->brand)
                <p>{{ $item->brand }}</p>
              @endif
              <h3 class="text-danger">¥{{ number_format($item->price) }}</h3>

              <div class="d-flex mb-3">
                <form action="{{ route('items.like', $item) }}" method="POST">
                  @csrf
                  <button type="submit" class="btn {{ $isLiked ? 'btn-danger' : 'btn-outline-danger' }}">
                    <i class="fa fa-heart"></i> いいね {{ $item->likes()->count() }}
                  </button>
                </form>
                <label>コメント ({{ $item->comments->count() }})</label>
                <form action="{{ route('items.purchase', $item) }}" method="GET" class="me-2">
                  @csrf
                  @if($item->seller_id !== Auth::id() && !$item->sold)
                    <button type="submit" class="btn btn-primary">購入手続きへ</button>
                  @endif
                </form>
              </div>

              <!-- 商品説明 -->
              <div class="mt-4">
                <h4>商品説明</h4>
                <div class="p-3 bg-light">
                  {!! nl2br(e($item->description)) !!}
                </div>
              </div>

              <div class="mb-3">
                <h5>商品の情報</h5>
                @foreach($item->categories as $category)
                  <span class="badge bg-secondary me-1">カテゴリー {{ $category->name }}</span>
                @endforeach
                <p>商品の状態 {{ $item->condition }}</p>
              </div>

          <!-- コメント欄 -->
          <div class="mt-4">
            <h4>コメント ({{ $item->comments->count() }})</h4>
            <form action="{{ route('items.comment', $item) }}" method="POST" class="mb-3">
              @csrf
              <div class="mt-3">
                @foreach($item->comments as $comment)
                  <div class="card mb-2">
                    <div class="card-header d-flex justify-content-between">
                      <span>
                        <img src="{{ asset('images/items/' . basename($comment->user->profile_image)) }}" alt="{{ $comment->user->name }}" class="rounded-circle" style="width: 40px; height: 40px;"> {{ $comment->user->name }}
                      </span>
                    </div>
                    <div class="card-body">
                      {!! nl2br(e($comment->content)) !!}
                    </div>
                  </div>
                @endforeach
                <div class="text-center text-muted">
                  ここにコメントが入ります
                </div>
              </div>
              <div class="form-group">
                <p for="content" class="form-label">商品へのコメント</p>
                <textarea name="content" class="form-control @error('content') is-invalid @enderror" rows="3"></textarea>
                @error('content')
                  <div class="error-message">{{ $message }}</div>
                @enderror
              </div>
              <button type="submit" class="btn btn-primary mt-2">コメントを送信する</button>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection