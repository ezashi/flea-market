@extends('layouts.app')
@section('content')
<div>
  <h3>メッセージを編集</h3>
  <form method="POST" action="{{ route('chat.update', $message->id) }}">
    @csrf
    <div>
      <label for="message">メッセージ内容</label>
      <textarea id="message" name="message" required>{{ old('message', $message->message) }}</textarea>
      @error('message')
        <div style="color: red; margin-top: 5px;">{{ $message }}</div>
      @enderror
    </div>

    <div>
      <a href="{{ route('chat.show', $message->item_id) }}">キャンセル</a>
      <button type="submit">更新</button>
    </div>
  </form>
</div>
@endsection