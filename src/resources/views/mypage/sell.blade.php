@extends('layouts.app')
@section('content')
<div class="container">
  <div class="row justify-content-center">
    <div class="col-md-9">
      <div class="card">
        <div class="card-header">出品した商品一覧</div>
        <div class="card-body">
          @if($items->isEmpty())
            <div class="alert alert-info">
              出品した商品はありません。
            </div>
          @else
            <div class="row">
              @foreach($items as $item)
                <div class="col-md-4 mb-4">
                  <div class="card h-100">
                    <a href="{{ route('items.show', $item) }}" class="text-decoration-none text-dark">
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
  </div>
</div>
@endsection