@extends('layouts.app')
@section('content')
<style>
  .register-page {
    background-color: #fff;
    min-height: 100vh;
    padding: 0;
    display: flex;
    align-items: flex-start;
    justify-content: center;
    padding-top: 20px;
  }

  .register-form-container {
    background-color: white;
    max-width: 400px;
    width: 100%;
    margin: 0px;
    padding: 60px 0px;
    border-radius: 8px;
    box-shadow: none;
  }

  .register-title {
    text-align: center;
    font-size: 24px;
    font-weight: bold;
    margin-bottom: 40px;
    color: #333;
  }

  .form-group * {
    font-weight: bold;
  }
</style>

<div class="register-page">
  <div class="register-form-container">
    <div class="register-title">会員登録</div>
    <form method="POST" action="{{ route('register') }}">
      @csrf
      <div class="form-group">
        <label for="name" class="form-label">ユーザー名</label>
        <input id="name" type="text" name="name" value="{{ old('name') }}" class="form-input" required>
        @error('name')
        <div class="error-message">{{ $message }}</div>
        @enderror
      </div>

      <div class="form-group">
        <label for="email" class="form-label">メールアドレス</label>
        <input id="email" type="email" name="email" value="{{ old('email') }}" class="form-input" required>
        @error('email')
          <div class="error-message">{{ $message }}</div>
        @enderror
      </div>

      <div class="form-group">
        <label for="password" class="form-label">パスワード</label>
        <input id="password" type="password" name="password" class="form-input" required>
        @error('password')
          <div class="error-message">{{ $message }}</div>
        @enderror
      </div>

      <div class="form-group">
        <label for="password-confirm" class="form-label">確認用パスワード</label>
        <input id="password-confirm" type="password" name="password_confirmation" class="form-input" required>
      </div>

      <button type="submit" class="primary-btn">登録する</button>
    </form>

    <div class="form-footer">
      <a href="{{ route('login') }}">ログインはこちら</a>
    </div>
  </div>
</div>
@endsection