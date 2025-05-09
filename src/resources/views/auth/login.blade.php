@extends('layouts.app')
@section('content')
<div class="container">
  <div class="row justify-content-center">
    <div class="col-md-8">
      <div class="card">
        <div class="card-header">ログイン</div>
        <div class="card-body">
          <form method="POST" action="{{ route('login') }}">
            @csrf
            <div class="form-group row">
              <label for="email" class="col-md-4">メールアドレス</label>
              <div class="col-md-6">
              <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}">
              @error('email')
                <div class="error-message">{{ $message }}</div>
              @enderror
              </div>
            </div>
            <div class="form-group row">
              <label for="password" class="col-md-4">パスワード</label>
              <div class="col-md-6">
              <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password">
              @error('password')
                <div class="error-message">{{ $message }}</div>
              @enderror
              </div>
            </div>
            <div class="form-group row mb-0">
              <div class="col-md-8 offset-md-4">
                <button type="submit" class="btn btn-primary">
                  ログイン
                </button>
              </div>
            </div>
          </form>
          <div class="mt-3 text-center">
            <a href="{{ route('register') }}">会員登録はこちら</a>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection