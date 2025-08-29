@extends('layouts.app')
@section('content')
<style>
  .mypage-profile-page {
    background-color: #f5f5f5;
    min-height: 100vh;
  }
  .mypage-profile-form-container {
    background-color: white;
    max-width: 600px;
    margin: 40px auto;
    padding: 60px 40px;
    border-radius: 8px;
    box-shadow: none;
  }

  .mypage-profile-form-title {
    text-align: center;
    font-size: 24px;
    font-weight: bold;
    margin-bottom: 40px;
    color: #333;
  }

  .mypage-profile-image-section {
    display: flex;
    align-items: center;
    gap: 20px;
    margin-bottom: 40px;
    justify-content: center;
    flex-direction: column;
  }

  .mypage-profile-avatar {
    width: 120px;
    height: 120px;
    border-radius: 50%;
    background-color: #ccc;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #666;
    font-size: 48px;
    font-weight: bold;
    position: relative;
    overflow: hidden;
  }

  .mypage-profile-avatar img {
    width: 100%;
    height: 100%;
    object-fit: cover;
  }

  .mypage-image-select-button {
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

  .mypage-image-select-button:hover {
    background-color: white;
    color: #ff6b6b;
  }

  .mypage-profile-file-input {
    display: none;
  }

  .mypage-profile-form-group {
    margin-bottom: 25px;
  }

  .mypage-profile-form-label {
    display: block;
    margin-bottom: 8px;
    font-size: 16px;
    color: #333;
    font-weight: 500;
  }

  .mypage-profile-form-input {
    width: 100%;
    padding: 12px 16px;
    border: 2px solid #ddd;
    border-radius: 5px;
    font-size: 16px;
    outline: none;
    transition: border-color 0.2s;
  }

  .mypage-profile-form-input:focus {
    border-color: #ff6b6b;
  }

  .mypage-profile-update-button {
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
  }

  .mypage-profile-update-button:hover {
    background-color: #e55555;
  }
</style>

<div class="mypage-profile-page">
  <div class="mypage-profile-form-container">
    <div class="mypage-profile-form-title">プロフィール設定</div>

    <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data">
      @csrf
      <div class="mypage-profile-image-section">
        <div class="mypage-profile-avatar">
          @if($user->profile_image)
            <img src="{{ asset('$user->profile_image) }}" alt="{{ $user->name }}">
          @else
            {{ strtoupper(substr($user->name, 0, 1)) }}
          @endif
        </div>
        <label for="profile_image" class="mypage-image-select-button">
          画像を選択する
        </label>
        <input id="profile_image" type="file" name="profile_image" class="mypage-profile-file-input" accept="image/*">
        @error('profile_image')
          <div class="error-message">{{ $message }}</div>
        @enderror
      </div>

      <div class="mypage-profile-form-group">
        <label for="name" class="mypage-profile-form-label">ユーザー名</label>
        <input id="name" type="text" name="name" value="{{ old('name', $user->name) }}" class="mypage-profile-form-input" required>
        @error('name')
          <div class="error-message">{{ $message }}</div>
        @enderror
      </div>

      <div class="mypage-profile-form-group">
        <label for="postal_code" class="mypage-profile-form-label">郵便番号</label>
        <input id="postal_code" type="text" name="postal_code" value="{{ old('postal_code', $user->postal_code) }}" class="mypage-profile-form-input">
        @error('postal_code')
          <div class="error-message">{{ $message }}</div>
        @enderror
      </div>

      <div class="mypage-profile-form-group">
        <label for="address" class="mypage-profile-form-label">住所</label>
        <input id="address" type="text" name="address" value="{{ old('address', $user->address) }}" class="mypage-profile-form-input">
        @error('address')
          <div class="error-message">{{ $message }}</div>
        @enderror
      </div>

      <div class="mypage-profile-form-group">
        <label for="building" class="mypage-profile-form-label">建物名</label>
        <input id="building" type="text" name="building" value="{{ old('building', $user->building) }}" class="mypage-profile-form-input">
        @error('building')
          <div class="error-message">{{ $message }}</div>
        @enderror
      </div>

      <button type="submit" class="mypage-profile-update-button">更新する</button>
    </form>
  </div>
</div>
@endsection