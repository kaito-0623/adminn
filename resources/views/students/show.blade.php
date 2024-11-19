<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>学生詳細表示画面</title>
</head>
<body>
    <h1>学生詳細表示画面</h1>
    <p>Name: {{ $student->name }}</p>
    <p>Email: {{ $student->email }}</p>
    <p>Grade: {{ $student->grade }}</p>
    <nav>
        <ul>
            <li><a href="{{ route('students.edit', $student->id) }}">学生編集</a></li>
            <li><a href="{{ route('grades.create', ['student_id' => $student->id]) }}">成績追加</a></li>
            <li><a href="{{ route('students.index') }}">戻る</a></li>
        </ul>
    </nav>
</body>
</html>
