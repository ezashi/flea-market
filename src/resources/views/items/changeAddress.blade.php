@extends('layouts.app')
@section('content')
<style>
  .change-address-page {
    background-color: #fff;
    min-height: 100vh;
    padding: 0;
  }

  .change-address-form-container {
    background-color: white;
    max-width: 600px;
    margin: 0 auto;
    padding: 60px 40px;
    min-height: 100vh;
    box-shadow: none;
    display: flex;
    flex-direction: column;
    justify-content: center;
  }

  .change-address-title {
    text-align: center;
    font-size: 24px;
    font-weight: bold;
    margin-bottom: 60px;
    color: #333;
  }

  .change-address-form-group {
    margin-bottom: 40px;
  }

  .change-address-form-label {
    display: block;
    margin-bottom: 12px;
    font-size: 16px;
    color: #333;
    font-weight: bold;
  }

  .change-address-form-input {
    width: 100%;
    padding: 15px 20px;
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
    padding: 18px;
    background-color: #ff6b6b;
    color: white;
    border: none;
    border-radius: 5px;
    font-size: 16px;
    font-weight: 600;
    cursor: pointer;
    transition: background-color 0.2s;
    margin-top: 40px;
  }

  .change-address-update-button:hover {
    background-color: #e55555;
  }

  .error-message {
    color: #dc3545;
    font-size: 12px;
    margin-top: 5px;
  }
</style>

<div class="change-address-page">
  <div class="change-address-form-container">
    <h1 class="change-address-title">住所の変更</h1>

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