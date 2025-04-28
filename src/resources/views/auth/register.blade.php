@extends('layouts.app')
@section('content')
<div class="container">
  <div class="row justify-content-center">
    <div class="col-md-8">
      <div class="card">
        <div class="card-header">会員登録</div>
        <div class="card-body">
          <form method="POST" action="{{ route('register') }}">
            @csrf
            <div class="form-group row">
              <label for="name" class="col-md-4">ユーザー名</label>
              <div class="col-md-6">
                <input id="name" type="text" class="form-control-name" name="name" value="{{ old('name') }}">
                @error('name')
                <span class="invalid-feedback" role="alert">
                  <strong>{{ $message }}</strong>
                </span>
                @enderror
              </div>
            </div>
            <div class="form-group row">
              <label for="email" class="col-md-4">メールアドレス</label>
              <div class="col-md-6">
                <input id="email" type="email" class="form-control-email" name="email" value="{{ old('email') }}">
                @error('email')
                <span class="invalid-feedback" role="alert">
                  <strong>{{ $message }}</strong>
                </span>
                @enderror
              </div>
            </div>
            <div class="form-group row">
              <label for="password" class="col-md-4">パスワード</label>
              <div class="col-md-6">
                <input id="password" type="password" class="form-control-password" name="password">
                @error('password')
                <span class="invalid-feedback" role="alert">
                  <strong>{{ $message }}</strong>
                </span>
                @enderror
              </div>
            </div>
            <div class="form-group row">
              <label for="password-confirm" class="col-md-4">確認用パスワード</label>
              <div class="col-md-6">
                <input id="password-confirm" type="password" class="form-control" name="password_confirmation">
              </div>
            </div>
            <div class="form-group row mb-0">
              <div class="col-md-6 offset-md-4">
                <button type="submit" class="btn btn-primary">
                  登録
                </button>
              </div>
            </div>
          </form>
          <div class="mt-3 text-center">
            <a href="{{ route('login') }}">ログインはこちら</a>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection