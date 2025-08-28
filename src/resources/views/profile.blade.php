@extends('layouts.app')
@section('content')
<div>
  <div>プロフィール設定</div>
  <div>
    <form method="POST" action="{{ route('update') }}" enctype="multipart/form-data">
      @csrf
      <div>
        <label for="profile_image">
          <span>{{ strtoupper(substr($user->name, 0, 1)) }}</span>
          画像を選択する
        </label>
        <input id="profile_image" type="file" name="profile_image" style="width: 100px; height: 100px; object-fit: cover;" alt="{{ $user->name }}">
        @error('profile_image')
          <div class="error-message">{{ $message }}</div>
        @enderror
      </div>
      <div>
        <label for="name">ユーザー名</label>
        <input id="name" type="text" name="name">
        @error('name')
          <div class="error-message">{{ $message }}</div>
        @enderror
        <label for="postal_code">郵便番号</label>
        <input id="postal_code" type="text" name="postal_code">
        @error('postal_code')
          <div class="error-message">{{ $message }}</div>
        @enderror
        <label for="address">住所</label>
        <input id="address" type="text" name="address">
        @error('address')
          <div class="error-message">{{ $message }}</div>
        @enderror
        <label for="building">建物名</label>
        <input id="building" type="text" name="building">
        @error('building')
          <div class="error-message">{{ $message }}</div>
        @enderror
      </div>
      <button type="submit">更新する</button>
    </form>
  </div>
</div>
@endsection