@extends('layouts.app')
@section('content')
<style>
  .edit-page {
    background-color: #f5f5f5;
    min-height: 100vh;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 20px;
  }

  .edit-form-container {
    background-color: white;
    max-width: 600px;
    width: 100%;
    padding: 40px;
    border-radius: 8px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
  }

  .edit-form-title {
    font-size: 24px;
    font-weight: bold;
    color: #333;
    margin-bottom: 30px;
    text-align: center;
  }

  .form-group {
    margin-bottom: 25px;
  }

  .message-textarea {
    width: 100%;
    min-height: 150px;
    padding: 15px;
    border: 2px solid #ddd;
    border-radius: 5px;
    font-size: 16px;
    outline: none;
    transition: border-color 0.2s;
    resize: vertical;
    font-family: inherit;
  }

  .message-textarea:focus {
    border-color: #007bff;
  }

  .form-actions {
    display: flex;
    gap: 15px;
    justify-content: center;
    margin-top: 30px;
  }

  .cancel-button {
    background-color: #6c757d;
    color: white;
    padding: 12px 24px;
    border: none;
    border-radius: 5px;
    font-size: 16px;
    cursor: pointer;
    text-decoration: none;
    display: inline-block;
    transition: background-color 0.2s;
  }

  .cancel-button:hover {
    background-color: #545b62;
  }

  .update-button {
    background-color: #007bff;
    color: white;
    padding: 12px 24px;
    border: none;
    border-radius: 5px;
    font-size: 16px;
    cursor: pointer;
    transition: background-color 0.2s;
  }

  .update-button:hover {
    background-color: #0056b3;
  }

  .error-message {
    color: #dc3545;
    font-size: 14px;
    margin-top: 5px;
  }
</style>

<div class="edit-page">
  <div class="edit-form-container">
    <h1 class="edit-form-title">メッセージを編集</h1>

    <form method="POST" action="{{ route('chat.update', $message->id) }}">
      @csrf
      <div class="form-group">
        <textarea
          id="message"
          name="message"
          class="message-textarea"
          required
        >{{ old('message', $message->message) }}</textarea>
        @error('message')
          <div class="error-message">{{ $message }}</div>
        @enderror
      </div>

      <div class="form-actions">
        <a href="{{ route('chat.show', $message->item_id) }}" class="cancel-button">キャンセル</a>
        <button type="submit" class="update-button">更新</button>
      </div>
    </form>
  </div>
</div>
@endsection