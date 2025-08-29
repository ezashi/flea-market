@extends('layouts.app')
@section('content')
<style>
  .login-page {
    background-color: #f5f5f5;
    min-height: 100vh;
    padding-top: 0;
  }

  .login-form-container {
    background-color: white;
    max-width: 400px;
    margin: 80px auto;
    padding: 60px 40px;
    border-radius: 8px;
    box-shadow: none;
  }

  .login-title {
    text-align: center;
    font-size: 24px;
    font-weight: bold;
    margin-bottom: 40px;
    color: #333;
  }
</style>

<div class="login-page">
  <div class="login-form-container">
    <div class="login-title">ログイン</div>
    <form method="POST" action="{{ route('login') }}">
      @csrf
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

      <button type="submit" class="primary-btn">ログインする</button>
    </form>

    <div class="form-footer">
      <a href="{{ route('register') }}">会員登録はこちら</a>
    </div>
  </div>
</div>
@endsection