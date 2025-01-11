<!DOCTYPE html>
<html lang="ja">
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
            <label for="grade">学年:</label>
            <select id="grade" name="grade" required>
                <option value="1年生">1年生</option>
                <option value="2年生">2年生</option>
                <option value="3年生">3年生</option>
                <option value="4年生">4年生</option>
            </select>
        </div>

        <div>
            <label for="term">学期:</label>
            <select id="term" name="term" required>
                <option value="1学期">1学期</option>
                <option value="2学期">2学期</option>
                <option value="3学期">3学期</option>
            </select>
        </div>

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

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <script>
        function goBack() {
            window.history.back();
        }
    </script>
</body>
</html>
