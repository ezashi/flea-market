<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>coachtechフリマ</title>
  <link rel="stylesheet" href="{{ asset('css/sanitize.css') }}" />
  <link rel="stylesheet" href="{{ asset('css/style.css') }}" />

</head>
<body>
  <header>
    <div class="header">
      <a href="{{ route('index') }}">
        <img class="logo" src="{{ asset('image/Free Market App Logo.svg') }}" alt="coachtech logo"/>
      </a>
    </div>
    <div>
      @auth
        @if (!Route::is('login') && !Route::is('register'))
          <!-- 検索フォーム -->
          <form action="{{ request()->is('mylist') ? route('mylist') : route('index') }}" method="GET" id="search-form">
            <input class="search-input" type="text" name="search" placeholder=" 何をお探しですか？" value="{{ $search ?? '' }}">
          </form>

          <form method="POST" action="{{ route('logout') }}" id="logout-form">
            @csrf
            <button type="submit">ログアウト</button>
          </form>
          <a href="{{ route('mypage') }}">マイページ</a>
          <a href="{{ route('items.create') }}">出品</a>
        @endif
      @else
        @if (!Route::is('login') && !Route::is('register'))
          <!-- 検索フォーム -->
          <form action="{{ route('index') }}" method="GET" id="search-form">
            <input class="search-input" type="text" name="search" placeholder=" 何をお探しですか？" value="{{ request('search') }}">
          </form>

          <form method="GET" action="/login" id="login-form">
            @csrf
            <button type="submit">ログイン</button>
          </form>
          <a href="{{ route('mypage') }}">マイページ</a>
          <a href="{{ route('items.create') }}">出品</a>
        @endif
      @endauth
    </div>
  </header>
  <main>
    @yield('content')
  </main>
</body>
</html>