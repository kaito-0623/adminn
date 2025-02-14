<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>メニュー画面</title>
</head>
<body>
    <h1>メニュー画面</h1>
    <nav>
        <ul>
            <li><a href="{{ route('students.create') }}">学生登録</a></li>
            <li><a href="{{ route('students.index') }}">学生表示</a></li>
            <li><a href="{{ route('schoolGrades.update-student-grades') }}">学年更新</a></li> <!-- ここを修正 -->
        </ul>
    </nav>
</body>
</html>
