@extends('layouts.app')
@section('content')
<style>
  .login-page {
    background-color: #fff;
    min-height: 100vh;
    padding: 0;
    display: flex;
    align-items: flex-start;
    justify-content: center;
    padding-top: 20px;
  }

  .login-form-container {
    background-color: white;
    max-width: 400px;
    width: 100%;
    margin: 0 0px;
    padding: 60px 0px;
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

  .form-group * {
    font-weight: bold;
  }
</style>

<div class="login-page">
  <div class="login-form-container">
    <h1 class="login-title">ログイン</h1>
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