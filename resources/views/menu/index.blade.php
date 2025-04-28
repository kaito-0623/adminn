<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>メニュー画面</title>
    <link rel="stylesheet" href="{{ asset('css/menu_index.css') }}">
</head>
<body>
    <h1>メニュー画面</h1>

    <div class="container">
        <!-- フラッシュメッセージ表示 -->
        <div class="flash-message">
            @if (session('success'))
                <p class="success">{{ session('success') }}</p>
            @elseif (session('error'))
                <p class="error">{{ session('error') }}</p>
            @endif
        </div>

        <nav>
            <ul>
                <li>
                    <a href="{{ route('students.create') }}">学生登録</a>
                </li>
                <li>
                    <a href="{{ route('students.index') }}">学生表示</a>
                </li>
                <li>
                    <!-- 学年更新ボタンを一括更新の仕組みに変更 -->
                    <form action="{{ route('students.update-all-grades') }}" method="POST">
                        @csrf
                        <button type="submit">学年更新</button>
                    </form>
                </li>
            </ul>
        </nav>
    </div>
</body>
</html>