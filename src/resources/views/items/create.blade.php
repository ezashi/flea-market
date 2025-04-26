@extends('layouts.app')
@section('content')
<div class="container">
  <div class="row justify-content-center">
    <div class="col-md-8">
      <div class="card">
        <div class="card-header">商品出品</div>
        <div class="card-body">
          <form method="POST" action="{{ route('items.store') }}" enctype="multipart/form-data">
            @csrf
            <!-- 商品名 -->
            <div class="mb-3 row">
              <label for="name" class="col-md-4 col-form-label text-md-end">商品名</label>
              <div class="col-md-6">
                <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required>
                @error('name')
                  <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                  </span>
                @enderror
              </div>
            </div>

            <!-- 価格 -->
            <div class="mb-3 row">
              <label for="price" class="col-md-4 col-form-label text-md-end">価格</label>
              <div class="col-md-6">
                <div class="input-group">
                  <span class="input-group-text">¥</span>
                  <input id="price" type="number" class="form-control @error('price') is-invalid @enderror" name="price" value="{{ old('price') }}" required>
                </div>
                @error('price')
                  <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                  </span>
                @enderror
              </div>
            </div>

            <!-- 商品の状態 -->
            <div class="mb-3 row">
              <label for="condition" class="col-md-4 col-form-label text-md-end">商品の状態</label>
              <div class="col-md-6">
                <select id="condition" class="form-select @error('condition') is-invalid @enderror" name="condition" required>
                  <option value="">選択してください</option>
                  <option value="良好" {{ old('condition') == '良好' ? 'selected' : '' }}>良好</option>
                  <option value="目立った傷や汚れなし" {{ old('condition') == '目立った傷や汚れなし' ? 'selected' : '' }}>目立った傷や汚れなし</option>
                  <option value="やや傷や汚れあり" {{ old('condition') == 'やや傷や汚れあり' ? 'selected' : '' }}>やや傷や汚れあり</option>
                  <option value="状態が悪い" {{ old('condition') == '状態が悪い' ? 'selected' : '' }}>状態が悪い</option>
                </select>
                @error('condition')
                  <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                  </span>
                @enderror
              </div>
            </div>

            <!-- カテゴリ -->
            <div class="mb-3 row">
              <label class="col-md-4 col-form-label text-md-end">カテゴリ</label>
              <div class="col-md-6">
                @foreach($categories as $category)
                  <div class="form-check">
                    <input class="form-check-input @error('categories') is-invalid @enderror" type="checkbox" name="categories[]" value="{{ $category->id }}" id="category{{ $category->id }}" {{ in_array($category->id, old('categories', [])) ? 'checked' : '' }}>
                    <label class="form-check-label" for="category{{ $category->id }}">
                      {{ $category->name }}
                    </label>
                  </div>
                @endforeach
                @error('categories')
                  <span class="invalid-feedback d-block" role="alert">
                    <strong>{{ $message }}</strong>
                  </span>
                @enderror
              </div>
            </div>

            <!-- 商品説明 -->
            <div class="mb-3 row">
              <label for="description" class="col-md-4 col-form-label text-md-end">商品の説明</label>
              <div class="col-md-6">
                <textarea id="description" class="form-control @error('description') is-invalid @enderror" name="description" rows="5" required>{{ old('description') }}</textarea>
                @error('description')
                  <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                  </span>
                @enderror
              </div>
            </div>

            <!-- 商品画像 -->
            <div class="mb-3 row">
              <label for="image" class="col-md-4 col-form-label text-md-end">商品画像</label>
              <div class="col-md-6">
                <input id="image" type="file" class="form-control @error('image') is-invalid @enderror" name="image">
                @error('image')
                  <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                  </span>
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