<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>coachtechフリマ</title>
    <link rel="stylesheet" href="{{ asset('css/sanitize.css') }}" />
    <style>
        //
    </style>
</head>
<body>
    <header>
        <div class="navbar">
            <a href="{{ route('index') }}" class="navbar-brand">
                <img src="{{ asset('images/Free Market App Logo.svg') }}" alt="coachtechフリマ->index"/>
            </a>
            @auth
                @if (!Route::is('login') && !Route::is('register'))
                    <!-- 検索フォーム -->
                    <form action="{{ route('index') }}" method="GET" class="mb-4">
                        <input type="text" class="form-control" name="search" placeholder=" 何をお探しですか？" value="{{ request('search') }}">
                        <button class="btn btn-outline-secondary" type="submit">検索</button>
                    </form>

                    <form method="POST" action="{{ route('logout') }}" style="display: inline;">
                        @csrf
                        <button type="submit" class="nav-link" style="background: none; border: none; cursor: pointer;">
                            ログアウト
                        </button>
                    </form>
                    <!-- <a href="{{ route('logout') }}" class="mb-nav">ログアウト</a> -->
                    <a href="{{ route('mypage') }}" class="mb-link">マイページ</a>
                    <form action="{{ route('items.create') }}" method="GET" class="mb-4">
                        <button type="submit" class="btn btn-primary">出品</button>
                    </form>
                @endif
            @else
                @if (!Route::is('login') && !Route::is('register'))
                    <!-- 検索フォーム -->
                    <form action="{{ route('index') }}" method="GET" class="mb-4">
                        <input type="text" class="form-control" name="search" placeholder=" 何をお探しですか？" value="{{ request('search') }}">
                        <button class="btn btn-outline-secondary" type="submit">検索</button>
                    </form>

                    <a href="{{ route('login') }}" class="nav-link">ログイン</a>
                    <a href="{{ route('mypage') }}" class="mb-link">マイページ</a>
                    <form action="{{ route('items.create') }}" method="GET" class="mb-4">
                        <button type="submit" class="btn btn-primary">出品</button>
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