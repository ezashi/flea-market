@extends('layouts.app')
@section('content')
<div>
  <h2>住所の変更</h2>
  <div>
    <form method="POST" action="{{ route('items.AddressUpdate', $item) }}">
      @csrf
      <div>
        <label for="postal_code">郵便番号</label>
        <input id="postal_code" type="text" name="postal_code" value="{{ old('postal_code', $user->postal_code) }}">
        @error('postal_code')
          <div class="error-message">{{ $message }}</div>
        @enderror
        <label for="address">住所</label>
        <input id="address" type="text" name="address" value="{{ old('address', $user->address) }}">
        @error('address')
          <div class="error-message">{{ $message }}</div>
        @enderror
        <label for="building">建物名</label>
        <input id="building" type="text" name="building" value="{{ old('building', $user->building) }}">
        @error('building')
          <div class="error-message">{{ $message }}</div>
        @enderror
      </div>
        <button type="submit">更新する</button>
    </form>
  </div>
</div>
@endsection