<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>学生成績追加画面</title>
</head>
<body>
    <h1>学生成績追加画面</h1>
    <form method="POST" action="{{ route('grades.store') }}">
        @csrf
        <input type="hidden" name="student_id" value="{{ $student_id }}">
        <div>
            <label for="subject">科目:</label>
            <input type="text" id="subject" name="subject" required>
        </div>
        <div>
            <label for="score">成績:</label>
            <input type="number" id="score" name="score" required>
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

