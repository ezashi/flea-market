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
                <form method="POST" action="{{ route('logout') }}" style="display: inline;">
                @csrf
                <button type="submit" class="nav-link" style="background: none; border: none; cursor: pointer;">
                    ログアウト
                </button>
                </form>
            @else
                <a href="{{ route('login') }}" class="nav-link">ログイン</a>
            @endauth
        </div>
    </header>
    <main>
        @yield('content')
    </main>
</body>
</html>