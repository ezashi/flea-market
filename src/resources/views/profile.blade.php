@extends('layouts.app')
@section('content')
<div class="container">
  <div class="row justify-content-center">
    <div class="col-md-8">
      <div class="card">
        <div class="card-header">プロフィール設定</div>
          <div class="card-body">
            <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data">
              @csrf
              <div class="form-group row mb-3">
                <label for="profile_image" class="col-md-4">プロフィール画像</label>
                <div class="col-md-6">
                  <input id="profile_image" type="file" class="form-control-profile_image" name="profile_image">
                  @error('profile_image')
                    <div class="error-message">{{ $message }}</div>
                  @enderror
                </div>
              </div>
              <div class="form-group row mb-3">
                <label for="name" class="col-md-4">ユーザー名</label>
                <div class="col-md-6">
                  <input id="name" type="text" class="form-control-name" name="name" value="{{ old('name', $user->name) }}">
                  @error('name')
                    <div class="error-message">{{ $message }}</div>
                  @enderror
                </div>
              </div>
              <div class="form-group row mb-3">
                <label for="postal_code" class="col-md-4">郵便番号</label>
                <div class="col-md-6">
                  <input id="postal_code" type="text" class="form-control-postal_code" name="postal_code" value="{{ old('postal_code', $user->postal_code) }}">
                  @error('postal_code')
                    <div class="error-message">{{ $message }}</div>
                  @enderror
                </div>
              </div>
              <div class="form-group row mb-3">
                <label for="address" class="col-md-4">住所</label>
                <div class="col-md-6">
                  <input id="address" type="text" class="form-control-address" name="address" value="{{ old('address', $user->address) }}">
                  @error('address')
                    <div class="error-message">{{ $message }}</div>
                  @enderror
                </div>
              </div>
              <div class="form-group row mb-3">
                <label for="building" class="col-md-4">建物名</label>
                <div class="col-md-6">
                  <input id="building" type="text" class="form-control-building" name="building" value="{{ old('building', $user->building) }}">
                  @error('building')
                    <div class="error-message">{{ $message }}</div>
                  @enderror
                </div>
              </div>
              <div class="form-group row mb-0">
                <div class="col-md-6 offset-md-4">
                  <button type="submit" class="btn btn-primary">
                    更新する
                  </button>
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection
