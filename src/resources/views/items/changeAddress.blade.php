@extends('layouts.app')
@section('content')
<style>
  .change-address-page {
    background-color: #f5f5f5;
    min-height: 100vh;
  }
  .change-address-form-container {
    background-color: white;
    max-width: 600px;
    margin: 80px auto;
    padding: 60px 40px;
    border-radius: 8px;
    box-shadow: none;
  }

  .change-address-title {
    text-align: center;
    font-size: 24px;
    font-weight: bold;
    margin-bottom: 40px;
    color: #333;
  }

  .change-address-form-group {
    margin-bottom: 25px;
  }

  .change-address-form-label {
    display: block;
    margin-bottom: 8px;
    font-size: 16px;
    color: #333;
    font-weight: 500;
  }

  .change-address-form-input {
    width: 100%;
    padding: 12px 16px;
    border: 2px solid #ddd;
    border-radius: 5px;
    font-size: 16px;
    outline: none;
    transition: border-color 0.2s;
  }

  .change-address-form-input:focus {
    border-color: #ff6b6b;
  }

  .change-address-update-button {
    width: 100%;
    padding: 15px;
    background-color: #ff6b6b;
    color: white;
    border: none;
    border-radius: 5px;
    font-size: 16px;
    font-weight: 600;
    cursor: pointer;
    transition: background-color 0.2s;
    margin-top: 20px;
  }

  .change-address-update-button:hover {
    background-color: #e55555;
  }
</style>

<div class="change-address-page">
  <div class="change-address-form-container">
    <div class="change-address-title">住所の変更</div>

    <form method="POST" action="{{ route('items.AddressUpdate', $item) }}">
      @csrf

      <div class="change-address-form-group">
        <label for="postal_code" class="change-address-form-label">郵便番号</label>
        <input id="postal_code" type="text" name="postal_code" value="{{ old('postal_code', $user->postal_code) }}" class="change-address-form-input">
        @error('postal_code')
          <div class="error-message">{{ $message }}</div>
        @enderror
      </div>

      <div class="change-address-form-group">
        <label for="address" class="change-address-form-label">住所</label>
        <input id="address" type="text" name="address" value="{{ old('address', $user->address) }}" class="change-address-form-input">
        @error('address')
          <div class="error-message">{{ $message }}</div>
        @enderror
      </div>

      <div class="change-address-form-group">
        <label for="building" class="change-address-form-label">建物名</label>
        <input id="building" type="text" name="building" value="{{ old('building', $user->building) }}" class="change-address-form-input">
        @error('building')
          <div class="error-message">{{ $message }}</div>
        @enderror
      </div>

      <button type="submit" class="change-address-update-button">更新する</button>
    </form>
  </div>
</div>
@endsection