@extends('layouts.app')
@section('content')
<div>
  <div>会員登録</div>
  <div>
    <form method="POST" action="{{ route('register') }}">
      @csrf
      <div>
        <label for="name">ユーザー名</label>
        <input id="name" type="text" name="name" value="{{ old('name') }}">
        @error('name')
          <div class="error-message">{{ $message }}</div>
        @enderror
      </div>
      <div>
        <label for="email">メールアドレス</label>
        <input id="email" type="email" name="email" value="{{ old('email') }}">
        @error('email')
          <div class="error-message">{{ $message }}</div>
        @enderror
      </div>
      <div>
        <label for="password">パスワード</label>
        <input id="password" type="password" name="password">
        @error('password')
          <div class="error-message">{{ $message }}</div>
        @enderror
      </div>
      <div>
        <label for="password-confirm">確認用パスワード</label>
        <input id="password-confirm" type="password" name="password_confirmation">
      </div>
      <div>
        <button type="submit">登録する</button>
      </div>
    </form>
    <div>
      <a href="{{ route('login') }}">ログインはこちら</a>
    </div>
  </div>
</div>
@endsection