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
            <a href="{{ route('index') }}" class="navbar-brand">coachtechフリマ</a>
        </div>
    </header>
    <main>
        @yield('content')
    </main>
</body>
</html>