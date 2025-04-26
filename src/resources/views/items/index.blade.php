@extends('layouts.app')
@section('content')
<div class="container">
  <div class="row justify-content-center">
    <div class="col-md-12">
      <div class="card">
        <div class="card-header">商品一覧</div>
        <div class="card-body">
        <!-- 検索フォーム -->
          <form action="{{ route('index') }}" method="GET" class="mb-4">
            <div class="input-group">
              <input type="text" class="form-control" name="search" placeholder="商品名を検索" value="{{ request('search') }}">
              <button class="btn btn-outline-secondary" type="submit">検索</button>
            </div>
          </form>

          <!-- 商品一覧 -->
          <div class="row">
            @forelse($items as $item)
              <div class="col-md-4 mb-4">
                <div class="card h-100">
                  <div class="position-relative">
                    @if($item->image)
                      <img src="{{ asset('storage/' . $item->image) }}" class="card-img-top" alt="{{ $item->name }}">
                    @else
                      <div class="card-img-top bg-light text-center py-5">No Image</div>
                    @endif

                    @if($item->sold)
                      <div class="position-absolute top-0 end-0 bg-danger text-white px-2 py-1">Sold</div>
                    @endif
                  </div>
                  <div class="card-body">
                    <h5 class="card-title">{{ $item->name }}</h5>
                    <p class="card-text">¥{{ number_format($item->price) }}</p>
                    <a href="{{ route('items.show', $item) }}" class="btn btn-primary">詳細を見る</a>
                  </div>
                </div>
              </div>
            @empty
              <div class="col-12">
                <div class="alert alert-info">
                  商品がありません。
                </div>
              </div>
            @endforelse
          </div>

          <!-- ページネーション -->
          <div class="d-flex justify-content-center mt-4">
            {{ $items->links() }}
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection