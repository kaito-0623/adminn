<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>学生詳細表示画面</title>
</head>
<body>
    <h1>学生詳細表示画面</h1>
    <div>
        <label>学年:</label>
        <span>{{ $student->grade->name }}</span>
    </div>
    <div>
        <label>名前:</label>
        <span>{{ $student->name }}</span>
    </div>
    <div>
        <label>住所:</label>
        <span>{{ $student->address }}</span>
    </div>
    @if($student->img_path)
    <div>
        <label>顔写真:</label>
        <img src="{{ asset('storage/' . $student->img_path) }}" alt="学生の顔写真">
    </div>
    @endif
    <button type="button" onclick="goBack()">戻る</button>

    <script>
    function goBack() {
        window.history.back();
    }
    </script>
</body>
</html>

