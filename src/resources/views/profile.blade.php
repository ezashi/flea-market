@extends('layouts.app')
@section('content')
<div>
  <div>プロフィール設定</div>
  <div>
    <form method="POST" action="{{ route('update') }}" enctype="multipart/form-data">
      @csrf
      <div>
        @if($user->profile_image)
          <label for="profile_image" style="cursor: pointer;">
            <img src="{{ asset(Auth::user()->profile_image) }}" style="max-width: 150px;" alt="{{ $user->name }}">
            画像を選択する
          </label>
        @else
          <label for="profile_image" style="cursor: pointer;">
            <span>{{ strtoupper(substr($user->name, 0, 1)) }}</span>
            画像を選択する
          </label>
        @endif
        <input id="profile_image" type="file" name="profile_image" style="max-width: 150px;" alt="{{ $user->name }}">
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