@extends('layouts.app')
@section('content')
<div>
  <div>ログイン</div>
  <div>
    <form method="POST" action="{{ route('login') }}">
      @csrf
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
        <button type="submit">ログイン</button>
      </div>
    </form>
    <div>
      <a href="{{ route('register') }}">会員登録はこちら</a>
    </div>
  </div>
</div>
@endsection