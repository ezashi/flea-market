@extends('layouts.app')
@section('content')
<div>
  <div>ログイン</div>
  <div>
    <form method="POST" action="{{ route('login') }}">
      @csrf
      <label for="email">メールアドレス</label>
      <input id="email" type="email" name="email" value="{{ old('email') }}">
      @error('email')
        <div class="error-message">{{ $message }}</div>
      @enderror
      <label for="password">パスワード</label>
      <input id="password" type="password" name="password">
      @error('password')
        <div class="error-message">{{ $message }}</div>
      @enderror
      <button type="submit">ログインする</button>
    </form>
    <div>
      <a href="{{ route('register') }}">会員登録はこちら</a>
    </div>
  </div>
</div>
@endsection