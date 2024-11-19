<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>成績編集画面</title>
</head>
<body>
    <h1>成績編集画面</h1>
    <form method="POST" action="{{ route('grades.update', $grade->id) }}">
        @csrf
        @method('PUT')
        <div>
            <label for="subject">科目:</label>
            <input type="text" id="subject" name="subject" value="{{ $grade->subject }}" required>
        </div>
        <div>
            <label for="score">成績:</label>
            <input type="number" id="score" name="score" value="{{ $grade->score }}" required>
        </div>
        <button type="submit">更新</button>
        <button type="button" onclick="goBack()">戻る</button>
    </form>
    <script>
        function goBack() {
            window.history.back();
        }
    </script>
</body>
</html>
