<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>coachtechフリマ</title>
    <link rel="stylesheet" href="{{ asset('css/sanitize.css') }}" />
    <style>
        
    </style>
</head>
<body>
    <header>
        <div class="navbar">
            <a href="{{ route('index') }}" class="navbar-brand">coachtechフリマ</a>
            <ul class="navbar-nav">
                @guest
                    <li class="nav-item">
                        <a href="{{ route('login') }}" class="nav-link">ログイン</a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('register') }}" class="nav-link">会員登録</a>
                    </li>
                @else
                    <li class="nav-item">
                        <a href="{{ route('profile.show') }}" class="nav-link">プロフィール設定</a>
                    </li>
                    <li class="nav-item">
                        <form method="POST" action="{{ route('logout') }}" style="display: inline;">
                            @csrf
                            <button type="submit" class="nav-link" style="background: none; border: none; cursor: pointer;">
                                ログアウト
                            </button>
                        </form>
                    </li>
                @endguest
            </ul>
        </div>
    </header>
    <main>
        @yield('content')
    </main>
</body>
</html>