<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>学生登録画面</title>
</head>
<body>
    <h1>学生登録画面</h1>
    
    <!-- エラーメッセージの表示 -->
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('students.store') }}" enctype="multipart/form-data">
        @csrf
        <div>
            <label for="name">名前:</label>
            <input type="text" id="name" name="name" required>
        </div>
        <div>
            <label for="address">住所:</label>
            <input type="text" id="address" name="address" required>
        </div>
        <div>
            <label for="email">メールアドレス:</label>
            <input type="email" id="email" name="email" required>
        </div>
        <div>
            <label for="grade">学年:</label>
            <select id="grade" name="grade" required>
                @foreach(['1年生', '2年生', '3年生', '4年生'] as $grade)
                    <option value="{{ $grade }}">{{ $grade }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label for="photo">顔写真:</label>
            <input type="file" id="photo" name="photo" accept="image/*" required>
        </div>
        <button type="submit">登録</button>
    </form>

    <!-- メニュー画面に戻るボタン -->
    <button onclick="window.location.href='{{ url('/menu') }}'">メニューに戻る</button>
</body>
</html>
