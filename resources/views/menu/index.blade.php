<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>メニュー画面</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f9f9f9;
            margin: 0;
            padding: 0;
        }

        h1 {
            text-align: center;
            color: #333;
            margin: 20px 0;
        }

        .container {
            max-width: 600px;
            margin: 30px auto;
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .flash-message {
            text-align: center;
            margin-bottom: 20px;
        }

        .flash-message p {
            margin: 0;
            padding: 10px;
            border-radius: 5px;
        }

        .flash-message p.success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .flash-message p.error {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        nav ul {
            list-style: none;
            padding: 0;
            text-align: center;
        }

        nav ul li {
            margin: 15px 0;
        }

        nav ul li a, nav ul li button {
            display: inline-block;
            width: 100%;
            max-width: 200px;
            padding: 10px;
            text-decoration: none;
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
        }

        nav ul li a:hover, nav ul li button:hover {
            background-color: #0056b3;
        }

        nav ul li button {
            text-align: center;
        }

        nav ul li button:focus {
            outline: none;
        }
    </style>
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
                    <!-- 学年更新フォーム -->
                    <form action="{{ route('students.update-grades') }}" method="POST">
                        @csrf
                        <button type="submit">学年更新</button>
                    </form>
                </li>
            </ul>
        </nav>
    </div>
</body>
</html>
