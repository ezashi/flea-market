<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>coachtechフリマ</title>
    <link rel="stylesheet" href="{{ asset('css/sanitize.css') }}" />
    <style>
        /* //ベースのスタイル設定
        body {
            font-family: 'Arial', sans-serif;
            line-height: 1.6;
            color: #333;
            background-color: #f8f9fa;
        }

        //ナビゲーションバー
        .navbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1rem 2rem;
            background-color: #fff;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
        }

        .navbar-brand {
            font-size: 1.5rem;
            font-weight: bold;
            color: #333;
            text-decoration: none;
        }

        .navbar-nav {
            display: flex;
            list-style: none;
            margin: 0;
            padding: 0;
        }

        .nav-item {
            margin-left: 1.5rem;
        }

        .nav-link {
            color: #6c757d;
            text-decoration: none;
        }

        .nav-link:hover {
            color: #007bff;
        }

        //コンテナ
        .container {
            max-width: 1200px;
            margin: 2rem auto;
            padding: 0 15px;
        }

        //カード
        .card {
            background-color: #fff;
            border-radius: 0.25rem;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
            margin-bottom: 1.5rem;
        }

        .card-header {
            padding: 1rem;
            border-bottom: 1px solid #e9ecef;
            background-color: #f8f9fa;
            font-weight: bold;
        }

        .card-body {
            padding: 1.5rem;
        }

        //フォーム
        .form-group {
            margin-bottom: 1rem;
        }

        .form-control-name,
        .form-control-email,
        .form-control-password,
        .form-control-profile_image,
        .form-control-postal_code,
        .form-control-address {
            display: block;
            width: 100%;
            padding: 0.375rem 0.75rem;
            font-size: 1rem;
            line-height: 1.5;
            color: #495057;
            background-color: #fff;
            background-clip: padding-box;
            border: 1px solid #ced4da;
            border-radius: 0.25rem;
            transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
        }

        .form-control:focus {
            color: #495057;
            background-color: #fff;
            border-color: #80bdff;
            outline: 0;
            box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
        }

        .btn {
            display: inline-block;
            font-weight: 400;
            text-align: center;
            white-space: nowrap;
            vertical-align: middle;
            user-select: none;
            border: 1px solid transparent;
            padding: 0.375rem 0.75rem;
            font-size: 1rem;
            line-height: 1.5;
            border-radius: 0.25rem;
            transition: color 0.15s ease-in-out, background-color 0.15s ease-in-out, border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
        }

        .btn-primary {
            color: #fff;
            background-color: #007bff;
            border-color: #007bff;
        }

        .btn-primary:hover {
            color: #fff;
            background-color: #0069d9;
            border-color: #0062cc;
        }

        .btn-success {
            color: #fff;
            background-color: #28a745;
            border-color: #28a745;
        }

        .btn-success:hover {
            color: #fff;
            background-color: #218838;
            border-color: #1e7e34;
        }

        //アラート
        .alert {
            position: relative;
            padding: 0.75rem 1.25rem;
            margin-bottom: 1rem;
            border: 1px solid transparent;
            border-radius: 0.25rem;
        }

        .alert-info {
            color: #0c5460;
            background-color: #d1ecf1;
            border-color: #bee5eb;
        }

        //レスポンシブ用のクラス
        .row {
            display: flex;
            flex-wrap: wrap;
            margin-right: -15px;
            margin-left: -15px;
        }

        .col-md-3, .col-md-4, .col-md-6, .col-md-8, .col-md-9, .col-md-12 {
            position: relative;
            width: 100%;
            padding-right: 15px;
            padding-left: 15px;
        }

        @media (min-width: 768px) {
            .col-md-3 {
                flex: 0 0 25%;
                max-width: 25%;
            }
            .col-md-4 {
                flex: 0 0 33.333333%;
                max-width: 33.333333%;
            }
            .col-md-6 {
                flex: 0 0 50%;
                max-width: 50%;
            }
            .col-md-8 {
                flex: 0 0 66.666667%;
                max-width: 66.666667%;
            }
            .col-md-9 {
                flex: 0 0 75%;
                max-width: 75%;
            }
            .col-md-12 {
                flex: 0 0 100%;
                max-width: 100%;
            }
            .offset-md-4 {
                margin-left: 33.333333%;
            }
        }

        //商品カード
        .card-img-top {
            width: 100%;
            height: 200px;
            object-fit: cover;
        }

        .position-relative {
            position: relative;
        }

        .position-absolute {
            position: absolute;
        }

        .top-0 {
            top: 0;
        }

        .end-0 {
            right: 0;
        }

        .bg-danger {
            background-color: #dc3545;
        }

        .text-white {
            color: #fff;
        }

        .px-2 {
            padding-left: 0.5rem;
            padding-right: 0.5rem;
        }

        .py-1 {
            padding-top: 0.25rem;
            padding-bottom: 0.25rem;
        }

        //ページネーション
        .pagination {
            display: flex;
            justify-content: center;
            list-style: none;
            margin: 1rem 0;
            padding: 0;
        }

        .page-item {
            display: inline-block;
            margin: 0 0.25rem;
        }

        .page-link {
            display: block;
            padding: 0.5rem 0.75rem;
            line-height: 1.25;
            color: #007bff;
            background-color: #fff;
            border: 1px solid #dee2e6;
            border-radius: 0.25rem;
            text-decoration: none;
        }

        .page-item.active .page-link {
            z-index: 1;
            color: #fff;
            background-color: #007bff;
            border-color: #007bff;
        }

        .page-item.disabled .page-link {
            color: #6c757d;
            pointer-events: none;
            cursor: not-allowed;
            background-color: #fff;
            border-color: #dee2e6;
        } */
    </style>
</head>
<body>
    <header>
        <div class="navbar">
            <a href="{{ route('index') }}" class="navbar-brand">
                <img src="{{ asset('image/Free Market App Logo.svg') }}" alt="coachtechフリマ->index"/>
            </a>
            @auth
                @if (!Route::is('login') && !Route::is('register'))
                    <!-- 検索フォーム -->
                    <form action="{{ route('index') }}" method="GET" class="mb-4">
                        <input type="text" class="form-control" name="search" placeholder=" 何をお探しですか？" value="{{ request('search') }}">
                        <button class="btn btn-outline-secondary" type="submit" style="background: none; border: none; cursor: pointer;">検索</button>
                    </form>

                    <form method="POST" action="{{ route('logout') }}" style="display: inline;">
                        @csrf
                        <button type="submit" class="nav-link" style="background: none; border: none; cursor: pointer;">
                            ログアウト
                        </button>
                    </form>
                    <form method="GET" action="{{ route('mypage') }}" style="display: inline;">
                        @csrf
                        <button type="submit" class="nav-mb" style="background: none; border: none; cursor: pointer;">
                            マイページ
                        </button>
                    </form>
                    <form action="{{ route('items.create') }}" method="GET" class="mb-4">
                        <button type="submit" class="btn btn-primary">出品</button>
                    </form>
                @endif
            @else
                @if (!Route::is('login') && !Route::is('register'))
                    <!-- 検索フォーム -->
                    <form action="{{ route('index') }}" method="GET" class="mb-4">
                        <input type="text" class="form-control" name="search" placeholder=" 何をお探しですか？" value="{{ request('search') }}">
                        <button class="btn btn-outline-secondary" type="submit" style="background: none; border: none; cursor: pointer;">検索</button>
                    </form>

                    <form method="GET" action="{{ route('login') }}" style="display: inline;">
                        @csrf
                        <button type="submit" class="nav-link" style="background: none; border: none; cursor: pointer;">
                            ログイン
                        </button>
                    </form>
                    <form method="GET" action="{{ route('mypage') }}" style="display: inline;">
                        @csrf
                        <button type="submit" class="nav-mb" style="background: none; border: none; cursor: pointer;">
                            マイページ
                        </button>
                    </form>
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