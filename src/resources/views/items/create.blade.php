@extends('layouts.app')
@section('content')
<style>
  .create-page {
    background-color: #f5f5f5;
    min-height: 100vh;
  }

  .create-form-container {
    background-color: white;
    max-width: 600px;
    margin: 40px auto;
    padding: 40px;
    border-radius: 8px;
    box-shadow: none;
  }

  .create-form-title {
    text-align: center;
    font-size: 24px;
    font-weight: bold;
    margin-bottom: 40px;
    color: #333;
  }

  .image-upload-area {
    border: 2px dashed #ddd;
    border-radius: 8px;
    padding: 60px 20px;
    text-align: center;
    background-color: #fafafa;
    position: relative;
    margin-bottom: 40px;
  }

  .image-select-btn {
    background-color: #ff6b6b;
    color: white;
    padding: 8px 16px;
    border: 2px solid #ff6b6b;
    border-radius: 5px;
    font-size: 14px;
    cursor: pointer;
    transition: all 0.2s;
    text-decoration: none;
    display: inline-block;
  }

  .image-select-btn:hover {
    background-color: white;
    color: #ff6b6b;
  }

  .file-input {
    display: none;
  }

  .section-title {
    font-size: 18px;
    font-weight: bold;
    color: #333;
    margin-bottom: 25px;
    border-bottom: 1px solid #ddd;
    padding-bottom: 10px;
  }

  .category-section {
    margin-bottom: 40px;
  }

  .category-label {
    display: block;
    margin-bottom: 15px;
    font-size: 16px;
    color: #333;
    font-weight: 500;
  }

  .category-tags {
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
  }

  .category-tag {
    background-color: white;
    border: 2px solid #ff6b6b;
    color: #ff6b6b;
    padding: 8px 16px;
    border-radius: 20px;
    font-size: 14px;
    cursor: pointer;
    transition: all 0.3s ease;
    text-decoration: none;
    display: inline-block;
    user-select: none;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
  }

  .category-tag:hover {
    background-color: #ffe6e6;
    transform: translateY(-1px);
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
  }

  .category-tag.selected {
    background-color: #ff6b6b !important;
    color: white !important;
    border-color: #ff6b6b !important;
    transform: translateY(-1px);
    box-shadow: 0 4px 8px rgba(255, 107, 107, 0.3);
  }

  .category-tag.selected:hover {
    background-color: #e55555 !important;
    border-color: #e55555 !important;
  }

  .category-checkbox {
    display: none;
  }

  .condition-section {
    margin-bottom: 40px;
  }

  .condition-label {
    display: block;
    margin-bottom: 15px;
    font-size: 16px;
    color: #333;
    font-weight: 500;
  }

  .condition-select {
    width: 100%;
    padding: 12px 16px;
    border: 2px solid #ddd;
    border-radius: 5px;
    font-size: 16px;
    outline: none;
    background-color: white;
    cursor: pointer;
    transition: border-color 0.2s;
  }

  .condition-select:focus {
    border-color: #ff6b6b;
  }

  .form-section {
    margin-bottom: 40px;
  }

  .create-form-group {
    margin-bottom: 25px;
  }

  .create-form-label {
    display: block;
    margin-bottom: 8px;
    font-size: 16px;
    color: #333;
    font-weight: 500;
  }

  .create-form-input {
    width: 100%;
    padding: 12px 16px;
    border: 2px solid #ddd;
    border-radius: 5px;
    font-size: 16px;
    outline: none;
    transition: border-color 0.2s;
  }

  .create-form-input:focus {
    border-color: #ff6b6b;
  }

  .create-form-textarea {
    width: 100%;
    padding: 12px 16px;
    border: 2px solid #ddd;
    border-radius: 5px;
    font-size: 16px;
    outline: none;
    transition: border-color 0.2s;
    resize: vertical;
    min-height: 120px;
  }

  .create-form-textarea:focus {
    border-color: #ff6b6b;
  }

  .price-input-group {
    position: relative;
  }

  .price-symbol {
    position: absolute;
    left: 16px;
    top: 50%;
    transform: translateY(-50%);
    color: #333;
    font-size: 16px;
  }

  .price-input {
    width: 100%;
    padding: 12px 16px 12px 30px;
    border: 2px solid #ddd;
    border-radius: 5px;
    font-size: 16px;
    outline: none;
    transition: border-color 0.2s;
  }

  .price-input:focus {
    border-color: #ff6b6b;
  }

  .submit-button {
    width: 100%;
    padding: 15px;
    background-color: #ff6b6b;
    color: white;
    border: none;
    border-radius: 5px;
    font-size: 16px;
    font-weight: 600;
    cursor: pointer;
    transition: background-color 0.2s;
    margin-top: 20px;
  }

  .submit-button:hover {
    background-color: #e55555;
  }

  .error-message {
    color: #dc3545;
    font-size: 12px;
    margin-top: 5px;
  }
</style>

<div class="create-page">
  <div class="create-form-container">
    <div class="create-form-title">商品の出品</div>

    <form method="POST" action="{{ route('items.store') }}" enctype="multipart/form-data" novalidate>
      @csrf

      <div class="image-upload-area">
        <label for="image" class="image-select-btn">画像を選択する</label>
        <input id="image" type="file" name="image" class="file-input" accept="image/*">
        @error('image')
          <div class="error-message">{{ $message }}</div>
        @enderror
      </div>

      <div class="section-title">商品の詳細</div>

      <div class="category-section">
        <label class="category-label">カテゴリー</label>
        <div class="category-tags">
          @foreach($categories as $category)
            <input type="checkbox" name="categories[]" value="{{ $category->id }}" id="category{{ $category->id }}" class="category-checkbox" {{ in_array($category->id, old('categories', [])) ? 'checked' : '' }}>
            <label for="category{{ $category->id }}" class="category-tag">
              {{ $category->name }}
            </label>
          @endforeach
        </div>
        @error('categories')
          <div class="error-message">{{ $message }}</div>
        @enderror
      </div>

      <div class="condition-section">
        <label for="condition" class="condition-label">商品の状態</label>
        <select id="condition" name="condition" class="condition-select" required>
          @if(!old('condition'))
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

      <div class="section-title">商品名と説明</div>

      <div class="form-section">
        <div class="create-form-group">
          <label for="name" class="create-form-label">商品名</label>
          <input id="name" type="text" name="name" value="{{ old('name') }}" class="create-form-input" required>
          @error('name')
            <div class="error-message">{{ $message }}</div>
          @enderror
        </div>

        <div class="create-form-group">
          <label for="brand" class="create-form-label">ブランド名</label>
          <input id="brand" type="text" name="brand" value="{{ old('brand') }}" class="create-form-input">
          @error('brand')
            <div class="error-message">{{ $message }}</div>
          @enderror
        </div>

        <div class="create-form-group">
          <label for="description" class="create-form-label">商品の説明</label>
          <textarea id="description" name="description" class="create-form-textarea" required>{{ old('description') }}</textarea>
          @error('description')
            <div class="error-message">{{ $message }}</div>
          @enderror
        </div>

        <div class="create-form-group">
          <label for="price" class="create-form-label">販売価格</label>
          <div class="price-input-group">
            <span class="price-symbol">¥</span>
            <input id="price" type="number" name="price" value="{{ old('price') }}" class="price-input" required min="1">
          </div>
          @error('price')
            <div class="error-message">{{ $message }}</div>
          @enderror
        </div>
      </div>

      <button type="submit" class="submit-button">出品する</button>
    </form>
  </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
  // カテゴリー選択の動的スタイル変更
  const categoryTags = document.querySelectorAll('.category-tag');

  categoryTags.forEach(tag => {
    const checkboxId = tag.getAttribute('for');
    const checkbox = document.getElementById(checkboxId);

    if (checkbox && checkbox.checked) {
      tag.classList.add('selected');
    }

    // クリック時の処理
    tag.addEventListener('click', function(e) {
      e.preventDefault();

      if (checkbox) {
        // チェックボックスの状態を切り替え
        checkbox.checked = !checkbox.checked;

        // 見た目の更新
        if (checkbox.checked) {
          tag.classList.add('selected');
        } else {
          tag.classList.remove('selected');
        }

        // デバッグ用ログ
        console.log(`Category ${checkboxId}: ${checkbox.checked ? 'selected' : 'deselected'}`);
      }
    });
  });

  // 画像選択時に選択した画像名を表示
  const imageInput = document.getElementById('image');
  const imageLabel = document.querySelector('label[for="image"]');

  if (imageInput && imageLabel) {
    imageInput.addEventListener('change', function() {
      if (this.files && this.files[0]) {
        imageLabel.textContent = this.files[0].name;
        imageLabel.style.backgroundColor = '#28a745';
        imageLabel.style.borderColor = '#28a745';
      } else {
        imageLabel.textContent = '画像を選択する';
        imageLabel.style.backgroundColor = '#ff6b6b';
        imageLabel.style.borderColor = '#ff6b6b';
      }
    });
  }

  // フォーム送信前の確認（デバッグ用）
  const form = document.querySelector('form');
  if (form) {
    form.addEventListener('submit', function() {
      const selectedCategories = document.querySelectorAll('input[name="categories[]"]:checked');
      console.log('Selected categories count:', selectedCategories.length);
    });
  }
});
</script>
@endsection