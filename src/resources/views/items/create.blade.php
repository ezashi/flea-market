@extends('layouts.app')
@section('content')
<div class="container">
  <div class="row justify-content-center">
    <div class="col-md-8">
      <div class="card">
        <h2 class="card-header">商品の出品</h2>
        <div class="card-body">
          <form method="POST" action="{{ route('items.store') }}" enctype="multipart/form-data" novalidate>
            @csrf
            <!-- 商品画像 -->
            <div class="mb-3 row">
              <label for="image" class="col-md-4 col-form-label text-md-end">商品画像</label>
              <div class="col-md-6">
                <input id="image" type="file" class="form-control @error('image') is-invalid @enderror" name="image">
                @error('image')
                  <div class="error-message">{{ $message }}</div>
                @enderror
              </div>
            </div>

            <h3 class="card-header">商品の詳細</h3>
            <!-- カテゴリ -->
            <div class="mb-3 row">
              <label class="col-md-4 col-form-label text-md-end">カテゴリー</label>
              <div class="col-md-6">
                @foreach($categories as $category)
                  <div class="category-button">
                    <input class="category-input @error('categories') is-invalid @enderror" type="checkbox" name="categories[]" value="{{ $category->id }}" id="category{{ $category->id }}" {{ in_array($category->id, old('categories', [])) ? 'checked' : '' }}>
                    <label class="category-button" for="category{{ $category->id }}">
                      {{ $category->name }}
                    </label>
                  </div>
                @endforeach
                @error('categories')
                  <div class="error-message">{{ $message }}</div>
                @enderror
              </div>
            </div>

            <!-- 商品の状態 -->
            <div class="mb-3 row">
              <label for="condition" class="col-md-4 col-form-label text-md-end">商品の状態</label>
              <div class="col-md-6">
                <select id="condition" class="form-select @error('condition') is-invalid @enderror" name="condition" required>
                  @if(!session('condition'))
                    <option value="" disabled selected>選択してください</option>
                  @endif
                  @foreach($conditions as $condition)
                    <option value="{{ $condition->name }}" {{ old('condition') == $condition->name ? 'selected' : '' }}>
                      {{ $condition->name }}
                    </option>
                  @endforeach
                </select>
                @error('condition')
                  <div class="error-message">{{ $message }}</div>
                @enderror
              </div>
            </div>

            <h3 class="card-header">商品名と説明</h3>
            <!-- 商品名 -->
            <div class="mb-3 row">
              <label for="name" class="col-md-4 col-form-label text-md-end" >商品名</label>
              <div class="col-md-6">
                <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required>
                @error('name')
                  <div class="error-message">{{ $message }}</div>
                @enderror
              </div>
            </div>

            <!-- ブランド名 -->
            <div class="mb-3 row">
              <label for="brand" class="col-md-4 col-form-label text-md-end">ブランド名</label>
              <div class="col-md-6">
                <input id="brand" type="text" class="form-control" name="brand" value="{{ old('brand') }}">
              </div>
            </div>
            <!-- 商品説明 -->
            <div class="mb-3 row">
              <label for="description" class="col-md-4 col-form-label text-md-end">商品の説明</label>
              <div class="col-md-6">
                <textarea id="description" class="form-control @error('description') is-invalid @enderror" name="description" rows="5" required>{{ old('description') }}</textarea>
                @error('description')
                  <div class="error-message">{{ $message }}</div>
                @enderror
              </div>
            </div>

            <!-- 価格 -->
            <div class="mb-3 row">
              <label for="price" class="col-md-4 col-form-label text-md-end">販売価格</label>
              <div class="col-md-6">
                <div class="input-group">
                  <span class="input-group-text">¥</span>
                  <input id="price" type="number" class="form-control @error('price') is-invalid @enderror" name="price" value="{{ old('price') }}" required>
                </div>
                @error('price')
                  <div class="error-message">{{ $message }}</div>
                @enderror
              </div>
            </div>

            <!-- 送信ボタン -->
            <div class="mb-3 row">
              <div class="col-md-6 offset-md-4">
                <button type="submit" class="btn btn-primary">
                  出品する
                </button>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection