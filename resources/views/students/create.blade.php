<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>学生登録画面</title>
</head>
<body>
    <h1>学生登録画面</h1>
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
            <label for="photo">顔写真:</label>
            <input type="file" id="photo" name="photo" accept="image/*" required>
        </div>
        <button type="submit">登録</button>
        <button type="button" onclick="goBack()">戻る</button>
    </form>

    <script>
    function goBack() {
        window.history.back();
    }
    </script>
</body>
</html>

