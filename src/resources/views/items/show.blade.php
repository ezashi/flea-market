@extends('layouts.app')
@section('content')
<div class="container">
  <div class="row justify-content-center">
    <div class="col-md-10">
      <div class="card">
        <div class="card-header">商品詳細</div>
        <div class="card-body">
          <div class="row">
          <!-- 商品画像 -->
            <div class="col-md-6">
              <div class="position-relative">
                @if($item->image)
                  <img src="{{ asset('storage/' . $item->image) }}" class="img-fluid" alt="{{ $item->name }}">
                @else
                  <div class="bg-light text-center py-5">No Image</div>
                @endif

                @if($item->sold)
                  <div class="position-absolute top-0 end-0 bg-danger text-white px-3 py-2">Sold</div>
                @endif
              </div>
            </div>

            <!-- 商品情報 -->
            <div class="col-md-6">
              <h2>{{ $item->name }}</h2>
              <p class="text-muted">出品者: {{ $item->seller->name }}</p>
              <h3 class="text-danger">¥{{ number_format($item->price) }}</h3>
              <div class="d-flex mb-3">
                @if($item->seller_id !== Auth::id() && !$item->sold)
                  <a href="{{ route('items.purchase', $item) }}" class="btn btn-success me-2">購入手続きへ</a>
                @endif
                <form action="{{ route('items.like', $item) }}" method="POST">
                  @csrf
                  <button type="submit" class="btn {{ $isLiked ? 'btn-danger' : 'btn-outline-danger' }}">
                    <i class="fa fa-heart"></i> いいね {{ $item->likes()->count() }}
                  </button>
                </form>
              </div>

              <div class="mb-3">
                <h5>商品の状態</h5>
                <p>{{ $item->condition }}</p>
              </div>

              <div class="mb-3">
                <h5>カテゴリ</h5>
                <div>
                  @foreach($item->categories as $category)
                    <span class="badge bg-secondary me-1">{{ $category->name }}</span>
                  @endforeach
                </div>
              </div>
            </div>
          </div>

          <!-- 商品説明 -->
          <div class="mt-4">
            <h4>商品説明</h4>
            <div class="p-3 bg-light">
              {!! nl2br(e($item->description)) !!}
            </div>
          </div>

          <!-- コメント欄 -->
          <div class="mt-4">
            <h4>コメント ({{ $item->comments->count() }})</h4>
            <form action="{{ route('items.comment', $item) }}" method="POST" class="mb-3">
              @csrf
              <div class="form-group">
                <textarea name="content" class="form-control @error('content') is-invalid @enderror" rows="3" placeholder="コメントを入力"></textarea>
                @error('content')
                  <div class="error-message">{{ $message }}</div>
                @enderror
              </div>
              <button type="submit" class="btn btn-primary mt-2">コメントする</button>
            </form>

            <div class="mt-3">
              @forelse($item->comments as $comment)
                <div class="card mb-2">
                  <div class="card-header d-flex justify-content-between">
                    <span>{{ $comment->user->name }}</span>
                    <small>{{ $comment->created_at->format('Y/m/d H:i') }}</small>
                  </div>
                  <div class="card-body">
                    {!! nl2br(e($comment->content)) !!}
                  </div>
                </div>
              @empty
                <div class="text-center text-muted">
                  まだコメントはありません。
                </div>
              @endforelse
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection