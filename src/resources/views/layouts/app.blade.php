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

      @if (!Route::is('login') && !Route::is('register') && !Route::is('chat.show'))
        <div class="search-container">
          <form action="{{ request()->is('mylist') ? route('mylist') : route('index') }}" method="GET" id="search-form">
            <input class="search-input" type="text" name="search" placeholder="何をお探しですか？" value="{{ $search ?? '' }}">
          </form>
        </div>

        <div class="header-nav">
          <div class="header-auth-mypage">
            @auth
              <form method="POST" action="{{ route('logout') }}" id="logout-form" style="display: inline;">
                @csrf
                <button type="submit">ログアウト</button>
              </form>
            @else
              <a href="{{ route('login') }}">ログイン</a>
            @endauth
            <a href="{{ route('mypage') }}">マイページ</a>
          </div>
          <a href="{{ route('items.create') }}" class="listing-btn">出品</a>
        </div>
      @endif
    </div>
  </header>

  <main>
    @yield('content')
  </main>
</body>
</html>