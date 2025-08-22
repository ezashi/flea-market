<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>coachtechフリマ</title>
  <link rel="stylesheet" href="{{ asset('css/sanitize.css') }}" />

</head>
<body>
  <header>
    <div>
      <a href="{{ route('index') }}">
        <img src="{{ asset('image/Free Market App Logo.svg') }}" alt="coachtechロゴ"/>
      </a>
      @auth
        @if (!Route::is('login') && !Route::is('register'))
          <!-- 検索フォーム -->
          <form action="{{ request()->is('mylist') ? route('mylist') : route('index') }}" method="GET">
            <input type="text" name="search" placeholder=" 何をお探しですか？" value="{{ $search ?? '' }}">
            <button type="submit">検索</button>
          </form>

          <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit">ログアウト</button>
          </form>
          <form method="GET" action="{{ route('mypage') }}">
            @csrf
            <button type="submit">マイページ</button>
          </form>
          <form action="{{ route('items.create') }}" method="GET">
            <button type="submit">出品</button>
          </form>
        @endif
      @else
        @if (!Route::is('login') && !Route::is('register'))
          <!-- 検索フォーム -->
          <form action="{{ route('index') }}" method="GET">
            <input type="text" name="search" placeholder=" 何をお探しですか？" value="{{ request('search') }}">
            <button type="submit">検索</button>
          </form>

          <form method="GET" action="/login">
            @csrf
            <button type="submit">ログイン</button>
          </form>
          <form method="GET" action="{{ route('mypage') }}">
            @csrf
            <button type="submit">マイページ</button>
          </form>
          <form action="{{ route('items.create') }}" method="GET">
            <button type="submit">出品</button>
          </form>
        @endif
      @endauth
    </div>
  </header>
  <main>
    @yield('content')
  </main>
</body>
</html>