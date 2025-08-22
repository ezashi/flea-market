@extends('layouts.app')
@section('content')
<div>
  <h2>商品の出品</h2>
  <div>
    <form method="POST" action="{{ route('items.store') }}" enctype="multipart/form-data" novalidate>
      @csrf
      <!-- 商品画像 -->
      <div>
        <label for="image">商品画像</label>
        <input id="image" type="file" name="image">
          @error('image')
            <div class="error-message">{{ $message }}</div>
          @enderror
      </div>

      <h3>商品の詳細</h3>
      <!-- カテゴリ -->
      <div>
        <label>カテゴリー</label>
        @foreach($categories as $category)
          <input type="checkbox" name="categories[]" value="{{ $category->id }}" id="category{{ $category->id }}" {{ in_array($category->id, old('categories', [])) ? 'checked' : '' }}>
          <label for="category{{ $category->id }}">
            {{ $category->name }}
          </label>
        @endforeach
        @error('categories')
          <div class="error-message">{{ $message }}</div>
        @enderror
      </div>

      <!-- 商品の状態 -->
      <div>
        <label for="condition">商品の状態</label>
        <select id="condition" name="condition" required>
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

      <h3>商品名と説明</h3>
      <!-- 商品名 -->
      <div>
        <label for="name">商品名</label>
        <input id="name" type="text" name="name" value="{{ old('name') }}" required>
        @error('name')
          <div class="error-message">{{ $message }}</div>
        @enderror
      </div>

      <!-- ブランド名 -->
      <div>
        <label for="brand">ブランド名</label>
        <input id="brand" type="text" name="brand" value="{{ old('brand') }}">
      </div>
      <!-- 商品説明 -->
      <div>
        <label for="description">商品の説明</label>
        <textarea id="description" name="description" rows="5" required>{{ old('description') }}</textarea>
        @error('description')
          <div class="error-message">{{ $message }}</div>
        @enderror
      </div>

      <!-- 価格 -->
      <div>
        <label for="price">販売価格</label>
        <span>¥</span>
        <input id="price" type="number" name="price" value="{{ old('price') }}" required>
        @error('price')
          <div class="error-message">{{ $message }}</div>
        @enderror
      </div>

      <!-- 送信ボタン -->
      <div>
        <button type="submit">出品する</button>
      </div>
    </form>
  </div>
</div>
@endsection