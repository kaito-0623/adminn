<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>メニュー画面</title>
    <link rel="stylesheet" href="{{ asset('css/menu_index.css') }}">
</head>
<body>

    <!--ヘッダーを追加し、左上にボタンを配置 -->
    <header class="menu-header">
        <div class="left-section">
            <a href="{{ route('users.index') }}" class="user-button">ユーザー管理</a>
        </div>
    </header>
    <h1>メニュー画面</h1>

    <div class="container">
        <nav>
            <ul>
                <li>
                    <a href="{{ route('students.create') }}" class="btn btn-success">学生登録</a>
                </li>
                <li>
                    <a href="{{ route('students.index') }}" class="btn btn-info">学生一覧</a>
                </li>
                <li>
                    <form action="{{ route('students.update-all-grades') }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-warning">学年更新</button>
                    </form>
                </li>
            </ul>
        </nav>
    </div>

</body>
</html>